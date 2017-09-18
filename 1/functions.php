<?php
$themename = "nostalgia";
//theme options
require_once("theme-options.php");

//contact form options
require_once("contact-form-options.php");

//custom meta box
require_once("meta-box.php");
require_once("post-meta-box.php");

//about
require_once("about_post_type.php");

//service
require_once("service_post_type.php");

//portfolio
require_once("portfolio_post_type.php");

//comments
require_once("comments-functions.php");

//widgets
require_once("widget-latest-post.php");
require_once("widget-latest-portfolio.php");

//admin functions
require_once("admin/functions.php");

//Make theme available for translation
//Translations can be filed in the /languages/ directory
load_theme_textdomain($themename, get_template_directory() . '/languages');

//register sidebars
if(function_exists("register_sidebar"))
{
	register_sidebar(array(
		"id" => "home-left",
		"name" => "Home Sidebar Left",
		'before_widget' => '<li id="%1$s" class="widget %2$s clear-fix">'
	));
	register_sidebar(array(
		"id" => "home-right",
		"name" => "Home Sidebar Right",
		'before_widget' => '<li id="%1$s" class="widget %2$s clear-fix">'
	));
	register_sidebar(array(
		"id" => "blog-top",
		"name" => "Blog Sidebar Top",
		"before_widget" => "<div id='sidebar-blog-top' class='clear-fix'>",
		"after_widget" => "</div>"
	));
	register_sidebar(array(
		"id" => "blog-bottom",
		"name" => "Blog Sidebar Bottom",
		"before_widget" => "<div id='sidebar-blog-bottom' class='clear-fix'>",
		"after_widget" => "</div>"
	));
	register_sidebar(array(
		"id" => "post-top",
		"name" => "Post Sidebar Top",
		"before_widget" => "<div id='sidebar-post-top' class='clear-fix'>",
		"after_widget" => "</div>"
	));
	register_sidebar(array(
		"id" => "post-bottom",
		"name" => "Post Sidebar Bottom",
		"before_widget" => "<div id='sidebar-post-bottom' class='clear-fix'>",
		"after_widget" => "</div>"
	));
}

//register blog post thumbnail & portfolio thumbnail
add_theme_support("post-thumbnails");
add_image_size("blog-post-thumb", 490, 200, true);
add_image_size($themename . "-portfolio-thumb", 230, 150, true);
function theme_image_sizes($sizes)
{
	global $themename;
	$addsizes = array(
		"blog-post-thumb" => __("Blog post thumbnail", $themename),
		$themename . "-portfolio-thumb" => __("Portfolio thumbnail", $themename)
	);
	$newsizes = array_merge($sizes, $addsizes);
	return $newsizes;
}
add_filter("image_size_names_choose", "theme_image_sizes");

//excerpt
function theme_excerpt_more($more) 
{
	return '';
}
add_filter('excerpt_more', 'theme_excerpt_more', 99);
	
//shortcodes
require_once("shortcodes.php");

