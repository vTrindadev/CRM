<?php
// Inicia a sessão caso não esteja ativa
include('protection.php');
if (!isset($_SESSION)) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recupera o email do usuário logado (assumindo que está armazenado na sessão)
$emailUsuario = $_SESSION['email'] ?? '';

// Prepara a consulta para buscar apenas demandas vinculadas ao CRV (usando o email do usuário)
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
  <!-- Loader para exibir enquanto os dados estão sendo carregados -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <!-- Menu de navegação -->
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
      <!-- Formulário de logout -->
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <!-- Área principal do conteúdo -->
  <div class="container">
    <div class="info-container">
      <?php
      // Verifica se a consulta retornou resultados
      if ($result->num_rows > 0) {
          // Loop através das demandas encontradas
          while ($row = $result->fetch_assoc()) {
              // Concatena todos os dados da demanda para a busca
              $busca = strtolower(
                  $row["Nota"] . ' ' .
                  $row["Cotacao"] . ' ' .
                  $row["Cliente"] . ' ' .
                  $row["Escopo"] . ' ' .
                  $row["TipoProposta"] . ' ' .
                  $row["id"] . ' ' .
                  $row["Status"]
              );

              // Gera a URL para detalhamento da demanda
              $url = "detalhes.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&Status=" . urlencode($row["Status"]);

              // Definir a classe do status com base no valor do status
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

              // Exibição das informações da demanda
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
          // Mensagem caso não haja demandas para o usuário logado
          echo "<p style='color: white;'>Nenhuma demanda encontrada para este usuário.</p>";
      }

      // Fecha a consulta e a conexão com o banco
      $stmt->close();
      $conn->close();
      ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/filtro.js"></script>
</body>
</html>
