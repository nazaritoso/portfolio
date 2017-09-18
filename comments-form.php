<?php global $themename; ?>
<h3 class="comment_form_head"><?php _e('Leave a comment', $themename); ?><a href="#cancel" id="cancel_comment" title="<?php _e('Cancel reply', $themename); ?>"><?php _e('Cancel reply', $themename); ?></a></h3>
<form class="comment_form" id='comment_form'>
	<div class="clear-fix form-line">
		<div class="float-left">
			<input type="text" name="comment-user-name" id="comment-user-name" value="<?php _e('Your Name', $themename); ?>" onfocus="clearInput(this,'focus','<?php _e('Your Name', $themename); ?>')" onblur="clearInput(this,'blur','<?php _e('Your Name', $themename); ?>')"/>	
		</div>
		<div class="float-right">
			<input type="text" name="comment-user-email" id="comment-user-email" value="<?php _e('Your Email Address', $themename); ?>" onfocus="clearInput(this,'focus','<?php _e('Your Email Address', $themename); ?>')" onblur="clearInput(this,'blur','<?php _e('Your Email Address', $themename); ?>')"/>	
		</div>
	</div>
	<div class="clear-fix form-line">
		<textarea rows="0" cols="0" name="comment-message" id="comment-message" onfocus="clearInput(this,'focus','<?php _e('Message', $themename); ?>')" onblur="clearInput(this,'blur','<?php _e('Message', $themename); ?>')"><?php _e('Message', $themename); ?></textarea>	
	</div>
	<div class="clear-fix form-line">
		<a href="javascript:submitCommentForm();" class="button block" id="comment-send"><?php _e('Post comment', $themename); ?></a>
		<input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID()); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo $parent->ID; ?>" />
		<input type="hidden" name="action" value="theme_comment_form" />
		<input type="hidden" name="comment_parent_id" value="0" />
		<input type="hidden" name="paged" value="1" />
	</div>
</form>