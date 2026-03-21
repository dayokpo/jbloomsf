<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.5
 * 
 * Admin Panel Post, Project, Profile Settings
 * Created by CMSMasters
 * 
 */


function flower_shop_options_single_tabs() {
	$tabs = array();
	
	
	$tabs['post'] = esc_attr__('Post', 'flower-shop');
	
	if (CMSMASTERS_PROJECT_COMPATIBLE && class_exists('Cmsmasters_Projects')) {
		$tabs['project'] = esc_attr__('Project', 'flower-shop');
	}
	
	if (CMSMASTERS_PROFILE_COMPATIBLE && class_exists('Cmsmasters_Profiles')) {
		$tabs['profile'] = esc_attr__('Profile', 'flower-shop');
	}
	
	
	return apply_filters('cmsmasters_options_single_tabs_filter', $tabs);
}


function flower_shop_options_single_sections() {
	$tab = flower_shop_get_the_tab();
	
	
	switch ($tab) {
	case 'post':
		$sections = array();
		
		$sections['post_section'] = esc_attr__('Blog Post Options', 'flower-shop');
		
		
		break;
	case 'project':
		$sections = array();
		
		$sections['project_section'] = esc_attr__('Portfolio Project Options', 'flower-shop');
		
		
		break;
	case 'profile':
		$sections = array();
		
		$sections['profile_section'] = esc_attr__('Person Block Profile Options', 'flower-shop');
		
		
		break;
	default:
		$sections = array();
		
		
		break;
	}
	
	
	return apply_filters('cmsmasters_options_single_sections_filter', $sections, $tab);
} 