function theme_after_setup_theme()
{
	global $themename;
	if(!get_option($themename . "_installed"))
	{		
		$theme_options = array(
			"main_box_text" => "<span>Kate Douglas</span>",
			"footer_text_left" => "<a href='http://themeforest.net/user/QuanticaLabs/portfolio?ref=QuanticaLabs'>Please feel free to share my works</a>",
			"footer_text_right" => "<a href='http://themeforest.net/user/QuanticaLabs?ref=QuanticaLabs'>Copyright Keith Douglas</a>",
			"color" => "FFC000",
			"menu_count" => 5,
			"display_home_widget_on_start" => 1,
			"display_menu_on_start" => 0,
			"responsive" => 1,
			"icons" => array(
				"type" => array(	
					"facebook",
					"twitter",
					"google"
				),
				"value" => array(
					"#",
					"http://twitter.com/QuanticaLabs",
					"#"
				)
			),
			"backgrounds" => array(
				get_template_directory_uri() . "/images/background/01.jpg",
				get_template_directory_uri() . "/images/background/02.jpg",
				get_template_directory_uri() . "/images/background/03.jpg",
				get_template_directory_uri() . "/images/background/04.jpg",
				get_template_directory_uri() . "/images/background/05.jpg",
				get_template_directory_uri() . "/images/background/06.jpg"
			),
			"background_title" => array(
				'<span class="supersized-caption-title">Halle Kearney By Robby Mueller</span><br/><br/>Well, today was Halle Kearney\'s first senior picture session in Akron, OH and I decided to go with something a little different here.<br/><br/>This is not going to be used as one of her senior pictures (As far as I know).<br/><br/>We took a different spin on things and I\'m a tad bit stoked on it.<br/><br/><a href="http://www.flickr.com/photos/ro2b3yface/5623260278/in/photostream/">Author Website</a>',
				'<span class="supersized-caption-title">Lavender By Vincent van der Pas</span><br/><br/>Taken in the south of France, near Apt.<br/><br/><a href="http://www.flickr.com/photos/archetypefotografie/4958711873/in/photostream/">Author Website</a>',
				'<span class="supersized-caption-title">Plainsong By Robb North</span><br/><br/>sometimes you make me feel<br/>like i\'m living at the edge of the world<br/>like i\'m living at the edge of the world<br/><br/><a href="http://www.flickr.com/photos/robbn1/3405147407/in/photostream/">Author Website</a>',
				'<span class="supersized-caption-title">Falcon on Rue Drolet By Flat-Black 66</span><br/><a href="http://www.flickr.com/photos/flatblack66/4733463620/">Author Website</a>',
				'<span class="supersized-caption-title">New kitchen radio By Johan Larsson</span><br/><br/>Tivoli Model One.<br/><br/><a href="http://www.flickr.com/photos/johanl/6125230384/in/photostream/">Author Website</a',
				'<span class="supersized-caption-title">Bekohlicious! By 55Laney69</span><br/><br/>Canon 550D + Canon 50mm F1.8 EF II @F1.8 :). Cross Processed with Alien Skin Exposure<br/><br/><a href="http://www.flickr.com/photos/hansel5569/6001781706/in/photostream/">Author Website</a>'
			),
			"autoplay" => 0,
			"overlay" => 1,
			"transition" => "fade",
			"transition_speed" => 750,
			"slide_interval" => 5000,
			"tracks" => array(),
			"music_loop" => 1,
			"music_autoplay" => 1
		);
		add_option($themename . "_options", $theme_options);
		
		$theme_contact_form_options = array(
			"name_hint" => "Your Name",
			"email_hint" => "Your E-mail Address",
			"text_hint" => "Message",
			"email_subject" => "Nostalgia: Contact from WWW",
			"admin_name" => get_settings("admin_email"),
			"admin_email" => get_settings("admin_email"),
			"template" => "<html>
	<head>
	</head>
	<body>
		<div><b>Name</b>: [name]</div>
		<div><b>E-mail</b>: [email]</div>
		<div><b>Message</b>: [message]</div>
	</body>
</html>",
			"smtp_host" => "",
			"smtp_username" => "",
			"smtp_password" => "",
			"smtp_port" => "",
			"smtp_secure" => "",
			"name_error" => "Please enter your name.",
			"email_error" => "Please enter valid e-mail.",
			"text_error" => "Please enter your message.",
			"message_send_error" => "Sorry, we can\'t send this message.",
			"message_send_ok" => "Thank you for contacting us."
		);
		add_option($themename . "_contact_form_options", $theme_contact_form_options);
		
		update_option("blogdescription", "Portfolio WordPress Theme");
		
		add_option($themename . "_installed", 1);
	}
}
add_action("after_setup_theme", "theme_after_setup_theme");

function theme_switch_theme($theme_template)
{
	global $themename;
	delete_option($themename . "_installed");
}
add_action("switch_theme", "theme_switch_theme");

//enable custom background
add_custom_background();

//theme options
global $theme_options;
$theme_options = theme_stripslashes_deep(get_option($themename . "_options"));
	
