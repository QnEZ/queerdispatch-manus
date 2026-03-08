<?php
/**
 * QueerDispatch Theme — GitHub Releases Auto-Updater
 *
 * Hooks into WordPress's built-in theme update system and checks
 * the GitHub Releases API for new versions. When a newer release is
 * found, WordPress will show the standard "update available" notice
 * in Appearance → Themes and allow one-click updating.
 *
 * Usage: instantiated once in functions.php — no configuration needed.
 *
 * @package QueerDispatch
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class QueerDispatch_GitHub_Updater {

    /**
     * GitHub repository owner (username or org).
     * @var string
     */
    private $github_user = 'QnEZ';

    /**
     * GitHub repository name.
     * @var string
     */
    private $github_repo = 'queerdispatch-manus';

    /**
     * WordPress theme slug (directory name).
     * @var string
     */
    private $theme_slug = 'queerdispatch-theme';

    /**
     * Transient key for caching the GitHub API response.
     * @var string
     */
    private $transient_key = 'queerdispatch_github_release';

    /**
     * How long to cache the GitHub API response (in seconds).
     * Default: 12 hours.
     * @var int
     */
    private $cache_ttl = 43200;

    /**
     * Constructor — registers all WordPress hooks.
     */
    public function __construct() {
        add_filter( 'pre_set_site_transient_update_themes',  array( $this, 'check_for_update' ) );
        add_filter( 'themes_api',                            array( $this, 'theme_info' ), 10, 3 );
        add_filter( 'upgrader_source_selection',             array( $this, 'fix_source_dir' ), 10, 4 );
        add_action( 'upgrader_process_complete',             array( $this, 'clear_cache' ), 10, 2 );
        add_action( 'admin_notices',                         array( $this, 'maybe_show_update_notice' ) );
        add_action( 'wp_ajax_queerdispatch_dismiss_update',  array( $this, 'dismiss_update_notice' ) );
    }

    // ----------------------------------------------------------------
    // GITHUB API
    // ----------------------------------------------------------------

    /**
     * Fetch the latest release data from the GitHub Releases API.
     * Results are cached in a transient to avoid hammering the API.
     *
     * @return object|false Release data object, or false on failure.
     */
    private function get_latest_release() {
        $cached = get_transient( $this->transient_key );
        if ( $cached !== false ) {
            return $cached;
        }

        $api_url  = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
        $response = wp_remote_get( $api_url, array(
            'timeout'    => 15,
            'user-agent' => 'QueerDispatch-Theme-Updater/' . wp_get_theme( $this->theme_slug )->get( 'Version' ),
            'headers'    => array(
                'Accept' => 'application/vnd.github.v3+json',
            ),
        ) );

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            // Cache a short negative result so we don't spam on errors
            set_transient( $this->transient_key, false, 300 );
            return false;
        }

        $body    = wp_remote_retrieve_body( $response );
        $release = json_decode( $body );

        if ( empty( $release ) || empty( $release->tag_name ) ) {
            set_transient( $this->transient_key, false, 300 );
            return false;
        }

        set_transient( $this->transient_key, $release, $this->cache_ttl );
        return $release;
    }

    /**
     * Parse a version string from a GitHub tag (strips leading 'v').
     *
     * @param  string $tag  e.g. "v1.2.0" or "1.2.0"
     * @return string       e.g. "1.2.0"
     */
    private function parse_version( $tag ) {
        return ltrim( $tag, 'v' );
    }

    /**
     * Find the .zip asset URL in a release's assets array.
     * Falls back to the auto-generated zipball_url if no explicit asset found.
     *
     * @param  object $release  GitHub release object.
     * @return string           Download URL for the theme zip.
     */
    private function get_zip_url( $release ) {
        if ( ! empty( $release->assets ) ) {
            foreach ( $release->assets as $asset ) {
                if ( substr( $asset->name, -4 ) === '.zip' ) {
                    return $asset->browser_download_url;
                }
            }
        }
        return $release->zipball_url;
    }

    // ----------------------------------------------------------------
    // WORDPRESS UPDATE HOOKS
    // ----------------------------------------------------------------

    /**
     * Hook into WordPress's theme update transient.
     * If a newer version exists on GitHub, inject it so WordPress shows
     * the standard update notice and enables one-click updating.
     *
     * @param  object $transient  WordPress update_themes transient.
     * @return object             Modified transient.
     */
    public function check_for_update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $release         = $this->get_latest_release();
        $current_version = wp_get_theme( $this->theme_slug )->get( 'Version' );

        if ( ! $release ) {
            return $transient;
        }

        $latest_version = $this->parse_version( $release->tag_name );

        if ( version_compare( $latest_version, $current_version, '>' ) ) {
            $transient->response[ $this->theme_slug ] = array(
                'theme'       => $this->theme_slug,
                'new_version' => $latest_version,
                'url'         => "https://github.com/{$this->github_user}/{$this->github_repo}",
                'package'     => $this->get_zip_url( $release ),
                'requires'    => '5.8',
                'requires_php'=> '7.4',
            );
        }

        return $transient;
    }

    /**
     * Provide theme information for the "View version X.X details" popup
     * that appears when an admin clicks the update link.
     *
     * @param  false|object $result  Default result.
     * @param  string       $action  API action requested.
     * @param  object       $args    Request arguments.
     * @return false|object          Theme info object or original false.
     */
    public function theme_info( $result, $action, $args ) {
        if ( $action !== 'theme_information' ) {
            return $result;
        }
        if ( ! isset( $args->slug ) || $args->slug !== $this->theme_slug ) {
            return $result;
        }

        $release = $this->get_latest_release();
        if ( ! $release ) {
            return $result;
        }

        $latest_version = $this->parse_version( $release->tag_name );
        $changelog      = ! empty( $release->body ) ? $this->markdown_to_html( $release->body ) : '';

        $info = (object) array(
            'name'          => 'QueerDispatch Theme',
            'slug'          => $this->theme_slug,
            'version'       => $latest_version,
            'author'        => '<a href="https://github.com/' . esc_attr( $this->github_user ) . '">QueerDispatch</a>',
            'homepage'      => "https://github.com/{$this->github_user}/{$this->github_repo}",
            'requires'      => '5.8',
            'requires_php'  => '7.4',
            'last_updated'  => $release->published_at,
            'download_link' => $this->get_zip_url( $release ),
            'sections'      => array(
                'description' => '<p>An independent LGBTQIA2S+ news &amp; community WordPress theme with 7 switchable aesthetic styles: Anarchist, Goth, Witchy, Pastel Rainbow Goth, Cyberpunk Queer, Cottagecore Queer, and Riot Grrrl.</p>',
                'changelog'   => $changelog ?: '<p>See <a href="' . esc_url( $release->html_url ) . '">GitHub release notes</a> for details.</p>',
            ),
        );

        return $info;
    }

    /**
     * Rename the extracted source directory to the correct theme slug.
     *
     * When WordPress extracts a ZIP whose top-level folder doesn't exactly
     * match the installed theme's directory name, it installs a *new* theme
     * instead of overwriting the existing one. This filter renames the
     * extracted folder to $theme_slug before WordPress moves it into place,
     * ensuring the update overwrites the correct directory.
     *
     * @param  string      $source        Extracted source path.
     * @param  string      $remote_source Temp directory containing the ZIP.
     * @param  WP_Upgrader $upgrader      Upgrader instance.
     * @param  array       $hook_extra    Extra hook data.
     * @return string|WP_Error           Corrected source path, or WP_Error.
     */
    public function fix_source_dir( $source, $remote_source, $upgrader, $hook_extra ) {
        global $wp_filesystem;

        // Normalise paths — ensure both have a trailing slash.
        $source         = trailingslashit( $source );
        $remote_source  = trailingslashit( $remote_source );
        $correct_source = $remote_source . $this->theme_slug . '/';

        // Guard 1: Only act when this is a theme upgrade.
        // hook_extra['theme'] is set by Theme_Upgrader; fall back to
        // checking whether the source path lives inside remote_source
        // and the extracted folder name looks like our theme.
        $is_our_theme = false;

        if ( isset( $hook_extra['theme'] ) && $hook_extra['theme'] === $this->theme_slug ) {
            $is_our_theme = true;
        } elseif ( isset( $hook_extra['type'] ) && $hook_extra['type'] === 'theme' ) {
            // WordPress 5.5+ sets hook_extra['type'] = 'theme' for all theme upgrades.
            // Check whether the source directory is inside our remote_source temp dir.
            if ( strpos( $source, $remote_source ) === 0 ) {
                $is_our_theme = true;
            }
        } elseif ( strpos( $source, $remote_source ) === 0 ) {
            // Fallback: if the source is inside the remote_source temp dir,
            // check if it contains a style.css that identifies our theme.
            $style_css = $source . 'style.css';
            if ( $wp_filesystem->exists( $style_css ) ) {
                $contents = $wp_filesystem->get_contents( $style_css );
                if ( $contents && strpos( $contents, 'Text Domain: queerdispatch' ) !== false ) {
                    $is_our_theme = true;
                }
            }
        }

        if ( ! $is_our_theme ) {
            return $source;
        }

        // Guard 2: If the extracted folder is already named correctly, do nothing.
        if ( untrailingslashit( $source ) === untrailingslashit( $correct_source ) ) {
            return $source;
        }

        // Guard 3: If the correct target already exists (e.g. a previous failed
        // attempt left it behind), remove it first so the move succeeds.
        if ( $wp_filesystem->is_dir( $correct_source ) ) {
            $wp_filesystem->delete( $correct_source, true );
        }

        if ( ! $wp_filesystem->move( $source, $correct_source ) ) {
            return new WP_Error(
                'queerdispatch_rename_failed',
                sprintf(
                    /* translators: 1: source path, 2: target path */
                    __( 'Could not rename %1$s to %2$s during theme update.', 'queerdispatch' ),
                    $source,
                    $correct_source
                )
            );
        }

        return $correct_source;
    }

    /**
     * Clear the cached release data after a theme update completes.
     *
     * @param WP_Upgrader $upgrader  Upgrader instance.
     * @param array       $options   Upgrade options.
     */
    public function clear_cache( $upgrader, $options ) {
        if (
            isset( $options['type'], $options['themes'] ) &&
            $options['type'] === 'theme' &&
            in_array( $this->theme_slug, (array) $options['themes'], true )
        ) {
            delete_transient( $this->transient_key );
        }
    }

    // ----------------------------------------------------------------
    // ADMIN NOTICE (optional in-dashboard banner)
    // ----------------------------------------------------------------

    /**
     * Show a dismissible admin notice when a new version is available.
     * Only shown to users who can manage themes, and only on relevant pages.
     */
    public function maybe_show_update_notice() {
        if ( ! current_user_can( 'update_themes' ) ) {
            return;
        }

        // Only show on dashboard, themes, and plugins pages
        $screen = get_current_screen();
        if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'update-core' ), true ) ) {
            return;
        }

        $release         = $this->get_latest_release();
        $current_version = wp_get_theme( $this->theme_slug )->get( 'Version' );

        if ( ! $release ) {
            return;
        }

        $latest_version = $this->parse_version( $release->tag_name );

        if ( ! version_compare( $latest_version, $current_version, '>' ) ) {
            return;
        }

        // Check if dismissed for this version
        $dismissed = get_user_meta( get_current_user_id(), 'queerdispatch_dismissed_update', true );
        if ( $dismissed === $latest_version ) {
            return;
        }

        $update_url   = wp_nonce_url(
            admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( $this->theme_slug ) ),
            'upgrade-theme_' . $this->theme_slug
        );
        $release_url  = esc_url( $release->html_url );
        $dismiss_url  = wp_nonce_url(
            admin_url( 'admin-ajax.php?action=queerdispatch_dismiss_update&version=' . urlencode( $latest_version ) ),
            'queerdispatch_dismiss_update'
        );

        ?>
        <div class="notice notice-info is-dismissible queerdispatch-update-notice" style="border-left-color: #cc0000;">
            <p>
                <strong>🏳️‍🌈 QueerDispatch Theme <?php echo esc_html( $latest_version ); ?> is available!</strong>
                You are running version <?php echo esc_html( $current_version ); ?>.
                <a href="<?php echo esc_url( $update_url ); ?>" class="button button-primary" style="margin-left:8px;">Update Now</a>
                <a href="<?php echo $release_url; ?>" target="_blank" rel="noopener" style="margin-left:8px;">View Release Notes</a>
                <a href="<?php echo esc_url( $dismiss_url ); ?>" style="margin-left:8px; color:#999; font-size:12px;">Dismiss</a>
            </p>
        </div>
        <?php
    }

    /**
     * AJAX handler to dismiss the update notice for a specific version.
     */
    public function dismiss_update_notice() {
        check_ajax_referer( 'queerdispatch_dismiss_update' );

        if ( ! current_user_can( 'update_themes' ) ) {
            wp_die( 'Unauthorized', 403 );
        }

        $version = isset( $_GET['version'] ) ? sanitize_text_field( $_GET['version'] ) : '';
        if ( $version ) {
            update_user_meta( get_current_user_id(), 'queerdispatch_dismissed_update', $version );
        }

        wp_safe_redirect( wp_get_referer() ?: admin_url() );
        exit;
    }

    // ----------------------------------------------------------------
    // HELPERS
    // ----------------------------------------------------------------

    /**
     * Very basic Markdown-to-HTML converter for release notes.
     * Handles headings, bold, code, and list items.
     *
     * @param  string $markdown  Raw Markdown text.
     * @return string            HTML string.
     */
    private function markdown_to_html( $markdown ) {
        $html = esc_html( $markdown );

        // Headings
        $html = preg_replace( '/^### (.+)$/m',  '<h4>$1</h4>', $html );
        $html = preg_replace( '/^## (.+)$/m',   '<h3>$1</h3>', $html );
        $html = preg_replace( '/^# (.+)$/m',    '<h2>$1</h2>', $html );

        // Bold
        $html = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html );

        // Inline code
        $html = preg_replace( '/`(.+?)`/', '<code>$1</code>', $html );

        // List items
        $html = preg_replace( '/^[-*] (.+)$/m', '<li>$1</li>', $html );
        $html = preg_replace( '/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html );

        // Line breaks
        $html = nl2br( $html );

        return $html;
    }
}
