<?php
    session_start();

    require_once("redirect.php");
    require_once("banco.php");

    function getLoginData($referer) {
        if(isset($_SESSION["ra"]) && isset($_SESSION["tipo_acesso"])) {
            $banco = new Banco();
            $ra = $_SESSION["ra"];
            $tipo = $_SESSION["tipo_acesso"];
            
            switch ($tipo) {
                case 0:
                    $stmt = $banco->getConexao()->prepare("SELECT * FROM aluno WHERE RA = ? AND ativo = 1;");
                    break;
                case 1:
                    $stmt = $banco->getConexao()->prepare("SELECT * FROM professor WHERE RA = ? AND ativo = 1;");
                    break;
                case 2:
                    $stmt = $banco->getConexao()->prepare("SELECT * FROM adm WHERE RA = ?;");
                    break;
                default:
                    redirect($referer);
                    break;
            }

            $stmt->bind_param("i", $ra);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $firstRow = $result->fetch_assoc();
        
                $resultArray = array();
        
                // Iteramos sobre as colunas para associar dinamicamente os nomes
                foreach ($firstRow as $colName => $colValue) {
                    $resultArray[$colName] = $colValue;
                }
        
                // Agora, $resultArray contém os resultados com os nomes das colunas como índices
                return $resultArray;
            } else {
                redirect($referer);
            }
        } else {
            redirect($referer);
        }
    }

    function RBAC($nivel, $referer) {
        if ($_SESSION["tipo_acesso"] != $nivel) {
            redirect($referer);
        } else {
            return true;
        }
    }
?>