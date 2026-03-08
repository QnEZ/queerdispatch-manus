<?php
/**
 * QueerDispatch Theme Functions
 *
 * @package QueerDispatch
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ============================================================
// THEME SETUP
// ============================================================

function queerdispatch_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'queerdispatch', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 800, 500, true );
    add_image_size( 'queerdispatch-hero', 1200, 700, true );
    add_image_size( 'queerdispatch-card', 600, 380, true );
    add_image_size( 'queerdispatch-list', 280, 200, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'queerdispatch' ),
        'footer'    => esc_html__( 'Footer Menu', 'queerdispatch' ),
        'social'    => esc_html__( 'Social Links Menu', 'queerdispatch' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Add support for wide and full alignment in block editor
    add_theme_support( 'align-wide' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
add_action( 'after_setup_theme', 'queerdispatch_setup' );

// ============================================================
// CONTENT WIDTH
// ============================================================

function queerdispatch_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'queerdispatch_content_width', 800 );
}
add_action( 'after_setup_theme', 'queerdispatch_content_width', 0 );

// ============================================================
// ENQUEUE SCRIPTS AND STYLES
// ============================================================

function queerdispatch_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );

    // Google Fonts
    wp_enqueue_style(
        'queerdispatch-google-fonts',
        'https://fonts.googleapis.com/css2?family=Anton&family=Cinzel:wght@400;700;900&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=UnifrakturMaguntia&family=Josefin+Sans:wght@300;400;700&family=Special+Elite&family=Raleway:wght@300;400;700&family=Orbitron:wght@400;700;900&family=Quicksand:wght@300;400;700&family=Abril+Fatface&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Share+Tech&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'queerdispatch-style',
        get_stylesheet_uri(),
        array( 'queerdispatch-google-fonts' ),
        $theme_version
    );

    // Style switcher JS
    wp_enqueue_script(
        'queerdispatch-style-switcher',
        get_template_directory_uri() . '/js/style-switcher.js',
        array(),
        $theme_version,
        true
    );

    // Main navigation JS
    wp_enqueue_script(
        'queerdispatch-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        $theme_version,
        true
    );

    // Pass data to JS
    wp_localize_script( 'queerdispatch-style-switcher', 'queerdispatchData', array(
        'themeUrl'   => get_template_directory_uri(),
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'queerdispatch_style_nonce' ),
        'savedStyle' => queerdispatch_get_current_style(),
    ) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'queerdispatch_scripts' );

// ============================================================
// WIDGET AREAS
// ============================================================

function queerdispatch_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'queerdispatch' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in the sidebar.', 'queerdispatch' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'queerdispatch' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer column 1.', 'queerdispatch' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'queerdispatch' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer column 2.', 'queerdispatch' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'queerdispatch_widgets_init' );

// ============================================================
// STYLE SWITCHER — COOKIE & AJAX
// ============================================================

/**
 * Available theme styles
 */
function queerdispatch_get_styles() {
    return array(
        'anarchist'          => array(
            'name'   => 'Anarchist',
            'desc'   => 'Black & red, punk zine',
            'emoji'  => '✊',
            'colors' => array( '#cc0000', '#0d0d0d' ),
        ),
        'goth'               => array(
            'name'   => 'Goth',
            'desc'   => 'Dark academia, ornate',
            'emoji'  => '🦇',
            'colors' => array( '#4a0e6b', '#0a0a0a' ),
        ),
        'witchy'             => array(
            'name'   => 'Witchy',
            'desc'   => 'Mystical, tarot vibes',
            'emoji'  => '🔮',
            'colors' => array( '#6b2fa0', '#1a0a2e' ),
        ),
        'pastel-rainbow-goth' => array(
            'name'   => 'Pastel Rainbow Goth',
            'desc'   => 'Kawaii-goth hybrid',
            'emoji'  => '🌈',
            'colors' => array( '#ff9de2', '#1a0a2e' ),
        ),
        'cyberpunk'          => array(
            'name'   => 'Cyberpunk Queer',
            'desc'   => 'Neon glitch, tech noir',
            'emoji'  => '⚡',
            'colors' => array( '#ff00ff', '#050510' ),
        ),
        'cottagecore'        => array(
            'name'   => 'Cottagecore Queer',
            'desc'   => 'Cozy nature, floral',
            'emoji'  => '🌿',
            'colors' => array( '#5a7a3a', '#f5f0e8' ),
        ),
        'riot-grrrl'         => array(
            'name'   => 'Riot Grrrl',
            'desc'   => 'Hot pink, feminist punk',
            'emoji'  => '🎸',
            'colors' => array( '#ff0080', '#0d0d0d' ),
        ),
    );
}

