<?php
    var_dump($_POST); // ← Adicione isso aqui
    require_once __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    switch($action){
        case('cadastraAluno'):
            $aluno = new Aluno();
            CadastraAluno($action);
            break;
    }
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
    
    function CadastraAluno($action){
        $nomeAluno = $_POST['nomeAluno'] ?? '';
        $cpfAluno = $_POST['cpfAluno'] ?? '';
        $dataNascimentoAluno = $_POST['dataNascimentoAluno'] ?? '';
        $telefoneAluno = $_POST['telefoneAluno'] ?? '';
        $emailAluno = $_POST['emailAluno'] ?? '';
        global $pdo, $checarAlunoRepetido, $inserirAluno;

            if(!alunoRepetido($pdo,$checarAlunoRepetido,$nomeAluno)){
                cadastrandoAluno($pdo,$inserirAluno,$nomeAluno,$cpfAluno,$dataNascimentoAluno,$telefoneAluno,$emailAluno);
                header("Location: ../HTML\Gerenciar.html");
                exit;
            }else{
                header("Location: ../HTML/Gerenciar.html");
                exit;
            }
        }
        function alunoRepetido($pdo,$checarAlunoRepetido,$nomeAluno){
            $stmt = $pdo ->prepare($checarAlunoRepetido);
            $stmt -> execute([$nomeAluno]);
            return $stmt ->fetchColumn() > 0;
        }
        function cadastrandoAluno($pdo,$inserirAluno,$nomeAluno,$cpfAluno,$dataNascimentoAluno,$telefoneAluno,$emailAluno){
            $stmt = $pdo->prepare($inserirAluno);
            $stmt->execute([$nomeAluno,$cpfAluno,$dataNascimentoAluno,$telefoneAluno,$emailAluno]);
        } 
?>