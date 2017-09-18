<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html <?php language_attributes(); ?>>
	<?php global $theme_options;?>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo("html_type"); ?>; charset=<?php bloginfo("charset"); ?>" />
		<meta name="generator" content="WordPress <?php bloginfo("version"); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<title><?php bloginfo('name'); ?> | <?php is_home() || is_front_page() || is_404() ? bloginfo('description') : wp_title(''); ?></title>
		<link rel="stylesheet" href="<?php bloginfo("stylesheet_url"); ?>" type="text/css" media="screen" />
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo("rss2_url"); ?>" />
		<link rel="pingback" href="<?php bloginfo("pingback_url"); ?>" />
		<?php
		wp_head(); 
		?>
		<link rel="shortcut icon" href="<?php bloginfo("template_directory"); ?>/images/favicon.ico" />
		<?php
		if($theme_options["color"]!='FFC000' && $theme_options["color"]!=''):
		$rgba = html2rgb($theme_options["color"]);
		?>
		<style type="text/css">
			#nostalgia-tab,
			#start-preloader ul li,
			#nostalgia-tab-content,
			#nostalgia-navigation-name-box,
			#nostalgia-tab-footer ul.social-list,
			#nostalgia-navigation-close-button:hover,
			#nostalgia-navigation-menu ul li a:hover,
			#nostalgia-navigation-menu .bx-prev:hover,
			#nostalgia-navigation-menu .bx-next:hover,
			.bx-wrapper-twitter .bx-prev:hover,
			.bx-wrapper-twitter .bx-next:hover,
			#nostalgia-navigation-menu ul li a.menu-item-selected,
			#nostalgia-tab-content .jspTrack,
			.jPlayerControl:hover,
			.widgetControl:hover,
			#prevslide:hover, 
			#nextslide:hover
			{
				background-color: #<?php echo $theme_options["color"];?>;
			}
			#nostalgia-tab-footer,
			#nostalgia-tab-footer a,
			.post div.caption a,
			ul.blog-list li.blog-list-post div.blog-list-post-image div.caption a,
			#slidecaption-wrapper span.supersized-caption-title,
			#slidecaption a,
			.widget_twitter ul li a,
			.widget.latest_post a.read-more
			{
				color: #<?php echo $theme_options["color"];?>;
			}
			#nostalgia-tab-content
			{
				background-color:rgba(<?php echo $rgba[0]; ?>,<?php echo $rgba[1]; ?>,<?php echo $rgba[2]; ?>,0.9);
				-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5<?php echo $theme_options["color"]; ?>,endColorstr=#E5<?php echo $theme_options["color"]; ?>);
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#E5<?php echo $theme_options["color"]; ?>,endColorstr=#E5<?php echo $theme_options["color"]; ?>);
			}
			.icon-2.icon-2-1	{ background-image:url('<?php echo get_template_directory_uri(); ?>/images/icon/icon-2/icon_mini_phone_white.png');			}
			.icon-2.icon-2-2	{ background-image:url('<?php echo get_template_directory_uri(); ?>/images/icon/icon-2/icon_mini_fax_white.png');			}
			.icon-2.icon-2-3	{ background-image:url('<?php echo get_template_directory_uri(); ?>/images/icon/icon-2/icon_mini_mail_white.png');			}
			ul.image-list div.image-list-caption, div.contact-details-about
			{
				background-image: url('<?php echo get_template_directory_uri(); ?>/images/icon_plus.png');
			}
			a.fancybox-image,
			a.fancybox-video,
			a.audio-item
			{
				background-image: url('<?php echo get_template_directory_uri(); ?>/images/preloader2.gif');
			}
			#nostalgia-tab-content .jspDrag
			{
				background: none;
				background-color: rgba(0, 0, 0, 0.2);
				-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#33000000,endColorstr=#33000000);
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#33000000,endColorstr=#33000000);
				zoom: 1;
			}
		</style>
		<?php
		endif;
		?>
		<style type="text/css">
			@media only screen and (max-height: <?php echo (int)$theme_options["menu_count"]*50+35+265; ?>px)
			{
				#nostalgia-navigation-name-box
				{
					position: static;
					margin-top: 2px;
				}
				#nostalgia-navigation-click-here-box
				{
					position: relative;
					margin-top: -65px;
				}
			}
		</style>
	</head>
	<body <?php body_class(); ?>>
		<div id="nostalgia">
	<!-- /Header -->