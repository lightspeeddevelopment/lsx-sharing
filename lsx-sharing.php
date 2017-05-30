<?php
/*
 * Plugin Name: LSX Sharing
 * Plugin URI:  https://www.lsdev.biz/product/lsx-sharing/
 * Description: Sharing plugin for LSX Theme.
 * Version:     1.0.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-sharing
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_SHARING_CORE', __FILE__ );
define( 'LSX_SHARING_URL',  plugin_dir_url( __FILE__ ) );
define( 'LSX_SHARING_VER',  '1.0.0' );

/* ======================= The API Classes ========================= */

if ( ! class_exists( 'LSX_API_Manager' ) ) {
	require_once( 'classes/class-lsx-api-manager.php' );
}

/**
 * Run when the plugin is active, and generate a unique password for the site instance.
 */
function lsx_sharing_activate_plugin() {
	$lsx_to_password = get_option( 'lsx_api_instance', false );

	if ( false === $lsx_to_password ) {
		update_option( 'lsx_api_instance', LSX_API_Manager::generatePassword() );
	}
}
register_activation_hook( __FILE__, 'lsx_sharing_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Currency Settings.
 */
function lsx_sharing_options_pages_filter( $pages ) {
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter( 'lsx_api_manager_options_pages', 'lsx_sharing_options_pages_filter', 10, 1 );

function lsx_sharing_api_admin_init() {
	global $lsx_sharing_api_manager;

	if ( class_exists( 'Tour_Operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	$data = array(
		'api_key' => '',
		'email'   => '',
	);

	if ( false !== $options && isset( $options['api'] ) ) {
		if ( isset( $options['api']['lsx-sharing_api_key'] ) && '' !== $options['api']['lsx-sharing_api_key'] ) {
			$data['api_key'] = $options['api']['lsx-sharing_api_key'];
		}

		if ( isset( $options['api']['lsx-sharing_email'] ) && '' !== $options['api']['lsx-sharing_email'] ) {
			$data['email'] = $options['api']['lsx-sharing_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );

	if ( false === $instance ) {
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id' => 'LSX Sharing',
		'version'    => '1.0.0',
		'instance'   => $instance,
		'email'      => $data['email'],
		'api_key'    => $data['api_key'],
		'file'       => 'lsx-sharing.php',
	);

	$lsx_sharing_api_manager = new LSX_API_Manager( $api_array );
}
add_action( 'admin_init', 'lsx_sharing_api_admin_init' );

/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-admin.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-button.php' );

global $lsx_sharing_admin;
$lsx_sharing_admin = new LSX_Sharing_Admin();

global $lsx_sharing;
$lsx_sharing = new LSX_Sharing();
