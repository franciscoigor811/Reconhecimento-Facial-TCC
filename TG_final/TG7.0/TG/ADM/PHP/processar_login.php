<?php
    session_start();

    require("banco.php");
    require("redirect.php");

    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../MenuLogin.html';

    if(isset($_POST["RA"]) && isset($_POST["password"]) && isset($_POST["tipo_acesso"])) {
        $banco = new Banco();
        $ra = $_POST["RA"];
        $senha = $_POST["password"];
        $tipo = $_POST["tipo_acesso"];

        switch ($tipo) {
            case 0:
                $stmt = $banco->getConexao()->prepare("SELECT * FROM aluno WHERE RA = ? AND Senha = ? AND ativo = 1;");
                break;
            case 1:
                $stmt = $banco->getConexao()->prepare("SELECT * FROM professor WHERE RA = ? AND Senha = ? AND ativo = 1;");
                break;
            case 2:
                $stmt = $banco->getConexao()->prepare("SELECT * FROM adm WHERE RA = ? AND Senha = ?;");
                break;
            default:
                redirect($referer);
                break;
        }

        $stmt->bind_param("is", $ra, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $firstRow = $result->fetch_assoc();
    
            $_SESSION["ra"] = $firstRow["RA"];
            $_SESSION["tipo_acesso"] = $tipo;
    
            switch ($tipo) {
                case 0:
                    redirect("../../Aluno/VisualizarFaltas.php");
                    break;
                case 1:
                    redirect("../../Professor/MenuProfessor.php");
                    break;
                case 2:
                    redirect("../HTML/ADM-Menu.php");
                    break;
                default:
                    redirect($referer);
                    break;
            }
        } else {
            $_SESSION["erro"] = true;
            redirect($referer);
        }
    
    } else {
        $_SESSION["erro"] = true;
        redirect($referer);
    }
?>