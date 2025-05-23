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

if (isset($_GET['id'])) {
    $edicao = true;
    $id = $_GET['id'];
    $resultado = $conn->query("SELECT * FROM demandas WHERE id = $id");
    if ($resultado->num_rows > 0) {
        $dados = $resultado->fetch_assoc();
    } else {
        echo "<script>alert('Demanda não encontrada.'); window.location.href = 'CRV.php';</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $campos = ['nota','crv','cliente','codigoCliente','cnpj','cidade','estado','pais','escopo','Status','cotacao','prazoProposta','prioridade','tipoProposta','refCliente','especificacaoCliente','emFabrica','quantidadeEquip','equipamentos','observacao','valor','frete','status_aplicador','aplicador', 'filial', 'feedback_crv', 'feedback_aplicador'];
    foreach ($campos as $campo) {
        $$campo = isset($_POST[$campo]) ? $conn->real_escape_string($_POST[$campo]) : null;
    }

    if ($edicao) {
        $updateSql = "UPDATE demandas SET 
            Nota='$nota', crv='$crv', Cliente='$cliente', CodigoCliente='$codigoCliente', 
            Cnpj='$cnpj', Cidade='$cidade', Estado='$estado', Pais='$pais', Escopo='$escopo', Status='$Status', 
            Cotacao='$cotacao', PrazoProposta='$prazoProposta', Prioridade='$prioridade', TipoProposta='$tipoProposta', 
            refCliente='$refCliente', EspecificacaoCliente='$especificacaoCliente', Emfabrica='$emFabrica', 
            QuantidadeEquip='$quantidadeEquip', Equipamentos='$equipamentos', Observacao='$observacao', 
            valor='$valor', frete='$frete', status_aplicador='$status_aplicador', aplicador='$aplicador', filial='$filial', feedback_crv='$feedback_crv', feedback_aplicador='$feedback_aplicador'
            WHERE id=$id";

        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('Demanda atualizada com sucesso! ID da demanda:$id'); window.location.href = 'CRV.php';</script>";
        } else {
            echo "Erro ao atualizar a demanda: " . $conn->error;
        }
    } else {
        $insertSql = "INSERT INTO demandas (Nota, crv, Cliente, CodigoCliente, Cnpj, Cidade, Estado, Pais, Escopo, Status, Cotacao, PrazoProposta, Prioridade, TipoProposta, refCliente, EspecificacaoCliente, Emfabrica, QuantidadeEquip, Equipamentos, Observacao, valor, frete, status_aplicador, aplicador, filial, feedback_crv, feedback_aplicador)
                      VALUES ('$nota', '$crv', '$cliente', '$codigoCliente', '$nomeCliente', '$cnpj', '$cidade', '$estado', '$pais', '$escopo', '$Status', '$cotacao', '$prazoProposta', '$prioridade', '$tipoProposta', '$refCliente', '$especificacaoCliente', '$emFabrica', '$quantidadeEquip', '$equipamentos', '$observacao', '$valor', '$frete', '$status_aplicador', '$aplicador', '$filial', '$feedback_crv', '$feedback_aplicador')";

        if ($conn->query($insertSql) === TRUE) {
            echo "<script>alert('Demanda criada com sucesso! ID da demanda:$id'); window.location.href = 'CRV.php';</script>";
        } else {
            echo "Erro ao criar a demanda: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>MEG+CRVDET</title>
  <link rel="stylesheet" href="css/padrao.css">
  <link rel="stylesheet" href="css/detalhe.css">
</head>
<body>
  <div id="loader"><div class="spinner"></div></div>
  <div id="menu">
    <a href="home.php">
        <img id="Logo" src="img/MEG+ (2).png" alt="Logo WEG">
    </a>
    <div class="opt-menu">
        <a href="javascript:history.back()" class="btn-menu">
            <h3>Voltar</h3>
        </a>
      <a href="detalhes_crv.php" class="btn-menu activo"><h3>Editar</h3></a>
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
        <div class="form-group"><label for="id">ID:</label><input type="text" id="id" name="id" value="<?= valor('id', $dados) ?>"></div>
        <div class="form-group"><label for="nota">Nota:</label><input type="text" id="nota" name="nota" value="<?= valor('Nota', $dados) ?>"></div>
        <div class="form-group"><label for="cotacao">Cotação:</label><input type="text" id="cotacao" name="cotacao" value="<?= valor('Cotacao', $dados) ?>"></div>
        <div class="form-group">
          <label for="crv">CRV:</label>
          <select id="crv" name="crv" required>
            <option value="">Selecione</option>
            <?php
            $crvs = [
              "anan@weg.net" => "Ana Paula Nolasco",
              "abaptista@weg.net" => "Andre Luis Rosa Baptista",
              "diegolc@weg.net" => "Diego Lopes Caobianco",
              "guilhermehk@weg.net" => "Guilherme Henrique Khun",
              "freiriag@weg.net" => "Joao Pedro Freiria Schlichting",
              "mvitor@weg.net" => "Joao Vitor Machado Mariquito",
              "rsilva@weg.net" => "Ricardo Goncalves da Silva",
              "guareschi@weg.net" => "Rodrigo Guareschi"
            ];
            foreach ($crvs as $email => $nome) {
              $selected = valor('crv', $dados) == $email ? 'selected' : '';
              echo "<option value='$email' $selected>$nome</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Cliente -->
      <div class="form-section">
        <div class="form-section-title">Cliente</div>
        <div class="form-group"><label for="cliente">Cliente:</label><input type="text" id="cliente" name="cliente" value="<?= valor('Cliente', $dados) ?>" required></div>
        <div class="form-group"><label for="codigoCliente">Código Cliente:</label><input type="text" id="codigoCliente" name="codigoCliente" value="<?= valor('CodigoCliente', $dados) ?>" required></div>
        <div class="form-group"><label for="cnpj">CNPJ:</label><input type="text" id="cnpj" name="cnpj" value="<?= valor('Cnpj', $dados) ?>" required></div>
        <div class="form-group"><label for="cidade">Cidade:</label><input type="text" id="cidade" name="cidade" value="<?= valor('Cidade', $dados) ?>" required></div>
        <div class="form-group"><label for="estado">Estado:</label><input type="text" id="estado" name="estado" value="<?= valor('Estado', $dados) ?>" required></div>
        <div class="form-group"><label for="pais">País:</label><input type="text" id="pais" name="pais" value="<?= valor('Pais', $dados) ?>" required></div>
      </div>

      <!-- Proposta -->
      <div class="form-section">
        <div class="form-section-title">Proposta</div>
        <div class="form-group">
          <label for="Status">Status:</label>
            <select id="Status" name="Status" required>
              <?php
                $status_aplicador = valor('status_aplicador', $dados); // ajuste conforme necessário

                // Lista padrão
                $StatusList = ["Proposta em Elaboração", "Em Negociação", "Proposta Concluída", "Perdido", "Budget", "Proposta em Revisão"];

                // Lista alternativa se for "Proposta Concluída"
                if ($status_aplicador === "Proposta Concluída") {
                  $StatusList = ["Proposta Concluída", "Em Negociação", "Previsto para Mês Espec.", "Perdido", "Budget", "Proposta em Revisão"];
                }

                foreach ($StatusList as $st) {
                  $selected = valor('Status', $dados) == $st ? 'selected' : '';
                  echo "<option value='$st' $selected>$st</option>";
                }
              ?>
            </select>

        </div>
        <div class="form-group"><label for="status_aplicador">status_aplicador:</label><input type="text" id="status_aplicador" name="status_aplicador" value="<?= valor('status_aplicador', $dados) ?>" readonly></div>
        <div class="form-group"><label for="aplicador">Aplicador:</label><input type="text" id="aplicador" name="aplicador" value="<?= valor('aplicador', $dados) ?>"></div>
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
        <!-- Exibir arquivo anexado -->
        <?php if (!empty($dados['anexo'])): ?>
            <div class="form-group">
                <label for="arquivo">Arquivo Anexado:</label>
                <a href="<?= $dados['anexo']; ?>" target="_blank" style="color: #0090C5;" >Clique aqui para baixar o arquivo</a>
            </div>
        <?php endif; ?>
      </div>

      
      <!-- Comentários -->
      <div class="form-section">
        <div class="form-section-title">FeedBack CRV - Aplicador</div>
        <div class="form-group"><label for="feedback_crv">CRV:</label><textarea id="observacao" name="feedback_crv" ><?= valor('feedback_crv', $dados) ?></textarea></div>
        <div class="form-group"><label for="feedback_aplicador">Aplicador:</label><textarea id="observacao" name="feedback_aplicador" ><?= valor('feedback_aplicador', $dados) ?></textarea></div>
      </div>

      <div class="form-group">
        <button type="submit"><?= $edicao ? 'Salvar Alterações' : 'Criar Demanda' ?></button>
      </div>
    </form>
  </div>

  <script src="js/loader.js"></script>
  <script src="js/wave.js"></script>
  <script src="js/frete.js"></script>
  <script>
    document.getElementById('Status')?.addEventListener('change', function () {
      const status_aplicador = document.getElementById('status_aplicador');
      if (!status_aplicador) return;

      const aplicadorValue = this.value;

      // Define as regras de correspondência
      if (aplicadorValue === 'Proposta Concluída') {
        status_aplicador.value = 'Proposta Concluída';
      } else if (aplicadorValue === 'Proposta em Elaboração') {
        status_aplicador.value = 'Nova Solicitação';
      } else if (aplicadorValue === 'Perdido') {
        status_aplicador.value = 'Perdido';
      } else if (aplicadorValue === 'Em Negociação') {
        status_aplicador.value = 'Em Implantação';
      } else if (aplicadorValue === 'Budget') {
        status_aplicador.value = 'Budget';
      } else if (aplicadorValue === 'Proposta em Revisão') {
        status_aplicador.value = 'Revisar Proposta';
      } else {
        Status.value = ''; // ou mantenha o valor atual se quiser evitar alteração
      }
    });
    document.getElementById('tipoProposta').addEventListener('change', function() {
      var Status = this.value;
      var StatusAplicador = document.getElementById('aplicador');

      if (Status === 'Campo') {
        aplicador.value = 'lucaspaulo@weg.net, luisfranca@weg.net';
      } else if (Status === 'Fábrica') {
        aplicador.value = 'grahl@weg.net, jonas3, luisgm, pcampos';
      } else if (Status === 'Partes e Peças') {
        aplicador.value = 'adrianad@weg.net, ullera@weg.net, gabrielfl@weg.net, cristianeaf@weg.net';
      }
    });
  </script>
</body>
</html>
