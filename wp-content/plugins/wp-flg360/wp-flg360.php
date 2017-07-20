<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://costinbotez.co.uk
 * @since             1.0.0
 * @package           Wp_Flg360
 *
 * @wordpress-plugin
 * Plugin Name:       WP FLG360 Integration
 * Plugin URI:        https://costinbotez.co.uk
 * Description:       Integrates WordPress with FLG360 API to capture leads from Contact Form 7
 * Version:           1.0.0
 * Author:            Costin Botez
 * Author URI:        https://costinbotez.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-flg360
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-flg360-activator.php
 */
function activate_wp_flg360() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-flg360-activator.php';
	Wp_Flg360_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-flg360-deactivator.php
 */
function deactivate_wp_flg360() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-flg360-deactivator.php';
	Wp_Flg360_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_flg360' );
register_deactivation_hook( __FILE__, 'deactivate_wp_flg360' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-flg360.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_flg360() {

	$plugin = new Wp_Flg360();
	$plugin->run();

}
run_wp_flg360();
