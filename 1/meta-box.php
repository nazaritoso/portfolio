<?php
//Adds a box to the main column on the Page edit screens
function theme_add_custom_box() 
{
	global $themename;
    add_meta_box( 
        "options",
        __("Options", $themename),
        "theme_inner_custom_box",
        "page",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "theme_add_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

// Prints the box content
function theme_inner_custom_box($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_noncename");

	//The actual fields for data entry
	$icon = get_post_meta($post->ID, $themename. "_icon", true);
	$direction = get_post_meta($post->ID, $themename . "_direction", true);
	$color = get_post_meta($post->ID, $themename . "_color", true);
	$blog_categories = get_post_meta($post->ID, $themename . "_blog_categories", true);
	$blog_order = get_post_meta($post->ID, $themename . "_blog_order", true);
	$post_categories = get_terms("category");
	echo '
	<table>
			<tr>
			<td>
				<label>'.__('Background image:',$themename).'</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="background_img" name="background_img" value="' . esc_attr(get_post_meta($post->ID, $themename . "_background_img", true)) . '" />
				<input type="button" class="button" name="'.$themename.'_upload_button" id="background_url_button" value="'.__('Browse',$themename).'" />
			</td>
		</tr> 	
						
		<tr>
			<td>
				<label for="icon">' . __('Icon', $themename) . ':</label>
			</td>
			<td>
				<select style="width: 120px;" id="icon" name="icon">
					<option value=""' . ($icon=="" ? ' selected="selected"' : '') . '>-</option>
					<option value="checkmark"' . ($icon=="checkmark" ? ' selected="selected"' : '') . '>' . __('checkmark', $themename) . '</option>
					<option value="features"' . ($icon=="features" ? ' selected="selected"' : '') . '>' . __('features', $themename) . '</option>
					<option value="image"' . ($icon=="image" ? ' selected="selected"' : '') . '>' . __('image', $themename) . '</option>
					<option value="info"' . ($icon=="info" ? ' selected="selected"' : '') . '>' . __('info', $themename) . '</option>
					<option value="mail"' . ($icon=="mail" ? ' selected="selected"' : '') . '>' . __('mail', $themename) . '</option>
					<option value="people"' . ($icon=="people" ? ' selected="selected"' : '') . '>' . __('people', $themename) . '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="direction">' . __('Direction', $themename) . ':</label>
			</td>
			<td>
				<select style="width: 120px;" id="direction" name="direction">
					<option value="left"' . ($direction=="left" ? ' selected="selected"' : '') . '>' . __('left', $themename) . '</option>
					<option value="right"' . ($direction=="right" ? ' selected="selected"' : '') . '>' . __('right', $themename) . '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="color">' . __('Custom background color', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="color" name="color" value="' . esc_attr(get_post_meta($post->ID, $themename . "_color", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="custom_url">' . __('Custom URL', $themename) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="custom_url" name="custom_url" value="' . esc_attr(get_post_meta($post->ID, $themename . "_custom_url", true)) . '" />
			</td>
		</tr>';
		if(count($post_categories))
		{
			echo '
		<tr>
			<td>
				<label for="blog_categories">' . __('Blog categories', $themename) . ':</label>
			</td>
			<td>
				<select id="blog_categories" name="blog_categories[]" multiple="multiple">';
					foreach($post_categories as $post_category)
						echo '<option value="' . $post_category->term_id . '"' . (is_array($blog_categories) && in_array($post_category->term_id, $blog_categories) ? ' selected="selected"' : '') . '>' . $post_category->name . '</option>';
			echo '
				</select>
			</td>
		</tr>';
		}
		echo '
		<tr>
			<td>
				<label for="blog_order">' . __('Blog order', $themename) . ':</label>
			</td>
			<td>
				<select style="width: 120px;" id="blog_order" name="blog_order">
					<option value="asc"' . ($blog_order=="asc" ? ' selected="selected"' : '') . '>' . __('ascending', $themename) . '</option>
					<option value="desc"' . ($blog_order=="desc" ? ' selected="selected"' : '') . '>' . __('descending', $themename) . '</option>
				</select>
			</td>
		</tr>
	</table>
	';
}

//When the post is saved, saves our custom data
function theme_save_postdata($post_id) 
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
	update_post_meta($post_id, $themename . "_background_img", $_POST["background_img"]);
	update_post_meta($post_id, $themename . "_icon", $_POST["icon"]);
	update_post_meta($post_id, $themename . "_direction", $_POST["direction"]);
	update_post_meta($post_id, $themename . "_color", $_POST["color"]);
	update_post_meta($post_id, $themename . "_custom_url", $_POST["custom_url"]);
	update_post_meta($post_id, $themename . "_blog_categories", $_POST["blog_categories"]);
	update_post_meta($post_id, $themename . "_blog_order", $_POST["blog_order"]);
}
add_action("save_post", "theme_save_postdata");
?>