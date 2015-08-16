<?php
/*
Plugin Name: Awesome Facebook Importer
Plugin URI: http://www.tiagomatos.com
Description: Import your facebook posts
Version: 1.0
Author: Tiago Matos
*/

if (!class_exists('afbi')) {
	class afbi
	{

		public function __construct() {
			define( 'AFBI_PATH', plugin_dir_path( __FILE__));
			add_action('init', array($this, 'init'), 1);
			$this->before_setup_theme();
			add_action('after_setup_theme', array($this, 'after_setup_theme'), 1);
		}

		public function init() {
			if (is_admin()) {
				add_action('admin_menu', array($this, 'admin_menu'), 1);
			}
		}

		public function admin_menu() {
			add_menu_page('Awesome Facebook Importer', 'Facebook Importer', 'manage_options', 'afbi_import', array($this, 'admin_menu_page'), false, '80.035');
		}

		public function admin_menu_page() {
			if (!current_user_can( 'manage_options')) {
				wp_die(__( 'You do not have sufficient permissions to access this page.'));
			}
			include AFBI_PATH . 'src/View/Imports/index.php';
		}

		public function before_setup_theme() {
			include_once(AFBI_PATH . 'src/Controller/ImportsController.php');
		}

		public function after_setup_theme() {
		}
	}
	new afbi();
}