function flower_shop_options_single_fields($set_tab = false) {
	if ($set_tab) {
		$tab = $set_tab;
	} else {
		$tab = flower_shop_get_the_tab();
	}
	
	
	$options = array();
	
	
	$defaults = flower_shop_settings_single_defaults();
	
	
	switch ($tab) {
	case 'post':
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_layout', 
			'title' => esc_html__('Layout Type', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio_img', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_layout'], 
			'choices' => array( 
				esc_html__('Right Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_r.jpg' . '|r_sidebar', 
				esc_html__('Left Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_l.jpg' . '|l_sidebar', 
				esc_html__('Full Width', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/fullwidth.jpg' . '|fullwidth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_title', 
			'title' => esc_html__('Post Title', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_title'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_date', 
			'title' => esc_html__('Post Date', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_date'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_cat', 
			'title' => esc_html__('Post Categories', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_cat'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_author', 
			'title' => esc_html__('Post Author', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_author'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_comment', 
			'title' => esc_html__('Post Comments', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_comment'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_tag', 
			'title' => esc_html__('Post Tags', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_tag'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_like', 
			'title' => esc_html__('Post Likes', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_like'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_nav_box', 
			'title' => esc_html__('Posts Navigation Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_nav_box'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_nav_order_cat', 
			'title' => esc_html__('Posts Navigation Order by Category', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_nav_order_cat'] 
		);
		
		if (class_exists('Cmsmasters_Content_Composer')) {
			$options[] = array( 
				'section' => 'post_section', 
				'id' => 'flower-shop' . '_blog_post_share_box', 
				'title' => esc_html__('Sharing Box', 'flower-shop'), 
				'desc' => esc_html__('show', 'flower-shop'), 
				'type' => 'checkbox', 
				'std' => $defaults[$tab]['flower-shop' . '_blog_post_share_box'] 
			);
		}
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_post_author_box', 
			'title' => esc_html__('About Author Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_post_author_box'] 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_more_posts_box', 
			'title' => esc_html__('More Posts Box', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_more_posts_box'], 
			'choices' => array( 
				esc_html__('Show Related Posts', 'flower-shop') . '|related', 
				esc_html__('Show Popular Posts', 'flower-shop') . '|popular', 
				esc_html__('Show Recent Posts', 'flower-shop') . '|recent', 
				esc_html__('Hide More Posts Box', 'flower-shop') . '|hide' 
			) 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_more_posts_count', 
			'title' => esc_html__('More Posts Box Items Number', 'flower-shop'), 
			'desc' => esc_html__('posts', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_more_posts_count'], 
			'min' => '2', 
			'max' => '20' 
		);
		
		$options[] = array( 
			'section' => 'post_section', 
			'id' => 'flower-shop' . '_blog_more_posts_pause', 
			'title' => esc_html__('More Posts Slider Pause Time', 'flower-shop'), 
			'desc' => esc_html__("in seconds, if '0' - autoslide disabled", 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_blog_more_posts_pause'], 
			'min' => '0', 
			'max' => '20' 
		);
		
		
		break;
	case 'project':
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_title', 
			'title' => esc_html__('Project Title', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_title'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_details_title', 
			'title' => esc_html__('Project Details Title', 'flower-shop'), 
			'desc' => esc_html__('Enter a project details block title', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_details_title'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_date', 
			'title' => esc_html__('Project Date', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_date'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_cat', 
			'title' => esc_html__('Project Categories', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_cat'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_author', 
			'title' => esc_html__('Project Author', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_author'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_comment', 
			'title' => esc_html__('Project Comments', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_comment'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_tag', 
			'title' => esc_html__('Project Tags', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_tag'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_like', 
			'title' => esc_html__('Project Likes', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_like'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_link', 
			'title' => esc_html__('Project Link', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_link'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_share_box', 
			'title' => esc_html__('Sharing Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_share_box'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_nav_box', 
			'title' => esc_html__('Projects Navigation Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_nav_box'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_nav_order_cat', 
			'title' => esc_html__('Projects Navigation Order by Category', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_nav_order_cat'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_author_box', 
			'title' => esc_html__('About Author Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_author_box'] 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_more_projects_box', 
			'title' => esc_html__('More Projects Box', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_more_projects_box'], 
			'choices' => array( 
				esc_html__('Show Related Projects', 'flower-shop') . '|related', 
				esc_html__('Show Popular Projects', 'flower-shop') . '|popular', 
				esc_html__('Show Recent Projects', 'flower-shop') . '|recent', 
				esc_html__('Hide More Projects Box', 'flower-shop') . '|hide' 
			) 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_more_projects_count', 
			'title' => esc_html__('More Projects Box Items Number', 'flower-shop'), 
			'desc' => esc_html__('projects', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_more_projects_count'], 
			'min' => '2', 
			'max' => '20' 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_more_projects_pause', 
			'title' => esc_html__('More Projects Slider Pause Time', 'flower-shop'), 
			'desc' => esc_html__("in seconds, if '0' - autoslide disabled", 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_more_projects_pause'], 
			'min' => '0', 
			'max' => '20' 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_project_slug', 
			'title' => esc_html__('Project Slug', 'flower-shop'), 
			'desc' => esc_html__('Enter a page slug that should be used for your projects single item', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_project_slug'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_pj_categs_slug', 
			'title' => esc_html__('Project Categories Slug', 'flower-shop'), 
			'desc' => esc_html__('Enter page slug that should be used on projects categories archive page', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_pj_categs_slug'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'project_section', 
			'id' => 'flower-shop' . '_portfolio_pj_tags_slug', 
			'title' => esc_html__('Project Tags Slug', 'flower-shop'), 
			'desc' => esc_html__('Enter page slug that should be used on projects tags archive page', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_portfolio_pj_tags_slug'], 
			'class' => '' 
		);
		
		
		break;
	case 'profile':
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_title', 
			'title' => esc_html__('Profile Title', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_title'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_details_title', 
			'title' => esc_html__('Profile Details Title', 'flower-shop'), 
			'desc' => esc_html__('Enter a profile details block title', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_details_title'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_cat', 
			'title' => esc_html__('Profile Categories', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_cat'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_comment', 
			'title' => esc_html__('Profile Comments', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_comment'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_like', 
			'title' => esc_html__('Profile Likes', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_like'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_nav_box', 
			'title' => esc_html__('Profiles Navigation Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_nav_box'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_nav_order_cat', 
			'title' => esc_html__('Profiles Navigation Order by Category', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_nav_order_cat'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_share_box', 
			'title' => esc_html__('Sharing Box', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_share_box'] 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_post_slug', 
			'title' => esc_html__('Profile Slug', 'flower-shop'), 
			'desc' => esc_html__('Enter a page slug that should be used for your profiles single item', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_post_slug'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'profile_section', 
			'id' => 'flower-shop' . '_profile_pl_categs_slug', 
			'title' => esc_html__('Profile Categories Slug', 'flower-shop'), 
			'desc' => esc_html__('Enter page slug that should be used on profiles categories archive page', 'flower-shop'), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_profile_pl_categs_slug'], 
			'class' => '' 
		);
		
		
		break;
	}
	
	
	return apply_filters('cmsmasters_options_single_fields_filter', $options, $tab);
}

