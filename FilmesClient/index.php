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
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script>
      function getAttributeOfXML(xml, attribute){
        return xml.getElementsByTagName(attribute)[0].childNodes[0].nodeValue;
      }
      function editModal(element, id){
        // Get the modal
        var modal = document.getElementById('edit_modal');
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName('close')[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = 'none';
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = 'none';
          }
        }
        modal.style.display = 'block';
        $.ajax({
          type: 'GET',
          url: 'get_filme.php?id='+id,
          data: {},
          success: function(data) {
            parser = new DOMParser();
            xml = parser.parseFromString(data, 'text/xml');
            console.log(xml)
            $('#edit_id').val(id);
            $('#edit_titulo').val(getAttributeOfXML(xml, 'titulo'));
            $('#edit_diretor').val(getAttributeOfXML(xml, 'diretor'));
            $('#edit_genero').val(getAttributeOfXML(xml, 'genero'));
            $('#edit_lancamento').val(getAttributeOfXML(xml, 'lancamento'));
          },
          error:function(err){
            alert("error"+JSON.stringify(err));
        }
      });
      }
      function closeModal(){
      var modal = document.getElementById('edit_modal');
      modal.style.display = 'none';
    }
    </script>
  </head>
  <body>
    <?php
      session_start();
      if(isset($_SESSION['filmes'])){
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
    <h2>Filmes | SOAP-WSDL</h2>
    <div class="search-container">
      <form action="consulta.php">
        <input id="search_bar" type="text" placeholder="Filtro.." name="filtro">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
    <div class="container">
      <form action="adicionar_filme.php" method="post">
        <div class="row">
          <div class="col-50">
            <label class="lbform" for="titulo">Título</label>
          </div>
          <div class="col-75">
            <input type="text" id="titulo" name="titulo" placeholder="Título..">
          </div>
        </div>
        <div class="row">
          <div class="col-50">
            <label class="lbform" for="diretor">Diretor</label>
          </div>
          <div class="col-75">
            <input type="text" id="diretor" name="diretor" placeholder="Diretor..">
          </div>
        </div>
        <div class="row">
          <div class="col-50">
            <label class="lbform" for="genero">Gênero</label>
          </div>
          <div class="col-75">
            <input type="text" id="genero" name="genero" placeholder="Gênero..">
          </div>
        </div>
        <div class="row">
          <div class="col-50">
            <label class="lbform" for="lancamento">Ano de Lançamento</label>
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
              <input id="editar_button" class="edit_submit" type="submit"
                value="Editar"
                onclick="editModal(this, <?=(string) $filme->id?>)"/>
            </td>
          </tr>
      <?php endforeach; ?>
    </table>
    <div id="edit_modal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <form action="alterar_filme.php" method="post">
          <input hidden id="edit_id" name="id" value="">
          <div class="row">
            <div class="col-50">
              <label class="lbform" for="titulo">Título</label>
            </div>
            <div class="col-75">
              <input type="text" id="edit_titulo" name="titulo" placeholder="Título..">
            </div>
          </div>
          <div class="row">
            <div class="col-50">
              <label class="lbform" for="diretor">Diretor</label>
            </div>
            <div class="col-75">
              <input type="text" id="edit_diretor" name="diretor" placeholder="Diretor..">
            </div>
          </div>
          <div class="row">
            <div class="col-50">
              <label class="lbform" for="genero">Gênero</label>
            </div>
            <div class="col-75">
              <input type="text" id="edit_genero" name="genero" placeholder="Gênero..">
            </div>
          </div>
          <div class="row">
            <div class="col-50">
              <label class="lbform" for="lancamento">Ano de Lançamento</label>
            </div>
            <div class="col-75">
              <input type="text" id="edit_lancamento" name="lancamento" placeholder="Ano de Lançamento..">
            </div>
          </div>
          <div id="add_button" class="row">
            <input class="add_submit" type="submit" value="Confirmar" onclick="closeModal()">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
