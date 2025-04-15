<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if(isset($_POST['email']) || isset($_POST['senha'])){
    if(strlen($_POST['email']) == 0){
        echo "Preencha o E-mail";
    } else if(strlen($_POST['senha']) == 0){
        echo "Preencha a Senha";
    } else {
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $conn->real_escape_string($_POST['senha']);
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha='$senha'";
        $sql_query = $conn->query($sql_code) or die ("Falha na execução do código SQL:" . $conn->error);
        $quantidade = $sql_query->num_rows;
        if($quantidade == 1){
            $usuario = $sql_query->fetch_assoc();
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['user'] = $usuario['user'];
            header("Location: home.php");
        } else {
            echo "Falha ao logar! Usuário ou senha incorretos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/padrao.css">
    <link rel="stylesheet" href="css/login.css">

    <title>CRM MEG</title>
</head>
<body>
    <section id="conteiner">
        
        <div id="box-form">
            <img id="Logo" src="img/weg branco.png" alt="">
            <h2 id="titulo-form">Sign In</h2>
            <form action="" id="loginForm" method="POST">
                <input type="text" id="username" name="email" required placeholder="Login:">
                <input type="password" id="password" name="senha" required placeholder="Senha:">
                <button type="submit">Login</button>
            </form>
        </div>
    </section>
</body>
</html>
