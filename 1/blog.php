<?php
/*
Template Name: Blog
*/
if(!(int)$included)
{
get_header(); ?>
<!-- Navigation -->
<div id="nostalgia-navigation">
	<div id="nostalgia-navigation-menu">
		<?php
		$params = array();
		$result = theme_get($params);
		if($result["custom_css"]!=''):
		?>
		<style type="text/css">
			<?php echo $result["custom_css"]; ?>
		</style>
		<?php
		endif;
		?>
		<!-- Menu -->
		<ul class="clear-fix">
			<?php
			echo $result["html"];
			?>
		</ul>
		<!-- Music control -->
		<?php if(count($theme_options["tracks"])): ?>
		<a href="#" class="jPlayerControl<?php echo (!$theme_options["music_autoplay"] ? " inactive" : ""); ?>"></a>
		<?php endif; ?>
		<!-- /Music control -->
		<!-- /Menu -->
		<a href="main" id="nostalgia-navigation-close-button"></a>
	</div>
	<!-- Name box -->
	<div id="nostalgia-navigation-name-box">
		<?php echo $theme_options["main_box_text"]; ?>
	</div>
	<!-- /Name box -->
	<div id="nostalgia-navigation-click-here-box"></div>
</div>
<!-- /Navigation -->
<!-- Widget area -->
<?php
if(is_active_sidebar('home-left') || is_active_sidebar('home-right')):
?>
<div class="sidebar-home"<?php if(!(int)$theme_options["display_home_widget_on_start"]): ?> style="display: none;"<?php endif; ?>>
<?php
if(is_active_sidebar('home-left')):
?>
<ul class="sidebar-home-left">
<?php
	get_sidebar('home-left');
?>
</ul>
<?php
endif; 
?>
<?php
if(is_active_sidebar('home-right')):
?>
<ul class="sidebar-home-right">
<?php
	get_sidebar('home-right');
?>
</ul>
<?php
endif;
?>
</div>
<?php
endif;
?>
<!-- Widget area -->
<!-- Tab -->
<div id="nostalgia-tab">
	<!-- Tab icon -->
	<div id="nostalgia-tab-icon"></div>
	<!-- /Tab icon -->
	<!-- Content -->
	<div id="nostalgia-tab-content">
		<form name="nostalgia-tab-content-menu" id="nostalgia-tab-content-menu" method="post" action="">
			<div>
				<select id="nostalgia-tab-content-menu-select" name="nostalgia-tab-content-menu-select">
					<?php
					$params = array(
						'type' => 'dropdown'
					);
					$result = theme_get($params);
					echo $result["html"];
					?>
					<option value='main'><?php _e('Close', $themename); ?></option>
				</select>
			</div>
		</form>
		<!-- Scroll section -->
		<div id="nostalgia-tab-content-scroll">
			<div id="nostalgia-tab-content-page">
				<!-- Page content -->
				<?php
				}
				global $themename;
				the_content();
				global $parent_url, $post;

				$parent_url = $post->post_name;
				$post_categories = array_values(array_filter((array)get_post_meta(get_the_ID(), $themename . "_blog_categories", true)));
				if(!count($post_categories))
					$post_categories = get_terms("category", "fields=ids");
				if(count($post_categories))
				{
					?>
					<div class="layout-50 clear-fix">
						<?php
						require_once("blog-category-walker.php");
						?>
						<div class="layout-50-left">
							<ul class="blog-category-list">
								<li>
									<a <?php if((int)$_GET["category_id"]==0): ?> class="category-selected" <?php endif;?> href="<?php echo $parent_url; ?>/category-all/" title="<?php _e("Show all posts", $themename); ?>">
										<?php _e("General", $themename); ?>
										<?php
										//count all posts
										query_posts(array( 
											'post_type' => 'post',
											'post_status' => 'publish',
											'posts_per_page' => -1,
											'cat' => implode(",", $post_categories)
										));
										global $wp_query;
										$post_count = $wp_query->post_count;
										?>
										<strong>[<?php echo $post_count; ?>]</strong>
									</a>
								</li>
						<?php
						if(count($post_categories)>1):
						wp_list_categories(array(
							"title_li" => "",
							"show_count" => 1,
							"include" => implode(",", array_slice($post_categories, 0, floor(count($post_categories)/2))),
							"parent_url" => $parent_url,
							"walker" => new Blog_Category_Walker()
						));
						endif;
						?>
							</ul>
						</div>
						<div class="layout-50-right">
							<ul class="blog-category-list">
						<?php
						wp_list_categories(array(
							"title_li" => "",
							"show_count" => 1,
							"include" => implode(",", array_slice($post_categories, floor(count($post_categories)/2), count($post_categories))),
							"parent_url" => $parent_url,
							"walker" => new Blog_Category_Walker()
						));
						?>
							</ul>
						</div>
					</div>
					<?php
				}
				query_posts(array( 
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => 5,
					'cat' => ((int)$_GET["category_id"]>0 ? (int)$_GET["category_id"] : implode(",", $post_categories)),
					'paged' => (int)$_GET["paged"],
					'order' => get_post_meta(get_the_ID(), $themename . "_blog_order", true)
				));
				get_sidebar('blog-top');
				?>
				<ul class="blog-list">
				<?php
				if(have_posts()) : while (have_posts()) : the_post();
				?>
					<li <?php post_class("blog-list-post"); ?>>
						<div class="blog-list-post-header clear-fix">
							<h3>
								<a href="<?php echo $parent_url; ?>/<?php echo $post->post_name;?>" title="<?php the_title();?>">
									<?php the_title(); ?>
								</a>
							</h3>
							<span><span class="month"><?php the_time('m'); ?></span><?php the_time('d'); ?></span>
						</div>
						<div class="blog-list-post-image clear-fix">
							<?php 
							if(has_post_thumbnail()):
							$thumb_id = get_post_thumbnail_id(get_the_ID());
							$attachment_image = wp_get_attachment_image_src($thumb_id, "large");
							$large_image_url = $attachment_image[0];
							$thumbnail_image = get_posts(array('p' => $thumb_id, 'post_type' => 'attachment'));
							$features_images_loop =  get_post_meta(get_the_ID(), $themename . "_features_images_loop", true);
							?>
							<a href="<?php echo $large_image_url; ?>" title="<?php echo esc_attr($thumbnail_image[0]->post_title); ?>" class="fancybox-image<?php echo ($features_images_loop=='yes' ? ' cyclic' : ''); ?>" rel="featured_<?php echo get_the_ID(); ?>">
								<?php the_post_thumbnail("blog-post-thumb", array("alt" => get_the_title(), "title" => "")); ?>
								<span></span>
							</a>
							<?php
							$show_images_in = get_post_meta(get_the_ID(), $themename. "_show_images_in", true);
							if($show_images_in=="blog" || $show_images_in=="both")
							{
								$images = get_post_meta(get_the_ID(), $themename. "_images", true);
								$images_titles = get_post_meta(get_the_ID(), $themename. "_images_titles", true);
								$images_count = count(array_values(array_filter((array)$images)));
								for($i=0; $i<$images_count; $i++)
								{
								?>
								<a href="<?php echo $images[$i]; ?>" title="<?php echo esc_attr($images_titles[$i]); ?>" class="fancybox-image fancybox-hidden<?php echo ($features_images_loop=='yes' ? ' cyclic' : ''); ?>" rel="featured_<?php echo get_the_ID(); ?>">
									&nbsp;
								</a>
								<?php 
								}
							}
							endif; ?>
							<div class="caption clear-fix">
								<span class="category icon-2 icon-2-4">
									in 
									<?php $categories = get_the_category();
									foreach($categories as $key=>$category)
									{
										echo '<a href="' . $parent_url . '/category-' . $category->term_id . '/" ';
										if(empty($category->description))
											echo 'title="' . sprintf(__('View all posts filed under %s', $themename), $category->name) . '"';
										else
											echo 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
										echo '>' .$category->name . '</a>' . ($key+1<count($categories) ? ', ' : '');
									}?>
								</span>
								<span class="comment icon-2 icon-2-6">
									this entry has <a href="<?php echo $parent_url; ?>/<?php echo $post->post_name;?>#comments" title="<?php comments_number(); ?>"><?php comments_number(); ?></a>
								</span>
								<span class="author icon-2 icon-2-5">
									by <span class="highlight"><?php the_author();?></span>
								</span>
							</div>
						</div>
						<p class="blog-list-post-content clear-fix">
							<?php the_excerpt_rss(); ?>
							<br/>
							<a class="read-more" href="<?php echo $parent_url; ?>/<?php echo $post->post_name;?>" title="<?php _e("Read more", $themename);?>">
								<?php _e("Read more", $themename);?>
							</a>
						</p>
					</li>
				<?php
				endwhile; endif;
				require_once("pagination.php");
				kriesi_pagination('', 2, $parent_url);
				//Reset Query
				wp_reset_query();
				?>
				</ul>
				<?php get_sidebar('blog-bottom');
				if(!(int)$included)
				{?>
			</div>
		</div>
		<!-- /Scroll section -->
		<!-- Footer -->		
		<div id="nostalgia-tab-footer" class="clear-fix">
			<?php 
			$arrayEmpty = true;
			for($i=0; $i<count($theme_options["icons"]["type"]); $i++)
			{
				if($theme_options["icons"]["type"][$i]!="")
					$arrayEmpty = false;
			}
			if(!$arrayEmpty):
			?>
			<ul class="no-list social-list">
				<?php
				for($i=0; $i<count($theme_options["icons"]["type"]); $i++)
				{
					if($theme_options["icons"]["type"][$i]!=""):
				?>
				<li><a href="<?php echo $theme_options["icons"]["value"][$i];?>" class="social-<?php echo $theme_options["icons"]["type"][$i]; ?>"></a></li>
				<?php
					endif;
				}
				?>
			</ul>
			<?php endif; ?>
			<?php if($theme_options["footer_text_left"]!="" ||  $theme_options["footer_text_right"]!=""): ?>
			<div class="nostalgia-tab-footer-caption">
				<?php if($theme_options["footer_text_left"]!=""): ?>
				<span class="float-left"><?php echo $theme_options["footer_text_left"]; ?></span>
				<?php endif; ?>
				<?php if($theme_options["footer_text_right"]!=""): ?>
				<span class="float-right"><?php echo $theme_options["footer_text_right"]; ?></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<!-- /Footer -->
	</div>
	<!-- /Content -->
</div>
<!-- /Tab -->
<?php get_footer(); 
}?>