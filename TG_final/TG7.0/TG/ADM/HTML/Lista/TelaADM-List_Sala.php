<?php
require("../../PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if (RBAC(2, $referer)) {
    $logindata = getLoginData($referer);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Matérias</title>
    <link rel="stylesheet" href="../../CSS/TelaADM-List.css">
</head>

<body>
    <header>
        <h1>Lista de Matérias Cadastradas</h1>
        <div>
            <p>
                <?php echo $logindata["RA"]; ?>
            </p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a>
        </div>
    </header>
    <main>
        <div class="search-bar">
            <input type="text" id="nomeSearch" placeholder="Digite o Nome da Matéria">
            <button onclick="searchByNome()">Buscar</button>
        </div>
        <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        <table>
            <thead>
                <tr>
                    <th>Nome da Matéria </th>
                    <th>Professor</th>
                    <th>N° Sala</th>
                    <th>N° Bloco</th>
                    <th>Turma</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $banco = new Banco();
                $stmt = $banco->getConexao()->prepare("SELECT m.id_materia, m.Nome_materia, p.Nome, m.Numero_sala, m.Numero_Bloco, t.Nome_turma FROM materia AS m LEFT JOIN materias_turmas AS mt ON m.id_materia = mt.materia_id LEFT JOIN turma AS t ON mt.turma_id = t.id_turma LEFT JOIN professor AS p ON m.id_professor = p.RA");

                $stmt->execute();

                $result = $stmt->get_result();

                while ($linha = $result->fetch_object()) {
                    echo '<tr>';
                    echo '<td>' . $linha->Nome_materia . '</td>';
                    echo '<td>' . $linha->Nome . '</td>';
                    echo '<td>' . $linha->Numero_sala . '</td>';
                    echo '<td>' . $linha->Numero_Bloco . '</td>';
                    echo '<td>' . $linha->Nome_turma . '</td>';
                    echo '<td><a href="../Editar/TelaADM-Edit_Sala.php?id=' . $linha->id_materia . '">Editar</a></td>';
                    //echo '<td> <input type="checkbox"></td>';
                    echo '</tr>';
                }
                ?>
                <!-- Exemplo de matéria na lista -->
                <!-- <tr>
                    <td>Matemática</td>
                    <td>Paulo</td>
                    <td>201</td>
                    <td>6</td>
                    <td>10UNA-Comp</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Sala.html">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>Português</td>
                    <td>Virginia</td>
                    <td>302</td>
                    <td>10</td>
                    <td>3UMA-ARQ</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Sala.php">Editar</a>
                    </td>
                </tr>
                <tr>
                    <td>Ciências</td>
                    <td>Valdirene</td>
                    <td>103</td>
                    <td>10</td>
                    <td>1UNA-QUI</td>
                    <td>
                        <a href="../Editar/TelaADM-Edit_Sala.php">Editar</a>
                    </td>
                </tr> -->
                <!-- Outras matérias podem ser listadas aqui -->
            </tbody>
        </table>
    </main>
</body>
<script>
    function searchByNome() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("nomeSearch");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; // A coluna do nome da matéria é a primeira (índice 0)

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