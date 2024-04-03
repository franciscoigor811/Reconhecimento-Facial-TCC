<?php
require("../../ADM/PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(1, $referer)){
    $logindata = getLoginData($referer);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelamento de Aula</title>
    <link rel="stylesheet" href="../CSS/AulaProfessor.css">
</head>

<body>
    <header>
        <h1>Cancelar Aula</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="../../MenuLogin.html"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
        <form action="Interromper.php" method="POST" id = "formcancela">
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" id="data_hora" name="data_hora" required>
            <input type="hidden" name="tipo" value = "0">

            <label for="materia">Materia:</label>
            <select id="materia" name="materia" required>
                <option selected hidden disabled> -- Selecione uma matéria -- </option>
                <?php
                $banco = new Banco();
                $stmt = $banco->getConexao()->prepare("SELECT * from materia where id_professor = ?;");
                $stmt->bind_param("i", $logindata["RA"]);
                $stmt->execute();

                $result = $stmt->get_result();

                while ($linha = $result->fetch_object()) {
                    echo  '<option value = "'.$linha -> id_materia.'">' . $linha->Nome_materia . '</option>';
                }
                ?>
            </select>

            <label for="motivo">Motivo de Não Ter Aula:</label>
            <textarea id="motivo" name="motivo" rows="4" required style="resize:none;"></textarea>


            <button type="submit">Salvar</button>
            <a href="../MenuProfessor.php"><button type="button">Voltar</button></a>
        </form>
    </main>
    <script>

    document.addEventListener('DOMContentLoaded', function () {
            var formulario = document.getElementById('formcancela');

            formulario.addEventListener('submit', function (event) {
                // Obtém o valor selecionado no campo de matéria
                var materiaSelecionada = document.getElementById("materia").value;

                // Verifica se a opção padrão desabilitada foi selecionada
                if (!materiaSelecionada || materiaSelecionada.trim() === '') {
                    alert("Por favor, selecione uma matéria válida.");
                    event.preventDefault(); // Previne o envio do formulário
                }
                // Se a validação passar, o formulário será enviado normalmente
            });
        });
        
    </script>
</body>

</html>