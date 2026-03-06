<?php
/**
 * QueerDispatch Theme — Admin Version & Release History Page
 *
 * Adds an "About / Updates" page under Appearance in the WordPress admin.
 * Shows the current installed version, the latest GitHub release, full
 * release history with changelogs, and a one-click update button.
 *
 * @package QueerDispatch
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the admin menu page under Appearance.
 */
function queerdispatch_register_version_page() {
    add_theme_page(
        esc_html__( 'QueerDispatch Theme — Version & Updates', 'queerdispatch' ),
        esc_html__( '🏳️‍🌈 Theme Updates', 'queerdispatch' ),
        'manage_options',
        'queerdispatch-version',
        'queerdispatch_render_version_page'
    );
}
add_action( 'admin_menu', 'queerdispatch_register_version_page' );

/**
 * Enqueue admin styles for the version page.
 */
function queerdispatch_version_page_styles( $hook ) {
    if ( $hook !== 'appearance_page_queerdispatch-version' ) {
        return;
    }
    wp_add_inline_style( 'wp-admin', '
        .qd-version-wrap { max-width: 900px; margin: 24px auto; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .qd-header { display: flex; align-items: center; gap: 16px; background: #0d0d0d; color: #fff; padding: 24px 28px; border-radius: 8px; margin-bottom: 24px; }
        .qd-header h1 { margin: 0; font-size: 22px; color: #fff; }
        .qd-header .qd-badge { background: linear-gradient(135deg, #cc0000, #4a0e6b); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .qd-header .qd-up-to-date { background: #2ea44f; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 13px; }
        .qd-header .qd-update-available { background: #e3a008; color: #0d0d0d; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .qd-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
        .qd-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; }
        .qd-card h3 { margin: 0 0 12px; font-size: 14px; text-transform: uppercase; letter-spacing: .05em; color: #666; }
        .qd-card .qd-value { font-size: 28px; font-weight: 700; color: #0d0d0d; }
        .qd-card .qd-sub { font-size: 12px; color: #888; margin-top: 4px; }
        .qd-update-btn { display: inline-block; background: #cc0000; color: #fff !important; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 12px; }
        .qd-update-btn:hover { background: #a00000; }
        .qd-releases h2 { font-size: 18px; border-bottom: 2px solid #0d0d0d; padding-bottom: 8px; margin-bottom: 20px; }
        .qd-release { border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 16px; overflow: hidden; }
        .qd-release-header { display: flex; align-items: center; gap: 12px; background: #f8f8f8; padding: 14px 18px; cursor: pointer; }
        .qd-release-header:hover { background: #f0f0f0; }
        .qd-release-tag { font-weight: 700; font-size: 15px; font-family: monospace; color: #0d0d0d; }
        .qd-release-date { color: #888; font-size: 13px; margin-left: auto; }
        .qd-release-latest { background: #2ea44f; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .qd-release-installed { background: #0073aa; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .qd-release-body { padding: 16px 18px; border-top: 1px solid #e0e0e0; display: none; }
        .qd-release-body.open { display: block; }
        .qd-release-body h3, .qd-release-body h4 { margin: 12px 0 6px; }
        .qd-release-body ul { margin: 6px 0 12px 20px; }
        .qd-release-body code { background: #f4f4f4; padding: 1px 5px; border-radius: 3px; font-size: 12px; }
        .qd-release-footer { padding: 10px 18px; background: #fafafa; border-top: 1px solid #e0e0e0; display: flex; gap: 12px; }
        .qd-release-footer a { font-size: 13px; color: #0073aa; text-decoration: none; }
        .qd-release-footer a:hover { text-decoration: underline; }
        .qd-error { background: #fff3cd; border: 1px solid #ffc107; padding: 16px; border-radius: 6px; color: #856404; }
        .qd-loading { color: #888; font-style: italic; }
        @media (max-width: 700px) { .qd-cards { grid-template-columns: 1fr; } }
    ' );
    // Inline JS for accordion toggle
    wp_add_inline_script( 'jquery', '
        jQuery(function($){
            $(".qd-release-header").on("click", function(){
                $(this).next(".qd-release-body").toggleClass("open");
            });
            // Auto-open the latest release
            $(".qd-release-header").first().next(".qd-release-body").addClass("open");
        });
    ' );
}
add_action( 'admin_enqueue_scripts', 'queerdispatch_version_page_styles' );

/**
 * Fetch all releases from GitHub API (cached).
 *
 * @return array|false  Array of release objects, or false on failure.
 */
function queerdispatch_get_all_releases() {
    $cached = get_transient( 'queerdispatch_all_releases' );
    if ( $cached !== false ) {
        return $cached;
    }

    $response = wp_remote_get(
        'https://api.github.com/repos/QnEZ/queerdispatch-manus/releases?per_page=30',
        array(
            'timeout'    => 15,
            'user-agent' => 'QueerDispatch-Theme-Admin/' . wp_get_theme()->get( 'Version' ),
            'headers'    => array( 'Accept' => 'application/vnd.github.v3+json' ),
        )
    );

    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
        return false;
    }

    $releases = json_decode( wp_remote_retrieve_body( $response ) );
    if ( ! is_array( $releases ) ) {
        return false;
    }

    set_transient( 'queerdispatch_all_releases', $releases, 3600 ); // 1 hour cache
    return $releases;
}

/**
 * Render the version & updates admin page.
 */
function queerdispatch_render_version_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to view this page.', 'queerdispatch' ) );
    }

    $theme           = wp_get_theme();
    $current_version = $theme->get( 'Version' );
    $releases        = queerdispatch_get_all_releases();
    $latest_release  = is_array( $releases ) && ! empty( $releases ) ? $releases[0] : null;
    $latest_version  = $latest_release ? ltrim( $latest_release->tag_name, 'v' ) : null;
    $has_update      = $latest_version && version_compare( $latest_version, $current_version, '>' );

    $update_url = wp_nonce_url(
        admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( 'queerdispatch-theme' ) ),
        'upgrade-theme_queerdispatch-theme'
    );

    ?>
    <div class="qd-version-wrap">

        <!-- Header -->
        <div class="qd-header">
            <span style="font-size:32px;">🏳️‍🌈</span>
            <div>
                <h1>QueerDispatch Theme</h1>
                <div style="font-size:13px; color:#aaa; margin-top:4px;">Independent LGBTQIA2S+ News &amp; Community</div>
            </div>
            <div style="margin-left:auto; text-align:right;">
                <?php if ( $has_update ) : ?>
                    <span class="qd-update-available">⬆ Update Available: v<?php echo esc_html( $latest_version ); ?></span>
                <?php elseif ( $latest_version ) : ?>
                    <span class="qd-up-to-date">✓ Up to date</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="qd-cards">
            <div class="qd-card">
                <h3><?php esc_html_e( 'Installed Version', 'queerdispatch' ); ?></h3>
                <div class="qd-value">v<?php echo esc_html( $current_version ); ?></div>
                <div class="qd-sub"><?php echo esc_html( $theme->get( 'Name' ) ); ?></div>
            </div>
            <div class="qd-card">
                <h3><?php esc_html_e( 'Latest Release', 'queerdispatch' ); ?></h3>
                <?php if ( $latest_version ) : ?>
                    <div class="qd-value">v<?php echo esc_html( $latest_version ); ?></div>
                    <div class="qd-sub">
                        <?php
                        if ( $latest_release && ! empty( $latest_release->published_at ) ) {
                            echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $latest_release->published_at ) ) );
                        }
                        ?>
                    </div>
                    <?php if ( $has_update ) : ?>
                        <a href="<?php echo esc_url( $update_url ); ?>" class="qd-update-btn">
                            ⬆ <?php esc_html_e( 'Update Now', 'queerdispatch' ); ?>
                        </a>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="qd-value" style="font-size:16px; color:#888;"><?php esc_html_e( 'Could not fetch', 'queerdispatch' ); ?></div>
                    <div class="qd-sub"><?php esc_html_e( 'Check your internet connection', 'queerdispatch' ); ?></div>
                <?php endif; ?>
            </div>
            <div class="qd-card">
                <h3><?php esc_html_e( 'Repository', 'queerdispatch' ); ?></h3>
                <div style="font-size:14px; margin-top:4px;">
                    <a href="https://github.com/QnEZ/queerdispatch-manus" target="_blank" rel="noopener" style="color:#0073aa; font-weight:600;">
                        github.com/QnEZ/queerdispatch-manus
                    </a>
                </div>
                <div class="qd-sub" style="margin-top:8px;">
                    <a href="https://github.com/QnEZ/queerdispatch-manus/releases" target="_blank" rel="noopener">
                        <?php esc_html_e( 'All releases →', 'queerdispatch' ); ?>
                    </a>
                </div>
            </div>
            <div class="qd-card">
                <h3><?php esc_html_e( 'Total Releases', 'queerdispatch' ); ?></h3>
                <div class="qd-value"><?php echo is_array( $releases ) ? count( $releases ) : '—'; ?></div>
                <div class="qd-sub">
                    <a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=queerdispatch_clear_release_cache&_wpnonce=' . wp_create_nonce( 'qd_clear_cache' ) ) ); ?>">
                        <?php esc_html_e( 'Refresh cache', 'queerdispatch' ); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Release History -->
        <div class="qd-releases">
            <h2><?php esc_html_e( 'Release History', 'queerdispatch' ); ?></h2>

            <?php if ( ! is_array( $releases ) || empty( $releases ) ) : ?>
                <div class="qd-error">
                    <?php esc_html_e( 'Could not load release history. Please check your internet connection or visit the GitHub repository directly.', 'queerdispatch' ); ?>
                    <br><a href="https://github.com/QnEZ/queerdispatch-manus/releases" target="_blank" rel="noopener">github.com/QnEZ/queerdispatch-manus/releases</a>
                </div>
            <?php else : ?>
                <?php foreach ( $releases as $i => $release ) :
                    $ver         = ltrim( $release->tag_name, 'v' );
                    $is_latest   = ( $i === 0 );
                    $is_current  = ( $ver === $current_version );
                    $pub_date    = ! empty( $release->published_at )
                        ? date_i18n( get_option( 'date_format' ), strtotime( $release->published_at ) )
                        : '';

                    // Find zip asset
                    $zip_url = $release->zipball_url;
                    if ( ! empty( $release->assets ) ) {
                        foreach ( $release->assets as $asset ) {
                            if ( substr( $asset->name, -4 ) === '.zip' ) {
                                $zip_url = $asset->browser_download_url;
                                break;
                            }
                        }
                    }

                    // Convert release body markdown to basic HTML
                    $body_html = '';
                    if ( ! empty( $release->body ) ) {
                        $body_html = esc_html( $release->body );
                        $body_html = preg_replace( '/^### (.+)$/m',  '<h4>$1</h4>', $body_html );
                        $body_html = preg_replace( '/^## (.+)$/m',   '<h3>$1</h3>', $body_html );
                        $body_html = preg_replace( '/^# (.+)$/m',    '<h2>$1</h2>', $body_html );
                        $body_html = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $body_html );
                        $body_html = preg_replace( '/`(.+?)`/', '<code>$1</code>', $body_html );
                        $body_html = preg_replace( '/^[-*] (.+)$/m', '<li>$1</li>', $body_html );
                        $body_html = nl2br( $body_html );
                    }
                ?>
                <div class="qd-release">
                    <div class="qd-release-header">
                        <span class="qd-release-tag">v<?php echo esc_html( $ver ); ?></span>
                        <?php if ( $is_latest ) : ?>
                            <span class="qd-release-latest"><?php esc_html_e( 'Latest', 'queerdispatch' ); ?></span>
                        <?php endif; ?>
                        <?php if ( $is_current ) : ?>
                            <span class="qd-release-installed"><?php esc_html_e( 'Installed', 'queerdispatch' ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $release->name ) && $release->name !== $release->tag_name ) : ?>
                            <span style="color:#555; font-size:14px;"><?php echo esc_html( $release->name ); ?></span>
                        <?php endif; ?>
                        <span class="qd-release-date"><?php echo esc_html( $pub_date ); ?></span>
                        <span style="color:#aaa; font-size:18px; margin-left:8px;">▾</span>
                    </div>
                    <div class="qd-release-body">
                        <?php if ( $body_html ) : ?>
                            <?php echo wp_kses_post( $body_html ); ?>
                        <?php else : ?>
                            <p style="color:#888;"><?php esc_html_e( 'No release notes provided.', 'queerdispatch' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="qd-release-footer">
                        <a href="<?php echo esc_url( $release->html_url ); ?>" target="_blank" rel="noopener">
                            <?php esc_html_e( 'View on GitHub', 'queerdispatch' ); ?> ↗
                        </a>
                        <a href="<?php echo esc_url( $zip_url ); ?>">
                            ⬇ <?php esc_html_e( 'Download .zip', 'queerdispatch' ); ?>
                        </a>
                        <?php if ( $has_update && $is_latest ) : ?>
                            <a href="<?php echo esc_url( $update_url ); ?>" style="color:#cc0000; font-weight:600;">
                                ⬆ <?php esc_html_e( 'Install this update', 'queerdispatch' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
    <?php
}

/**
 * AJAX handler to manually clear the release cache.
 */
function queerdispatch_clear_release_cache_ajax() {
    check_admin_referer( 'qd_clear_cache' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized', 403 );
    }
    delete_transient( 'queerdispatch_all_releases' );
    delete_transient( 'queerdispatch_github_release' );
    wp_safe_redirect( admin_url( 'themes.php?page=queerdispatch-version&cache_cleared=1' ) );
    exit;
}
add_action( 'wp_ajax_queerdispatch_clear_release_cache', 'queerdispatch_clear_release_cache_ajax' );
