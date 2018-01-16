<?php
/**
 * Plugin Name: WP Show Images In Post
 * Description: Shows all images that are inserted into selected post
 * Author:      Martin Jankov
 * Author URI:  https://mk.linkedin.com/in/martinjankov
 * Version:     1.0.0
 * Text Domain: wsip
 *
 * WP Show Images In Post is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Show Images In Post is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Show Images In Post. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    WPShowImagesInPost
 * @author     Martin Jankov
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, Martin Jankov
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class WPShowImagesInPost {

	private static $_instance;

	private $_version = '1.0.0';

	public $post_images;

	public static function instance() {

		if ( ! isset( self::$_instance ) && ! ( self::$_instance instanceof WPShowImagesInPost ) ) {

			self::$_instance = new WPShowImagesInPost;
			self::$_instance->constants();
			self::$_instance->includes();

			add_action( 'plugins_loaded', array( self::$_instance, 'objects' ), 10 );
			add_action( 'admin_enqueue_scripts', array( self::$_instance, 'load_global_admin_assets' ), 10 );
		}
		return self::$_instance;
	}

	private function includes() {

		// Admin/Dashboard only includes
		if ( is_admin() ) {
			require_once WSIP_PLUGIN_DIR . 'classes/admin/WSIP_Post_Search_Images.php';
		}
	}

	private function constants() {

		// Plugin version
		if ( ! defined( 'WSIP_VERSION' ) ) {
			define( 'WSIP_VERSION', $this->_version );
		}

		// Plugin Folder Path
		if ( ! defined( 'WSIP_PLUGIN_DIR' ) ) {
			define( 'WSIP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'WSIP_PLUGIN_URL' ) ) {
			define( 'WSIP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'WSIP_PLUGIN_FILE' ) ) {
			define( 'WSIP_PLUGIN_FILE', __FILE__ );
		}
	}

	public function objects() {		

		// Init classes if is Admin/Dashboard
		if(is_admin())
		{
			$this->post_images = new WSIP_Post_Search_Images;
		}
	}

	public function load_global_admin_assets($hook){
		/**
		 * Load the assets only on the specified post type. 
		 * This is optional, you can remove this code if you don't need it
		 */
		global $post;
		if(!isset($post)) return;
		
		wp_enqueue_script('wsip-admin-script', WSIP_PLUGIN_URL.'assets/js/admin/script.js', array('jquery'), WSIP_VERSION, true);
		wp_localize_script( 'wsip-admin-script', 'wsip', array(
				'ajax_url'	=> admin_url( 'admin-ajax.php' ),
				'post_content' => $post->post_content
			));
	}
}

/**
 * Use this function as global in all other classes and/or files. 
 * 
 * You can do wsip()->object1->some_function()
 * You can do wsip()->object2->some_function()
 * 
 */
function wsip() {
	return WPShowImagesInPost::instance();
}
wsip();