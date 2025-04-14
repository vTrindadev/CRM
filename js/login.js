document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Previne o envio padrão do formulário

    const formData = new FormData(this); // Pega os dados do formulário

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Salva o estado de login localmente
            localStorage.setItem('isLoggedIn', 'true');

            // Redireciona para a página principal
            window.location.href = 'home.html';
        } else {
            // Mostra mensagem de erro
            alert(data.error || 'Erro desconhecido ao fazer login.');
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro na comunicação com o servidor.');
    });
});





// document.getElementById('loginForm').addEventListener('submit', function(event) {
//     event.preventDefault(); // Previne o envio do formulário

//     // Obtém os valores inseridos pelo usuário
//     const username = document.getElementById('username').value;
//     const password = document.getElementById('password').value;

//     if (username === 'Admin' && password === '123') {
//         // Define um item no localStorage para indicar que o usuário está logado
//         localStorage.setItem('isLoggedIn', 'true');
        
//         // Redireciona para a página de sucesso (pode substituir pelo URL real)
//         window.location.href = 'home.html';
//     } else if (username === 'Turtle' && password === '2024') {
//         // Define um item no localStorage para indicar que o usuário está logado
//         localStorage.setItem('isLoggedIn', 'true');
        
//         // Redireciona para a página de sucesso (pode substituir pelo URL real)
//         window.location.href = 'home.html';
//     } else {
//         // Mostra uma mensagem de erro
//         alert('Usuário ou senha incorretos');
//     }
// });
