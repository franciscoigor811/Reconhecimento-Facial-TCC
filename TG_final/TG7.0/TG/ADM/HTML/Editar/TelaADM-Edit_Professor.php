<?php
require("../../PHP/session_verify.php");
//include("../../PHP/banco.php");

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../MenuLogin.html';

if(RBAC(2, $referer)){
    $logindata = getLoginData($referer);
    $RA = isset($_GET["ra"]) ? $_GET["ra"] : redirect($referer);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar de Professor</title>
    <link rel="stylesheet" href="../../CSS/TelaADM.css">
    <link rel="icon" href="seu-icone.png" type="image/png">
</head>

<body>
    <header>
        <h1>Cadastro de Professor</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
    <form action="Update_professor.php" method="post">
            <?php
            $banco = new Banco();
            $stmt = $banco->getConexao()->prepare("SELECT * FROM `professor` where RA = ?");
            $stmt->bind_param("i", $RA);

            $stmt->execute();

            $result = $stmt->get_result();

            while ($linha = $result->fetch_object()) {
                //     echo '<tr>';
                //     echo '<td>' . $linha->Nome . '</td>';
                //     echo '<td>' . $linha->Email . '</td>';
                //     echo '<td>0' . $linha->RA . '</td>';
                //     echo '<td><a href="../Editar/TelaADM-Edit_Aluno.php?ra='.$linha->RA.'">Editar</a></td>';
                //     echo '</tr>';
                // }
                ?>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nomeProfessor" name="nomeProfessor" required value="<?php echo $linha->Nome; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="emailProfessor" name="emailProfessor"required value="<?php echo $linha->Email; ?>">
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estadoProfessor" name="estadoProfessor" required value="<?php echo $linha->Estado; ?>">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidadeProfessor" name="cidadeProfessor" required value="<?php echo $linha->Cidade; ?>">
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairroProfessor" name="bairroProfessor" required value="<?php echo $linha->Bairro; ?>">
            </div>
            <div class="form-group">
                <label for="rua">Rua:</label>
                <input type="text" id="ruaProfessor" name="ruaProfessor" required value="<?php echo $linha->Rua; ?>">
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cepProfessor" name="cepProfessor" required value="<?php echo $linha->CEP; ?>">
            </div>
            <div class="form-group">
                <label for="RA">RA:</label>
                <input type="text" id="RAProfessor" name="RAProfessor" inputmode="numeric" pattern="[0-9]*" readonly
                        value="<?php echo $linha->RA; ?>">
                    <input type="hidden" name="RA" value="<?php echo $linha->RA; ?>" hidden>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senhaProfessor" name="senhaProfessor" >
            </div>
            <div class="form-group">
                <label for="confirmasenha">Confirmar Senha:</label>
                <input type="password" id="confirmasenhaProfessor" name="confirmasenhaProfessor" >
            </div>

            <div class="form-group">
                    <label class="control-label">Conta ativa?</label>
                    <div>
                        <label>
                            <input type="hidden" name="ativo" value="false">
                            <?php
                            if ($linha->ativo == "1")
                                echo "<input class='checkbox' type='checkbox' name='ativo' id='ativo' value='true' checked onclick='muda()'>";
                            else
                                echo "<input class='checkbox' type='checkbox' name='ativo' id='ativo' onclick='muda()'>";
                            ?>
                            <span id="checkbox">Ativa</span>
                        </label>
                    </div>
                </div>
            
            <button type="submit">Salvar</button>
            
            <a href="../Lista/TelaADM-List_Professor.php"><button type="button">Voltar</button></a>
            <?php
            }
            ?>
        </form>
        <script>
            function muda() {
                var id = document.getElementById("checkbox").innerHTML;
                var c = document.getElementById("ativo");
                if (id == "Ativa") {
                    document.getElementById("checkbox").innerHTML = "Inativa";
                    c.setAttribute("value", "false");
                }
                if (id == "Inativa") {
                    document.getElementById("checkbox").innerHTML = "Ativa";
                    c.setAttribute("value", "true");
                }
            }
            function view() {
                var c = document.getElementById("ativo");
                var id = document.getElementById("checkbox")
                if (c.hasAttribute("checked") == true) {
                    id.innerHTML = "Ativa";
                } else {
                    id.innerHTML = "Inativa";
                }
            }
            window.onload = view();
        </script>
    </main>
</body>

</html>