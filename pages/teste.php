<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm_db";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta na tabela teste
$sql = "SELECT Victorcp FROM teste";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM IMPLEMENTAÇÃO - Teste</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/BDCliente.css">
</head>
<body>
  <div id="menu">
    <a href="home.php"><img id="Logo" src="img/weg branco.png" alt="Logo WEG"></a>
    <div class="opt-menu">
      <a href="home.php" class="btn-menu"><h3>Home</h3></a>
      <a href="CRV.html" class="btn-menu"><h3>Aplicador Implementação</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="teste.php" class="btn-menu activo"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.html" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <h1>Lista de Clientes da Tabela Teste</h1>

    <?php
    if ($result->num_rows > 0) {
        // Exibe os dados dinamicamente
        while($row = $result->fetch_assoc()) {
            echo '
            <div class="info-split-card">
              <div class="info-left">
                <p><strong>Nome:</strong> ' . htmlspecialchars($row["Victorcp"]) . '</p>
              </div>
              <div class="info-right">
                <div class="arrow-icon">➤</div>
              </div>
            </div>';
        }
    } else {
        echo "<p>Nenhum cliente encontrado.</p>";
    }
    $conn->close();
    ?>
  </div>

  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>
