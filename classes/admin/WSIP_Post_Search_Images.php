<?php

class WSIP_Post_Search_Images
{
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'wsip_call_meta_box'), 10, 2);
		add_action('wp_ajax_wsip_search_images_by_url', array($this, 'wsip_search_images_by_url' ));
	}

	public function wsip_call_meta_box($post_type, $post)
	{
		add_meta_box(
			'wsip_search_images_post_meta_box',
			__('WP Show Images In Post', 'wsip_plugin'),
			array($this,'wsip_search_images_post_meta_box'),
			'post',
			'normal',
			'high',
			$args
		);
	}

	public function wsip_search_images_post_meta_box($post, $args)
	{
		wp_nonce_field('wsip_plugin_name', 'wsip_plugin_nonce');

		@include (WSIP_PLUGIN_DIR. "views/admin/template-search-images-post.php");
	}

	
	public function wsip_search_images_by_url()
	{
		$img_sources = $_POST['images_source'];
		$img_sources = implode("','", $img_sources);
		
		global $wpdb;
	
		$images= $wpdb->get_results("SELECT guid, post_title FROM $wpdb->posts WHERE guid IN ('".$img_sources."')");
		echo json_encode($images);
        die;
	}	
}