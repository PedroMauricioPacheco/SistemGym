<?php
    require_once __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    class Aluno{
        private $nome;
        private $cpf;
        private $dataNascimento;
        private $telefone;
        private $email;

        public function __construct($nome,$cpf,$dataNascimento,$telefone,$email)
        {
            $this->nome = $nome;
            $this->cpf = $cpf;
            $this->dataNascimento =$dataNascimento;
            $this->telefone = $telefone;
            $this->email = $email;
        }

        public function getNome(){
            return $this->nome;
        }
        public function getCpf(){
            return $this->cpf;
        }
        public function getDataNascimento(){
            return $this->dataNascimento;
        }
        public function getTelefone(){
            return $this->telefone;
        }
        public function getEmail(){
            return $this->email;
        }
        public function setNome($nome){
            $this->nome = $nome;
        }
        public function setCpf($cpf){
            $this->cpf = $cpf;
        }
        public function setDataNascimento($dataNascimento){
            $this->dataNascimento = $dataNascimento;
        }
        public function setTelefone($telefone){
            $this->telefone = $telefone;
        }
        public function setEmail($email){
            $this->email = $email;
        }
    } 
    
    
        
?>