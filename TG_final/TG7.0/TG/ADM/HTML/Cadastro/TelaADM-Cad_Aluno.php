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
    <title>Cadastro de Aluno</title>
    <link rel="stylesheet" href="../../CSS/TelaADM.css">
    <link rel="icon" href="seu-icone.png" type="image/png">
</head>

<body>
    <header>
        <h1>Cadastro de Aluno</h1>
        <div>
            <p><?php echo $logindata["RA"];?></p>
            <a href="http://127.0.0.1/TG_final/TG7.0/TG/ADM/PHP/logout.php"><button type="button">Sair</button></a> 
        </div>
    </header>
    <main>
        <form action="../../PHP/CadastroAluno.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nomeAluno" name="nomeAluno" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="emailAluno" name="emailAluno" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estadoAluno" name="estadoAluno" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidadeAluno" name="cidadeAluno" required>
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairroAluno" name="bairroAluno" required>
            </div>
            <div class="form-group">
                <label for="rua">Rua:</label>
                <input type="text" id="ruaAluno" name="ruaAluno" required>
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cepAluno" name="cepAluno" required minlength="8" maxlength="8">
            </div>
            <div class="form-group">
                <label for="RA">RA:</label>
                <input type="text" id="RAAluno" name="RAAluno" inputmode="numeric" pattern="[0-9]*" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senhaAluno" name="senhaAluno" required>
            </div>
            <div class="form-group">
                <label for="confirmasenha">Confirmar Senha:</label>
                <input type="password" id="confirmasenhaAluno" name="confirmasenhaAluno" required>
            </div>
            <div class="form-group">
                <p style="font-size:20px;"> Foto </p>
                <input style="color:#9e9e9e;" name="FOTOA" type="file" id="FOTOA" aria-required="true" class="full-width">
            </div>
            <button type="submit">Cadastrar</button>
            <a href="../ADM-Menu.php"><button type="button">Voltar</button></a>
        </form>
    </main>

    <!-- <script>
        document.getElementById('cepAluno').addEventListener('input', function() {
      const cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos

      if (cep.length === 8) {
        const apiUrl = `https://h-apigateway.conectagov.estaleiro.serpro.gov.br/api-cep/v1/consulta/cep/${cep}`;

        // Faz a requisição à API
        fetch(apiUrl)
          .then(response => response.json())
          .then(data => {
            document.getElementById('estadoAluno').value = data.uf || '';
            document.getElementById('cidadeAluno').value = data.cidade || '';
            document.getElementById('bairroAluno').value = data.bairro || '';
            document.getElementById('ruaAluno').value = data.endereco || '';
          })
          .catch(error => console.error('Erro ao consultar o CEP:', error));
      }
    });


    document.getElementById('cepAluno').addEventListener('input', function() {
      const cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos

      if (cep.length === 8) {
        // Obtenha o token de acesso OAuth2
        fetch('https://h-apigateway.conectagov.estaleiro.serpro.gov.br/oauth2/jwt-token', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'Basic ' + btoa('seuClientId:seuClientSecret')
          },
          body: 'grant_type=client_credentials'
        })
        .then(response => response.json())
        .then(tokenData => {
          // Use o token de acesso para fazer a chamada à API
          const apiUrl = `https://h-apigateway.conectagov.estaleiro.serpro.gov.br/api-cep/v1/consulta/cep/${cep}`;

          fetch(apiUrl, {
            headers: {
              'Authorization': `Bearer ${tokenData.access_token}`
            }
          })
          .then(response => response.json())
          .then(data => {
            document.getElementById('estadoAluno').value = data.uf || '';
            document.getElementById('cidadeAluno').value = data.cidade || '';
            document.getElementById('bairroAluno').value = data.bairro || '';
            document.getElementById('ruaAluno').value = data.endereco || '';
          })
          .catch(error => console.error('Erro ao consultar o CEP:', error));
        })
        .catch(error => console.error('Erro ao obter o token de acesso:', error));
      }
    });
    </script> -->
<script>
    const cepInput = document.getElementById("cepAluno");
const addressInput = document.getElementById("ruaAluno");
const cityInput = document.getElementById("cidadeAluno");
const neighborhoodInput = document.getElementById("bairroAluno");
const regionInput = document.getElementById("estadoAluno");

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