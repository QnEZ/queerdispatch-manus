<?php
/**
 * The template for displaying all single posts
 *
 * @package QueerDispatch
 */

get_header();
?>

<main id="primary" class="content-area">
    <div class="main-content-grid">
        <div class="main-column">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

                    <header class="single-post-header">
                        <?php queerdispatch_post_tags(); ?>
                        <h1 class="single-post-title"><?php the_title(); ?></h1>
                        <?php queerdispatch_post_meta( 'single-post-meta' ); ?>
                        <div class="reading-mode-bar">
                            <button id="reading-mode-toggle" class="reading-mode-btn" aria-pressed="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                <span class="reading-mode-label"><?php esc_html_e( 'Reading Mode', 'queerdispatch' ); ?></span>
                            </button>
                        </div>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="single-post-featured-image">
                            <?php the_post_thumbnail( 'queerdispatch-hero', array( 'alt' => get_the_title() ) ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="single-post-content">
                        <?php the_content(); ?>
                    </div>

                    <footer class="single-post-footer">
                        <div class="post-tags-footer">
                            <?php the_tags( '<span class="tags-label">' . esc_html__( 'Tags: ', 'queerdispatch' ) . '</span>', ', ', '' ); ?>
                        </div>
                        <div class="post-nav">
                            <?php
                            the_post_navigation( array(
                                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'queerdispatch' ) . '</span><span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'queerdispatch' ) . '</span><span class="nav-title">%title</span>',
                            ) );
                            ?>
                        </div>
                    </footer>

                </article>

                <?php comments_template(); ?>

            <?php endwhile; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>
