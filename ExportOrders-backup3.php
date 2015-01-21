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


// function array_to_objecttree($array) {
//   if (is_numeric(key($array))) { // Because Filters->Filter should be an array
//     foreach ($array as $key => $value) {
//       $array[$key] = array_to_objecttree($value);
//     }
//     return $array;
//   }
//   $Object = new stdClass;
//   foreach ($array as $key => $value) {
//     if (is_array($value)) {
//       $Object->$key = array_to_objecttree($value);
//     }  else {
//       $Object->$key = $value;
//     }
//   }
//   return $Object;
// }



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
					"ItemSKU" => $sku,
					"QuantityOrdered" => (int)$orderItem['qty'],
					"QuantityShipped" => (int)$orderItem['qty']
				);	
			}

			if(strlen($orderData['_order_number_formatted']) > 0)
			{
				$sequentialOrderNumber = (string)$orderData['_order_number_formatted'];
			} elseif (strlen($orderData['_order_number']) > 0) {
				$sequentialOrderNumber = (string)$orderData['_order_number'];
			} else {
				$sequentialOrderNumber = null;
			}
			
			//
			// Build order
			//
			
			// $client = new SoapClient(
			// 	null, array(
   //      'location' => '',
   //      'uri' => 'http://www.realprofitsolutions.com/OrderSubmit';
   //      'trace' => 1,
   //      'use' => SOAP_LITERAL,
   //  		)
			// );


			// $wsdl   = "http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx"; 
			// $client = new SoapClient($wsdl);

			//Use the functions of the client, the params of the function are in 
			//the associative array
				$order_data = array(
				  'Key' => $optionsArray['efs_merchant_id'],
				  'RemoteOrderNumber' => (strlen($sequentialOrderNumber) > 0 ? $sequentialOrderNumber : (string)$orderNumber),
				  'RemoteCustomerID' => '', 
				  'OrderDate' => '09/30/2014',
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
					'ShipFax' => '',
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
					'BillFax' => '',
					'ShipMethodCode' => 'CPC15',
					'ProjectCode' => 'CWP01',
					'TransactionTypeID' => '1',
					'WareHouse' => 'T',
					'OrderedItem' => array(
					  'ItemSKU' => 'C1-BLK',
					  'QuantityOrdered' => '1',
					  'QuantityShipped' => '1'
					),
					'OrderedItem' => array(
					  'ItemSKU' => 'C1-J10',
					  'QuantityOrdered' => '1',
					  'QuantityShipped' => '1'
						),
					);

				// $xml = array(
				// 	'Key' => '97ECC170-D946-4DD1-9C17-9F18E2C35437',
				// 	'SKU' => 'GSPRT',
				// 	);

				// $tracking = new OrderSubmit();
				// $tracking = new ArrayObject;
				// $tracking->Key = '97ECC170-D946-4DD1-9C17-9F18E2C35437';
				// $tracking->SKU = 'GSPRT';
				// $xmlArray = array();
				// $xmlArray[] = array(
				// 	'Key'=>'97ECC170-D946-4DD1-9C17-9F18E2C35437',
				// 	'SKU'=>'GSPRT',
				// );
				


				// $xml = array_to_objecttree($xml);

				$xml = new SimpleXMLElement('<xmlString></xmlString>');
				$node = $xml->addChild('root');
				array_to_xml($order_data, $node);

				// $xml = file_get_contents('post_xml.xml');
				$url = 'https://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx/OrderSubmit';


				$post_data = array(
				    "xml" => $xml,
				);

				$stream_options = array(
				    'http' => array(
				       'method'  => 'POST',
				       'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				       'content' => http_build_query($post_data),
				    ),
				);

				// $context  = stream_context_create($stream_options);

				// return $context;

				// $response = file_get_contents($url, null, $context);

				// $xml_header = "<ns1:GenericSearchRequest>"
				//      . "<soap:Envelope>$id</soap:Envelope>"
				//      . "<ns1:CallingSystem>WEB</ns1:CallingSystem>"
				//      . "</ns1:GenericSearchRequest>";
				// $query = new SoapVar($xml, XSD_ANYXML);

				// $response = $this->client->__SoapCall(
				//     'GenericUniqueIdentifierSearch',
				//     array($query)
				// );

				// $args = array(new SoapVar($xml, XSD_ANYXML));    

			
				// $response = $client->ProductQuery($tracking);

				// var_dump($client->getFunctions());
				// var_dump($client->getTypes());

				// var_dump($response);


					// $debug = "====== REQUEST HEADERS =====" . PHP_EOL;
	    // 		$debug .= var_dump($client->__getLastRequestHeaders());
	    // 		$debug .= "========= REQUEST ==========" . PHP_EOL;
	    // 		$debug .= var_dump($client->__getLastRequest());
	    // 		$debug .= "========= RESPONSE =========" . PHP_EOL;
	    // 		$debug .= var_dump($response);


				// var_dump($response);

				// return $response;
				
				// } catch (SoapFault $e) {
				    // echo "Error: {$e}";
				// }
				

		// $order_data = array_flip($order_data);

		// array_walk_recursive($order_data, array ($xml, 'addChild'));
		
		// $response = $client->OrderSubmit($xml);

		// var_dump($client->getFunctions());
		// var_dump($client->getTypes());

		// var_dump($response);

		// var_dump($xml);

		// echo "REQUEST:\n" . $response->__getLastRequest() . "\n";
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

	$xml = new SimpleXMLElement('<xmlString/>');
	$node = $xml->addChild('root');
	array_to_xml($order_data, $node);
	print $xml->asXML();

	// print $debug;
	// echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
	// print_r($xml);
	// var_dump($response);
	// echo json_encode($json);
	// ob_end_flush();

	
?>