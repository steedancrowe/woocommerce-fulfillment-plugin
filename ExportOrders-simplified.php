<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


			$wsdl   = "http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?wsdl"; 
			$client = new SoapClient($wsdl);



			//Use the functions of the client, the params of the function are in 
			//the associative array
				$order_data = new stdClass();
				// $ProductQuery = new stdClass();
				$order_data->Key = '97ECC170-D946-4DD1-9C17-9F18E2C35437';
				$order_data->SKU = 'GSPRT';
				
				// $order_data = array();		
				// $order_data['key'][] = '97ECC170-D946-4DD1-9C17-9F18E2C35437';
				// $order_data['SKU'][] = 'GSPRT';
				
				// $response = $client->ConnectionPoll();

				$response = $client->ProductQuery($order_data);
				// $response = new SoapServer("http://rpsolutions-2.realprofitsolutions.com/IFWebSrvTest/OrderProcessing.asmx?wsdl", array('ProductQuery', $order_data));

				// $response = $client->ProductQuery();
		
				var_dump($response);
				// var_dump($order_data);

				  // Check for a fault
		    if (is_soap_fault($client)) {       
		        echo '<h2>Fault</h2><pre>';
		        print_r($response);
		        echo '</pre>';      
		    }else{
		        echo "Result: <BR><pre>";
		        print_r($response);
		        echo '</pre>';
		    }
					$debug = "====== REQUEST HEADERS =====" . PHP_EOL;
	    		$debug .= var_dump($client->__getLastRequestHeaders());
	    		$debug .= "========= REQUEST ==========" . PHP_EOL;
	    		$debug .= var_dump($client->__getLastRequest());
	    		$debug .= "========= RESPONSE =========" . PHP_EOL;
	    		$debug .= var_dump($response);


		

	// print $debug;
	
?>