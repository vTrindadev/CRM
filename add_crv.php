<?php
include('protection.php');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if(isset($_GET['codigoCliente'])){
  $codigoCliente = $_GET['codigoCliente'];
  
  // Query para pegar os dados do cliente
  $sql = "SELECT Cliente, Cnpj, Cidade, Estado, País FROM clientes WHERE Código = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $codigoCliente);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows > 0){
      $cliente = $result->fetch_assoc();
      echo json_encode($cliente);  // Retorna os dados em formato JSON
  } else {
      echo json_encode(['error' => 'Cliente não encontrado.']);
  }
  exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nota = $_POST['nota'];
    $crv = $_POST['crv'];
    $cliente = $_POST['cliente'];
    $codigoCliente = $_POST['codigoCliente'];
    $cnpj = $_POST['cnpj'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];
    $escopo = $_POST['escopo'];
    $Status = $_POST['Status'];
    $cotacao = $_POST['cotacao'];
    $prazoProposta = $_POST['prazoProposta'];
    $prioridade = $_POST['prioridade'];
    $tipoProposta = $_POST['tipoProposta'];
    $refCliente = $_POST['refCliente'];
    $especificacaoCliente = $_POST['especificacaoCliente'];
    $emFabrica = $_POST['emFabrica'];
    $quantidadeEquip = $_POST['quantidadeEquip'];
    $equipamentos = $_POST['equipamentos'];
    $observacao = $_POST['observacao'];
    $valor = $_POST['valor'];
    $frete = isset($_POST['frete']) ? $_POST['frete'] : null;
    $StatusAplicador = $_POST['status_aplicador']; 
    $aplicador = $_POST['aplicador']; 

    $insertSql = "INSERT INTO demandas (Nota, crv, Cliente, CodigoCliente, Cnpj, Cidade, Estado, Pais, Escopo, Status, Cotacao, PrazoProposta, Prioridade, TipoProposta, refCliente, EspecificacaoCliente, Emfabrica, QuantidadeEquip, Equipamentos, Observacao, valor, frete, status_aplicador, aplicador)
                  VALUES ('$nota', '$crv', '$cliente', '$codigoCliente', '$cnpj', '$cidade', '$estado', '$pais', '$escopo', '$Status', '$cotacao', '$prazoProposta', '$prioridade', '$tipoProposta', '$refCliente', '$especificacaoCliente', '$emFabrica', '$quantidadeEquip', '$equipamentos', '$observacao', '$valor', '$frete', '$StatusAplicador', '$aplicador')";

    if ($conn->query($insertSql) === TRUE) {
        echo "<script>
                alert('Demanda criada com sucesso!');
                window.location.href = 'CRV.php';
              </script>";
    } else {
        echo "Erro ao criar a demanda: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM CRV - Criar Demanda</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/detalhe.css">
</head>
<body>
  <div id="loader"><div class="spinner"></div></div>

  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="CRV.php" class="btn-menu"><h3>CRV</h3></a>
      <a href="detalhes.php" class="btn-menu activo"><h3>Adicionar</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <div class="container">
    
    <form id="detalhesForm" method="POST">

      <!-- Identificação -->
      <div class="form-section">
        <div class="form-section-title">Identificação</div>
        <div class="form-group"><label for="nota">Nota:</label><input type="text" id="nota" name="nota"></div>
        <div class="form-group"><label for="cotacao">Cotação:</label><input type="text" id="cotacao" name="cotacao"></div>
        <div class="form-group">
          <label for="crv">CRV:</label>
          <select id="crv" name="crv" required>
            <option value="">Selecione</option>
            <option value="anan@weg.net">Ana Paula Nolasco</option>
            <option value="abaptista@weg.net">Andre Luis Rosa Baptista</option>
            <option value="diegolc@weg.net">Diego Lopes Caobianco</option>
            <option value="guilhermehk@weg.net">Guilherme Henrique Khun</option>
            <option value="freiriag@weg.net">Joao Pedro Freiria Schlichting</option>
            <option value="mvitor@weg.net">Joao Vitor Machado Mariquito</option>
            <option value="rsilva@weg.net">Ricardo Goncalves da Silva</option>
            <option value="guareschi@weg.net">Rodrigo Guareschi</option>
          </select>
        </div>
      </div>

      <!-- Cliente -->
      <div class="form-section">
        <div class="form-section-title">Cliente</div>
        <div class="form-group"><label for="cliente">Cliente:</label><input type="text" id="cliente" name="cliente" required></div>
        <div class="form-group"><label for="codigoCliente">Código Cliente:</label><input type="text" id="codigoCliente" name="codigoCliente" required onblur="buscarCliente()"></div>
        <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" name="cnpj" required></div>
        <div class="form-group"><label for="cidade">Cidade:</label><input type="text" id="cidade" name="cidade" required></div>
        <div class="form-group"><label for="estado">Estado:</label><input type="text" id="estado" name="estado" required></div>
        <div class="form-group"><label for="pais">País:</label><input type="text" id="pais" name="pais" required></div>
      </div>

      <!-- Proposta -->
      <div class="form-section">
        <div class="form-section-title">Proposta</div>
        <div class="form-group">
          <label for="Status">Status:</label>
          <select id="Status" name="Status" required>
            <option value="Proposta em Elaboração">Proposta em Elaboração</option>
          </select>
        </div>
        <div class="form-group"><label for="aplicador">Aplicador:</label><input type="text" id="aplicador" name="aplicador"></div>
        <div class="form-group">
          <input type="hidden" id="status_aplicador" name="status_aplicador" value="Distribuir">
        </div>
        <div class="form-group"><label for="valor">Valor:</label><input type="text" id="valor" name="valor"></div>
        <div class="form-group"><label for="prazoProposta">Prazo Proposta:</label><input type="date" id="prazoProposta" name="prazoProposta" required></div>
        <div class="form-group">
          <label for="prioridade">Prioridade:</label>
          <select id="prioridade" name="prioridade" required>
            <option value="">Selecione</option>
            <option value="Máquina Parada">Máquina Parada</option>
            <option value="Urgente">Urgente</option>
            <option value="Normal">Normal</option>
            <option value="Estimativa">Estimativa</option>
          </select>
        </div>
        <div class="form-group">
          <label for="tipoProposta">Tipo Proposta:</label>
          <select id="tipoProposta" name="tipoProposta" required>
            <option value="">Selecione</option>
            <option value="Campo">Campo</option>
            <option value="Fábrica">Fábrica</option>
            <option value="Partes e Peças">Partes e Peças</option>
          </select>
        </div>
        <div class="form-group">
          <label for="escopo">Escopo:</label>
          <textarea id="escopo" name="escopo" required></textarea>
        </div>

        <div class="form-group" id="freteContainer" style="display: none;">
          <label for="frete">Frete:</label>
          <select id="frete" name="frete">
            <option value="">Selecione</option>
            <option value="Por conta do Cliente">Por conta do Cliente</option>
            <option value="Por conta da WEG">Por conta da WEG</option>
          </select>
        </div>
      </div>

      <!-- Comercial & Técnica -->
      <div class="form-section">
        <div class="form-section-title">Comercial & Técnica</div>
        <div class="form-group"><label for="refCliente">Ref Cliente:</label><input type="text" id="refCliente" name="refCliente" required></div>
        <div class="form-group"><label for="especificacaoCliente">Especificação Cliente:</label><input type="text" id="especificacaoCliente" name="especificacaoCliente" required></div>
        <div class="form-group">
          <label for="emFabrica">Em Fábrica:</label>
          <select id="emFabrica" name="emFabrica" required>
            <option value="Não">Não</option>
            <option value="Sim">Sim</option>
          </select>
        </div>
        <div class="form-group"><label for="quantidadeEquip">Quantidade Equip:</label><input type="text" id="quantidadeEquip" name="quantidadeEquip" required></div>
        <div class="form-group"><label for="equipamentos">Equipamentos:</label><input type="text" id="equipamentos" name="equipamentos" required></div>
        <div class="form-group">
          <label for="observacao">Observação:</label>
          <textarea id="observacao" name="observacao" required></textarea>
        </div>
      </div>

      <div class="form-group">
        <button type="submit">Criar Demanda</button>
      </div>
    </form>
  </div>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/frete.js"></script>
  <script src="js/aplicadores.js"></script>

  <script>
    function buscarCliente() {
      var codigoCliente = document.getElementById('codigoCliente').value;

      if (codigoCliente.length > 0) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'add_crv.php?codigoCliente=' + codigoCliente, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.error) {
              alert(response.error);
            } else {
              document.getElementById('cliente').value = response.Cliente;
              document.getElementById('cnpj').value = response.Cnpj;
              document.getElementById('cidade').value = response.Cidade;
              document.getElementById('estado').value = response.Estado;
              document.getElementById('pais').value = response.País;
            }
          }
        };
        xhr.send();
      }
    }
  </script>


</body>
</html>
