const dadosCRV = [
    { id: "001", cliente: "Empresa XPTO", escopo: "Instalação de painéis", Status: "Em análise" },
    { id: "002", cliente: "AlphaTech", escopo: "Manutenção preventiva", Status: "Aprovado" },
    { id: "003", cliente: "Beta Solutions", escopo: "Retrofit de quadros", Status: "Em cotação" },
    { id: "004", cliente: "Indústria Nova", escopo: "Projeto elétrico completo", Status: "Pendente" },
    { id: "005", cliente: "Construtora Vale", escopo: "Ampliação de sistema", Status: "Reprovado" },
    { id: "006", cliente: "EletroMaster", escopo: "Substituição de componentes", Status: "Finalizado" },
    { id: "007", cliente: "Inova Engenharia", escopo: "Instalação de inversores", Status: "Aguardando cliente" },
    { id: "008", cliente: "GigaPower", escopo: "Layout elétrico novo", Status: "Em execução" },
    { id: "009", cliente: "Usina Solar BR", escopo: "Adequação NR-10", Status: "Em análise" },
    { id: "010", cliente: "Metalúrgica Sul", escopo: "Modernização de painéis", Status: "Aprovado" },
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
        <p><strong>Status:</strong> ${item.Status}</p>
      </div>
      <div class="arrow-icon">➤</div>
    `;
    container.appendChild(card);
  });