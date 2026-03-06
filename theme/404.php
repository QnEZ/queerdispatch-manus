<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package QueerDispatch
 */

get_header();
?>

<main id="primary" class="content-area">
    <div class="container" style="text-align: center; padding: 80px 20px;">
        <h1 class="section-title" style="font-size: 6rem; margin-bottom: 16px;">404</h1>
        <p style="font-size: 1.2rem; margin-bottom: 32px; color: var(--color-text-muted);">
            <?php esc_html_e( 'This page has gone underground. It might have been moved, deleted, or never existed.', 'queerdispatch' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
            <?php esc_html_e( 'Return to the Dispatch', 'queerdispatch' ); ?>
        </a>
        <div style="margin-top: 48px; max-width: 400px; margin-left: auto; margin-right: auto;">
            <?php get_search_form(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
