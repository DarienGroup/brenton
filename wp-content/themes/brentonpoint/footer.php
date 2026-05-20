	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-footer__inner container">
			<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer', 'brentonpoint' ); ?>">
				<?php wp_nav_menu( [ 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'depth' => 1 ] ); ?>
			</nav>
			<p class="site-info">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
			</p>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
