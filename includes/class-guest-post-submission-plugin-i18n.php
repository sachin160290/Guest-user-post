<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'guest-post-submission-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
