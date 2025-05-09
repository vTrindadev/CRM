<?php
// Proteção da página (verificação de sessão)
include('protection.php');

// Inicia a sessão caso não esteja ativa
if (!isset($_SESSION)) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Estabelecendo conexão com o banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recuperando o e-mail do usuário logado (se existir)
$emailUsuario = $_SESSION['email'] ?? '';

// Consulta para buscar as demandas associadas ao aplicador (usuário logado)
$sql = "SELECT * FROM demandas WHERE aplicador LIKE ?";
$likeEmail = "%$emailUsuario%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $likeEmail);
$stmt->execute();
$result = $stmt->get_result();

// Inicializando um array para as notificações de demandas "em implantação" ou "urgente"
$notificacoes = [];

// Consulta para buscar notificações de demandas "em implantação" ou "urgente"
$sqlNotif = "SELECT id, Nota, status_aplicador, Prioridade FROM demandas 
             WHERE aplicador LIKE ? AND 
             (status_aplicador = 'em implantação' OR Prioridade = 'urgente')";
$stmtNotif = $conn->prepare($sqlNotif);
$stmtNotif->bind_param("s", $likeEmail);
$stmtNotif->execute();
$resultNotif = $stmtNotif->get_result();

// Processamento das notificações
while ($rowNotif = $resultNotif->fetch_assoc()) {
    $exibir = [];

    if (strtolower($rowNotif['status_aplicador']) === 'em implantação') {
        $exibir['tipo'] = 'status';
        $exibir['id'] = $rowNotif['id'];
        $exibir['info'] = $rowNotif['status_aplicador'];
    } elseif (strtolower($rowNotif['Prioridade']) === 'urgente') {
        $exibir['tipo'] = 'prioridade';
        $exibir['id'] = $rowNotif['id'];
        $exibir['info'] = $rowNotif['Prioridade'];
    }

    if (!empty($exibir)) {
        $notificacoes[] = $exibir;
    }
}

// Fechando a consulta de notificações
$stmtNotif->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+PROP</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/crv.css">
</head>
<body>
  <!-- Loader de carregamento -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <!-- Menu de navegação -->
  <div id="menu">
    <a href="home.php"><img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG"></a>
    <div class="opt-menu">
      <a href="Apl_Proposta.php" class="btn-menu activo"><h3>Aplicador Proposta</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
        <!-- Ícone de notificações -->
        <div class="notification-icon">
            <img src="img/bell.svg" class="bell-icon" alt="Sino" id="notificationBell">
            <?php if (count($notificacoes) > 0): ?>
            <span class="notification-dot"></span>
            <div class="notification-dropdown" id="notificationDropdown">
                <ul>
                <?php foreach ($notificacoes as $n): ?>
                    <li>
                        <a href="detalhes.php?id=<?= urlencode($n['id']) ?>">
                            <strong>ID:</strong> <?= htmlspecialchars($n['id']) ?><br>
                            <?php if ($n['tipo'] === 'status'): ?>
                                <strong>Status:</strong> <?= htmlspecialchars($n['info']) ?>
                            <?php elseif ($n['tipo'] === 'prioridade'): ?>
                                <strong>Prioridade:</strong> <?= htmlspecialchars($n['info']) ?>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <!-- Formulário de logout -->
        <form action="logout.php" method="post">
            <button type="submit" class="btn-menu-sair">Sair</button>
        </form>
    </div>
  </div>

  <!-- Container principal de conteúdo -->
  <div class="container">
    <div class="info-container">
      <?php
      // Verifica se existem demandas para exibir
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // Preparação dos dados para busca (lowercase para pesquisa)
              $busca = strtolower(
                  $row["Nota"] . ' ' .
                  $row["Cotacao"] . ' ' .
                  $row["crv"] . ' ' .
                  $row["Cliente"] . ' ' .
                  $row["Escopo"] . ' ' .
                  $row["TipoProposta"] . ' ' .
                  $row["id"] . ' ' .
                  $row["status_aplicador"]
              );

              // URL para os detalhes da demanda
              $url = "detalhes.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&Status=" . urlencode($row["status_aplicador"]);

              // Definição de classe de status com base no valor de status_aplicador
              $Status = strtolower($row["status_aplicador"]);
              $StatusClass = match ($Status) {
                  'proposta em elaboração' => 'Status-elaboracao',
                  'em peritagem' => 'Status-peritagem',
                  'perdido' => 'Status-perdido',
                  'distribuir' => 'Status-distribuir',
                  'proposta concluída' => 'Status-concluído',
                  'nova solicitação' => 'Status-solicitacao',
                  'revisar proposta' => 'Status-revisão',
                  'em implantação' => 'Status-negociacao',
                  default => 'Status-default',
              };

              // Exibe os detalhes da demanda em um cartão
              echo '<div class="info-card" data-busca="' . htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') . '">';
              echo '<div class="info-sections">';
              echo '<div class="info-row">';
              echo '<p><strong>ID:</strong> ' . htmlspecialchars($row["id"]) . '</p>';
              echo '<p><strong>Nota:</strong> ' . htmlspecialchars($row["Nota"]) . '</p>';
              echo '<p><strong>Cotação:</strong> ' . htmlspecialchars($row["Cotacao"]) . '</p>';
              echo '<p><strong>Prazo Proposta:</strong> ' . htmlspecialchars($row["PrazoProposta"]) . '</p>';
              echo '<p><strong>CRV:</strong> ' . htmlspecialchars($row["crv"]) . '</p>';
              echo '</div>';
              echo '<div class="info-row">';
              echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
              echo '<p><strong>Escopo:</strong> ' . htmlspecialchars($row["Escopo"]) . '</p>';

              // Definindo classe de prioridade com base no valor de Prioridade
              $prioridade = strtolower($row["Prioridade"]);
              $prioridadeClass = match ($prioridade) {
                  'urgente' => 'prioridade-urgente',
                  'máquina parada' => 'prioridade-maquina',
                  'normal' => 'prioridade-normal',
                  'estimativa' => 'prioridade-estimativa',
                  default => 'prioridade-default',
              };
              echo '</div>';
              echo '</div>';
              echo '<div class="card-end">';
              echo '<div class="Status-badge ' . $StatusClass . '">' . htmlspecialchars($row["status_aplicador"]) . '</div>';
              echo '<p class="prioridade-badge ' . $prioridadeClass . '"><strong>Prioridade:</strong> ' . htmlspecialchars($row["Prioridade"]) . '</p>';
              echo '<div class="arrow-icon"><a href="' . $url . '">➤</a></div>';
              echo '</div>';
              echo '</div>';
          }
      } else {
          // Caso não haja demandas
          echo "<p style='color: white;'>Nenhuma demanda encontrada para este usuário.</p>";
      }

      // Fechar a consulta e a conexão com o banco
      $stmt->close();
      $conn->close();
      ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/filtro.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Controle do menu dropdown de notificações
        const bell = document.getElementById("notificationBell");
        const dropdown = document.getElementById("notificationDropdown");

        if (bell && dropdown) {
            bell.addEventListener("click", function (e) {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            });

            // Fecha o dropdown se clicar fora
            document.addEventListener("click", function () {
                dropdown.style.display = "none";
            });

            // Impede que o clique dentro do dropdown feche ele
            dropdown.addEventListener("click", function (e) {
                e.stopPropagation();
            });
        }
    });
  </script>
</body>
</html>
