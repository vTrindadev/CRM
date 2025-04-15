<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta SQL para pegar os dados
$sql = "SELECT * FROM demandas";  // Substitua 'teste' pelo nome correto da tabela
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM CRV</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/crv.css">
</head>
<body>
  <div id="loader">
      <div class="spinner"></div>
  </div>
  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="home.php" class="btn-menu">
          <h3>Home</h3>
      </a>
      <a href="CRV.php" class="btn-menu activo">
          <h3>Aplicador de Implementação</h3>
      </a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu">
          <h3>Clientes</h3>
      </a>
      <a href="BD_Equipamentos.php" class="btn-menu">
          <h3>Equipamentos</h3>
      </a>
    </div>
    <div class="opt-menu">
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <div class="info-container">
      <?php
      // Verifica se há resultados na consulta
      if ($result->num_rows > 0) {
          // Exibe cada card com os dados
          while($row = $result->fetch_assoc()) {
              echo '<div class="info-card">';
              echo '<div>';
              echo '<p><strong>Nota:</strong> ' . $row["Nota"] . '</p>';
              echo '<p><strong>Cotação:</strong> ' . $row["Cotacao"] . '</p>';
              echo '<p><strong>Cliente:</strong> ' . $row["Cliente"] . '</p>';
              echo '<p><strong>Escopo:</strong> ' . $row["Escopo"] . '</p>';
              echo '<p><strong>Tipo Proposta:</strong> ' . $row["TipoProposta"] . '</p>';
              echo '<p><strong>Status:</strong> ' . $row["Status"] . '</p>';
              echo '</div>';
              echo '<div class="arrow-icon">➤</div>';
              echo '</div>';
          }
      } else {
          echo "<p>Nenhum resultado encontrado.</p>";
      }

      // Fecha a conexão com o banco de dados
      $conn->close();
      ?>
    </div>
  </div>

  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>

</body>
</html>
