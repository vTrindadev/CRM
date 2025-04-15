document.addEventListener("DOMContentLoaded", function () {
    const inputBusca = document.getElementById("inputBusca");
    const cards = document.querySelectorAll(".info-card");

    inputBusca.addEventListener("input", function () {
        const filtro = inputBusca.value.toLowerCase();

        cards.forEach(card => {
            const texto = card.getAttribute("data-busca");
            if (texto.includes(filtro)) {
                card.style.display = "flex"; // ou "block", dependendo do seu layout
            } else {
                card.style.display = "none";
            }
        });
    });
});