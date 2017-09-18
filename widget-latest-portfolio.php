<?php
class latest_portfolio_widget extends WP_Widget 
{
	/** constructor */
    function latest_portfolio_widget() 
	{
		$widget_options = array(
			'classname' => 'latest_portfolio',
			'description' => 'Displays latest portfolio item'
		);
        parent::WP_Widget('latest_portfolio', 'Latest Portfolio', $widget_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$portfolio_page_id = $instance['portfolio_page_id'];
		$categories = $instance['categories'];
		$title = apply_filters('widget_title', $instance['title']);
		$items_count = $instance['items_count'];

		echo $before_widget;

		if($title) 
		{
			echo $before_title . $title . $after_title;
		}
		global $themename;
		//get posts
		query_posts(array( 
			'post_type' => $themename . '_portfolio',
			'posts_per_page' => $items_count,
			'post_status' => 'publish',
			$themename . '_portfolio_category' => implode(",", (array)$categories),
			'orderby' => 'date', 
			'order' => 'DESC'
		));
		$parent = get_post($portfolio_page_id);
		$parent_url = $parent->post_name;
		if(have_posts()) : 
			?>
			<ul class="latest_portfolio_list">
			<?php
			while (have_posts()) : the_post();
			?>
				<li>
					<a class="title link" href="<?php echo $parent_url; ?>" title="<?php the_title();?>">
						<?php the_title(); ?>
					</a>
					<?php
					if(has_post_thumbnail())
					{
						$video_url = get_post_meta(get_the_ID(), "video_url", true);
						if($video_url!="")
							$large_image_url = $video_url;
						else
						{
							$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "large");
							$large_image_url = $attachment_image[0];
						}
						$audio_url = get_post_meta(get_the_ID(), "audio_url", true);
						$external_url = get_post_meta(get_the_ID(), "external_url", true);
						$external_url_target = get_post_meta(get_the_ID(), "external_url_target", true);
						$iframe_url = get_post_meta(get_the_ID(), "iframe_url", true);
						$portfolio_description_location = get_post_meta(get_the_ID(), "portfolio_description_location", true);
					?>
					<div class="latest_portfolio_content clear-fix">
						<?php 
						echo '<a href="' . ($external_url=='' && $audio_url=='' ? ($iframe_url!='' ? $iframe_url : $large_image_url) : ($audio_url=='' ? $external_url : $audio_url)) . '"' . ($external_url=='' && $audio_url=='' ? ' class="fancybox-latest-portfolio fancybox-' . ($video_url!='' ? 'video' : ($iframe_url!='' ? 'iframe' : 'image')) . '"' : ($audio_url!='' ? ' class="audio-item"' : '')) . ($portfolio_description_location=='lightbox' || $portfolio_description_location=='both' ? ' title="' . esc_attr(get_the_content()) . '"' : '' ) . '>';
						the_post_thumbnail($themename . "-portfolio-thumb", array("alt" => get_the_title(), "title" => "")); 
						echo '<span/></a>';
						if($portfolio_description_location=='item' || $portfolio_description_location=='both')
							the_content();
						?>
					</div>
					<?php
					}
					?>
				</li>
			<?php
			endwhile; 
			?>
			</ul>
			<?php
		endif;
        echo $after_widget;
    }
	
	/** @see WP_Widget::update */
    function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['portfolio_page_id'] = strip_tags($new_instance['portfolio_page_id']);
		$instance['categories'] = $new_instance['categories'];
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['items_count'] = strip_tags($new_instance['items_count']);
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{	
		global $themename;
		$portfolio_page_id = $instance['portfolio_page_id'];
		$categories = $instance['categories'];
		$title = esc_attr($instance['title']);
		$items_count = esc_attr($instance['items_count']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('portfolio_page_id'); ?>"><?php _e('Portfolio page', $themename); ?></label>
			<select id="<?php echo $this->get_field_id('portfolio_page_id'); ?>" name="<?php echo $this->get_field_name('portfolio_page_id'); ?>">
			<?php
			$args = array(
				'post_type' => 'page',
				'post_status' => 'publish'
			);
			query_posts($args);
			if(have_posts()) : while (have_posts()) : the_post();
			?>
				<option <?php echo ($portfolio_page_id==get_the_ID() ? ' selected="selected"':'');?> value='<?php the_ID();?>'><?php the_title(); ?></option>
			<?php
			endwhile;
			endif;
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories', $themename); ?></label>
			<select multiple="multiple" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]">
			<?php
			$portfolio_categories = get_terms($themename . "_portfolio_category");
			foreach($portfolio_categories as $portfolio_category)
			{
			?>
				<option <?php echo (is_array($categories) && in_array($portfolio_category->slug, $categories) ? ' selected="selected"':'');?> value='<?php echo $portfolio_category->slug;?>'><?php echo $portfolio_category->name; ?></option>
			<?php
			}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $themename); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('items_count'); ?>"><?php _e('Items count', $themename); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('items_count'); ?>" name="<?php echo $this->get_field_name('items_count'); ?>" type="text" value="<?php echo $items_count; ?>" />
		</p>
		<?php
	}
}
//register widget
add_action('widgets_init', create_function('', 'return register_widget("latest_portfolio_widget");'));
?>