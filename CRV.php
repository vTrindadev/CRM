<?php
include('protection.php');

if (!isset($_SESSION)) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$emailUsuario = $_SESSION['email'] ?? '';

// Demandas do CRV
$sql = "SELECT * FROM demandas WHERE crv LIKE ?";
$stmt = $conn->prepare($sql);
$likeEmail = "%$emailUsuario%";
$stmt->bind_param("s", $likeEmail);
$stmt->execute();
$result = $stmt->get_result();

// Notificações por "proposta concluída" ou "urgente"
$notificacoes = [];
$sqlNotif = "SELECT id, Nota, Status, Prioridade FROM demandas 
             WHERE crv LIKE ? AND 
             (Status = 'proposta concluída' OR Status = 'perdido' OR Status = 'informação pendente')";
$stmtNotif = $conn->prepare($sqlNotif);
$stmtNotif->bind_param("s", $likeEmail);
$stmtNotif->execute();
$resultNotif = $stmtNotif->get_result();

while ($rowNotif = $resultNotif->fetch_assoc()) {
    $exibir = [];

    // Verifica o Status
    if (strtolower($rowNotif['Status']) === 'proposta concluída') {
        $exibir['tipo'] = 'status';
        $exibir['id'] = $rowNotif['id'];
        $exibir['info'] = $rowNotif['Status'];
    }
    // Verifica a Prioridade (exemplo para 'urgente')
    elseif (strtolower($rowNotif['Status']) === 'perdido') {
        $exibir['tipo'] = 'status';
        $exibir['id'] = $rowNotif['id'];
        $exibir['info'] = $rowNotif['Status'];
    }

    elseif (strtolower($rowNotif['Status']) === 'informação pendente') {
        $exibir['tipo'] = 'status';
        $exibir['id'] = $rowNotif['id'];
        $exibir['info'] = $rowNotif['Status'];
    }

    // Adiciona à lista de notificações se não estiver vazia
    if (!empty($exibir)) {
        $notificacoes[] = $exibir;
    }
}

$stmtNotif->close();
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
      <a href="Apl_Proposta.php" class="btn-menu activo"><h3>Aplicador Proposta</h3></a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
        <div class="notification-icon">
            <img src="img/bell.svg" class="bell-icon" alt="Sino" id="notificationBell">
            <?php if (count($notificacoes) > 0): ?>
            <span class="notification-dot"></span>
            <div class="notification-dropdown" id="notificationDropdown">
                <ul>
                <?php foreach ($notificacoes as $n): ?>
                    <li>
                        <a href="detalhes_crv.php?id=<?= urlencode($n['id']) ?>">
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

        <form action="logout.php" method="post">
            <button type="submit" class="btn-menu-sair">Sair</button>
        </form>
    </div>

  </div>

  <div class="container">
    <div class="info-container">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $busca = strtolower(
                  $row["Nota"] . ' ' .
                  $row["Cotacao"] . ' ' .
                  $row["crv"] . ' ' .
                  $row["Cliente"] . ' ' .
                  $row["Escopo"] . ' ' .
                  $row["TipoProposta"] . ' ' .
                  $row["id"] . ' ' .
                  $row["Status"]
              );

              $url = "detalhes_crv.php?id=" . $row["id"] . "&nota=" . urlencode($row["Nota"]) . "&cotacao=" . urlencode($row["Cotacao"]) . "&cliente=" . urlencode($row["Cliente"]) . "&escopo=" . urlencode($row["Escopo"]) . "&Status=" . urlencode($row["status_aplicador"]);

              $Status = strtolower($row["Status"]);
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

              echo '<div class="info-card" data-busca="' . htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') . '">';
              echo '<div class="info-sections">';
              echo '<div class="info-row">';
              echo '<p><strong>ID:</strong> ' . htmlspecialchars($row["id"]) . '</p>';
              echo '<p><strong>Nota:</strong> ' . htmlspecialchars($row["Nota"]) . '</p>';
              echo '<p><strong>Cotação:</strong> ' . htmlspecialchars($row["Cotacao"]) . '</p>';
              echo '<p><strong>Prazo Proposta:</strong> ' . htmlspecialchars($row["PrazoProposta"]) . '</p>';
              echo '<p><strong>Aplicador:</strong> ' . htmlspecialchars($row["aplicador"]) . '</p>';
              echo '</div>';
              echo '<div class="info-row">';
              echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
              echo '<p><strong>Escopo:</strong> ' . htmlspecialchars($row["Escopo"]) . '</p>';
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
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notificationBell");
    const dropdown = document.getElementById("notificationDropdown");

    if (bell && dropdown) {
        bell.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        // Fecha se clicar fora
        document.addEventListener("click", function () {
            dropdown.style.display = "none";
        });

        // Impede que o clique dentro da dropdown feche ela
        dropdown.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }
});</script>
</body>
</html>
