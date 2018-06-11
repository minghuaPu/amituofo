	<script type="text/javascript" src="./wp-content/themes/begin/js/fjw_commons.js"></script>

	<?php get_header(); 
	?>
<style>
	.main_box > div {background-color:#fff;width:1120px !important;padding:10px 0 0 10px;}
</style>
	<div class="main_box">
		<!-- 佛教公案 -->
		<div class="fjzx_box" style="width:70%;">
			<div class="fjzx_top_box">
				<div class="left_top_box">
					<?php get_template_part('template/slider'); ?>
				</div>
 <div class="right_top_box">
		<?php if ( ! dynamic_sidebar( 'first-right' ) ) : ?>
				 
		<?php endif; ?>
	 

				
				 
 <!-- 遍历佛教公案开始 -->
                    <?php $list_num=array(  
                         'showposts'=>8,
                         'orderby'=>guid,
                         'cat' => 22);
                    query_posts($list_num);
                         if (have_posts()){
                             $nu=1;
                         while (have_posts()) { the_post(); 
                             if($nu==1){
                     ?>
					<div class="top_box">
						<div class="left_box">
							<?php img_thumbnail();?>
						</div>
						<div class="right_box hid">						
                             <?php
                             }
                             ?>                            
                             <ul>
                                 <li>
                                 	<a <?php if($nu==1 ||$nu==5){echo 'class="subtitle"';}?> href="<?php the_permalink()?>">
                                    <?php the_title();?></a>
								</li>
							</ul>						 
                             <?php if($nu==4){
                             ?>					
                         </div>	
					</div>
                     <div class="middle_box">
						<div class="left_box">
							<?php img_thumbnail();?>
						</div>
						<div class="right_box hid">
                             <?php
                               }
                              ?> 

                         <?php
                           ++$nu;
                           }
                          }
                         ?></div> </div>
 <!-- 遍历佛教公案结束 -->
                     <br>
                     <hr>
					<div class="bottom_box boxli">
<!-- 遍历佛教公案列表开始 -->
						 <?php 
						     $list=array(  
                             'showposts'=>5,
                             'orderby'=>guid,
                             'cat' => 22);
                         query_posts($list);
                         if (have_posts()){?>
						<ul>

							<?php while (have_posts()) { the_post(); ?>
							<li>
								
								<a href="<?php the_permalink() ?>" class="subtitle_text"><?php the_title();?>|报恩祈福的最佳时机</a><span class="subtitle_text"><?php the_time('m-d')?>&nbsp;&nbsp;</span>
							</li>
                             <?php
                             }
                         }
                             ?>
 <!-- 遍历佛教公案列表结束 -->
						</ul>
					</div>
				</div>
			</div>
			<div class="fjzx_bottom_box fjbo">
<!-- 遍历佛教公案图片开始 -->
			 <?php 
					 $list=array(  
                         'showposts'=>4,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 22);
                     query_posts($list);
                      if (have_posts()){
                      while (have_posts()) { the_post(); ?>				
				<div class="img_box">
					<a href="#"><?php img_thumbnail();?></a>
					<p><a href="<?php the_permalink()?>"><?php the_title();?></a></p>
				</div>
                      <?php
                         }
                      }
                         ?>
<!-- 遍历佛教公案图结束 -->                         				
			</div>
		</div>
		
		<!-- 圣贤德育 -->
		<div class="fjgc_box" style="width:70%">
			<div class="fjgc_left_box">
				<div class="left_title">
					<div class="fjgc_icon" >
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style=" position: relative;left:-100px;width:40px;height:25px;">
						<span>圣贤德育</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="/wordpress/archives/category/guancha/jieluxiee/">更多&gt;&gt;</a></li>
<!-- 圣贤德育子类目导航开始 -->							
                            <?php
				                 $cat=149	;
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
				<hr>
				<div class="left_top_box">
					<div class="left_box">
<!-- 圣贤德育左边图片开始 -->	
						        <?php 
		                             $arg = array(   
	                                 'showposts'=>1,
                                     'cat' => 149,
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
	                             'showposts'=>3,
                                 'cat' => 149);
                             query_posts($args);		  
                        if (have_posts()){
	                             $n=1;
                        while (have_posts()) { the_post(); 
	                           if($n==1){
                        	?>
						<div style="height: 110px; margin-bottom:10px;margin-top:5px;"><p> <a style="color: #9a0000; font-weight: bold; " href="<?php the_permalink()?>"><?php the_title()?></a></p><span style="border-bottom: 1px dashed #b9aca3;color: #777777;padding-bottom:10px;"><?php the_excerpt()?></span>
						         <?php
	                                 }else{
		                         ?>
						
							<ul>
								<li><div class="circle_label"></div><a href="<?php the_permalink()?>"><?php the_title() ?></a><span class="subtitle_text"><?php the_time('m-d')?>&nbsp;&nbsp;</span></li>		
							</ul>
						
						 <?php
	                     }
	                         ++$n;
                         }
                         }
                         ?>
<!-- 圣贤德育右边列表结束 -->	 
					</div>
					</div></div>
				<hr>
				<div class="shengxdy">
<!-- 圣贤德育下边列表开始 -->	 
					 <?php $list_num=array(  
                         'showposts'=>9,
                         'orderby'=>guid,
                         'cat' => 149);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==4||$nu==7){ ?>
						   	 <div class="sxdy_li">
						<ul> 
							<li class="shengxdy_pic">
								<?php img_thumbnail();?>
							</li>
							 <?php
                             }else{
                             ?> 								
							<li class="subtitle_text"><div class="circle_label"></div><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==3||$nu==6||$nu==9){ ?>
						</ul>
					</div>
					<?php
                             } 
                             ?> 
                     <?php ++$nu;}}?>
