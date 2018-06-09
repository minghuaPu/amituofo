<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php begin_title(); ?>
<link rel="shortcut icon" href="<?php echo zm_get_option('favicon'); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo zm_get_option('apple_icon'); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/css3-mediaqueries.js"></script>
<![endif]-->
<?php wp_head(); ?>
<?php echo zm_get_option('ad_t'); ?>
<?php echo zm_get_option('tongji_h'); ?>

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/fjw.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style1.css">
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
<div class="main_logo">

	<?php $h_video =  zm_get_option('h_video'); 

		if (strstr($h_video, 'mp4')) {
			 echo '<video style="width: 100%;" src="'.$h_video.'" autoplay loop />';
		}else{
			echo '<img src="'.$h_video.'" alt="">';
		}

	?>
	</div>
	<div class="daohang">
           <a href="/" class="shouye">首页</a>
		  <?php wp_nav_menu(10); ?>
		
	</div>
	<?php get_template_part( 'template/header-slider' ); ?>
	<?php get_template_part('ad/ads', 'header'); ?>
	<?php get_template_part( 'template/header-sub' ); ?>
 