    // Filtro de busca
    document.getElementById('inputBusca').addEventListener('input', function() {
        var filter = this.value.toLowerCase(); // Obtém o valor digitado no campo de busca
        var cards = document.querySelectorAll('.info-card'); // Pega todos os cards

        cards.forEach(function(card) {
            // Pega o texto de "Código" e "Cliente"
            var codigo = card.querySelector('.codigo').textContent.toLowerCase();
            var cliente = card.querySelector('.cliente').textContent.toLowerCase();

            // Se o código ou o nome do cliente contiverem o valor digitado, exibe o card, caso contrário, oculta
            if (codigo.includes(filter) || cliente.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });