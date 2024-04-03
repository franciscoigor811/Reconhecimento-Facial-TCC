<!--Update de acidentes-->
<?php
    require("redirect.php");
    include("professor_usuario.php");
    $aluno = new Usuario();

    $nome = isset($_POST['nomeProfessor']) ? $_POST['nomeProfessor'] : '';
    $email = isset($_POST['emailProfessor']) ? $_POST['emailProfessor'] : '';
    $estado = isset($_POST['estadoProfessor']) ? $_POST['estadoProfessor'] : '';
    $cidade = isset($_POST['cidadeProfessor']) ? $_POST['cidadeProfessor'] : '';
    $bairro = isset($_POST['bairroProfessor']) ? $_POST['bairroProfessor'] : '';
    $rua = isset($_POST['ruaProfessor']) ? $_POST['ruaProfessor'] : '';
    $cep = isset($_POST['cepProfessor']) ? $_POST['cepProfessor'] : '';
    $ra = isset($_POST['RAProfessor']) ? intval($_POST['RAProfessor']) : 0;
    $senha = isset($_POST['senhaProfessor']) ? $_POST['senhaProfessor'] : '';
    $confirmasenha = isset($_POST['confirmasenhaProfessor']) ? $_POST['confirmasenhaProfessor'] : '';
    
    // Verifique se as senhas são iguais
    if ($senha === $confirmasenha) {
        $resultado = $aluno->cadastrar($nome, $email, $estado, $cidade, $bairro, $rua, $cep, $ra, $senha);
    
        if ($resultado) {
            echo "Professor cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o aluno. Por favor, tente novamente.";
        }


    } else {
        echo "As senhas não coincidem. Por favor, tente novamente.";
    }


    

    

        redirect("../HTML/Cadastro/TelaADM-Cad_Professor.php");

?>