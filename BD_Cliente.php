<?php
// Inclusão do arquivo de proteção para garantir que o usuário tenha permissão
include('protection.php');

// Definição das variáveis de conexão com o banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco de dados usando MySQLi
$conn = new mysqli($host, $user, $pass, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);  // Caso haja erro, a execução é interrompida
}

// Consulta SQL para buscar todos os dados da tabela "clientes"
$sql = "SELECT * FROM clientes";  // Substitua 'clientes' pelo nome correto da tabela
$result = $conn->query($sql);  // Executa a consulta e armazena o resultado
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+CUSTOMER</title>
  <!-- Links para os arquivos de estilo CSS -->
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/BDCliente.css">
</head>
<body>
  <!-- Loader de carregamento da página -->
  <div id="loader">
      <div class="spinner"></div>
  </div>

  <!-- Menu de navegação -->
  <div id="menu">
    <!-- Link para a página inicial -->
    <a href="home.php">
        <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
        <!-- Botão de voltar para a página anterior -->
        <a href="javascript:history.back()" class="btn-menu">
            <h3>Voltar</h3>
        </a>
        <!-- Barra de busca -->
        <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
        <!-- Link para a página de clientes -->
        <a href="BD_Cliente.php" class="btn-menu activo">
            <h3>Clientes</h3>
        </a>
        <!-- Link para a página de equipamentos -->
        <a href="BD_Equipamentos.php" class="btn-menu">
            <h3>Equipamentos</h3>
        </a>
    </div>
    <div class="opt-menu">
      <!-- Link para adicionar novo cliente -->
      <a href="add_bdcliente.php" class="btn-menu">
          <h3>Adicionar +</h3>
      </a>
      <!-- Formulário de logout -->
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <!-- Container principal onde os dados serão exibidos -->
  <div class="container">
    <div class="info-container">
      <?php
      // Verifica se há resultados na consulta ao banco de dados
      if ($result->num_rows > 0) {
          // Se houver dados, exibe cada cliente em um card
          while($row = $result->fetch_assoc()) {
            echo '<div class="info-card">';
            echo '<div class="card-content">';

            echo '<div class="edit-icon">';
            echo '<a href="edit_cliente.php?codigo=' . urlencode($row["Código"]) . '" title="Editar">';
            echo '<img src="img/edit-icon.svg" alt="Editar" class="icon-edit">';
            echo '</a>';
            echo '</div>';

            echo '<div class="card-section">';
            echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($row["Cliente"]) . '</p>';
            echo '</div>';

            echo '<div class="card-section">';
            echo '<p><strong>Código:</strong> ' . htmlspecialchars($row["Código"]) . '</p>';
            echo '<p><strong>Cidade:</strong> ' . htmlspecialchars($row["Cidade"]) . '</p>';
            echo '</div>';

            echo '<div class="card-section">';
            echo '<p><strong>CNPJ:</strong> ' . htmlspecialchars($row["CNPJ"]) . '</p>';
            echo '<p><strong>Estado:</strong> ' . htmlspecialchars($row["Estado"]) . '</p>';
            echo '</div>';

            echo '</div>'; // fecha card-content
            echo '</div>'; // fecha info-card

          }
      } else {
          // Caso não haja resultados na consulta, exibe uma mensagem
          echo "<p>Nenhum resultado encontrado.</p>";
      }

      // Fecha a conexão com o banco de dados
      $conn->close();
      ?>
    </div>
  </div>

  <!-- Scripts que são carregados ao final para não interferirem no carregamento da página -->
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>

  <!-- Script de filtro para buscar clientes -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Pegando a referência do campo de busca e dos cards
        const inputBusca = document.getElementById("inputBusca");
        const cards = document.querySelectorAll(".info-card");

        // Adiciona um evento para o campo de busca
        inputBusca.addEventListener("input", function () {
            const filtro = inputBusca.value.toLowerCase();  // Converte a entrada para minúsculo

            // Percorre cada card e aplica o filtro
            cards.forEach(card => {
                const texto = card.textContent.toLowerCase();  // Converte o conteúdo do card para minúsculo
                // Se o texto do card inclui o filtro, exibe o card, caso contrário, esconde
                if (texto.includes(filtro)) {
                    card.style.display = "flex";  // ou "block", dependendo do seu layout
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
  </script>
</body>
</html>
