<?php
/**
 * The template for displaying comments
 *
 * @package QueerDispatch
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'queerdispatch' ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            } else {
                printf(
                    esc_html( _nx(
                        '%1$s thought on &ldquo;%2$s&rdquo;',
                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                        $comment_count,
                        'comments title',
                        'queerdispatch'
                    ) ),
                    number_format_i18n( $comment_count ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 48,
                'callback'   => 'queerdispatch_comment_callback',
            ) );
            ?>
        </ol>

        <?php the_comments_pagination( array(
            'prev_text' => esc_html__( '&laquo; Older Comments', 'queerdispatch' ),
            'next_text' => esc_html__( 'Newer Comments &raquo;', 'queerdispatch' ),
        ) ); ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'queerdispatch' ); ?></p>
    <?php endif; ?>

    <?php
    comment_form( array(
        'title_reply'          => esc_html__( 'Leave a Reply', 'queerdispatch' ),
        'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'queerdispatch' ),
        'cancel_reply_link'    => esc_html__( 'Cancel reply', 'queerdispatch' ),
        'label_submit'         => esc_html__( 'Post Comment', 'queerdispatch' ),
        'class_submit'         => 'btn',
        'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'queerdispatch' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>',
    ) );
    ?>

</div>
<?php

/**
 * Custom comment callback
 */
function queerdispatch_comment_callback( $comment, $args, $depth ) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment' ); ?>>
        <div class="comment-body">
            <div class="comment-meta">
                <?php echo get_avatar( $comment, 40 ); ?>
                <span class="comment-author"><?php comment_author_link(); ?></span>
                <span class="comment-date">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <?php comment_date(); ?> <?php esc_html_e( 'at', 'queerdispatch' ); ?> <?php comment_time(); ?>
                    </a>
                </span>
            </div>
            <?php if ( '0' === $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'queerdispatch' ); ?></p>
            <?php endif; ?>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            <div class="comment-reply">
                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="reply">',
                    'after'     => '</span>',
                ) ) );
                ?>
            </div>
        </div>
    </li>
    <?php
}
