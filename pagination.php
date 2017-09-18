<?php
function kriesi_pagination($pages = '', $range = 2, $parent_url)
{
	global $themename;
	$showitems = ($range * 2)+1;  

    $paged = ((int)$_GET["paged"]==0 ? 1 : (int)$_GET["paged"]);

	if($pages == '')
	{
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages)
		{
			$pages = 1;
		}
	}   

	if(1 != $pages)
	{
		echo "<ul class='" . $themename . "_pagination'>";
		if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='" . home_url($parent_url . ((int)$_GET["category_id"]>0 ? "/category-" . (int)$_GET["category_id"] : "") . "/page-1/") . "' class='pagination_arrow'>&laquo;</a></li>";
		if($paged > 1 && $showitems < $pages) echo "<li><a href='" . home_url($parent_url . ((int)$_GET["category_id"]>0 ? "/category-" . (int)$_GET["category_id"] : "") . "/page-" . ($paged-1) . "/") . "' class='pagination_arrow'>&lsaquo;</a></li>";

		for ($i=1; $i <= $pages; $i++)
		{
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			{
				echo "<li>" . ($paged == $i ? "<span class='selected'>".$i."</span>":"<a href='" . home_url($parent_url . ((int)$_GET["category_id"]>0 ? "/category-" . (int)$_GET["category_id"] : "") . "/page-" . $i . "/") . "'>".$i."</a>") . "</li>";
			}
		}

		if ($paged < $pages && $showitems < $pages) echo "<li><a href='" . home_url($parent_url . ((int)$_GET["category_id"]>0 ? "/category-" . (int)$_GET["category_id"] : "") . "/page-" . ($paged+1) . "/") . "' class='pagination_arrow'>&rsaquo;</a></li>";  
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='" . home_url($parent_url . ((int)$_GET["category_id"]>0 ? "/category-" . (int)$_GET["category_id"] : "") . "/page-" . $pages . "/") . "' class='pagination_arrow'>&raquo;</a></li>";
		echo "</ul>";
	}
}
?>