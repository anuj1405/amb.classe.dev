<?php

/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.1
 *
 * @package    nc-ajax-cart-for-woocommerce
 * @subpackage nc-ajax-cart-for-woocommerce/admin/includes
 */
 global $pagenow; 
 
 ?>
<div class="wrap ajax_cart">
    <h1><?php _e('NC Grid List View Settings',$this->plugin_name); ?></h1>
    <?php if ( $pagenow == 'admin.php' && $_GET['page'] == 'nc_grid_list_view' ){ ?>
    <form method="post" action="<?php admin_url( 'admin.php?page=nc_grid_list_view' ); ?>" enctype="multipart/form-data">
				<table class="form-table">
				
				<?php wp_nonce_field( "nc_grid_list_view_page" );  ?>       
						         <tr>
						            <th><?php _e('Default layout',$this->plugin_name); ?></th>
						            <td>
						               <select name="nc_grid_list_view_default_layout" >
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_default_layout'])=='list') ? " selected='selected'": "";?>value="list"><?php _e('List',$this->plugin_name); ?></option>           								               		
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_default_layout'])=='grid') ? " selected='selected'": "";?>value="grid"><?php _e('Grid',$this->plugin_name); ?></option>
						               </select>
						            </td>
						         </tr>
                                 
                                 
                                  <tr>
						            <th><?php _e('List View Image Position',$this->plugin_name); ?></th>
						            <td>
						               <select name="nc_grid_list_view_img_position" >
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_img_position'])=='left') ? " selected='selected'": "";?>value="left"><?php _e('Left',$this->plugin_name); ?></option>           								               		
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_img_position'])=='right') ? " selected='selected'": "";?>value="right"><?php _e('Right',$this->plugin_name); ?></option>
						               </select>
						            </td>
						         </tr>
                                 
                                  <tr>
						            <th><?php _e('List View Image Wrapper Width',$this->plugin_name); ?></th>
						            <td>
						               <input type="text" name="nc_grid_list_view_img_wrapper_width" value="<?php echo esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_img_wrapper_width']); ?>"  />%  (<em class="description"><?php _e('Default 22%',$this->plugin_name); ?></em>)
						            </td>
						         </tr>
                            
                                 
                                 <tr>
						            <th><?php _e('List View Content Wrapper Width',$this->plugin_name); ?></th>
						            <td>
						               <input type="text" name="nc_grid_list_view_content_wrapper_width" value="<?php echo esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_content_wrapper_width']); ?>"  />% (<em class="description"><?php _e('Default 74%',$this->plugin_name); ?></em>)
						            </td>
						         </tr>
                                 <tr>
						            <th><?php _e('Enable in Categories',$this->plugin_name); ?></th>
						            <td>
						               <select name="nc_grid_list_view_enable_in_cat" >
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_enable_in_cat'])=='0') ? " selected='selected'": "";?>value="0"><?php _e('No',$this->plugin_name); ?></option>           								               		
						               		<option <?php echo (esc_attr($this->nc_grid_list_view_settings['nc_grid_list_view_enable_in_cat'])=='1') ? " selected='selected'": "";?>value="1"><?php _e('Yes',$this->plugin_name); ?></option>
						               </select>
						            </td>
						         </tr>
                                 
                                 
                                 
                                 <tr>
						            <th><?php _e('Enable Description On List View',$this->plugin_name); ?></th>
						            <td>
						               <input type="checkbox" name="nc_grid_list_view_enable_description" 
				<?php echo $this->nc_grid_list_view_settings['nc_grid_list_view_enable_description']==1 ? 'checked="checked"' : ''; ?> value="1"  />
						               		
						            </td>
						         </tr>
                                 
				</table>
					<p class="submit">
	                    <input type="submit" class="button-primary" name="nc_grid_list_view_submit" value="<?php _e('Save Changes',$this->plugin_name) ?>" />
	               
	                </p>
		
				</form>
	
				 <?php } ?>