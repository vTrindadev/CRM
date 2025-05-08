<?php
include('protection.php');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crm meg";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$edicao = false;
$dados = [];

// Verifica se o parâmetro 'id' foi passado
if (isset($_GET['id'])) {
    $edicao = true;
    $id = $_GET['id'];
    $resultado = $conn->query("SELECT * FROM demandas WHERE id = $id");
    if ($resultado->num_rows > 0) {
        $dados = $resultado->fetch_assoc();
    } else {
        echo "<script>alert('Demanda não encontrada.'); window.location.href = 'Apl_Proposta.php';</script>";
        exit;
    }
} else {
    // Se não tiver 'id', redireciona para outra página, já que é somente edição
    echo "<script>window.location.href = 'Apl_Proposta.php';</script>";
    exit;
}

// Se o formulário for enviado via POST, realiza o UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nota = $_POST['nota'];
    $cotacao = $_POST['cotacao'];
    $crv = $_POST['crv'];
    $status_aplicador = $_POST['status_aplicador'];
    $Status = $_POST['Status'];
    $aplicador = $_POST['aplicador'];
    $valor = $_POST['valor'];
    $prazoProposta = $_POST['prazoProposta'];
    $prioridade = $_POST['prioridade'];
    $tipoProposta = $_POST['tipoProposta'];
    $escopo = $_POST['escopo'];
    $frete = $_POST['frete'];
    $refCliente = $_POST['refCliente'];
    $especificacaoCliente = $_POST['especificacaoCliente'];
    $emFabrica = $_POST['emFabrica'];
    $quantidadeEquip = $_POST['quantidadeEquip'];
    $equipamentos = $_POST['equipamentos'];
    $observacao = $_POST['observacao'];
    $filial = $_POST['filial'];
    $feedback_crv = $_POST['feedback_crv'];
    $feedback_aplicador = $_POST['feedback_aplicador'];
    // Atualiza os dados no banco
    $sql = "UPDATE demandas SET 
                Nota = '$nota', 
                Cotacao = '$cotacao', 
                crv = '$crv', 
                status_aplicador = '$status_aplicador', 
                Status = '$Status', 
                Aplicador = '$aplicador', 
                Valor = '$valor', 
                PrazoProposta = '$prazoProposta', 
                Prioridade = '$prioridade', 
                TipoProposta = '$tipoProposta', 
                Escopo = '$escopo', 
                Frete = '$frete', 
                RefCliente = '$refCliente', 
                EspecificacaoCliente = '$especificacaoCliente', 
                Emfabrica = '$emFabrica', 
                QuantidadeEquip = '$quantidadeEquip', 
                Equipamentos = '$equipamentos', 
                Observacao = '$observacao',
                filial = '$filial',
                feedback_crv = '$feedback_crv', 
                feedback_aplicador = '$feedback_aplicador'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Demanda atualizada com sucesso!'); window.location.href = 'Apl_Proposta.php?id=$id';</script>";
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM CRV - <?= $edicao ? 'Editar' : 'Criar' ?> Demanda</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/detalhe.css">
</head>
<body>
  <div id="loader"><div class="spinner"></div></div>
  <div id="menu">
  <a href="home.php">
      <img id="Logo" src="img/weg branco.png" alt="Logo WEG">
  </a>
  <div class="opt-menu">
      <a href="javascript:history.back()" class="btn-menu">
          <h3>Voltar</h3>
      </a>
      <a href="detalhes.php" class="btn-menu activo"><h3>Editar</h3></a> <!-- Aqui a opção será sempre 'Editar' -->
      <input type="text" id="inputBusca" placeholder="Buscar..." class="input-menu">
      <a href="BD_Cliente.php" class="btn-menu"><h3>Clientes</h3></a>
      <a href="BD_Equipamentos.php" class="btn-menu"><h3>Equipamentos</h3></a>
  </div>
  <div class="opt-menu">
    <form action="logout.php" method="post">
        <button type="submit" class="btn-menu-sair">Sair</button>
    </form>
  </div>
