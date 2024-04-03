<?php
    require("redirect.php");
    include("banco.php");

    $banco = new Banco();

    $idMateria = isset($_POST['idMateria']) ? $_POST['idMateria'] : '';
    $nomeMateria = isset($_POST['nomeMateria']) ? $_POST['nomeMateria'] : '';
    $numsalaMateria = isset($_POST['numsalaMateria']) ? $_POST['numsalaMateria'] : '';
    $numblocoMateria = isset($_POST['numblocoMateria']) ? $_POST['numblocoMateria'] : '';
    $horainicioMateria = isset($_POST['horainicioMateria']) ? $_POST['horainicioMateria'] . ':00': '00:00:00';
    $tempoaulaMateria = isset($_POST['tempoaulaMateria']) ? $_POST['tempoaulaMateria'] . ':00': '00:00:00';
    $quantidadeAulas = isset($_POST['quantidadeAulas']) ? $_POST['quantidadeAulas'] : '';
    $diasemanaMateria = isset($_POST['diasemanaMateria']) ? $_POST['diasemanaMateria'] : '';
    $idProfessor = isset($_POST['idProfessor']) ? $_POST['idProfessor'] : '';
    $turmasList = isset($_POST['turmaList']) ? $_POST['turmaList'] : '';
    $datainicioMateria = isset($_POST['datainicio']) ? $_POST['datainicio'] : '0000-00-00';
    $datafimMateria = isset($_POST['datafim']) ? $_POST['datafim'] : '0000-00-00';

    //echo($horainicioMateria);

    $stmt = $banco->getConexao()->prepare("INSERT INTO materia (id_materia, Nome_materia, Numero_sala, Numero_bloco, Hora_inicial, Tempo_aula, qtde_aulas, dia_semana, id_professor, data_inicio, data_fim) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ississiiiss", $idMateria, $nomeMateria, $numsalaMateria, $numblocoMateria, $horainicioMateria, $tempoaulaMateria, $quantidadeAulas, $diasemanaMateria, $idProfessor, $datainicioMateria, $datafimMateria);

    if ($stmt->execute()) {
        // Obtém o ID da matéria recém-inserida
        
    
        // Separe a lista de turmas
        $turmas = explode("<-->", $turmasList);
    
        // Loop para inserir cada turma na matéria
        foreach ($turmas as $turma) {
            // Prepara o comando SQL para inserir um aluno na turma
            $stmt = $banco->getConexao()->prepare('INSERT INTO materias_turmas (materia_id, turma_id) VALUES (?, ?)');
            $stmt->bind_param('ii', $idMateria, $turma);
    
            // Executa o comando SQL para inserir o aluno na turma
            $stmt->execute();
        }
    
        echo "Matéria e turmas inseridos com sucesso!";
    } else {
        echo "Erro ao inserir a matéria.";
    }
    
    $banco->getConexao() -> close();
    redirect("../HTML/Lista/TelaADM-List_Sala.php");

?>
