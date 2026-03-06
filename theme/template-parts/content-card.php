<?php
/**
 * Template part for displaying a post as a card
 *
 * @package QueerDispatch
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="card-thumbnail">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'queerdispatch-card', array( 'alt' => '' ) ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <?php queerdispatch_post_tags(); ?>

        <h3 class="card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p class="card-excerpt"><?php echo queerdispatch_excerpt( 18 ); ?></p>

        <?php queerdispatch_post_meta(); ?>
    </div>
</article>
