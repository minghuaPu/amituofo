<?php
// 最新文章
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_cat',
			'description' => __( '显示全部分类或某个分类的最新文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_cat', '最新文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php $q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1'; if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '最新文章',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "new_cat" );' ) );

// 分类文章（图片）
class img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_cat',
			'description' => __( '以图片形式调用一个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_cat', '分类图片', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="picture img_cat">
	<?php $q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1'; if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<span class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), 12 ); ?></a></span>
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类图片',
		'titleUrl' => '',
		'numposts' => 4,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "img_cat" );' ) );

// 近期留言
class recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'recent_comments',
			'description' => __( '带头像的近期留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('recent_comments', '近期留言', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="message" class="message-widget">
	<ul>
		<?php
		$show_comments = $number;
		$my_email = get_bloginfo ('admin_email');
		$i = 1;
		$comments = get_comments('number=200&status=approve&type=comment');
		foreach ($comments as $my_comment) {
			if ($my_comment->comment_author_email != $my_email) {
				?>
				<li>
					<a href="<?php echo get_permalink($my_comment->comment_post_ID); ?>#anchor-comment-<?php echo $my_comment->comment_ID; ?>" title="<?php echo get_the_title($my_comment->comment_post_ID); ?>" rel="external nofollow">
						<?php if (zm_get_option('first_avatar')) { ?>
							<?php echo get_avatar($my_comment->comment_author, '', get_comment_author(), $my_comment->comment_author); ?>
						<?php } else { ?>
							<?php echo get_avatar($my_comment->comment_author_email,64, '', $my_comment->comment_author); ?>
						<?php } ?>
						<span class="comment_author"><strong><?php echo $my_comment->comment_author; ?></strong></span>
						<?php echo convert_smilies($my_comment->comment_content); ?>
					</a>
				</li>
				<?php
				if ($i == $show_comments) break;
				$i++;
			}
		}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '近期评论';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "recent_comments" );' ) );

// 热评文章
class hot_comment extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_comment',
			'description' => __( '调用评论最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_comment', '热评文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="hot_comment_widget">
<?php } ?>
	<ul>
		<?php if($instance['show_thumbs']) { ?>
			<?php
				$review = new WP_Query( array(
					'post_type' => array( 'post' ),
					'showposts' => $number,
					'ignore_sticky_posts' => true,
					'orderby' => 'comment_count',
					'order' => 'dsc',
					'date_query' => array(
						array(
							'after' => ''.$days. 'month ago',
						),
					),
				) );
			?>
			
			<?php while ( $review->have_posts() ): $review->the_post(); ?>
				<li>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="date"><?php the_time('m/d') ?></span>
					<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
				</li>
			<?php endwhile;?>
		<?php } else { ?>
			<?php hot_comment_viewed($number, $days); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热评文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('days'); ?>">时间限定：</label>
		<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" />
		<label>有图/无图：月/天</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_comment" );' ) );

// 标签云
class cx_tag_cloud extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cx_tag_cloud',
			'description' => __( '可实现3D特效' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('cx_tag_cloud', '热门标签', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_3d'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
?>
<?php if ($instance['show_3d']) { ?>
	<div id="tag_cloud_widget">
<?php } else { ?>
	<div class="tagcloud">
<?php } ?>
	<?php wp_tag_cloud( array ( 'smallest' => '14', 'largest' => 14, 'unit' => 'px', 'order' => 'RAND', 'number' => $number ) ); ?>
	<div class="clear"></div>

	<?php if ($instance['show_3d']) : ?><?php wp_enqueue_script( '3dtag.min', get_template_directory_uri() . '/js/3dtag.js', array(), version, false ); ?><?php endif; ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_3d'] = $new_instance['show_3d']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门标签';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_3d') ); ?>" <?php checked( (bool) $instance["show_3d"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>">显示3D特效</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "cx_tag_cloud" );' ) );

// 随机文章
class random_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'random_post',
			'description' => __( '显示随机文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('random_post', '随机文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="random_post_widget">
<?php } ?>

	<ul>
		<?php
		$cat = get_the_category();
		foreach($cat as $key=>$category){
		    $catid = $category->term_id;
		}
		$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 1,'cat' => $catid );
		$query_posts = new WP_Query();
		$query_posts->query($args);
		while ($query_posts->have_posts()) : $query_posts->the_post();
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '随机文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "random_post" );' ) );

// 相关文章
class related_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'related_post',
			'description' => __( '显示相关文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('related_post', '相关文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="related_post_widget">
<?php } ?>

	<ul>
		<?php
			$post_num = $number;
			global $post;
			$tmp_post = $post;
			$tags = ''; $i = 0;
			if ( get_the_tags( $post->ID ) ) {
			foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
			$tags = strtr(rtrim($tags, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php
			$i += 1;
			}
			}
			if ( $i < $post_num ) {
			$post = $tmp_post; setup_postdata($post);
			$cats = ''; $post_num -= $i;
			foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
			$cats = strtr(rtrim($cats, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php
		}
		}
		$post = $tmp_post; setup_postdata($post);
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '相关文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "related_post" );' ) );

// 本站推荐
class hot_commend extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_commend',
			'description' => __( '调用指定的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_commend', '本站推荐', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="hot" class="hot_commend">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php $i = 1; query_posts( array ( 'meta_key' => 'hot', 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
			<li>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
					</span>
					<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
					<?php if (function_exists('zm_link')) { zm_link(); } ?><i class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></i>
				<?php } else { ?>
					<?php if($i < 4) { ?>
						<span class="new-title"><span class='li-number'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php } else { ?>
						<span class="new-title"><span class='li-numbers'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php } ?>
				<?php } ?>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本站推荐';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_commend" );' ) );

// 读者墙
class readers extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'readers',
			'description' => __( '最活跃的读者' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('readers', '读者墙', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 6;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<div id="readers_widget" class="readers">
	<?php
		global $wpdb;
		  $counts = wp_cache_get( 'mostactive' );

		  if ( false === $counts ) {
		    $counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
		        FROM {$wpdb->prefix}comments
		        WHERE comment_date > date_sub( NOW(), INTERVAL $days DAY )
		            AND comment_approved = '1'
		            AND comment_author_email != 'example@example.com'
		            AND comment_author_email != ''
		            AND comment_author_url != ''
		            AND comment_type = ''
		            AND user_id = '0'
		        GROUP BY comment_author_email
		        ORDER BY cnt DESC
		        LIMIT $number");
		  }

		  $mostactive = '';

		  if ( $counts ) {
		    wp_cache_set( 'mostactive', $counts );

		    foreach ($counts as $count) {
		      $c_url = $count->comment_author_url;
		      $mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. get_template_directory_uri()."/inc/go.php?url=".$c_url . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 96, '', $count->comment_author . ' 发表 ' . $count->cnt . ' 条评论') . '</a></span></div>';
		    }
		  echo $mostactive;
		  }
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '读者墙';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '6'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "readers" );' ) );

