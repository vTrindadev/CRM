<?php
// --- Conexão com o Banco de Dados ---
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

// --- Verificação de Formulário e Inserção de Dados ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fabricante = $_POST["fabricante"];
    $titulo = $_POST["titulo"];
    $materialSAP = $_POST["materialSAP"];
    $linhaCarcaca = $_POST["linhaCarcaca"];
    $descricaoSAP = $_POST["descricaoSAP"];

    $sql = "INSERT INTO equipamentos (Fabricante, Título, MaterialSAP, LinhaCarcaca, DescricaoSAP) 
            VALUES ('$fabricante', '$titulo', '$materialSAP', '$linhaCarcaca', '$descricaoSAP')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Equipamento adicionado com sucesso!'); window.location.href='BD_Equipamentos.php';</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <!-- Cabeçalho -->
  <meta charset="UTF-8">
  <title>CRM CRV - Adicionar Equipamento</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/addbdequip.css">
</head>
<body>

  <!-- Loader -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <!-- Menu -->
  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="javascript:history.back()" class="btn-menu">
          <h3>Voltar</h3>
      </a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
    </div>
    <div class="opt-menu">
      <a href="add_bdequip.php" class="btn-menu activo"><h3>Adicionar +</h3></a>
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <!-- Container Principal -->
  <div class="container">
    <!-- Formulário de Adição de Equipamento -->
    <div class="info-container">
      <form method="POST" action="">
        <div class="form-section">
          <div class="form-section-title">Adicionar Equipamento</div>
          <div class="form-group">
            <label for="fabricante">Fabricante:</label>
            <input type="text" name="fabricante" id="fabricante" required placeholder="Digite o fabricante">
          </div>
          <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required placeholder="Digite o título">
          </div>
          <div class="form-group">
            <label for="materialSAP">Material SAP:</label>
            <input type="text" name="materialSAP" id="materialSAP" required placeholder="Digite o material SAP">
          </div>
          <div class="form-group">
            <label for="linhaCarcaca">Linha Carcaça:</label>
            <input type="text" name="linhaCarcaca" id="linhaCarcaca" required placeholder="Digite a linha da carcaça">
          </div>
          <div class="form-group">
            <label for="descricaoSAP">Descrição SAP:</label>
            <input type="text" name="descricaoSAP" id="descricaoSAP" required placeholder="Digite a descrição SAP">
          </div>
        </div>

        <div class="form-group">
          <button type="submit">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="js/filtro.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>

</body>
</html>
