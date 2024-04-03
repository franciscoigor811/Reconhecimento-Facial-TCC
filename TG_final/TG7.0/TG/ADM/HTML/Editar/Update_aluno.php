<?php
require("../../PHP/redirect.php");
include("../../PHP/banco.php");

$nomeProfessor = isset($_POST["nomeAluno"]) ? $_POST["nomeAluno"] : '';
$emailProfessor = isset($_POST["emailAluno"]) ? $_POST["emailAluno"] : '';
$estadoProfessor = isset($_POST["estadoAluno"]) ? $_POST["estadoAluno"] : '';
$cidadeProfessor = isset($_POST["cidadeAluno"]) ? $_POST["cidadeAluno"] : '';
$bairroProfessor = isset($_POST["bairroAluno"]) ? $_POST["bairroAluno"] : '';
$ruaProfessor = isset($_POST["ruaAluno"]) ? $_POST["ruaAluno"] : '';
$cepProfessor = isset($_POST["cepAluno"]) ? $_POST["cepAluno"] : '';
$RA = isset($_POST["RA"]) ? $_POST["RA"] : 0;

$ativo = $_POST['ativo'] == "true" ? 1 : 0;

$banco = new Banco();
if(isset($_POST["password"]) && isset($_POST["confirmapassword"]) && $_POST["password"] === $_POST["confirmapassword"] && $_POST["password"] != "") {  
    $password = $_POST["password"];

    $stmt = $banco->getConexao()->prepare("UPDATE `aluno` SET `Nome` = ?, `Email` = ?, `Senha` = ?, `CEP` = ?, `Rua` = ?, `Bairro` = ?, `Cidade` = ?, `Estado`  = ?, ativo = ? WHERE `aluno`.`RA` = ?");
    $stmt->bind_param("ssssssssii", $nomeProfessor, $emailProfessor, $password, $cepProfessor, $ruaProfessor, $bairroProfessor, $cidadeProfessor, $estadoProfessor, $ativo, $RA);
} else {
    $stmt = $banco->getConexao()->prepare("UPDATE `aluno` SET `Nome` = ?, `Email` = ?, `CEP` = ?, `Rua` = ?, `Bairro` = ?, `Cidade` = ?, `Estado` = ?, ativo = ? WHERE `aluno`.`RA` = ?");
    $stmt->bind_param("sssssssii", $nomeProfessor, $emailProfessor, $cepProfessor, $ruaProfessor, $bairroProfessor, $cidadeProfessor, $estadoProfessor, $ativo, $RA);
}



$stmt->execute();

redirect("TelaADM-Edit_Aluno.php");




?>