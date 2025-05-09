<?php
include('protection.php');

if (!isset($_SESSION['acesso'])) {
  echo "Sessão não iniciada ou variável 'acesso' não definida.";
  exit();
}

if ($_SESSION['acesso'] !== 'Admin') {
  echo "Acesso negado. Você não tem permissão para acessar esta página.";
  exit();
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

$sql = "SELECT * FROM demandas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+ALL</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/all.css">
</head>
<body>
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <div id="menu">
    <a href="home.php"><img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG"></a>
    <div class="opt-menu">
      <a href="all.php" class="btn-menu activo"><h3> All</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
      <a href="add_crv.php" class="btn-menu"><h3>Adicionar +</h3></a>
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <div class="container">
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
                  $row["status_aplicador"] . ' ' .  
                  $row["aplicador"] . ' ' . 
                  $row["crv"] . ' ' . 
                  $row["Status"]
              );

              $url = "detalhes_crv.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&Status=" . urlencode($row["Status"]) . "&status_aplicador=" . urlencode($row["status_aplicador"]);

              $Status = strtolower($row["Status"]);
              $StatusClass = '';

              switch ($Status) {
                  case 'proposta em elaboração':
                      $StatusClass = 'Status-elaboracao';
                      break;
                  case 'em peritagem':
                      $StatusClass = 'Status-peritagem';
                      break;
                  case 'perdido':
                      $StatusClass = 'Status-perdido';
                      break;
                  default:
                      $StatusClass = 'Status-default';
              }

              $Status = strtolower($row["status_aplicador"]);
              $StatusClass = '';

              switch ($Status) {
                case 'proposta em elaboração':
                    $StatusClass = 'Status-elaboracao';
                    break;
                case 'em peritagem':
                    $StatusClass = 'Status-peritagem';
                    break;
                case 'perdido':
                    $StatusClass = 'Status-perdido';
                    break;
                case 'distribuir':
                    $StatusClass = 'Status-distribuir';
                    break;
                case 'concluído':
                    $StatusClass = 'Status-concluído';
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
              echo '<p><strong>CRV:</strong> ' . htmlspecialchars($row["crv"]) . '</p>';
              echo '<p><strong>Aplicador:</strong> ' . htmlspecialchars($row["aplicador"]) . '</p>';
              echo '</div>';
              echo '<div class="info-row">';
              echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
              echo '<p><strong>Escopo:</strong> ' . htmlspecialchars($row["Escopo"]) . '</p>';
              echo '<p><strong>Tipo Proposta:</strong> ' . htmlspecialchars($row["TipoProposta"]) . '</p>';
              $prioridade = strtolower($row["Prioridade"]);
              $prioridadeClass = '';
              
              switch ($prioridade) {
                  case 'urgente':
                      $prioridadeClass = 'prioridade-urgente';
                      break;
                  case 'máquina parada':
                      $prioridadeClass = 'prioridade-maquina';
                      break;
                  case 'Normal':
                      $prioridadeClass = 'prioridade-normal';
                      break;
                  case 'estimativa':
                      $prioridadeClass = 'prioridade-estimativa';
                      break;
                  default:
                      $prioridadeClass = 'prioridade-default';
              }      
              echo '</div>';
              echo '</div>';
              echo '<div class="card-end">';
              echo '<div class="Status-badge ' . $StatusClass . '">' . htmlspecialchars($row["Status"]) . '</div>';
              echo '<div class="Status-badge ' . $StatusClass . '">' . htmlspecialchars($row["status_aplicador"]) . '</div>';
              echo '<p class="prioridade-badge ' . $prioridadeClass . '"><strong>Prioridade:</strong> ' . htmlspecialchars($row["Prioridade"]) . '</p>';
              echo '<div class="arrow-icon"><a href="' . $url . '">➤</a></div>';
              echo '</div>';
              echo '</div>';
          }
      } else {
          echo "<p>Nenhum resultado encontrado.</p>";
      }
      $conn->close();
      ?>
    </div>
  </div>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/filtro.js"></script>
</body>
</html>
