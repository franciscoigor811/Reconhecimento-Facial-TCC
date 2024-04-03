<?php
include("../../PHP/banco.php");
$RA = isset($_GET["ra"]) ? $_GET["ra"] : 0;

$banco = new Banco();

    $stmt = $banco->getConexao()->prepare("DELETE FROM `aluno` WHERE `aluno`.`RA` = ?");
    $stmt->bind_param("i", $RA);




$stmt->execute();

?>