<!-- 圣贤德育下边列表结束 -->	 
				</div>
			</div>
			<div class="fjgc_right_box daks">
				<ul style="margin: 2px;" class="kaishi_box">
					<li style=" color: #634529; font-weight: bold;">大德开示</li>
					<hr />
<!-- 大德开示下边列表开始 -->	 
					<?php $guancha_list=array(
                         'showposts'=>10,
                         'orderby'=>guid,
                         'cat' => 6);
                         query_posts($guancha_list);
                         if (have_posts()){
                         while (have_posts()) { the_post(); ?>
                         <li style=" color: #634529;"><div class="circle_label"></div><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                        <?php
                         }
                         }
                    ?>	
<!-- 大德开示下边列表结束 -->	 									
				</ul>
			</div>
		</div>

		<div style="width: 70%; height: auto; overflow: hidden;  margin: 0 auto; ">
			<div style="float: left; width: 76%; ">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;">
						<span>智慧心语</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 智慧心语子类目开始 -->	
                            <?php
				                 $cat=147	;
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
				<hr>
				
			</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_top">
<!-- 智慧心语图片开始 -->
                         <?php $list_num=array(  
	                          'showposts'=>5,
                              'cat' => 147,);
                              query_posts($list_num);
                              if (have_posts()){?>
                         <ul>
                              <?php while (have_posts()) { the_post(); ?>
                             <li>
                                 <a href="<?php the_permalink()?>">
                                 <?php img_thumbnail();?>
                                 <h3 style="display:block;text-align:center;">
                                 <?php the_title();?></h3>
                                 </a>
                             </li>
                             <?php
                              } ?>
                         </ul>
                         <?php
                             }
                         ?>  
<!-- 智慧心语图片结束 -->
				</div>
				<div class="zpyl_left_bottom">
<!-- 智慧心语文章列表开始 -->
                     
					 <?php $list_num=array(  
                         'showposts'=>21,
                         'orderby'=>guid,
                         'cat' => 147);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==8||$nu==15){ ?>
					 <div style="float: left; width: 30%; margin-left: 3%;">
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
					<?php
                             } 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 智慧心语文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right" style="float: right; width: 23%;  height: 400px;margin-top: -30px;">	
			     <h3 class="zpyl_left_right_top" >
                   佛教经典
			     </h3>
			     <hr/>	
        	     <div class="tuju">
<!-- 佛教经典开始 -->
					 <?php $list_num=array(  
                         'showposts'=>3,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 3);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul> 
                     	<li style="height:120px;" ><a href="<?php the_permalink()?>"><?php img_thumbnail();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 佛教经典结束 -->                     
			     </div>		
			</div>					
		</div>
		<div style="width: 70%; height: 450px; overflow: hidden;  margin: 0 auto; ">
			<div style="float: left; width: 76%; ">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;">
						<span>观音菩萨</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 观音菩萨子类目开始 -->	
                            <?php
				                 $cat=81;
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
				</div>
				<hr>
				
			</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_top">
