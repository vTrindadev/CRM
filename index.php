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

// Variável para armazenar erro de login
$erro_login = "";

// Verifica se o formulário foi enviado
if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (strlen($_POST['email']) == 0) {
        $erro_login = "<p class='erro-login'>Preencha seu e-mail</p>";
    } else if (strlen($_POST['senha']) == 0) {
        $erro_login = "<p class='erro-login'>Preencha sua senha</p>";
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
            $_SESSION['acesso'] = $usuario['acesso'];  
            $_SESSION['filial'] = $usuario['filial'];  
            $_SESSION['codigo'] = $usuario['codigo']; 


            // Redireciona para a página inicial
            header("Location: home.php");
            exit;

        } else {
            $erro_login = "<p class='erro-login'>Falha ao logar! E-mail ou senha incorretos.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEG+LOGIN</title>

    <link rel="stylesheet" href="css/padrao.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section id="conteiner">
        <div id="holder"></div>
        <div id="box-form">
            <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
            <h2 id="titulo-form">Sign In</h2>

            <!-- Mensagem de erro (se houver) -->
            <?php if (!empty($erro_login)) echo $erro_login; ?>

            <!-- Formulário de login -->
            <form action="" method="POST" id="loginForm">
                <input type="text" id="username" name="email" required placeholder="E-mail:">
                <input type="password" id="password" name="senha" required placeholder="Senha:">
                <button type="submit" class="button button--stroke" data-block="button">
                    <span class="button__flair"></span>
                    <span class="button__label">Login</span>
                </button>
            </form>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-content">
            <img src="img/MEG+ (2).png" alt="Logo WEG" class="footer-logo">
            <p>&copy; 2025 MEG+. TODOS OS DIREITOS RESERVADOS.</p>
        </div>
    </footer>

    <script src="js/loader.js"></script>
    <script src="js/wave.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>        class Button {
        constructor(buttonElement) {
            this.block = buttonElement;
            this.init();
            this.initEvents();
        }

        init() {
            const el = gsap.utils.selector(this.block);
            this.DOM = {
            button: this.block,
            flair: el(".button__flair")
            };
            this.xSet = gsap.quickSetter(this.DOM.flair, "xPercent");
            this.ySet = gsap.quickSetter(this.DOM.flair, "yPercent");
        }

        getXY(e) {
            const { left, top, width, height } = this.DOM.button.getBoundingClientRect();
            const xTransformer = gsap.utils.pipe(
            gsap.utils.mapRange(0, width, 0, 100),
            gsap.utils.clamp(0, 100)
            );
            const yTransformer = gsap.utils.pipe(
            gsap.utils.mapRange(0, height, 0, 100),
            gsap.utils.clamp(0, 100)
            );
            return {
            x: xTransformer(e.clientX - left),
            y: yTransformer(e.clientY - top)
            };
        }

        initEvents() {
            this.DOM.button.addEventListener("mouseenter", (e) => {
            const { x, y } = this.getXY(e);
            this.xSet(x);
            this.ySet(y);
            gsap.to(this.DOM.flair, {
                scale: 1,
                duration: 0.8,
                ease: "power2.out"
            });
            });

            this.DOM.button.addEventListener("mouseleave", (e) => {
            const { x, y } = this.getXY(e);
            gsap.killTweensOf(this.DOM.flair);
            gsap.to(this.DOM.flair, {
                xPercent: x > 90 ? x + 20 : x < 10 ? x - 20 : x,
                yPercent: y > 90 ? y + 20 : y < 10 ? y - 20 : y,
                scale: 0,
                duration: 0.5,
                ease: "power2.out"
            });
            });

            this.DOM.button.addEventListener("mousemove", (e) => {
            const { x, y } = this.getXY(e);
            gsap.to(this.DOM.flair, {
                xPercent: x,
                yPercent: y,
                duration: 0.8,
                ease: "power2"
            });
            });
        }
        }

        document.querySelectorAll('[data-block="button"]').forEach((el) => new Button(el));
</script>
    

</body>
</html>
