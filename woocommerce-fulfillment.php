<?php
/*
Plugin Name: WooCommerce Fulfillment Integration
Plugin URI: http://wordpress.org/extend/plugins/woocommerce-fulfillment-integration/
Description: Allows for advanced integration with eFulfillment Service for automated order placement and fulfillment.
Author: eFulfillment Service, Inc.
Version: 1.5.5
Author URI: http://efulfillmentservice.com
*/   
   
/**
* Guess the wp-content and plugin urls/paths
*/
// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


if (!class_exists('woocommerce_efs')) {
    class woocommerce_efs {
        /**
        * @var string The options string name for this plugin
        */
        var $optionsName = 'woocommerce_efs_options';
        
        /**
        * @var string $localizationDomain Domain used for localization
        */
        var $localizationDomain = "woocommerce_efs";
        
        /**
        * @var string $pluginurl The path to this plugin
        */ 
        var $thispluginurl = '';
        /**
        * @var string $pluginurlpath The path to this plugin
        */
        var $thispluginpath = '';
            
        /**
        * @var array $options Stores the options for this plugin
        */
        var $options = array();
        
        //Class Functions
        /**
        * PHP 4 Compatible Constructor
        */
        function woocommerce_efs(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
            //Language Setup
            $locale = get_locale();
            $mo = dirname(__FILE__) . "/languages/" . $this->localizationDomain . "-".$locale.".mo";
            load_textdomain($this->localizationDomain, $mo);

            //"Constants" setup
            $this->thispluginurl = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->thispluginpath = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)).'/';
            
            //Initialize the options
            //This is REQUIRED to initialize the options when the plugin is loaded!
            $this->getOptions();
            
            //Actions        
            add_action("admin_menu", array(&$this,"admin_menu_link"));
        }        
        
        /**
        * Retrieves the plugin options from the database.
        * @return array
        */
        function getOptions() {
            //Don't forget to set up the default options
            if (!$theOptions = get_option($this->optionsName)) {
                $theOptions = array('default'=>'options');
                update_option($this->optionsName, $theOptions);
            }
            $this->options = $theOptions;
            
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //There is no return here, because you should use the $this->options variable!!!
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }
        /**
        * Saves the admin options to the database.
        */
        function saveAdminOptions(){
            return update_option($this->optionsName, $this->options);
        }
        
        /**
        * @desc Adds the options subpanel
        */
        function admin_menu_link() {
			add_submenu_page('woocommerce', 'Fulfillment Integration', 'Fulfillment Integration','administrator', __FILE__,array(&$this,'admin_options_page'));
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
        }
        
        /**
        * @desc Adds the Settings link to the plugin activate/deactivate page
        */
        function filter_plugin_actions($links, $file) {
           $settings_link = '<a href="admin.php?page=woocommerce-fulfillment-integration/' . basename(__FILE__) . '">' . __('Settings') . '</a>';
           array_unshift( $links, $settings_link ); // before other links
           return $links;
        }

        /**
        * Adds settings/options page
        */
        function admin_options_page() { 
            if($_POST['woocommerce_efs_save']){
                if (! wp_verify_nonce($_POST['_wpnonce'], 'woocommerce_efs-update-options') ) die('<div class="error"><p>Whoops! There was a problem with the data you posted. Please go back and try again.</p></div>'); 
                if ($_POST['efs_order_status'] == $_POST['efs_shipped_status']) die('<div class="error"><p>Whoops! <strong>Order Status</strong> and <strong>Shipped Status</strong> cannot be the same.</p></div>');
                $this->options['efs_merchant_id'] = $_POST['efs_merchant_id'];                   
                $this->options['efs_merchant_token'] = $_POST['efs_merchant_token'];
                $this->options['efs_merchant_transaction_type'] = $_POST['efs_merchant_transaction_type'];
                $this->options['efs_merchant_warehouse'] = $_POST['efs_merchant_warehouse'];
                $this->options['efs_order_status'] = $_POST['efs_order_status'];
                $this->options['efs_shipped_status'] = $_POST['efs_shipped_status'];
                $this->options['efs_received_status'] = $_POST['efs_received_status'];
                $this->options['efs_tracking_note'] = $_POST['efs_tracking_note'];
                                        
                $this->saveAdminOptions();
                
                echo '<div class="updated"><p>Success! Your changes were sucessfully saved!</p></div>';
            }
            
            $statuses = (array) get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
            $yesnos = array('NO', 'YES');
?>                                   
                <div class="wrap">
                <h2>Fulfillment Integration Settings</h2>
                <form method="post" id="woocommerce_efs_options">
                <?php wp_nonce_field('woocommerce_efs-update-options'); ?>
                    <table width="100%" cellspacing="2" cellpadding="5" class="form-table"> 
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Client ID:', $this->localizationDomain); ?></th> 
                            <td><input name="efs_merchant_id" type="text" id="efs_merchant_id" value="<?php echo $this->options['efs_merchant_id'] ;?>" size="45"/>
                            <span style="color: silver; font-style: italic;">From "My Settings" in your Fulfillment Control Panel account.</span>
                        </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Project:', $this->localizationDomain); ?></th> 
                            <td><input name="efs_merchant_token" type="text" id="efs_merchant_token" size="35" value="<?php echo $this->options['efs_merchant_token'] ;?>"/>
                            <span style="color: silver; font-style: italic;">From "My Settings" in you Fulfillment Control Panel account.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Transaction Type:', $this->localizationDomain); ?></th> 
                            <td><input name="efs_merchant_transaction_type" type="text" id="efs_merchant_transaction_type" size="5" value="<?php echo $this->options['efs_merchant_transaction_type'] ;?>"/>
                            <span style="color: silver; font-style: italic;">From "My Settings" in you Fulfillment Control Panel account.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Warehouse:', $this->localizationDomain); ?></th> 
                            <td><input name="efs_merchant_warehouse" type="text" id="efs_merchant_warehouse" size="5" value="<?php echo $this->options['efs_merchant_warehouse'] ;?>"/>
                            <span style="color: silver; font-style: italic;">From "My Settings" in you Fulfillment Control Panel account.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Order Status:', $this->localizationDomain); ?></th> 
                            <td>
                            	<select name="efs_order_status" id="efs_order_status">
                            		<option value="0">Choose Status</option>
                            		<option value="0">-------------</option>
                            		<?php
										foreach($statuses as $status) {
											if($status->name != "completed")
											{
												$style = ($status->name == $this->options['efs_order_status']) ? ' selected' : '';
												echo '<option value="' . $status->name . '"' . $style . '>' . $status->name .'</option>';
											}
										}
									?>
                            	</select>
                            	<span style="color: silver; font-style: italic;">This is the status of orders you would like us to retrieve from WooCommerce for processing in the warehouse. The integration service will <strong>ONLY</strong> attempt to retrieve orders matching this status.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Received Status:', $this->localizationDomain); ?></th> 
                            <td>
                            	<select name="efs_received_status" id="efs_received_status">
                            		<option value="0">Choose Status</option>
                            		<option value="0">-------------</option>
                            		<?php
                            			$receivedDisabledStyle = ($this->options['efs_received_status'] == 'DISABLED') ? ' selected' : '';
                            		?>
                            		<option value="DISABLED"<?= $receivedDisabledStyle ?>>DISABLED</option>
                            		<option value="0">-------------</option>
                            		<?php
										foreach($statuses as $status) {
											$style = ($status->name == $this->options['efs_received_status']) ? ' selected' : '';
											echo '<option value="' . $status->name . '"' . $style . '>' . $status->name .'</option>';
										}
									?>
                            	</select>
                            	<span style="color: silver; font-style: italic;">Optional. We can update an order's status in WooCommerce when we successfully receive an order for processing.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Shipped Status:', $this->localizationDomain); ?></th> 
                            <td>
                            	<select name="efs_shipped_status" id="efs_shipped_status">
                            		<option value="0">Choose Status</option>
                            		<option value="0">-------------</option>
                            		<?php
                            			$shippedDisabledStyle = ($this->options['efs_shipped_status'] == 'DISABLED') ? ' selected' : '';
                            		?>
                            		<option value="DISABLED"<?= $shippedDisabledStyle ?>>DISABLED</option>
                            		<option value="0">-------------</option>
                            		<?php
										foreach($statuses as $status) {
											$style = ($status->name == $this->options['efs_shipped_status']) ? ' selected' : '';
											echo '<option value="' . $status->name . '"' . $style . '>' . $status->name .'</option>';
										}
									?>
                            	</select>
                            	<span style="color: silver; font-style: italic;">Optional. We can update an order's status in WooCommerce.</span>
                            </td> 
                        </tr>
                        <tr valign="top"> 
                            <th width="33%" scope="row"><?php _e('Tracking Note:', $this->localizationDomain); ?></th> 
                            <td>
                            	<select name="efs_tracking_note" id="efs_tracking_note">
                            		<?php
										foreach($yesnos as $yesno) {
											$style = ($yesno == $this->options['efs_tracking_note']) ? ' selected' : '';
											echo '<option value="' . $yesno . '"' . $style . '>' . $yesno .'</option>';
										}
									?>
                            	</select>
                            	<span style="color: silver; font-style: italic;">Optional. Since WooCommerce doesn't natively support tracking numbers we can add a tracking number as a note (this will only function if "shipped status" is enabled). Please note: we will <strong>ALWAYS</strong> add a note when updating an order with more than one tracking number.</span>
                            </td> 
                        </tr>
                        <tr>
                            <th colspan=2><input type="submit" name="woocommerce_efs_save" value="Save" /></th>
                        </tr>
                    </table>
                </form>
                
                </div>
                <?php
                $pluginUrl = $this->thispluginurl;
                ?>
                <div class="wrap">
                	<h2>Plugin URL</h2>
                    <p><strong>PLEASE NOTE:</strong> To complete the integration set up process and connect to your Fulfillment Control Panel account you must contact your Client Services Representative or visit <a href="https://support.efulfillmentservice.com/">support.efulfillmentservice.com</a> to obtain instructions.</p>
                    <p>Once you have obtained the necessary instructions you will need the information below.</p>
                	<em>You must copy and paste this exactly into your eFulfillment integration settings</em><br />
                	<form>
                		<input type="text" name="pluginUrl" value="<?= $pluginUrl ?>" onclick="this.select()" size="80" />
                	</form>
                </div>
                <?php
        }
        
    
        
  } //End Class
} //End if class exists statement

//instantiate the class
if (class_exists('woocommerce_efs')) {
    $woocommerce_efs_var = new woocommerce_efs();
}
?>