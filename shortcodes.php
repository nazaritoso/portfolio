<?php
//contact info
function theme_contact_info($atts, $content)
{
	return '<div class="clear-fix contact-details">' . do_shortcode($content) . '</div>';
}
add_shortcode("contact_info", "theme_contact_info");

//google map details
function theme_contact_details($atts, $content)
{
	extract(shortcode_atts(array(
		"phone" => "",
		"fax" => "",
		"email" => ""
	), $atts));
	
	$output = '<div class="contact-details-about">' . do_shortcode(apply_filters('the_content', $content));
	if($phone!="" || $fax!="" || $email!="")
		$output .= '
		<ul class="no-list">'
			. ($phone!="" ? '<li class="icon-2 icon-2-1">' . $phone . '</li>' : '')
			. ($fax!="" ? '<li class="icon-2 icon-2-2">' . $fax . '</li>' : '')		
			. ($email!="" ? '<li class="icon-2 icon-2-3">' . $email . '</li>' : '')				
		. '</ul>';
	$output .= '</div>';
	return $output;
}
add_shortcode("contact_details", "theme_contact_details");

//google map
function theme_map_shortcode($atts)
{
	global $themename;
	extract(shortcode_atts(array(
		"id" => "map",
		"width" => "245",
		"height" => "200",
		"lat" => "29.760193",
		"lng" => "-95.36939",
		"marker_lat" => "29.760193",
		"marker_lng" => "-95.36939",
		"zoom" => "10",
		"arrow" => "1",
		"streetviewcontrol" => "false",
		"maptypecontrol" => "false"
	), $atts));
	$output = "
	<div class='contact-details-map'>"
		. ((int)$arrow ? '<div class="contact-details-map-arrow"></div>' : '') .
		"<div id='$id' class='" . $themename . "_map' style='width: " . $width . "px; height: " . $height . "px;'></div>
	</div>
	<script type='text/javascript'>
	try
    {
        var coordinate=new google.maps.LatLng($lat, $lng);
        var mapOptions= 
        {
            zoom:$zoom,
            center:coordinate,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
			streetViewControl:$streetviewcontrol,
			mapTypeControl:$maptypecontrol
        };
        var map = new google.maps.Map(document.getElementById('$id'),mapOptions);";
	if($marker_lat!="" && $marker_lng!="")
	{
	$output .= "
		new google.maps.Marker({
			position: new google.maps.LatLng($marker_lat, $marker_lng),
			map: map
		});";
	}
	$output .= "
    }
    catch(e) {};
	</script>";
	return $output;
}
add_shortcode($themename . "_map", "theme_map_shortcode");

//contact form
function theme_contact_form_shortcode($atts)
{
	global $theme_contact_form_options;
	global $themename;
	$output = "";
	$output .= '<form name="contact" id="contact_form" action="" method="post">
            <div class="clear-fix form-line">
                <div class="float-left">
                    <input type="text" name="contact-user-name" id="contact-user-name" value="' . $theme_contact_form_options["name_hint"] . '" onfocus="clearInput(this,\'focus\',\'' . $theme_contact_form_options["name_hint"] . '\')" onblur="clearInput(this,\'blur\',\'' . $theme_contact_form_options["name_hint"] . '\')"/>	
                </div>
                <div class="float-right">
                    <input type="text" name="contact-user-email" id="contact-user-email" value="' . $theme_contact_form_options["email_hint"] . '" onfocus="clearInput(this,\'focus\',\'' . $theme_contact_form_options["email_hint"] . '\')" onblur="clearInput(this,\'blur\',\'' . $theme_contact_form_options["email_hint"] . '\')"/>	
                </div>
			</div>
            <div class="clear-fix form-line">
                    <textarea rows="0" cols="0" name="contact-message" id="contact-message" onfocus="clearInput(this,\'focus\',\'' . $theme_contact_form_options["text_hint"] . '\')" onblur="clearInput(this,\'blur\',\'' . $theme_contact_form_options["text_hint"] . '\')">' . $theme_contact_form_options["text_hint"] . '</textarea>	
			</div>
            <div class="clear-fix form-line">
				<a href="javascript:submitContactForm();" class="button block" id="contact-send">' . __('Send', $themename) . '</a>
				<input type="hidden" name="action" value="theme_contact_form" />
			</div>
        </form>';
	return $output;
}
add_shortcode($themename . "_contact_form", "theme_contact_form_shortcode");