<!-- 观音菩萨图片开始 -->
                         <?php $list_num=array(  
	                          'showposts'=>5,
                              'cat' => 81,);
                              query_posts($list_num);
                              if (have_posts()){?>
                         <ul>
                              <?php while (have_posts()) { the_post(); ?>
                             <li>
                                 <a href="<?php the_permalink()?>">
                                 <?php img_thumbnail();?>
                                 <h3 style="display:block;text-align:center;">
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
                         'showposts'=>20,
                         'orderby'=>guid,
                         'cat' => 81);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==6||$nu==11||$nu==16){ ?>
					 <div style="float: left; width: 22%; margin-left: 3%;">
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
					<?php
                             } 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 观音菩萨文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right" style="float: right; width: 23%;  height: 400px;margin-top: -30px;">	
			     <h3 class="zpyl_left_right_top" >
                   佛法圣迹
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 佛法圣迹开始 -->
					 <?php $list_num=array(  
                         'showposts'=>9,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 23);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul style="list-style-type:disc;margin-left: 5px;"> 
                     	<li style="height:30px;overflow: hidden;list-style-type:disc;"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 佛法圣迹结束 -->                     
			     </div>	
			     <div><img src="/wp-content/themes/begin/img/fjw/20180426162124316.jpg" /></div>	
			</div>					
		</div>
		<div style="width: 70%; height: 450px; overflow: hidden;  margin: 0 auto;  ">
			<div style="float: left; width: 76%; ">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;top:-5px;width:40px;height:25px;">
						<span>学佛受用</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 学佛受用子类目开始 -->	
                            <?php
				                 $cat=16	;
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
				<hr>
				
			</div>
			<div class="zpyl_left" >
				<div class="zpyl_left_bottom xuefsy">
<!-- 学佛受用文章列表开始 -->                     
					 <?php $list_num=array(  
                         'showposts'=>20,
                         'orderby'=>guid,
                         'ororder'=>disc,
                         'cat' => 16);
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
					<?php
                             } 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 学佛受用文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right" style="float: right; width: 23%;  height: 400px;margin-top: -30px;">	
			     <h3 class="zpyl_left_right_top" >
                   素味人生
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 素味人生开始 -->
					 <?php $list_num=array(  
                         'showposts'=>10,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 154);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul style="list-style-type:disc;margin-left: 5px;"> 
                     	<li style="height:30px;overflow: hidden;list-style-type:disc;"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 素味人生结束 -->                     
			     </div>	
			</div>					
		</div>
        <div style="width:70%; margin:auto;"><img src="/wp-content/themes/begin/img/fjw/20180426173624984.jpg"/>
        </div>

		<div style="width: 70%; height: auto;overflow: hidden;margin: 0 auto; pdding-top:10px; ">
			<div style="float: left; width: 73%;margin-left: 20px; ">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;">
						<span>学佛修行</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 学佛修行子类目开始 -->	
                            <?php
				                 $cat=30	;
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
				<hr>
				
			</div>
			<div class="zpyl_left xfxx_box" style="margin-top:20px;" >
				<div class="zpyl_left_bottom xuefxx">
<!-- 学佛修行图片文章列表开始 -->                    
					 <?php


					  $list_num=array(  
                         'showposts'=>16,
                         'orderby'=>guid,
                         'cat' => 30);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
						   <?php if($nu==1||$nu==5||$nu==9||$nu==13){ ?>
					 <div class="xx_lb">
					 	<div class="xufo_tupian"style="margin-bottom:10px;">
					 		 <?php img_thumbnail()?>
					 		 <div class="xufo_tupian_title">
								 
								 <a href="<?php the_permalink()?>"><?php the_title()?></a>
					 		       <?php the_excerpt()?>
					 	     </div>
					 	 </div>
						<ul style="margin-top:20px;margin-left:20px;"> 							
							 <?php
                             }else{
                             ?> 								
							<li><a href="<?php the_permalink()?>"><?php the_title();?></a><span style="float:right;"><?php the_time('m-d')?></span></li>
							<?php
                             } 
                             ?> 
                              <?php if($nu==4||$nu==8||$nu==12||$nu==16){ ?>
						</ul>
					</div>
					<?php
                             } 
                             ?> 
                     <?php ++$nu;}}?>
                     
