<?php
if(have_comments()):
	?>
	<h3>
		<?php comments_number(); ?>
	</h3>
	<ul class="comments_list">
	<?php
	paginate_comments_links();
	wp_list_comments(array(
		'avatar_size' => 60,
		'page' => (int)$_GET["paged"],
		'per_page' => '5',
		'callback' => 'theme_comments_list'
	));
	?>
	</ul>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$(".reply_button").click(function(event){
			event.preventDefault();
			if(!((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))||(navigator.userAgent.match(/Android/i))))
			{
				var api =  $('#nostalgia-tab-content-scroll').jScrollPane({maintainPosition:true,autoReinitialise:false}).data('jsp');
				api.scrollToElement($("#comment_form"));
			}
			$("#comment_form [name='comment_parent_id']").val($(this).attr("href").substr(1));
			$("#cancel_comment").css('display', 'block');
		});
		$("#cancel_comment").click(function(event){
			event.preventDefault();
			$(this).css('display', 'none');
			$("#comment_form [name='comment_parent_id']").val(0);
		});
	});
	</script>
	<?php
	global $parent;
	if($parent)
		$parent_url = $parent->post_name;
	global $post;
	$comment_parent_url =  $parent_url . "/" . $post->post_name;
	$query = "SELECT COUNT(*) AS count FROM $wpdb->comments WHERE comment_approved = 1 AND comment_post_ID = " . get_the_ID() . " AND comment_parent = 0";
    $parents = $wpdb->get_row($query);
	if($parents->count>5)
		comments_pagination(2, ceil($parents->count/5), $comment_parent_url);
endif;
function theme_comments_list($comment, $args, $depth)
{
	global $themename;
	global $parent;
	global $post;
	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class('clear-fix'); ?> id="li-comment-<?php comment_ID() ?>">
		<div class="comment-author-avatar">
			<?php echo get_avatar( $comment->comment_author_email, $args['avatar_size'] ); ?>
		</div>
		<div class="comment-details">
			<h5 class="comment-header">
				<?php 
				comment_author_link();
				printf(__(', %1$s at %2$s'), get_comment_date(),  get_comment_time());
				edit_comment_link(__('(Edit)'),'&nbsp;&nbsp;&nbsp;','');
				if((int)$comment->comment_parent>0)
				{
					if($parent)
						$parent_url = $parent->post_name;
					echo '<br /><a class="show_source_comment" href="' . $parent_url . "/" . $post->post_name . ((int)$_GET["paged"]>0 ? "/page-" . $_GET["paged"] . "/" : "") . '#li-comment-' . (int)$comment->comment_parent . '" title="' . __('Show comment', $themename) . '">';
					_e('in reply to ', $themename);
					$comment_parent = get_comment($comment->comment_parent);
					echo $comment_parent->comment_author . '</a>';
				}
				?>
			</h5>
			<?php
			comment_text();
			?>
			<a class="reply_button" href="#<?php comment_ID(); ?>" title="<?php _e('Reply', $themename); ?>">
				<?php _e('Reply', $themename); ?>
			</a>
		</div>
<?php
}
function comments_pagination($range, $pages, $parent_url)
{
	global $themename;
	$paged = ((int)$_GET["paged"]==0 ? 1 : (int)$_GET["paged"]);
	$showitems = ($range * 2)+1;
	echo "<ul class='" . $themename . "_pagination clear-fix'>";
	if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='" . $parent_url . "/page-1/" . "' class='pagination_arrow'>&laquo;</a></li>";
	if($paged > 1 && $showitems < $pages) echo "<li><a href='" . $parent_url . "/page-" . ($paged-1) . "/" . "' class='pagination_arrow'>&lsaquo;</a></li>";

	for ($i=1; $i <= $pages; $i++)
	{
		if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		{
			echo "<li>" . ($paged == $i ? "<span class='selected'>".$i."</span>":"<a href='" . $parent_url . "/page-" . $i . "/" . "'>".$i."</a>") . "</li>";
		}
	}

	if ($paged < $pages && $showitems < $pages) echo "<li><a href='" . $parent_url . "/page-" . ($paged+1) . "/" . "' class='pagination_arrow'>&rsaquo;</a></li>";  
	if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='" . $parent_url . "/page-" . $pages . "/" . "' class='pagination_arrow'>&raquo;</a></li>";
	echo "</ul>";
}
?>