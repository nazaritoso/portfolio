<?php
//comment form submit
function theme_comment_form()
{
	global $themename;
	require_once("form-functions.php");
    $response=array('error'=>0,'info'=>null);

    $values=array
    (
        'comment-user-name' => $_POST['comment-user-name'],
        'comment-user-email' => $_POST['comment-user-email'],
        'comment-message' => $_POST['comment-message']
    );

    if(isEmpty($values['comment-user-name']) || strcmp($values['comment-user-name'], __('Your Name', $themename))==0)
    {
        $response['error']=1;
        $response['info'][]=array('fieldId'=>'comment-user-name','message'=> __('Please enter your name',  $themename));
    }

    if(!validateEmail($values['comment-user-email']) || strcmp($values['comment-user-email'], __('Your Email Address',  $themename))==0)
    {
        $response['error']=1;	
        $response['info'][]=array('fieldId'=>'comment-user-email','message'=> __('Please enter valid e-mail address',  $themename));
    }

    if(isEmpty($values['comment-message']) || strcmp($values['comment-message'], __('Message',  $themename))==0)
    {
        $response['error']=1;
        $response['info'][]=array('fieldId'=>'comment-message','message'=> __('Please enter your message',  $themename));
    }	

    if($response['error']==1) createResponse($response);

    if(isGPC()) $values=array_map('stripslashes',$values);

    $values=array_map('htmlspecialchars',$values);
	
	$time = current_time('mysql');

	$data = array(
		'comment_post_ID' => (int)$_POST['post_id'],
		'comment_author' => $values['comment-user-name'],
		'comment_author_email' => $values['comment-user-email'],
		'comment_content' => $values['comment-message'],
		'comment_parent' => (int)$_POST['parent_comment_id'],
		'comment_date' => $time,
		'comment_approved' => ((int)get_option('comment_moderation') ? 0 : 1),
		'comment_parent' => (int)$_POST['comment_parent_id']
	);

	if(wp_insert_comment($data))
	{
		$response['error']=0;
		$response['info'][]=array('fieldId'=>'comment-send','message'=> __('Your comment has been added',  $themename));
		//get post comments
		//post
		query_posts("p=" . (int)$_POST['post_id'] . "&post_type=post");
		if(have_posts()) : the_post(); 
			if((int)$_POST["parent_id"]>0)
			{
				global $parent;
				$parent = get_post($_POST["parent_id"]);
			}
			ob_start();
			if((int)$_POST['comment_parent_id']==0)
			{
				global $wpdb;
				$query = "SELECT COUNT(*) AS count FROM $wpdb->comments WHERE comment_approved = 1 AND comment_post_ID = " . get_the_ID() . " AND comment_parent = 0";
				$parents = $wpdb->get_row($query);
				$_GET["paged"] = ceil($parents->count/5);
				if($parent)
					$parent_url = $parent->post_name;
				global $post;
				$response['change_url'] = $parent_url . "/" . $post->post_name . "/page-" . $_GET["paged"] . "/";
			}
			else
				$_GET["paged"] = (int)$_POST["paged"];
			comments_template();
			$response['html'] = ob_get_contents();
			ob_end_clean();
		endif;
	}
	else
	{
		$response['error']=1;	
        $response['info'][]=array('fieldId'=>'comment-send','message'=> __('Error while adding comment',  $themename));
        	
	}
	createResponse($response);
}
add_action("wp_ajax_theme_comment_form", "theme_comment_form");
add_action("wp_ajax_nopriv_theme_comment_form", "theme_comment_form");
?>