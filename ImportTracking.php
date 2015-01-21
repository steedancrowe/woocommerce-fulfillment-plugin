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
$efs_shipped_status = $optionsArray['efs_shipped_status'];
$efs_tracking_note = $optionsArray['efs_tracking_note'];

//
// Check for incoming efs_merchant_token & efs_merchant_id to match options
//
if($efs_merchant_id == $_GET['efs_merchant_id'] && $efs_merchant_token == $_GET['efs_merchant_token'])
{
	if(isset($_GET['_tracking']) && isset($_GET['_id']))
	{
		if($efs_shipped_status != 'DISABLED')
		{
			//
			// Get status ID
			//
			$updateStatusId = $wpdb->get_var(
				"
				SELECT slug
				FROM $wpdb->terms
				WHERE name = '{$efs_shipped_status}'
				"
			);
			
			//
			// Update order status on success and add tracking number to notes
			//
			$order = new WC_Order( $_GET['_id'] );
			$notes = $order->get_customer_order_notes();
			$addNote = 1;
			if($order->status != $efs_shipped_status)
			{
				$trackingArray = explode(";", $_GET['_tracking']);
				$trackingNumber = $trackingArray[0];
				if(isset($_GET['_carrier']))
				{
					$carrier = strtolower($_GET['_carrier']);
					if(!add_post_meta( $_GET['_id'], '_date_shipped', time(), true)) update_post_meta($_GET['_id'], '_date_shipped', time());
					if(!add_post_meta( $_GET['_id'], '_tracking_number', $trackingNumber, true)) update_post_meta($_GET['_id'], '_tracking_number', $trackingNumber);
					if(!add_post_meta( $_GET['_id'], '_tracking_provider', $carrier, true)) update_post_meta($_GET['_id'], '_tracking_provider', $carrier);
					if(!add_post_meta( $_GET['_id'], '_custom_tracking_provider', '', true)) update_post_meta($_GET['_id'], '_custom_tracking_provider', '');
					if(!add_post_meta( $_GET['_id'], '_custom_tracking_link', '', true)) update_post_meta($_GET['_id'], '_custom_tracking_link', '');
					$carrier = strtoupper($carrier) . " ";
				}
				if(count($trackingArray) > 1)
				{
				    $trackingMessage = "Your order was shipped with multiple {$carrier}tracking numbers: " . implode(",", $trackingArray);
				    foreach ($notes as $note) {
						if($note->comment_content == $trackingMessage) $addNote = 0;
					}
					if($addNote == 1)
					{
				    	$order->add_order_note($trackingMessage, 1);
				    }
				} else {
					$trackingMessage = "Your order was shipped with the following {$carrier}tracking number: " . $trackingNumber;
					foreach ($notes as $note) {
						if($note->comment_content == $trackingMessage) $addNote = 0;
					}
					if($addNote == 1)
					{
						if(isset($efs_tracking_note))
						{
							if($efs_tracking_note == 'YES') $order->add_order_note($trackingMessage, 1);
						} else {
							$order->add_order_note($trackingMessage, 1);
						}
					}
				}
				$order->update_status($updateStatusId);
			}
		}
		status_header( 200 );
		$json = array("status" => "success");
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