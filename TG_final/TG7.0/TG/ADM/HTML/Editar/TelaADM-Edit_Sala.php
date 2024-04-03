<?php
require("../../PHP/session_verify.php");
include("../../PHP/professor_usuario.php");
//include("../../PHP/banco.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if (RBAC(2, $referer)) {
    $logindata = getLoginData($referer);
    $id = isset($_GET["id"]) ? $_GET["id"] : redirect($referer);
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
            <p>
                <?php echo $logindata["RA"]; ?>
            </p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a>
        </div>
    </header>
    <main>
        <form action="Update_sala.php" method="post">

            <?php
            $banco = new Banco();
            $banco2 = new Banco();
            $stmt = $banco->getConexao()->prepare("SELECT * FROM `materia` where id_materia = ?");
            $stmt2 = $banco2->getConexao()->prepare("SELECT mt.materia_id, t.Nome_turma, t.id_turma FROM materias_turmas AS mt LEFT JOIN turma AS t ON mt.turma_id = t.id_turma WHERE mt.materia_id = ?");
            $stmt->bind_param("i", $id);
            $stmt2->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            while ($linha = $result->fetch_object()) {
                //     echo '<tr>';
                //     echo '<td>' . $linha->Nome . '</td>';
                //     echo '<td>' . $linha->Email . '</td>';
                //     echo '<td>0' . $linha->RA . '</td>';
                //     echo '<td><a href="../Editar/TelaADM-Edit_Aluno.php?ra='.$linha->RA.'">Editar</a></td>';
                //     echo '</tr>';
                // }
                ?>
                <div class="form-group">
                    <label for="nomeMateria">Nome da Matéria:</label>
                    <input type ="hidden" id= "idmateria" name = "idMateria" value = "<?php echo $id;?>" hidden>
                    <input type="text" id="nomeMateria" name="nomeMateria" required
                        value="<?php echo $linha->Nome_materia; ?>">
                </div>
                <div class="form-group">
                    <label for="nome">Professor:</label>
                    <select id="nomeProfessor" name="idProfessor" required>
                        <?php foreach ($vetor as $professor) { ?>
                            <option value="<?php echo $professor->getRa(); ?>" <?php if ($professor->getRa() == $linha->id_professor) {
                                   echo "selected";
                               } ?>>
                                <?php echo $professor->getNome(); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nomeTurma">Turmas:</label>
                    <ul id="turma">
                        <?php
                        $valor = "";
                        $stmt2->execute();
                        $i = 0;

                        $result2 = $stmt2->get_result();
                        while ($linha2 = $result2->fetch_object()) {
                            echo '<li data-id="' . $linha2->id_turma . '"><span name="turmaItem" id="turmaItem' . $linha2->id_turma . '">' . $linha2->Nome_turma . '</span> <button onclick="removeturma()">remove</button></li>';
                            if ($i == 0) {
                                $valor = $linha2->id_turma;
                            } else {
                                $valor = $valor . "<-->" . $linha2->id_turma;
                            }
                            $i++;
                        }
                        ?>
                    </ul>
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
                <input type="hidden" name="turmaList" id="turmaList" value="<?php echo $valor; ?>">
                <div class="form-group">
                    <label for="numsalaMateria">N° Sala:</label>
                    <input type="text" id="numsalaMateria" name="numsalaMateria" inputmode="numeric" pattern="[0-9]*"
                        required value="<?php echo $linha->Numero_sala; ?>">
                </div>
                <div class="form-group">
                    <label for="numblocoMateria">N° Bloco:</label>
                    <input type="text" id="numblocoMateria" name="numblocoMateria" inputmode="numeric" pattern="[0-9]*"
                        required value="<?php echo $linha->Numero_bloco; ?>">
                </div>
                <div class="form-group">
                <label for="data">Data inicio:</label>
                <input type="date" id="datainicio" name="datainicio" required value = "<?php echo $linha -> data_inicio?>">

            </div>
            <div class="form-group">
                <label for="data">Data Fim:</label>
                <input type="date" id="datafim" name="datafim" required value = "<?php echo $linha -> data_fim?>">

            </div>
                <div class="form-group">
                    <label for="horainicioMateria">Hora Inicial:</label>
                    <input type="time" id="horainicioMateria" name="horainicioMateria" required
                        value="<?php echo $linha->Hora_inicial; ?>">
                </div>
                <div class="form-group">
                    <label for="tempoaulaMateria">Tempo de Aula:</label>
                    <input type="time" id="tempoaulaMateria" name="tempoaulaMateria" required
                        value="<?php echo $linha->Tempo_aula; ?>">
                </div>
                <div class="form-group">
                    <label for="quantidadeAulas">Quantidade de Aulas:</label>
                    <input type="number" id="quantidadeAulas" name="quantidadeAulas" required
                        value="<?php echo $linha->qtde_aulas; ?>">
                </div>
                <div class="form-group">
                    <label for="diasemanaMateria">Dia da Semana:</label>
                    <select id="diasemanaMateria" name="diasemanaMateria" required>
                        <option value="1" <?php echo ($linha->dia_semana == 1) ? 'selected' : ''; ?>>Segunda-feira</option>
                        <option value="2" <?php echo ($linha->dia_semana == 2) ? 'selected' : ''; ?>>Terça-feira</option>
                        <option value="3" <?php echo ($linha->dia_semana == 3) ? 'selected' : ''; ?>>Quarta-feira</option>
                        <option value="4" <?php echo ($linha->dia_semana == 4) ? 'selected' : ''; ?>>Quinta-feira</option>
                        <option value="5" <?php echo ($linha->dia_semana == 5) ? 'selected' : ''; ?>>Sexta-feira</option>
                        <option value="6" <?php echo ($linha->dia_semana == 6) ? 'selected' : ''; ?>>Sábado</option>
                        
                    </select>
                </div>

                <button type="submit">Cadastrar</button>
                <a href="../Lista/TelaADM-List_Sala.php"><button type="button">Voltar</button></a>
                <?php
            }
            ?>
        </form>
    </main>
</body>
<script>
    var turmaList = "";
    var i = 0;

    function addturma() {
        var selectElement = document.getElementById("addturma");
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var turmaId = selectedOption.value;
        var turmaNome = selectedOption.text;

        if (turmaId && turmaNome) {
            var turmaListInput = document.getElementById("turmaList");
            var turmaList = turmaListInput.value;

            // Check if the turmaId already exists in the list
            if (turmaList.indexOf(turmaId) === -1) {
                // Add the new turmaId to the list
                turmaList = (turmaList.length > 0 ? turmaList + "<-->" : "") + turmaId;

                // Update the input hidden
                turmaListInput.value = turmaList;

                // Update the list in the HTML
                var turmaListArray = turmaList.split("<-->");
                var turmaListHtml = "";
                for (var i = 0; i < turmaListArray.length; i++) {
                    var currentTurmaId = turmaListArray[i];
                    turmaListHtml += "<li data-id='" + currentTurmaId + "'><span name='turmaItem' id='turmaItem" + currentTurmaId + "'>" + getTurmaNome(currentTurmaId) +
                                     "</span> <button onclick='removeturma()'>remove</button></li>";
                }
                document.getElementById("turma").innerHTML = turmaListHtml;
            } else {
                // Display a message indicating that the turma is already in the list
                alert("Turma already in the list.");
            }
        }
    }


// Função para obter o nome da turma pelo ID usando foreach
function getTurmaNome(turmaId) {
    var vetorTurmas = [
        <?php foreach ($vetorTurmas as $turma): ?>
            { ra: <?php echo $turma->getRa(); ?>, nome: '<?php echo $turma->getNome(); ?>' },
        <?php endforeach; ?>
    ];

    // Iterar sobre o array vetorTurmas em JavaScript
    for (var i = 0; i < vetorTurmas.length; i++) {
        if (vetorTurmas[i].ra == turmaId) {
            return vetorTurmas[i].nome;
        }
    }
    
    return "NomeTurmaDesconhecido"; // Retorna "NomeTurmaDesconhecido" se o ID não for encontrado
}


// Função fictícia para atualizar o input hidden
function updateHiddenInput() {
    // Implemente a lógica para atualizar o input hidden, se necessário
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