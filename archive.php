<?php
/**
 * The template for displaying archive pages
 *
 * @package QueerDispatch
 */

get_header();
?>

<main id="primary" class="content-area">
    <div class="main-content-grid">
        <div class="main-column">
            <header class="page-header" style="margin-bottom: 32px;">
                <div class="section-header">
                    <h1 class="section-title">
                        <?php
                        if ( is_category() ) {
                            single_cat_title();
                        } elseif ( is_tag() ) {
                            single_tag_title( esc_html__( 'Tag: ', 'queerdispatch' ) );
                        } elseif ( is_author() ) {
                            the_author();
                        } elseif ( is_year() ) {
                            echo get_the_date( 'Y' );
                        } elseif ( is_month() ) {
                            echo get_the_date( 'F Y' );
                        } elseif ( is_day() ) {
                            echo get_the_date();
                        } else {
                            esc_html_e( 'Archives', 'queerdispatch' );
                        }
                        ?>
                    </h1>
                </div>
                <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
            </header>

            <?php if ( have_posts() ) : ?>
                <div class="articles-list">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'list' ); ?>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => esc_html__( '&laquo; Previous', 'queerdispatch' ),
                    'next_text' => esc_html__( 'Next &raquo;', 'queerdispatch' ),
                ) ); ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No posts found.', 'queerdispatch' ); ?></p>
            <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
