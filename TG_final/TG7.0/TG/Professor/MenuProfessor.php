<?php
require("../ADM/PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(1, $referer)){
    $logindata = getLoginData($referer);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Professores</title>
    <link rel="stylesheet" href="../ADM/CSS/TelaADM-Menu.css">
</head>

<body>
    <header>
        <h1>Menu</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="../MenuLogin.html"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main class="table-container">
        <!-- Use a classe .table-container aqui -->
        <table>
            <tr>
                <!--<td><a href="HTML/AlunosPresentes.html" class="menu-button">Alunos Presentes</a></td>-->
                <td><a href="HTML/CancelarAula.php" class="menu-button">Cancelar Aula</a></td>
                <td><a href="HTML/EncerrarAula.php" class="menu-button">Encerrar Aula</a></td>
            </tr>
        </table>
    </main>
</body>

</html>