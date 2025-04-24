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

// Consultar dados para o gráfico de Tipo de Proposta
$query1 = "SELECT TipoProposta, COUNT(*) as total FROM demandas GROUP BY TipoProposta";
$result1 = mysqli_query($conn, $query1);

$tipoProposta = [];
$totalTipoProposta = [];
while ($row = mysqli_fetch_assoc($result1)) {
    $tipoProposta[] = $row['TipoProposta'];
    $totalTipoProposta[] = $row['total'];
}

// Consultar dados para o gráfico de Prioridade
$query2 = "SELECT Prioridade, COUNT(*) as total FROM demandas GROUP BY Prioridade";
$result2 = mysqli_query($conn, $query2);

$prioridade = [];
$totalPrioridade = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $prioridade[] = $row['Prioridade'];
    $totalPrioridade[] = $row['total'];
}

// Consultar dados para o gráfico de Status
$query3 = "SELECT Status, COUNT(*) as total FROM demandas GROUP BY Status";
$result3 = mysqli_query($conn, $query3);

$status = [];
$totalStatus = [];
while ($row = mysqli_fetch_assoc($result3)) {
    $status[] = $row['Status'];
    $totalStatus[] = $row['total'];
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
    <div id="holder"></div>
    <!-- Gráfico de Tipo de Proposta -->
    <div class="chart-container">
      <canvas id="myChart1"></canvas>
    </div>

    <!-- Gráfico de Prioridade -->
    <div class="chart-container">
      <canvas id="myChart2"></canvas>
    </div>

    <!-- Gráfico de Status -->
    <div class="chart-container">
      <canvas id="myChart3"></canvas>
    </div>
  </div>

  <script>
    // Gráfico de Tipo de Proposta
    const ctx1 = document.getElementById('myChart1').getContext('2d');
    const myChart1 = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($tipoProposta); ?>,
        datasets: [{
          label: 'Total de Demandas por Tipo de Proposta',
          data: <?php echo json_encode($totalTipoProposta); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: {
              color: 'white' // ou qualquer outra cor em hex, rgba, etc.
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: 'white'  // Cor dos valores no eixo Y
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas horizontais
            }
          },
          x: {
            ticks: {
              color: 'white'   // Cor dos valores no eixo X
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas verticais
            }
          }
        }
      }
    });

    // Gráfico de Prioridade
    const ctx2 = document.getElementById('myChart2').getContext('2d');
    const myChart2 = new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($prioridade); ?>,
        datasets: [{
          label: 'Total de Demandas por Prioridade',
          data: <?php echo json_encode($totalPrioridade); ?>,
          backgroundColor: 'rgba(255, 99, 132, 0.6)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: {
              color: 'white' // ou qualquer outra cor em hex, rgba, etc.
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: 'white'  // Cor dos valores no eixo Y
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas horizontais
            }
          },
          x: {
            ticks: {
              color: 'white'  // Cor dos valores no eixo X
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas verticais
            }
          }
        }
      }
    });

    // Gráfico de Status
    const ctx3 = document.getElementById('myChart3').getContext('2d');
    const myChart3 = new Chart(ctx3, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($status); ?>,
        datasets: [{
          label: 'Distribuição das Demandas por Status',
          data: <?php echo json_encode($totalStatus); ?>,
          backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)'],
          borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: 'white'
            }
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.label + ': ' + tooltipItem.raw + ' demandas';
              }
            }
          }
        }
      }
    });
  </script>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>
