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
$sql = "SELECT * FROM demandas";
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
          <h3>CRV</h3>
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
      <a href="add_crv.php" class="btn-menu">
          <h3>Adicionar +</h3>
      </a>
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <div class="info-container">
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              $busca = strtolower(
                  $row["Nota"] . ' ' . 
                  $row["Cotacao"] . ' ' . 
                  $row["Cliente"] . ' ' . 
                  $row["Escopo"] . ' ' . 
                  $row["TipoProposta"] . ' ' . 
                  $row["id"] . ' ' .  
                  $row["Status"]
              );
              // Criando a URL para direcionar para a página de detalhes
              $url = "detalhes.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&status=" . urlencode($row["Status"]);
              echo '<div class="info-card" data-busca="' . htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') . '">';
              echo '<div>';
              echo '<p><strong>ID:</strong> ' . htmlspecialchars($row["id"]) . '</p>';
              echo '<p><strong>Nota:</strong> ' . htmlspecialchars($row["Nota"]) . '</p>';
              echo '<p><strong>Cotação:</strong> ' . htmlspecialchars($row["Cotacao"]) . '</p>';
              echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
              echo '<p><strong>Escopo:</strong> ' . htmlspecialchars($row["Escopo"]) . '</p>';
              echo '<p><strong>Tipo Proposta:</strong> ' . htmlspecialchars($row["TipoProposta"]) . '</p>';
              echo '<p><strong>Status:</strong> ' . htmlspecialchars($row["Status"]) . '</p>';
              echo '</div>';
              echo '<div class="arrow-icon"><a href="' . $url . '">➤</a></div>';
              echo '</div>';
          }
      } else {
          echo "<p>Nenhum resultado encontrado.</p>";
      }
      $conn->close();
      ?>
    </div>
  </div>

  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/filtro.js"></script>

</body>
</html>