</div>
  <div class="container">
    
    <form id="detalhesForm" method="POST">
      <?php function valor($chave, $dados) { return isset($dados[$chave]) ? htmlspecialchars($dados[$chave]) : ''; } ?>

      <!-- Identificação -->
      <div class="form-section">
        <div class="form-section-title">Identificação</div>
        <div class="form-group"><label for="id">ID:</label><input type="text" id="id" name="id" value="<?= valor('id', $dados) ?>"readonly></div>
        <div class="form-group"><label for="nota">Nota:</label><input type="text" id="nota" name="nota" value="<?= valor('Nota', $dados) ?>"></div>
        <div class="form-group"><label for="cotacao">Cotação:</label><input type="text" id="cotacao" name="cotacao" value="<?= valor('Cotacao', $dados) ?>"></div>
        <div class="form-group"><label for="crv">CRV:</label><input type="text" id="crv" name="crv" value="<?= valor('crv', $dados) ?>"readonly></div>
      </div>

      <!-- Cliente -->
      <div class="form-section">
        <div class="form-section-title">Cliente</div>
        <div class="form-group"><label for="cliente">Cliente:</label><input type="text" id="cliente" name="cliente" value="<?= valor('Cliente', $dados) ?>"readonly required></div>
        <div class="form-group"><label for="codigoCliente">Código Cliente:</label><input type="text" id="codigoCliente" name="codigoCliente" value="<?= valor('CodigoCliente', $dados) ?>"readonly required></div>
        <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" name="cnpj" value="<?= valor('Cnpj', $dados) ?>"readonly required></div>
        <div class="form-group"><label for="cidade">Cidade:</label><input type="text" id="cidade" name="cidade" value="<?= valor('Cidade', $dados) ?>"readonly required></div>
        <div class="form-group"><label for="estado">Estado:</label><input type="text" id="estado" name="estado" value="<?= valor('Estado', $dados) ?>"readonly required></div>
        <div class="form-group"><label for="pais">País:</label><input type="text" id="pais" name="pais" value="<?= valor('Pais', $dados) ?>"readonly required></div>
      </div>

      <!-- Proposta -->
      <div class="form-section">
        <div class="form-section-title">Proposta</div>
        <div class="form-group">
          <label for="status_aplicador">Status Aplicador:</label>
          <select id="status_aplicador" name="status_aplicador" required>
            <?php
              $status = valor('Status', $dados); // ajuste conforme necessário

              $StatusAplicadorList = [
                'Distribuir',
                'Nova Solicitação',
                'Em Peritagem',
                'Proposta Concluída',
                'Informação Pendente',
                'Perdido',
                'Em Consulta'
              ];

              if ($status === "Proposta em Revisão") {
                $StatusAplicadorList = ["Revisar Proposta", "Proposta Concluída", "Informação Pendente", "Perdido", "Em Consulta"];
              }

              if ($status === "Em Negociação") {
                $StatusAplicadorList = ["Em Implantação", "Implantado", "Implantado - Em peritagem/Revisão", "Implantado - Proposta Revisada", "Implantado - Em Negociação de aditivo", "Implantado - Em Ajuste de Pedências", "Implantado e Liberado", "Implantado e Liberado c/ Pendências", "Devolvido para Revisão do Pedido"];
              }

              foreach ($StatusAplicadorList as $Status) {
                $selected = valor('status_aplicador', $dados) == $Status ? 'selected' : '';
                echo "<option value='$Status' $selected>$Status</option>";
              }
            ?>
          </select>
        </div>


        <div class="form-group"><label for="aplicador">Aplicador:</label><input type="text" id="aplicador" name="aplicador" value="<?= valor('aplicador', $dados) ?>"></div>
        <div class="form-group"><label for="Status">Status:</label><input type="text" id="Status" name="Status" value="<?= valor('Status', $dados) ?>" readonly></div>
        <div class="form-group"><label for="valor">Valor:</label><input type="text" id="valor" name="valor" value="<?= valor('valor', $dados) ?>"></div>
        <div class="form-group"><label for="prazoProposta">Prazo Proposta:</label><input type="date" id="prazoProposta" name="prazoProposta" value="<?= valor('PrazoProposta', $dados) ?>" required></div>
        <div class="form-group">
          <label for="prioridade">Prioridade:</label>
          <select id="prioridade" name="prioridade" required>
            <?php
              $prioridades = ["Máquina Parada", "Urgente", "Normal", "Estimativa"];
              foreach ($prioridades as $pri) {
                $selected = valor('Prioridade', $dados) == $pri ? 'selected' : '';
                echo "<option value='$pri' $selected>$pri</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tipoProposta">Tipo Proposta:</label>
          <select id="tipoProposta" name="tipoProposta" required>
            <?php
              $tipos = ["Campo", "Fábrica", "Partes e Peças"];
              foreach ($tipos as $tipo) {
                $selected = valor('TipoProposta', $dados) == $tipo ? 'selected' : '';
                echo "<option value='$tipo' $selected>$tipo</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group"><label for="escopo">Escopo:</label><textarea id="escopo" name="escopo" required><?= valor('Escopo', $dados) ?></textarea></div>
        <div class="form-group" id="freteContainer" style="<?= in_array(valor('TipoProposta', $dados), ['Fábrica', 'Partes e Peças']) ? '' : 'display: none;' ?>">
          <label for="frete">Frete:</label>
          <select id="frete" name="frete">
            <option value="">Selecione</option>
            <option value="Por conta do Cliente" <?= valor('frete', $dados) == 'Por conta do Cliente' ? 'selected' : '' ?>>Por conta do Cliente</option>
            <option value="Por conta da WEG" <?= valor('frete', $dados) == 'Por conta da WEG' ? 'selected' : '' ?>>Por conta da WEG</option>
          </select>
        </div>
      </div>

      <!-- Comercial & Técnica -->
      <div class="form-section">
        <div class="form-section-title">Comercial & Técnica</div>
        <div class="form-group"><label for="refCliente">Ref Cliente:</label><input type="text" id="refCliente" name="refCliente" value="<?= valor('refCliente', $dados) ?>" required></div>
        <div class="form-group"><label for="especificacaoCliente">Especificação Cliente:</label><input type="text" id="especificacaoCliente" name="especificacaoCliente" value="<?= valor('EspecificacaoCliente', $dados) ?>" required></div>
        <div class="form-group">
          <label for="emFabrica">Em Fábrica:</label>
          <select id="emFabrica" name="emFabrica" required>
            <option value="Não" <?= valor('Emfabrica', $dados) == 'Não' ? 'selected' : '' ?>>Não</option>
            <option value="Sim" <?= valor('Emfabrica', $dados) == 'Sim' ? 'selected' : '' ?>>Sim</option>
          </select>
        </div>
        <div class="form-group"><label for="quantidadeEquip">Quantidade Equip:</label><input type="text" id="quantidadeEquip" name="quantidadeEquip" value="<?= valor('QuantidadeEquip', $dados) ?>" required></div>
        <div class="form-group"><label for="equipamentos">Equipamentos:</label><input type="text" id="equipamentos" name="equipamentos" value="<?= valor('Equipamentos', $dados) ?>" required></div>
        <div class="form-group"><label for="observacao">Observação:</label><textarea id="observacao" name="observacao" required><?= valor('Observacao', $dados) ?></textarea></div>
      </div>

      <!-- Comentários -->
      <div class="form-section">
        <div class="form-section-title">FeedBack CRV - Aplicador</div>
        <div class="form-group"><label for="feedback_crv">CRV:</label><textarea id="observacao" name="feedback_crv" ><?= valor('feedback_crv', $dados) ?></textarea></div>
        <div class="form-group"><label for="feedback_aplicador">Aplicador:</label><textarea id="observacao" name="feedback_aplicador" ><?= valor('feedback_aplicador', $dados) ?></textarea></div>
      </div>

      <div class="form-group">
      <button type="submit">Salvar Alterações</button>
      </div>
    </form>
  </div>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/frete.js"></script>
  <script>
    document.getElementById('status_aplicador')?.addEventListener('change', function () {
      const Status = document.getElementById('Status');
      if (!Status) return;

      const aplicadorValue = this.value;

      // Define as regras de correspondência
      if (aplicadorValue === 'Proposta Concluída') {
        Status.value = 'Proposta Concluída';
      } else if (aplicadorValue === 'Informação Pendente') {
        Status.value = 'Informação Pendente';
      } else if (aplicadorValue === 'Perdido') {
        Status.value = 'Perdido';
      } else if (aplicadorValue === 'Distribuir') {
        Status.value = 'Proposta em Elaboração';
      } else if (aplicadorValue === 'Nova Solicitação') {
        Status.value = 'Proposta em Elaboração';
      } else if (aplicadorValue === 'Em Peritagem') {
        Status.value = 'Proposta em Elaboração';
      } else if (aplicadorValue === 'Em Consulta') {
        Status.value = 'Proposta em Elaboração';
      } else if (aplicadorValue === 'Revisar Proposta') {
        Status.value = 'Proposta em Revisão';
      } else if (aplicadorValue === 'Implantado') {
        Status.value = 'Implantado';
      } else if (aplicadorValue === 'Implantado - Em peritagem/Revisão') {
        Status.value = 'Implantado';
      } else if (aplicadorValue === 'Implantado - Proposta Revisada') {
        Status.value = 'Implantado - Em peritagem/Revisão';
      } else if (aplicadorValue === 'Implantado - Em Negociação de aditivo') {
        Status.value = 'Implantado - Em Negociação de aditivo';
      } else if (aplicadorValue === 'Implantado - Em Ajuste de Pedências') {
        Status.value = 'Implantado - Em Ajuste de Pedências';
      } else if (aplicadorValue === 'Implantado e Liberado') {
        Status.value = 'Implantado e Liberado';
      } else if (aplicadorValue === 'Implantado e Liberado c/ Pendências') {
        Status.value = 'Implantado e Liberado c/ Pendências';
      } else if (aplicadorValue === 'Devolvido para Revisão do Pedido') {
        Status.value = 'Devolvido para Revisão do Pedido';
      } else {
        Status.value = ''; // ou mantenha o valor atual se quiser evitar alteração
      }
    });
  </script>
</body>
</html>
