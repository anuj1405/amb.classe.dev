<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    nc-ajax-cart-for-woocommerce
 * @subpackage nc-ajax-cart-for-woocommerce/admin
 * @author     Nabaraj Chapagain <nabarajc6@gmail.com>
 */
class NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Admin {

	/**
	 * The ID of this plugin.
	 * * @since      1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 * * @since      1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
		/**
	 * The NC Grid List View  settings
	 * * @since      1.0.1
	 * @access   private
	 * @var      string    $version    The current version of the plugin.
	 */	
	
	private $nc_grid_list_view_settings;

	/**
	 * Initialize the class and set its properties.
	 * * @since      1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		 if(get_option( 'nc_grid_list_view_settings'))
	  	{
			$this->nc_grid_list_view_settings=get_option( 'nc_grid_list_view_settings' );
	 	 }
	 	 else
	 	 {
			$this->nc_grid_list_view_default_settings();
	 	 }

	}
	
     /**
	 * Register the stylesheets for the admin area.
	 * * @since      1.0.1
	 */	
	public function nc_grid_list_view_default_settings (){

		$default=array();
		$default['nc_grid_list_view_default_layout']='grid';
		$default['nc_grid_list_view_img_position']='left';
		$default['nc_grid_list_view_img_wrapper_width']='22';
		$default['nc_grid_list_view_content_wrapper_width']='74';
		$default['nc_grid_list_view_enable_in_cat']='1';
		$default['nc_grid_list_view_enable_description']='1';
	    add_option( "nc_grid_list_view_settings", $default );
			
	}

	/**
	 * Register admin menu for the plugin
	 * of the plugin.
	 * * @since      1.0.1
	 * @access   private
	 */	
	
	public function  nc_grid_list_view_menu(){
			$settings=add_submenu_page('woocommerce', 'NC Grid List View Settings', 'NC Grid List View Settings', 'manage_options', 'nc_grid_list_view',
			array($this, 'nc_grid_list_view_settings_form'));
			add_action( "load-{$settings}", array($this,'nc_grid_list_view_settings_page') );
			}
			
	
	/**
	 * NC Grid List View  settings and redirection
	 * of the plugin.
	 * * @since      1.0.1
	 * @access   private
	 */				
			
	public function nc_grid_list_view_settings_page() {
			if ( $_POST["nc_grid_list_view_submit"]) {
			check_admin_referer( "nc_grid_list_view_page" );
			$this->nc_grid_list_view_save_settings();
			$param = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
			wp_redirect(admin_url('admin.php?page=nc_grid_list_view&'.$param));
			exit;
			}
				}		
			
			
	
	/**
	 * NC Grid List View  settings form
	 * of the plugin.
	 * * @since      1.0.1
	 * @access   private
	 */		
		public function nc_grid_list_view_settings_form(){
			
				include_once('includes/nc-ajax-grid-list-view-for-woocommerce-settings-form.php');
		}
	

	/**
	 * NC Grid List View  POST values and update options
	 * of the plugin.
	 * * @since      1.0.1
	 * @access   private
	 */			
			
	public function nc_grid_list_view_save_settings(){
        
	$this->nc_grid_list_view_settings=array();
	if ( isset ( $_GET['page'] )=='nc_grid_list_view') {
	$this->nc_grid_list_view_settings=array();
	$this->nc_grid_list_view_settings['nc_grid_list_view_default_layout']=$_POST['nc_grid_list_view_default_layout'];
	$this->nc_grid_list_view_settings['nc_grid_list_view_img_position']=$_POST['nc_grid_list_view_img_position'];
	$this->nc_grid_list_view_settings['nc_grid_list_view_img_wrapper_width']=str_replace('%','',$_POST['nc_grid_list_view_img_wrapper_width']);
	$this->nc_grid_list_view_settings['nc_grid_list_view_content_wrapper_width']=str_replace('%','',$_POST['nc_grid_list_view_content_wrapper_width']);
	$this->nc_grid_list_view_settings['nc_grid_list_view_enable_in_cat']=$_POST['nc_grid_list_view_enable_in_cat'];
	$this->nc_grid_list_view_settings['nc_grid_list_view_enable_description']=$_POST['nc_grid_list_view_enable_description'];

		 //update option
		 update_option( "nc_grid_list_view_settings", $this->nc_grid_list_view_settings );

	}	
		}

}
