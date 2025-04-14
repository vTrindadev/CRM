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

// Verifica se o formulário foi enviado
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
  <meta charset="UTF-8">
  <title>CRM CRV</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/addbdequip.css">
</head>
<body>
  <div id="loader">
      <div class="spinner"></div>
  </div>
  <div id="menu">
    <a href="home.html">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="home.html" class="btn-menu">
          <h3>Home</h3>
      </a>
      <a href="CRV.php" class="btn-menu">
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
      <a href="add_bdequip.php" class="btn-menu activo">
          <h3>Adicionar +</h3>
      </a>
      <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <div class="info-container">
    <form method="POST" action="">
            <label>Fabricante:</label>
            <input type="text" name="fabricante" required placeholder="Digite...">

            <label>Título:</label>
            <input type="text" name="titulo" required placeholder="Digite...">

            <label>Material SAP:</label>
            <input type="text" name="materialSAP" required placeholder="Digite...">

            <label>Linha Carcaça:</label>
            <input type="text" name="linhaCarcaca" required placeholder="Digite...">

            <label>Descrição SAP:</label>
            <input type="text" name="descricaoSAP" required placeholder="Digite...">

            <button type="submit">Salvar</button>
        </form>
    </div>
  </div>
  <script src="js/filtro.js"></script>
  <script src="js/verificador.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>
</body>
</html>
