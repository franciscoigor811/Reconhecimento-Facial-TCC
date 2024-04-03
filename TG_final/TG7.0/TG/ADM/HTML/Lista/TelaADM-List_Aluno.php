<?php
//include("../../PHP/banco.php");
require("../../PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(2, $referer)){
    $logindata = getLoginData($referer);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
    <link rel="stylesheet" href="../../CSS/TelaADM-List.css">
</head>

<body>
    <header>
        <h1>Lista de Alunos Cadastrados</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
        <div class="search-bar">
            <input type="text" id="raSearch" placeholder="Digite o RA">
            <button onclick="searchByRA()">Buscar</button>
        </div>
        <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        <h2 class="diferencialTabela"> Alunos Ativos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>RA</th>

                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de aluno na lista -->
                <?php
                $banco = new Banco();
                $stmt = $banco->getConexao()->prepare("SELECT `RA`,`Nome`,`Email` FROM `aluno` where ativo = 1");
      
        $stmt->execute();
        
        $result = $stmt->get_result();

        while ($linha = $result->fetch_object()) {
            echo '<tr>';
            echo '<td>' . $linha->Nome . '</td>';
            echo '<td>' . $linha->Email . '</td>';
            echo '<td>0' . $linha->RA . '</td>';
            echo '<td><a href="../Editar/TelaADM-Edit_Aluno.php?ra='.$linha->RA.'">Editar</a></td>';
            
            echo '</tr>';
        }
                ?>
                <!-- <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>123456</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>46784678</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>8764488</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr> -->
                <!-- Outros alunos podem ser listados aqui -->
            </tbody>
        </table>
        
        <h2 class="diferencialTabela"> Alunos Inativos</h2>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>RA</th>

                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de aluno na lista -->
                <?php
                //$banco = new Banco();
                $stmt = $banco->getConexao()->prepare("SELECT `RA`,`Nome`,`Email` FROM `aluno` where ativo = 0");
      
        $stmt->execute();
        
        $result = $stmt->get_result();

        while ($linha = $result->fetch_object()) {
            echo '<tr>';
            echo '<td>' . $linha->Nome . '</td>';
            echo '<td>' . $linha->Email . '</td>';
            echo '<td>0' . $linha->RA . '</td>';
            echo '<td><a href="../Editar/TelaADM-Edit_Aluno.php?ra='.$linha->RA.'">Editar</a></td>';
            //echo '<td> <input type="checkbox"></td>';
            echo '</tr>';
        }
                ?>
                <!-- <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>123456</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>46784678</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>João Silva</td>
                    <td>joao@email.com</td>
                    <td>8764488</td>
                    <td>Turma A</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Aluno.html">Editar</a>
                    </td>
                </tr> -->
                <!-- Outros alunos podem ser listados aqui -->
            </tbody>
        </table>
    </main>
</body>
<script>
    function searchByRA() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("raSearch");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2]; // A coluna RA está na terceira coluna (índice 2)

            if (td) {
                txtValue = td.textContent || td.innerText;

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

</html>