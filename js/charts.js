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
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)' // Cor dos valores no eixo Y
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas horizontais
            }
          },
          x: {
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor dos valores no eixo X
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
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)' // Cor dos valores no eixo Y
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor das linhas horizontais
            }
          },
          x: {
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)'  // Cor dos valores no eixo X
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