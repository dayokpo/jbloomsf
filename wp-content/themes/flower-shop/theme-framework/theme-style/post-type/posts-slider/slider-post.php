<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.5
 * 
 * Posts Slider Post Template
 * Created by CMSMasters
 * 
 */


$cmsmasters_metadata = explode(',', $cmsmasters_post_metadata);


$title = in_array('title', $cmsmasters_metadata) ? true : false;
$excerpt = (in_array('excerpt', $cmsmasters_metadata) && flower_shop_slider_post_check_exc_cont('post')) ? true : false;
$date = in_array('date', $cmsmasters_metadata) ? true : false;
$categories = (get_the_category() && (in_array('categories', $cmsmasters_metadata))) ? true : false;
$author = in_array('author', $cmsmasters_metadata) ? true : false;
$comments = (comments_open() && (in_array('comments', $cmsmasters_metadata))) ? true : false;
$likes = in_array('likes', $cmsmasters_metadata) ? true : false;
$more = in_array('more', $cmsmasters_metadata) ? true : false;


$cmsmasters_post_format = get_post_format();

?>
<!-- Start Posts Slider Post Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class('cmsmasters_slider_post'); ?>>
	<div class="cmsmasters_slider_post_outer">
	<?php		
		flower_shop_thumb_rollover(get_the_ID(), 'cmsmasters-blog-masonry-thumb', false, false, false, false, false, false, false, false, true, false, false);
		
		$date ? flower_shop_get_slider_post_date('post') : '';
		
		$title ? flower_shop_slider_post_heading(get_the_ID(), 'post', 'h5') : '';
		
		
		if ($author || $categories || $likes || $comments) {
			echo '<div class="cmsmasters_slider_post_inner">';
				
				if ($likes || $comments) {
					echo '<footer class="cmsmasters_slider_post_footer entry-meta">';
						
						$likes ? flower_shop_slider_post_like('post') : '';
						
						$comments ? flower_shop_get_slider_post_comments('post') : '';
						
					echo '</footer>';
				}
				
				
				if ($author || $categories) {
					echo '<div class="cmsmasters_slider_post_cont_info entry-meta">';
						
						$author ? flower_shop_get_slider_post_author('post') : '';
						
						$categories ? flower_shop_get_slider_post_category(get_the_ID(), 'category', 'post') : '';
						
					echo '</div>';
				}
				
			echo '</div>';
		}
		
		$excerpt ? flower_shop_slider_post_exc_cont('post') : '';
		
		$more ? flower_shop_slider_post_more(get_the_ID()) : '';
		
	?>
	</div>
</article>
<!-- Finish Posts Slider Post Article -->

