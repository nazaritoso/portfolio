<?php
class latest_post_widget extends WP_Widget 
{
	/** constructor */
    function latest_post_widget() 
	{
		$widget_options = array(
			'classname' => 'latest_post',
			'description' => 'Displays latest post'
		);
        parent::WP_Widget('latest_post', 'Latest Posts', $widget_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$blog_page_id = $instance['blog_page_id'];
		$title = apply_filters('widget_title', $instance['title']);
		$post_count = $instance['post_count'];

		echo $before_widget;

		if($title) 
		{
			echo $before_title . $title . $after_title;
		}
		//get posts
		global $themename;
		$parent = get_post($blog_page_id);
		$parent_url = $parent->post_name;
		$post_categories = array_values(array_filter((array)get_post_meta($blog_page_id, $themename . "_blog_categories", true)));
		if(!count($post_categories))
			$post_categories = get_terms("category", "fields=ids");
		$args = array(
			'posts_per_page' => $post_count,
			'post_type' => 'post',
			'post_status' => 'publish',
			'cat' => implode(",", $post_categories),
			'orderby' => 'date', 
			'order' => 'DESC'
		);
		query_posts($args);
		if(have_posts()) : 
			?>
			<ul class="latest_post_list">
			<?php
			while (have_posts()) : the_post();
			global $post;
			?>
				<li>
					<a class="title link" href="<?php echo $parent_url; ?>/<?php echo $post->post_name;?>" title="<?php the_title();?>">
						<?php the_title(); ?>
					</a>
					<p class="blog-list-post-content clear-fix">
						<?php the_content_rss('', true, '', 20); ?>
						<br/>
						<a class="read-more link" href="<?php echo $parent_url; ?>/<?php echo $post->post_name;?>" title="<?php _e("Read more", $themename);?>">
							<?php _e("Read more", $themename);?>
						</a>
					</p>
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
		$instance['blog_page_id'] = strip_tags($new_instance['blog_page_id']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_count'] = strip_tags($new_instance['post_count']);
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{	
		global $themename;
		$blog_page_id = esc_attr($instance['blog_page_id']);
		$title = esc_attr($instance['title']);
		$post_count = esc_attr($instance['post_count']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('blog_page_id'); ?>"><?php _e('Blog page', $themename); ?></label>
			<select id="<?php echo $this->get_field_id('blog_page_id'); ?>" name="<?php echo $this->get_field_name('blog_page_id'); ?>">
			<?php
			$args = array(
				'meta_key' => '_wp_page_template',
				'meta_value' => 'blog.php',
				'post_type' => 'page',
				'post_status' => 'publish'
			);
			query_posts($args);
			if(have_posts()) : while (have_posts()) : the_post();
			?>
				<option <?php echo ($blog_page_id==get_the_ID() ? ' selected="selected"':'');?> value='<?php the_ID();?>'><?php the_title(); ?></option>
			<?php
			endwhile;
			endif;
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $themename); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Post count', $themename); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="text" value="<?php echo $post_count; ?>" />
		</p>
		<?php
	}
}
//register widget
add_action('widgets_init', create_function('', 'return register_widget("latest_post_widget");'));
?>