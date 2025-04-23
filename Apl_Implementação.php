<?php
include('protection.php');

if (!isset($_SESSION)) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Email do usuário logado
$emailUsuario = $_SESSION['email'] ?? '';

// Buscar apenas demandas vinculadas ao CRV (email)
$sql = "SELECT * FROM demandas WHERE crv = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $emailUsuario);
$stmt->execute();
$result = $stmt->get_result();
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
    <a href="home.php"><img id="Logo" src="img/weg branco.png" alt="Logo WEG"></a>
    <div class="opt-menu">
      <a href="home.php" class="btn-menu"><h3>Home</h3></a>
      <a href="Apl_Implementação.php" class="btn-menu activo"><h3>Aplicador Implementação</h3></a>
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
    <div id="holder"></div>
    <div class="info-container">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $busca = strtolower(
                  $row["Nota"] . ' ' .
                  $row["Cotacao"] . ' ' .
                  $row["Cliente"] . ' ' .
                  $row["Escopo"] . ' ' .
                  $row["TipoProposta"] . ' ' .
                  $row["id"] . ' ' .
                  $row["Status"]
              );

              $url = "detalhes.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&Status=" . urlencode($row["Status"]);

              $Status = strtolower($row["Status"]);
              $StatusClass = '';

              switch ($Status) {
                  case 'concluído':
                      $StatusClass = 'Status-concluído';
                      break;
                  case 'em andamento':
                      $StatusClass = 'Status-andamento';
                      break;
                  case 'pendente':
                      $StatusClass = 'Status-pendente';
                      break;
                  default:
                      $StatusClass = 'Status-default';
              }

              echo '<div class="info-card" data-busca="' . htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') . '">';
              echo '<div class="info-sections">';
              echo '<div class="info-row">';
              echo '<p><strong>ID:</strong> ' . htmlspecialchars($row["id"]) . '</p>';
              echo '<p><strong>Nota:</strong> ' . htmlspecialchars($row["Nota"]) . '</p>';
              echo '<p><strong>Cotação:</strong> ' . htmlspecialchars($row["Cotacao"]) . '</p>';
              echo '</div>';
              echo '<div class="info-row">';
              echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
              echo '<p><strong>Escopo:</strong> ' . htmlspecialchars($row["Escopo"]) . '</p>';
              echo '<p><strong>Tipo Proposta:</strong> ' . htmlspecialchars($row["TipoProposta"]) . '</p>';
              echo '</div>';
              echo '</div>';
              echo '<div class="card-end">';
              echo '<div class="Status-badge ' . $StatusClass . '">' . htmlspecialchars($row["Status"]) . '</div>';
              echo '<div class="arrow-icon"><a href="' . $url . '">➤</a></div>';
              echo '</div>';
              echo '</div>';
          }
      } else {
          echo "<p style='color: white;'>Nenhuma demanda encontrada para este usuário.</p>";
      }

      $stmt->close();
      $conn->close();
      ?>
    </div>
  </div>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/filtro.js"></script>
</body>
</html>
