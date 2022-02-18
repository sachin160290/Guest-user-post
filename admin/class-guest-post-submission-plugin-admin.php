<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/admin
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'guest_post_add_plugin_page') );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Submission_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Submission_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-post-submission-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Post_Submission_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Post_Submission_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-post-submission-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
     * Add Guest post information page
	 * 
 	 * @since     1.0.0
     */ 
    public function guest_post_add_plugin_page() {
		add_menu_page('Guest Post Information', 'Guest Post Information', 'manage_options', 'guest-post-information-page', array( $this, 'guest_post_information_admin_callback'), '', 6 );
    }


	/**
     * Guest post information callback function
	 * 
	 * @since     1.0.0
     */
    public function guest_post_information_admin_callback() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient pilchards to access this page.')    );
		}
        include GUEST_POST_PLUGIN_PATH . 'admin/partials/guest-post-submission-plugin-admin-display.php' ;
    }
	  
}
