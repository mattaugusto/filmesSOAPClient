<?php
  try {
    $id = $_POST["id"];
    $client = new SoapClient('http://127.0.0.1:8666/filme?wsdl');
    $function = 'exclui';
    $arguments = array(
      "id" => $id,
    );
    $response = $client->__soapCall($function, $arguments);
    header("Location: http://localhost/FilmesClient/");
    die();
  } catch (Exception $exception) {
      die('Error initializing SOAP client: ' . $exception->getMessage());
  }
?>
