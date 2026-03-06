<?php
/**
 * The sidebar containing the main widget area
 *
 * @package QueerDispatch
 */
?>

<aside id="secondary" class="sidebar" role="complementary">

    <?php /* Newsletter Widget */ ?>
    <div class="widget">
        <h2 class="widget-title"><?php esc_html_e( 'Newsletter', 'queerdispatch' ); ?></h2>
        <div class="widget-body">
            <div class="newsletter-form">
                <p><?php esc_html_e( 'Get weekly dispatches and breaking updates.', 'queerdispatch' ); ?></p>
                <form action="#" method="post">
                    <?php wp_nonce_field( 'queerdispatch_newsletter', 'newsletter_nonce' ); ?>
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'your@email.com', 'queerdispatch' ); ?>" required>
                    <button type="submit"><?php esc_html_e( 'Subscribe', 'queerdispatch' ); ?></button>
                </form>
            </div>
        </div>
    </div>

    <?php /* Most Read */ ?>
    <div class="widget">
        <h2 class="widget-title"><?php esc_html_e( 'Most Read', 'queerdispatch' ); ?></h2>
        <div class="widget-body">
            <?php
            $most_read = new WP_Query( array(
                'posts_per_page' => 5,
                'orderby'        => 'comment_count',
                'order'          => 'DESC',
            ) );
            if ( $most_read->have_posts() ) :
            ?>
                <ul class="recent-posts-list">
                    <?php while ( $most_read->have_posts() ) : $most_read->the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                                <span class="post-date"><?php the_date(); ?></span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <?php /* Most Recent */ ?>
    <div class="widget">
        <h2 class="widget-title"><?php esc_html_e( 'Most Recent', 'queerdispatch' ); ?></h2>
        <div class="widget-body">
            <?php
            $most_recent = new WP_Query( array(
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );
            if ( $most_recent->have_posts() ) :
            ?>
                <ul class="recent-posts-list">
                    <?php while ( $most_recent->have_posts() ) : $most_recent->the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                                <span class="post-date"><?php the_date(); ?></span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php endif; ?>

</aside>
