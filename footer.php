    <?php queerdispatch_style_switcher(); ?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer-grid">
            <div class="footer-brand">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </p>
                <?php endif; ?>
                <p><?php bloginfo( 'description' ); ?></p>
            </div>

            <div class="footer-nav-col">
                <h3 class="footer-widget-title"><?php esc_html_e( 'Sections', 'queerdispatch' ); ?></h3>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'footer-links',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>

            <div class="footer-newsletter-col">
                <h3 class="footer-widget-title"><?php esc_html_e( 'Get Updates', 'queerdispatch' ); ?></h3>
                <div class="newsletter-form">
                    <p><?php esc_html_e( 'Newsletter signup — weekly dispatches and breaking updates.', 'queerdispatch' ); ?></p>
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <form action="#" method="post" class="newsletter-form">
                            <input type="email" name="email" placeholder="<?php esc_attr_e( 'your@email.com', 'queerdispatch' ); ?>" required>
                            <button type="submit"><?php esc_html_e( 'Subscribe', 'queerdispatch' ); ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copyright">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                <?php esc_html_e( 'All rights reserved.', 'queerdispatch' ); ?>
            </p>
            <p class="footer-credits">
                <?php
                printf(
                    /* translators: %s: WordPress link */
                    esc_html__( 'Powered by %s', 'queerdispatch' ),
                    '<a href="https://wordpress.org/" target="_blank" rel="noopener">WordPress</a>'
                );
                ?>
            </p>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
