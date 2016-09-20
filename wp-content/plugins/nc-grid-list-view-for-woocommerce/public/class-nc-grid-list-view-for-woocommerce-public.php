<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    nc-grid-list-view-for-woocommerce
 * @subpackage nc-grid-list-view-for-woocommerce/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    nc-grid-list-view-for-woocommerce
 * @subpackage nc-grid-list-view-for-woocommerce/includes
 * @author     Nabaraj Chapagain <nabarajc6@gmail.com>
 */
class NC_GRID_LIST_VIEW_FOR_WOOCOMMERCE_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function nc_grid_list_view_icons() {
	$settings=get_option('nc_grid_list_view_settings');	
	if(is_shop()):
	   if($settings['nc_grid_list_view_enable_in_cat']==0):
		return;
		endif;
	endif;
	?>
     <div id="nc-grid-list-view-assets" data-default-layout="<?php echo $settings['nc_grid_list_view_default_layout']; ?>" data-img-position="<?php echo $settings['nc_grid_list_view_img_position']; ?>"></div>
    <div id="nc-list-grid-icons" style="display:block; clear:both">
    <a href="javascript:void(0)" data-id="grid" id="grid-icon" ><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/grid.png"  alt="grid-view"/></a>
    <a href="javascript:void(0)" data-id="list" id="list-icon" ><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/list.png"  alt="list-view"/></a>
    </div>
    <?php
	
	}
	

	/**
	 * excerpt for list view
	 *
	 * @since    1.0.0
	 */
	 
     public function nc_grid_list_view_woocommerce_product_excerpt()  
     { 
     echo '<div class="nc-list-grid-view-excerpt">'; 
     the_excerpt(); 
     echo '</div>'; 
     } 


	

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name."-list-grid-view-style", plugin_dir_url( __FILE__ ) . 'css/nc-list-grid-view-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name."-list-grid-view-script", plugin_dir_url( __FILE__ ) . 'js/nc-list-grid-view-public.js', array( 'jquery' ), $this->version, false );

	}


/**
	 * Image opening wrapper for list view
	 *
	 * @since    1.0.0
	 */
	public function nc_grid_list_view_img_opening_wrapper() {
    
    echo '<div class="nc-list-content-image ">';

	}
	
	/**
	 * Opening wrapper for list view product description, add to cart, meta etc.
	 *
	 * @since    1.0.0
	 */
	public function nc_grid_list_view_content_opening_wrapper() {
    
    echo '<div class="nc-list-content-product ">';

	}
	
	
	/**
	 * closing div for img and content wrapper
	 *
	 * @since    1.0.0
	 */
	public function nc_grid_list_view_closing_wrapper() {
    
    echo '</div>';

	}
	
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function nc_grid_list_view_custom_style() {
    
	$settings=get_option('nc_grid_list_view_settings');
	if($settings['nc_grid_list_view_default_layout']=='list')
	$img_width=$settings['nc_grid_list_view_img_wrapper_width'];
	$content_width=$settings['nc_grid_list_view_content_wrapper_width'];
	$img_position=$settings['nc_grid_list_view_img_position'];
	?>
   
    <style type="text/css"> 
 		 #nc-list-grid-icons{margin:10px 0; clear:both; text-align:left;}
		 #nc-list-grid-icons a{padding-right:10px; display:inline-block; border:0;}
 		#nc-list-grid-icons a img{border:0;}
 		.woocommerce ul.list li{text-align:left !important;}
 		 .woocommerce ul.list li::after{clear:both;}
 		.woocommerce ul.list li.product, .woocommerce ul.list li.product{width:100% !important;}
 		.woocommerce ul.list li.product .product-image-box, .woocommerce ul.list li.product .product-image-box{width:30% !important; height:auto  		!important; float:left;} 
 		.woocommerce ul.products.list li.product .nc-list-content-image {
  		 width: <?php echo !empty($img_width) ? $img_width."%" : '22%'; ?>;
  		 float: <?php echo !empty($img_position) ? $img_position : 'left';?>;
 		  clear: none;
 		text-align:left;
 		}
 		.woocommerce ul.products.list li.product .nc-list-content-product{
  		 width: <?php echo !empty($content_width) ? $content_width."%" : '74%'; ?>;
  		 float: <?php echo ($img_position=='left') ? 'right' : 'left';?>;;
  		 clear: none;
  		text-align:left;
 		}
 		.woocommerce ul.products div.nc-list-grid-view-excerpt {
 			display:none;
 		}
 		.woocommerce ul.products.list div.nc-list-grid-view-excerpt {
 		display:block;
 		text-align:left;
 		}
	</style>    
    <?php
	}
}