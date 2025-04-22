<?php
include('protection.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM MEG</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/home.css">
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
        <a href="home.php" class="btn-menu activo">
            <h3>Home</h3>
        </a>
        <a href="all.php" class="btn-menu">
            <h3>All</h3>
        </a>
        <a href="CRV.php" class="btn-menu">
            <h3>CRV</h3>
        </a>
        <a href="Apl_Proposta.php" class="btn-menu">
            <h3>Aplicador de Propostas</h3>
        </a>
        <a href="Apl_Implementação.php" class="btn-menu">
            <h3>Aplicador de Implementação</h3>
        </a>
    </div>
    <div class="opt-menu">
      <form action="logout.php" method="post">
          <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>

  </div>

  <div class="container">
    <h1>Welcome to <br> CRM MEG <br><span class="username"><?php echo $_SESSION['user']; ?></span></h1>
</div>


    <script src="js/loader.js"></script>
    <script src="js/home.js"></script>
</body>
</html>
