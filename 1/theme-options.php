<?php
//admin menu
function theme_admin_menu() 
{
	global $themename;
	add_submenu_page("themes.php", ucfirst($themename), "Theme Options", "edit_theme_options", "ThemeOptions", $themename . "_options");
}
add_action("admin_menu", "theme_admin_menu");

function theme_stripslashes_deep($value)
{
	$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);

	return $value;
}

function nostalgia_options() 
{
	global $themename;
	if($_POST["action"]==$themename . "_save")
	{
		$theme_options = (array)get_option($themename . "_options");
		if($_POST[$themename . "_submit"]=="Save Main Options")
		{
			$theme_options_main = array(
				"main_box_text" => $_POST["main_box_text"],
				"footer_text_left" => $_POST["footer_text_left"],
				"footer_text_right" => $_POST["footer_text_right"],
				"color" => $_POST["color"],
				"menu_count" => $_POST["menu_count"],
				"display_home_widget_on_start" => (int)$_POST["display_home_widget_on_start"],
				"display_menu_on_start" => (int)$_POST["display_menu_on_start"],
				"responsive" => (int)$_POST["responsive"]
			);
			update_option($themename . "_options", array_merge($theme_options, $theme_options_main));
			$selected_tab = 0;
		}
		else if($_POST[$themename . "_submit"]=="Save Footer Icons Options")
		{
			$theme_options_icons = array(
				"icons" => array(
					"type" => $_POST["icon_type"],
					"value" => $_POST["icon_value"]
				)
			);
			update_option($themename . "_options", array_merge($theme_options, $theme_options_icons));
			$selected_tab = 1;
		}
		else if($_POST[$themename . "_submit"]=="Save Backgrounds Options")
		{
			$theme_options_backgrounds = array(
				"backgrounds" => array_values(array_filter($_POST["background_url"])),
				"background_title" => array_values(array_filter($_POST["background_title"])),
				"autoplay" => (int)$_POST["autoplay"],
				"overlay" => (int)$_POST["overlay"],
				"transition" => $_POST["transition"],
				"transition_speed" => (int)$_POST["transition_speed"],
				"slide_interval" => (int)$_POST["slide_interval"]
			);
			update_option($themename . "_options", array_merge($theme_options, $theme_options_backgrounds));
			$selected_tab = 2;
		}
		else if($_POST[$themename . "_submit"]=="Save Music Options")
		{
			$theme_options_music = array(
				"tracks" => array_values(array_filter($_POST["track_url"])),
				"music_loop" => (int)$_POST["music_loop"],
				"music_autoplay" => (int)$_POST["music_autoplay"]
			);
			update_option($themename . "_options", array_merge($theme_options, $theme_options_music));
			$selected_tab = 3;
		}
		else
		{
			$theme_options = array(
				"main_box_text" => $_POST["main_box_text"],
				"footer_text_left" => $_POST["footer_text_left"],
				"footer_text_right" => $_POST["footer_text_right"],
				"color" => $_POST["color"],
				"menu_count" => $_POST["menu_count"],
				"display_home_widget_on_start" => (int)$_POST["display_home_widget_on_start"],
				"display_menu_on_start" => (int)$_POST["display_menu_on_start"],
				"responsive" => (int)$_POST["responsive"],
				"icons" => array(
					"type" => $_POST["icon_type"],
					"value" => $_POST["icon_value"]
				),
				"backgrounds" => array_values(array_filter($_POST["background_url"])),
				"background_title" => array_values(array_filter($_POST["background_title"])),
				"autoplay" => (int)$_POST["autoplay"],
				"overlay" => (int)$_POST["overlay"],
				"tracks" => array_values(array_filter($_POST["track_url"])),
				"music_loop" => (int)$_POST["music_loop"],
				"music_autoplay" => (int)$_POST["music_autoplay"],
				"transition" => $_POST["transition"],
				"transition_speed" => (int)$_POST["transition_speed"],
				"slide_interval" => (int)$_POST["slide_interval"]
			);
			update_option($themename . "_options", $theme_options);
			$selected_tab = 0;
		}
	}
	$theme_options = theme_stripslashes_deep(get_option($themename . "_options"));
	$icons = array(
		"facebook",
		"flickr",
		"google",
		"linkedin",
		"rss",
		"skype",
		"soundcloud",
		"twitter",
		"wordpress",
		"xing"
	);
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php echo ucfirst($themename);?> Options</h2>
	</div>
	<?php 
	if($_POST["action"]==$themename . "_save")
	{
	?>
	<div class="updated"> 
		<p>
			<strong>
				<?php _e('Options saved', $themename); ?>
			</strong>
		</p>
	</div>
	<?php 
	}
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="<?php echo $themename; ?>-options-tabs">
		<ul class="nav-tabs">
			<li class="nav-tab">
				<a href="#tab-main">
					<?php _e('Main', $themename); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-social-footer-icons">
					<?php _e('Social footer icons', $themename); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-backgrounds">
					<?php _e('Background', $themename); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-music">
					<?php _e('Music', $themename); ?>
				</a>
			</li>
		</ul>
		<div id="tab-main">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th colspan="2" scope="row" style="font-weight: bold;">
							<?php _e('Main', $themename); ?>
						</th>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="main_header"><?php _e('Main box text', $themename); ?></label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($theme_options["main_box_text"]); ?>" id="main_box_text" name="main_box_text">
							<input type="button" class="button" name="<?php echo $themename;?>_upload_button" id="main_box_upload_button" value="<?php _e('Insert logo', $themename); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="footer_text_left"><?php _e('Footer text left', $themename); ?></label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($theme_options["footer_text_left"]); ?>" id="footer_text_left" name="footer_text_left">
							<span class="description"><?php _e('Can be text or any html', $themename); ?>.</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="footer_text_right"><?php _e('Footer text right', $themename); ?></label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($theme_options["footer_text_right"]); ?>" id="footer_text_right" name="footer_text_right">
							<span class="description"><?php _e('Can be text or any html', $themename); ?>.</span>
						</td>
					</tr>
					<tr>
						<td>
							<label for="color"><?php _e('Theme color:', $themename); ?></label>
						</td>
						<td>
							<input type="text" class="regular-text" id="color" name="color" value="<?php echo esc_attr($theme_options["color"]); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="menu_count"><?php _e('Number of menu positions:', $themename); ?></label>
						</td>
						<td>
							<input type="text" class="regular-text" id="menu_count" name="menu_count" value="<?php echo esc_attr($theme_options["menu_count"]); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Display home widget on start', $themename); ?></label>
						</td>
						<td>
							<select id="display_home_widget_on_start" name="display_home_widget_on_start">
								<option value="1"<?php echo ((int)$theme_options["display_home_widget_on_start"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
								<option value="0"<?php echo (!(int)$theme_options["display_home_widget_on_start"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Display menu on start', $themename); ?></label>
						</td>
						<td>
							<select id="display_menu_on_start" name="display_menu_on_start">
								<option value="1"<?php echo ((int)$theme_options["display_menu_on_start"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
								<option value="0"<?php echo (!(int)$theme_options["display_menu_on_start"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Responsive', $themename); ?></label>
						</td>
						<td>
							<select id="responsive" name="responsive">
								<option value="1"<?php echo ((int)$theme_options["responsive"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
								<option value="0"<?php echo (!(int)$theme_options["responsive"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<input type="submit" value="<?php _e('Save Main Options', $themename); ?>" class="button-primary" name="<?php echo $themename; ?>_submit">
			</p>
		</div>
		<div id="tab-social-footer-icons">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th colspan="2" scope="row" style="font-weight: bold;">
							<?php _e('Social footer icons', $themename); ?>
						</th>
					</tr>
					<?php
					for($i=0; $i<8; $i++)
					{
					?>
					<tr valign="top">
						<th scope="row">
							<label><?php _e('Icon type', $themename); ?>: </label>
							<select name="icon_type[]">
								<option value="">-</option>
								<?php for($j=0; $j<count($icons); $j++)
								{
								?>
								<option value="<?php echo $icons[$j]; ?>"<?php echo ($icons[$j]==$theme_options["icons"]["type"][$i] ? " selected='selected'" : "") ?>><?php echo $icons[$j]; ?></option>
								<?php
								}
								?>
							</select>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo $theme_options["icons"]["value"][$i]; ?>" name="icon_value[]">
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			<p>
				<input type="submit" value="<?php _e('Save Footer Icons Options', $themename); ?>" class="button-primary" name="<?php echo $themename; ?>_submit">
			</p>
		</div>
		<div id="tab-backgrounds">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th colspan="2" scope="row" style="font-weight: bold;">
							<?php _e('Backgrounds', $themename); ?>
						</th>
					</tr>
					<?php
					$backgrounds_count = count($theme_options["backgrounds"]);
					if($backgrounds_count==0)
						$backgrounds_count = 6;
					for($i=0; $i<$backgrounds_count; $i++)
					{
					?>
					<tr class="background_url_row">
						<td>
							<label><?php _e('Background image url', $themename); echo " " . ($i+1); ?></label>
						</td>
						<td>
							<input class="regular-text" type="text" id="<?php echo $themename;?>_background_url_<?php echo ($i+1); ?>" name="background_url[]" value="<?php echo esc_attr($theme_options["backgrounds"][$i]); ?>" />
							<input type="button" class="button" name="<?php echo $themename;?>_upload_button" id="<?php echo $themename;?>_background_url_button_<?php echo ($i+1); ?>" value="<?php _e('Browse', $themename); ?>" />
						</td>
					</tr>
					<tr class="background_title_row">
						<td>
							<label><?php _e('Background title', $themename); echo " " . ($i+1); ?></label>
						</td>
						<td>
							<input class="regular-text" type="text" id="<?php echo $themename;?>_background_title_<?php echo ($i+1); ?>" name="background_title[]" value="<?php echo esc_attr($theme_options["background_title"][$i]); ?>" />
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td></td>
						<td>
							<input type="button" class="button" name="<?php echo $themename;?>_add_new_button" id="<?php echo $themename;?>_add_new_button" value="<?php _e('Add background', $themename); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Autoplay', $themename); ?></label>
						</td>
						<td>
							<select id="autoplay" name="autoplay">
								<option value="0"<?php echo (!(int)$theme_options["autoplay"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
								<option value="1"<?php echo ((int)$theme_options["autoplay"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Background overlay', $themename); ?></label>
						</td>
						<td>
							<select id="overlay" name="overlay">
								<option value="0"<?php echo (!(int)$theme_options["overlay"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
								<option value="1"<?php echo ((int)$theme_options["overlay"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="transition"><?php _e('Transition:', $themename); ?></label>
						</td>
						<td>
							<select id="transition" name="transition">
								<option value="none"<?php echo ($theme_options["transition"]=="none" ? " selected='selected'" : "") ?>><?php _e('none', $themename); ?></option>
								<option value="fade"<?php echo ($theme_options["transition"]=="fade" ? " selected='selected'" : "") ?>><?php _e('fade', $themename); ?></option>
								<option value="slideTop"<?php echo ($theme_options["transition"]=="slideTop" ? " selected='selected'" : "") ?>><?php _e('slideTop', $themename); ?></option>
								<option value="slideRight"<?php echo ($theme_options["transition"]=="slideRight" ? " selected='selected'" : "") ?>><?php _e('slideRight', $themename); ?></option>
								<option value="slideBottom"<?php echo ($theme_options["transition"]=="slideBottom" ? " selected='selected'" : "") ?>><?php _e('slideBottom', $themename); ?></option>
								<option value="slideLeft"<?php echo ($theme_options["transition"]=="slideLeft" ? " selected='selected'" : "") ?>><?php _e('slideLeft', $themename); ?></option>
								<option value="carouselRight"<?php echo ($theme_options["transition"]=="carouselRight" ? " selected='selected'" : "") ?>><?php _e('carouselRight', $themename); ?></option>
								<option value="carouselLeft"<?php echo ($theme_options["transition"]=="carouselLeft" ? " selected='selected'" : "") ?>><?php _e('carouselLeft', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="transition_speed"><?php _e('Transition speed:', $themename); ?></label>
						</td>
						<td>
							<input type="text" class="regular-text" id="transition_speed" name="transition_speed" value="<?php echo (int)esc_attr($theme_options["transition_speed"]); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="slide_interval"><?php _e('Slide interval:', $themename); ?></label>
						</td>
						<td>
							<input type="text" class="regular-text" id="slide_interval" name="slide_interval" value="<?php echo (int)esc_attr($theme_options["slide_interval"]); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<input type="submit" value="<?php _e('Save Backgrounds Options', $themename); ?>" class="button-primary" name="<?php echo $themename; ?>_submit">
			</p>
		</div>
		<div id="tab-music">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th colspan="2" scope="row" style="font-weight: bold;">
							<?php _e('Music', $themename); ?>
						</th>
					</tr>
					<?php
					$tracks_count = count($theme_options["tracks"]);
					if($tracks_count==0)
						$tracks_count = 1;
					for($i=0; $i<$tracks_count; $i++)
					{
					?>
					<tr class="track_url_row">
						<td>
							<label><?php _e('Track url', $themename); echo " " . ($i+1); ?></label>
						</td>
						<td>
							<input class="regular-text" type="text" id="<?php echo $themename;?>_track_url_<?php echo ($i+1); ?>" name="track_url[]" value="<?php echo esc_attr($theme_options["tracks"][$i]); ?>" />
							<input type="button" class="button" name="<?php echo $themename;?>_upload_button" id="<?php echo $themename;?>_track_url_button_<?php echo ($i+1); ?>" value="<?php _e('Browse', $themename); ?>" />
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td></td>
						<td>
							<input type="button" class="button" name="<?php echo $themename;?>_add_new_button" id="<?php echo $themename;?>_add_new_button_track" value="<?php _e('Add track', $themename); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Loop', $themename); ?></label>
						</td>
						<td>
							<select id="music_loop" name="music_loop">
								<option value="0"<?php echo (!(int)$theme_options["music_loop"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
								<option value="1"<?php echo ((int)$theme_options["music_loop"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Autoplay', $themename); ?></label>
						</td>
						<td>
							<select id="music_autoplay" name="music_autoplay">
								<option value="0"<?php echo (!(int)$theme_options["music_autoplay"] ? " selected='selected'" : "") ?>><?php _e('no', $themename); ?></option>
								<option value="1"<?php echo ((int)$theme_options["music_autoplay"]==1 ? " selected='selected'" : "") ?>><?php _e('yes', $themename); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<input type="submit" value="<?php _e('Save Music Options', $themename); ?>" class="button-primary" name="<?php echo $themename; ?>_submit">
			</p>
		</div>
		<p>
			<input type="hidden" name="action" value="<?php echo $themename; ?>_save" />
			<input type="submit" value="<?php _e('Save All Options', $themename); ?>" class="button-primary" name="<?php echo $themename; ?>_submit">
		</p>
		<input type="hidden" id="<?php echo $themename; ?>-selected-tab" value="<?php echo $selected_tab;?>" />
	</form>
<?php
}
?>