<?php
//custom post type - portfolio
function theme_portfolio_init()
{
	global $themename;
	$labels = array(
		'name' => _x('Portfolio', 'post type general name', $themename),
		'singular_name' => _x('Portfolio Item', 'post type singular name', $themename),
		'add_new' => _x('Add New', $themename . '_portfolio', $themename),
		'add_new_item' => __('Add New Portfolio Item', $themename),
		'edit_item' => __('Edit Portfolio Item', $themename),
		'new_item' => __('New Portfolio Item', $themename),
		'all_items' => __('All Portfolio Items', $themename),
		'view_item' => __('View Portfolio Item', $themename),
		'search_items' => __('Search Portfolio Item', $themename),
		'not_found' =>  __('No portfolio items found', $themename),
		'not_found_in_trash' => __('No portfolio items found in Trash', $themename), 
		'parent_item_colon' => '',
		'menu_name' => __("Portfolio", $themename)
	);
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",  
		"menu_position" => 20,
		"hierarchical" => false,  
		"rewrite" => true,  
		"supports" => array("title", "editor", "thumbnail", "page-attributes")  
	);
	register_post_type($themename . "_portfolio", $args);  
	
	register_taxonomy($themename . "_portfolio_category", array($themename . "_portfolio"), array("label" => "Categories", "singular_label" => "Category", "rewrite" => true, "hierarchical" => true)); 
}  
add_action("init", "theme_portfolio_init"); 

