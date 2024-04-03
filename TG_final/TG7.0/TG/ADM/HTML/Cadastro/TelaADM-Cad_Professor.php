<?php
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
    <title>Cadastro de Professor</title>
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
        <form action="../../PHP/CadastroProfessor.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nomeProfessor" name="nomeProfessor" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="emailProfessor" name="emailProfessor" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estadoProfessor" name="estadoProfessor" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidadeProfessor" name="cidadeProfessor" required>
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairroProfessor" name="bairroProfessor" required>
            </div>
            <div class="form-group">
                <label for="rua">Rua:</label>
                <input type="text" id="ruaProfessor" name="ruaProfessor" required>
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cepProfessor" name="cepProfessor" required>
            </div>
            <div class="form-group">
                <label for="RA">RA:</label>
                <input type="text" id="RAProfessor" name="RAProfessor" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senhaProfessor" name="senhaProfessor" required>
            </div>
            <div class="form-group">
                <label for="confirmasenha">Confirmar Senha:</label>
                <input type="password" id="confirmasenhaProfessor" name="confirmasenhaProfessor" required>
            </div>
            <button type="submit">Cadastrar</button>
            <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        </form>
    </main>
    <script>
    const cepInput = document.getElementById("cepProfessor");
const addressInput = document.getElementById("ruaProfessor");
const cityInput = document.getElementById("cidadeProfessor");
const neighborhoodInput = document.getElementById("bairroProfessor");
const regionInput = document.getElementById("estadoProfessor");

cepInput.addEventListener("keypress", (e) => {
  const onlyNumbers = /[0-9]|\./;
  const key = String.fromCharCode(e.keyCode);

  // allow only numbers
  if (!onlyNumbers.test(key)) {
    e.preventDefault();
    return;
  }
});

cepInput.addEventListener("keyup", (e) => {
  const inputValue = e.target.value;

  //   Check if we have a CEP
  if (inputValue.length === 8) {
    getAddress(inputValue);
  }
});

const getAddress = async (cep) => {
  

  cepInput.blur();

  const apiUrl = `https://viacep.com.br/ws/${cep}/json/`;

  const response = await fetch(apiUrl);

  const data = await response.json();

  // Show error and reset form
  if (data.erro === "true") {
    if (!addressInput.hasAttribute("disabled")) {
      toggleDisabled();
    }

    addressForm.reset();
    toggleLoader();
    toggleMessage("CEP Inválido, tente novamente.");
    return;
  }

  addressInput.value = data.logradouro;
  cityInput.value = data.localidade;
  neighborhoodInput.value = data.bairro;
  regionInput.value = data.uf;
};
</script>
</body>

</html>