// 关注我们
class feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'feed',
			'description' => __( 'RSS、微信、微博' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('feed', '关注我们', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-rss">
		<ul>
			<li class="weixin">
				<span class="tipso_style" id="tip-w-j" data-tipso='<span class="weixin-qr"><img src="<?php echo $instance['weixin']; ?>" alt="weixin"/></span>'><a title="微信"><i class="be be-weixin"></i></a></span>
			</li>
			<li class="feed"><a title="订阅" href="<?php echo esc_url( home_url('/') ); ?>feed/" target="_blank" rel="external nofollow"><i class="be be-rss"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="tqq"><a title="" href="<?php echo $instance['tqqurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
		</ul>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关注我们';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-tqq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '输入链接地址'));
		$tqqurl = $instance['tqqurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">腾讯微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">腾讯微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "feed" );' ) );

// 广告位
class advert extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'advert',
			'description' => __( '用于侧边添加广告代码' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('advert', '广告位', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
?>

<?php if ( ! wp_is_mobile() ) { ?>
<div id="advert_widget">
	<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			if ( current_user_can('unfiltered_html') )
				$instance['text'] =  $new_instance['text'];
			else
				$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = ! empty( $new_instance['filter'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '广告位';
		}
		$text = esc_textarea($instance['text']);
		global $wpdb;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>">内容：</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />


<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "advert" );' ) );

// 关于本站
class about extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about',
			'description' => __( '本站信息、RSS、微信、微博、QQ' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about', '关于本站', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-about">
		<div class="about-main">
			<div class="about-img">
				<img src="<?php echo $instance['about_img']; ?>" alt="name"/>
			</div>
			<div class="about-name"><?php echo $instance['about_name']; ?></div>
			<div class="about-the"><?php echo $instance['about_the']; ?></div>
		</div>
		<div class="clear"></div>
		<ul>
			<li class="weixin">
				<span class="tipso_style" id="tip-w" data-tipso='<span class="weixin-qr"><img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/></span>'><a title="微信"><i class="be be-weixin"></i></a></span>
			</li>
			<li class="tqq"><a target=blank rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes title="QQ在线"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></li>
		</ul>
		<div class="about-inf">
			<span class="about-pn"><?php _e( '文章', 'begin' ); ?> <?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span>
			<span class="about-cn"><?php _e( '留言', 'begin' ); ?> <?php global $wpdb;	echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?></span>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['about_img'] = $new_instance['about_img'];
			$instance['about_name'] = $new_instance['about_name'];
			$instance['about_the'] = $new_instance['about_the'];
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['rss'] = $new_instance['rss'];
			$instance['rssurl'] = $new_instance['rssurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于本站';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('about_img' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$about_img = $instance['about_img'];
		$instance = wp_parse_args((array) $instance, array('about_name' => '网站名称'));
		$about_name = $instance['about_name'];
		$instance = wp_parse_args((array) $instance, array('about_the' => '到小工具中更改此内容'));
		$about_the = $instance['about_the'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'be be-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
		$rssurl = $instance['rssurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>">头像：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_name'); ?>">昵称：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_name' ); ?>" name="<?php echo $this->get_field_name( 'about_name' ); ?>" type="text" value="<?php echo $about_name; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_the'); ?>">说明：</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('about_the'); ?>" name="<?php echo $this->get_field_name('about_the'); ?>"><?php echo $about_the; ?></textarea></p>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">QQ图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">QQ号：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "about" );' ) );

// 图片
class img_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_widget',
			'description' => __( '调用最新图片文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_widget', '最新图片', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture img_widget">
	<?php
	    $args = array(
	        'post_type' => 'picture',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'gallery',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新图片';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'gallery', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 视频
class video_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'video_widget',
			'description' => __( '调用最新视频文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('video_widget', '最新视频', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture video_widget">
	<?php
	    $args = array(
	        'post_type' => 'video',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'videos',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { videor_thumbnail_h(); } else { videor_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新视频';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'videos', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 淘客
class tao_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tao_widget',
			'description' => __( '调用最新商品' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('tao_widget', '最新商品', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'tao',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'taobao',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { tao_thumbnail_h(); } else { tao_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新商品';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'taobao', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 多功能小工具
class php_text extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'php_text',
			'description' => __( '支持PHP、JavaScript、短代码等' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('php_text', '增强文本', $widget_ops);
	}

	function widget( $args, $instance ) {

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$cssClass = empty($instance['cssClass']) ? '' : $instance['cssClass'];
		$text = apply_filters('widget_enhanced_text', $instance['text'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$hideEmpty = !empty($instance['hideEmpty']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$filterText = !empty($instance['filter']) ? true : false;
		$bare = !empty($instance['bare']) ? true : false;

		if ( $cssClass ) {
			if( strpos($before_widget, 'class') === false ) {
				$before_widget = str_replace('>', 'class="'. $cssClass . '"', $before_widget);
			} else {
				$before_widget = str_replace('class="', 'class="'. $cssClass . ' ', $before_widget);
			}
		}

	// 通过PHP解析文本
	ob_start();
	eval('?>' . $text);
	$text = ob_get_contents();
	ob_end_clean();

		// 通过do_shortcode运行文本
		$text = do_shortcode($text);

		if (!empty($text) || !$hideEmpty) {
			echo $bare ? '' : $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title</a>";
				echo $bare ? $title : $before_title . $title . $after_title;
			}
			echo $bare ? '' : '<div class="textwidget widget-text">';

			// 重复的内容
			echo $filterText ? wpautop($text) : $text;
			echo $bare ? '' : '</div>' . $after_widget;
		}
	}

    /**
     * 更新内容
     */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = wp_filter_post_kses($new_instance['text']);
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['cssClass'] = strip_tags($new_instance['cssClass']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['hideEmpty'] = isset($new_instance['hideEmpty']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['filter'] = isset($new_instance['filter']);
			$instance['bare'] = isset($new_instance['bare']);

		return $instance;
	}

    /**
     * 管理面板
     */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'titleUrl' => '',
			'cssClass' => '',
			'text' => ''
		));
		$title = $instance['title'];
		$titleUrl = $instance['titleUrl'];
		$cssClass = $instance['cssClass'];
		$text = format_to_edit($instance['text']);
?>

		<style>
			.monospace {
				font-family: Consolas, Lucida Console, monospace;
			}
			.etw-credits {
				font-size: 6.9em;
				background: #F7F7F7;
				border: 1px solid #EBEBEB;
				padding: 4px 6px;
			}
		</style>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('cssClass'); ?>">CSS 类:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('cssClass'); ?>" name="<?php echo $this->get_field_name('cssClass'); ?>" type="text" value="<?php echo $cssClass; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">内容：</label>
			<textarea class="widefat monospace" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('hideTitle'); ?>" name="<?php echo $this->get_field_name('hideTitle'); ?>" type="checkbox" <?php checked(isset($instance['hideTitle']) ? $instance['hideTitle'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideTitle'); ?>">不显示标题</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('hideEmpty'); ?>" name="<?php echo $this->get_field_name('hideEmpty'); ?>" type="checkbox" <?php checked(isset($instance['hideEmpty']) ? $instance['hideEmpty'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideEmpty'); ?>">不显示空的小工具</label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>">自动添加段落</label>
		</p>
		<!-- 
		<p>
			<input id="<?php echo $this->get_field_id('bare'); ?>" name="<?php echo $this->get_field_name('bare'); ?>" type="checkbox" <?php checked(isset($instance['bare']) ? $instance['bare'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('bare'); ?>">标题之前不输出after_widget</label>
		</p>
		 -->
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "php_text" );' ) );

// 即将发布
class timing_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'timing_post',
			'description' => __( '即将发表的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('timing_post', '即将发布', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div class="timing_post">
	<ul>
		<?php
		$my_query = new WP_Query( array ( 'post_status' => 'future','order' => 'ASC','showposts' => $number,'ignore_sticky_posts' => '1'));
		if ($my_query->have_posts()) {
			while ($my_query->have_posts()) : $my_query->the_post();
				$do_not_duplicate = $post->ID; ?>
				<li><i class="be be-schedule"> <?php the_time('G:i') ?></i><?php the_title(); ?></li>
			<?php endwhile;}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '即将发布';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "timing_post" );' ) );

// 作者墙
class author_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'author_widget',
			'description' => __( '显示所有作者头像' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('author_widget', '作者墙', $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php
	$author_numbers=$instance['author_numbers'];
	if($author_numbers) {} else { $author_numbers=50; }
	$list = $instance['exclude_author'];
	$array = explode(',', $list); 
	$count=count($array);
	for($excludeauthor=0;$excludeauthor<=$count;$excludeauthor++) {
		$exclude.="user_login!='".trim($array[$excludeauthor])."'";
		if($excludeauthor!=$count) {
			$exclude.=" and ";
		}
	}
	$where = "WHERE ".$exclude."";
	global $wpdb;
	$table_prefix.=$wpdb->base_prefix;
	$table_prefix.="users";
	$table_prefix1.=$wpdb->base_prefix;
	$table_prefix1.="posts";

	$get_results="SELECT count(p.post_author) as post1,c.id, c.user_login, c.display_name, c.user_email, c.user_url, c.user_registered FROM {$table_prefix} as c , {$table_prefix1} as p {$where} and p.post_type = 'post' AND p.post_status = 'publish' and c.id=p.post_author GROUP BY p.post_author order by post1 DESC limit {$author_numbers}  ";
	$comment_counts = (array) $wpdb->get_results("{$get_results}", object);
?>
<div class="author_widget_box">
	<?php
		foreach ( $comment_counts as $count ) {
			$user = get_userdata($count->id);
			echo '<ul class="xl9"><li class="author_box">';
			$post_count = get_usernumposts($user->ID);
			$postount = get_avatar( $user->user_email, $size = 0);

				$temp=explode(" ",$user->display_name);
			 	$link = sprintf(
					'<a href="%1$s" title="%2$s" >'.$postount.' <span class="clear"></span>%3$s %4$s %5$s</a>',
					get_author_posts_url( $user->ID, $user->user_login ),
					esc_attr( sprintf( ' %s 发表 %s 篇文章', $user->display_name,get_usernumposts($user->ID) ) ),
					$temp[0],$temp[1],$temp[2]
				);
			echo $link;
			echo "</li></ul>";
		}
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}
function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '作者墙'
		)); 
		?> 

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
			<?php $video_embed_c = stripslashes(htmlspecialchars($instance['exclude_author'], ENT_QUOTES)); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>">排除的作者：</label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['exclude_author'] ), ENT_QUOTES)); ?></textarea>
		</p>
		<p>
		<p>
			<label for="<?php echo $this->get_field_id( 'author_numbers' ); ?>">显示数量：</label>
			<input type="text" id="<?php echo $this->get_field_id('author_numbers'); ?>" name="<?php echo $this->get_field_name('author_numbers'); ?>" value="<?php echo $instance['author_numbers']; ?>" style="width:100%;" />
        </p>
	<?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "author_widget" );' ) );

// 关于作者
class about_author extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about_author',
			'description' => __( '只显示在正文和作者页面' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about_author', '关于作者', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if ( is_author() || is_single() ){ 
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
?>

<?php if ( is_author() || is_single() ) { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div id="about_author_widget">
	<div class="author-meta-box">
		<?php if ( $instance[ 'author_back' ]  ) { ?>
			<div class="author-back"><img src="<?php echo $instance['author_back']; ?>" alt="bj"/></div>
		<?php } ?>
		<div class="author-meta">
			<div class="author-avatar"><?php echo get_avatar( get_the_author_meta('user_email'), '96' ); ?><div class="clear"></div></div>
			<h4 class="author-the"><?php the_author(); ?></h4>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="author-th">
			<div class="author-description"><?php the_author_meta( 'user_description' ); ?></div>
			<div class="author-n author-nickname"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
			<div class="author-n"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
			<div class="clear"></div>
			<div class="author-m"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php _e( '更多文章', 'begin' ); ?></a></div>
			<div class="clear"></div>
		</div>
	<div class="clear"></div>
	</div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['author_back'] = $new_instance['author_back'];
			// $instance['author_url'] = $new_instance['author_url'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于作者';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('author_back' => 'https://wx2.sinaimg.cn/large/0066LGKLly1fjvq3dc19uj309q02sa9y.jpg'));
		$author_back = $instance['author_back'];
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('author_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'author_back' ); ?>" name="<?php echo $this->get_field_name( 'author_back' ); ?>" type="text" value="<?php echo $author_back; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "about_author" );' ) );

// 最近更新过的文章
class updated_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'updated_posts',
			'description' => __( '调用最近更新过的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('updated_posts', '最近更新过的文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 15;
?>

<div class="post_cat">
	<ul>
		<?php if ( function_exists('recently_updated_posts') ) recently_updated_posts($number,$days); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最近更新过的文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '15'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">排除近期文章（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "updated_posts" );' ) );

// EDD下载
class edd_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'edd_widget',
			'description' => __( '调用最新EDD下载' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('edd_widget', '最新下载', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'download',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'download_category',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { tao_thumbnail_h(); } else { tao_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新下载';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'download_category', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
if (function_exists( 'edd_get_actions' )) {
add_action( 'widgets_init', create_function( '', 'register_widget( "edd_widget" );' ) );
}

// 用户登录
class user_login extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'user_login',
			'description' => __( '用户登录、管理站点及用户中心等链接' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('user_login', '用户登录', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="login_widget">
	<?php get_currentuserinfo();global $current_user, $user_ID, $user_identity;	if( !$user_ID || '' == $user_ID ) { ?>
		<form action="<?php echo wp_login_url( get_permalink() ); ?>" method="post" class="loginform">
			<p>
				<input class="username" name="log" required="required" type="text" placeholder="名称"/>
			</p>
			<p>
				<input class="password" name="pwd" required="required" type="password" placeholder="密码" />
			</p>
			<div class="login-form"><?php do_action('login_form'); ?></div>
			<p class="login button"> 
				<input type="submit" value="登录" />
			</p>
			<div class="login-widget-reg">
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER[ 'REQUEST_URI' ]; ?>" />
				<label><input type="checkbox" name="rememberme" class="modlogn_remember" value="yes"  checked="checked">自动登录</label>
				<a href="<?php echo esc_url( home_url('/') ); ?>wp-login.php?action=lostpassword">&nbsp;&nbsp;找回密码</a>
				<?php if ( zm_get_option('reg_url') == '' ) { ?><?php } else { ?><a href="<?php echo stripslashes( zm_get_option('reg_url') ); ?>" target="_blank"> 立即注册</a><?php } ?>
			</div>
		</form>
	<?php } ?>
	<?php global $user_identity,$user_level;get_currentuserinfo();if ($user_identity) { ?>
		<div class="login-user-widget">
			<div class="login-widget-avata">
				<?php global $current_user;	get_currentuserinfo();
					echo get_avatar( $current_user->user_email, 64);
				?>
				<div class="clear"></div>
				您已登录：<?php echo $user_identity; ?>
			</div>
			<div class="login-widget-link">
				<?php if ( zm_get_option('user_url') == '' ) { ?>
				<?php } else { ?>
			  		<a href="<?php echo get_permalink( zm_get_option('user_url') ); ?>" target="_blank">用户中心</a>
			  	<?php } ?>
				<?php if (current_user_can('level_10') ){ ?><?php wp_register('', ''); ?><?php } ?>
				<a href="<?php echo wp_logout_url( home_url() ); ?>" title="">退出登录</a>
				<div class="clear"></div>
			</div>
		</div>
	 <?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '用户登录';
		}
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "user_login" );' ) );

// 留言板
class pages_recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'pages_recent_comments',
			'description' => __( '调用“留言板”页面留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('pages_recent_comments', '留言板', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="message" class="message-widget">
	<ul>
		<?php
		$show_comments = $number;
		$my_email = get_bloginfo ('admin_email');
		$i = 1;
		$comments = get_comments(
			array(
				'post_id' => $instance["pages_id"]
			)
		);
		foreach ($comments as $my_comment) {
			if ($my_comment->comment_author_email != $my_email) {
				?>
				<li>
					<a href="<?php echo get_permalink($my_comment->comment_post_ID); ?>#anchor-comment-<?php echo $my_comment->comment_ID; ?>" title="<?php echo get_the_title($my_comment->comment_post_ID); ?>" rel="external nofollow">
						<?php echo get_avatar($my_comment->comment_author_email,64, '', $my_comment->comment_author); ?>
						<span class="comment_author"><strong><?php echo $my_comment->comment_author; ?></strong></span>
						<?php echo convert_smilies($my_comment->comment_content); ?>
					</a>
				</li>
				<?php
				if ($i == $show_comments) break;
				$i++;
			}
		}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['pages_id'] = $new_instance['pages_id'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '留言板';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('pages_id'); ?>">选择页面：</label>
		<?php wp_dropdown_pages( array( 'name' => $this->get_field_name("pages_id"), 'selected' => $instance["pages_id"] ) ); ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "pages_recent_comments" );' ) );

// Tab
class zmTabs extends WP_Widget {
	function __construct() {
		parent::__construct(
			'zmtabs',
			__('Tab组合小工具'),
			array(
				'description' => __( '最新文章、热评文章、热门文章、最近留言' ),
			'classname' => 'widget_zm_tabs'
			)
		);
	}

	public function zm_get_defaults() {
		return array(
			'title'       => '',
			'tabs_category'   => 1,
			'tabs_date'     => 1,
			// Recent posts
			'recent_enable'   => 1,
			'recent_thumbs'   => 1,
			'recent_cat_id'   => '0',
			'recent_num'    => '5',
			// Popular posts
			'popular_enable'  => 1,
			'popular_thumbs'  => 1,
			'popular_cat_id'  => '0',
			'popular_time'    => '0',
			'popular_num'     => '5',
			// Recent comments
			'comments_enable'   => 1,
			'comments_avatars'  => 1,
			'comments_num'    => '5',
			// viewe
			'viewe_enable'     => 1,
			'viewe_thumbs'  => 1,
			'viewe_number'  => '5',
			'viewe_days'    => '90',
		);
	}


/*  Create tabs-nav
/* ------------------------------------ */
	private function _create_tabs($tabs,$count) {
		// Borrowed from Jermaine Maree, thanks mate!
		$titles = array(
			'recent'	=> __('最新文章', 'begin'),
			'popular'	=> __('热评文章', 'begin'),
			'viewe'		=> __('热门文章', 'begin'),
			'comments'	=> __('最近留言', 'begin')
		);
		$icons = array(
			'recent'   => 'be be-file',
			'popular'  => 'be be-favoriteoutline',
			'viewe'     => 'be be-eye',
			'comments' => 'be be-speechbubble'
		);

		$output = sprintf('<div class="zm-tabs-nav group tab-count-%s">', $count);
		foreach ( $tabs as $tab ) {
			$output .= sprintf('<span class="zm-tab tab-%1$s"><a href="javascript:"><i class="%3$s"></i><span>%4$s</span></a></span>',
				$tab,
				$tab . '-' . $this -> number,
				$icons[$tab],
				$titles[$tab]
			);
		}
		$output .= '</div>';
		return $output;
	}

/*  Widget
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
	$defaults = $this -> zm_get_defaults();

	$instance = wp_parse_args( (array) $instance, $defaults );

	$title = apply_filters('widget_title',$instance['title']);
	$title = empty( $title ) ? '' : $title;
		$output = $before_widget."\n";
		if ( ! empty( $title ) )
			$output .= $before_title.$title.$after_title;
		ob_start();

/*  Set tabs-nav order & output it
/* ------------------------------------ */
	$tabs = array();
	$count = 0;
	$order = array(
		'recent'	=> 1,
		'popular'	=> 2,
		'viewe'		=> 3,
		'comments'	=> 4
	);
	asort($order);
	foreach ( $order as $key => $value ) {
		if ( $instance[$key.'_enable'] ) {
			$tabs[] = $key;
			$count++;
		}
	}
	if ( $tabs && ($count > 1) ) { $output .= $this->_create_tabs($tabs,$count); }
?>

	<div class="zm-tabs-container">

		<?php if($instance['recent_enable']) { // Recent posts enabled? ?>

			<?php $recent=new WP_Query(); ?>
			<?php $recent->query('showposts='.$instance["recent_num"].'&cat='.$instance["recent_cat_id"].'&ignore_sticky_posts=1');?>

			<div class="new_cat">
				<ul id="tab-recent-<?php echo $this -> number ?>" class="zm-tab group <?php if($instance['recent_thumbs']) { echo 'thumbs-enabled'; } ?>" style="display:block;">
					<h4><?php _e( '最新文章', 'begin' ); ?></h4>
					<?php while ($recent->have_posts()): $recent->the_post(); ?>
					<li>
						<?php if($instance['recent_thumbs']) { ?>
							<span class="thumbnail">
								<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
							</span>
							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
						<?php } else { ?>
							<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
						<?php } ?>
					</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul><!--/.zm-tab-->
			</div>
		<?php } ?>

		<?php if($instance['popular_enable']) { // Popular posts enabled? ?>

			<?php
				$popular = new WP_Query( array(
					'post_type'				=> array( 'post' ),
					'showposts'				=> $instance['popular_num'],
					'cat'					=> $instance['popular_cat_id'],
					'ignore_sticky_posts'	=> true,
					'orderby'				=> 'comment_count',
					'order'					=> 'dsc',
					'date_query' => array(
						array(
							'after' => $instance['popular_time'],
						),
					),
				) );
			?>

			<div class="new_cat">
				<ul id="tab-popular-<?php echo $this -> number ?>" class="zm-tab group <?php if($instance['popular_thumbs']) { echo 'thumbs-enabled'; } ?>">
					<h4><?php _e( '热评文章', 'begin' ); ?></h4>
					<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
					<li>
						<?php if($instance['popular_thumbs']) { ?>
							<span class="thumbnail">
								<?php zm_thumbnail(); ?>
							</span>
							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						<?php } ?>
					</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul><!--/.zm-tab-->
			</div>
		<?php } ?>

		<?php if($instance['viewe_enable']) { // viewe enabled? ?>

			<div class="new_cat">
				<ul id="tab-viewe-<?php echo $this -> number ?>" class="zm-tab group">
					<h4><?php _e( '热门文章', 'begin' ); ?></h4>
					<?php if($instance['viewe_thumbs']) { ?>
						<?php if (function_exists('get_most_viewed')) { ?>
							<?php get_timespan_most_viewed_img('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
						<?php } else { ?>
							<li><a href="https://wordpress.org/plugins/wp-postviews/" rel="external nofollow" target="_blank">需要安装WP-PostViews插件</a></li>
						<?php } ?>
					<?php } else { ?>
						<?php if (function_exists('get_most_viewed')) { ?>
							<?php get_timespan_most_viewed('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
						<?php } else { ?>
							<li><a href="https://wordpress.org/plugins/wp-postviews/" rel="external nofollow" target="_blank">需要安装WP-PostViews插件</a></li>
						<?php } ?>
					<?php } ?>
					<?php wp_reset_query(); ?>
				</ul><!--/.zm-tab-->
			</div>

		<?php } ?>

		<?php if($instance['comments_enable']) { // Recent comments enabled? ?>

			<?php $comments = get_comments(array('number'=>$instance["comments_num"],'status'=>'approve','post_status'=>'publish')); ?>
			<div class="message-tab message-widget">
				<ul>
				<h4><?php _e( '最近留言', 'begin' ); ?></h4>
					<?php
					$show_comments = $instance["comments_num"];
					$my_email = get_bloginfo ('admin_email');
					$i = 1;
					$comments = get_comments('number=200&status=approve&type=comment');
					foreach ($comments as $my_comment) {
						if ($my_comment->comment_author_email != $my_email) {
							?>
							<li>
								<a href="<?php echo get_permalink($my_comment->comment_post_ID); ?>#anchor-comment-<?php echo $my_comment->comment_ID; ?>" title="<?php echo get_the_title($my_comment->comment_post_ID); ?>" rel="external nofollow">
									<?php if($instance['comments_avatars']) { ?>
										<?php if (zm_get_option('first_avatar')) { ?>
											<?php echo get_avatar($my_comment->comment_author, '', get_comment_author(), $my_comment->comment_author); ?>
										<?php } else { ?>
											<?php echo get_avatar($my_comment->comment_author_email,64, '', $my_comment->comment_author); ?>
										<?php } ?>
									<?php } ?>
									<span class="comment_author"><strong><?php echo $my_comment->comment_author; ?></strong></span>
									<?php echo convert_smilies($my_comment->comment_content); ?>
								</a>
							</li>
							<?php
							if ($i == $show_comments) break;
							$i++;
						}
					}
					?>
				</ul>
			</div>
		<?php } ?>

	</div>

<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}

/*  Widget update
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['tabs_category'] = $new['tabs_category']?1:0;
		$instance['tabs_date'] = $new['tabs_date']?1:0;
	// Recent posts
		$instance['recent_enable'] = $new['recent_enable']?1:0;
		$instance['recent_thumbs'] = $new['recent_thumbs']?1:0;
		$instance['recent_cat_id'] = strip_tags($new['recent_cat_id']);
		$instance['recent_num'] = strip_tags($new['recent_num']);
	// Popular posts
		$instance['popular_enable'] = $new['popular_enable']?1:0;
		$instance['popular_thumbs'] = $new['popular_thumbs']?1:0;
		$instance['popular_cat_id'] = strip_tags($new['popular_cat_id']);
		$instance['popular_time'] = strip_tags($new['popular_time']);
		$instance['popular_num'] = strip_tags($new['popular_num']);
	// Recent comments
		$instance['comments_enable'] = $new['comments_enable']?1:0;
		$instance['comments_avatars'] = $new['comments_avatars']?1:0;
		$instance['comments_num'] = strip_tags($new['comments_num']);
	// viewe
		$instance['viewe_enable'] = $new['viewe_enable']?1:0;
		$instance['viewe_thumbs'] = $new['viewe_thumbs']?1:0;
		$instance['viewe_number'] = strip_tags($new['viewe_number']);
		$instance['viewe_days'] = strip_tags($new['viewe_days']);
		return $instance;
	}

/*  Widget form
/* ------------------------------------ */
	public function form($instance) {
		// Default widget settings
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>
	.widget .widget-inside .zm-options-tabs .postform { width: 100%; }
	.widget .widget-inside .zm-options-tabs p { margin: 3px 0; }
	.widget .widget-inside .zm-options-tabs hr { margin: 20px 0 10px; }
	.widget .widget-inside .zm-options-tabs h4 { margin-bottom: 10px; }
	</style>

	<div class="zm-options-tabs">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">标题：</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>

		<h4>最新文章</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('recent_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('recent_enable') ); ?>" <?php checked( (bool) $instance["recent_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('recent_enable') ); ?>">显示最新文章</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('recent_thumbs') ); ?>" <?php checked( (bool) $instance["recent_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("recent_num") ); ?>">显示篇数</label>
			<input style="width:20%;" id="<?php echo esc_attr( $this->get_field_id("recent_num") ); ?>" name="<?php echo esc_attr( $this->get_field_name("recent_num") ); ?>" type="text" value="<?php echo absint($instance["recent_num"]); ?>" size='3' />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("recent_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("recent_cat_id"), 'selected' => $instance["recent_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>

		<hr>
		<h4>热评文章</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('popular_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('popular_enable') ); ?>" <?php checked( (bool) $instance["popular_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('popular_enable') ); ?>">显示热评文章</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('popular_thumbs') ); ?>" <?php checked( (bool) $instance["popular_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("popular_num") ); ?>">显示篇数</label>
			<input style="width:20%;" id="<?php echo esc_attr( $this->get_field_id("popular_num") ); ?>" name="<?php echo esc_attr( $this->get_field_name("popular_num") ); ?>" type="text" value="<?php echo absint($instance["popular_num"]); ?>" size='3' />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("popular_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("popular_cat_id"), 'selected' => $instance["popular_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>
		<p style="padding-top: 0.3em;">
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>">选择时间段：</label>
			<select style="width: 100%;" id="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>" name="<?php echo esc_attr( $this->get_field_name("popular_time") ); ?>">
				<option value="0"<?php selected( $instance["popular_time"], "0" ); ?>>全部</option>
				<option value="1 year ago"<?php selected( $instance["popular_time"], "1 year ago" ); ?>>一年内</option>
				<option value="1 month ago"<?php selected( $instance["popular_time"], "1 month ago" ); ?>>一月内</option>
				<option value="1 week ago"<?php selected( $instance["popular_time"], "1 week ago" ); ?>>一周内</option>
				<option value="1 day ago"<?php selected( $instance["popular_time"], "1 day ago" ); ?>>24小时内</option>
			</select>
		</p>

		<hr>
		<h4>热门文章</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('viewe_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('viewe_enable') ); ?>" <?php checked( (bool) $instance["viewe_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('viewe_enable') ); ?>">显示热门文章</label>
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('viewe_thumbs') ); ?>" <?php checked( (bool) $instance["viewe_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>">显示缩略图</label>
		</p>

		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("viewe_number") ); ?>">显示篇数：</label>
			<input style="width:20%;" id="<?php echo esc_attr( $this->get_field_id("viewe_number") ); ?>" name="<?php echo esc_attr( $this->get_field_name("viewe_number") ); ?>" type="text" value="<?php echo absint($instance["viewe_number"]); ?>" size='3' />
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("viewe_days") ); ?>">时间限定（天）：</label>
			<input style="width:20%;" id="<?php echo esc_attr( $this->get_field_id("viewe_days") ); ?>" name="<?php echo esc_attr( $this->get_field_name("viewe_days") ); ?>" type="text" value="<?php echo absint($instance["viewe_days"]); ?>" size='3' />
		</p>

		<hr>
		<h4>最新留言</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('comments_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments_enable') ); ?>" <?php checked( (bool) $instance["comments_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('comments_enable') ); ?>">显示最新留言</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments_avatars') ); ?>" <?php checked( (bool) $instance["comments_avatars"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>">显示头像</label>
		</p>
		<p>
			<label style="width: 55%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("comments_num") ); ?>">显示数量：</label>
			<input style="width:20%;" id="<?php echo esc_attr( $this->get_field_id("comments_num") ); ?>" name="<?php echo esc_attr( $this->get_field_name("comments_num") ); ?>" type="text" value="<?php echo absint($instance["comments_num"]); ?>" size='3' />
		</p>
	</div>
<?php
}
}

function zm_register_widget_tabs() {
	register_widget( 'zmTabs' );
}
add_action( 'widgets_init', 'zm_register_widget_tabs' );

// 即将发布
class site_profile extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'site_profile',
			'description' => __( '网站概况' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('site_profile', '网站概况', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$time = strip_tags($instance['time']) ? absint( $instance['time'] ) : 2007-8-1;
?>

<div class="site-profile">
	<ul>
		<li><i class="be be-paper"></i>文章总数<span><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?> 篇</span></li>
		<li><i class="be be-speechbubble"></i>评论留言<span><?php global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?> 条</span></li>
		<li><i class="be be-folder"></i>分类目录<span><?php echo $count_categories = wp_count_terms('category'); ?> 个</span></li>
		<li><i class="be be-localoffer"></i>文章标签<span><?php echo $count_tags = wp_count_terms('post_tag'); ?> 个</span></li>
		<li><i class="be be-link"></i>友情链接<span><?php global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'Y'"); ?> 个</span></li>
		<li><i class="be be-schedule"></i>网站运行<span><?php echo floor((time()-strtotime($instance['time']))/86400); ?> 天</span></li>
		<li><i class="be be-eye"></i>浏览总量<span><?php echo all_view(); ?> 次</span></li>
		<li><i class="be be-editor"></i>最后更新<span><?php global $wpdb; $last =$wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y年n月j日', strtotime($last[0]->MAX_m));echo $last; ?></span></li>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['time'] = strip_tags($new_instance['time']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '网站概况';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('time' => '2007-8-1'));
		$time = strip_tags($instance['time']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('time'); ?>">建站日期：</label>
	<input id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" type="text" value="<?php echo $time; ?>" size="10" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "site_profile" );' ) );

// 热门文章
class hot_post_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_post_img',
			'description' => __( '调用点击最多的文章，安装 wp-postviews 插件,并有统计数据' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_post_img', '热门文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="hot_post_widget" class="new_cat">
<?php } else { ?>
<div id="hot_post_widget">
<?php } ?>

	<ul>
		<?php if($instance['show_thumbs']) { ?>
			<?php if (function_exists('get_most_viewed')) { ?>
			<?php if (zm_get_option('lazy_s')) { ?>
			<?php get_timespan_most_viewed_img_h('post',$number,$days, true, true); ?>
			<?php } else { ?>
			<?php get_timespan_most_viewed_img('post',$number,$days, true, true); ?>
			<?php } ?>
		<?php } else { ?>
			<li><a href="https://wordpress.org/plugins/wp-postviews/" rel="external nofollow" target="_blank">需要安装WP-PostViews插件</a></li>
		<?php } ?>
		<?php } else { ?>
		    <?php get_timespan_most_viewed('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
		<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" />
	 </p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

if( function_exists( 'the_views' ) ) { 
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_post_img" );' ) );
}

// 大家喜欢
class like_most_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'like_most_img',
			'description' => __( '调用点赞最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('like_most_img', '大家喜欢', $widget_ops);
	}
	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="like" class="new_cat">
<?php } else { ?>
<div id="like" class="like_most">
<?php } ?>
	<ul>
		<?php if($instance['show_thumbs']) { ?>
			<?php if (zm_get_option('lazy_s')) { ?>
				<?php get_like_most_img_h('post',$number,$days, true, true); ?>
			<?php } else { ?>
				<?php get_like_most_img('post',$number,$days, true, true); ?>
			<?php } ?>
		<?php } else { ?>
			<?php get_like_most('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '大家喜欢';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		 <label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
		<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "like_most_img" );' ) );

// Ajax组合小工具
if ( !class_exists('wpz_widget') ) {
	class wpz_widget extends WP_Widget {
		function __construct() {
			// ajax functions
			add_action('wp_ajax_wpz_widget_content', array(&$this, 'ajax_wpz_widget_content'));
			add_action('wp_ajax_nopriv_wpz_widget_content', array(&$this, 'ajax_wpz_widget_content'));
			// css
			add_action('wp_enqueue_scripts', array(&$this, 'wpz_register_scripts'));
			$widget_ops = array('classname' => 'widget_wpz', 'description' => __('最新文章、热门文章、推荐文章、热门文章等', 'begin-tab'));
			$control_ops = array('width' => 300, 'height' => 350);
			parent::__construct('wpz_widget', __('Ajax组合小工具', 'begin-tab'), $widget_ops, $control_ops);
		}
	    function wpz_register_scripts() { 
			// JS 
			wp_register_script( 'wpz_widget', get_template_directory_uri() . "/js/ajax-tab.js", array('jquery') );
			wp_localize_script( 'wpz_widget', 'wpz',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ))
			);
	    }

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 
				'tabs' => array('recent' => 1, 'popular' => 1, 'hot' => 1, 'review' => 1, 'comments' => 1, 'recommend' => 1), 
				'tab_order' => array('recent' => 1, 'popular' => 2, 'hot' => 3, 'review' => 4, 'comments' => 5, 'recommend' => 6),
				'allow_pagination' => 1,
				'post_num' => '5', 
				'comment_num' => '12',
				'show_thumb' => '1', 
				'viewe_days' => '90',
				'review_days' => '3',
				'like_days' => '90', 
			) );
			
			extract($instance);
			?>

			<div class="wpz_options_form">

		        <h4><?php _e('选择', 'begin-tab'); ?></h4>
		        
				<div class="wpz_select_tabs">
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recent">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recent" name="<?php echo $this->get_field_name("tabs"); ?>[recent]" value="1" <?php if (isset($tabs['recent'])) { checked( 1, $tabs['recent'], true ); } ?> />		
						<?php _e( '最新文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px" for="<?php echo $this->get_field_id("tabs"); ?>_popular">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_popular" name="<?php echo $this->get_field_name("tabs"); ?>[popular]" value="1" <?php if (isset($tabs['popular'])) { checked( 1, $tabs['popular'], true ); } ?> />
						<?php _e( '大家喜欢', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_hot">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_hot" name="<?php echo $this->get_field_name("tabs"); ?>[hot]" value="1" <?php if (isset($tabs['hot'])) { checked( 1, $tabs['hot'], true ); } ?> />
						<?php _e( '热门文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_review">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_review" name="<?php echo $this->get_field_name("tabs"); ?>[review]" value="1" <?php if (isset($tabs['review'])) { checked( 1, $tabs['review'], true ); } ?> />
						<?php _e( '热评文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_comments">
						<input type="checkbox" class="checkbox wpz_enable_comments" id="<?php echo $this->get_field_id("tabs"); ?>_comments" name="<?php echo $this->get_field_name("tabs"); ?>[comments]" value="1" <?php if (isset($tabs['comments'])) { checked( 1, $tabs['comments'], true ); } ?> />
						<?php _e( '最近留言', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recommend">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recommend" name="<?php echo $this->get_field_name("tabs"); ?>[recommend]" value="1" <?php if (isset($tabs['recommend'])) { checked( 1, $tabs['recommend'], true ); } ?> />
						<?php _e( '推荐阅读', 'begin-tab'); ?>
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="wpz_tab_order_header"><?php _e('顺序', 'begin-tab'); ?></h4>

				<div class="wpz_tab_order">
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recent" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recent" name="<?php echo $this->get_field_name('tab_order'); ?>[recent]" type="number" min="1" step="1" value="<?php echo $tab_order['recent']; ?>" style="width: 48px;" />
						<?php _e('最新文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_popular" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_popular" name="<?php echo $this->get_field_name('tab_order'); ?>[popular]" type="number" min="1" step="1" value="<?php echo $tab_order['popular']; ?>" style="width: 48px;" />
						<?php _e('大家喜欢', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_hot" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_hot" name="<?php echo $this->get_field_name('tab_order'); ?>[hot]" type="number" min="1" step="1" value="<?php echo $tab_order['hot']; ?>" style="width: 48px;" />
						<?php _e('热门文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_review" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_review" name="<?php echo $this->get_field_name('tab_order'); ?>[review]" type="number" min="1" step="1" value="<?php echo $tab_order['review']; ?>" style="width: 48px;" />
						<?php _e('热评文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_comments" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_comments" name="<?php echo $this->get_field_name('tab_order'); ?>[comments]" type="number" min="1" step="1" value="<?php echo $tab_order['comments']; ?>" style="width: 48px;" />
						<?php _e('最近留言', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recommend" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recommend" name="<?php echo $this->get_field_name('tab_order'); ?>[recommend]" type="number" min="1" step="1" value="<?php echo $tab_order['recommend']; ?>" style="width: 48px;" />
						<?php _e('推荐阅读', 'begin-tab'); ?>
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="wpz_advanced_options_header"><?php _e('选项', 'begin-tab'); ?></h4>

				<div class="wpz_advanced_options">
			        <p>
						<label for="<?php echo $this->get_field_id("allow_pagination"); ?>">
							<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("allow_pagination"); ?>" name="<?php echo $this->get_field_name("allow_pagination"); ?>" value="1" <?php if (isset($allow_pagination)) { checked( 1, $allow_pagination, true ); } ?> />
							<?php _e( '显示翻页', 'begin-tab'); ?>
						</label>
					</p>

						<p>
							<label for="<?php echo $this->get_field_id("show_thumb"); ?>">
								<input type="checkbox" class="checkbox wpz_show_thumbnails" id="<?php echo $this->get_field_id("show_thumb"); ?>" name="<?php echo $this->get_field_name("show_thumb"); ?>" value="1" <?php if (isset($show_thumb)) { checked( 1, $show_thumb, true ); } ?> />
								<?php _e( '显示缩略图', 'begin-tab'); ?>
							</label>
						</p>

					<div class="wpz_post_options">

						<p>
							<label for="<?php echo $this->get_field_id('post_num'); ?>"><?php _e('显示数量：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('pcat'); ?>"><?php _e('最新文章排除的分类ID，如：-1,-3：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('pcat'); ?>" name="<?php echo $this->get_field_name('pcat'); ?>" type="text" value="<?php echo $pcat; ?>" />
							</label>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('like_days'); ?>"><?php _e('大家喜欢时间限定（天）：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('like_days'); ?>" name="<?php echo $this->get_field_name('like_days'); ?>" type="number" min="1" step="1" value="<?php echo $like_days; ?>" />
							</label>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('viewe_days'); ?>"><?php _e('热门文章时间限定（天）：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('viewe_days'); ?>" name="<?php echo $this->get_field_name('viewe_days'); ?>" type="number" min="1" step="1" value="<?php echo $viewe_days; ?>" />
							</label>
						</p>
					
						<p>
							<label for="<?php echo $this->get_field_id('review_days'); ?>"><?php _e('热评文章时间限定（月）：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('review_days'); ?>" name="<?php echo $this->get_field_name('review_days'); ?>" type="number" min="1" step="1" value="<?php echo $review_days; ?>" />
							</label>
						</p>
					
					    <p>
							<label for="<?php echo $this->get_field_id('comment_num'); ?>">
								<?php _e('评论显示数量：', 'begin-tab'); ?>
								<br />
								<input type="number" min="1" step="1" id="<?php echo $this->get_field_id('comment_num'); ?>" name="<?php echo $this->get_field_name('comment_num'); ?>" value="<?php echo $comment_num; ?>" />
							</label>
						</p>
					</div>
				</div><!-- .wpz_advanced_options -->
			</div><!-- .wpz_options_form -->
			<?php 
		}
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['tabs'] = $new_instance['tabs'];
			$instance['tab_order'] = $new_instance['tab_order']; 
			$instance['allow_pagination'] = $new_instance['allow_pagination'];
			$instance['post_num'] = $new_instance['post_num'];
			$instance['comment_num'] =  $new_instance['comment_num'];
			$instance['viewe_days'] =  $new_instance['viewe_days'];
			$instance['review_days'] =  $new_instance['review_days'];
			$instance['like_days'] =  $new_instance['like_days'];
			$instance['show_thumb'] = $new_instance['show_thumb'];
			$instance['pcat'] = $new_instance['pcat'];
			return $instance;
		}
		function widget( $args, $instance ) {
			extract($args);
			extract($instance);
			wp_enqueue_script('wpz_widget');
			wp_enqueue_style('wpz_widget');
			if (empty($tabs)) $tabs = array('recent' => 1, 'popular' => 1);
			$tabs_count = count($tabs);
			if ($tabs_count <= 1) {
				$tabs_count = 1;
			} elseif($tabs_count > 5) {
				$tabs_count = 6;
			}

			$available_tabs = array(
				'recent' => __('最新文章', 'begin'), 
				'popular' => __('大家喜欢', 'begin'), 
				'hot' => __('热门文章', 'begin'), 
				'review' => __('热评文章', 'begin'), 
				'comments' => __('最近留言', 'begin'),
				'recommend' => __('推荐阅读', 'begin'));
			array_multisort($tab_order, $available_tabs);
			?>

			<?php echo $before_widget; ?>
			<div class="wpz_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
				<div class="wpz-tabs <?php echo "has-$tabs_count-"; ?>tabs">
					<?php foreach ($available_tabs as $tab => $label) { ?>
						<?php if (!empty($tabs[$tab])): ?>
							<span class="tab_title"><a href="#" title="<?php echo $label; ?>" id="<?php echo $tab; ?>-tab"></a></span>
						<?php endif; ?>
					<?php } ?> 
					<div class="clear"></div>
				</div>
				<!--end .tabs-->

				<div class="clear"></div>

				<div class="new_cat">
					<?php if (!empty($tabs['popular'])): ?>
						<div id="popular-tab-content" class="tab-content">
						</div> <!--end #popular-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['recent'])): ?>	
						<div id="recent-tab-content" class="tab-content">
						</div> <!--end #recent-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['recommend'])): ?>
						<div id="recommend-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #recommend-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['hot'])): ?>
						<div id="hot-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #tags-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['review'])): ?>
						<div id="review-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #tags-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['comments'])): ?>
						<div id="comments-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #comments-tab-content-->
					<?php endif; ?>


					<div class="clear"></div>
				</div> <!--end .inside -->

				<div class="clear"></div>
			</div><!--end #tabber -->
			<?php 
			// inline script 
			// to support multiple instances per page with different settings
			unset($instance['tabs'], $instance['tab_order']); // unset unneeded
			?>

			<script type="text/javascript">
				jQuery(function($) { 
					$('#<?php echo $widget_id; ?>_content').data('args', <?php echo json_encode($instance); ?>);
				});
			</script>

			<?php echo $after_widget; ?>
			<?php 
		}

		function ajax_wpz_widget_content() {
			$tab = $_POST['tab'];
			$args = $_POST['args'];
	    	$number = intval( $_POST['widget_number'] );
			$page = intval($_POST['page']);
			if ($page < 1)
				$page = 1;

			if ( !is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$wpz_widgets = new wpz_widget();
				$settings = $wpz_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die( __('出错了！', 'begin-tab') );
				}
			}

			// sanitize args
			$post_num = (empty($args['post_num']) ? 5 : intval($args['post_num']));
			if ($post_num > 20 || $post_num < 1) { // max 20 posts
				$post_num = 5;
			}
			$comment_num = (empty($args['comment_num']) ? 5 : intval($args['comment_num']));
			if ($comment_num > 20 || $comment_num < 1) {
				$comment_num = 5;
			}
			$viewe_days = (empty($args['viewe_days']) ? 90 : intval($args['viewe_days']));
			$review_days = (empty($args['review_days']) ? 3 : intval($args['review_days']));
			$like_days = (empty($args['like_days']) ? 90 : intval($args['like_days']));
			$show_thumb = !empty($args['show_thumb']);
			$pcat = strip_tags($args['pcat']);
			$allow_pagination = !empty($args['allow_pagination']);
	        
			/* ---------- Tab Contents ---------- */
			switch ($tab) { 

				/* ---------- Recent Posts ---------- */ 
				case "recent":
					?>
					<ul>
						<h4><?php _e( '最新文章', 'begin' ); ?></h4>
						<?php 
						$recent = new WP_Query('posts_per_page='. $post_num .'&orderby=post_date&order=desc&post_status=publish&cat='.$pcat.'&paged='. $page);
						$last_page = $recent->max_num_pages;
						while ($recent->have_posts()) : $recent->the_post();
						?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail(); ?>
									</span>

									<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
									<span class="date"><?php the_time('m/d') ?></span>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile; wp_reset_query(); ?>
					</ul>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Popular Posts ---------- */
				case "popular":
					?>
					<ul> 
						<h4><?php _e( '大家喜欢', 'begin' ); ?></h4>
						<?php 
						$date_query=array(
							array(
								'column' => 'post_date',
								'before' => date('Y-m-d H:i',time()),
								'after' =>date('Y-m-d H:i',time()-3600*24*$viewe_days)
							)
						);
						$args=array(
						'meta_key' => 'zm_like',
						'orderby' => 'meta_value_num',
						'posts_per_page'=>$post_num,
						'date_query' => $like_days,
						'paged' => $page,
						'order' => 'DESC'
						);
						query_posts($args); while (have_posts()) : the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail(); ?>
								</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<span class="date"><?php the_time('m/d') ?></span>
								<span class="discuss"><i class="be be-thumbs-up-o"></i><?php zm_get_current_count(); ?></span>
							<?php } else { ?>
								<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php endwhile;wp_reset_query(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- hot ---------- */
				case "hot":
					?> 
					<ul> 
						<h4><?php _e( '热门文章', 'begin' ); ?></h4>
						<?php 
						$date_query=array(
							array(
								'column' => 'post_date',
								'before' => date('Y-m-d H:i',time()),
								'after' =>date('Y-m-d H:i',time()-3600*24*$viewe_days)
							)
						);
						$args=array(
						'meta_key' => 'views',
						'orderby' => 'meta_value_num',
						'post_status' => 'publish',
						'posts_per_page'=>$post_num,
						'date_query' => $date_query,
						'paged' => $page,
						'order' => 'DESC'
						);
						query_posts($args); while (have_posts()) : the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
							<?php } else { ?>
								<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php endwhile;wp_reset_query(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest recommend ---------- */
				case "recommend":
					?> 
					<ul>
						<h4><?php _e( '推荐阅读', 'begin' ); ?></h4>
						<?php query_posts( array ( 'meta_key' => 'hot', 'showposts' => $post_num, 'ignore_sticky_posts' => 1, 'paged' => $page ) ); while ( have_posts() ) : the_post(); ?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail(); ?>
									</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<span class="date"><?php the_time('m/d') ?></span>
								<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile;?>
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest comments ---------- */
				case "comments":
					?> 
					<div class="message-tab message-widget">
						<ul>
							<h4><?php _e( '最近留言', 'begin' ); ?></h4>
							<?php 
							$no_comments = false;
							$avatar_size = 64;
							$comment_length = 90;
							$comment_args = apply_filters(
								'wpt_comments_tab_args',
								array(
									'type' => 'comments',
									'status' => 'approve'
								)
							);
							$comments_total = new WP_Comment_Query();
							$comments_total_number = $comments_total->query( array_merge( array('count' => 1 ), $comment_args ) );
							$last_page = (int) ceil($comments_total_number / $comment_num);
							$comments_query = new WP_Comment_Query();
							$offset = ($page-1) * $comment_num;
							$comments = $comments_query->query( array_merge( array( 'number' => $comment_num, 'offset' => $offset ), $comment_args ) );
							if ( $comments ) : foreach ( $comments as $comment ) : ?>

							<li>
								<a href="<?php echo get_comment_link($comment->comment_ID); ?>">
									<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
									<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
									<?php echo convert_smilies($comment->comment_content); ?>
								</a>
							</li>

							<?php endforeach; else : ?>
								<li><?php _e('暂无留言', 'begin'); ?></li>
								<?php $no_comments = true;
							endif; ?>
						</ul>
					</div>
	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Latest review ---------- */
				case "review":
					?>

					<?php
						$review = new WP_Query( array(
							'post_type' => array( 'post' ),
							'showposts' => $post_num,
							'ignore_sticky_posts' => true,
							'orderby' => 'comment_count',
							'post_status' => 'publish',
							'order' => 'dsc',
							'paged' => $page,
							'date_query' => array(
								array(
									'after' => ''.$review_days. 'month ago',
								),
							),
						) );
					?>

					<ul>
						<h4><?php _e( '热评文章', 'begin' ); ?></h4>
						<?php while ( $review->have_posts() ): $review->the_post(); ?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
						<?php } else { ?>
							<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
						<?php } ?>
						<div class="clear"></div>
					</li>
						<?php endwhile;?>
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;
			} 
			die(); // required to return a proper result
		}
		function tab_pagination($page, $last_page) {
			?>
			<div class="wpz-pagination">
				<div class="clear"></div>
				<?php if ($page > 1) : ?>
					<a href="#" class="previous"><span><i class="be be-arrowleft"></i><?php _e('上页', 'begin'); ?></span></a>
				<?php endif; ?>
				<?php if ($page != $last_page) : ?>
					<a href="#" class="next"><span><?php _e('下页', 'begin'); ?><i class="be be-arrowright"></i></span></a>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
			<?php 
		}
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "wpz_widget" );' ) );

// 今日更新
class mday_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'mday_post',
			'description' => __( '今日发表的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('mday_post', '今日更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<ul>
		<?php
		$today = getdate();
		$args = array(
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
			'posts_per_page' => $number,
		);
		$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail"><?php zm_thumbnail(); ?></span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<span class="widget-cat"><i class="be be-folder"></i><?php zm_category(); ?></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="date"><?php echo $showtime=date("m/d");?></span>
			<span class="new-title-no">暂无更新</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '今日更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "mday_post" );' ) );

// 本周更新
class week_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'week_post',
			'description' => __( '本周更新的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('week_post', '本周更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<ul>
		<?php
			$args = array(
				'date_query' => array(
					array(
						'year' => date( 'Y' ),
						'week' => date( 'W' ),
					),
				),
				'posts_per_page' => $number,
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
			<li>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail"><?php zm_thumbnail(); ?></span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="s-cat"><?php zm_category(); ?></span>
					<span class="date"><?php the_time('m/d') ?></span>
				<?php } else { ?>
					<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no">暂无更新</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本周更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "week_post" );' ) );

// 指定文章
class specified_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'specified_post',
			'description' => __( '调用指定时间内的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('specified_post', '指定时间文章', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="new_cat">
	<ul>
		<?php
			$args = array(
				'date_query' => array(
					array(
						'after'     =>  array(
							'year'  => $instance['from_y'],
							'month' => $instance['from_m'],
							'day'   => $instance['from_d'],
						),
						'before'    => array(
							'year'  => $instance['to_y'],
							'month' => $instance['to_m'],
							'day'   => $instance['to_d'],
						),
						'inclusive' => true,
					),
				),
				'posts_per_page' => $instance['numposts'],
				'cat' => $instance['sp_cat'],
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
		<li>
			<span class="thumbnail"><?php zm_thumbnail(); ?></span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
			<span class="date"><?php the_time('m/d') ?></span>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no">暂无文章</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['sp_cat'] = $new_instance['sp_cat'];
	$instance['from_y'] = $new_instance['from_y'];
	$instance['from_m'] = $new_instance['from_m'];
	$instance['from_d'] = $new_instance['from_d'];
	$instance['to_y'] = $new_instance['to_y'];
	$instance['to_m'] = $new_instance['to_m'];
	$instance['to_d'] = $new_instance['to_d'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '指定时间文章',
		'numposts' => 5,
		'from_y' => 2017,
		'from_m' => 1,
		'from_d' => 2,
		'to_y' => 2017,
		'to_m' => 5,
		'to_d' => 31,
		'sp_cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<h4 class="from_m_options_header"><?php _e('输入起止日期', 'begin-tab'); ?></h4>

		<p>
			<label for="<?php echo $this->get_field_id('from_y'); ?>" style="width: 33%;">从 
			<input id="<?php echo $this->get_field_id('from_y'); ?>" name="<?php echo $this->get_field_name('from_y'); ?>" type="text" value="<?php echo $instance['from_y']; ?>" size="3" /> 年 
			</label>
			<label for="<?php echo $this->get_field_id('from_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_m'); ?>" name="<?php echo $this->get_field_name('from_m'); ?>" type="text" value="<?php echo $instance['from_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('from_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_d'); ?>" name="<?php echo $this->get_field_name('from_d'); ?>" type="text" value="<?php echo $instance['from_d']; ?>" size="3" />日起
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('to_y'); ?>" style="width: 33%;">至 </label>
			<input id="<?php echo $this->get_field_id('to_y'); ?>" name="<?php echo $this->get_field_name('to_y'); ?>" type="text" value="<?php echo $instance['to_y']; ?>" size="3" /> 年 
			<label for="<?php echo $this->get_field_id('to_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_m'); ?>" name="<?php echo $this->get_field_name('to_m'); ?>" type="text" value="<?php echo $instance['to_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('to_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_d'); ?>" name="<?php echo $this->get_field_name('to_d'); ?>" type="text" value="<?php echo $instance['to_d']; ?>" size="3" /> 日止
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sp_cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('sp_cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['sp_cat'])); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "specified_post" );' ) );


// 产品
class show_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'show_widget',
			'description' => __( '调用产品文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('show_widget', '最新产品', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture img_widget">
	<?php
	    $args = array(
	        'post_type' => 'show',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'products',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				<span class="show-t"></span>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新产品';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
		$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'products', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 父子分类
function get_category_related_id($cat) {
	$this_category = get_category($cat);
	while($this_category->category_parent) {
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;
}

// 父子分类名称
class child_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'child_cat',
			'description' => __( '用于显示当前文章和分类父子分类名称' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('child_cat', '父子分类', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if(get_category_children(get_category_related_id(the_category_ID(false)))!= "" ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
?>

<?php if(!is_page()) { ?>
<?php if(get_category_children(get_category_related_id(the_category_ID(false)))!= "" ) { ?>
	<div class="widget_categories related-cat">
		<?php
			echo '<ul class="cat_list">';
			echo wp_list_categories("child_of=".get_category_related_id(the_category_ID(false)). "&depth=0&hide_empty=0&use_desc_for_title=&hierarchical=0&title_li=&orderby=id&order=ASC");
			echo '</ul>';
		?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			// $instance['author_url'] = $new_instance['author_url'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '父子分类';
		}
		global $wpdb;
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "child_cat" );' ) );

// 同分类文章
class same_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'same_post',
			'description' => __( '调用相同分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('same_post', '同分类文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( is_single() ) {
			if ( is_single() ) : global $post; $categories = get_the_category(); foreach ($categories as $category) : 
			$title =  $category->name;
			endforeach; endif;
			wp_reset_query();
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		}
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 10;
		$orderby = strip_tags($instance['orderby']) ? absint( $instance['orderby'] ) : ASC;
?>

<?php if ( is_single() ) { ?>
<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php
		if ( is_single() ) : global $post; $categories = get_the_category(); foreach ($categories as $category) : ?>
			<?php $posts = get_posts('numberposts='.$instance['number'].'&order='.$instance['orderby'].'&category='. $category->term_id); foreach($posts as $post) : ?>
			<?php if($instance['show_thumbs']) { ?>
			<li class="cat-title">
			<?php } else { ?>
			<li>
			<?php } ?>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="date"><?php the_time('m/d') ?></span>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
			</li>
			<?php endforeach; ?>
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
	</ul>
	<div class="clear"></div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['orderby'] = strip_tags($new_instance['orderby']);
			return $instance;
		}
	function form($instance) {
		global $wpdb;
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args((array) $instance, array('number' => '10'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('orderby' => 'ASC'));
		$orderby = strip_tags($instance['orderby']);
?>
	<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>">文章排序：</label>
		<input id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" type="text" value="<?php echo $orderby; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "same_post" );' ) );

// 幻灯小工具
class slider_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'slider_post',
			'description' => __( '以幻灯形式调用指定的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('slider_post', '幻灯', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$postid = $instance['post_id'];
?>

<div class="widge-slider">
	<ul id="slider-s">
		<?php query_posts( array ( 'post__in' => explode(',',$postid), 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
		<span>
			<?php zm_widge_thumbnail(); ?>
			<?php the_title( sprintf( '<p class="widge-caption"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></p>' ); ?>
		</span>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['post_id'] = strip_tags($new_instance['post_id']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '幻灯';
		}
		global $wpdb;
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
	<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "slider_post" );' ) );

// 同标签的文章
class tag_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tag_post',
			'description' => __( '调用相同标签的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('tag_post', '同标签文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$tag_id = strip_tags($instance['tag_id']);
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="tag_post">
<?php } ?>
	<ul>
		<?php $recent = new WP_Query( array( 'posts_per_page' =>$number, 'tag__in' => explode(',', $tag_id)) ); ?>
		<?php while($recent->have_posts()) : $recent->the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['tag_id'] = strip_tags($new_instance['tag_id']);
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '同标签文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$tag_id = strip_tags($instance['tag_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tag_id'); ?>">标签 ID：</label>
		<input class="widefat"  id="<?php echo $this->get_field_id( 'tag_id' ); ?>" name="<?php echo $this->get_field_name( 'tag_id' ); ?>" type="text" value="<?php echo $tag_id; ?>" size="15" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "tag_post" );' ) );


// 调用指定ID文章
class ids_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ids_post',
			'description' => __( '调用指定ID的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('ids_post', '指定文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$id_post = strip_tags($instance['id_post']);
?>


<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="ids_post">
<?php } ?>
	<ul>
		<?php 
		$args = array(
		    'post__in'   => explode(',', $id_post)
		);
		query_posts($args)
		 ?>
		<?php while (have_posts()) : the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<i class="be be-arrowright"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="date"><?php the_time('m/d') ?></span>
				<span class="widget-cat"><i class="be be-folder"></i><?php zm_category(); ?></span>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['id_post'] = strip_tags($new_instance['id_post']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '指定文章';
		}
		global $wpdb;
		$id_post = strip_tags($instance['id_post']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('id_post'); ?>">文章 ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id_post' ); ?>" name="<?php echo $this->get_field_name( 'id_post' ); ?>" type="text" value="<?php echo $id_post; ?>" size="15" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "ids_post" );' ) );

// 折叠菜单小工具
class tree_menu extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'description' => __( '添加可折叠的自定义菜单。' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'tree_menu', __('折叠菜单'), $widget_ops );
	}
	public function widget( $args, $instance ) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget'];
		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		$nav_menu_args = array(
			'fallback_cb' => '',
			'theme_location'	=> 'widget-tree',
			'menu_class'		=> 'tree-menu',
			'menu'        => $nav_menu
		);
		
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	public function form( $instance ) {
		global $wp_customize;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
				<p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) { echo 'display: none;'; } ?>">
					<button type="button" class="button"><?php _e( 'Edit Menu' ) ?></button>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "tree_menu" );' ) );

// 分类模块
class t_img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 't_img_cat',
			'description' => __( '显示全部分类或某个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('t_img_cat', '分类模块', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="w-img-cat">
		<?php $q = 'showposts=1&ignore_sticky_posts=1'; if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
			<figure class="w-thumbnail"><?php if (zm_get_option('lazy_s')) { zm_long_thumbnail_h(); } else { zm_long_thumbnail(); } ?></figure>
			<div class="w-img-title"><?php the_title( sprintf( '<ul><li><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li></ul>' ); ?></div>
		<?php endwhile; ?>
	<ul>
		<?php $q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1&offset=1'; if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
		<li><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>

<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "t_img_cat" );' ) );





// 第一屏幻灯片右边数据模块
class f_silde_r extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'f_silde_r',
			'description' => __( '显示全部分类或某个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('f_silde_r', '第一屏幻灯片右边数据模块', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="top_box">
<?php
 $q = 'showposts=1&ignore_sticky_posts=1'; 
 if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; 
 query_posts($q); 
 while (have_posts()) : the_post(); ?>
						<div class="left_box">
							<?php img_thumbnail();?>
						</div>
						<div class="right_box hid">					                                                  
                             <ul>
                                 <li>
                                 	<a  class="subtitle"  href="<?php the_permalink()?>">
                                    <?php the_title();?></a>
								</li>
		<?php endwhile; ?>

		<?php 
		$q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1&offset=1'; 
		if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat']; 
		query_posts($q); 
		while (have_posts()) : the_post(); ?>
				<li><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
				
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
							</ul>						                           			
                         </div>	
					</div>
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {

	if($new_instance['numposts']>5){$new_instance['numposts']=5;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',
		'titleUrl' => '',
		'numposts'=>'5',
        'newWindow'=>'',
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(注意最多六篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "f_silde_r" );' ) );


// 纯标题数据
class cbt_data extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cbt_data',
			'description' => __( '显示全部分类或某个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('cbt_data', '纯标题数据', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

					<div class="bottom_box boxli">
						<ul>
		<?php 
		$q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1'; 
		if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat'];
	    query_posts($q); while (have_posts()) : the_post(); ?>
						<li>
							<strong class="liststyle">.</strong>		
							<a href="<?php the_permalink() ?>" class="subtitle_text"><?php the_title();?>|</a><span class="subtitle_text"><?php the_time('m-d')?>&nbsp;&nbsp;</span>
						</li>
				
		<?php endwhile;?>
		<?php wp_reset_query();?>

 	</ul>
	</div>                 
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>5){$new_instance['numposts']=5;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
    	'numposts'=>'5',
		'cat' => 0));
		 ?> 
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(注意最多5篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>

<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "cbt_data" );' ) );



//第一幻灯片下面的图片
class sidebar_picture extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sidebar_picture',
			'description' => __( '显示某个分类的图片和标题' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sidebar_picture', '纯图片标题', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

					<div class="bottom_box boxli">
						<ul>
		<?php 
		$q = 'showposts='.$instance['numposts'].'&ignore_sticky_posts=1&order='.$instance['order'].'&orderby='.$instances['orderby']; 
		if (!empty($instance['cat'])) $q .= '&category__and='.$instance['cat'];
	    query_posts($q); while (have_posts()) : the_post(); ?>	
				<div class="img_box">
					<a href="<?php the_permalink()?>"><?php img_thumbnail();?></a>
					<p><a href="<?php the_permalink()?>"><?php the_title();?>   <?php echo $instance['orderby']?></a></p>
				</div>

		<?php endwhile; ?>
		<?php wp_reset_query(); ?>

 	</ul>
					</div>                 
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>4){$new_instance['numposts']=4;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'numposts' => 4,
		'order'=>'ASC',
		'cat' => 22));
		$order = $instance['order'];
		 ?> 
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
	    <p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>

<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "sidebar_picture" );' ) );



//显示第二屏
class sb_two_top1 extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_two_top1',
			'description' => __( '显示第二屏' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_two_top1', '第二屏', $widget_ops);
	}
	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;

		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>      				            
					<!-- 圣贤德育子类目导航开始 -->		
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
		                            <?php
				                 $cat=$instance['cat']	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 圣贤德育子类目导航结束 -->			                 
						</ul>
					</div>	
				</div>	
				<div class="left_top_box">
					<div class="left_box">
<!-- 圣贤德育左边图片开始 -->	
						        <?php 
		                             $arg = array( 
		                               
	                                 'showposts'=>1,
                                     'cat' =>$instance['cat'],
                                     );
                                 query_posts($arg);	  
                                 if (have_posts()){
                                 while (have_posts()) { the_post(); ?>
                                 <?php img_thumbnail();?>
                                 <?php
                                 }
                                 }
                                 ?>
<!-- 圣贤德育左边图片结束 -->	                                
					</div>
					<div class="right_box rli">
<!-- 圣贤德育右边列表开始 -->	 
                     <?php  
	                     $args = array(
	                            'ignore_sticky_posts'=>1,
	                             'showposts'=>3,
                                 'cat' => $instance['cat']);
                             query_posts($args);		  
                        if (have_posts()){
	                             $n=1;
                        while (have_posts()) { the_post(); 
	                           if($n==1){
                        	?>
						<div class="rli_title"><p> <a class="rli_title_a" href="<?php the_permalink()?>"><?php the_title()?></a></p><span class="rli_title_s"><?php the_excerpt()?></span>
						         <?php
	                                 }else{
		                         ?>
						
							<ul>
								<li><div class="circle_label"></div><a href="<?php the_permalink()?>"><?php the_title() ?></a><span class="subtitle_text fr_time"><?php the_time('m-d')?>&nbsp;&nbsp;</span></li>		
							</ul>
						
						 <?php
	                     }
	                         ++$n;
                         }
                         }
                         ?>
<!-- 圣贤德育右边列表结束 -->	 
					</div>
			</div>
				<p></p>
				<div class="shengxdy">
<!-- 圣贤德育下边列表开始 -->	 
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==7){ ?>
						   	 <div class="sxdy_li">
						<ul>
							<li class="shengxdy_pic">
								<?php img_thumbnail();?>
							</li>
							 <?php
                             }elseif($nu==13){
                             ?> 
                             <div class="sxdy_li" id="wei">
						<ul> 
							<li class="shengxdy_pic">
								<?php img_thumbnail();?>
							</li>
							 <?php
                             }else{
                             ?>	
                            
							<li class="subtitle_text"><strong class="liststyle1">.</strong><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==6||$nu==12||$nu==18){ ?>
						</ul>
					</div>
					          <?php }else if($nu==$instance['numposts']){?>
                        </ul>
					</div>
					<?php
				        }else{}
                             
                             ?> 
                     <?php ++$nu;}}?>
<!-- 圣贤德育下边列表结束 -->	 
				</div>
	   </div>
	   			<div class="fjgc_right_box daks">
				<ul class="kaishi_box">
					<li class="redcolor ziti"><a href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a></li>
					<hr />
<!-- 大德开示下边列表开始 -->	 
					<?php $guancha_list=array(
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($guancha_list);
                         if (have_posts()){
                         while (have_posts()) { the_post(); ?>
                         <li class="kais_li"><strong class="liststyle2">.</strong><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                        <?php
                         }
                         }
                    ?>	
<!-- 大德开示下边列表结束 -->	 									
				</ul>
			</div>

		
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>18){$new_instance['numposts']=18;}else{}
	if($new_instance['numposts1']>15){$new_instance['numposts1']=15;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['hideTitle1'] = isset($new_instance['hideTitle1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '',
		'titleUrl' => '',
		'newWindow'=>'',
		'numposts' => 4,
		'order'=>'',
		'cat' =>'22',

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' => 4,
		'order1'=>'',
		'cat1' =>'22'));
		$titleUrl = $instance['titleUrl'];
		$titleUrl1 = $instance['titleUrl1'];
		 ?> 
		 <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多18篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多15篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>		    
	    </p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_two_top1" );' ) );







// 第三屏
class sb_three extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_three',
			'description' => __( '显示第三屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_three', '第三屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a  href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 智慧心语子类目开始 -->	
                            <?php
				                 $cat=$instance['cat']	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 智慧心语子类目结束-->				                 
						</ul>
					</div>
				</div>
			</div>
				<hr>
		</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_top">
<!-- 智慧心语图片开始 -->
                         <?php $list_num=array(  
	                          'showposts'=>5,
	                          'order'=>$instance['order'],
                              'cat' => $instance['cat']);
                              query_posts($list_num);
                              if (have_posts()){?>
                         <ul>
                              <?php while (have_posts()) { the_post(); ?>
                             <li>
                                 <a href="<?php the_permalink()?>">
                                 <?php img_thumbnail();?>
                                 <h3>
                                 <?php the_title();?></h3>
                                 </a>
                             </li>
                             <?php
                              } ?>
                         </ul>
                         <?php
                             }
                      ?>
                </div>
				<div class="zpyl_left_bottom">               
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==8||$nu==15){ ?>
					 <div class="tb_bo">
						<ul> 
							 <?php
                             }else{
                             ?> 								
							<li><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==7||$nu==14||$nu==21){ ?>
						</ul>
					</div>
					<?php }else if($nu==$instance['numposts']){?>
					    </ul>
					</div>
					<?php
                          }else{}
                              
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 智慧心语文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right tb_right">	
			     <h3>
                  <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/><div class="tuju">
<!-- 佛教经典开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul> 
                     	<li><a href="<?php the_permalink()?>"><?php img_thumbnail();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 佛教经典结束 -->                     
			     </div>		
			</div>				

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>21){$new_instance['numposts']=21;}else{}
	if($new_instance['numposts1']>3){$new_instance['numposts']=3;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['order'] = $new_instance['order'];
	$instance['cat'] = $new_instance['cat'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['hideTitle1'] = isset($new_instance['hideTitle1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['order1'] = $new_instance['order1'];
	$instance['cat1'] = $new_instance['cat1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',		
		'titleUrl' => '',		
		'numposts' => 5,
	    'newWindow' => 0,
	    'order'=>'',
		'cat' => 0,	

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' => 3,
		'order1'=>'',
		'cat1' =>'0'));
		$titleUrl = $instance['titleUrl'];
		$titleUrl1 = $instance['titleUrl1'];
		 ?> 
        <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多18篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示图片数量(最多3张)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>		    
	    </p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_three" );' ) );






//第四屏
class sb_four extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_four',
			'description' => __( '显示第四屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_four', '第四屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 观音菩萨子类目开始 -->	
                            <?php
				                 $cat=$instance['cat'];
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 观音菩萨子类目结束-->				                 
						</ul>
					</div>
				</div></div>
				<hr>
				
			</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_top">
<!-- 观音菩萨图片开始 -->
                         <?php $list_num=array(  
	                          'showposts'=>5,
	                          'order'=>$instance['order'],
                              'cat' => $instance['cat'],);
                              query_posts($list_num);
                              if (have_posts()){?>
                         <ul>
                              <?php while (have_posts()) { the_post(); ?>
                             <li>
                                 <a href="<?php the_permalink()?>">
                                 <?php img_thumbnail();?>
                                 <h3 class="fob_h3">
                                 <?php the_title();?></h3>
                                 </a>
                             </li>
                             <?php
                              } ?>
                         </ul>
                         <?php
                             }
                         ?>  
<!-- 观音菩萨图片结束 -->
				</div>
				<div class="zpyl_left_bottom">
<!-- 观音菩萨文章列表开始 -->
                     
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==6||$nu==11||$nu==16){ ?>
					 <div class="fob_list">
						<ul> 
							 <?php
                             }else{
                             ?> 								
							<li><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==5||$nu==10||$nu==15||$nu==20){ ?>
						</ul>
					</div>
					          <?php }else if($nu==$instance['numposts']){?>
						</ul>
					</div>					          
					<?php
                             } else{}
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 观音菩萨文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right fo_left">	
			     <h3>
                   <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 佛法圣迹开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); $n==1; ?>	
                     <ul class="fo_lf_ul"> 
                     	<li><a href="<?php the_permalink()?>"><?php the_title();?></a></li>                     	
                     </ul>
                          <?php if($n==$instance['numposts1']-1){?>
                          <div><?php img_thumbnail(); ?></div>
                          <?php if($n==$instance['numposts1']-1) {}}?>
                     <?php $n++; } }?>
<!-- 佛法圣迹结束 -->                     
			     </div>	
			     	
			</div>	

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>20){$new_instance['numposts']=20;}else{}
	if($new_instance['numposts1']>9){$new_instance['numposts1']=9;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',		
		'titleUrl' => '',		
		'numposts' =>'',
		'newWindow'=>'',
		'order'=>'',
		'cat' => 0,

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' => 4,
		'order1'=>'',
		'cat1' =>'22'));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 
         <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开左边标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多20篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		 <p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多9篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>		  

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_four" );' ) );






//第五屏
class sb_five extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_five',
			'description' => __( '显示第五屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_five', '第五屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 学佛受用子类目开始 -->	
                            <?php
				                 $cat=$instance['cat']	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 学佛受用子类目结束-->				                 
						</ul>
					</div>
				</div>
			</div>
				<hr>
				
			</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_bottom xuefsy">
<!-- 学佛受用文章列表开始 -->                     
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         
                         'ororder'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==6||$nu==11||$nu==16){ ?>
					 <div class="fjwn" >
					 	     <div class="fjwm_tupian">
					 	         <?php img_thumbnail();?>
					 	     </div>
						<ul> 

							 <?php
                             }else{
                             ?> 								
							<li><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==5||$nu==10||$nu==15||$nu==20){ ?>
						</ul>
					</div>
                              <?php }else if($nu==$instance['numposts']){ ?>
						</ul>
					</div>
					<?php
                        }else{} 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 学佛受用文章列表结束 -->
				</div>
			</div>
					
		</div>
			<div class="zpyl_left_right fi_right">	
			     <h3 class="zpyl_left_right_top" >
                   <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 素味人生开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul class="fi_ri_ul"> 
                     	<li class="fi_ri_li"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 素味人生结束 -->                     
			     </div>	
			</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
    if($new_instance['numposts']>20){$new_instance['numposts']=20;}else{}
	if($new_instance['numposts1']>10){$new_instance['numposts1']=10;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['order'] = $new_instance['order'];
	$instance['cat'] = $new_instance['cat'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',		
		'titleUrl' => '',		
		'numposts' => '',
		'newWindow'=>'',
		'order'=>'',	
		'cat' => 0,

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' => 4,
		'order1'=>'',
		'cat1' =>'22'));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 
        <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多20篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多10篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>	
		</p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_five" );' ) );



//第六屏
class sb_six extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_six',
			'description' => __( '显示第六屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_six', '第六屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
			
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 学佛修行子类目开始 -->	
                            <?php
				                 $cat=$instance['cat'];
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 学佛修行类目结束-->				                 
						</ul>
					</div>
				</div>
			</div>
				<hr>
				
			</div>
			<div class="zpyl_left xfxx_box sb_li" >
				<div class="zpyl_left_bottom xuefxx">
<!-- 学佛修行图片文章列表开始 -->                    
					 <?php


					  $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==5||$nu==9||$nu==13){ ?>
					 <div class="xx_lb">
					 	<div class="xufo_tupian">
					 		 <?php img_thumbnail()?>
					 		 <div class="xufo_tupian_title">
								 
								 <a href="<?php the_permalink()?>"><?php the_title()?></a>
					 		       <?php the_excerpt()?>
					 	     </div>
					 	 </div>
						<ul class="sb_ul"> 							
							 <?php
                             }else{
                             ?> 								
							<li><a href="<?php the_permalink()?>"><?php the_title();?></a><span class="sb_span"><?php the_time('m-d')?></span></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==4||$nu==8||$nu==12||$nu==16){ ?>
						</ul>
					</div>
                              <?php }else if($nu==$instance['numposts']){ ?>
						</ul>
					</div>
					<?php
                        }else{} 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 学佛修行图片文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right si_le">	
			     <h3 class="" >
                   <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 西方极乐开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); $n==1;?>	                    
                     <ul class="si_le_ul"> 
                     	<li class="si_le_li"><a href="<?php the_permalink()?>"><?php the_title();?></a>
                     </ul>
                          <?php if($n==$instance['numposts1']-1){?>
                          <div><?php img_thumbnail(); ?></div>
                          <?php if($n==$instance['numposts1']-1) {}}?>
                     <?php $n++; } }?>
<!-- 西方极乐结束 -->                        		 
                 </div>	
			     	
			</div>					
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>16){$new_instance['numposts']=16;}else{}
	if($new_instance['numposts1']>10){$new_instance['numposts1']=10;}else{}	
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',		
		'titleUrl' => '',	
		'numposts' => 5,
	    'newWindow'=>'',
	    'order'=>'',
		'cat' => 0,

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' =>'',
		'order1'=>'',
		'cat1' =>''));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 
        <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开左边标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多16篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
        <p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多10篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>
		</p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_six" );' ) );





//第七屏
class sb_seven extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_seven',
			'description' => __( '显示第七屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_seven', '第七屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 佛教视频子类目开始 -->	
                            <?php
				                 $cat=$instance['cat']	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 佛教视频类目结束-->				                 
						</ul>
					</div>
				</div>
			</div>
				<hr>
				
			</div>
			<div class="shipin"  >
<!-- 佛教视频列表开始 -->
                 <ul>    
					 <?php $list_num=array( 
					    
                         'showposts'=>8,
                         'orderby'=>'guid','post_type' => 'video');
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
					    <li>
					    	<div class="evl_sp">
					 	         <div class="shipin_tupian">
					                 <?php img_thumbnail()?></div>
					 		     <div class="evl_spi"><a href="<?php the_permalink()?>"><?php the_title()?></a>
					 	         </div>
					 	    </div>    
					     </li>
                     <?php }}?>                    
<!-- 佛教视频列表结束 -->		
			     </ul>
			</div>
			<div class="zpyl_left_right evl_right">	
			     <h3 class="" >
                   <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 佛教广播开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul class="evl_right_ul"> 
                     	<li class="evl_right_li"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 佛教广播结束 -->                     
			     </div>	
			</div>								
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>8){$new_instance['numposts']=8;}else{}
	if($new_instance['numposts1']>10){$new_instance['numposts1']=10;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',	
		'titleUrl' => '',		
		'numposts' => 5,
		'newWindow'=>'',
	    'order'=>'',
		'cat' => 0,

		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' =>'',
		'order1'=>'',
		'cat1' =>'4'));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 
        <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示视频数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多10篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>	
		</p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_seven" );' ) );




//第八屏
class sb_eight extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_eight',
			'description' => __( '显示第八屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_eight', '第八屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;

		$title1 = empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;		
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
        	
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 放生子类目开始 -->	
                            <?php
				                 $cat=$instance['cat'];
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 放生类目结束-->				                 
						</ul>
					</div>
				</div>
			</div>
				<hr>
                <div class="fs_ss_bottom fs_gg">
<!-- 放生列表开始 -->	                	
                	  <?php 
                	  $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                         $nu=1;
                     if (have_posts()){
                     while (have_posts()) { the_post(); 
                     	if($nu==1||$nu==2){?>
                             <div class="fs_ss_tupian"><?php img_thumbnail()?> 
                                    <div class="fs_ss_title"> <a  href="<?php the_permalink()?>"><?php the_title()?></a></div>
                             </div>
                         <?php }else{ if($nu==3){?>
                              <ul class="fs_ss_list"><?php }?>
                             	 <li>
                             		<a href="<?php the_permalink()?>"><?php the_title()?></a>
                                </li>                             
                     
                     <?php }if($nu==14){?></ul><?php }else if($nu==$instance['numposts']){?></ul>

                     <?php }else{}++$nu; }}?>
<!-- 放生列表结束 -->	                         
                </div>
        	</div>
        	<div class="ss eight_right">
        			<div class="left_title">
					<div class="fjgc_icon">
						<img class="ei_ri_img" src="./wp-content/themes/begin/img/fjw/icon.jpg">
						<span class="ei_span"><a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a></span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl1;?>" target='_blank'>更多&gt;&gt;</a></li>
<!--  素食子类目开始 -->	
                            <?php
				                 $cat=$instance['cat1'];
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
									 
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 素食类目结束-->				                 
						</ul>
					</div>
				</div>
				<hr>
         		<div class="fs_ss_bottom sushi">
<!-- 素食列表开始 -->	    
                	  <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                         $nu=1;
                     if (have_posts()){
                     while (have_posts()) { the_post(); 
                     	if($nu==1||$nu==2){?>
                             <div class="fs_ss_tupian"><?php img_thumbnail()?> 
                                    <div class="fs_ss_title"> <a  href="<?php the_permalink()?>"><?php the_title()?></a></div>
                             </div>
                         <?php }else{ if($nu==3){?>
                              <ul class="fs_ss_list"><?php }?>
                             	 <li>
                             		<a href="<?php the_permalink()?>"><?php the_title()?></a>
                                </li>                             
	                     <?php }if($nu==14){?></ul><?php }else if($nu==$instance['numposts']){?></ul>

                     <?php }else{}++$nu; }}?>
 <!-- 素食列表结束 -->	                                			
         		</div>
        	</div>							
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>14){$new_instance['numposts']=14;}else{}
	if($new_instance['numposts1']>14){$new_instance['numposts1']=14;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['cat'] = $new_instance['cat'];
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['order'] = strip_tags($new_instance['order']);
	$instance['numposts'] = strip_tags($new_instance['numposts']);

	$instance['cat1'] = $new_instance['cat1'];
	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['order1'] = strip_tags($new_instance['order1']);	
	$instance['newWindow1'] = isset($new_instance['newWindow1']);	
	$instance['numposts1'] = strip_tags($new_instance['numposts1']);
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',
		'title1' => '分类模块',
		'titleUrl' => '',
		'titleUrl1' => '',
		'numposts' => '',
		'numposts1' => '',
	    'newWindow'=>'',
	    'newWindow1'=>'',
	    'order'=>'',
	    'order1'=>'',
	    'cat1'=>0,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 

		<p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多14篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">左边内容选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多14篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>	
		</p>

<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_eight" );' ) );



//第九屏
class sb_nine extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_nine',
			'description' => __( '显示第九屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_nine', '第九屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
  

					<div class="dade">
						<ul>
							
<!--  大德开示子类目开始 -->	
                            <?php
				                 $cat=$instance['cat'];
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 大德开示类目结束-->	<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>			                 
						</ul>
					</div>
				</div>
				<hr>
         		<div class="fjwd_content">
<!-- 大德开示列表开始 -->	    
                      <div class="fjwd_content_left ma">
                	     <?php 
                	     if($instance['numposts']>5){$num1=5;}else{$num1=$instance['numposts'];}
                	     $list_num=array(  
                             'showposts'=>$num1,
                             'order'=>$instance['order'],
                             'cat' => $instance['cat']);
                             query_posts($list_num);
                             $nu=1;
                             if (have_posts()){
                                 while (have_posts()) { the_post(); 
                     	             if($nu==1){?>
                                         <div class="fjwd_top botop">                                      	
                                            <div class="fjwd_title boti"> <a  href="<?php the_permalink()?>"><?php the_title()?></a></div>
                                            <?php img_thumbnail()?> 
                                             <div class="fjwd_content boli"><?php the_excerpt()?></div>
                                         </div>
                                         <hr>
                                     <?php }else{ if($nu==2){?>
                                          <ul class="fjwd_list"><?php }?>
                             	             <li>

                             		             <a href="<?php the_permalink()?>"><?php the_title()?></a>

                             		             <span class="fjc_sp"><?php the_time('m-d')?></span>
                                             </li>                             
                                 
                                         <?php }if($nu==6){?></ul><?php }else if($nu==$instance['numposts']){?></ul><?php }else{} ++$nu;}}?>
                      </div>

                      <div class="fjwd_content_right">
                      	    <?php
                             if($instance['order']=="DESC"){$instance['order']="ASC";}else{$instance['order']="DESC";}
                          if($instance['numposts']>5){$num2=$instance['numposts']-5;}else{$num2=0;$instance['cat']=1011111111;}
                             $list_num=array(  
                             'showposts'=>$num2,
                             'order'=>$instance['order'],
                             'cat' => $instance['cat']);
                             query_posts($list_num);
                             $nu=1;
                             if (have_posts()){
                                 while (have_posts()) { the_post(); 
                     	             if($nu==1){?>
                                         <div class="fjwd_top botop">                                      	
                                            <div class="fjwd_title boti"> <a  href="<?php the_permalink()?>"><?php the_title()?></a></div>
                                            <?php img_thumbnail()?> 
                                             <div class="fjwd_content boli"><?php the_excerpt()?></div>
                                         </div>
                                         <hr>
                                     <?php }else{ if($nu==2){?>
                                          <ul class="fjwd_list" ><?php }?>
                             	             <li>
                             		             <a class="list1" href="<?php the_permalink()?>"><?php the_title()?></a>
                             		             <span class="fjc_sp"><?php the_time('m-d')?></span>
                                             </li>                             
                                          <?php }if($nu==6){?></ul><?php }else if($nu==$instance['numposts']){?></ul><?php }else{} ++$nu;}}?>
                      </div>
 <!-- 大德开示列表结束 -->	                                			
         		</div>        	


<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	if($new_instance['numposts']>10){$new_instance['numposts']=10;}else{}
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['order']=$new_instance['order'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',
		'newWindow'=>'',		
		'titleUrl' => '',	
		'numposts' => 5,
		'order'=>'',
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
	
		 ?> 
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多10篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>


<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_nine" );' ) );




//第十屏
class sb_ten extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'sb_ten',
			'description' => __( '显示第十屏内容' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('sb_ten', '第十屏', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$title1 =  empty($instance['title1']) ? '' : $instance['title1'];
		$titleUrl1 = empty($instance['titleUrl1']) ? '' : $instance['titleUrl1'];
		$newWindow1 = !empty($instance['newWindow1']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="<?php echo $titleUrl;?>" target='_blank'>更多&gt;&gt;</a></li>
<!-- 感应事迹子类目开始 -->	
                            <?php
				                 $cat=$instance['cat']	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 感应事迹类目结束-->				                 
						</ul>
					</div>
				</div>
				<hr>
				
			</div>
			<div class="shipin" >
<!-- 感应事迹列表开始 -->
                 <ul class="ten_ul">    
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts'],
                         'order'=>$instance['order'],
                         'cat' => $instance['cat']);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
					    <li >
					    	<div class="nb_d">
					 	         <div class="shipin_tupian">
					                 <?php img_thumbnail()?></div>
					 		     <div class="nb_d1"><a  href="<?php the_permalink()?>"><?php the_title()?></a>
					 	         </div>
					 	    </div>    
					     </li>
                     <?php }}?>                    
<!-- 感应事迹列表结束 -->		
			     </ul>
			</div>
			<div class="zpyl_left_right nb_right">	
			     <h3 class="zpyl_left_right_top" >
                   <a class="redcolor ziti" href="<?php echo $titleUrl1;?>" target='_blank'><?php echo $title1;?></a>
			     </h3>
			     <hr/>	
        	     <div>
<!-- 明信因果开始 -->
					 <?php $list_num=array(  
                         'showposts'=>$instance['numposts1'],
                         'order'=>$instance['order1'],
                         'cat' => $instance['cat1']);
                         query_posts($list_num);
                     if (have_posts()){
                     	$nu=1;
                     while (have_posts()) { the_post(); if($nu==1||$nu==14){?>	
                     <ul <?php if($nu==1){?>class="list_le"<?php }else{?>class="list_ri" <?php }?>> 
                          <?php }?>
                     	<li class="nb_li"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     	<?php if($nu==13||$nu==26){?>
                     </ul><?php }else if($nu==$instance['numposts1']){?>
                     </ul>
                     <?php }else{} ++$nu;} }?>
<!-- 明信因果结束 -->                     
			     </div>	
			</div>
<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	$instance['order'] = $new_instance['order'];

	$instance['title1'] = strip_tags($new_instance['title1']);
	$instance['titleUrl1'] = strip_tags($new_instance['titleUrl1']);
	$instance['newWindow1'] = isset($new_instance['newWindow1']);
	$instance['numposts1'] = $new_instance['numposts1'];
	$instance['cat1'] = $new_instance['cat1'];
	$instance['order1'] = $new_instance['order1'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',	
		'titleUrl' => '',	
		'numposts' => 5,
		'newWindow'=>'',
		'cat' => 0,
		'order'=>'',
		'title1' => '',
		'titleUrl1' => '',
		'newWindow1'=>'',
		'numposts1' => 4,
		'order1'=>'',
		'cat1' =>'22'));
		$titleUrl = $instance['titleUrl'];
	    $titleUrl1 = $instance['titleUrl1'];
		 ?> 

       <p><strong>以下为左侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数(最多8篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="DESC" <?php if($instance['order']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order'); ?>">降序 <?php echo $instance['order'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="ASC"  <?php if($instance['order']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order'); ?>">升序</label>		    
	    </p>
	    	    <br><hr><p><strong>以下为右侧内容</strong></p>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo $instance['title1']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl1'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl1'); ?>" name="<?php echo $this->get_field_name('titleUrl1'); ?>" type="text" value="<?php echo $titleUrl1; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow1'); ?>" name="<?php echo $this->get_field_name('newWindow1'); ?>" <?php checked(isset($instance['newWindow1']) ? $instance['newWindow1'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow1'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts1'); ?>">显示篇数(最多26篇)：</label> 
			<input id="<?php echo $this->get_field_id('numposts1'); ?>" name="<?php echo $this->get_field_name('numposts1'); ?>" type="text" value="<?php echo $instance['numposts1']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat1'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat1'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat1'])); ?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="DESC" <?php if($instance['order1']=="DESC"){ echo 'checked';} ?>   />
			<label for="<?php echo $this->get_field_id('order1'); ?>">降序 <?php echo $instance['order1'];?></label>
		</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order1'); ?>" value="ASC"  <?php if($instance['order1']=="ASC"){ echo 'checked';} ?>  />
			<label for="<?php echo $this->get_field_id('order1'); ?>">升序</label>	
		</p>


<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "sb_ten" );' ) );