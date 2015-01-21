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
	if(isset($_GET['_stock']) && isset($_GET['_id']))
	{
		//
		// Check if the product or variation exists, calculate the inventory adjustment, update inventory
		//
		
		$product = get_product($_GET['_id']);
		if($product->exists())
		{
			$product->set_stock($_GET['_stock']);
			status_header( 200 );
			$json = array("status" => "success");
		} else {
			status_header( 400 );
			$json = array(
				"status" => "failure",
				"error" => "no products match _id"
			);	
		}
	} else {
		status_header( 400 );
		$json = array(
			"status" => "failure",
			"error" => "missing _stock or _id value"
		);
	}
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