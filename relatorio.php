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

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Filtros
$crv_filter = $_GET['crv'] ?? '';
$aplicador_filter = $_GET['aplicador'] ?? '';

// === Tipo de Proposta ===
$query1 = "SELECT TipoProposta, COUNT(*) as total FROM demandas WHERE 1";
if ($crv_filter) $query1 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query1 .= " AND aplicador = '$aplicador_filter'";
$query1 .= " GROUP BY TipoProposta";
$result1 = mysqli_query($conn, $query1);

$tipoProposta = $totalTipoProposta = [];
while ($row = mysqli_fetch_assoc($result1)) {
    $tipoProposta[] = $row['TipoProposta'];
    $totalTipoProposta[] = $row['total'];
}

// === Prioridade ===
$query2 = "SELECT Prioridade, COUNT(*) as total FROM demandas WHERE 1";
if ($crv_filter) $query2 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query2 .= " AND aplicador = '$aplicador_filter'";
$query2 .= " GROUP BY Prioridade";
$result2 = mysqli_query($conn, $query2);

$prioridade = $totalPrioridade = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $prioridade[] = $row['Prioridade'];
    $totalPrioridade[] = $row['total'];
}

// === Status ===
$query3 = "SELECT Status, COUNT(*) as total FROM demandas WHERE 1";
if ($crv_filter) $query3 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query3 .= " AND aplicador = '$aplicador_filter'";
$query3 .= " GROUP BY Status";
$result3 = mysqli_query($conn, $query3);

$status = $totalStatus = [];
while ($row = mysqli_fetch_assoc($result3)) {
    $status[] = $row['Status'];
    $totalStatus[] = $row['total'];
}

// === Ranking de CRVs ===
$query6 = "SELECT crv, COUNT(*) as total FROM demandas WHERE 1";
if ($crv_filter) $query6 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query6 .= " AND aplicador = '$aplicador_filter'";
$query6 .= " GROUP BY crv ORDER BY total DESC";
$result6 = mysqli_query($conn, $query6);

$crvs = $totalCrvs = [];
while ($row = mysqli_fetch_assoc($result6)) {
    $crvs[] = $row['crv'];
    $totalCrvs[] = $row['total'];
}

// === Demandas por Estado ===
$query7 = "SELECT Estado, COUNT(*) as total FROM demandas WHERE 1";
if ($crv_filter) $query7 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query7 .= " AND aplicador = '$aplicador_filter'";
$query7 .= " GROUP BY Estado";
$result7 = mysqli_query($conn, $query7);

$estados = $totalEstados = [];
while ($row = mysqli_fetch_assoc($result7)) {
    $estados[] = $row['Estado'];
    $totalEstados[] = $row['total'];
}

// === Tendência de Valor das Propostas ===
$query8 = "
    SELECT DATE_FORMAT(criacao, '%Y-%m') as mes, 
           SUM(CASE WHEN Status = 'Proposta Concluída' THEN valor ELSE 0 END) as valor_concluido,
           SUM(CASE WHEN Status != 'Proposta Concluída' THEN valor ELSE 0 END) as valor_criado
    FROM demandas 
    WHERE 1
    GROUP BY mes
    ORDER BY mes
";
$result8 = mysqli_query($conn, $query8);

$data_tendencia = $valor_concluido = $valor_criado = [];
while ($row = mysqli_fetch_assoc($result8)) {
    $data_tendencia[] = $row['mes'];
    $valor_concluido[] = (float)$row['valor_concluido'];
    $valor_criado[] = (float)$row['valor_criado'];
}

// === Demandas Fora do Prazo ===
$query9 = "
    SELECT 
        TipoProposta, 
        COUNT(*) AS total_fora_do_prazo 
    FROM demandas 
    WHERE TIMESTAMPDIFF(HOUR, PrazoProposta, NOW()) > 24  -- Alterando para 24 horas ou o tempo que deseja
";

if ($crv_filter) $query9 .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $query9 .= " AND aplicador = '$aplicador_filter'";

$query9 .= " GROUP BY TipoProposta";
$result9 = mysqli_query($conn, $query9);

$tipoForaPrazo = $totalForaPrazo = [];
while ($row = mysqli_fetch_assoc($result9)) {
    $tipoForaPrazo[] = $row['TipoProposta'];
    $totalForaPrazo[] = $row['total_fora_do_prazo'];
}

// Por MÊS
$queryMes = "
    SELECT DATE_FORMAT(criacao, '%Y-%m') as periodo, COUNT(*) as total
    FROM demandas
    WHERE 1
";
if ($crv_filter) $queryMes .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $queryMes .= " AND aplicador = '$aplicador_filter'";
$queryMes .= " GROUP BY periodo ORDER BY periodo";
$resultMes = mysqli_query($conn, $queryMes);

$labelsMes = $dadosMes = [];
while ($row = mysqli_fetch_assoc($resultMes)) {
    $labelsMes[] = $row['periodo'];
    $dadosMes[] = (int)$row['total'];
}

