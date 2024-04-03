<!--Update de acidentes-->
<?php
    require("../../ADM/PHP/redirect.php");
    include("../../ADM/PHP/banco.php");
    
    $banco = new Banco();

    $id_materia = isset($_POST['materia']) ? $_POST['materia'] : '';
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : '';
    $data_hora = isset($_POST['data_hora']) ? $_POST['data_hora'] : '';
 
    // Verifique se as senhas são iguais
    $stmt = $banco->getConexao()->prepare("INSERT INTO aulas_interrompidas (id_materia, tipo, motivo, data_hora) VALUES (?, ?, ?, ?)");

    $stmt->bind_param("isss", $id_materia, $tipo, $motivo, $data_hora);

    $stmt->execute();
    
    // Verifique se a execução foi bem-sucedida
    if ($stmt->affected_rows > 0) {
        redirect("../MenuProfessor.php");
    } else {
        echo "Erro ao inserir os dados.";
    }
?>
