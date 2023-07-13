<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://opbhl.com
 * @since             1.0.0
 * @package           Opbhl
 *
 * @wordpress-plugin
 * Plugin Name:       opbhl
 * Plugin URI:        https://opbhl.com
 * Description:       This is a system that enables website administrators to manage game statistics and updates on the backend.
 * Version:           1.0.0
 * Author:            GreenPixel
 * Author URI:        https://opbhl.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       opbhl
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OPBHL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-opbhl-activator.php
 */
function activate_opbhl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-opbhl-activator.php';
	Opbhl_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-opbhl-deactivator.php
 */
function deactivate_opbhl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-opbhl-deactivator.php';
	Opbhl_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_opbhl' );
register_deactivation_hook( __FILE__, 'deactivate_opbhl' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-opbhl.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_opbhl() {

	$plugin = new Opbhl();
	$plugin->run();

}
run_opbhl();
