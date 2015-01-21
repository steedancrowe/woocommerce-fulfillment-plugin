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
    // Loop through orders, build array for json
    //

    $orderNumbersStart = '8277';
    $orderNumbersEnd = '8462';
    $orderNumber = $orderNumberStart;

        do {
          $orderNumber .= +1;

           $ordersArray = array(
             'Key' => $optionsArray['efs_merchant_id'],
             'OrderID' => $orderNumber
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
        $client = new SOAPClient('http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?WSDL', 
        // $client = new SOAPClient('https://www.integratedfulfillment.net/WebSrv/OrderProcessing.asmx?WSDL', 
                array('trace' => true,
                      'soap_version' => 'SOAP_1_1',
                      'content-type' => 'text/xml',
                    ));
        
         } while($orderNumber != $orderNumbersEnd);


         try {
          
          // make the call to the API

          $makeOrders = $client->OrderQuery(array('xmlString'=> $ordersArray, 'encoding'=>'utf-16'));

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

        var_dump($makeOrders);

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

