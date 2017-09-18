<?php
//Adds a box to the main column on the Post edit screens
function theme_add_post_custom_box() 
{
	global $themename;
    add_meta_box( 
        "options",
        __("Options", $themename),
        "theme_inner_custom_post_box",
        "post",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "theme_add_post_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

// Prints the box content
function theme_inner_custom_post_box($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_noncename");

	//The actual fields for data entry
	$images = get_post_meta($post->ID, $themename. "_images", true);
	$images_titles = get_post_meta($post->ID, $themename. "_images_titles", true);
	$features_images_loop = get_post_meta($post->ID, $themename. "_features_images_loop", true);
	$show_images_in = get_post_meta($post->ID, $themename. "_show_images_in", true);
	echo '
	<table>
		<tbody>
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
					<label for="features_images_loop">' . __('Features images loop', $themename) . ':</label>
				</td>
				<td>
					<select id="features_images_loop" name="features_images_loop">
						<option value="yes"' . ($features_images_loop=="yes" ? ' selected="selected"' : '') . '>' . __('yes', $themename) . '</option>
						<option value="no"' . ($features_images_loop=="no" ? ' selected="selected"' : '') . '>' . __('no', $themename) . '</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					' . __('Show featured images lightbox', $themename) . '
				</td>
				<td>
					<select id="show_images_in" name="show_images_in">
						<option value="blog"' . ($show_images_in=="blog" ? ' selected="selected"' : '') . '>' . __('on post list', $themename) . '</option>
						<option value="post"' . ($show_images_in=="post" ? ' selected="selected"' : '') . '>' . __('in single post', $themename) . '</option>
						<option value="both"' . ($show_images_in=="both" ? ' selected="selected"' : '') . '>' . __('both', $themename) . '</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	';
}

//When the post is saved, saves our custom data
function theme_save_post_postdata($post_id) 
{
	global $themename;
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (!wp_verify_nonce($_POST[$themename . '_noncename'], plugin_basename( __FILE__ )))
		return;


	// Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;
		
	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, $themename . "_images", array_filter($_POST["images"]));
	update_post_meta($post_id, $themename . "_images_titles", array_filter($_POST["images_titles"]));
	update_post_meta($post_id, $themename . "_features_images_loop", $_POST["features_images_loop"]);
	update_post_meta($post_id, $themename . "_show_images_in", $_POST["show_images_in"]);
}
add_action("save_post", "theme_save_post_postdata");
?>