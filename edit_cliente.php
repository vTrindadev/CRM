<?php
include('protection.php');

// Conexão com o banco
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o código foi passado via GET
if (!isset($_GET['codigo'])) {
    echo "Código do cliente não fornecido.";
    exit;
}

$codigo = $_GET['codigo'];

// Se o formulário foi enviado, atualiza o cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $CNPJ = $_POST["CNPJ"];
    $Cliente = $_POST["Cliente"];
    $Cidade = $_POST["Cidade"];
    $Estado = $_POST["Estado"];
    $País = $_POST["País"];

    $sql = "UPDATE clientes SET 
                CNPJ = '$CNPJ',
                Cliente = '$Cliente',
                Cidade = '$Cidade',
                Estado = '$Estado',
                País = '$País'
            WHERE Código = '$codigo'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cliente atualizado com sucesso!'); window.location.href='BD_Cliente.php';</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Busca os dados do cliente atual
$sql = "SELECT * FROM clientes WHERE Código = '$codigo'";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    echo "Cliente não encontrado.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+EDITCUSTOMER</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/addbdequip.css">
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
      <a href="javascript:history.back()" class="btn-menu"><h3>Voltar</h3></a>
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
    </div>
    <div class="opt-menu">
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>

  <div class="container">
    <div class="info-container">
      <form method="POST" action="">
        <div class="form-section-title">Editar Cliente</div>

        <div class="form-section">
          <div class="form-group">
            <label for="Código">Código:</label>
            <input type="text" id="Código" value="<?= htmlspecialchars($row["Código"]) ?>" disabled>
          </div>

          <div class="form-group">
            <label for="CNPJ">CNPJ:</label>
            <input type="text" name="CNPJ" id="CNPJ" required value="<?= htmlspecialchars($row["CNPJ"]) ?>">
          </div>
        </div>

        <div class="form-section">
          <div class="form-group">
            <label for="Cliente">Cliente:</label>
            <input type="text" name="Cliente" id="Cliente" required value="<?= htmlspecialchars($row["Cliente"]) ?>">
          </div>

          <div class="form-group">
            <label for="Cidade">Cidade:</label>
            <input type="text" name="Cidade" id="Cidade" required value="<?= htmlspecialchars($row["Cidade"]) ?>">
          </div>
        </div>

        <div class="form-section">
          <div class="form-group">
            <label for="Estado">Estado:</label>
            <input type="text" name="Estado" id="Estado" required value="<?= htmlspecialchars($row["Estado"]) ?>">
          </div>

          <div class="form-group">
            <label for="País">País:</label>
            <input type="text" name="País" id="País" required value="<?= htmlspecialchars($row["País"]) ?>">
          </div>
        </div>

        <div class="form-group">
          <button type="submit">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>

  <script src="js/loader.js"></script>
</body>
</html>
