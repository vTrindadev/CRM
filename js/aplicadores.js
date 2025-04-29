document.getElementById('tipoProposta').addEventListener('change', function () {
  updateAplicadores();
});

document.getElementById('emFabrica').addEventListener('change', function () {
  updateAplicadores();
});

function updateAplicadores() {
  const tipoProposta = document.getElementById('tipoProposta').value;
  const emFabrica = document.getElementById('emFabrica').value;

  const aplicadoresMap = {
    'Campo': 'lucaspaulo@weg.net, luisfranca@weg.net',
    'Fábrica': 'grahl@weg.net, luisgm@weg.net',
    'Partes e Peças': 'adrianad@weg.net, ullera@weg.net, gabrielfl@weg.net, cristianeaf@weg.net, diefanyg@weg.net'
  };

  const aplicador = document.getElementById('aplicador');

  // Verifica as condições: se tipoProposta for "Fábrica" e emFabrica for "Sim"
  if (emFabrica === 'Sim') {
    aplicador.value = 'jonas3@weg.net, pcampos@weg.net'; // Sobrescreve os aplicadores se emFabrica for "Sim"
  } else {
    aplicador.value = aplicadoresMap[tipoProposta] || ''; // Usa o map de acordo com o tipo de proposta
  }
}
