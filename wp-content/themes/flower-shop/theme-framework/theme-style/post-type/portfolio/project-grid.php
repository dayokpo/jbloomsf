<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.5
 * 
 * Project Grid Template
 * Created by CMSMasters
 * 
 */


$cmsmasters_project_metadata = explode(',', $cmsmasters_pj_metadata);


$title = (in_array('title', $cmsmasters_project_metadata)) ? true : false;
$excerpt = (in_array('excerpt', $cmsmasters_project_metadata) && flower_shop_project_check_exc_cont()) ? true : false;
$categories = (get_the_terms(get_the_ID(), 'pj-categs') && (in_array('categories', $cmsmasters_project_metadata))) ? true : false;
$comments = (comments_open() && (in_array('comments', $cmsmasters_project_metadata))) ? true : false;
$likes = (in_array('likes', $cmsmasters_project_metadata)) ? true : false;
$rollover = in_array('rollover', $cmsmasters_project_metadata) ? true : false;
$more = (in_array('more', $cmsmasters_project_metadata)) ? true : false;


$cmsmasters_project_link_url = get_post_meta(get_the_ID(), 'cmsmasters_project_link_url', true);

$cmsmasters_project_link_redirect = get_post_meta(get_the_ID(), 'cmsmasters_project_link_redirect', true);

$cmsmasters_project_link_target = get_post_meta(get_the_ID(), 'cmsmasters_project_link_target', true);


$project_sort_categs = get_the_terms(0, 'pj-categs');

$project_categs = '';

if ($project_sort_categs != '') {
	foreach ($project_sort_categs as $project_sort_categ) {
		$project_categs .= ' ' . $project_sort_categ->slug;
	}
	
	$project_categs = ltrim($project_categs, ' ');
}


$cmsmasters_post_format = get_post_format();

?>
<!-- Start Project Grid Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class('cmsmasters_project_grid'); echo (($project_categs != '') ? ' data-category="' . esc_attr($project_categs) . '"' : '') ?>>
	<div class="project_outer">
	<?php 
		echo '<div class="project_img_wrap">';
		
		flower_shop_thumb_rollover(get_the_ID(), 'cmsmasters-project-grid-thumb', false, $rollover, false, false, false, false, false, false, true, $cmsmasters_project_link_redirect, $cmsmasters_project_link_url, $cmsmasters_project_link_target);
		
		echo '</div>';
		
		
		if ($categories) {
			echo '<div class="cmsmasters_project_cont_info entry-meta">';
				
				flower_shop_get_project_category(get_the_ID(), 'pj-categs', 'page');
				
			echo '</div>';
		}
		
		
		$title ? flower_shop_project_heading(get_the_ID(), 'h5', $cmsmasters_project_link_redirect, $cmsmasters_project_link_url, $cmsmasters_project_link_target) : '';
		
		$excerpt ? flower_shop_project_exc_cont() : '';
		
		
		if ($more || $likes || $comments) {
			echo '<footer class="cmsmasters_project_footer entry-meta">';
				
				$more ? cmsmasters_project_more(get_the_ID()) : '';
				
				
				if ($likes || $comments) {
					echo '<div class="cmsmasters_project_info">';
						
						$likes ? flower_shop_get_project_likes('page') : '';
						
						$comments ? flower_shop_get_project_comments('page') : '';
						
					echo '</div>';
				}
				
			echo '</footer>';
		}
		
		
		if (!$title) {
			echo '<div class="dn">';
				flower_shop_project_heading(get_the_ID(), 'h6');
			echo '</div>';
		}
		
		echo '<span class="dn meta-date">' . get_the_time('YmdHis') . '</span>';
	?>
	</div>
</article>
<!-- Finish Project Grid Article -->

