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

function objectToArray($d) {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }
 
    if (is_array($d)) {
      /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
      return array_map(__FUNCTION__, $d);
    }
    else {
      // Return array
      return $d;
    }
}

function arrayToObject($d) {
  if (is_array($d)) {
    /*
    * Return array converted to object
    * Using __FUNCTION__ (Magic constant)
    * for recursive call
    */
    return (object) array_map(__FUNCTION__, $d);
  }
  else {
    // Return object
    return $d;
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
$efs_received_status = $optionsArray['efs_received_status'];
$efs_shipped_status = $optionsArray['efs_shipped_status'];

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
          $skuId = $orderItem['id'];
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
          "ItemSKU" => $sku,
          // "ItemSKU" => 'BT2X2.BB',
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

        if($orderData['_shipping_country'] == 'US') {
          $shippingMethod = 'UPS03';
        } else{
          if($orderData['_order_total'] >= 200){
            $shippingMethod = 'CPC14';  
          } else {
            $shippingMethod = 'CPC01';
          }
        }

      $ordersArray = array(
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
          'BillFirstName' => $orderData['_billing_first_name'],
          'BillLastName' => $orderData['_billing_last_name'],
          'BillCompany' => $orderData['_billing_company'],
          'BillAddress1' => $orderData['_billing_address_1'],
          'BillAddress2' => $orderData['_billing_address_2'],
          'BillCity' => $orderData['_billing_city'],
          'BillProvinceCode' => $orderData['_billing_state'],
          'BillPostalCode' => $orderData['_billing_postcode'],
          'BillCountryCode' => $orderData['_billing_country'],
          'ShipEMailAddress' => $orderData['_billing_email'],
          'BillEMailAddress' => $orderData['_billing_email'],
          'ShipPhone1' => $orderData['_shipping_phone'],
          'BillPhone1' => $orderData['_billing_phone'],
          'ShipMethodCode' => $shippingMethod,
          'ProjectCode' => 'CWP01',
          'TransactionTypeID' => '1',
          'WareHouse' => 'T',
          $itemsArray,
        );
        
      $orders = $ordersArray;

      $xml = new simpleXMLElement('<root/>');
      array_to_xml($orders, $xml);

      $xml = $xml->asXML();
      $ordersArray = str_replace('<?xml version="1.0"?>', '', $xml);
      $ordersArray = trim($ordersArray);

      // print_r($ordersArray);
      // die(); 

    $namespace = 'http://schemas.xmlsoap.org/soap/envelope/';      

    // setup a SOAP client, and assign the authentication headers to $client
    // $client = new SOAPClient('http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?WSDL', 
    $client = new SOAPClient('https://www.integratedfulfillment.net/WebSrv/OrderProcessing.asmx?WSDL', 
            array('trace' => true,
                  'soap_version' => 'SOAP_1_1',
                  'content-type' => 'text/xml',
                ));
     

    try {
      
      // make the call to the API

      $makeOrders = $client->OrderSubmit(array('xmlString'=> $ordersArray, 'encoding'=>'utf-16'));

      $updateStatusId = $wpdb->get_var(
        "
        SELECT slug
        FROM $wpdb->terms
        WHERE name = '{$efs_received_status}'
        "
      );
      
      //
      // Update order status on success and add tracking number to notes
      //

    } catch (SOAPFault $exception) {
      var_dump($client->__getLastRequestHeaders());
      var_dump($client->__getLastRequest());
      // var_dump($client->__getFunctions());
      var_dump($exception);
      exit;
    }
     
    // again, normally we'd do something useful here
    print "order ID:". $orderData['ID'] ."\r\n";
    var_dump($makeOrders);

    // print_r($makeOrders);
    $response = $makeOrders->OrderSubmitResult->any;
    $xml     = simplexml_load_string($response);
  
    if($xml[0]->Status == 0){
      $order = new WC_Order( $orderData['ID'] );
      if($order->status != $efs_received_status)
      {
        $order->update_status($updateStatusId);
      }
    }
    

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

echo json_encode($json);
  
?>

