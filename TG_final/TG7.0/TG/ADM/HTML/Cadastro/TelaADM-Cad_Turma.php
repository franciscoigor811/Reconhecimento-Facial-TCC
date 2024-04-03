<?php
require("../../PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(2, $referer)){
    $logindata = getLoginData($referer);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Turma</title>
    <link rel="stylesheet" href="../../CSS/TelaADM.css">
    <link rel="icon" href="seu-icone.png" type="image/png">
</head>

<body>
    <header>
        <h1>Cadastro de Turma</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
        <form action="../../PHP/cadastr_turma.php" method="post">
            <div class="form-group">
                <label for="nomeTurma">Nome da Turma:</label>
                <input type="text" id="nomeTurma" name="nomeTurma" required>
            </div>
            <td>
                <label for="nomeAlunos">Alunos:</label>
                <ul id="alunos"></ul>
                <input type="text" name="alunos" id="addalunos" />
                <a class="button" onclick="addalunos();" value="">add alunos</a>
            </td>
            <!-- Campo oculto para armazenar a lista de alunos -->
            <input type="hidden" name="alunosList" id="alunosList" value="">

            <button type="submit">Cadastrar</button>
            <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        </form>
    </main>
</body>
<script>
    var alunosList = "";
    var i = 0;

    function addalunos() {
        var alunoss = document.getElementById("addalunos").value;
        if (alunoss != "") {
            alunosList += "<li><span name='alunosItem' id='alunosItem" + i + "'>" + alunoss + "</span> " +
                "<button onclick='removealunos()'>remove</button></li>";
            i++;
            document.getElementById("alunos").innerHTML = alunosList;
            document.getElementById("addalunos").value = "";
        }
        updateHiddenInput();
    }

    function removealunos() {
        var items = document.querySelectorAll("#alunos li"),
            index, tab = [];
        for (var j = 0; j < items.length; j++) {
            tab.push(items[j].innerHTML);
        }
        for (var j = 0; j < items.length; j++) {
            items[j].onclick = function() {

                index = tab.indexOf(this.innerHTML);
                items[index].parentNode.removeChild(items[index]);
                tab.splice(index, 1);

                // Após remover o elemento da lista, atualize o input hidden
                updateHiddenInput();
            };
        }
        console.log(tab);
        alunosList = "";
        for (var j = 0; j < tab.length; j++) {
            alunosList += "<li>" + tab[j] + "</li>";
        }
    }

    function updateHiddenInput() {
        var items = document.querySelectorAll("#alunos li span");
        var alunos = [];
        for (var i = 0; i < items.length; i++) {
            alunos.push(items[i].textContent);
        }
        document.getElementById("alunosList").value = alunos.join("<-->");
    }
</script>

</html>