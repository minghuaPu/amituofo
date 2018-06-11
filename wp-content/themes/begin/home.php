	<script type="text/javascript" src="./wp-content/themes/begin/js/fjw_commons.js"></script>

	<?php get_header(); 
	?>
<style>
	.main_box > div {background-color:#fff;width:1120px !important;padding:10px 0 0 10px;}
</style>
 
	<div class="main_box">
		<!-- 佛教公案 -->
		<div class="fjzx_box">
			<div class="fjzx_top_box">
				<div class="left_top_box">
					<?php get_template_part('template/slider'); ?>
				</div>

				<div class="right_top_box">
<?php if ( ! dynamic_sidebar( 'first_hdp_right' ) ) : ?>
			 
		<?php endif; ?>
					
				</div>
			</div>
			<div class="fjzx_bottom_box fjbo">
<!-- 遍历佛教公案图片开始 -->
<?php if ( ! dynamic_sidebar( 'sidebar-picture' ) ) : ?>
			 
		<?php endif; ?>
<!-- 遍历佛教公案图结束 -->                         				
			</div>
		</div>
		
		<!-- 圣贤德育 -->
		<div class="fjgc_box">
		<div class="fjgc_left_box">	
		<?php if ( ! dynamic_sidebar( 'sb_two_top' ) ) : ?>
			 
		<?php endif; ?>	

				
		</div>

		<div class="three_box">
		<?php if ( ! dynamic_sidebar( 'sb_three' ) ) : ?>
			 
		<?php endif; ?>					
		</div>
		<div  class="four_box">
		<?php if ( ! dynamic_sidebar( 'sb_four' ) ) : ?>
			 
		<?php endif; ?>					
		</div>

		<div class="five_box">
		<?php if ( ! dynamic_sidebar( 'sb_five' ) ) : ?>
			 
		<?php endif; ?>	
        
	    </div>

        <div  class="center_one"><img src="wp-content/themes/begin/img/fjw/20180426173624984.jpg"/>
        </div>

		<div class="six_box">
		<?php if ( ! dynamic_sidebar( 'sb_six' ) ) : ?>
			 
		<?php endif; ?>	
		</div>


		<div class="evl_box">
		<?php if ( ! dynamic_sidebar( 'sb_seven' ) ) : ?>
			 
		<?php endif; ?>			
		</div>

       	<div class="eight_box">
		<?php if ( ! dynamic_sidebar( 'sb_eight' ) ) : ?>
			 
		<?php endif; ?>
        </div>
       	<div class="fjwd">
		<?php if ( ! dynamic_sidebar( 'sb_nine' ) ) : ?>
			 
		<?php endif; ?>        		
        </div>
		<div class="nine_box">
		<?php if ( ! dynamic_sidebar( 'sb_ten' ) ) : ?>
			 
		<?php endif; ?>
		</div>

 <?php get_footer(); ?>

