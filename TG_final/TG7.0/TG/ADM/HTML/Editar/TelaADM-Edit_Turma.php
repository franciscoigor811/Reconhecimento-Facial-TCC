<?php
require_once("../../PHP/session_verify.php");
include_once("../../PHP/professor_usuario.php");
include_once("../../PHP/banco.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if (RBAC(2, $referer)) {
    $logindata = getLoginData($referer);
    $idTurma = isset($_GET["id"]) ? $_GET["id"] : redirect($referer);

    // Busca dados da turma
    $bancoTurma = new Banco();
    $stmtTurma = $bancoTurma->getConexao()->prepare("SELECT * FROM turma WHERE id_turma = ?");
    $stmtTurma->bind_param("i", $idTurma);
    $stmtTurma->execute();
    $resultTurma = $stmtTurma->get_result();

    if ($resultTurma->num_rows > 0) {
        $dadosTurma = $resultTurma->fetch_object();
    } else {
        // Trate o caso em que a turma não é encontrada
        redirect($referer);
    }

    // Busca alunos associados a essa turma
    $bancoAlunos = new Banco();
    $stmtAlunos = $bancoAlunos->getConexao()->prepare("SELECT ta.aluno_RA, a.Nome FROM turma_alunos AS ta LEFT JOIN aluno AS a ON ta.aluno_RA = a.RA WHERE ta.Turma_id = ?");
    $stmtAlunos->bind_param("i", $idTurma);
    $stmtAlunos->execute();
    $resultAlunos = $stmtAlunos->get_result();

    // Busca RAs de todos os alunos cadastrados nessa turma
    $stmtRAs = $bancoAlunos->getConexao()->prepare("SELECT aluno_RA FROM turma_alunos WHERE Turma_id = ?");
    $stmtRAs->bind_param("i", $idTurma);
    $stmtRAs->execute();
    $resultRAs = $stmtRAs->get_result();
    $RAsTurma = array();
    while ($row = $resultRAs->fetch_assoc()) {
        $RAsTurma[] = $row['aluno_RA'];
    }

    $RAsTurmaString = implode("<-->", $RAsTurma);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma</title>
    <link rel="stylesheet" href="../../CSS/TelaADM.css">
    <link rel="icon" href="seu-icone.png" type="image/png">
</head>

<body>
    <header>
        <h1>Editar Turma</h1>
        <div>
            <p>
                <?php echo $logindata["RA"]; ?>
            </p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a>
        </div>
    </header>
    <main>
        <form action="Update_turma.php" method="post">
            <div class="form-group">
                <input type="hidden" name = "idTurma" value ="<?php echo $idTurma;?>" hidden>
                <label for="nomeTurma">Nome da Turma:</label>
                <input type="text" id="nomeTurma" name="nomeTurma" required
                    value="<?php echo $dadosTurma->Nome_turma; ?>">
            </div>

            <div class="form-group">
                <label for="alunos">Alunos:</label>
                <ul id="alunos">
                    <?php while ($aluno = $resultAlunos->fetch_object()) { ?>
                        <li data-id="<?php echo $aluno->aluno_RA; ?>">
                            <span name="alunoItem" id="alunoItem<?php echo $aluno->aluno_RA; ?>">
                                <?php echo $aluno->Nome; ?> (RA:
                                <?php echo $aluno->aluno_RA; ?>)
                            </span>
                            <button type="button" onclick="removeAluno('<?php echo $aluno->aluno_RA; ?>')">remove</button>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="form-group">
                <label for="addAluno">Adicionar Aluno:</label>
                <input type="text" id="addAluno" name="addAluno">
                <button type="button" id ="btnAddAluno">Adicionar</button>
            </div>

            <input type="hidden" id="alunoList" name="alunoList" value="<?php echo $RAsTurmaString; ?>">

            <button type="submit">Salvar</button>
            <a href="../Lista/TelaADM-List_Turma.php"><button type="button">Voltar</button></a>
        </form>
    </main>

    <script>
        document.getElementById("btnAddAluno").addEventListener("click", addAluno, false);
       
        function addAluno() {
            console.log("Função addAluno chamada.");
            var inputElement = document.getElementById("addAluno");
            var alunoRA = inputElement.value.trim();

            if (alunoRA && "<?php echo $RAsTurmaString; ?>".indexOf(alunoRA) === -1) {
                var alunoListInput = document.getElementById("alunoList");
                var alunoList = alunoListInput.value;

                alunoList = (alunoList.length > 0 ? alunoList + "<-->" : "") + alunoRA;
                alunoListInput.value = alunoList;

                var ul = document.getElementById("alunos");
                var li = document.createElement("li");
                li.setAttribute("data-id", alunoRA);
                li.innerHTML = "<span name='alunoItem' id='alunoItem" + alunoRA + "'>" +
                    alunoRA + " <button type='button' onclick='removeAluno(\"" + alunoRA + "\")'>remove</button></span>";
                ul.appendChild(li);

                inputElement.value = "";
            } else if ("<?php echo $RAsTurmaString; ?>".indexOf(alunoRA) !== -1) {
                alert("RA do aluno já está na lista.");
            }
        }

        function removeAluno(alunoRA) {
            var ul = document.getElementById("alunos");
            var li = ul.querySelector("[data-id='" + alunoRA + "']");
            li.parentNode.removeChild(li);

            updateHiddenInputAluno();
        }

        function updateHiddenInputAluno() {
            var ul = document.getElementById("alunos");
            var items = ul.getElementsByTagName("li");
            var alunoList = [];
            for (var i = 0; i < items.length; i++) {
                alunoList.push(items[i].getAttribute("data-id"));
            }
            document.getElementById("alunoList").value = alunoList.join("<-->");
        }
        if (typeof addAluno === 'function') {
            console.log('A função addAluno está definida.');
        } else {
            console.error('A função addAluno NÃO está definida.');
        }
    
    </script>
</body>

</html>