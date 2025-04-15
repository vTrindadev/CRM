const dadosCRV = [
    { id: "001", cliente: "Empresa XPTO", escopo: "Instalação de painéis", status: "Em análise" },
    { id: "002", cliente: "AlphaTech", escopo: "Manutenção preventiva", status: "Aprovado" },
    { id: "003", cliente: "Beta Solutions", escopo: "Retrofit de quadros", status: "Em cotação" },
    { id: "004", cliente: "Indústria Nova", escopo: "Projeto elétrico completo", status: "Pendente" },
    { id: "005", cliente: "Construtora Vale", escopo: "Ampliação de sistema", status: "Reprovado" },
    { id: "006", cliente: "EletroMaster", escopo: "Substituição de componentes", status: "Finalizado" },
    { id: "007", cliente: "Inova Engenharia", escopo: "Instalação de inversores", status: "Aguardando cliente" },
    { id: "008", cliente: "GigaPower", escopo: "Layout elétrico novo", status: "Em execução" },
    { id: "009", cliente: "Usina Solar BR", escopo: "Adequação NR-10", status: "Em análise" },
    { id: "010", cliente: "Metalúrgica Sul", escopo: "Modernização de painéis", status: "Aprovado" },
  ];

  const container = document.getElementById(".info-card");

  dadosCRV.forEach((item) => {
    const card = document.createElement("div");
    card.classList.add("info-card");
    card.innerHTML = `
      <div>
        <p><strong>ID:</strong> ${item.id}</p>
        <p><strong>Cliente:</strong> ${item.cliente}</p>
        <p><strong>Escopo:</strong> ${item.escopo}</p>
        <p><strong>Status:</strong> ${item.status}</p>
      </div>
      <div class="arrow-icon">➤</div>
    `;
    container.appendChild(card);
  });