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

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Código = $_POST["Código"];
    $CNPJ = $_POST["CNPJ"];
    $Cliente = $_POST["Cliente"];
    $Cidade = $_POST["Cidade"];
    $Estado = $_POST["Estado"];
    $País = $_POST["País"];

    $sql = "INSERT INTO clientes (Código, CNPJ, Cliente, Cidade, Estado, País) 
            VALUES ('$Código', '$CNPJ', '$Cliente', '$Cidade', '$Estado', '$País')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cliente adicionado com sucesso!'); window.location.href='BD_Cliente.php';</script>";
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
    <a href="home.php">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="home.php" class="btn-menu">
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
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <div class="container">
    <div id="holder"></div>
    <div class="info-container">
    <form method="POST" action="">
            <label>Código:</label>
            <input type="text" name="Código" required placeholder="Digite...">

            <label>CNPJ:</label>
            <input type="text" name="CNPJ" required placeholder="Digite...">

            <label>Cliente:</label>
            <input type="text" name="Cliente" required placeholder="Digite...">

            <label>Cidade:</label>
            <input type="text" name="Cidade" required placeholder="Digite...">

            <label>Estado:</label>
            <input type="text" name="Estado" required placeholder="Digite...">

            
            <label>País:</label>
            <input type="text" name="País" required placeholder="Digite...">

            <button type="submit">Salvar</button>
        </form>
    </div>
  </div>
  <script src="js/filtro.js"></script>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>
</body>
</html>
