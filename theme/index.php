<?php
/**
 * The main template file — Homepage / Blog Index
 *
 * @package QueerDispatch
 */

get_header();
?>

<main id="primary" class="content-area">

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <header class="page-header container">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php if ( is_home() ) : ?>
        <?php get_template_part( 'template-parts/home', 'hero' ); ?>
    <?php endif; ?>

    <div class="main-content-grid">
        <div class="main-column">

            <?php if ( is_home() ) : ?>

                <?php /* Top Stories Grid */ ?>
                <section class="content-section" aria-labelledby="top-stories-heading">
                    <div class="section-header">
                        <h2 class="section-title" id="top-stories-heading"><?php esc_html_e( 'Top Stories', 'queerdispatch' ); ?></h2>
                    </div>
                    <div class="articles-grid">
                        <?php
                        $top_stories = new WP_Query( array(
                            'posts_per_page' => 4,
                            'ignore_sticky_posts' => 1,
                        ) );
                        if ( $top_stories->have_posts() ) :
                            while ( $top_stories->have_posts() ) : $top_stories->the_post();
                                get_template_part( 'template-parts/content', 'card' );
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </section>

                <?php /* News Section */ ?>
                <section class="content-section" aria-labelledby="news-heading">
                    <div class="section-header">
                        <h2 class="section-title" id="news-heading"><?php esc_html_e( 'News', 'queerdispatch' ); ?></h2>
                    </div>
                    <div class="articles-list">
                        <?php
                        $news_cat = get_category_by_slug( 'news' );
                        $news_query = new WP_Query( array(
                            'posts_per_page' => 4,
                            'category_name'  => 'news',
                        ) );
                        if ( $news_query->have_posts() ) :
                            while ( $news_query->have_posts() ) : $news_query->the_post();
                                get_template_part( 'template-parts/content', 'list' );
                            endwhile;
                            wp_reset_postdata();
                        else :
                            $fallback = new WP_Query( array( 'posts_per_page' => 4, 'offset' => 4 ) );
                            if ( $fallback->have_posts() ) :
                                while ( $fallback->have_posts() ) : $fallback->the_post();
                                    get_template_part( 'template-parts/content', 'list' );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        endif;
                        ?>
                    </div>
                </section>

                <?php /* Culture Section */ ?>
                <section class="content-section" aria-labelledby="culture-heading">
                    <div class="section-header">
                        <h2 class="section-title" id="culture-heading"><?php esc_html_e( 'Culture', 'queerdispatch' ); ?></h2>
                    </div>
                    <div class="articles-grid">
                        <?php
                        $culture_query = new WP_Query( array(
                            'posts_per_page' => 3,
                            'category_name'  => 'culture',
                        ) );
                        if ( $culture_query->have_posts() ) :
                            while ( $culture_query->have_posts() ) : $culture_query->the_post();
                                get_template_part( 'template-parts/content', 'card' );
                            endwhile;
                            wp_reset_postdata();
                        else :
                            $fallback2 = new WP_Query( array( 'posts_per_page' => 3, 'offset' => 8 ) );
                            if ( $fallback2->have_posts() ) :
                                while ( $fallback2->have_posts() ) : $fallback2->the_post();
                                    get_template_part( 'template-parts/content', 'card' );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        endif;
                        ?>
                    </div>
                </section>

                <?php /* Politics Section */ ?>
                <section class="content-section" aria-labelledby="politics-heading">
                    <div class="section-header">
                        <h2 class="section-title" id="politics-heading"><?php esc_html_e( 'Politics', 'queerdispatch' ); ?></h2>
                    </div>
                    <div class="articles-grid">
                        <?php
                        $politics_query = new WP_Query( array(
                            'posts_per_page' => 3,
                            'category_name'  => 'anti-lgbtq-legislation',
                        ) );
                        if ( $politics_query->have_posts() ) :
                            while ( $politics_query->have_posts() ) : $politics_query->the_post();
                                get_template_part( 'template-parts/content', 'card' );
                            endwhile;
                            wp_reset_postdata();
                        else :
                            $fallback3 = new WP_Query( array( 'posts_per_page' => 3, 'offset' => 11 ) );
                            if ( $fallback3->have_posts() ) :
                                while ( $fallback3->have_posts() ) : $fallback3->the_post();
                                    get_template_part( 'template-parts/content', 'card' );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        endif;
                        ?>
                    </div>
                </section>

                <?php /* Opinion Section */ ?>
                <section class="content-section" aria-labelledby="opinion-heading">
                    <div class="section-header">
                        <h2 class="section-title" id="opinion-heading"><?php esc_html_e( 'Opinion', 'queerdispatch' ); ?></h2>
                    </div>
                    <div class="articles-list">
                        <?php
                        $opinion_query = new WP_Query( array(
                            'posts_per_page' => 3,
                            'category_name'  => 'editorial',
                        ) );
                        if ( $opinion_query->have_posts() ) :
                            while ( $opinion_query->have_posts() ) : $opinion_query->the_post();
                                get_template_part( 'template-parts/content', 'list' );
                            endwhile;
                            wp_reset_postdata();
                        else :
                            $fallback4 = new WP_Query( array( 'posts_per_page' => 3, 'offset' => 14 ) );
                            if ( $fallback4->have_posts() ) :
                                while ( $fallback4->have_posts() ) : $fallback4->the_post();
                                    get_template_part( 'template-parts/content', 'list' );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        endif;
                        ?>
                    </div>
                </section>

            <?php else : ?>

                <?php /* Standard blog loop */ ?>
                <?php if ( have_posts() ) : ?>
                    <div class="articles-list container">
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
                    <div class="container">
                        <p><?php esc_html_e( 'No posts found.', 'queerdispatch' ); ?></p>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div><!-- .main-column -->

        <?php get_sidebar(); ?>

    </div><!-- .main-content-grid -->

</main><!-- #primary -->

<?php get_footer(); ?>
