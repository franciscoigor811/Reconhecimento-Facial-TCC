<?php
require("../PHP/session_verify.php");

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
    <title>ADM Menu</title>
    <link rel="stylesheet" href="../CSS/TelaADM-Menu.css">
</head>

<body>
    <header>
        <h1>Menu de Administração</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="../../MenuLogin.html"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main class="table-container">
        <!-- Use a classe .table-container aqui -->
        <table>
            <tr>
                <td><a href="Cadastro/TelaADM-Cad_Aluno.php" class="menu-button">Cadastrar Aluno</a></td>
                <td><a href="Cadastro/TelaADM-Cad_Professor.php" class="menu-button">Cadastrar Professor</a></td>
            </tr>
            <tr>
                <td><a href="Cadastro/TelaADM-Cad_Sala.php" class="menu-button">Cadastrar Matéria</a></td>
                <td><a href="Cadastro/TelaADM-Cad_Turma.php" class="menu-button">Cadastrar Turma</a></td>
            </tr>
            <tr>
                <td><a href="Lista/TelaADM-List_Aluno.php" class="menu-button">Lista Aluno</a></td>
                <td><a href="Lista/TelaADM-List_Professor.php" class="menu-button">Lista Professor</a></td>
            </tr>
            <tr>
                <td><a href="Lista/TelaADM-List_Sala.php" class="menu-button">Lista Matéria</a></td>
                <td><a href="Lista/TelaADM-List_Turma.php" class="menu-button">Lista Turma</a></td>
            </tr>
        </table>
    </main>
</body>

</html>