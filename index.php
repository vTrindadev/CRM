
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "crm meg";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        echo "<p style='color:red;'>Erro na conexão com o banco</p>";
        exit();
    }

    $username = $_POST['E-mail'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo "<script>
                    localStorage.setItem('isLoggedIn', 'true');
                    window.location.href = 'home.html';
                  </script>";
        } else {
            echo "<p style='color:red;'>Senha incorreta</p>";
        }
    } else {
        echo "<p style='color:red;'>Usuário não encontrado</p>";
    }

    $conn->close();
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
            <form action="" id="loginForm">
                <input type="text" id="username" name="username" required placeholder="Login:">
                <input type="password" id="password" name="password" required placeholder="Senha:">
                <button type="submit">Login</button>
            </form>
        </div>
    </section>
    <script src="js/login.js"></script>
</body>
</html>
