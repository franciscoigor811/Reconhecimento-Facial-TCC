<?php
require_once("../../PHP/redirect.php");
include_once("../../PHP/banco.php");

$banco = new Banco();

// Receba os parâmetros de atualização da matéria
$idMateria = isset($_POST['idMateria']) ? $_POST['idMateria'] : '';

// Verifique se o ID da matéria foi fornecido
if ($idMateria === '') {
    echo "ID da matéria não foi fornecido.";
    exit; // Ou redirecione para uma página de erro, conforme necessário
}

$nomeMateria = $_POST['nomeMateria'];
$numsalaMateria = $_POST['numsalaMateria'];
$numblocoMateria = $_POST['numblocoMateria'];
$horainicioMateria = $_POST['horainicioMateria'] . ':00';
$tempoaulaMateria = $_POST['tempoaulaMateria'] . ':00';
$quantidadeAulas = $_POST['quantidadeAulas'];
$diasemanaMateria = $_POST['diasemanaMateria'];
$idProfessor = isset($_POST['idProfessor']) ? $_POST['idProfessor'] : null;
$datainicioMateria = isset($_POST['datainicio']) ? $_POST['datainicio'] : null;
$datafimMateria = isset($_POST['datafim']) ? $_POST['datafim'] : null;

// Verifique se as variáveis obrigatórias estão definidas
if ($idProfessor === null || $datainicioMateria === null || $datafimMateria === null) {
    echo "idp: " . $idProfessor . " <br>";
    echo "dim: " . $datainicioMateria . " <br>";
    echo "dfm: " . $datafimMateria . " <br>";
    echo "Parâmetros obrigatórios não foram fornecidos.";
    exit; // Ou redirecione para uma página de erro, conforme necessário
}

// Atualize os campos da tabela materia
$stmtUpdate = $banco->getConexao()->prepare("UPDATE materia SET Nome_materia = ?, Numero_sala = ?, Numero_bloco = ?, Hora_inicial = ?, Tempo_aula = ?, qtde_aulas = ?, dia_semana = ?, id_professor = ?, data_inicio = ?, data_fim = ? WHERE id_materia = ?");
$stmtUpdate->bind_param("ssissiisssi", $nomeMateria, $numsalaMateria, $numblocoMateria, $horainicioMateria, $tempoaulaMateria, $quantidadeAulas, $diasemanaMateria, $idProfessor, $datainicioMateria, $datafimMateria, $idMateria);
$stmtUpdate->execute();
$stmtUpdate->close();

// Exclua os registros existentes na tabela materias_turmas para a matéria a ser atualizada
$stmtDelete = $banco->getConexao()->prepare('DELETE FROM materias_turmas WHERE materia_id = ?');
$stmtDelete->bind_param('i', $idMateria);
$stmtDelete->execute();
$stmtDelete->close();

// Insira novamente os registros atualizados na tabela materias_turmas
$turmasList = isset($_POST['turmaList']) ? $_POST['turmaList'] : '';
$turmas = explode("<-->", $turmasList);

// Loop para inserir cada turma na matéria
foreach ($turmas as $turma) {
    // Prepara o comando SQL para inserir um aluno na turma
    $stmtInsert = $banco->getConexao()->prepare('INSERT INTO materias_turmas (materia_id, turma_id) VALUES (?, ?)');
    $stmtInsert->bind_param('ii', $idMateria, $turma);

    // Executa o comando SQL para inserir o aluno na turma
    if (!$stmtInsert->execute()) {
        // Check for specific foreign key violation error
        if ($stmtInsert->errno == 1452) {
            // Output a message indicating which Turma ID caused the foreign key violation
            die("Erro de violação de chave estrangeira: Turma ID $turma não encontrada na tabela turma.");
        } else {
            // Output a generic error message
            die("Erro ao inserir na tabela materias_turmas: " . $stmtInsert->error);
        }
    }

    $stmtInsert->close();
}

// Atualização bem-sucedida
echo "Matéria e turmas atualizadas com sucesso!";

$banco->getConexao()->close();
redirect("../Lista/TelaADM-List_Sala.php");
?>
