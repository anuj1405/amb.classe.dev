<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *
 * @package    nc-grid-list-view-for-woocommerce
 * @subpackage nc-grid-list-view-for-woocommerce/includes
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
 * @package    nc-grid-list-view-for-woocommerce
 * @subpackage nc-grid-list-view-for-woocommerce/includes
 * @author     Nabaraj Chapagain <nabarajc6@gmail.com>
 */
class NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'nc-grid-list-view-for-woocommerce';
		$this->version = '1.0.0';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nc-grid-list-view-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nc-grid-list-view-for-woocommerce-i18n.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nc-grid-list-view-for-woocommerce-admin.php';
		 

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nc-grid-list-view-for-woocommerce-public.php';

		$this->loader = new NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}



	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 * * @since      1.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu',$plugin_admin, 'nc_grid_list_view_menu');
		$this->loader->add_action( 'init',$plugin_admin,'nc_grid_list_view_default_settings');
		

	}
	
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
         
		$assets=get_option('nc_grid_list_view_settings'); 
		$plugin_public = new NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$assets=get_option('nc_grid_list_view_settings'); 
		$this->loader->add_action( 'woocommerce_before_shop_loop', $plugin_public, 'nc_grid_list_view_icons',40 );
		if($assets['nc_grid_list_view_enable_description']==1):
		$this->loader->add_action( 'woocommerce_after_shop_loop_item_title', $plugin_public, 'nc_grid_list_view_woocommerce_product_excerpt', 35, 2 );
		endif;
		$this->loader->add_action( 'woocommerce_before_shop_loop_item_title', $plugin_public, 'nc_grid_list_view_img_opening_wrapper',9);
		$this->loader->add_action( 'woocommerce_shop_loop_item_title', $plugin_public, 'nc_grid_list_view_closing_wrapper',9);
		$this->loader->add_action( 'woocommerce_shop_loop_item_title', $plugin_public, 'nc_grid_list_view_content_opening_wrapper',9);
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'nc_grid_list_view_closing_wrapper',11);
		if($assets['nc_grid_list_view_enable_in_cat']==1):
		$this->loader->add_action( 'woocommerce_before_subcategory_title', $plugin_public, 'nc_grid_list_view_img_opening_wrapper',9);
		$this->loader->add_action( 'woocommerce_before_subcategory_title', $plugin_public, 'nc_grid_list_view_closing_wrapper',11);
		$this->loader->add_action( 'woocommerce_before_subcategory_title', $plugin_public, 'nc_grid_list_view_content_opening_wrapper',11);
		$this->loader->add_action( 'woocommerce_after_subcategory_title', $plugin_public, 'nc_grid_list_view_content_opening_wrapper',11);
		endif;
		$this->loader->add_action( 'wp_head', $plugin_public, 'nc_grid_list_view_custom_style');
		

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
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
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

}
