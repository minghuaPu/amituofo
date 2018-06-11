	</div><!-- .site-content -->
	
	<?php if (zm_get_option('footer_link')) { ?>
		<?php get_template_part( 'template/footer-links' ); ?>
	<?php } ?>
	<?php get_template_part( 'template/footer-widget' ); ?>
	 
<?php if (zm_get_option('login')) { ?>
<?php get_template_part( 'template/login' ); ?>
<?php } ?>
<?php get_template_part( 'template/scroll' ); ?>
<?php get_template_part( 'template/the-blank' ); ?>
<?php if (zm_get_option('weibo_t')) { ?>
	<script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
	<html xmlns:wb="https://open.weibo.com/wb">
<?php } ?>
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>