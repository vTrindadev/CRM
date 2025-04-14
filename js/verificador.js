// Verifica se o usuário está logado
if (localStorage.getItem('isLoggedIn') !== 'true') {
    // Redireciona para a página de login se não estiver logado
    window.location.href = 'index.php';
}

// Adiciona um listener ao botão de logout
document.getElementById('logoutButton').addEventListener('click', function() {
    // Remove o item do localStorage
    localStorage.removeItem('isLoggedIn');
    // Redireciona para a página de login
    window.location.href = 'index.php';
});
