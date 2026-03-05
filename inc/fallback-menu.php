<?php
/**
 * Fallback menu when no menu is assigned
 *
 * @package QueerDispatch
 */

function queerdispatch_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'queerdispatch' ) . '</a></li>';

    $pages = get_pages( array( 'number' => 8 ) );
    foreach ( $pages as $page ) {
        echo '<li><a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( $page->post_title ) . '</a></li>';
    }

    echo '</ul>';
}
