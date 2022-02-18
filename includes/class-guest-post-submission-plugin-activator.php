<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin_Activator {

	/**
	 * Plugin Actication Function
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/*  Register a new user on Plugin Activation*/
			$username = 'guest_user';
			$email 	  = 'guest_user@example.com';
			if (username_exists($username) == null && email_exists($email) == false):
				wp_insert_user( array(
					'user_login' => $username,
					'user_pass' => 'guest_user@123',
					'user_email' => $email,
					'first_name' => 'Guest',
					'last_name' => 'User',
					'display_name' => 'Guest User',
					'role' => 'author'
				));
			endif;	
	}

}
