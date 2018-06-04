<?php
require get_template_directory() . '/inc/function.php';
// 其它自定义代码加到此行下面

function getPicArticle(){
	global $wpdb;

	$showposts = 1; 
	$posts = $wpdb->get_results($wpdb->prepare("SELECT `post_id`, `meta_value` AS `post_thumbnail_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_thumbnail_id' ORDER BY `post_id` DESC LIMIT 0,{$showposts}",ARRAY_A)); 
	$i = 0;  
	 return $posts;
}