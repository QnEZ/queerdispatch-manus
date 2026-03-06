<?php
/**
 * Template part for displaying a post as a list item
 *
 * @package QueerDispatch
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-list-item' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="list-item-thumbnail">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'queerdispatch-list', array( 'alt' => '' ) ); ?>
            </a>
        </div>
    <?php else : ?>
        <div class="list-item-thumbnail" style="background: linear-gradient(135deg, var(--color-accent), var(--color-bg-secondary));"></div>
    <?php endif; ?>

    <div class="list-item-body">
        <?php queerdispatch_post_tags(); ?>

        <h3 class="list-item-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php queerdispatch_post_meta( 'card-meta' ); ?>

        <p class="list-item-excerpt"><?php echo queerdispatch_excerpt( 22 ); ?></p>

        <a href="<?php the_permalink(); ?>" class="read-more">
            <?php esc_html_e( 'Read &rarr;', 'queerdispatch' ); ?>
        </a>
    </div>
</article>
