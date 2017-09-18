<?php
class Blog_Category_Walker extends Walker_Category
{
	public function start_el(&$output, $category, $depth, $args)
	{
		global $themename;
		extract($args);
		$cat_name = esc_attr($category->name);
		$cat_name = apply_filters('list_cats', $cat_name, $category);
		if($args["style"]=="list")
			$output .= '<li class="' . $themename . '_post_category">';
		$output .= '<a' . ((int)$_GET["category_id"]==(int)$category->term_id ? ' class="category-selected"' : '') . ' id="category-' . $category->term_id . '" href="' . $parent_url . '/category-' . $category->term_id . '/" ';
		if($use_desc_for_title == 0 || empty($category->description))
			$output .= 'title="' . sprintf(__('View all posts filed under %s', $themename), $cat_name) . '"';
		else
			$output .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		$output .= '>';
		$output .= $cat_name;
		if(isset($show_count) && $show_count)
			$output .= ' <strong>[' . intval($category->count) . ']</strong>';
		$output .= '</a>';
	}
	
	 function end_el(&$output, $page, $depth, $args) 
	 {
		if($args["style"]!="list")
			return;
		$output .= "</li>";
	} 
}
?>