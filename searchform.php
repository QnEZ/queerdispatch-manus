<?php
/**
 * Template for displaying the search form
 *
 * @package QueerDispatch
 */
$unique_id = esc_attr( uniqid( 'search-form-' ) );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo $unique_id; ?>" class="sr-only"><?php esc_html_e( 'Search', 'queerdispatch' ); ?></label>
    <input
        type="search"
        id="<?php echo $unique_id; ?>"
        class="search-field"
        placeholder="<?php esc_attr_e( 'Search&hellip;', 'queerdispatch' ); ?>"
        value="<?php echo get_search_query(); ?>"
        name="s"
    >
    <button type="submit" class="search-submit">
        <span class="sr-only"><?php esc_html_e( 'Search', 'queerdispatch' ); ?></span>
        &#128269;
    </button>
</form>
