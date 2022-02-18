<?php
/**
 * 
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since             1.0.0
 * @package           Guest_Post_Submission_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Guest Post Submission Plugin
 * Plugin URI:        https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * Description:       This plugin is used to display the Guest post on frontend and take entry of post from Guest user.
 * Version:           1.0.0
 * Author:            Sachin Yadav
 * Author URI:        https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       guest-post-submission-plugin
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
define( 'GUEST_POST_SUBMISSION_PLUGIN_VERSION', '1.0.0' );

define( 'GUEST_POST_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-guest-post-submission-plugin-activator.php
 */
function activate_guest_post_submission_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guest-post-submission-plugin-activator.php';
	Guest_Post_Submission_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-guest-post-submission-plugin-deactivator.php
 */
function deactivate_guest_post_submission_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guest-post-submission-plugin-deactivator.php';
	Guest_Post_Submission_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_guest_post_submission_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_guest_post_submission_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-guest-post-submission-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_guest_post_submission_plugin() {

	$plugin = new Guest_Post_Submission_Plugin();
	$plugin->run();

}
run_guest_post_submission_plugin();


/**
 * Plugin Menu add for Guest Post Information
 *
 * @since    1.0.0
 */
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'my_plugin_settings_link' );
function my_plugin_settings_link($links) { 
	$url = esc_url( add_query_arg(
		'page',
		'guest-post-information-page',
		get_admin_url() . 'admin.php'
	) );
	$settings_link = '<a href="'.$url.'">Guest Post Information</a>'; 
	array_push($links, $settings_link); 
  	return $links; 
}
