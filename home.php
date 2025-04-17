<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"index.php\">Entrar</a></p>");
}


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
    <a href="home.html">
        <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
    </a>
    <div class="opt-menu">  
        <a href="home.html" class="btn-menu activo">
            <h3>Home</h3>
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
        <button id="logoutButton" class="btn-menu-sair">Sair</button>
    </div>
  </div>

  <div class="container">
    <h1>Welcome to <br> CRM MEG</h1>
  </div>
    <script src="js/verificador.js"></script>
    <script src="js/loader.js"></script>
    <script src="js/home.js"></script>
</body>
</html>
