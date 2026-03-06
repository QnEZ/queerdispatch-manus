<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-style="<?php echo esc_attr( queerdispatch_get_current_style() ); ?>">
<?php wp_body_open(); ?>

<div id="page" class="site-wrapper">
    <a class="sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'queerdispatch' ); ?></a>

    <?php
    // Breaking News Bar
    if ( get_theme_mod( 'queerdispatch_breaking_news_enabled', true ) ) :
        $breaking_text = get_theme_mod( 'queerdispatch_breaking_news_text', '' );
        if ( $breaking_text ) :
            $items = explode( ' | ', $breaking_text );
    ?>
    <div class="breaking-news-bar" role="marquee" aria-label="<?php esc_attr_e( 'Breaking News', 'queerdispatch' ); ?>">
        <div class="breaking-news-inner">
            <span class="breaking-label"><?php esc_html_e( 'Breaking', 'queerdispatch' ); ?></span>
            <div class="breaking-ticker">
                <div class="breaking-ticker-inner">
                    <?php foreach ( $items as $item ) : ?>
                        <span><?php echo wp_kses_post( $item ); ?></span>
                    <?php endforeach; ?>
                    <?php foreach ( $items as $item ) : ?>
                        <span><?php echo wp_kses_post( $item ); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        endif;
    endif;
    ?>

    <header id="masthead" class="site-header" role="banner">
        <div class="header-inner">
            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) :
                    the_custom_logo();
                else :
                ?>
                    <div class="site-title-wrap">
                        <p class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </p>
                        <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( $description || is_customize_preview() ) :
                        ?>
                            <p class="site-description"><?php echo $description; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="header-actions">
                <?php get_search_form(); ?>
                <a href="<?php echo esc_url( get_page_by_path( 'subscribe' ) ? get_permalink( get_page_by_path( 'subscribe' ) ) : '#subscribe' ); ?>" class="btn">
                    <?php esc_html_e( 'Subscribe', 'queerdispatch' ); ?>
                </a>
            </div>
        </div>
    </header>

    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'queerdispatch' ); ?>">
        <div class="nav-inner">
            <button class="menu-toggle" id="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="sr-only"><?php esc_html_e( 'Menu', 'queerdispatch' ); ?></span>
                &#9776; <?php esc_html_e( 'Menu', 'queerdispatch' ); ?>
            </button>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'nav-menu',
                'fallback_cb'    => 'queerdispatch_fallback_menu',
            ) );
            ?>
        </div>
    </nav>
