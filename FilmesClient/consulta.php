<?php
  try {
    $filtro = $_POST["filtro"];
    $client = new SoapClient('http://127.0.0.1:8666/filme?wsdl');
    $function = 'consulta';
    $arguments = array(
      "filtro" => $filtro,
    );
    $response = $client->__soapCall($function, $arguments);
    session_start();
    $_SESSION['filmes'] = $response;
    header("Location: http://localhost/FilmesClient/");
    die();
  } catch (Exception $exception) {
      die('Error initializing SOAP client: ' . $exception->getMessage());
  }
?>