//contact form options
global $theme_contact_form_options;
$theme_contact_form_options = theme_stripslashes_deep(get_option($themename . "_contact_form_options"));

//get_links_config
function theme_get_links_config($params = null)
{
	global $themename;
	$result = array();
	//get pages
	query_posts(array( 
		'posts_per_page' => -1,
		'post_type' => 'page',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC' 
	));
	$i = 0;
	
	if(have_posts()) : while (have_posts()) : the_post(); 
		global $post;
		$direction = get_post_meta(get_the_ID(), $themename . "_direction", true);
		$result["link"][] = $post->post_name;
		$result["post_id"][] = get_the_ID();
		$result["title"][] = get_the_title();
		$result["icon"][] = get_post_meta(get_the_ID(), $themename . "_icon", true);
		$result["direction"][] = ($direction=='' ? 'left' : $direction);
		$i++;
	endwhile; endif;
	
	//Reset Query
	wp_reset_query();
	
	//get posts
	query_posts(array( 
		'posts_per_page' => -1,
		'post_type' => 'post',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC' 
	));
	if(have_posts()) : while (have_posts()) : the_post(); 
		global $post;
		$result["link"][] = $post->post_name;
		$result["post_id"][] = get_the_ID();
		$result["title"][] = get_the_title();
	endwhile; endif;
	
	//Reset Query
	wp_reset_query();
	
	return $result;
}

