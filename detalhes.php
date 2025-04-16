<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM demandas WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Nenhum dado encontrado.";
        exit;
    }
} else {
    echo "ID não especificado.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM CRV</title>
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
      <a href="home.php" class="btn-menu"><h3>Home</h3></a>
      <a href="CRV.php" class="btn-menu activo"><h3>CRV</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
      <a href="add_crv.php" class="btn-menu"><h3>Adicionar +</h3></a>
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <form id="detalhesForm">

      <div class="form-section">
        <div class="form-section-title">Identificação</div>
        <div class="form-group"><label for="id">ID:</label><input type="text" id="id" value="<?= htmlspecialchars($row['id']) ?>" readonly></div>
        <div class="form-group"><label for="nota">Nota:</label><input type="text" id="nota" value="<?= htmlspecialchars($row['Nota']) ?>" readonly></div>
        <div class="form-group"><label for="crv">CRV:</label><input type="text" id="crv" value="<?= htmlspecialchars($row['crv']) ?>" readonly></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Cliente</div>
        <div class="form-group"><label for="cliente">Cliente:</label><input type="text" id="cliente" value="<?= htmlspecialchars($row['Cliente']) ?>" readonly></div>
        <div class="form-group"><label for="codigoCliente">Código Cliente:</label><input type="text" id="codigoCliente" value="<?= htmlspecialchars($row['CodigoCliente']) ?>" readonly></div>
        <div class="form-group"><label for="nomeCliente">Nome Cliente:</label><input type="text" id="nomeCliente" value="<?= htmlspecialchars($row['NomeCliente']) ?>" readonly></div>
        <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" value="<?= htmlspecialchars($row['Cnpj']) ?>" readonly></div>
        <div class="form-group"><label for="cidade">Cidade:</label><input type="text" id="cidade" value="<?= htmlspecialchars($row['Cidade']) ?>" readonly></div>
        <div class="form-group"><label for="estado">Estado:</label><input type="text" id="estado" value="<?= htmlspecialchars($row['Estado']) ?>" readonly></div>
        <div class="form-group"><label for="pais">País:</label><input type="text" id="pais" value="<?= htmlspecialchars($row['Pais']) ?>" readonly></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Proposta</div>
        <div class="form-group"><label for="escopo">Escopo:</label><input type="text" id="escopo" value="<?= htmlspecialchars($row['Escopo']) ?>" readonly></div>
        <div class="form-group"><label for="status">Status:</label><input type="text" id="status" value="<?= htmlspecialchars($row['Status']) ?>" readonly></div>
        <div class="form-group"><label for="cotacao">Cotação:</label><input type="text" id="cotacao" value="<?= htmlspecialchars($row['Cotacao']) ?>" readonly></div>
        <div class="form-group"><label for="prazoProposta">Prazo Proposta:</label><input type="text" id="prazoProposta" value="<?= htmlspecialchars($row['PrazoProposta']) ?>" readonly></div>
        <div class="form-group"><label for="prioridade">Prioridade:</label><input type="text" id="prioridade" value="<?= htmlspecialchars($row['Prioridade']) ?>" readonly></div>
        <div class="form-group"><label for="tipoProposta">Tipo Proposta:</label><input type="text" id="tipoProposta" value="<?= htmlspecialchars($row['TipoProposta']) ?>" readonly></div>
      </div>

      <div class="form-section">
        <div class="form-section-title">Comercial & Técnica</div>
        <div class="form-group"><label for="refCliente">Ref Cliente:</label><input type="text" id="refCliente" value="<?= htmlspecialchars($row['refCliente']) ?>" readonly></div>
        <div class="form-group"><label for="especificacaoCliente">Especificação Cliente:</label><input type="text" id="especificacaoCliente" value="<?= htmlspecialchars($row['EspecificacaoCliente']) ?>" readonly></div>
        <div class="form-group"><label for="emFabrica">Em Fábrica:</label><input type="text" id="emFabrica" value="<?= htmlspecialchars($row['Emfabrica']) ?>" readonly></div>
        <div class="form-group"><label for="quantidadeEquip">Quantidade Equip:</label><input type="text" id="quantidadeEquip" value="<?= htmlspecialchars($row['QuantidadeEquip']) ?>" readonly></div>
        <div class="form-group"><label for="equipamentos">Equipamentos:</label><input type="text" id="equipamentos" value="<?= htmlspecialchars($row['Equipamentos']) ?>" readonly></div>
        <div class="form-group" style="width: 100%;"><label for="observacao">Observação:</label><input type="text" id="observacao" value="<?= htmlspecialchars($row['Observacao']) ?>" readonly></div>
      </div>
      
    </form>
  </div>

  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>
