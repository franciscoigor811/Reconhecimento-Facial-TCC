<?php
require("../ADM/PHP/session_verify.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(0, $referer)){
    $logindata = getLoginData($referer);
    $alunoRA = $logindata["RA"];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Faltas</title>
    <link rel="stylesheet" href="VisualizarFaltas.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <header>
        <h1>Menu do Aluno</h1>
        <div>
            <p><?php echo $logindata["Nome"];?></p>
            <a href="../MenuLogin.html"><button type="button">Sair</button></a> 
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>Matéria Matriculada</th>
                <th>Quantidade de Faltas</th>
                <th>Aulas Ministradas</th>
                <th>Total de aulas</th>
                <th>Horas Feitas</th>
                <th>Gerar Gráfico</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $banco = new Banco();

                $materias = [];

                //$alunoRA = isset($_POST['RA']) ? $_POST['RA'] : '';
                $materiaID = isset($_POST['ID']) ? $_POST['ID'] : '';

                $stmt = $banco->getConexao()->prepare("SELECT * FROM materia WHERE id_materia IN (SELECT materia_id FROM materias_turmas WHERE turma_id IN (SELECT Turma_id FROM turma_alunos WHERE aluno_RA = ?));");

                $stmt->bind_param("i", $alunoRA);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result -> num_rows > 0) {
                    while ($row = $result -> fetch_assoc()) {
                        $row["Nome_materia"];
                        echo "<tr class=\"materia\">";
                        echo "<td>" . $row["Nome_materia"] . "</td>";
                        echo "<td>.</td>";
                        echo "<td>.</td>";
                        echo "<td>.</td>";
                        echo "<td>";
                            echo "<button onclick=\"openList_Aluno(" . $row["id_materia"] . ")\">Visualizar</button>";
                        echo "</td>";
                        echo "<td>";
                            echo "<button onclick=\"gerarGrafico(this)\" name=\"visualizar\">Visualizar</button>";
                        echo "</td>";
                        echo "</tr>";
                        $materias[] = $row["id_materia"];
                    }
                } else {
                    echo "Nenhum resultado encontrado.";
                }

                $stmt -> free_result(); 
            ?>
        </tbody>
    </table>
    <script>
        function gerarGrafico(button) {
            // Obter a linha correspondente ao botão clicado
            var tr = button.closest("tr");

            // Obter dados da segunda e terceira coluna
            var faltas = parseInt(tr.cells[1].innerText, 10);
            var ministradas = parseInt(tr.cells[2].innerText, 10);
            var horasFeitas = ministradas - faltas;
            var totalAulas = parseInt(tr.cells[3].innerText, 10);
            var horasCompleta = totalAulas - horasFeitas;

            // Criar um novo elemento div para "Total"
            var totalDiv = document.createElement("div");
            totalDiv.id = "Total";

            // Criar um parágrafo para "Total de aulas"
            var totalAulasParagrafo = document.createElement("p");
            totalAulasParagrafo.textContent = "Total de aulas:";
            
            // Criar um parágrafo para exibir o total de aulas
            var totalAulasElement = document.createElement("p");
            totalAulasElement.id = "totalAulas";
            totalAulasElement.textContent = totalAulas;

            // Adicionar os parágrafos à div "Total"
            totalDiv.appendChild(totalAulasParagrafo);
            totalDiv.appendChild(totalAulasElement);
            // Criar um novo elemento div e canvas dinamicamente
            var graficoDiv = document.createElement("div");
            graficoDiv.id = "grafico";
            var canvas = document.createElement("canvas");
            canvas.id = "graficoPizza";

            // Adicionar o canvas à div
            graficoDiv.appendChild(canvas);

            // Exibir o modal
            var modal = document.getElementById("chartModal");
            modal.innerHTML = "";  // Limpar conteúdo anterior
            modal.appendChild(graficoDiv);

            // Dados para o novo gráfico de pizza
            var dadosGrafico = {
                labels: ["Faltas", "Presença", "Aulas Restantes"],
                datasets: [{
                    data: [faltas, horasFeitas, horasCompleta],
                    backgroundColor: [
                        'rgb(214, 12, 12)',
                        'rgb(8, 6, 137)',
                        'rgb(11, 223, 46)',
                        // Adicione mais cores conforme necessário
                    ],
                    borderWidth: 1
                }]
            };

            // Cria um novo gráfico de pizza
            var ctx = canvas.getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: dadosGrafico,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Criar e adicionar o botão Fechar
            var fecharButton = document.createElement("button");
            fecharButton.id = "fecharModal";
            fecharButton.textContent = "Fechar";
            fecharButton.onclick = function () {
                fecharModal();
            };
            modal.appendChild(fecharButton);

            // Exibir o modal
            modal.style.display = "block";
        }

        function openList_Aluno(id) {
            // Exibir o modal
            var modalListAluno = document.getElementById("TelaADM-List_Aluno-" + id);
            modalListAluno.style.display = "flex";
        }

        function fecharList_Aluno(id) {
            // Ocultar o modal
            var modalListAluno = document.getElementById("TelaADM-List_Aluno-" + id);
            modalListAluno.style.display = "none";
        }

        function fecharModal() {
            var modal = document.getElementById("chartModal");
            modal.style.display = "none";
        }
    </script>

    <div id="chartModal">
        <div id="grafico">
            <canvas id="graficoPizza"></canvas>
        </div>
        <div id="Total">
            <p>Total de aulas:</p>
            <p id="totalAulas"></p>
        </div>
        <button onclick="fecharModal()" id="fecharModal">Fechar</button>
    </div>

    <?php

    $x = 0;
    
    foreach($materias as $id) {
        echo "<div class=\"TelaADM-List_Aluno\" id=\"TelaADM-List_Aluno-$id\">";
        echo "<button onclick=\"fecharList_Aluno($id)\" id=\"fecharModal\">Fechar</button>";
        echo "<table>";
        echo "    <thead>";
        echo "        <tr>";
        echo "            <th>Dia</th>";
        echo "            <th>Duração</th>";
        echo "            <th>Permanência</th>";
        echo "            <th>Presença</th>";
        echo "        </tr>";
        echo "    </thead>";
        echo "    <tbody>";
    
    
        $banco = new banco();
        $aulas_ministradas = [];
    

        $stmt = $banco->getConexao()->prepare("SELECT Hora_inicial, time_format(SEC_TO_TIME(TIME_TO_SEC(Hora_inicial) + TIME_TO_SEC(Tempo_aula)), '%H:%i:%s') as Hora_final, qtde_aulas, dia_semana, data_inicio, data_fim from materia WHERE id_materia = ?");
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        $result = $stmt->get_result();

        $total_aulas = 0;

        if ($result->num_rows > 0) {
            $materia = $result->fetch_assoc();
            $inicio = new DateTime($materia["data_inicio"]);
            $fim = new DateTime($materia["data_fim"]);
            $atual = $inicio;

            while ($atual <= $fim) {
                if (intval($atual->format('N')) % 7 == intval($materia["dia_semana"])) {
                    if ($atual <= new DateTime())
                        $total_aulas++;
            
                    $dia = [
                        "inicio" => $materia["Hora_inicial"],
                        "fim-esperado" => $materia["Hora_final"],
                        "fim-real" => $materia["Hora_final"],
                        "permanencia" => 0,
                        "presenca" => 0,
                        "aulas" => $materia["qtde_aulas"],
                        "cancelada" => false
                    ];
            
                    $aulas_ministradas[$atual->format("Y-m-d")] = $dia;
                }
                
                $atual->modify("+1 day");
            }

            $total_aulas *= $materia["qtde_aulas"];
        }

        $cancelada = false;

        $stmt->free_result();

        $stmt = $banco->getConexao()->prepare("SELECT tipo, data_hora FROM aulas_interrompidas WHERE id_materia = ?");
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $datahora = new DateTime($row["data_hora"]);
                $data = $datahora->format("Y-m-d");

                if ($row["tipo"] == 0) {
                    // Aula cancelada
                    if (isset($aulas_ministradas[$data])) {
                        $aulas_ministradas[$data]["presenca"] = $aulas_ministradas[$data]["aulas"];
                        $aulas_ministradas[$data]["fim-real"] = $aulas_ministradas[$data]["inicio"];
                        $aulas_ministradas[$data]["cancelada"] = true;
                    }
                } elseif ($row["tipo"] == 1) {
                    // Aula encerrada mais cedo
                    if (isset($aulas_ministradas[$data])) {
                        $aulas_ministradas[$data]["fim-real"] = $datahora->format("H:i:s");
                    }
                }
            }
        }

        // $stmt->free_result();

        // $stmt = $banco->getConexao()->prepare("SELECT data_hora FROM aulas_interrompidas WHERE id_materia = ?");
        // $stmt->bind_param("i", $id);

        // $stmt->execute();
        // $result = $stmt->get_result();

        // if ($result->num_rows > 0) {
        //     while ($row = $result->fetch_assoc()) {
        //         $datahora = new DateTime($row["data_hora"]);
        //         if (isset($aulas_ministradas[$datahora->format("Y-m-d")])) {
        //             $aulas_ministradas[$datahora->format("Y-m-d")]["fim"] = $datahora->format("H:i:s");
        //         }
        //     }
        // }

        // $stmt-> free_result(); 




        $stmt = $banco->getConexao()->prepare("CALL GetChamada(?, ?)");
        $stmt->bind_param("ii", $alunoRA, $id);

        $stmt->execute();
        $result = $stmt->get_result();

        $firstRow = true;
        $lastDate = null;
        $lastTimeIn = null;
        $totalTimeSeconds = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $datetimeString = $row["data_hora"];
                $tipo = $row["tipo"];

                $datetime = new DateTime($datetimeString);
                $data = $datetime->format('Y-m-d');

                if ($firstRow || $data != $lastDate) {
                    if (!$firstRow) {
                        if (isset($aulas_ministradas[$lastDate])) {
                            if($aulas_ministradas[$lastDate]["cancelada"]) {
                                $aulas_ministradas[$lastDate]["permanencia"] = 9999999999999;
                                continue;
                            }
                            $aulas_ministradas[$lastDate]["permanencia"] = $totalTimeSeconds;
                        }
                    }
                    $lastDate = $data;
                    $lastTimeIn = null;
                    $totalTimeSeconds = 0;
                    $firstRow = false;
                }

                if ($tipo == 1) {
                    $lastTimeIn = $datetime;
                } elseif ($tipo == 0 && $lastTimeIn !== null) {
                    $interval = $lastTimeIn->diff($datetime);
                    $totalTimeSeconds += $interval->s;
                    $totalTimeSeconds += $interval->i * 60;
                    $totalTimeSeconds += $interval->h * 3600;
                }
            }
            if ($lastDate !== null) {
                if (isset($aulas_ministradas[$data])) {
                    $aulas_ministradas[$data]["permanencia"] = $totalTimeSeconds;
                }
            }
        }

        $stmt->free_result();

        $tAulas = 0;
        $tPres = 0;

        foreach ($aulas_ministradas as $data => $aula) {
            $i = new DateTime('2023-01-01 ' . $aula["inicio"]);
            $f = new DateTime('2023-01-01 ' . $aula["fim-real"]);
            $dura = $i->diff($f);

            $totalMinutes = $dura->days * 24 * 60 + $dura->h * 60 + $dura->i;
            $presH = floor($aula["permanencia"] / 3600);
            $presM = floor(($aula["permanencia"] % 3600) / 60);
            $duraS = $dura->s + $dura->i * 60 + $dura->h * 3600;
            if($aula["permanencia"] >= $duraS) {
                $prcnt = 1;
            } else if($aula["permanencia"] <= 0) {
                $prcnt = 0;
            } else {
                $prcnt = $aula["permanencia"] / $duraS;
            }
            $valor = 100 / ($aula["aulas"] + 1);
            $presencas = (intval($prcnt) === 1 ? $aula["aulas"] : floor($prcnt * 100 / $valor));

            $presencas = $presencas > $aula["aulas"] ? $aula["aulas"] : $presencas;

            $tPres += $presencas;
            $tAulas += $aula["aulas"];

            echo '<tr>';
            echo '<td><input type="date" value="' . $data . '" readonly></td>';
            echo '<td><input type="time" value="' . sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60) . '" readonly></td>';
            echo '<td><input type="text" value="' . sprintf("%02d:%02d", $presH, $presM) . '" readonly></td>';
            echo '<td>' . $presencas . '</td>';
            echo '</tr>';
        }
        echo "        </tbody>";
        echo "    </table>";
        echo "</div>";

        echo "<script>";
        echo "var linha = document.getElementsByClassName(\"materia\")[$x];";
        echo "linha.childNodes[1].innerHTML = " . intval($total_aulas - $tPres) . ";";
        echo "linha.childNodes[2].innerHTML = " . intval($total_aulas) . ";";
        echo "linha.childNodes[3].innerHTML = " . intval($tAulas) . ";";
        echo "</script>";

        $x++;
    }
    ?>
            

</body>

</html>
