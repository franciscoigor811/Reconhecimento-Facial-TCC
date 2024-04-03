<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Presença dos Alunos</title>
    <link rel="stylesheet" href="../Professor/CSS/AlunosPresentes.css">
</head>

<body>
    <header>
        <h1>Tabela de Presença dos Alunos</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="../MenuLogin.html"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>

        <a href="VisualizarFaltas.php"><button type="button">Voltar</button></a>
        <table>
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Duração</th>
                    <th>Permanência</th>
                    <th>Presença</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("../ADM/PHP/banco.php");
                $banco = new banco();

                $alunoRA = isset($_POST['RA']) ? $_POST['RA'] : '';
                $materiaID = isset($_POST['ID']) ? $_POST['ID'] : '';

                $aulas_ministradas = [];

                $stmt = $banco->getConexao()->prepare("CALL GetChamada(?, ?)");
                $stmt2 = $banco->getConexao()->prepare("SELECT Hora_inicial, time_format(SEC_TO_TIME(TIME_TO_SEC(Hora_inicial) + TIME_TO_SEC(Tempo_aula)), '%H:%i:%s') as Hora_final, qtde_aulas, dia_semana, data_inicio, data_fim from materia WHERE id_materia = ?");
                $stmt3 = $banco->getConexao()->prepare("SELECT data_hora FROM aulas_interrompidas WHERE id_materia = ?");

                $stmt->bind_param("ii", $alunoRA, $materiaID);
                $stmt2->bind_param("i", $materiaID);
                $stmt3->bind_param("i", $materiaID);

              
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                if ($result2->num_rows > 0) {
                    $materia = $result2->fetch_assoc();
                    $inicio = new DateTime($materia["data_inicio"]);
                    $fim = new DateTime($materia["data_fim"]);
                    $atual = $inicio;

                    while ($atual <= $fim) {
                        $dia = [
                            "inicio" => $materia["Hora_inicial"],
                            "fim" => $materia["Hora_final"],
                            "permanencia" => 0,
                            "presenca" => 0,
                            "aulas" => $materia["qtde_aulas"]
                        ];

                        $aulas_ministradas[$atual->format("Y-m-d")] = $dia;
                        $atual->modify("+7 days");
                    }
                }

                $stmt2->free_result();

                $stmt3->execute();
                $result3 = $stmt3->get_result();

                

                if ($result3->num_rows > 0) {
                    while ($row = $result3->fetch_assoc()) {
                        $datahora = new DateTime($row["data_hora"]);
                        if (isset($aulas_ministradas[$datahora->format("Y-m-d")])) {
                            $aulas_ministradas[$datahora->format("Y-m-d")]["fim"] = $datahora->format("H:i:s");
                        }
                    }
                }

                $stmt3-> free_result(); 

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
                                if (isset($aulas_ministradas[$data])) {
                                    $aulas_ministradas[$data]["permanencia"] = $totalTimeSeconds;
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
                } else {
                    echo "Nenhum resultado encontrado.";
                }

                $banco->getConexao()->close();

                foreach ($aulas_ministradas as $data => $aula) {
                    $i = new DateTime($aula["inicio"]);
                    $f = new DateTime($aula["fim"]);
                    $dura = $i->diff($f);
                    $presH = $aula["presenca"] / 3600;
                    $presM = $aula["presenca"] / 60;
                    $duraS = $dura->s + $dura->i * 60 + $dura->h * 3600;
                    $prcnt = $aula["presenca"] == 0 ? 0 :$duraS / $aula["presenca"];
                    $valor = 100 / $aula["aulas"] + 1;
                    $presencas = intval($prcnt) === 1 ? $aula["aulas"] : floor($prcnt / $valor);

                    echo $i -> format("H:i:s");; 
                    echo $f -> format("H:i:s");
                    echo $dura -> format("H:i:s");;
                    echo $presH;
                    echo $presM;
                    echo $duraS;
                    echo $prcnt;
                    echo $valor;
                    echo $presencas;

                    echo '<tr>';
                    echo '<td><input type="date" value="' . $data . '" readonly></td>';
                    echo '<td><input type="time" value="' . $dura->format("H:i:s") . '" readonly></td>';
                    echo '<td><input type="text" value="' . sprintf("%02d:%02d", $presH, $presM) . '" readonly></td>';
                    echo '<td>' . $presencas . '</td>';
                    echo '</tr>';
                }
                ?>
                <!-- <tr>
                    <td><input type="date" value="2023-10-10" readonly></td>
                    <td><input type="time" value="08:30" readonly></td>
                    <td><input type="time" value="16:45" readonly></td>
                    <td>4</td>
                </tr>
                <tr>
                    <td><input type="date" value="2023-10-09" readonly></td>
                    <td><input type="time" value="08:30" readonly></td>
                    <td><input type="time" value="16:45" readonly></td>
                    <td>4</td>
                </tr>
                <tr>
                    <td><input type="date" value="2023-10-08" readonly></td>
                    <td><input type="time" value="08:30" readonly></td>
                    <td><input type="time" value="16:45" readonly></td>
                    <td>4</td>
                </tr> -->
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>

    </main>
</body>

</html>