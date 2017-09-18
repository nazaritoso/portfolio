<?php
//custom post type - service
function theme_service_init() 
{  
	global $themename;
	$labels = array(
		'name' => _x('Service', 'post type general name', $themename),
		'singular_name' => _x('Service Item', 'post type singular name', $themename),
		'add_new' => _x('Add New', $themename . '_service', $themename),
		'add_new_item' => __('Add New Service Item', $themename),
		'edit_item' => __('Edit Service Item', $themename),
		'new_item' => __('New Service Item', $themename),
		'all_items' => __('All Service Items', $themename),
		'view_item' => __('View Service Item', $themename),
		'search_items' => __('Search Service Item', $themename),
		'not_found' =>  __('No Service items found', $themename),
		'not_found_in_trash' => __('No service items found in Trash', $themename), 
		'parent_item_colon' => '',
		'menu_name' => __("Service", $themename)
	);
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",  
		"menu_position" => 20,
		"hierarchical" => false,  
		"rewrite" => true,  
		"supports" => array("title", "editor", "page-attributes")  
	);
	register_post_type($themename . "_service", $args);  
	
	register_taxonomy($themename . "_service_category", array($themename . "_service"), array("label" => "Categories", "singular_label" => "Category", "rewrite" => true)); 
}  
add_action("init", "theme_service_init"); 

//Adds a box to the main column on the Service edit screens
function theme_add_service_custom_box() 
{
	global $themename;
    add_meta_box( 
        "service_config",
        __("Options", $themename),
        "theme_inner_service_custom_box",
        $themename . "_service",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "theme_add_service_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

//Prints the box content
function theme_inner_service_custom_box($post) 
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_service_noncename");
	
	$icon = get_post_meta($post->ID, "icon", true);

	echo '
	<table>
		<tr>
			<td>
				<label for="icon">' . __('Icon', $themename) . ':</label>
			</td>
			<td>
				<select style="width: 120px;" id="icon" name="icon">
					<option value=""' . ($icon=="" ? ' selected="selected"' : '') . '>-</option>
					<option value="app"' . ($icon=="app" ? ' selected="selected"' : '') . '>' . __('app', $themename) . '</option>
					<option value="binoculars"' . ($icon=="binoculars" ? ' selected="selected"' : '') . '>' . __('binoculars', $themename) . '</option>
					<option value="briefcase"' . ($icon=="briefcase" ? ' selected="selected"' : '') . '>' . __('briefcase', $themename) . '</option>
					<option value="camera"' . ($icon=="camera" ? ' selected="selected"' : '') . '>' . __('camera', $themename) . '</option>
					<option value="document"' . ($icon=="document" ? ' selected="selected"' : '') . '>' . __('document', $themename) . '</option>
					<option value="heart"' . ($icon=="heart" ? ' selected="selected"' : '') . '>' . __('heart', $themename) . '</option>
					<option value="image"' . ($icon=="image" ? ' selected="selected"' : '') . '>' . __('image', $themename) . '</option>
					<option value="lightbulb"' . ($icon=="lightbulb" ? ' selected="selected"' : '') . '>' . __('lightbulb', $themename) . '</option>
					<option value="people"' . ($icon=="people" ? ' selected="selected"' : '') . '>' . __('people', $themename) . '</option>
					<option value="post"' . ($icon=="post" ? ' selected="selected"' : '') . '>' . __('post', $themename) . '</option>
				</select>
			</td>
		</tr>
	</table>
	';
}

//When the post is saved, saves our custom data
function theme_save_service_postdata($post_id) 
{
	global $themename;
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (!wp_verify_nonce($_POST[$themename . '_service_noncename'], plugin_basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "icon", $_POST["icon"]);
}
add_action("save_post", "theme_save_service_postdata");

//custom service items list
function nostalgia_service_edit_columns($columns)
{
	global $themename;
	$columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Service Item', 'post type singular name', $themename),   
		"icon" => __('Icon', $themename),
		$themename . "_service_category" => __('Categories', $themename),
		"date" => __('Date', $themename) 
	);

	return $columns;  
}  
add_filter("manage_edit-" . $themename . "_service_columns", $themename . "_service_edit_columns");   

function manage_nostalgia_service_posts_custom_column($column)
{
	global $themename;
	global $post;
	switch ($column)  
	{
		case "icon":   
			echo get_post_meta($post->ID, "icon", true);  
			break;
		case $themename . "_service_category":
			echo get_the_term_list($post->ID, $themename . "_service_category", '', ', ',''); 
			break;
	}  
}
add_action("manage_" . $themename . "_service_posts_custom_column", "manage_" . $themename . "_service_posts_custom_column");

//service
function theme_service_shortcode($atts)
{
	global $themename;
	extract(shortcode_atts(array(
		"category" => "",
	), $atts));
	
	$output = "";
	$output .= '<ul class="no-list list-service">';
	//get pages
	query_posts(array( 
		'post_type' => $themename . '_service',
		'posts_per_page' => '-1',
		'post_status' => 'publish',
		$themename . '_service_category' => $category,
		'orderby' => 'menu_order', 
		'order' => 'ASC'
	));
	if(have_posts()) : while (have_posts()) : the_post();
		$icon = get_post_meta(get_the_ID(), "icon", true);
		$output .= '
		<li class="icon-big icon-' . $icon . '">
			<h3>' . get_the_title() . '</h3>
			<p>
				' . do_shortcode(get_the_content()) . '
			</p>
		</li>';
	endwhile; endif;
	$output .= '</ul>';
	return $output;
}
add_shortcode($themename . "_service", "theme_service_shortcode");
?>