function theme_enqueue_scripts()
{
	global $themename;
	global $theme_options;
	//js
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-accordion");
	//wp_enqueue_script("jquery-ba-bqq", get_template_directory_uri() . "/js/jquery.ba-bqq.min.js", array("jquery"));
	wp_enqueue_script("jquery-history", get_template_directory_uri() . "/js/jquery.history.js", array("jquery"));
	wp_enqueue_script("jquery-easing", get_template_directory_uri() . "/js/jquery.easing.js", array("jquery"));
	wp_enqueue_script("jquery-block-ui", get_template_directory_uri() . "/js/jquery.blockUI.js", array("jquery"));
	wp_enqueue_script("jquery-bx-slider", get_template_directory_uri() . "/js/jquery.bxSlider.js", array("jquery"));
	wp_enqueue_script("jquery-qtip", get_template_directory_uri() . "/js/jquery.qtip.min.js", array("jquery"));
	wp_enqueue_script("jquery-fancybox", get_template_directory_uri() . "/js/jquery.fancybox.js", array("jquery"));
	wp_enqueue_script("jquery-mousewheel", get_template_directory_uri() . "/js/jquery.mousewheel.js", array("jquery"));
	wp_enqueue_script("jquery-jscrollpane", get_template_directory_uri() . "/js/jquery.jscrollpane.min.js", array("jquery"));
	wp_enqueue_script("jquery-supersized", get_template_directory_uri() . "/js/jquery.supersized.min.js", array("jquery"));
	wp_enqueue_script("jquery-jplayer", get_template_directory_uri() . "/js/jquery.jplayer.min.js", array("jquery"));
	wp_enqueue_script("jquery-supersized-shutter", get_template_directory_uri() . "/js/jquery.supersized.shutter.min.js", array("jquery"));
	wp_enqueue_script("google-maps-v3", "http://maps.google.com/maps/api/js?sensor=false");
	
	wp_enqueue_script("theme-script", get_template_directory_uri() . "/js/script.js", array("jquery"));
    wp_enqueue_script("theme-nostalgia", get_template_directory_uri() . "/js/theme.js", array("jquery"));
	wp_enqueue_script("theme-main", get_template_directory_uri() . "/js/main.js", array("jquery"));
	wp_enqueue_script("theme-comment-form", get_template_directory_uri() . "/js/theme_comment_form.js", array("jquery"));
	wp_enqueue_script("theme-contact-form", get_template_directory_uri() . "/js/theme_contact_form.js", array("jquery"));
	
	$data = theme_get_links_config();
	//backgrounds
	$data["backgrounds"] = $theme_options["backgrounds"];
	// CYCLE THROUGHT ALL POST AND CHECK THEIR POSTMETA FOR BACKGROUND IMG
	// IF BACKGROUND IMG IS SET, ADD IT TO THE $data['backgrounds'] ARRAY
	query_posts(array(
	'post_type' => 'page',
	'post_status' => 'publish'
			));
	
	if(have_posts()) : while (have_posts()) : the_post();
	global $post;
	$post_background_img = get_post_meta(get_the_ID(), $themename . "_background_img", true);
	if(!empty($post_background_img))
		$data["backgrounds"][]=$post_background_img;
	endwhile; endif;
	wp_reset_query();
	
	$data["background_title"] = $theme_options["background_title"];
	//autoplay
	$data["autoplay"] = $theme_options["autoplay"];
	//ajaxurl
	$data["ajaxurl"] = get_template_directory_uri() . "/theme-ajax.php";
	//themename
	$data["themename"] = $themename;
	//transition
	$data["transition"] = ($theme_options["transition"]!="" ? $theme_options["transition"] : "fade");
	$data["transition_speed"] = ((int)$theme_options["transition_speed"]>0 ? (int)$theme_options["transition_speed"] : 750);
	$data["slide_interval"] = ((int)$theme_options["slide_interval"]>0 ? (int)$theme_options["slide_interval"] : 5000);
	//music
	$data["tracks"] = $theme_options["tracks"];
	$data["music_loop"] = $theme_options["music_loop"];
	$data["music_autoplay"] = $theme_options["music_autoplay"];
	$data["swfPath"] = get_template_directory_uri() . "/js";
	//menu count
	$data["menu_count"] = ((int)$theme_options["menu_count"]>0 ? (int)$theme_options["menu_count"] : 5);
	$data["menu_height"] = $data["menu_count"]*50+35;
	//options
	$data["display_menu_on_start"] = (int)$theme_options["display_menu_on_start"];
	$data["responsive"] = (int)$theme_options["responsive"];
	//home url
	$data["home_url"] = get_home_url();
	//site description
	$data["blog_name"] = get_bloginfo('name');
	$data["blog_desc"] = get_bloginfo('description');
	//pass data to javascript
	$params = array(
		'l10n_print_after' => 'config = ' . json_encode($data) . ';'
	);
	wp_localize_script("theme-main", "config", $params);
	
	//css
	if((int)$theme_options["responsive"])
		wp_enqueue_style("responsive", get_template_directory_uri() . "/style/responsive.css");
	wp_enqueue_style("jquery-jscrollpane", get_template_directory_uri() . "/style/jquery.jScrollPane.css");
	wp_enqueue_style("jquery-qtip", get_template_directory_uri() . "/style/jquery.qtip.css");
	wp_enqueue_style("jquery-ui", get_template_directory_uri() . "/style/jquery-ui/jquery-ui.css");
	wp_enqueue_style("jquery-fancybox", get_template_directory_uri() . "/style/fancybox/jquery.fancybox.css");
	wp_enqueue_style("google-font-voces", "http://fonts.googleapis.com/css?family=Voces");
	wp_enqueue_style("google-font-dosis", "http://fonts.googleapis.com/css?family=Dosis:400,300,200,500,600,700,800");
	wp_enqueue_style("google-font-aldrich", "http://fonts.googleapis.com/css?family=Aldrich");
}
add_action("wp_enqueue_scripts", "theme_enqueue_scripts");

