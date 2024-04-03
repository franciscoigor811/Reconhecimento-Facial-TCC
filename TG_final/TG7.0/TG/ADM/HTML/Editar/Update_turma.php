<?php
require_once("../../PHP/redirect.php");
include_once("../../PHP/banco.php");

$banco = new Banco();

// Receba os parâmetros de atualização da turma
$idTurma = isset($_POST['idTurma']) ? $_POST['idTurma'] : '';

// Verifique se o ID da turma foi fornecido
if ($idTurma === '') {
    echo "ID da turma não foi fornecido.";
    exit; // Ou redirecione para uma página de erro, conforme necessário
}

$nomeTurma = $_POST['nomeTurma'];

// Atualize os campos da tabela turma
$stmtUpdate = $banco->getConexao()->prepare("UPDATE turma SET Nome_turma = ? WHERE id_turma = ?");
$stmtUpdate->bind_param("si", $nomeTurma, $idTurma);
$stmtUpdate->execute();
$stmtUpdate->close();

// Exclua os registros existentes na tabela turma_alunos para a turma a ser atualizada
$stmtDelete = $banco->getConexao()->prepare('DELETE FROM turma_alunos WHERE Turma_id = ?');
$stmtDelete->bind_param('i', $idTurma);
$stmtDelete->execute();
$stmtDelete->close();

// Insira novamente os registros atualizados na tabela turma_alunos
$alunosList = isset($_POST['alunoList']) ? $_POST['alunoList'] : '';
$alunos = explode("<-->", $alunosList);

// Loop para inserir cada aluno na turma
foreach ($alunos as $aluno) {
    // Prepara o comando SQL para inserir um aluno na turma
    $stmtInsert = $banco->getConexao()->prepare('INSERT INTO turma_alunos (Turma_id, aluno_RA) VALUES (?, ?)');
    $stmtInsert->bind_param('is', $idTurma, $aluno);

    // Executa o comando SQL para inserir o aluno na turma
    if (!$stmtInsert->execute()) {
        // Output a generic error message
        die("Erro ao inserir na tabela turma_alunos: " . $stmtInsert->error);
    }

    $stmtInsert->close();
}

// Atualização bem-sucedida
echo "Turma e alunos atualizados com sucesso!";

$banco->getConexao()->close();
redirect("../Lista/TelaADM-List_Turma.php");
?>