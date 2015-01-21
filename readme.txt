=== WooCommerce Fulfillment Integration ===
Contributors: eFulfillment Service
Tags: eFulfillment, eFulfillment Service, order fulfillment, woocommerce, woocommerce fulfillment, fulfillment, shipping, warehouse, warehousing, integration
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 1.5.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce store owners can integrate seamlessly with eFulfillment Service (EFS) to allow for automated eCommerce order placement and fulfillment.

== Description ==

This plugin lets WooCommerce store owners integrate seamlessly with eFulfillment Service (EFS) to allow for automated eCommerce order placement and fulfillment.

**Features**

**Automatic Order Placement:** Orders placed on your WooCommerce store are automatically received by eFulfillment Service, allowing for fast and easy order processing and fulfillment.

**Custom Status Updates:** Orders received by our system for processing can, optionally, have a custom order status set in WooCommerce.

**Inventory Level Updates:** eFulfillment Service sends available inventory counts back to your WooCommerce store each day, keeping you and your customers up to date on product availability. 

**Order Tracking Information:** Once orders have shipped, order confirmation and tracking information is sent back to your WooCommerce store automatically, marking orders complete. 

**PLEASE NOTE:** To complete the integration set up process and connect to your Fulfillment Control Panel account you must contact your Client Services Representative or visit [support.efulfillmentservice.com](https://support.efulfillmentservice.com/ "eFulfillment Support") to obtain instructions.

== Installation ==

It is recommended that you install the WooCommerce Fulfillment Integration plugin automatically, through the WordPress.org plugin directory:

1. Log into your WordPress admin area and click the 'Plugins' menu item in WordPress.
1. Click 'Add New' at the top of the page.
1. Enter "woocommerce fulfillment" in the search box and click the 'Search Plugins' button.
1. Look for "WooCommerce Fulfillment Integration" by eFulfillment Service, Inc. and click the "Install Now" button.
1. Once installed, activate the plugin and then follow our installation instructions to complete the setup: [support.efulfillmentservice.com](https://support.efulfillmentservice.com/ "eFulfillment Support").

If you are unable to use the plugin directory, follow these steps:

1. Download the latest version of the plugin from [here](http://wordpress.org/plugins/woocommerce-fulfillment-integration/ "WooCommerce Fulfillment Integration"), but leave it as a zip file.
1. Log into your WordPress admin area and click the 'Plugins' menu item in WordPress.
1. Click 'Add New' at the top of the page.
1. Click the 'Upload' link at the top of the page.
1. Click the 'Choose File' button and select the plugin zip file you saved in step 1.
1. Click the 'Install Now' button and wait for WordPress to finish the process.
1. Click the 'Activate Plugin' link after WordPress displays 'Plugin installed successfully'.
1. Follow our installation instructions to complete the setup: [support.efulfillmentservice.com](https://support.efulfillmentservice.com/ "eFulfillment Support").

== Frequently Asked Questions ==

= How long will integration take? =

Integration setup and installation is completely handled by you. If you are following our instructions and have the plugin installed it should only take a few minutes.

= Will we need to do any custom development work? =

No development is necessary to use this plugin. Simply configure your plugin settings in WordPress, then configure your integration settings in your Fulfillment Control Panel account.

= Is there a fee associated with the integration? =

No, we don't charge a fee for this service.

== Screenshots ==

1. This is the plugin settings page.

== Changelog ==

= 1.5.5 - 06/11/2014 =
* Support for custom order numbers with the PRO version of plugin [WooCommerce Sequential Order Numbers](http://wordpress.org/plugins/woocommerce-sequential-order-numbers/ "WooCommerce Sequential Order Numbers"). Example order number with prefix: #AJ-9041.

= 1.5.4 - 04/02/2013 =
* Added additional logic to prevent duplicate tracking number notes from being added to orders in cases where order status fails to update.

= 1.5.3 - 03/07/2014 =
* Fixed status updates for those using plugin [WooCommerce Sequential Order Numbers](http://wordpress.org/plugins/woocommerce-sequential-order-numbers/ "WooCommerce Sequential Order Numbers").

= 1.5.2 - 02/27/2014 =
* Added support for sequential order numbers via third-party plugin: [WooCommerce Sequential Order Numbers](http://wordpress.org/plugins/woocommerce-sequential-order-numbers/ "WooCommerce Sequential Order Numbers").

= 1.5.1 - 02/20/2014 =
* Fixed blank shipping method for orders in WooCommerce 2.1+.

= 1.5 - 12/02/2013 =
* Support for tracking URL and provider in order complete email via third-party plugin: [WooCommerce Shipment Tracking](http://www.woothemes.com/products/shipment-tracking/ "WooCommerce Shipment Tracking").
* Minor bug fixes.
* Settings page enhancements.
* Better support for orders with multiple tracking numbers.

= 1.4 - 10/07/2013 =
* If no _shipping_method_title is set, _shipping_method is used. This is common with manually entered orders, i.e. "free_shipping" vs. "Free Shipping".
* Minor bug fixes and support information updates.

== Upgrade Notice ==

= 1.5.1 =
This version fixes a compatibility problem with WooCommerce 2.1+ related to shipping methods.