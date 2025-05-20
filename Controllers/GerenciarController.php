<?php
    var_dump($_POST); // ← Adicione isso aqui
    require_once __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    switch($action){
        case('cadastraAluno'):
            $aluno = new Aluno();
            $aluno -> CadastraAluno($action);
            break;
    }
    class Aluno{
        private $nome;
        private $cpf;
        private $dataNascimento;
        private $telefone;
        private $email;

        function getNome(){
            return $this->nome;
        }
        function getCpf(){
            return $this->cpf;
        }
        function getDataNascimento(){
            return $this->dataNascimento;
        }
        function getTelefone(){
            return $this->telefone;
        }
        function getEmail(){
            return $this->email;
        }
        function setNome($nome){
            $this->nome = $nome;
        }
        function setCpf($cpf){
            $this->cpf = $cpf;
        }
        function setDataNascimento($dataNascimento){
            $this->dataNascimento = $dataNascimento;
        }
        function setTelefone($telefone){
            $this->telefone = $telefone;
        }
        function setEmail($email){
            $this->email = $email;
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
    }
?>