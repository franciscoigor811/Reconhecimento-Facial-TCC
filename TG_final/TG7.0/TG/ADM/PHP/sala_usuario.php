<?php
include("banco.php");

class Materia {
    private $id_materia;
    private $Nome_materia;
    private $Numero_sala;
    private $Numero_bloco;
    private $Hora_inicial;
    private $Tempo_aula;
    private $qtde_aulas;
    private $dia_semana;
    private $id_professor;
    private $banco;

    function __construct() {
        $this->banco = new banco();
    }

    public function getIdMateria() {
        return $this->id_materia;
    }

    public function setIdMateria($id_materia) {
        $this->id_materia = $id_materia;
    }

    public function getNomeMateria() {
        return $this->Nome_materia;
    }

    public function setNomeMateria($Nome_materia) {
        $this->Nome_materia = $Nome_materia;
    }

    public function getNumeroSala() {
        return $this->Numero_sala;
    }

    public function setNumeroSala($Numero_sala) {
        $this->Numero_sala = $Numero_sala;
    }

    public function getNumeroBloco() {
        return $this->Numero_bloco;
    }

    public function setNumeroBloco($Numero_bloco) {
        $this->Numero_bloco = $Numero_bloco;
    }

    public function getHoraInicial() {
        return $this->Hora_inicial;
    }

    public function setHoraInicial($Hora_inicial) {
        $this->Hora_inicial = $Hora_inicial;
    }

    public function getTempoAula() {
        return $this->Tempo_aula;
    }

    public function setTempoAula($Tempo_aula) {
        $this->Tempo_aula = $Tempo_aula;
    }

    public function getQtdeAulas() {
        return $this->qtde_aulas;
    }

    public function setQtdeAulas($qtde_aulas) {
        $this->qtde_aulas = $qtde_aulas;
    }

    public function getDiaSemana() {
        return $this->dia_semana;
    }

    public function setDiaSemana($dia_semana) {
        $this->dia_semana = $dia_semana;
    }

    public function getIdProfessor() {
        return $this->id_professor;
    }

    public function setIdProfessor($id_professor) {
        $this->id_professor = $id_professor;
    }

    public function cadastrarMateria() {
        $stmt = $this->banco->getConexao()->prepare("INSERT INTO materia (id_materia,Nome_materia, Numero_sala, Numero_bloco, Hora_inicial, Tempo_aula, qtde_aulas, dia_semana, id_professor) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssssssi", $this->id_materia, $this->Nome_materia, $this->Numero_sala, $this->Numero_bloco, $this->Hora_inicial, $this->Tempo_aula, $this->qtde_aulas, $this->dia_semana, $this->id_professor);

        return $stmt->execute();
    }

    public function atualizarMateria() {
        $stmt = $this->banco->getConexao()->prepare("UPDATE materia SET Nome_materia = ?, Numero_sala = ?, Numero_bloco = ?, Hora_inicial = ?, Tempo_aula = ?, qtde_aulas = ?, dia_semana = ?, id_professor = ? WHERE id_materia = ?");

        $stmt->bind_param("ssssssiii", $this->Nome_materia, $this->Numero_sala, $this->Numero_bloco, $this->Hora_inicial, $this->Tempo_aula, $this->qtde_aulas, $this->dia_semana, $this->id_professor, $this->id_materia);

        return $stmt->execute();
    }

    public function excluirMateria() {
        $stmt = $this->banco->getConexao()->prepare("DELETE FROM materia WHERE id_materia = ?");
        $stmt->bind_param("i", $this->id_materia);
        return $stmt->execute();
    }

    public function listarMaterias() {
        $stmt = $this->banco->getConexao()->prepare("SELECT * FROM materia");
        $stmt->execute();

        $result = $stmt->get_result();
        
        $vetorMaterias = array();
        $i = 0;

        while ($linha = mysqli_fetch_object($result)) {
            $vetorMaterias[$i] = new Materia();
            $vetorMaterias[$i]->setIdMateria($linha->id_materia);
            $vetorMaterias[$i]->setNomeMateria($linha->Nome_materia);
            $vetorMaterias[$i]->setNumeroSala($linha->Numero_sala);
            $vetorMaterias[$i]->setNumeroBloco($linha->Numero_bloco);
            $vetorMaterias[$i]->setHoraInicial($linha->Hora_inicial);
            $vetorMaterias[$i]->setTempoAula($linha->Tempo_aula);
            $vetorMaterias[$i]->setQtdeAulas($linha->qtde_aulas);
            $vetorMaterias[$i]->setDiaSemana($linha->dia_semana);
            $vetorMaterias[$i]->setIdProfessor($linha->id_professor);
            $i++;
        }

        return $vetorMaterias;
    }
}
?>
