<?php
require("redirect.php");
include("banco.php");
$banco = new banco();

$nomeTurma = isset($_POST['nomeTurma']) ? $_POST['nomeTurma'] : '';
$alunosList = isset($_POST['alunosList']) ? $_POST['alunosList'] : '';

// Prepara o comando SQL para inserir a turma
$stmt = $banco->getConexao()->prepare("INSERT INTO turma (Nome_turma) VALUES (?)");
$stmt->bind_param("s", $nomeTurma);

// Executa o primeiro comando SQL para inserir a turma
if ($stmt->execute()) {
    // Obtém o ID da turma recém-inserida
    $turma_id = $stmt->insert_id;

    // Separe a lista de alunos
    $alunos = explode("<-->", $alunosList);

    // Loop para inserir cada aluno na turma
    foreach ($alunos as $aluno) {
        // Prepara o comando SQL para inserir um aluno na turma
        $stmt = $banco->getConexao()->prepare('INSERT INTO turma_alunos (Turma_id, aluno_RA) VALUES (?, ?)');
        $stmt->bind_param('ii', $turma_id, $aluno);

        // Executa o comando SQL para inserir o aluno na turma
        $stmt->execute();
    }

    echo "Turma e alunos inseridos com sucesso!";
} else {
    echo "Erro ao inserir a turma.";
}

$banco->getConexao() -> close();
redirect("../HTML/Lista/TelaADM-List_Turma.php");
?>
