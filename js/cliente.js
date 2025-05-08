function buscarCliente() {
  var codigoCliente = document.getElementById('codigoCliente').value;

  if (codigoCliente.length > 0) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'add_crv.php?codigoCliente=' + codigoCliente, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);

        if (response.error) {
          alert(response.error);
        } else {
          document.getElementById('cliente').value = response.Cliente;
          document.getElementById('cnpj').value = response.Cnpj;
          document.getElementById('cidade').value = response.Cidade;
          document.getElementById('estado').value = response.Estado;
          document.getElementById('pais').value = response.País;
        }
      }
    };
    xhr.send();
  }
}


