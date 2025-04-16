<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $nota = $_POST['nota'];
    $crv = $_POST['crv'];
    $cliente = $_POST['cliente'];
    $codigoCliente = $_POST['codigoCliente'];
    $nomeCliente = $_POST['nomeCliente'];
    $cnpj = $_POST['cnpj'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];
    $escopo = $_POST['escopo'];
    $status = $_POST['status'];
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

    // Inserir os dados no banco de dados
    $insertSql = "INSERT INTO demandas (Nota, crv, Cliente, CodigoCliente, NomeCliente, Cnpj, Cidade, Estado, Pais, Escopo, Status, Cotacao, PrazoProposta, Prioridade, TipoProposta, refCliente, EspecificacaoCliente, Emfabrica, QuantidadeEquip, Equipamentos, Observacao)
                  VALUES ('$nota', '$crv', '$cliente', '$codigoCliente', '$nomeCliente', '$cnpj', '$cidade', '$estado', '$pais', '$escopo', '$status', '$cotacao', '$prazoProposta', '$prioridade', '$tipoProposta', '$refCliente', '$especificacaoCliente', '$emFabrica', '$quantidadeEquip', '$equipamentos', '$observacao')";

    if ($conn->query($insertSql) === TRUE) {
        echo "Demanda criada com sucesso!";
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
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <form id="detalhesForm" method="POST">

      <div class="form-section">
        <div class="form-section-title">Identificação</div>
        <div class="form-group"><label for="nota">Nota:</label><input type="text" id="nota" name="nota" required></div>
        <div class="form-group"><label for="crv">CRV:</label><input type="text" id="crv" name="crv" required></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Cliente</div>
        <div class="form-group"><label for="cliente">Cliente:</label><input type="text" id="cliente" name="cliente" required></div>
        <div class="form-group"><label for="codigoCliente">Código Cliente:</label><input type="text" id="codigoCliente" name="codigoCliente" required></div>
        <div class="form-group"><label for="nomeCliente">Nome Cliente:</label><input type="text" id="nomeCliente" name="nomeCliente" required></div>
        <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" name="cnpj" required></div>
        <div class="form-group"><label for="cidade">Cidade:</label><input type="text" id="cidade" name="cidade" required></div>
        <div class="form-group"><label for="estado">Estado:</label><input type="text" id="estado" name="estado" required></div>
        <div class="form-group"><label for="pais">País:</label><input type="text" id="pais" name="pais" required></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Proposta</div>
        <div class="form-group"><label for="escopo">Escopo:</label><input type="text" id="escopo" name="escopo" required></div>
        <div class="form-group"><label for="status">Status:</label><input type="text" id="status" name="status" required></div>
        <div class="form-group"><label for="cotacao">Cotação:</label><input type="text" id="cotacao" name="cotacao" required></div>
        <div class="form-group"><label for="prazoProposta">Prazo Proposta:</label><input type="text" id="prazoProposta" name="prazoProposta" required></div>
        <div class="form-group"><label for="prioridade">Prioridade:</label><input type="text" id="prioridade" name="prioridade" required></div>
        <div class="form-group"><label for="tipoProposta">Tipo Proposta:</label><input type="text" id="tipoProposta" name="tipoProposta" required></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Comercial & Técnica</div>
        <div class="form-group"><label for="refCliente">Ref Cliente:</label><input type="text" id="refCliente" name="refCliente" required></div>
        <div class="form-group"><label for="especificacaoCliente">Especificação Cliente:</label><input type="text" id="especificacaoCliente" name="especificacaoCliente" required></div>
        <div class="form-group"><label for="emFabrica">Em Fábrica:</label><input type="text" id="emFabrica" name="emFabrica" required></div>
        <div class="form-group"><label for="quantidadeEquip">Quantidade Equip:</label><input type="text" id="quantidadeEquip" name="quantidadeEquip" required></div>
        <div class="form-group"><label for="equipamentos">Equipamentos:</label><input type="text" id="equipamentos" name="equipamentos" required></div>
        <div class="form-group" style="width: 100%;"><label for="observacao">Observação:</label><input type="text" id="observacao" name="observacao" required></div>
      </div>

      <div class="form-group">
        <button type="submit">Criar Demanda</button>
      </div>
    </form>
  </div>

  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>

