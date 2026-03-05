<?php
/**
 * The template for displaying all pages
 *
 * @package QueerDispatch
 */

get_header();
?>

<main id="primary" class="content-area">
    <div class="main-content-grid">
        <div class="main-column">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-page' ); ?>>
                    <header class="single-post-header">
                        <h1 class="single-post-title"><?php the_title(); ?></h1>
                    </header>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="single-post-featured-image">
                            <?php the_post_thumbnail( 'queerdispatch-hero' ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="single-post-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php comments_template(); ?>
            <?php endwhile; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
