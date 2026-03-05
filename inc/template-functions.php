<?php
/**
 * Additional template functions and helpers
 *
 * @package QueerDispatch
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fallback menu when no menu is assigned in Appearance > Menus
 */
function queerdispatch_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'queerdispatch' ) . '</a></li>';

    $pages = get_pages( array( 'number' => 8, 'sort_column' => 'menu_order' ) );
    foreach ( $pages as $page ) {
        echo '<li><a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( $page->post_title ) . '</a></li>';
    }

    echo '</ul>';
}

/**
 * Add custom classes to nav menu items
 */
function queerdispatch_nav_menu_css_class( $classes, $item, $args ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        if ( $item->object === 'category' ) {
            $classes[] = 'menu-category';
        }
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'queerdispatch_nav_menu_css_class', 10, 3 );

/**
 * Wrap sub-menus with accessible markup
 */
function queerdispatch_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'queerdispatch_nav_menu_link_attributes', 10, 3 );

/**
 * Get related posts for single post view
 */
function queerdispatch_get_related_posts( $post_id, $count = 3 ) {
    $categories = wp_get_post_categories( $post_id );
    if ( empty( $categories ) ) {
        return array();
    }

    $args = array(
        'category__in'        => $categories,
        'post__not_in'        => array( $post_id ),
        'posts_per_page'      => $count,
        'ignore_sticky_posts' => 1,
        'orderby'             => 'rand',
    );

    $related = new WP_Query( $args );
    return $related->posts;
}

/**
 * Output reading time estimate
 */
function queerdispatch_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, ceil( $word_count / 200 ) );

    return sprintf(
        /* translators: %d: number of minutes */
        _n( '%d min read', '%d min read', $minutes, 'queerdispatch' ),
        $minutes
    );
}
