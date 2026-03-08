<?php
/**
 * QueerDispatch Theme — Tips Admin Enhancements
 *
 * Adds a "New Tips" admin bar badge, a quick-status bulk action,
 * and a sortable status column to the qd_tip list table.
 *
 * @package QueerDispatch
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ----------------------------------------------------------------
// Admin bar badge: show count of unread (status=new) tips
// ----------------------------------------------------------------

/**
 * Add a "Tips (N)" node to the admin bar when there are new tips.
 *
 * @param WP_Admin_Bar $admin_bar
 */
function queerdispatch_admin_bar_tips_badge( $admin_bar ) {
    if ( ! current_user_can( 'edit_posts' ) ) {
        return;
    }

    $new_tips = new WP_Query( array(
        'post_type'      => 'qd_tip',
        'post_status'    => 'private',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_tip_status',
                'value'   => 'new',
                'compare' => '=',
            ),
        ),
        'no_found_rows'  => false,
    ) );

    $count = $new_tips->found_posts;

    if ( $count < 1 ) {
        return;
    }

    $admin_bar->add_node( array(
        'id'    => 'queerdispatch-tips',
        'title' => sprintf(
            /* translators: %d: number of new tips */
            '📬 ' . _n( '%d New Tip', '%d New Tips', $count, 'queerdispatch' ),
            $count
        ),
        'href'  => admin_url( 'edit.php?post_type=qd_tip&tip_status_filter=new' ),
        'meta'  => array(
            'title' => sprintf(
                _n( '%d unread tip awaiting review', '%d unread tips awaiting review', $count, 'queerdispatch' ),
                $count
            ),
        ),
    ) );
}
add_action( 'admin_bar_menu', 'queerdispatch_admin_bar_tips_badge', 100 );

// ----------------------------------------------------------------
// Status filter dropdown above the tips list table
// ----------------------------------------------------------------

/**
 * Add a "Filter by status" dropdown to the tips list table.
 *
 * @param string $post_type
 */
