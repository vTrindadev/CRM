<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

// Verifica se o formulário foi enviado
if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Consulta o usuário no banco
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            // Inicia sessão
            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['email'] = $usuario['email'];
            $_SESSION['user'] = $usuario['user'];

            // Redireciona para a página inicial
            header("Location: home.php");
            exit;

        } else {
            echo "<p style='color: red;'>Falha ao logar! E-mail ou senha incorretos.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM MEG - Login</title>

    <link rel="stylesheet" href="css/padrao.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section id="conteiner">
        <div id="box-form">
            <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
            <h2 id="titulo-form">Sign In</h2>

            <!-- Formulário de login -->
            <form action="" method="POST" id="loginForm">
                <input type="text" id="username" name="email" required placeholder="Login:">
                <input type="password" id="password" name="senha" required placeholder="Senha:">
                <button type="submit">Login</button>
            </form>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-content">
            <img src="img/weg branco.png" alt="Logo WEG" class="footer-logo">
            <p>&copy; 2025 MEG CRM. Todos os direitos reservados.</p>
        </div>
    </footer>
    
    <script src="js/loader.js"></script>
</body>
</html>