/**
 * Get the current style from cookie, falling back to the Customizer default.
 *
 * Priority order:
 *   1. Visitor's saved cookie (their personal preference)
 *   2. Admin-chosen default in Appearance → Customize → Theme Style
 *   3. Hard fallback of 'anarchist' if nothing is set anywhere
 */
function queerdispatch_get_current_style() {
    $styles  = queerdispatch_get_styles();

    // 1. Visitor cookie takes highest priority
    if ( isset( $_COOKIE['queerdispatch_style'] ) ) {
        $style = sanitize_key( $_COOKIE['queerdispatch_style'] );
        if ( array_key_exists( $style, $styles ) ) {
            return $style;
        }
    }

    // 2. Admin-set Customizer default
    $customizer_default = get_theme_mod( 'queerdispatch_default_style', 'anarchist' );
    if ( array_key_exists( $customizer_default, $styles ) ) {
        return $customizer_default;
    }

    // 3. Hard fallback
    return 'anarchist';
}

/**
 * AJAX handler for saving style preference
 */
function queerdispatch_save_style() {
    check_ajax_referer( 'queerdispatch_style_nonce', 'nonce' );

    $styles = queerdispatch_get_styles();
    $style  = isset( $_POST['style'] ) ? sanitize_key( $_POST['style'] ) : 'anarchist';

    if ( ! array_key_exists( $style, $styles ) ) {
        wp_send_json_error( 'Invalid style' );
    }

    // Set cookie for 1 year
    setcookie( 'queerdispatch_style', $style, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

    wp_send_json_success( array( 'style' => $style ) );
}
add_action( 'wp_ajax_queerdispatch_save_style', 'queerdispatch_save_style' );
add_action( 'wp_ajax_nopriv_queerdispatch_save_style', 'queerdispatch_save_style' );

/**
 * Enqueue ALL theme CSS files simultaneously via wp_enqueue_style.
 *
 * WHY: Each theme file scopes its rules to body[data-style="X"] { ... }
 * which has higher specificity than :root. So all files can be loaded at
 * once — only the one matching the current data-style attribute applies.
 * Using wp_enqueue_style (instead of manual echo) ensures WordPress
 * correctly resolves the theme directory URI in all environments.
 */
function queerdispatch_enqueue_theme_styles() {
    $theme_version = wp_get_theme()->get( 'Version' );
    $theme_uri     = get_template_directory_uri();
    $styles        = queerdispatch_get_styles();

    foreach ( array_keys( $styles ) as $style_id ) {
        wp_enqueue_style(
            'queerdispatch-theme-' . $style_id,
            $theme_uri . '/css/themes/' . $style_id . '.css',
            array( 'queerdispatch-style' ),
            $theme_version
        );
    }
}
add_action( 'wp_enqueue_scripts', 'queerdispatch_enqueue_theme_styles' );

/**
 * Output the data-style attribute setter inline script early in <head>.
 * This sets data-style on <html> immediately so CSS selectors match
 * before the page is painted (prevents flash of unstyled content).
 */
function queerdispatch_output_style_init_script() {
    $current_style = queerdispatch_get_current_style();
    echo '<script>(function(s){document.documentElement.setAttribute("data-style",s);document.addEventListener("DOMContentLoaded",function(){if(document.body){document.body.setAttribute("data-style",s);}});})(' . json_encode( $current_style ) . ');</script>' . "\n";
}
add_action( 'wp_head', 'queerdispatch_output_style_init_script', 1 );

// ============================================================
// CUSTOMIZER
// ============================================================

function queerdispatch_customize_register( $wp_customize ) {
    // Default Style Setting
    $wp_customize->add_section( 'queerdispatch_style_section', array(
        'title'    => esc_html__( 'Theme Style', 'queerdispatch' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'queerdispatch_default_style', array(
        'default'           => 'anarchist',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage', // enables live preview without full refresh
    ) );

    $styles        = queerdispatch_get_styles();
    $style_choices = array();
    foreach ( $styles as $key => $style ) {
        $style_choices[ $key ] = $style['emoji'] . ' ' . $style['name'];
    }

    $wp_customize->add_control( 'queerdispatch_default_style', array(
        'label'   => esc_html__( 'Default Theme Style', 'queerdispatch' ),
        'section' => 'queerdispatch_style_section',
        'type'    => 'select',
        'choices' => $style_choices,
    ) );

    // Breaking News
    $wp_customize->add_section( 'queerdispatch_breaking_news', array(
        'title'    => esc_html__( 'Breaking News Bar', 'queerdispatch' ),
        'priority' => 40,
    ) );

    $wp_customize->add_setting( 'queerdispatch_breaking_news_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'queerdispatch_breaking_news_enabled', array(
        'label'   => esc_html__( 'Show Breaking News Bar', 'queerdispatch' ),
        'section' => 'queerdispatch_breaking_news',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'queerdispatch_breaking_news_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'queerdispatch_breaking_news_text', array(
        'label'       => esc_html__( 'Breaking News Text', 'queerdispatch' ),
        'description' => esc_html__( 'Separate multiple items with " | "', 'queerdispatch' ),
        'section'     => 'queerdispatch_breaking_news',
        'type'        => 'textarea',
    ) );
}
add_action( 'customize_register', 'queerdispatch_customize_register' );

/**
 * Enqueue the Customizer live-preview script.
 * This runs inside the preview iframe and handles postMessage updates.
 */
function queerdispatch_customize_preview_js() {
    wp_enqueue_script(
        'queerdispatch-customizer-preview',
        get_template_directory_uri() . '/js/customizer-preview.js',
        array( 'customize-preview', 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'customize_preview_init', 'queerdispatch_customize_preview_js' );

// ============================================================
// TEMPLATE TAGS & HELPERS
// ============================================================

/**
 * Output post categories as tags
 */
function queerdispatch_post_tags( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $categories = get_the_category( $post_id );
    if ( ! empty( $categories ) ) {
        echo '<div class="card-tags">';
        foreach ( array_slice( $categories, 0, 3 ) as $cat ) {
            echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="tag">' . esc_html( $cat->name ) . '</a>';
        }
        echo '</div>';
    }
}

/**
 * Output post meta (author + date)
 */
function queerdispatch_post_meta( $class = 'card-meta' ) {
    echo '<div class="' . esc_attr( $class ) . '">';
    echo '<span class="author">' . esc_html( get_the_author() ) . '</span>';
    echo '<span class="separator">•</span>';
    echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
    echo '</div>';
}

/**
 * Output the style switcher panel HTML
 */
function queerdispatch_style_switcher() {
    $styles        = queerdispatch_get_styles();
    $current_style = queerdispatch_get_current_style();
    ?>
    <div class="style-switcher" id="style-switcher" role="complementary" aria-label="<?php esc_attr_e( 'Theme Style Switcher', 'queerdispatch' ); ?>">
        <button class="style-switcher-toggle" id="style-switcher-toggle" aria-expanded="false" aria-controls="style-switcher-panel">
            <?php esc_html_e( 'Style', 'queerdispatch' ); ?>
        </button>
        <div class="style-switcher-panel" id="style-switcher-panel" role="dialog" aria-label="<?php esc_attr_e( 'Choose a theme style', 'queerdispatch' ); ?>">
            <h3><?php esc_html_e( 'Choose Your Aesthetic', 'queerdispatch' ); ?></h3>
            <?php foreach ( $styles as $key => $style ) : ?>
                <button
                    class="style-option <?php echo $key === $current_style ? 'active' : ''; ?>"
                    data-style="<?php echo esc_attr( $key ); ?>"
                    aria-pressed="<?php echo $key === $current_style ? 'true' : 'false'; ?>"
                    title="<?php echo esc_attr( $style['name'] . ': ' . $style['desc'] ); ?>"
                >
                    <span class="style-swatch" style="background: linear-gradient(135deg, <?php echo esc_attr( $style['colors'][0] ); ?>, <?php echo esc_attr( $style['colors'][1] ); ?>);" aria-hidden="true">
                        <?php echo $style['emoji']; ?>
                    </span>
                    <span>
                        <span class="style-name"><?php echo esc_html( $style['name'] ); ?></span>
                        <span class="style-desc"><?php echo esc_html( $style['desc'] ); ?></span>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Truncate text to a given word count
 */
function queerdispatch_excerpt( $length = 20 ) {
    $excerpt = get_the_excerpt();
    $words   = explode( ' ', $excerpt );
    if ( count( $words ) > $length ) {
        $excerpt = implode( ' ', array_slice( $words, 0, $length ) ) . '&hellip;';
    }
    return $excerpt;
}

// ============================================================
// CUSTOM EXCERPT LENGTH
// ============================================================

function queerdispatch_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'queerdispatch_excerpt_length', 999 );

function queerdispatch_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'queerdispatch_excerpt_more' );

// ============================================================
// BODY CLASSES
// ============================================================

function queerdispatch_body_classes( $classes ) {
    $current_style = queerdispatch_get_current_style();
    $classes[]     = 'style-' . $current_style;

    if ( is_singular() ) {
        $classes[] = 'singular';
    }

    return $classes;
}
add_filter( 'body_class', 'queerdispatch_body_classes' );

// ============================================================
// CUSTOM POST TYPES (optional: Events)
// ============================================================

function queerdispatch_register_post_types() {
    register_post_type( 'qd_event', array(
        'labels' => array(
            'name'               => esc_html__( 'Events', 'queerdispatch' ),
            'singular_name'      => esc_html__( 'Event', 'queerdispatch' ),
            'add_new'            => esc_html__( 'Add New Event', 'queerdispatch' ),
            'add_new_item'       => esc_html__( 'Add New Event', 'queerdispatch' ),
            'edit_item'          => esc_html__( 'Edit Event', 'queerdispatch' ),
            'new_item'           => esc_html__( 'New Event', 'queerdispatch' ),
            'view_item'          => esc_html__( 'View Event', 'queerdispatch' ),
            'search_items'       => esc_html__( 'Search Events', 'queerdispatch' ),
            'not_found'          => esc_html__( 'No events found', 'queerdispatch' ),
            'not_found_in_trash' => esc_html__( 'No events found in trash', 'queerdispatch' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'menu_icon'    => 'dashicons-calendar-alt',
        'rewrite'      => array( 'slug' => 'events' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'queerdispatch_register_post_types' );

// ============================================================
// TIP SUBMISSION SYSTEM
// ============================================================

/**
 * Register the qd_tip custom post type.
 * Tips are stored privately — not publicly accessible on the front end.
 */
function queerdispatch_register_tip_post_type() {
    register_post_type( 'qd_tip', array(
        'labels' => array(
            'name'               => esc_html__( 'Tips', 'queerdispatch' ),
            'singular_name'      => esc_html__( 'Tip', 'queerdispatch' ),
            'add_new'            => esc_html__( 'Add New Tip', 'queerdispatch' ),
            'add_new_item'       => esc_html__( 'Add New Tip', 'queerdispatch' ),
            'edit_item'          => esc_html__( 'Edit Tip', 'queerdispatch' ),
            'new_item'           => esc_html__( 'New Tip', 'queerdispatch' ),
            'view_item'          => esc_html__( 'View Tip', 'queerdispatch' ),
            'search_items'       => esc_html__( 'Search Tips', 'queerdispatch' ),
            'not_found'          => esc_html__( 'No tips found', 'queerdispatch' ),
            'not_found_in_trash' => esc_html__( 'No tips found in trash', 'queerdispatch' ),
            'menu_name'          => esc_html__( 'Tips', 'queerdispatch' ),
            'all_items'          => esc_html__( 'All Tips', 'queerdispatch' ),
        ),
        // Private — tips are not publicly queryable
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        'show_in_rest'        => false,
        'query_var'           => false,
        'has_archive'         => false,
        'supports'            => array( 'title', 'editor', 'custom-fields' ),
        'menu_icon'           => 'dashicons-megaphone',
        'capability_type'     => 'post',
        'capabilities'        => array(
            'create_posts' => 'do_not_allow', // Only created via form handler
        ),
        'map_meta_cap'        => true,
    ) );
}
add_action( 'init', 'queerdispatch_register_tip_post_type' );

/**
 * Register custom meta fields for qd_tip.
 */
function queerdispatch_register_tip_meta() {
    $fields = array(
        '_tip_contact_email' => array(
            'description' => 'Submitter contact email (optional)',
            'sanitize'    => 'sanitize_email',
        ),
        '_tip_contact_name' => array(
            'description' => 'Submitter name or alias (optional)',
            'sanitize'    => 'sanitize_text_field',
        ),
        '_tip_category' => array(
            'description' => 'Tip category selected by submitter',
            'sanitize'    => 'sanitize_text_field',
        ),
        '_tip_attachment_path' => array(
            'description' => 'Server path to uploaded attachment (if any)',
            'sanitize'    => 'sanitize_text_field',
        ),
        '_tip_attachment_name' => array(
            'description' => 'Original filename of uploaded attachment (if any)',
            'sanitize'    => 'sanitize_text_field',
        ),
        '_tip_ip' => array(
            'description' => 'Submitter IP address',
            'sanitize'    => 'sanitize_text_field',
        ),
        '_tip_status' => array(
            'description' => 'Editorial status: new, reviewing, actioned, dismissed',
            'sanitize'    => 'sanitize_text_field',
        ),
    );

    foreach ( $fields as $key => $args ) {
        register_post_meta( 'qd_tip', $key, array(
            'type'              => 'string',
            'description'       => $args['description'],
            'single'            => true,
            'sanitize_callback' => $args['sanitize'],
            'auth_callback'     => function() { return current_user_can( 'edit_posts' ); },
        ) );
    }
}
add_action( 'init', 'queerdispatch_register_tip_meta' );

/**
 * Handle tip form submission via admin-post.php (both logged-in and anonymous).
 *
 * Validates the nonce, sanitises all inputs, optionally handles a file
 * upload, creates a qd_tip post, stores meta, and sends an email
 * notification to the site admin.
 */
function queerdispatch_handle_tip_submission() {
    // Verify nonce
    if (
        ! isset( $_POST['queerdispatch_tip_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['queerdispatch_tip_nonce'] ) ), 'queerdispatch_submit_tip' )
    ) {
        wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'queerdispatch' ), 403 );
    }

    // Required field: tip body
    $tip_body = isset( $_POST['tip_body'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tip_body'] ) ) : '';
    if ( empty( trim( $tip_body ) ) ) {
        $redirect = add_query_arg( 'tip_error', 'empty', wp_get_referer() );
        wp_safe_redirect( $redirect );
        exit;
    }

    // Optional fields
    $contact_name  = isset( $_POST['tip_contact_name'] )  ? sanitize_text_field( wp_unslash( $_POST['tip_contact_name'] ) )  : '';
    $contact_email = isset( $_POST['tip_contact_email'] ) ? sanitize_email( wp_unslash( $_POST['tip_contact_email'] ) )       : '';
    $category      = isset( $_POST['tip_category'] )      ? sanitize_text_field( wp_unslash( $_POST['tip_category'] ) )      : 'General';

    // Build post title from category + truncated body
    $short_body  = mb_substr( $tip_body, 0, 60 );
    $post_title  = '[Tip] ' . $category . ': ' . $short_body . ( mb_strlen( $tip_body ) > 60 ? '…' : '' );

    // Create the post
    $post_id = wp_insert_post( array(
        'post_type'    => 'qd_tip',
        'post_title'   => $post_title,
        'post_content' => $tip_body,
        'post_status'  => 'private',
    ), true );

    if ( is_wp_error( $post_id ) ) {
        $redirect = add_query_arg( 'tip_error', 'save', wp_get_referer() );
        wp_safe_redirect( $redirect );
        exit;
    }

    // Store meta
    update_post_meta( $post_id, '_tip_contact_name',  $contact_name );
    update_post_meta( $post_id, '_tip_contact_email', $contact_email );
    update_post_meta( $post_id, '_tip_category',      $category );
    update_post_meta( $post_id, '_tip_status',        'new' );
    update_post_meta( $post_id, '_tip_ip',            sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );

    // Handle optional file upload
    $attachment_name = '';
    if ( ! empty( $_FILES['tip_attachment']['name'] ) ) {
        $file     = $_FILES['tip_attachment'];
        $allowed  = array( 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'mp4', 'mov' );
        $ext      = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        $max_size = 10 * 1024 * 1024; // 10 MB

        if ( in_array( $ext, $allowed, true ) && $file['size'] <= $max_size && $file['error'] === UPLOAD_ERR_OK ) {
            $upload_dir  = wp_upload_dir();
            $tips_dir    = trailingslashit( $upload_dir['basedir'] ) . 'queerdispatch-tips/';
            if ( ! file_exists( $tips_dir ) ) {
                wp_mkdir_p( $tips_dir );
                // Protect directory from direct browsing
                file_put_contents( $tips_dir . '.htaccess', 'Options -Indexes' . PHP_EOL . 'Deny from all' );
            }
            $safe_name = $post_id . '-' . sanitize_file_name( $file['name'] );
            $dest      = $tips_dir . $safe_name;
            if ( move_uploaded_file( $file['tmp_name'], $dest ) ) {
                update_post_meta( $post_id, '_tip_attachment_path', $dest );
                update_post_meta( $post_id, '_tip_attachment_name', sanitize_file_name( $file['name'] ) );
                $attachment_name = sanitize_file_name( $file['name'] );
            }
        }
    }

    // Email notification to admin
    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );
    $admin_url   = admin_url( 'edit.php?post_type=qd_tip' );

    $email_subject = sprintf(
        /* translators: 1: site name, 2: category */
        '[%1$s] New Tip Received — %2$s',
        $site_name,
        $category
    );

    $email_body  = "A new tip has been submitted to {$site_name}.\n\n";
    $email_body .= "Category: {$category}\n";
    $email_body .= $contact_name  ? "Name/Alias: {$contact_name}\n"    : "Name/Alias: (anonymous)\n";
    $email_body .= $contact_email ? "Contact: {$contact_email}\n"      : "Contact: (none provided)\n";
    $email_body .= $attachment_name ? "Attachment: {$attachment_name}\n" : '';
    $email_body .= "\n--- Tip Content ---\n{$tip_body}\n";
    $email_body .= "\nView and triage all tips: {$admin_url}\n";

    wp_mail( $admin_email, $email_subject, $email_body );

    // Redirect to success page
    $submit_page = get_page_by_path( 'submit-a-tip' );
    $success_url = $submit_page
        ? add_query_arg( 'tip_submitted', '1', get_permalink( $submit_page ) )
        : add_query_arg( 'tip_submitted', '1', home_url() );

    wp_safe_redirect( $success_url );
    exit;
}
add_action( 'admin_post_queerdispatch_submit_tip',        'queerdispatch_handle_tip_submission' );
add_action( 'admin_post_nopriv_queerdispatch_submit_tip', 'queerdispatch_handle_tip_submission' );

/**
 * Add custom columns to the Tips admin list table.
 */
function queerdispatch_tip_columns( $columns ) {
    unset( $columns['date'] );
    $columns['tip_category'] = esc_html__( 'Category', 'queerdispatch' );
    $columns['tip_contact']  = esc_html__( 'Contact', 'queerdispatch' );
    $columns['tip_status']   = esc_html__( 'Status', 'queerdispatch' );
    $columns['tip_attach']   = esc_html__( 'Attachment', 'queerdispatch' );
    $columns['date']         = esc_html__( 'Submitted', 'queerdispatch' );
    return $columns;
}
add_filter( 'manage_qd_tip_posts_columns', 'queerdispatch_tip_columns' );

/**
 * Populate custom columns in the Tips admin list table.
 */
function queerdispatch_tip_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'tip_category':
            echo esc_html( get_post_meta( $post_id, '_tip_category', true ) ?: '—' );
            break;
        case 'tip_contact':
            $name  = get_post_meta( $post_id, '_tip_contact_name',  true );
            $email = get_post_meta( $post_id, '_tip_contact_email', true );
            if ( $name || $email ) {
                echo esc_html( $name ?: '' );
                if ( $email ) {
                    echo ' <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
                }
            } else {
                echo '<em>' . esc_html__( 'Anonymous', 'queerdispatch' ) . '</em>';
            }
            break;
        case 'tip_status':
            $status  = get_post_meta( $post_id, '_tip_status', true ) ?: 'new';
            $colours = array(
                'new'        => '#cc0000',
                'reviewing'  => '#d97706',
                'actioned'   => '#16a34a',
                'dismissed'  => '#6b7280',
            );
            $colour = $colours[ $status ] ?? '#6b7280';
            printf(
                '<span style="display:inline-block;padding:2px 8px;border-radius:3px;background:%s;color:#fff;font-size:11px;font-weight:600;text-transform:uppercase;">%s</span>',
                esc_attr( $colour ),
                esc_html( $status )
            );
            break;
        case 'tip_attach':
            $name = get_post_meta( $post_id, '_tip_attachment_name', true );
            echo $name ? '📎 ' . esc_html( $name ) : '—';
            break;
    }
}
add_action( 'manage_qd_tip_posts_custom_column', 'queerdispatch_tip_column_content', 10, 2 );

/**
 * Add a meta box to the tip edit screen for status management.
 */
function queerdispatch_tip_meta_boxes() {
    add_meta_box(
        'qd_tip_details',
        esc_html__( 'Tip Details', 'queerdispatch' ),
        'queerdispatch_tip_details_meta_box',
        'qd_tip',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'queerdispatch_tip_meta_boxes' );

function queerdispatch_tip_details_meta_box( $post ) {
    wp_nonce_field( 'queerdispatch_save_tip_meta', 'queerdispatch_tip_meta_nonce' );
    $category = get_post_meta( $post->ID, '_tip_category',      true );
    $name     = get_post_meta( $post->ID, '_tip_contact_name',  true );
    $email    = get_post_meta( $post->ID, '_tip_contact_email', true );
    $status   = get_post_meta( $post->ID, '_tip_status',        true ) ?: 'new';
    $attach   = get_post_meta( $post->ID, '_tip_attachment_name', true );
    $ip       = get_post_meta( $post->ID, '_tip_ip',            true );
    ?>
    <table class="form-table" style="margin:0;">
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'Category', 'queerdispatch' ); ?></th>
            <td><?php echo esc_html( $category ?: '—' ); ?></td></tr>
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'Name/Alias', 'queerdispatch' ); ?></th>
            <td><?php echo $name ? esc_html( $name ) : '<em>' . esc_html__( 'Anonymous', 'queerdispatch' ) . '</em>'; ?></td></tr>
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'Contact Email', 'queerdispatch' ); ?></th>
            <td><?php echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '<em>' . esc_html__( 'None', 'queerdispatch' ) . '</em>'; ?></td></tr>
        <?php if ( $attach ) : ?>
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'Attachment', 'queerdispatch' ); ?></th>
            <td>📎 <?php echo esc_html( $attach ); ?></td></tr>
        <?php endif; ?>
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'IP Address', 'queerdispatch' ); ?></th>
            <td><?php echo esc_html( $ip ?: '—' ); ?></td></tr>
        <tr><th style="padding:4px 0;"><?php esc_html_e( 'Status', 'queerdispatch' ); ?></th>
            <td>
                <select name="qd_tip_status" style="width:100%;">
                    <?php foreach ( array( 'new', 'reviewing', 'actioned', 'dismissed' ) as $s ) : ?>
                        <option value="<?php echo esc_attr( $s ); ?>" <?php selected( $status, $s ); ?>>
                            <?php echo esc_html( ucfirst( $s ) ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td></tr>
    </table>
    <?php
}

/**
 * Save tip status from the meta box.
 */
function queerdispatch_save_tip_meta( $post_id ) {
    if (
        ! isset( $_POST['queerdispatch_tip_meta_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['queerdispatch_tip_meta_nonce'] ) ), 'queerdispatch_save_tip_meta' )
    ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['qd_tip_status'] ) ) {
        $allowed = array( 'new', 'reviewing', 'actioned', 'dismissed' );
        $status  = sanitize_text_field( wp_unslash( $_POST['qd_tip_status'] ) );
        if ( in_array( $status, $allowed, true ) ) {
            update_post_meta( $post_id, '_tip_status', $status );
        }
    }
}
add_action( 'save_post_qd_tip', 'queerdispatch_save_tip_meta' );

// ============================================================
// INCLUDES
// ============================================================

require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/class-github-updater.php';
require_once get_template_directory() . '/inc/admin-version-page.php';
require_once get_template_directory() . '/inc/tip-admin-columns.php';

// Instantiate the GitHub auto-updater
new QueerDispatch_GitHub_Updater();

// ============================================================
// SECURITY
// ============================================================

// Remove WordPress version from head
remove_action( 'wp_head', 'wp_generator' );

// Remove emoji scripts (performance)
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
