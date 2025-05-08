<?php
    include __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    switch($action){
        case('cadastraAluno');
            CadastraAluno($action);
            break;
    }

    function CadastraAluno($action){
        $nomeAluno = $_POST['nomeAluno'] ?? '';
        $cpfAluno = $_POST['cpfAluno'] ?? '';
        $dataNascimentoAluno = $_POST['dataNascimentoAluno'] ?? '';
        $telefoneAluno = $_POST['telefoneAluno'] ?? '';
        $emailAluno = $_POST['emailAluno'] ?? '';
        var_dump($_POST);
        exit;
        global $pdo, $checarAlunoRepetido, $inserirAluno;

        if(!alunoRepetido($pdo,$checarAlunoRepetido,$nomeAluno)){
            cadastrandoAluno($pdo,$inserirAluno,$nomeAluno,$cpfAluno,$dataNascimentoAluno,$telefoneAluno,$emailAluno);
            header("Location: ..HTML/Gerenciar.html");
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