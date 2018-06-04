<?php get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">
  <?php get_template_part( 'template/slider' ); ?>
  <?php
    wp_nav_menu( );
  ?>
  <div>佛教资讯</div>
  <?php
//WordPress loopposts retrieving from particular loop, steps
query_posts('cat=4');
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block">
    <?php img_thumbnail();?>
    <a href="<?php the_permalink()?>"  >
    <?php the_title();?>
    </ a></h3>
  <?php
}
}
?>
  <div>
    <hr />
    <?php 
query_posts('cat=4');
if (have_posts()){
while (have_posts()) { the_post(); ?>
    <h3 style="display:block"><a href="<?php the_permalink()?>"  >
      <?php the_title();?>
      </ a>
      <?php the_date('m-d')?>
    </h3>
    <?php
}
}
?>
  </div>
  <div>
    <?php query_posts('cat=4');
if (have_posts()){
while (have_posts()) { the_post(); ?>
    <h3 style="display:block"><a href="<?php the_permalink()?>">
      <?php img_thumbnail();?>
      </ a></h3>
    <?php
}
}
?>
  </div>
  
  <!--佛教观察-->
  <div>
    <div>
      <h2>佛教观察</h2>
      <span>
      <?php
				 $cat=5	;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				)); 
				foreach($cats as $the_cat){
					 
						echo '
						<a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a>';			
				}
			?>
      </span></div>
    <hr />
    <div class="up_box">
      <div class="left_img" style="float:left">
        <?php 
		  
	$arg = array(   
	'showposts'=>1,
     'cat' => 5,
);

query_posts($arg);	  
if (have_posts()){
while (have_posts()) { the_post(); ?>
        <?php img_thumbnail();?>
        <?php
}
}
?>
      </div>
      <div class="rt_list  style="float:left">
        <ul>
          <?php  
	$args = array(   
	'showposts'=>3,
     'cat' => 5,
);
query_posts($args);		  
if (have_posts()){
	$n=1;
while (have_posts()) { the_post(); ?>
          <?php
	  if($n==1){
	?>
          <li>
            <h2>
              <?php the_title()?>
            </h2>
            <div>
              <?php the_excerpt()?>
            </div>
          </li>
          <?php
	  }else{
		?>
          <li>
            <?php the_title() ?>
            <?php the_time('m-d')?>
          </li>
          <?php
	  }
	  ++$n;
}
}
?>
        </ul>
      </div>
    </div>
    <div style="margin-top:150px;">
      <hr />
    </div>
    <div class="down_box"> </div>
    <div class="down_box_pictures">
      <?php query_posts($args);
if (have_posts()){
while (have_posts()) { the_post(); ?>
      <h3 style="display:block;clear:both">
      <a href="<?php the_permalink()?>"><span>
      <?php img_thumbnail();?>
      </span> </a><span> </div>
    <div> </div>
    </span>
    </h3>
    <?php
}
}
?>
    <div class="down_box_list">
      <?php $list_num=array(  
	 'showposts'=>15,
     'cat' => 5,
);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
      <h3 style="display:block;clear:both">
      <a href="<?php the_permalink()?>"><span>
      <?php the_title();?>
      </span> </a><span> </div>
    <div> </div>
    </span>
    </h3>
    <?php
}
}
?>
  </div>
</div>
<div style="float:right">
  <h2>佛教问答</h2>
  <?php query_posts('cat=11');
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
    <?php the_title();?>
  </h3>
  <?php
}
}
?>
</div>

<!--宗教源流-->
  <div class="zongjiaoyuanliu" style="margin-top:100px;">
    <div>
      <h2>宗教源流</h2>
      <span>
      <?php
				 $cat=3	;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				)); 
				foreach($cats as $the_cat){
					 
						echo '
						<a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a>';			
				}
			?>
      </span></div>
    <hr />
    <div class="tupian">
      <?php $list_num=array(  
	 'showposts'=>5,
     'cat' => 3,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
   <?php img_thumbnail();?>
    <?php the_title();?>
  </h3>
  <?php
}
}
?>  
    </div>
    <div class="list">
          <?php $list_num=array(  
	 'showposts'=>15,
     'cat' => 3,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
    <?php the_title();?>
  </h3>
  <?php
}
}
?> 
    </div>
  </div>
  
<!--佛教专题-->
   <div><h2>佛教专题</h2><hr>
      <div>
             <?php $list_num=array(  
	 'showposts'=>4,
	 'orderby'=>guid,
     'cat' => 3,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
    <?php img_thumbnail();?>
  </h3>
  <?php
}
}
?> 
      </div>
   </div>
<!--佛教经典-->
  <div>
  <div>
      <h2>佛教经典</h2>
      <span>
      <?php
				 $cat=3;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				)); 
				foreach($cats as $the_cat){
					 
						echo '
						<a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a>';			
				}
			?>
      </span></div>
    <hr />
        <div class="tupian">
      <?php $list_num=array(  
	 'showposts'=>6,
     'cat' => 2,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
   <?php img_thumbnail();?>
    <?php the_title();?>
  </h3>
  <?php
}
}
?>  
    </div>
    <div class="list">
          <?php $list_num=array(  
	 'showposts'=>21,
     'cat' => 2,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
    <?php the_title();?>
  </h3>
  <?php
}
}
?> 
    </div>
  </div>
  
<!--推荐内容-->
   <div><h2>推荐内容</h2><hr>
      <div>
             <?php $list_num=array(  
	 'showposts'=>10,
	 'orderby'=>guid,
     'cat' => 2,);
 query_posts($list_num);
if (have_posts()){
while (have_posts()) { the_post(); ?>
  <h3 style="display:block"><a href="<?php the_permalink()?>"></a><span>
    <?php the_title();?>
  </h3>
  <?php
}
}
?> 
      </div>
    <img src=""/>
   </div>  
   <div>
          <h2>佛经五明</h2>
          <span>
      <?php
				 $cat=3;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				)); 
				foreach($cats as $the_cat){
					 
						echo '
						<a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a>';			
				}
			?>
      </span><hr/>
   </div>
   <div>
      <!-- 遍历 -->
       <?php $list_num=array(  
   'showposts'=>20,
   'orderby'=>guid,
     'cat' => 1);
 query_posts($list_num);
if (have_posts()){
  $nu=1;
while (have_posts()) { the_post(); 
  if($nu==1 ||  $nu==6  ||  $nu==11  ||  $nu==16){
 
  ?>
    <div >
        <div class="left_box">
         <?php img_thumbnail();?></div><ul class="left_box">
<?php
         }
  ?>    
          <li>  <?php the_title();?></li>
         <?php if($nu==6 ||  $nu==11  ||  $nu==16  ){
 
  ?>
        </ul>
     </div>
<?php
         }
  ?>
   
  <?php

  ++$nu;
}
}
?> 
    
   </div>
</main>
<!-- .site-main -->
</div>
<!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>