//Adds a box to the main column on the Portfolio edit screens
function theme_add_portfolio_custom_box() 
{
	global $themename;
    add_meta_box( 
        "portfolio_config",
        __("Options", $themename),
        "theme_inner_portfolio_custom_box",
        $themename . "_portfolio",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "theme_add_portfolio_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

// Prints the box content
function theme_inner_portfolio_custom_box($post) 
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_portfolio_noncename");

	//The actual fields for data entry
	$external_url_target = get_post_meta($post->ID, "external_url_target", true);
	$portfolio_description_location = get_post_meta($post->ID, "portfolio_description_location", true);
	$images = get_post_meta($post->ID, "images", true);
	$images_titles = get_post_meta($post->ID, "images_titles", true);
	$features_images_loop = get_post_meta($post->ID, "features_images_loop", true);
	echo '
	<table>
		<tr>
			<td>
				<label for="portfolio_video_url">' . __('Video URL (optional)', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="portfolio_video_url" name="portfolio_video_url" value="' . esc_attr(get_post_meta($post->ID, "video_url", true)) . '" />
				<span class="description">For Vimeo please use http://player.vimeo.com/video/%video_id% For YouTube: http://youtube.com/embed/%video_id%</span>
			</td>
		</tr>
		<tr>
			<td>
				<label for="portfolio_audio_url">' . __('Audio URL (optional)', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="portfolio_audio_url" name="portfolio_audio_url" value="' . esc_attr(get_post_meta($post->ID, "audio_url", true)) . '" />
				<input type="button" class="button" name="' . $themename . '_upload_button" id="portfolio_audio_url_button" value="' . __('Browse', $themename) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="portfolio_iframe_url">' . __('Ifame URL (optional)', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="portfolio_iframe_url" name="portfolio_iframe_url" value="' . esc_attr(get_post_meta($post->ID, "iframe_url", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="portfolio_external_url">' . __('External URL (optional)', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="portfolio_external_url" name="portfolio_external_url" value="' . esc_attr(get_post_meta($post->ID, "external_url", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="portfolio_external_url_target">' . __('External URL target', $themename) . ':</label>
			</td>
			<td>
				<select id="portfolio_external_url_target" name="portfolio_external_url_target">
					<option value="same_window"' . ($external_url_target=="same_window" ? ' selected="selected"' : '') . '>' . __('same window', $themename) . '</option>
					<option value="new_window"' . ($external_url_target=="new_window" ? ' selected="selected"' : '') . '>' . __('new window', $themename) . '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="portfolio_description_location">' . __('Description location', $themename) . ':</label>
			</td>
			<td>
				<select id="portfolio_description_location" name="portfolio_description_location">
					<option value="item"' . ($portfolio_description_location=="item" ? ' selected="selected"' : '') . '>' . __('item', $themename) . '</option>
					<option value="lightbox"' . ($portfolio_description_location=="lightbox" ? ' selected="selected"' : '') . '>' . __('lightbox', $themename) . '</option>
					<option value="both"' . ($portfolio_description_location=="both" ? ' selected="selected"' : '') . '>' . __('both', $themename) . '</option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th colspan="2" scope="row" style="font-weight: bold;">
				' . __('Additional featured images', $themename) . '
			</th>
		</tr>';
		$images_count = count(array_values(array_filter((array)$images)));
		if($images_count==0)
			$images_count = 3;
		for($i=0; $i<$images_count; $i++)
		{
		echo '
		<tr class="image_url_row">
			<td>
				<label>' . __('Image url', $themename) . " " . ($i+1) . '</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="' . $themename . '_image_url_' . ($i+1) . '" name="images[]" value="' . esc_attr($images[$i]) . '" />
				<input type="button" class="button" name="' . $themename . '_upload_button" id="' . $themename . '_image_url_button_' . ($i+1) . '" value="' . __('Browse', $themename) . '" />
			</td>
		</tr>
		<tr class="image_title_row">
			<td>
				<label>' . __('Image title', $themename) . " " . ($i+1) . '</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="' . $themename . '_image_title_' . ($i+1) . '" name="images_titles[]" value="' . esc_attr($images_titles[$i]) . '" />
			</td>
		</tr>';
		}
		echo '
		<tr>
			<td></td>
			<td>
				<input type="button" class="button" name="' . $themename . '_add_new_button" id="' . $themename . '_add_new_button_image" value="' . __('Add image', $themename) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="features_images_loop">' . __('Featured images loop', $themename) . ':</label>
			</td>
			<td>
				<select id="features_images_loop" name="features_images_loop">
					<option value="yes"' . ($features_images_loop=="yes" ? ' selected="selected"' : '') . '>' . __('yes', $themename) . '</option>
					<option value="no"' . ($features_images_loop=="no" ? ' selected="selected"' : '') . '>' . __('no', $themename) . '</option>
				</select>
			</td>
		</tr>
	</table>
	';
}

//When the post is saved, saves our custom data
function theme_save_portfolio_postdata($post_id) 
{
	global $themename;
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (!wp_verify_nonce($_POST[$themename . '_portfolio_noncename'], plugin_basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "video_url", $_POST["portfolio_video_url"]);
	update_post_meta($post_id, "audio_url", $_POST["portfolio_audio_url"]);
	update_post_meta($post_id, "iframe_url", $_POST["portfolio_iframe_url"]);
	update_post_meta($post_id, "external_url", $_POST["portfolio_external_url"]);
	update_post_meta($post_id, "external_url_target", $_POST["portfolio_external_url_target"]);
	update_post_meta($post_id, "portfolio_description_location", $_POST["portfolio_description_location"]);
	update_post_meta($post_id, "images", array_filter($_POST["images"]));
	update_post_meta($post_id, "images_titles", array_filter($_POST["images_titles"]));
	update_post_meta($post_id, "features_images_loop", $_POST["features_images_loop"]);
}
add_action("save_post", "theme_save_portfolio_postdata");

//custom portfolio items list
function nostalgia_portfolio_edit_columns($columns)
{
	global $themename;
	$columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Portfolio Item', 'post type singular name', $themename),   
		"video_url" => __('Video URL', $themename),
		"audio_url" => __('Audio URL', $themename),
		"iframe_url" => __('Iframe URL', $themename),
		"external_url" => __('External URL', $themename),
		$themename . "_portfolio_category" => __('Categories', $themename),
		"date" => __('Date', $themename)
	);    

	return $columns;  
}  
add_filter("manage_edit-" . $themename . "_portfolio_columns", $themename . "_portfolio_edit_columns");   

function manage_nostalgia_portfolio_posts_custom_column($column)
{
	global $themename;
	global $post;
	switch ($column)  
	{
		case "video_url":   
			echo get_post_meta($post->ID, "video_url", true);  
			break;
		case "audio_url":   
			echo get_post_meta($post->ID, "audio_url", true);  
			break;
		case "iframe_url":   
			echo get_post_meta($post->ID, "iframe_url", true);  
			break;
		case "external_url":   
			echo get_post_meta($post->ID, "external_url", true);  
			break;
		case $themename . "_portfolio_category":
			echo get_the_term_list($post->ID, $themename . "_portfolio_category", '', ', ',''); 
			break;
	}  
}
add_action("manage_" . $themename . "_portfolio_posts_custom_column", "manage_" . $themename . "_portfolio_posts_custom_column");

//portfolio
function portfolio_tab($categories="", $i="", $open="", $default_tab_title="")
{	
	global $themename;
	query_posts(array( 
		'post_type' => $themename . '_portfolio',
		'posts_per_page' => '-1',
		'post_status' => 'publish',
		$themename . '_portfolio_category' => ($categories!="" ? $categories[$i] : ""),
		'orderby' => 'menu_order', 
		'order' => 'ASC'
	));
	$output = "";
	if(have_posts()):
		$output .= '<h3' . ($categories!="" && $open==$categories[$i] ? ' class="ui-state-active"' : '') . '><a href="#">';
		if($categories!="")
		{
			$term = get_term_by('slug', $categories[$i], $themename . "_portfolio_category");
			$ancestors = get_ancestors($term->term_id, $themename . "_portfolio_category");
			for($j=count($ancestors)-1; $j>=0; $j--)
				$output .= get_term($ancestors[$j], $themename . "_portfolio_category")->name . " / ";
			$output .= $term->name;
		}
		else
			$output .= $default_tab_title;
		$output .= '</a></h3>
		<div><ul class="image-list">';
		$j=0;
		while(have_posts()): the_post();
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
				$images = get_post_meta(get_the_ID(), "images", true);
				$images_titles = get_post_meta(get_the_ID(), "images_titles", true);
				$images_count = count(array_values(array_filter((array)$images)));
				$features_images_loop =  get_post_meta(get_the_ID(), "features_images_loop", true);
				$output .= '
				<li class="' . ($j%2==0 ? 'left' : 'right') . '">
					<a' . ($categories!="" && !$images_count ? ' rel="' . $categories[$i] . '"' : ($images_count ? ' rel="featured_' . get_the_ID() . '"' : '' )) . ($external_url!="" && $external_url_target=="new_window" ? ' target="_blank"' : '') . ' href="' . ($external_url=="" && $audio_url=="" ? ($iframe_url!="" ? $iframe_url : $large_image_url) : ($audio_url=="" ? $external_url : $audio_url)) . '"' . ($external_url=="" && $audio_url=="" ? ' class="fancybox-' . ($video_url!="" ? 'video' : ($iframe_url!="" ? 'iframe' : 'image')) . ($features_images_loop=='yes' ? ' cyclic' : '') . '"' : ($audio_url!="" ? ' class="audio-item"' : '')) . ($portfolio_description_location=='lightbox' || $portfolio_description_location=='both' ? ' title="' . esc_attr(get_the_content()) . '"' : '' ) . '>'
						. get_the_post_thumbnail($post->ID, $themename . "-portfolio-thumb", array("alt" => get_the_title(), "title" => "")) .
						'<span/>
					</a>
					<div class="image-list-caption">
						<div class="image-list-caption-title">' . get_the_title() . '</div>
						' . ($portfolio_description_location=='item' || $portfolio_description_location=='both' ? '<div class="image-list-caption-subtitle">' . get_the_content() . '</div>' : '') . '
					</div>';
				if($external_url=="" && $audio_url=="" && $video_url=="" && $iframe_url=="")
				{
					for($k=0; $k<$images_count; $k++)
						$output .= '<a href="' . $images[$k] . '" title="' . esc_attr($images_titles[$k]) . '" class="fancybox-image fancybox-hidden' . ($features_images_loop=='yes' ? ' cyclic' : '') . '" rel="featured_' . get_the_ID() . '">&nbsp;</a>';
				}
				$output .= '</li>';
				$j++;
			}
		endwhile; 
		$output .= '</ul></div>';
	endif;
	return $output;
}
function theme_portfolio_shortcode($atts)
{
	global $themename;
	extract(shortcode_atts(array(
		"category" => "",
		"open" => "false",
		"default_tab_title" => "Portfolio Items"
	), $atts));
	
	$output = "";
	if(wp_count_posts($themename . "_portfolio")->publish)
	{
		$categories = array_values(array_filter(explode(',', $category)));
		$categoriesCount = count($categories);
		$output .= '<div class="nostalgia-accordion">';
		if($categoriesCount)
		{
			for($i=0; $i<$categoriesCount; $i++)
				$output .= portfolio_tab($categories, $i, $open);
		}
		else
			$output .= portfolio_tab("", "", $open, $default_tab_title);
		$output .= '</div>';
	}
	return $output;
}
add_shortcode($themename . "_portfolio", "theme_portfolio_shortcode");
?>