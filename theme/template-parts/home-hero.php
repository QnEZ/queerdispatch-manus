<?php
/**
 * Template part for the homepage hero section
 *
 * @package QueerDispatch
 */

$hero_query = new WP_Query( array(
    'posts_per_page' => 1,
    'post__in'       => get_option( 'sticky_posts' ),
    'ignore_sticky_posts' => 0,
) );

// Fallback to latest post if no sticky
if ( ! $hero_query->have_posts() ) {
    $hero_query = new WP_Query( array( 'posts_per_page' => 1 ) );
}

if ( $hero_query->have_posts() ) :
    $hero_query->the_post();
?>

<section class="hero-section" aria-label="<?php esc_attr_e( 'Featured Story', 'queerdispatch' ); ?>">
    <div class="hero-inner">
        <div class="hero-image">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                    <?php the_post_thumbnail( 'queerdispatch-hero', array( 'alt' => '' ) ); ?>
                </a>
            <?php else : ?>
                <div style="background: linear-gradient(135deg, var(--color-accent), var(--color-bg-secondary)); width: 100%; height: 100%; min-height: 300px;"></div>
            <?php endif; ?>
        </div>

        <div class="hero-content">
            <?php queerdispatch_post_tags(); ?>

            <h2 class="hero-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <p class="hero-excerpt"><?php echo queerdispatch_excerpt( 30 ); ?></p>

            <?php queerdispatch_post_meta( 'hero-meta' ); ?>

            <a href="<?php the_permalink(); ?>" class="btn">
                <?php esc_html_e( 'Read', 'queerdispatch' ); ?>
            </a>

            <?php
            $submit_page = get_page_by_path( 'submit-a-tip' );
            $submit_url  = $submit_page ? get_permalink( $submit_page ) : '#submit';
            ?>
            <a href="<?php echo esc_url( $submit_url ); ?>" class="btn" style="background: transparent; border: 2px solid var(--color-accent); color: var(--color-accent); margin-top: 4px;">
                <?php esc_html_e( 'Submit a Tip', 'queerdispatch' ); ?>
            </a>
        </div>
    </div>
</section>

<?php
    wp_reset_postdata();
endif;
?>
