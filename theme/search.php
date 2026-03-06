<?php
/**
 * The template for displaying search results
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
                        printf(
                            esc_html__( 'Search: %s', 'queerdispatch' ),
                            '<span>' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                </div>
            </header>

            <?php if ( have_posts() ) : ?>
                <div class="articles-list">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'list' ); ?>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No results found. Try a different search.', 'queerdispatch' ); ?></p>
                <?php get_search_form(); ?>
            <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
