<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//Set Output Header
header('Content-Type: application/json; charset=UTF-8');

function array_to_xml($array, &$xml) {
	foreach($array as $key => $value) {
    if(is_array($value)) {
        if(!is_numeric($key)){
            $subnode = $xml->addChild("$key");
            array_to_xml($value, $subnode);
        } else {
            array_to_xml($value, $xml);
        }
    } else {
        $xml->addChild("$key","$value");
    }
	}
}

//Prevent unwanted echoes
// ob_start();

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
	// Get Orders
	//
	$statusFilter = $efs_order_status;
	$orders = $wpdb->get_results( 
		"
		SELECT 
			p.ID AS OrderNumber,
			p.post_excerpt AS OrderComments,
			t.name AS OrderStatus
			
		FROM $wpdb->posts p
		JOIN $wpdb->term_relationships tr ON tr.object_id = p.ID
		JOIN $wpdb->term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
		JOIN $wpdb->terms t ON t.term_id = tt.term_id
		WHERE p.post_type = 'shop_order'
		AND t.name = '{$statusFilter}'
		"
	);

	//
	// Continue if orders are available
	//
	$orderCount = count($orders);
	if($orderCount > 0)
	{
		//
		// Get Order Details
		//
		$parsedOrders = array();
		foreach($orders as $order)
		{
			$parsedOrder = array();
			$parsedOrder['OrderComments'] = $order->OrderComments;
				
			$orderMeta = $wpdb->get_results(
				"
				SELECT *
				FROM $wpdb->postmeta
				WHERE post_id = {$order->OrderNumber}
				"
			);
			
			foreach($orderMeta as $meta)
			{
				$parsedOrder[$meta->meta_key] = $meta->meta_value;
			}
			$parsedOrder['ID'] = $order->OrderNumber;
			$parsedOrders[$order->OrderNumber] = $parsedOrder;
		}
		
		//
		// Loop through orders, build array for json
		//
		$ordersArray = array();
		foreach($parsedOrders as $orderNumber => $orderData)
		{
			//
			// Retrieve SKU and build item XML
			//
			$wooOrder = new WC_Order($orderNumber);
			$newShippingMethod = $wooOrder->get_shipping_method();
			if(strlen($newShippingMethod) > 0)
			{
				$shipping_method = $newShippingMethod;
			} else {
				$shipping_method = (strlen($orderData['_shipping_method_title']) > 0 ? $orderData['_shipping_method_title'] : $orderData['_shipping_method']);
			}

			$orderItems = $wooOrder->get_items();
			$itemsArray = array();
			foreach($orderItems as $orderItem)
			{
				if($orderItem['variation_id'] > 0)
				{
					$skuId = $orderItem['variation_id'];
				} else {
					$skuId = $orderItem['product_id'];
				}
				$sku = $wpdb->get_var(
					"
					SELECT meta_value
					FROM $wpdb->postmeta
					WHERE post_id = {$skuId}
					AND meta_key = '_sku'
					"
				);
				if(!$sku) $sku = "NO_SKU_ASSIGNED";
				
				$itemsArray[] = array(
					"OrderedItem" => array(
					"ItemSKU" => 'BT2X2.BB',
					"QuantityOrdered" => (int)$orderItem['qty'],
					"QuantityShipped" => (int)$orderItem['qty']
					)
				);	
			}

			if(strlen($orderData['_order_number_formatted']) > 0)
			{
				$sequentialOrderNumber = (string)$orderData['_order_number_formatted'];
			} elseif (strlen($orderData['_order_number']) > 0) {
				$sequentialOrderNumber = (string)$orderData['_order_number'];
			} else {
				$sequentialOrderNumber = '000000';
			}
			
			//
			// Build order
			//
			
			// $client = new SoapClient('http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx');

			//Use the functions of the client, the params of the function are in 
			//the associative array

      $ordersArray[] = array(
         'Key' => $optionsArray['efs_merchant_id'],
         'RemoteOrderNumber' => $orderData['ID'],
         'ShipFirstName' => $orderData['_shipping_first_name'],
          'ShipLastName' => $orderData['_shipping_last_name'],
          'ShipCompany' => $orderData['_shipping_company'],
          'ShipAddress1' => $orderData['_shipping_address_1'],
          'ShipAddress2' => $orderData['_shipping_address_2'],
          'ShipCity' => $orderData['_shipping_city'],
          'ShipPostalCode' => $orderData['_shipping_postcode'],
          'ShipCountryCode' => $orderData['_shipping_country'],
          'ShipProvinceCode' => $orderData['_shipping_state'],
          'ShipPhone1' => $orderData['_shipping_phone'],
          'BillFirstName' => $orderData['_billing_first_name'],
          'BillLastName' => $orderData['_billing_last_name'],
          'BillCompany' => $orderData['_billing_company'],
          'BillAddress1' => $orderData['_billing_address_1'],
          'BillAddress2' => $orderData['_billing_address_2'],
          'BillCity' => $orderData['_billing_city'],
          'BillPostalCode' => $orderData['_billing_postcode'],
          'BillCountryCode' => $orderData['_billing_country'],
          'ShipEMailAddress' => $orderData['_shipping_email'],
          'BillEMailAddress' => $orderData['_billing_email'],
          'BillProvinceCode' => $orderData['_billing_state'],
          'BillPhone1' => $orderData['_billing_phone'],
          'ShipMethodCode' => 'CPC01',
          'ProjectCode' => 'CWP01',
          'TransactionTypeID' => '1',
          'WareHouse' => 'T',
          $itemsArray,
        );
		
		$order_query = array(
				'Key' => '97ECC170-D946-4DD1-9C17-9F18E2C35437',
				'OrderID' => '111569'
			);

		// $product_query = new stdClass();
		// $product_query->Key = $optionsArray['efs_merchant_id'];
		// $product_query->SKU = 'GSPRT';	

		$product_query = array(
					'Key' => $optionsArray['efs_merchant_id'],
					'SKU' => 'GSPRT'
		);

		// $product_query = new stdClass();
		// $product_query = array();
		// $product_query->Key = $optionsArray['efs_merchant_id'];
		// $product_query->SKU = 'GSPRT';	
			
		// $response = new SoapServer("https://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?wsdl", array('OrderSubmit', $order_data));

		// $response = new SoapServer("https://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?wsdl", array('OrderQuery' => $order_query));

		// $response = new SoapServer("http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?wsdl");
		
		// print_r($ordersArray[1]);

		foreach($ordersArray as $orderArray){

			$response = $client->OrderSubmit('OrderSubmit', $orderArray);

			var_dump($response);
			
			// $xml = new SimpleXMLElement('<xmlString/>');
			// $node = $xml->addChild('root');
			// array_to_xml($orderArray, $node);


			// $URL = "http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx";

			// $xml_data = $xml;
 
			// $ch = curl_init($URL);
			// curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			// curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// $output = curl_exec($ch);
			// curl_close($ch);

		}

		// $response = $client->ConnectionPoll('ConnectionPoll');



		}
		$json = array(
			"status" => "success",
			"orders" => $params
		);
	
	} else {
		$json = array(
			"status" => "success",
			"orders" => "[]"
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
	
	// ob_clean();
	

	// echo json_encode($json);

	// $order_data = array_reverse($order_data);

	// $xml = new SimpleXMLElement('<xmlString/>');
	// $node = $xml->addChild('root');
	// array_to_xml($order_data, $node);
	
	// print $xml->asXML();

	// var_dump($response);

	// ob_end_flush();
?>