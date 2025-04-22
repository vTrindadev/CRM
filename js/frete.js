function buscarCliente() {
const codigo = document.getElementById("codigoCliente").value;
if (codigo.trim() === "") return;

fetch(`get_cliente.php?codigo=${codigo}`)
    .then(res => res.json())
    .then(data => {
    if (data.erro) {
        alert("Cliente não encontrado!");
        return;
    }

    document.getElementById("cliente").value = data.Cliente || "";
    document.getElementById("nomeCliente").value = data.NomeCliente || "";
    document.getElementById("cnpj").value = data.Cnpj || "";
    document.getElementById("cidade").value = data.Cidade || "";
    document.getElementById("estado").value = data.Estado || "";
    document.getElementById("pais").value = data.Pais || "";
    })
    .catch(error => {
    console.error("Erro ao buscar cliente:", error);
    });
}

// Exibir/ocultar campo de frete com base no tipo de proposta
document.getElementById("tipoProposta").addEventListener("change", function () {
const valor = this.value;
const freteContainer = document.getElementById("freteContainer");

if (valor === "Fábrica" || valor === "Partes e Peças") {
    freteContainer.style.display = "block";
} else {
    freteContainer.style.display = "none";
    document.getElementById("frete").value = "";
}
});
