<?php if (zm_get_option('footer_w')) { ?>
<div id="footer-widget-box" class="site-footer">
	<div class="footer-widget">
		<?php if ( ! dynamic_sidebar( 'sidebar-f' ) ) : ?>
			<aside class="add-widgets">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank"></a>
			</aside>
		<?php endif; ?>
		
	</div>
</div>
<?php } ?>