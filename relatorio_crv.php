<?php
// Conexão com o banco de dados
include('protection.php');
if (!isset($_SESSION['acesso'])) {
    echo "Sessão não iniciada ou variável 'acesso' não definida.";
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

// Conexão com o banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consultar dados para o gráfico de tendência de valor das propostas
$email_usuario = $_SESSION['email'];  // Pegando o email do usuário da sessão

$query8 = "
  SELECT DATE(criacao) as data, 
         SUM(CASE WHEN Status = 'Concluído' THEN valor ELSE 0 END) as valor_concluido,
         SUM(CASE WHEN Status != 'Concluído' THEN valor ELSE 0 END) as valor_criado
  FROM demandas 
  WHERE aplicador = '$email_usuario'
  GROUP BY DATE(criacao)
  ORDER BY data
";
$result8 = mysqli_query($conn, $query8);

$data_tendencia = [];
$valor_concluido = [];
$valor_criado = [];

while ($row = mysqli_fetch_assoc($result8)) {
    $data_tendencia[] = $row['criacao'];
    $valor_concluido[] = $row['valor'];
    $valor_criado[] = $row['valor_criado'];
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatórios CRM MEG</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/relatorio.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <a href="relatorio.php" class="btn-menu activo">
        <h3>Relatório</h3>
      </a>
    </div>
    <div class="opt-menu">
      <form action="logout.php" method="post">
        <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>


  <div class="container">    
    <!-- Gráfico de Tendência de Valor -->
    <div class="chart-container">
      <canvas id="myChart8"></canvas>
    </div>

  </div>

  <script>
   // Gráfico de Tendência de Valor
const ctx8 = document.getElementById('myChart8').getContext('2d');
const myChart8 = new Chart(ctx8, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($data_tendencia); ?>,
    datasets: [
      {
        label: 'Valor das Propostas Criadas',
        data: <?php echo json_encode($valor_criado); ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
        fill: false
      },
      {
        label: 'Valor das Propostas Concluídas',
        data: <?php echo json_encode($valor_concluido); ?>,
        backgroundColor: 'rgba(255, 99, 132, 0.6)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1,
        fill: false
      }
    ]
  },
  options: {
    plugins: {
      legend: { labels: { color: 'white' } }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: 'white' },
        grid: { color: 'rgba(255, 255, 255, 0.6)' }
      },
      x: {
        ticks: { color: 'white' },
        grid: { color: 'rgba(255, 255, 255, 0.6)' }
      }
    }
  }
});

  </script>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>
