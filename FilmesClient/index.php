<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="index.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Filmes | SOAP-WSDL</title>
    </head>
    <body>
      <h2>Filmes | SOAP-WSDL</h2>
      <div class="search-container">
        <form action="consulta.php">
          <input type="text" placeholder="Filtro.." name="filtro">
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
      </div>
      <div class="container">
        <form action="adicionar_filme.php" method="post">
          <div class="row">
            <div class="col-25">
              <label for="fname">Título</label>
            </div>
            <div class="col-75">
              <input type="text" id="titulo" name="titulo" placeholder="Título..">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="lname">Diretor</label>
            </div>
            <div class="col-75">
              <input type="text" id="diretor" name="diretor" placeholder="Diretor..">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="country">Gênero</label>
            </div>
            <div class="col-75">
              <input type="text" id="genero" name="genero" placeholder="Gênero..">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="subject">Ano de Lançamento</label>
            </div>
            <div class="col-75">
              <input type="text" id="lancamento" name="lancamento" placeholder="Ano de Lançamento..">
            </div>
          </div>
          <div id="add_button" class="row">
            <input class="add_submit" type="submit" value="Adicionar">
          </div>
        </form>
      </div>
      <?php
        session_start();
        if($_SESSION['filmes']){
          print_r($_SESSION['filmes']);
          $xmlResponse = simplexml_load_string($_SESSION['filmes']);
          $_SESSION['filmes'] = null;
        }else{
          try {
            $client = new SoapClient('http://127.0.0.1:8666/filme?wsdl');
            // print_r($client->__getFunctions());
            $function = 'lista';
            $arguments = array();
            $response = $client->__soapCall($function, $arguments);
            $xmlResponse = simplexml_load_string($response);
          } catch (Exception $exception) {
              die('Error initializing SOAP client: ' . $exception->getMessage());
          }
        }
      ?>
      <table>
        <tr>
          <th>Título</th>
          <th>Diretor</th>
          <th>Gênero</th>
          <th>Ano</th>
          <th>Ações</th>
        </tr>
        <?php foreach($xmlResponse->filme as $filme): ?>
            <tr>
              <td><?=(string) $filme->titulo?></td>
              <td><?=(string) $filme->diretor?></td>
              <td><?=(string) $filme->genero?></td>
              <td><?=(string) $filme->lancamento?></td>
              <td>
                <form action="excluir_filme.php" method="post">
                  <input hidden id="id" name="id" value="<?=(string) $filme->id?>">
                  <input class="rm_submit" type="submit" value="Excluir"/>
                </form>
              </td>
            </tr>
        <?php endforeach; ?>
      </table>

    </body>
</html>
