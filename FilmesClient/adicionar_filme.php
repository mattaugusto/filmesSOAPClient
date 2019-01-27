<?php
  try {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $genero = $_POST['genero'];
    $lancamento = $_POST['lancamento'];
    $client = new SoapClient('http://127.0.0.1:8666/filme?wsdl');
    $function = 'cadastra';
    $arguments = array(
      'titulo' => $titulo,
      'diretor' => $diretor,
      'genero' => $genero,
      'lancamento' => $lancamento
    );
    $response = $client->__soapCall($function, $arguments);
    header("Location: http://localhost/FilmesClient/");
    die();
  } catch (Exception $exception) {
      die('Error initializing SOAP client: ' . $exception->getMessage());
  }
?>
