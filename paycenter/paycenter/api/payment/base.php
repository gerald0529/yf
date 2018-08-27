<?php
use Omnipay\Omnipay;

require_once '../../../../vendor/autoload.php';
 //构造参数
 $arr=$_GET;	
 $gateway = Omnipay::create('PayPal_Express');
 $gateway->setUsername('1730154793-shop_api1.qq.com');
 $gateway->setPassword('9PU9443BE3ELCVPL');
 $gateway->setSignature('ASF70zltkxECKkEhGr2c-kpo7VzaAGcJndBmJ-pKDx2rqoIk56GqMjPL');   
 $gateway->setTestMode(true);

 session_start();
$params=$_SESSION['params'];

$response = $gateway->completePurchase($params)->send();

$paypalResponse = $response->getData();
return $paypalResponse;


 