//contact form submit
function theme_contact_form()
{
	global $theme_contact_form_options;

    require_once("form-functions.php");
    require_once("phpMailer/class.phpmailer.php");

    $response=array('error'=>0,'info'=>null);

    $values=array
    (
        'contact-user-name' => $_POST['contact-user-name'],
        'contact-user-email' => $_POST['contact-user-email'],
        'contact-message' => $_POST['contact-message']
    );

    if(isEmpty($values['contact-user-name']) || strcmp($values['contact-user-name'],$theme_contact_form_options["name_hint"])==0)
    {
        $response['error']=1;
        $response['info'][]=array('fieldId'=>'contact-user-name','message'=>$theme_contact_form_options["name_error"]);
    }

    if(!validateEmail($values['contact-user-email']) || strcmp($values['contact-user-email'],$theme_contact_form_options["email_hint"])==0)
    {
        $response['error']=1;	
        $response['info'][]=array('fieldId'=>'contact-user-email','message'=>$theme_contact_form_options["email_error"]);
    }

    if(isEmpty($values['contact-message']) || strcmp($values['contact-message'],$theme_contact_form_options["text_hint"])==0)
    {
        $response['error']=1;
        $response['info'][]=array('fieldId'=>'contact-message','message'=>$theme_contact_form_options["text_error"]);
    }	

    if($response['error']==1) createResponse($response);

    if(isGPC()) $values=array_map('stripslashes',$values);

    $values=array_map('esc_attr',$values);

    $body=$theme_contact_form_options["template"];
	$body = str_replace("[name]", $values["contact-user-name"], $body);
	$body = str_replace("[email]", $values["contact-user-email"], $body); 
	$body = str_replace("[message]", $values["contact-message"], $body);

    $mail=new PHPMailer(); 
	$mail->CharSet='UTF-8';
    $mail->SetFrom($theme_contact_form_options["admin_email"],$theme_contact_form_options["admin_name"]); 
    $mail->AddAddress($theme_contact_form_options["admin_email"],$theme_contact_form_options["admin_name"]);

    if(!isEmpty($theme_contact_form_options["smtp_host"]))
    {
		$mail->IsSMTP();
        $mail->SMTPAuth=true; 
        $mail->Host=$theme_contact_form_options["smtp_host"];
        $mail->Username=$theme_contact_form_options["smtp_username"];
        $mail->Password=$theme_contact_form_options["smtp_password"];
		if((int)$theme_contact_form_options["smtp_port"]>0)
			$mail->Port=(int)$theme_contact_form_options["smtp_port"];
		$mail->SMTPSecure=$theme_contact_form_options["smtp_secure"];
    }
    
    $mail->Subject=$theme_contact_form_options["email_subject"];
    $mail->MsgHTML($body);

    if(!$mail->Send())
    {
        $response['error']=1;	
        $response['info'][]=array('fieldId'=>'contact-send','message'=>$theme_contact_form_options["message_send_error"]);
        createResponse($response);		
    }

    $response['error']=0;
    $response['info'][]=array('fieldId'=>'contact-send','message'=>$theme_contact_form_options["message_send_ok"]);

    createResponse($response);
}
add_action("wp_ajax_theme_contact_form", "theme_contact_form");
add_action("wp_ajax_nopriv_theme_contact_form", "theme_contact_form");

//header subtitle
function theme_header_subtitle($atts, $content)
{
	return '<p class="subtitle-paragraph">' . do_shortcode($content) . '</p>';
}
add_shortcode("page_subtitle", "theme_header_subtitle");

//bold
function theme_bold($atts, $content)
{
	return '<span class="bold">' . do_shortcode($content) . '</span>';
}
add_shortcode("bold", "theme_bold");
?>