// Por SEMANA
$querySemana = "
    SELECT DATE_FORMAT(criacao, '%Y-%u') as periodo, COUNT(*) as total
    FROM demandas
    WHERE 1
";
if ($crv_filter) $querySemana .= " AND crv = '$crv_filter'";
if ($aplicador_filter) $querySemana .= " AND aplicador = '$aplicador_filter'";
$querySemana .= " GROUP BY periodo ORDER BY periodo";
$resultSemana = mysqli_query($conn, $querySemana);

$labelsSemana = $dadosSemana = [];
while ($row = mysqli_fetch_assoc($resultSemana)) {
    $labelsSemana[] = $row['periodo'];
    $dadosSemana[] = (int)$row['total'];
}

// Contagem total de demandas criadas (com os mesmos filtros aplicados)
$countQuery = "SELECT COUNT(*) AS total_demandas FROM demandas WHERE 1=1";

if ($crv_filter) {
    $countQuery .= " AND crv = '$crv_filter'";
}

if ($aplicador_filter) {
    $countQuery .= " AND aplicador = '$aplicador_filter'";
}

$countResult = $conn->query($countQuery);
$totalDemandas = $countResult->fetch_assoc()['total_demandas'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+RELATORIO</title>
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
      <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
    </a>
    <div class="opt-menu">  
      <a href="relatorio.php" class="btn-menu activo">
        <h3>Dashboard</h3>
      </a>
      <a href="all.php" class="btn-menu">
        <h3>All</h3>
      </a>
    </div>
    <div class="opt-menu">
      <form action="logout.php" method="post">
        <button type="submit" class="btn-menu-sair">Sair</button>
      </form>
    </div>
  </div>


  <div class="container">    
    <div class="filters">
    <form method="GET" action="relatorio.php">
      <label for="crv">CRV:</label>
      <select name="crv" id="crv">
        <option value="">Todos</option>
        <option value="anan@weg.net" <?php if ($crv_filter == 'anan@weg.net') echo 'selected'; ?>>Ana Paula Nolasco</option>
        <option value="abaptista@weg.net" <?php if ($crv_filter == 'abaptista@weg.net') echo 'selected'; ?>>Andre Luis Rosa Baptista</option>
        <option value="diegolc@weg.net" <?php if ($crv_filter == 'diegolc@weg.net') echo 'selected'; ?>>Diego Lopes Caobianco</option>
        <option value="guilhermehk@weg.net" <?php if ($crv_filter == 'guilhermehk@weg.net') echo 'selected'; ?>>Guilherme Henrique Khun</option>
        <option value="freiriag@weg.net" <?php if ($crv_filter == 'freiriag@weg.net') echo 'selected'; ?>>Joao Pedro Freiria Schlichting</option>
        <option value="mvitor@weg.net" <?php if ($crv_filter == 'mvitor@weg.net') echo 'selected'; ?>>Joao Vitor Machado Mariquito</option>
        <option value="rsilva@weg.net" <?php if ($crv_filter == 'rsilva@weg.net') echo 'selected'; ?>>Ricardo Goncalves da Silva</option>
        <option value="guareschi@weg.net" <?php if ($crv_filter == 'guareschi@weg.net') echo 'selected'; ?>>Rodrigo Guareschi</option>
      </select>

      <label for="aplicador">Aplicador:</label>
      <select name="aplicador" id="aplicador">
        <option value="">Todos</option>
        <option value="cristianeaf@weg.net" <?php if ($aplicador_filter == 'cristianeaf@weg.net') echo 'selected'; ?>>Cristiane Aline Fachini</option>
        <option value="jonas3@weg.net" <?php if ($aplicador_filter == 'jonas3@weg.net') echo 'selected'; ?>>Jonas Cesar Figueiredo</option>
        <option value="adrianad@weg.net" <?php if ($aplicador_filter == 'adrianad@weg.net') echo 'selected'; ?>>Adriana Dutra</option>
        <option value="ullera@weg.net" <?php if ($aplicador_filter == 'ullera@weg.net') echo 'selected'; ?>>Andre Luis Uller Costa</option>
        <option value="grahl@weg.net" <?php if ($aplicador_filter == 'grahl@weg.net') echo 'selected'; ?>>Diogo Mauri Grahl</option>
        <option value="gabrielfl@weg.net" <?php if ($aplicador_filter == 'gabrielfl@weg.net') echo 'selected'; ?>>Gabriel Felipe Lopes</option>
        <option value="lucaspaulo@weg.net" <?php if ($aplicador_filter == 'lucaspaulo@weg.net') echo 'selected'; ?>>Lucas Paulo Oliveira</option>
        <option value="luisgm@weg.net" <?php if ($aplicador_filter == 'luisgm@weg.net') echo 'selected'; ?>>Luis Gustavo Machuno</option>
        <option value="luisfranca@weg.net" <?php if ($aplicador_filter == 'luisfranca@weg.net') echo 'selected'; ?>>Luis Gustavo Franca</option>
        <option value="pcampos@weg.net" <?php if ($aplicador_filter == 'pcampos@weg.net') echo 'selected'; ?>>Pedro Orlando Campos</option>
      </select>

      <button type="submit">Filtrar</button>
    </form>
  </div>
    <div class="count-card">
      <h2><?php echo $totalDemandas; ?></h2>
      <p>Total de Demandas Criadas</p>
    </div>
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
    
    <!-- Gráfico de Ranking de CRVs com mais demandas -->
    <div class="chart-container">
      <canvas id="myChart6"></canvas>
    </div>

    <!-- Gráfico de Demandas por Estado -->
    <div class="chart-container">
      <canvas id="myChart7"></canvas>
    </div>

    <!-- Gráfico de Tendência -->
    <div class="chart-container">
      <canvas id="myChart8"></canvas>
    </div>
    <!-- Gráfico de Demandas Fora do Prazo -->
    <div class="chart-container">
      <canvas id="myChart9"></canvas>
    </div>
    <!-- Gráfico de Evolução -->
    <div class="chart-container">
      <div class="chart-header">
        <select id="periodoSelect">
          <option value="mensal">Mensal</option>
          <option value="semanal">Semanal</option>
        </select>
      </div>
      <canvas id="graficoEvolucao"></canvas>
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
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Demandas por tipo de proposta', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Demandas por Prioridade', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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
          legend: { position: 'top', labels: { color: 'white' } },
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


    // Gráfico de Ranking de CRVs com mais demandas
    const ctx6 = document.getElementById('myChart6').getContext('2d');
    const myChart6 = new Chart(ctx6, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($crvs); ?>,
        datasets: [{
          label: 'Demandas por CRV (Ranking)',
          data: <?php echo json_encode($totalCrvs); ?>,
          backgroundColor: 'rgba(255, 206, 86, 0.6)',
          borderColor: 'rgba(255, 206, 86, 1)',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Quantidade de Demandas por CRV', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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

    // Gráfico de Demandas por Estado
    const ctx7 = document.getElementById('myChart7').getContext('2d');
    const myChart7 = new Chart(ctx7, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($estados); ?>,
        datasets: [{
          label: 'Total de Demandas por Estado',
          data: <?php echo json_encode($totalEstados); ?>,
          backgroundColor: 'rgba(255, 159, 64, 0.6)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Demandas por Estado', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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

    
    // Gráfico Tendência
    const ctx8 = document.getElementById('myChart8').getContext('2d');
    const myChart8 = new Chart(ctx8, {
      type: 'bar', // Altera o tipo de gráfico de 'line' para 'bar'
      data: {
        labels: <?php echo json_encode($data_tendencia); ?>, // Exibe as datas agrupadas por mês
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
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Demandas Faturadas', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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

  // Gráfico de Demandas Fora do Prazo
  const ctx9 = document.getElementById('myChart9').getContext('2d');
  const myChart9 = new Chart(ctx9, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($tipoForaPrazo); ?>, // Tipos de Proposta
      datasets: [{
        label: 'Demandas Fora do Prazo',
        data: <?php echo json_encode($totalForaPrazo); ?>, // Número de demandas fora do prazo
        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Cor de fundo
        borderColor: 'rgba(255, 99, 132, 1)', // Cor da borda
        borderWidth: 1
      }]
    },
    options: {
      plugins: {
          legend: { labels: { color: 'white' } },
            title: {
              display: true,
              text: 'Demandas Fora do Prazo', // Título do gráfico
              color: 'white',
              font: {
                size: 18
              }
            }
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
  // Gráfico de Evolução
  const ctxEvolucao = document.getElementById('graficoEvolucao').getContext('2d');

  const dadosMensal = {
    labels: <?php echo json_encode($labelsMes); ?>,
    data: <?php echo json_encode($dadosMes); ?>
  };

  const dadosSemanal = {
    labels: <?php echo json_encode($labelsSemana); ?>,
    data: <?php echo json_encode($dadosSemana); ?>
  };

  let chartEvolucao = new Chart(ctxEvolucao, {
    type: 'line',
    data: {
      labels: dadosMensal.labels,
      datasets: [{
        label: 'Demandas por período',
        data: dadosMensal.data,
        backgroundColor: 'rgba(75, 192, 192, 0.4)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      plugins: {
        legend: { labels: { color: 'white' } },
        title: {
          display: true,
          text: 'Evolução de Demandas',
          color: 'white',
          font: { size: 18 }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: 'white' },
          grid: { color: 'rgba(255,255,255,0.3)' }
        },
        x: {
          ticks: { color: 'white' },
          grid: { color: 'rgba(255,255,255,0.3)' }
        }
      }
    }
  });

  document.getElementById('periodoSelect').addEventListener('change', function () {
    const tipo = this.value;
    const novosDados = tipo === 'mensal' ? dadosMensal : dadosSemanal;
    chartEvolucao.data.labels = novosDados.labels;
    chartEvolucao.data.datasets[0].data = novosDados.data;
    chartEvolucao.update();
  });

  </script>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
</body>
</html>
