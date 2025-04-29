document.getElementById('tipoProposta').addEventListener('change', function () {
    const aplicadoresMap = {
      'Campo': 'lucaspaulo@weg.net, luisfranca@weg.net',
      'Fábrica': 'grahl@weg.net, jonas3, luisgm, pcampos',
      'Partes e Peças': 'adrianad@weg.net, ullera@weg.net, gabrielfl@weg.net, cristianeaf@weg.net, diefanyg@weg.net'
    };

    const aplicador = document.getElementById('aplicador');
    aplicador.value = aplicadoresMap[this.value] || '';
  });