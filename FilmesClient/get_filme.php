<?php
  try {
    $id = trim($_GET['id']);
    $client = new SoapClient('http://127.0.0.1:8666/filme?wsdl');
    $function = 'get';
    $arguments = array(
      'id' => $id,
    );
    $response = $client->__soapCall($function, $arguments);
    session_start();
    print_r($response);
    die();
  } catch (Exception $exception) {
      die('Error initializing SOAP client: ' . $exception->getMessage());
  }
?>
