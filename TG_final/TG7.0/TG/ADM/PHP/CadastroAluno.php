<!--Update de acidentes-->
<?php
    require("redirect.php");
    include("aluno_usuario.php");
    $aluno = new Usuario();

    $nome = isset($_POST['nomeAluno']) ? $_POST['nomeAluno'] : '';
    $email = isset($_POST['emailAluno']) ? $_POST['emailAluno'] : '';
    $estado = isset($_POST['estadoAluno']) ? $_POST['estadoAluno'] : '';
    $cidade = isset($_POST['cidadeAluno']) ? $_POST['cidadeAluno'] : '';
    $bairro = isset($_POST['bairroAluno']) ? $_POST['bairroAluno'] : '';
    $rua = isset($_POST['ruaAluno']) ? $_POST['ruaAluno'] : '';
    $cep = isset($_POST['cepAluno']) ? $_POST['cepAluno'] : '';
    $ra = isset($_POST['RAAluno']) ? intval($_POST['RAAluno']) : 0;
    $senha = isset($_POST['senhaAluno']) ? $_POST['senhaAluno'] : '';
    $confirmasenha = isset($_POST['confirmasenhaAluno']) ? $_POST['confirmasenhaAluno'] : '';
    
    // Verifique se as senhas são iguais
    if ($senha === $confirmasenha) {
        $resultado = $aluno->cadastrar($nome, $email, $estado, $cidade, $bairro, $rua, $cep, $ra, $senha);
    
        if ($resultado) {
            echo "Aluno cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o aluno. Por favor, tente novamente.";
        }

        if (isset($_FILES['FOTOA']) && $_FILES['FOTOA']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['FOTOA']['name'], PATHINFO_EXTENSION);
            $nomeImagem = $ra . '.' . $extensao;
            $caminhoDestino = 'C:\\xampp\\htdocs\\TG_final\\Python\\Rostos\\' . $nomeImagem;
    
            // Mova o arquivo de imagem para a pasta desejada e renomeie-o
            if (move_uploaded_file($_FILES['FOTOA']['tmp_name'], $caminhoDestino)) {
                echo "Imagem enviada e salva com sucesso!";
            } else {
                echo "Erro ao salvar a imagem.";
            }
        }


    } else {
        echo "As senhas não coincidem. Por favor, tente novamente.";
    }


    

    echo '<script type="text/javascript">
            alert("Salvo com Sucesso !");
            window.history.go(-1);
        </script>';

        redirect("../HTML/Lista/TelaADM-List_Aluno.php");

?>