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
      
      // $client = new SoapClient('http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?WSDL');

      //Use the functions of the client, the params of the function are in 
      //the associative array
    
        if($orderData['_shipping_country'] == 'US') {
          $shippingMethod = 'UPS03';
        } else{
          if($orderData['_order_total'] >= 200){
            $shippingMethod = 'CPC14';  
          } else {
            $shippingMethod = 'CPC01';
          }
        }

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
          'ShipPhone1' => str_replace('(','', $orderData['_shipping_phone']),
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
          'BillPhone1' => str_replace('(','', $orderData['_billing_phone']),
          'ShipMethodCode' => $shippingMethod,
          'ProjectCode' => 'CWP01',
          'TransactionTypeID' => '1',
          'WareHouse' => 'T',
          $itemsArray,
        );
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
  

  echo json_encode($json);

  // $order_data = array_reverse($order_data);
  foreach($ordersArray as $orderArray){
    $xml = new SimpleXMLElement('<root/>');
    $node = $xml->addChild('order');
    array_to_xml($orderArray, $node);  

    print $xml->asXML();

  }
  
  
  
  // print_r($parsedOrders);
  // print_r($ordersArray);
  // var_dump($response);

  // ob_end_flush();
?>