<!-- 学佛修行图片文章列表结束 -->
				</div>
			</div>
			<div class="zpyl_left_right" style="float: right; width: 23%;  height: 400px;margin-top:-28px;">	
			     <h3 class="zpyl_left_right_top" >
                   西方极乐
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 西方极乐开始 -->
					 <?php $list_num=array(  
                         'showposts'=>10,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 34);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul style="list-style-type:disc;margin-left: 5px;"> 
                     	<li style="height:30px;overflow: hidden;list-style-type:disc;"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 西方极乐结束 -->                     
			     </div>	
			     <div><img src="/wp-content/themes/begin/img/fjw/20180426162124317.jpg" /></div>	
			</div>					
		</div>


		<div style="width: 70%;height: auto;overflow: hidden;margin: 0 auto;">
			<div style="float: left; width: 73%;">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;">
						<span>佛教视频</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 佛教视频子类目开始 -->	
                            <?php
				                 $cat=318	;
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
				<hr>
				
			</div>
			<div class="shipin" style="width: 721px;"  >
<!-- 佛教视频列表开始 -->
                 <ul>    
					 <?php $list_num=array(  
                         'showposts'=>8,
                         'orderby'=>'guid','post_type' => 'video');
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
					    <li style="">
					    	<div style="width: 120px; margin-left: 10px;">
					 	         <div class="shipin_tupian">
					                 <?php img_thumbnail()?></div>
					 		     <div class="" style="text-align:center;"><a href="<?php the_permalink()?>"><?php the_title()?></a>
					 	         </div>
					 	    </div>    
					     </li>
                     <?php }}?>                    
<!-- 佛教视频列表结束 -->		
			     </ul>
			</div>
			<div class="zpyl_left_right" style="float: left; width: 16%;  height: 400px;">	
			     <h3 class="zpyl_left_right_top" >
                   佛教广播
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 佛教广播开始 -->
					 <?php $list_num=array(  
                         'showposts'=>11,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 4);
                         query_posts($list_num);
                     if (have_posts()){
                     while (have_posts()) { the_post(); ?>	
                     <ul style="list-style-type:disc;margin-left: 5px;"> 
                     	<li style="height:30px;overflow: hidden;list-style-type:disc;"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     </ul>
                     <?php } }?>
<!-- 佛教广播结束 -->                     
			     </div>	
			</div>				
		</div>
       	<div style="width: 70%;height: auto;overflow: hidden;margin: 0 auto;">
        	<div class="fs" style="width:45%;margin-left:15px;    height: auto;">
				<div class="left_title">
					<div class="">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:0px;width:40px;height:25px;">
						<span style="position: relative;top:-6px;">放生</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 放生子类目开始 -->	
                            <?php
				                 $cat=12	;
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
				<hr>
                <div class="fs_ss_bottom" style="margin-top:20px;">
<!-- 放生列表开始 -->	                	
                	  <?php $list_num=array(  
                         'showposts'=>13,
                         'orderby'=>guid,
                         'cat' => 12);
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
                     <?php }++$nu;?>
                     <?php if($nu==14){?></ul><?php }}}?>
<!-- 放生列表结束 -->	                         
                </div>
        	</div>
        	<div class="ss" style="margin-left:10px;width:45%;">
        			<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:0px;width:40px;height:25px;">
						<span style="position:relative;top:-25px;">戒杀</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!--  素食子类目开始 -->	
                            <?php
				                 $cat=12;
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
         		<div class="fs_ss_bottom" style="margin-top:20px;">
<!-- 素食列表开始 -->	    
                	  <?php $list_num=array(  
                         'showposts'=>13,
                         'orderby'=>guid,
						 'order'=>'ASC',
                         'cat' => 12);
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
                     <?php }++$nu;?>
                     <?php if($nu==14){?></ul><?php }}}?>
 <!-- 素食列表结束 -->	                                			
         		</div>
        	</div>
        </div>
       	<div class="fjwd">
        		<div class="left_title" style="	clear:both;">
					<div class="fjgc_icon" ">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;">
						<span style="margin-left:50px;"> 大德开示</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!--  大德开示子类目开始 -->	
                            <?php
				                 $cat=6	;
				                 $cats = get_categories(array(
				               	     'child_of' => $cat,
					                 'parent' => $cat,
					                 'hide_empty' => 0
				                      )); 
				                 foreach($cats as $the_cat){					 
						          echo '<li><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></li>';			
				                 }
			                 ?>
