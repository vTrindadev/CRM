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
  <!-- --- Cabeçalho --- -->
  <meta charset="UTF-8">
  <title>MEG+ADDCUSTOMER</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/addbdequip.css">
</head>
<body>

  <!-- --- Loader --- -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <!-- --- Menu --- -->
  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
      <a href="javascript:history.back()" class="btn-menu">
          <h3>Voltar</h3>
      </a>
      <a href="BD_Cliente.php" class="btn-menu">
          <h3>Clientes</h3>
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

  <!-- --- Container Principal --- -->
  <div class="container">
    
    <!-- --- Formulário de Adição de Cliente --- -->
    <div class="info-container">
      <form method="POST" action="">
        <div class="form-section-title">Adicionar Cliente</div>
        <div class="form-section">
          <div class="form-group">
            <label for="Código">Código:</label>
            <input type="text" name="Código" id="Código" required placeholder="Digite...">
          </div>

          <div class="form-group">
            <label for="CNPJ">CNPJ:</label>
            <input type="text" name="CNPJ" id="CNPJ" required placeholder="Digite...">
          </div>
        </div>

        <div class="form-section">
          <div class="form-group">
            <label for="Cliente">Cliente:</label>
            <input type="text" name="Cliente" id="Cliente" required placeholder="Digite...">
          </div>

          <div class="form-group">
            <label for="Cidade">Cidade:</label>
            <input type="text" name="Cidade" id="Cidade" required placeholder="Digite...">
          </div>
        </div>

        <div class="form-section">
          <div class="form-group">
            <label for="Estado">Estado:</label>
            <input type="text" name="Estado" id="Estado" required placeholder="Digite...">
          </div>

          <div class="form-group">
            <label for="País">País:</label>
            <input type="text" name="País" id="País" required placeholder="Digite...">
          </div>
        </div>

        <div class="form-group">
          <button type="submit">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- --- Scripts --- -->
  <script src="js/filtro.js"></script>
  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/crv.js"></script>

</body>
</html>
