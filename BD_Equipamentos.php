<?php
include('protection.php');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta SQL para pegar os dados dos equipamentos
$sql = "SELECT * FROM equipamentos";  // Substitua 'equipamentos' pelo nome correto da tabela
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+EQUIP</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/BDCliente.css">
</head>
<body>
  <div id="loader">
      <div class="spinner"></div>
  </div>
  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
        <a href="javascript:history.back()" class="btn-menu">
            <h3>Voltar</h3>
        </a>
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu">
          <h3>Clientes</h3>
      </a>
      <a href="BD_Equipamentos.php" class="btn-menu activo">
          <h3>Equipamentos</h3>
      </a>
    </div>
    <div class="opt-menu">
      <a href="add_bdequip.php" class="btn-menu">
          <h3>Adicionar +</h3>
      </a>
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <div class="container">
    
    <div class="info-container">
      <?php
      // Verifica se há resultados na consulta
      if ($result->num_rows > 0) {
          // Exibe cada card com os dados
          while($row = $result->fetch_assoc()) {
              echo '<div class="info-card">';
              echo '<div class="card-content">';
              echo '<div class="edit-icon">';
              echo '<a href="edit_cliente.php?codigo=' . urlencode($row["Título"]) . '" title="Editar">';
              echo '<img src="img/edit-icon.svg" alt="Editar" class="icon-edit">';
              echo '</a>';
              echo '</div>';
              echo '<div class="card-section">';
              echo '<p><strong>Fabricante:</strong> ' . htmlspecialchars($row["Fabricante"]) . '</p>';
              echo '<p><strong>Título:</strong> ' . htmlspecialchars($row["Título"]) . '</p>';
              echo '</div>';
              echo '<div class="card-section">';
              echo '<p><strong>Material SAP:</strong> ' . htmlspecialchars($row["MaterialSAP"]) . '</p>';
              echo '<p><strong>Linha Carcaça:</strong> ' . htmlspecialchars($row["LinhaCarcaca"]) . '</p>';
              echo '</div>';
              echo '<div class="card-section">';
              echo '<p><strong>Descrição SAP:</strong> ' . htmlspecialchars($row["DescricaoSAP"]) . '</p>';
              echo '</div>';
              echo '</div>';
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

  <script src="js/filtro.js"></script>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>

  <!-- Script de Filtro -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputBusca = document.getElementById("inputBusca");
        const cards = document.querySelectorAll(".info-card");

        inputBusca.addEventListener("input", function () {
            const filtro = inputBusca.value.toLowerCase();

            cards.forEach(card => {
                const texto = card.textContent.toLowerCase(); // Filtrando pelo texto do card
                if (texto.includes(filtro)) {
                    card.style.display = "flex"; // ou "block", dependendo do seu layout
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
  </script>
</body>
</html>
