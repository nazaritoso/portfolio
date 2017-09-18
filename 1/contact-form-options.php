<?php
//admin menu
function theme_contact_form_admin_menu() 
{
	global $themename;
	add_options_page(__(ucfirst($themename) . ' Contact Form', $themename), __(ucfirst($themename) . ' Contact Form', $themename), 'administrator', $themename . '_contact_form', 'theme_contact_form_admin_page');
}
add_action('admin_menu', 'theme_contact_form_admin_menu');

function theme_contact_form_admin_page() 
{
	global $themename;
	if($_POST["action"]=="save")
	{
		$theme_contact_form_options = array(
			"name_hint" => $_POST["name_hint"],
			"email_hint" => $_POST["email_hint"],
			"text_hint" => $_POST["text_hint"],
			"email_subject" => $_POST["email_subject"],
			"admin_name" => $_POST["admin_name"],
			"admin_email" => $_POST["admin_email"],
			"template" => $_POST["template"],
			"smtp_host" => $_POST["smtp_host"],
			"smtp_username" => $_POST["smtp_username"],
			"smtp_password" => $_POST["smtp_password"],
			"smtp_port" => $_POST["smtp_port"],
			"smtp_secure" => $_POST["smtp_secure"],
			"name_error" => $_POST["name_error"],
			"email_error" => $_POST["email_error"],
			"text_error" => $_POST["text_error"],
			"message_send_error" => $_POST["message_send_error"],
			"message_send_ok" => $_POST["message_send_ok"]
		);
		update_option($themename . "_contact_form_options", $theme_contact_form_options);
	}
	$theme_contact_form_options = theme_stripslashes_deep(get_option($themename . "_contact_form_options"));
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php echo ucfirst($themename); _e(' Contact Form Options', $themename);?></h2>
	</div>
	<?php 
	if($_POST["action"]=="save")
	{
	?>
	<div class="updated"> 
		<p>
			<strong>
				<?php
					_e('Options saved', $themename);
				?>
			</strong>
		</p>
	</div>
	<?php 
	}
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="<?php echo $themename; ?>_contact_form_settings">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<?php
						_e('Admin email config', $themename);
						?>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_name"><?php _e('Name', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["admin_name"]); ?>" id="admin_name" name="admin_name">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_email"><?php _e('Email', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["admin_email"]); ?>" id="admin_email" name="admin_email">
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<br />
					</th>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<?php
						_e('Admin SMTP config (optional)', $themename);
						?>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="smtp_host"><?php _e('Host', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["smtp_host"]); ?>" id="smtp_host" name="smtp_host">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="smtp_username"><?php _e('Username', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["smtp_username"]); ?>" id="smtp_username" name="smtp_username">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="smtp_password"><?php _e('Password', $themename); ?></label>
					</th>
					<td>
						<input type="password" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["smtp_password"]); ?>" id="smtp_password" name="smtp_password">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="smtp_port"><?php _e('Port', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["smtp_port"]); ?>" id="smtp_port" name="smtp_port">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="smtp_secure"><?php _e('SMTP Secure', $themename); ?></label>
					</th>
					<td>
						<select id="smtp_secure" name="smtp_secure">
							<option value=""<?php echo ($theme_contact_form_options["smtp_secure"]=="" ? " selected='selected'" : "") ?>>-</option>
							<option value="ssl"<?php echo ($theme_contact_form_options["smtp_secure"]=="ssl" ? " selected='selected'" : "") ?>><?php _e('ssl', $themename); ?></option>
							<option value="tls"<?php echo ($theme_contact_form_options["smtp_secure"]=="tls" ? " selected='selected'" : "") ?>><?php _e('tls', $themename); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<br />
					</th>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<?php _e('Email config', $themename); ?>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="email_subject"><?php _e('Email subject', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["email_subject"]); ?>" id="email_subject" name="email_subject">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="template"><?php _e('Template', $themename); ?></label>
					</th>
					<td>
						<?php the_editor($theme_contact_form_options["template"], "template");?>
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<br />
					</th>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<?php _e('Fields hints', $themename); ?>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="name_hint"><?php _e('Name hint', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["name_hint"]); ?>" id="name_hint" name="name_hint">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="email_hint"><?php _e('Email hint', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["email_hint"]); ?>" id="email_hint" name="email_hint">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="text_hint"><?php _e('Text hint', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["text_hint"]); ?>" id="text_hint" name="text_hint">
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<br />
					</th>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row" style="font-weight: bold;">
						<?php _e('Error messages', $themename); ?>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="name_error"><?php _e('Name field', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["name_error"]); ?>" id="name_error" name="name_error">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="email_error"><?php _e('Email field', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["email_error"]); ?>" id="email_error" name="email_error">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="text_error"><?php _e('Text field', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["text_error"]); ?>" id="text_error" name="text_error">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="message_send_ok"><?php _e('Message send ok', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["message_send_ok"]); ?>" id="message_send_ok" name="message_send_ok">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="message_send_error"><?php _e('Message send error', $themename); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr($theme_contact_form_options["message_send_error"]); ?>" id="message_send_error" name="message_send_error">
					</td>
				</tr>
			</tbody>
		</table>
		<p>
			<input type="hidden" name="action" value="save" />
			<input type="submit" value="Save Options" class="button-primary" name="Submit">
		</p>
	</form>
<?php
}
?>