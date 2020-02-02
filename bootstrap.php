<?php
/**
 * Plugin Name: ROB (rat out blocker)
 * Description: Block external requests with regex or url part
 * Plugin URI:  https://web-marshal.ru/rob-rat-out-blocker/
 * Author URI:  https://www.linkedin.com/in/stasionok/
 * Author:      Stanislav Kuznetsov
 * Version:     1.0
 * License: GPLv2 or later
 * Text Domain: rob-rat-out-blocker
 * Domain Path: /languages
 *
 * Network: false
 */

defined( 'ABSPATH' ) || exit;

define( 'ROB_REQUIRED_PHP_VERSION', '7.0' );
define( 'ROB_REQUIRED_WP_VERSION', '5.0' );

/**
 * Checks if the system requirements are met
 *
 * @return bool|array Array of errors or false if all is ok
 */
function rob_requirements_met() {
	global $wp_version;

	$errors = false;

	if ( version_compare( PHP_VERSION, ROB_REQUIRED_PHP_VERSION, '<' ) ) {
		$errors[] = __( "Your server is running PHP version " . PHP_VERSION . " but this plugin requires at least PHP " . ROB_REQUIRED_PHP_VERSION . ". Please run an upgrade.", ROB_Common::PLUGIN_SYSTEM_NAME );
	}

	if ( version_compare( $wp_version, ROB_REQUIRED_WP_VERSION, '<' ) ) {
		$errors[] = __( "Your Wordpress running version is " . esc_html( $wp_version ) . " but this plugin requires at least version " . ROB_REQUIRED_WP_VERSION . ". Please run an upgrade.", ROB_Common::PLUGIN_SYSTEM_NAME );
	}

	return $errors;
}

/**
 * Begins execution of the plugin.
 *
 * Plugin run entry point
 */
function rob_run() {
	$plugin = new ROB_Common();
	$plugin->run();
}


/**
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
 * Otherwise older PHP installations could crash when trying to parse it.
 */
require_once( __DIR__ . '/controller/class-rob-common.php' );

$errors = rob_requirements_met();
if ( ! $errors ) {
	if ( method_exists( ROB_Common::class, 'activate' ) ) {
		register_activation_hook( __FILE__, array( ROB_Common::class, 'activate' ) );
	}

	rob_run();
} else {
	add_action( 'admin_notices', function () use ( $errors ) {
		require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
	} );
}

if ( method_exists( ROB_Common::class, 'deactivate' ) ) {
	register_deactivation_hook( __FILE__, array( ROB_Common::class, 'deactivate' ) );
}

if ( method_exists( ROB_Common::class, 'uninstall' ) ) {
	register_uninstall_hook( __FILE__, array( ROB_Common::class, 'uninstall' ) );
}

