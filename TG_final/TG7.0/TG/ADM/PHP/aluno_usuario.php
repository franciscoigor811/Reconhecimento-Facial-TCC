<?php
include("banco.php");

class Usuario {
    private $id;
    private $ra;
    private $nome;
    private $email;
    private $senha;
    private $cep;
    private $rua;
    private $bairro;
    private $cidade;
    private $estado;
    private $banco;

    function __construct() {
        $this->banco = new banco();
    }

    public function login($email, $senha) {
        $stmt = $this->banco->getConexao()->prepare("SELECT * FROM aluno WHERE email = ? AND senha = ?");
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        
        $result = $stmt->get_result();

        while ($linha = $result->fetch_object()) {   
            $this->setId($linha->id);
            $this->setRa($linha->ra);
            $this->setNome($linha->nome);
            $this->setEmail($linha->email);
            $this->setSenha($linha->senha);
            $this->setCep($linha->cep);
            $this->setRua($linha->rua);
            $this->setBairro($linha->bairro);
            $this->setCidade($linha->cidade);
            $this->setEstado($linha->estado);
        }

        return $this;    
    }
    public function cadastrar($nome, $email, $estado, $cidade, $bairro, $rua, $cep, $ra, $senha) {
        $stmt = $this->banco->getConexao()->prepare("INSERT INTO aluno (nome, email, estado, cidade, bairro, rua, cep, ra, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssis", $nome, $email, $estado, $cidade, $bairro, $rua, $cep, $ra, $senha);
    
        return $stmt->execute();
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getRa() {
        return $this->ra;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getRua() {
        return $this->rua;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setRa($ra) {
        $this->ra = $ra;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }
}
?>