function queerdispatch_tip_status_filter_dropdown( $post_type ) {
    if ( $post_type !== 'qd_tip' ) {
        return;
    }

    $current = isset( $_GET['tip_status_filter'] ) ? sanitize_text_field( $_GET['tip_status_filter'] ) : '';
    $statuses = array(
        ''          => __( 'All Statuses', 'queerdispatch' ),
        'new'       => __( 'New', 'queerdispatch' ),
        'reviewing' => __( 'Reviewing', 'queerdispatch' ),
        'actioned'  => __( 'Actioned', 'queerdispatch' ),
        'dismissed' => __( 'Dismissed', 'queerdispatch' ),
    );
    ?>
    <select name="tip_status_filter" id="tip_status_filter">
        <?php foreach ( $statuses as $value => $label ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>>
                <?php echo esc_html( $label ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'queerdispatch_tip_status_filter_dropdown' );

/**
 * Apply the status filter to the query.
 *
 * @param WP_Query $query
 */
function queerdispatch_tip_status_filter_query( $query ) {
    global $pagenow;

    if (
        ! is_admin() ||
        $pagenow !== 'edit.php' ||
        ! isset( $_GET['post_type'] ) ||
        $_GET['post_type'] !== 'qd_tip' ||
        empty( $_GET['tip_status_filter'] )
    ) {
        return;
    }

    $status = sanitize_text_field( $_GET['tip_status_filter'] );
    $allowed = array( 'new', 'reviewing', 'actioned', 'dismissed' );

    if ( ! in_array( $status, $allowed, true ) ) {
        return;
    }

    $meta_query = $query->get( 'meta_query' ) ?: array();
    $meta_query[] = array(
        'key'     => '_tip_status',
        'value'   => $status,
        'compare' => '=',
    );
    $query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'queerdispatch_tip_status_filter_query' );

// ----------------------------------------------------------------
// Quick-status bulk action: mark selected tips as reviewing / actioned / dismissed
// ----------------------------------------------------------------

/**
 * Register bulk actions for the tips list table.
 *
 * @param  array $bulk_actions
 * @return array
 */
function queerdispatch_tip_bulk_actions( $bulk_actions ) {
    $bulk_actions['mark_reviewing'] = __( 'Mark as Reviewing', 'queerdispatch' );
    $bulk_actions['mark_actioned']  = __( 'Mark as Actioned', 'queerdispatch' );
    $bulk_actions['mark_dismissed'] = __( 'Mark as Dismissed', 'queerdispatch' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-qd_tip', 'queerdispatch_tip_bulk_actions' );

/**
 * Handle bulk status updates.
 *
 * @param  string $redirect_to  URL to redirect to after action.
 * @param  string $action       The bulk action being taken.
 * @param  int[]  $post_ids     Array of post IDs.
 * @return string               Redirect URL.
 */
function queerdispatch_tip_handle_bulk_actions( $redirect_to, $action, $post_ids ) {
    $map = array(
        'mark_reviewing' => 'reviewing',
        'mark_actioned'  => 'actioned',
        'mark_dismissed' => 'dismissed',
    );

    if ( ! array_key_exists( $action, $map ) ) {
        return $redirect_to;
    }

    $new_status = $map[ $action ];

    foreach ( $post_ids as $post_id ) {
        if ( get_post_type( $post_id ) === 'qd_tip' && current_user_can( 'edit_post', $post_id ) ) {
            update_post_meta( $post_id, '_tip_status', $new_status );
        }
    }

    $redirect_to = add_query_arg( array(
        'bulk_action_done' => $action,
        'changed'          => count( $post_ids ),
    ), $redirect_to );

    return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-qd_tip', 'queerdispatch_tip_handle_bulk_actions', 10, 3 );

/**
 * Show an admin notice after a bulk status update.
 */
function queerdispatch_tip_bulk_action_notice() {
    if ( empty( $_GET['bulk_action_done'] ) || empty( $_GET['changed'] ) ) {
        return;
    }

    $action  = sanitize_text_field( $_GET['bulk_action_done'] );
    $changed = (int) $_GET['changed'];

    $labels = array(
        'mark_reviewing' => _n( '%d tip marked as Reviewing.', '%d tips marked as Reviewing.', $changed, 'queerdispatch' ),
        'mark_actioned'  => _n( '%d tip marked as Actioned.',  '%d tips marked as Actioned.',  $changed, 'queerdispatch' ),
        'mark_dismissed' => _n( '%d tip marked as Dismissed.', '%d tips marked as Dismissed.', $changed, 'queerdispatch' ),
    );

    if ( ! isset( $labels[ $action ] ) ) {
        return;
    }

    printf(
        '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
        esc_html( sprintf( $labels[ $action ], $changed ) )
    );
}
add_action( 'admin_notices', 'queerdispatch_tip_bulk_action_notice' );

// ----------------------------------------------------------------
// Make the Status column sortable
// ----------------------------------------------------------------

/**
 * Register sortable columns for the tips list table.
 *
 * @param  array $sortable_columns
 * @return array
 */
function queerdispatch_tip_sortable_columns( $sortable_columns ) {
    $sortable_columns['tip_status']   = 'tip_status';
    $sortable_columns['tip_category'] = 'tip_category';
    return $sortable_columns;
}
add_filter( 'manage_edit-qd_tip_sortable_columns', 'queerdispatch_tip_sortable_columns' );

/**
 * Handle sorting by status or category meta key.
 *
 * @param WP_Query $query
 */
function queerdispatch_tip_sort_by_meta( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( $orderby === 'tip_status' ) {
        $query->set( 'meta_key', '_tip_status' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( $orderby === 'tip_category' ) {
        $query->set( 'meta_key', '_tip_category' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'queerdispatch_tip_sort_by_meta' );

// ----------------------------------------------------------------
// Ensure private tips show up in the admin list table
// ----------------------------------------------------------------

/**
 * Include 'private' post status in the default tips query so editors
 * see all tips without needing to filter by status manually.
 *
 * @param WP_Query $query
 */
function queerdispatch_tip_admin_query( $query ) {
    global $pagenow;

    if (
        ! is_admin() ||
        $pagenow !== 'edit.php' ||
        ! isset( $_GET['post_type'] ) ||
        $_GET['post_type'] !== 'qd_tip' ||
        ! $query->is_main_query()
    ) {
        return;
    }

    // Show private posts (how tips are stored) by default
    if ( empty( $query->get( 'post_status' ) ) ) {
        $query->set( 'post_status', array( 'private' ) );
    }
}
add_action( 'pre_get_posts', 'queerdispatch_tip_admin_query' );
