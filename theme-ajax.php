<?php
if(!isset( $_REQUEST['action']))
	exit('-1');
	
require_once('../../../wp-load.php');
define('DOING_AJAX', true);
if(current_user_can('administrator'))
	define('WP_ADMIN', true);
do_action('wp_ajax_' . $_REQUEST['action']);
do_action('wp_ajax_nopriv_' . $_REQUEST['action']);
?>