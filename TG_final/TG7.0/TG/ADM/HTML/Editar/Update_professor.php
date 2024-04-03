<?php
require("../../PHP/redirect.php");
include("../../PHP/banco.php");

$nomeProfessor = isset($_POST["nomeProfessor"]) ? $_POST["nomeProfessor"] : '';
$emailProfessor = isset($_POST["emailProfessor"]) ? $_POST["emailProfessor"] : '';
$estadoProfessor = isset($_POST["estadoProfessor"]) ? $_POST["estadoProfessor"] : '';
$cidadeProfessor = isset($_POST["cidadeProfessor"]) ? $_POST["cidadeProfessor"] : '';
$bairroProfessor = isset($_POST["bairroProfessor"]) ? $_POST["bairroProfessor"] : '';
$ruaProfessor = isset($_POST["ruaProfessor"]) ? $_POST["ruaProfessor"] : '';
$cepProfessor = isset($_POST["cepProfessor"]) ? $_POST["cepProfessor"] : '';
$RA = isset($_POST["RA"]) ? $_POST["RA"] : 0;

$ativo = $_POST['ativo'] == "true" ? 1 : 0;

$banco = new Banco();
if(isset($_POST["password"]) && isset($_POST["confirmapassword"]) && $_POST["password"] === $_POST["confirmapassword"] && $_POST["password"] != "") {  
    $password = $_POST["password"];

    $stmt = $banco->getConexao()->prepare("UPDATE `professor` SET `Nome` = ?, `Email` = ?, `Senha` = ?, `CEP` = ?, `Rua` = ?, `Bairro` = ?, `Cidade` = ?, `Estado`  = ?, ativo = ? WHERE `professor`.`RA` = ?");
    $stmt->bind_param("ssssssssii", $nomeProfessor, $emailProfessor, $password, $cepProfessor, $ruaProfessor, $bairroProfessor, $cidadeProfessor, $estadoProfessor, $ativo, $RA);
} else {
    $stmt = $banco->getConexao()->prepare("UPDATE `professor` SET `Nome` = ?, `Email` = ?, `CEP` = ?, `Rua` = ?, `Bairro` = ?, `Cidade` = ?, `Estado` = ?, ativo = ? WHERE `professor`.`RA` = ?");
    $stmt->bind_param("sssssssii", $nomeProfessor, $emailProfessor, $cepProfessor, $ruaProfessor, $bairroProfessor, $cidadeProfessor, $estadoProfessor, $ativo, $RA);
}



$stmt->execute();

redirect("../Lista/TelaADM-List_Professor.php");




?>