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
// INCLUDES
// ============================================================

require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/class-github-updater.php';
require_once get_template_directory() . '/inc/admin-version-page.php';

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