//get theme
function theme_get($params)
{
	global $wpdb;
	global $themename;
	$result = array();

	//get pages
	query_posts(array( 
		'post_type' => 'page',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'orderby' => 'menu_order', 
		'order' => 'ASC'
	));
	$i = 0;
	$result["custom_css"] = "";
	$result["html"] = "";
	if(have_posts()) : while (have_posts()) : the_post();
		global $post;
		$color = get_post_meta(get_the_ID(), $themename . "_color", true);
		if($color!='')
		{
			$rgba = html2rgb($color);
			$result["custom_css"] .= '.nostalgia-menu-item-' . $post->post_name . ':hover, .nostalgia-menu-item-' . $post->post_name . '.menu-item-selected,
			.nostalgia-tab-' . $post->post_name . ',
			.nostalgia-tab-' . $post->post_name . ' #nostalgia-tab-content .jspTrack,
			.nostalgia-tab-' . $post->post_name . ' #nostalgia-tab-footer ul.social-list
			{
				background-color: #' . $color . ' !important;
			}
			.nostalgia-tab-' . $post->post_name . ' #nostalgia-tab-content
			{
				background-color:rgba(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',0.9);
				-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5' . $color . ',endColorstr=#E5' . $color . ');
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5' . $color . ',endColorstr=#E5' . $color . ');
			}
			.nostalgia-tab-' . $post->post_name . ' #nostalgia-tab-footer,
			.nostalgia-tab-' . $post->post_name . ' #nostalgia-tab-footer a,
			.nostalgia-tab-' . $post->post_name . ' div.post div.post-image div.caption,
			.nostalgia-tab-' . $post->post_name . ' ul.blog-list li.blog-list-post div.blog-list-post-image div.caption,
			.nostalgia-tab-' . $post->post_name . ' ul.image-list div.image-list-caption div.image-list-caption-subtitle
			{
				color: #' . $color . ';
			}';
		}
		$custom_url = get_post_meta(get_the_ID(), $themename . "_custom_url", true);
		if($params['type']=='dropdown')
			$result["html"] .= '
			<option value="' . ($custom_url!="" ? $custom_url : $post->post_name) . '" class="nostalgia-menu-item nostalgia-menu-item-' . $post->post_name . '">' . get_the_title() . '</option>';
		else
			$result["html"] .= '
			<li id="nostalgia-menu-element-' .  get_the_ID() . '" class="nostalgia-menu-element-' . ($i+1) . '">
				<a href="' . ($custom_url!="" ? $custom_url : $post->post_name) . '" class="nostalgia-menu-item nostalgia-menu-item-' . $post->post_name . '">
				' . get_the_title() . '
				</a>
			</li>';
		$i++;
	endwhile; endif;
	return $result;
}

//ajax
function theme_get_content()
{
	$result = array();
	if($_GET["name"]!='')
	{
		//post
		query_posts("name=" . $_GET["name"] . "&post_type=post");
		if(have_posts()) : the_post();
			if($_GET["parent_name"]!="")
			{
				global $parent;
				$args=array(
				  'name' => $_GET["parent_name"],
				  'post_type' => 'page',
				  'post_status' => 'publish',
				  'showposts' => 1
				);
				$parentArray = get_posts($args);
				$parent = $parentArray[0];
			}
			ob_start();
			if($_GET["type"]=="get_comments")
				comments_template();
			else
				include("single-blog.php");
			$result["html"] = ob_get_contents();
			ob_end_clean();
		else:
			//page
			query_posts("name=" . $_GET["name"] . "&post_type=page");
			if(have_posts()) : the_post(); 
				global $themename;
				$background_img = get_post_meta(get_the_ID(), $themename."_background_img", true);
				if(!empty($background_img))
					$result["background_img"] = $background_img;
				$template = get_post_meta(get_the_ID(), '_wp_page_template', true);
				if($template!="" && $template!="default")
				{
					ob_start();
					$included = 1;
					include($template);
					$result["html"] = ob_get_contents();
					ob_end_clean();
				}
				else
					$result["html"] = do_shortcode(get_the_content());
			endif;
		endif;
		//contact form 7
		if(defined('WPCF7_PLUGIN_DIR') && WPCF7_LOAD_JS)
			$result["cf7"] = plugins_url('scripts.js', WPCF7_PLUGIN_DIR . "/includes/js/scripts.js");
	}
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_theme_get_content", "theme_get_content");
add_action("wp_ajax_nopriv_theme_get_content", "theme_get_content");

function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}
?>