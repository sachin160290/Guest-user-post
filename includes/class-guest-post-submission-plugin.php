<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/sachin160290/Guest-Post-Submission-Plugin
 * @since      1.0.0
 *
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Guest_Post_Submission_Plugin
 * @subpackage Guest_Post_Submission_Plugin/includes
 * @author     Sachin Yadav <sachin.singh.yadav2011@gmail.com>
 */
class Guest_Post_Submission_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Guest_Post_Submission_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GUEST_POST_SUBMISSION_PLUGIN_VERSION' ) ) {
			$this->version = GUEST_POST_SUBMISSION_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'guest-post-submission-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		/* Create Post Type on Plugin Activation */
		add_action( 'init', array( $this, 'guest_post_custom_post_type') );

		/* Flush the re-write rules after registring CPT */
		add_action( 'init', array( $this, 'flush_rewrite_rules') );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Guest_Post_Submission_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Guest_Post_Submission_Plugin_i18n. Defines internationalization functionality.
	 * - Guest_Post_Submission_Plugin_Admin. Defines all hooks for the admin area.
	 * - Guest_Post_Submission_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-guest-post-submission-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-guest-post-submission-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-guest-post-submission-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-guest-post-submission-plugin-public.php';

		$this->loader = new Guest_Post_Submission_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Guest_Post_Submission_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Guest_Post_Submission_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Guest_Post_Submission_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Guest_Post_Submission_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Guest_Post_Submission_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Create Guest Post Custom Post Type
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function guest_post_custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Guest Post', 'Guest Post', 'guest-post-submission-plugin' ),
			'singular_name'         => _x( 'Guest Post', 'Guest Post', 'guest-post-submission-plugin' ),
			'menu_name'             => __( 'Guest Post', 'guest-post-submission-plugin' ),
			'name_admin_bar'        => __( 'Guest Post', 'guest-post-submission-plugin' ),
			'archives'              => __( 'Guest Post Archives', 'guest-post-submission-plugin' ),
			'attributes'            => __( 'Guest Post Attributes', 'guest-post-submission-plugin' ),
			'parent_item_colon'     => __( 'Parent Guest Post:', 'guest-post-submission-plugin' ),
			'all_items'             => __( 'All Guest Post', 'guest-post-submission-plugin' ),
			'add_new_item'          => __( 'Add New Guest Post', 'guest-post-submission-plugin' ),
			'add_new'               => __( 'Add New', 'guest-post-submission-plugin' ),
			'new_item'              => __( 'New Guest Post', 'guest-post-submission-plugin' ),
			'edit_item'             => __( 'Edit Guest Post', 'guest-post-submission-plugin' ),
			'update_item'           => __( 'Update Guest Post', 'guest-post-submission-plugin' ),
			'view_item'             => __( 'View Guest Post', 'guest-post-submission-plugin' ),
			'view_items'            => __( 'View Guest Post', 'guest-post-submission-plugin' ),
			'search_items'          => __( 'Search Guest Post', 'guest-post-submission-plugin' ),
			'not_found'             => __( 'No post found', 'guest-post-submission-plugin' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'guest-post-submission-plugin' ),
			'featured_image'        => __( 'Featured Image', 'guest-post-submission-plugin' ),
			'set_featured_image'    => __( 'Set featured image', 'guest-post-submission-plugin' ),
			'remove_featured_image' => __( 'Remove featured image', 'guest-post-submission-plugin' ),
			'use_featured_image'    => __( 'Use as featured image', 'guest-post-submission-plugin' ),
			'insert_into_item'      => __( 'Insert into Guest Post', 'guest-post-submission-plugin' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Guest Post', 'guest-post-submission-plugin' ),
			'items_list'            => __( 'Guest Post list', 'guest-post-submission-plugin' ),
			'items_list_navigation' => __( 'Guest Post list navigation', 'guest-post-submission-plugin' ),
			'filter_items_list'     => __( 'Filter Guest Post list', 'guest-post-submission-plugin' ),
		);
		$args = array(
			'label'                 => __( 'Guest Post', 'guest-post-submission-plugin' ),
			'description'           => __( 'Guest Post Description', 'guest-post-submission-plugin' ),
			'labels'                => $labels,
			'supports'              => array('title', 'editor', 'excerpt', 'author', 'thumbnail'),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'rewrite' 				=> array('slug' => 'guest-post'),
		);
		register_post_type( 'guest_post_cpt', $args );
	}

	/**
	 * Flush rewrite rules after define the post type
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function flush_rewrite_rules(){
		$get_flush_attr = get_option( 'guest_post_flush_val' );
		if( $get_flush_attr != 1):
			flush_rewrite_rules();
			update_option( 'guest_post_flush_val', 1 );
		endif;	

	}

}