<!-- 大德开示类目结束-->				                 
						</ul>
					</div>
				</div>
				<hr>
         		<div class="fjwd_content">
<!-- 大德开示列表开始 -->	    
                      <div class="fjwd_content_left ma">
                	     <?php $list_num=array(  
                             'showposts'=>5,
                             'orderby'=>guid,
                             'cat' => 6);
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

                             		             <span style="float:right;"><?php the_time('m-d')?></span>
                                             </li>                             
                                 <?php }++$nu;?>
                                         <?php if($nu==6){?></ul><?php }}}?>
                      </div>

                      <div class="fjwd_content_right">
                      	    <?php $list_num=array(  
                             'showposts'=>5,
                             'orderby'=>guid,
                             'order'=>'ASC',
                             'cat' => 6);
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
                             		             <span style="float:right;"><?php the_time('m-d')?></span>
                                             </li>                             
                                 <?php }++$nu;?>
                                         <?php if($nu==6){?></ul><?php }}}?>  
                      </div>
 <!-- 大德开示列表结束 -->	                                			
         		</div>        	
        </div>



		<div style="width: 70%; height: auto; overflow: hidden; margin: 0 auto;">
			<div style="float: left; width: 75%; ">
				<div class="left_title">
					<div class="fjgc_icon">
						<img src="./wp-content/themes/begin/img/fjw/icon.jpg" style="position: relative;left:-100px;width:40px;height:25px;"> 
						<span>感应事迹</span>
					</div>
					<div class="fjgc_nav_bar">
						<ul>
							<li><a href="http://localhost/wordpress/archives/category/zongpai/shijiezongjiao/">更多&gt;&gt;</a></li>
<!-- 感应事迹子类目开始 -->	
                            <?php
				                 $cat=4	;
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
                 <ul>    
					 <?php $list_num=array(  
                         'showposts'=>8,
                         'orderby'=>guid,
                         'cat' => 4);
                         query_posts($list_num);
                     if (have_posts()){
                             $nu=1;
                     while (have_posts()) { the_post(); ?>					
					    <li style="">
					    	<div style="width: 120px; margin-left: 10px;">
					 	         <div class="shipin_tupian">
					                 <?php img_thumbnail()?></div>
					 		     <div class="" style="text-align:center;"><a style="width:130px;height:30px;display: block;overflow: hidden;" href="<?php the_permalink()?>"><?php the_title()?></a>
					 	         </div>
					 	    </div>    
					     </li>
                     <?php }}?>                    
<!-- 感应事迹列表结束 -->		
			     </ul>
			</div>
			<div class="zpyl_left_right" style="float: right; width: 23%;  height: 400px;margin-top: -30px;">	
			     <h3 class="zpyl_left_right_top"style="margin-top:2px;" >
                   明信因果
			     </h3>
			     <hr/>	
        	     <div class="zpyl_left_right_bottom">
<!-- 明信因果开始 -->
					 <?php $list_num=array(  
                         'showposts'=>26,
                         'orderby'=>guid,
                         'order'=>'ASC',
                         'cat' => 15);
                         query_posts($list_num);
                     if (have_posts()){
                     	$nu=1;
                     while (have_posts()) { the_post(); if($nu==1||$nu==14){?>	
                     <ul <?php if($nu==1){?>class="list_le"<?php }else{?>class="list_ri" <?php }?>style="list-style-type:disc;margin-left: 5px;"> 
                          <?php }?>
                     	<li style="height:30px;overflow: hidden;list-style-type:disc;width:100px;"><a href="<?php the_permalink()?>"><?php the_title();?></a></li>
                     	<?php if($nu==13||$nu==26){?>
                     </ul>
                     <?php }?>

                     <?php ++$nu;} }?>
<!-- 明信因果结束 -->                     
			     </div>	
			</div>
		</div>

 <?php get_footer(); ?>

