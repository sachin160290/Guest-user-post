<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		unregister_post_type( 'guest_post_cpt' );
	}

}
