<?php

require("../../PHP/session_verify.php");
include("../../PHP/professor_usuario.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(2, $referer)){
    $logindata = getLoginData($referer);
    $dados = new Usuario();
    $vetorTurmas = $dados->listarTurmas();
    $vetor = $dados->listarDadosProfessores();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Matéria</title>
    <link rel="stylesheet" href="../../CSS/TelaADM.css">
    <link rel="icon" href="seu-icone.png" type="image/png">
</head>

<body>
    <header>
        <h1>Cadastro de Matéria</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
        <form action="../../PHP/Cadastro_sala.php" method="post">
            <div class="form-group">
                <label for="idMateria">Indentificação da materia:</label>
                <input type="text" id="idMateria" name="idMateria" required>
            </div>
            <div class="form-group">
                <label for="nomeMateria">Nome da Matéria:</label>
                <input type="text" id="nomeMateria" name="nomeMateria" required>
            </div>
            <div class="form-group">
                <label for="professorMateria">Professor:</label>
                <select id="nomeProfessor" name="idProfessor" required>
                    <?php foreach ($vetor as $professor) { ?>
                        <option value="<?php echo $professor->getRa(); ?>">
                            <?php echo $professor->getNome(); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nomeTurma">Turmas:</label>
                <ul id="turma"></ul>
                <select name="turma" id="addturma">
                    <?php foreach ($vetorTurmas as $turma) { ?>
                        <option value="<?php echo $turma->getRa(); ?>">
                            <?php echo $turma->getNome(); ?>
                        </option>
                    <?php } ?>

                </select>
                <a class="button" onclick="addturma();" value="">add turma</a>
            </div>


            <!-- Campo oculto para armazenar a lista de turma -->
            <input type="hidden" name="turmaList" id="turmaList" value="">
            <div class="form-group">
                <label for="numsalaMateria">N° Sala:</label>
                <input type="text" id="numsalaMateria" name="numsalaMateria" inputmode="numeric" pattern="[0-9]*"
                    required>
            </div>
            <div class="form-group">
                <label for="numblocoMateria">N° Bloco:</label>
                <input type="text" id="numblocoMateria" name="numblocoMateria" inputmode="numeric" pattern="[0-9]*"
                    required>
            </div>
            <div class="form-group">
                <label for="data">Data inicio:</label>
                <input type="date" id="datainicio" name="datainicio">

            </div>
            <div class="form-group">
                <label for="data">Data Fim:</label>
                <input type="date" id="datafim" name="datafim">

            </div>
            <div class="form-group">
                <label for="horainicioMateria">Hora Inicial:</label>
                <input type="time" id="horainicioMateria" name="horainicioMateria" required>
            </div>
            <div class="form-group">
                <label for="tempoaulaMateria">Tempo de Aula:</label>
                <input type="time" id="tempoaulaMateria" name="tempoaulaMateria" required>
            </div>
            <div class="form-group">
                <label for="quantidadeAulas">Quantidade de Aulas:</label>
                <input type="number" id="quantidadeAulas" name="quantidadeAulas" required>
            </div>
            <div class="form-group">
                <label for="diasemanaMateria">Dia da Semana:</label>
                <select id="diasemanaMateria" name="diasemanaMateria" required>
                    <option value="1">Segunda-feira</option>
                    <option value="2">Terça-feira</option>
                    <option value="3">Quarta-feira</option>
                    <option value="4">Quinta-feira</option>
                    <option value="5">Sexta-feira</option>
                    <option value="6">Sábado</option>
                </select>
            </div>

            <button type="submit">Cadastrar</button>
            <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        </form>
    </main>
</body>
<script>
    var turmaList = "";
    var i = 0;

    function addturma() {
        var turmass = document.getElementById("addturma").value;
        if (turmass != "") {
            turmaList += "<li><span name='turmaItem' id='turmaItem" + i + "'>" + turmass + "</span> " +
                "<button onclick='removeturma()'>remove</button></li>";
            i++;
            document.getElementById("turma").innerHTML = turmaList;
            document.getElementById("addturma").value = "";
        }
        updateHiddenInput();
    }

    function removeturma() {
        var items = document.querySelectorAll("#turma li"),
            index, tab = [];
        for (var j = 0; j < items.length; j++) {
            tab.push(items[j].innerHTML);
        }
        for (var j = 0; j < items.length; j++) {
            items[j].onclick = function () {

                index = tab.indexOf(this.innerHTML);
                items[index].parentNode.removeChild(items[index]);
                tab.splice(index, 1);

                // Após remover o elemento da lista, atualize o input hidden
                updateHiddenInput();
            };
        }
        console.log(tab);
        turmaList = "";
        for (var j = 0; j < tab.length; j++) {
            turmaList += "<li>" + tab[j] + "</li>";
        }
    }

    function updateHiddenInput() {
        var items = document.querySelectorAll("#turma li span");
        var turma = [];
        for (var i = 0; i < items.length; i++) {
            turma.push(items[i].textContent);
        }
        document.getElementById("turmaList").value = turma.join("<-->");
    }
</script>

</html>