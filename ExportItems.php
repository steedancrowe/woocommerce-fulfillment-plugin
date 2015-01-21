<?php
//Set Output Header
header('Content-Type: application/json; charset=UTF-8');

//Prevent unwanted echoes
ob_start();

//
// Requirements to interact with WordPress
//
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');

//Get plugin options
$options = $wpdb->get_var(
	"
	SELECT option_value
	FROM $wpdb->options
	WHERE option_name = 'woocommerce_efs_options'
	"
);

$optionsArray = unserialize($options);
$efs_merchant_id = $optionsArray['efs_merchant_id'];
$efs_merchant_token = $optionsArray['efs_merchant_token'];
$efs_order_status = $optionsArray['efs_order_status'];

//
// Check for incoming efs_merchant_token & efs_merchant_id to match options
//
if($efs_merchant_id == $_GET['efs_merchant_id'] && $efs_merchant_token == $_GET['efs_merchant_token'])
{
	//
	// Get Items
	//
	$statusFilter = $efs_order_status;
	$products = $wpdb->get_results( 
		"
		SELECT
			`ID`,
			`post_title`,
			`post_name`,
			`post_parent`	
		FROM $wpdb->posts
		WHERE `post_type` IN('product','product_variation')
		AND `post_status` = 'publish'
		"
	);
	
	//
	// Continue if products are available
	//
	$productsCount = count($products);
	if($productsCount > 0)
	{
		//
		// Get Product Details
		//
		$parsedProducts = array();
		foreach($products as $product)
		{
		
			$parsedProduct = array();
			$parsedProduct['name'] = $product->post_title;
				
			$productMeta = $wpdb->get_results(
				"
				SELECT *
				FROM $wpdb->postmeta
				WHERE post_id = {$product->ID}
				"
			);
			
			foreach($productMeta as $meta)
			{
				$parsedProduct[$meta->meta_key] = $meta->meta_value;
			}
			$parsedProducts[$product->ID] = $parsedProduct;
		}
		
		$itemsArray = array();
		foreach($parsedProducts as $itemId => $item)
		{
			$itemsArray[] = array(
				"id" => (string)$itemId,
				"ItemSKU" => $item['_sku'],
				"name" => $item['name'],
				"stock" => $item['_stock']
			);
		}
		
		$json = array(
			"status" => "success",
			"items" => $itemsArray
		);
	
	} else {
		$json = array(
			"status" => "success",
			"items" => "[]"
		);
	}
	status_header( 200 );
} else {
	status_header( 400 );
	$json = array(
		"status" => "failure",
		"error" => "invalid efs_merchant_id or efs_merchant_token"
	);
}
ob_clean();
echo json_encode($json);
ob_end_flush();
?>