document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Previne o envio do formulário

    // Obtém os valores inseridos pelo usuário
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === 'Admin' && password === '123') {
        // Define um item no localStorage para indicar que o usuário está logado
        localStorage.setItem('isLoggedIn', 'true');
        
        // Redireciona para a página de sucesso (pode substituir pelo URL real)
        window.location.href = 'home.php';
    } else if (username === 'Victorcp' && password === '123') {
        // Define um item no localStorage para indicar que o usuário está logado
        localStorage.setItem('isLoggedIn', 'true');
        
        // Redireciona para a página de sucesso (pode substituir pelo URL real)
        window.location.href = 'home.php';
    } else {
        // Mostra uma mensagem de erro
        alert('Usuário ou senha incorretos');
    }
});