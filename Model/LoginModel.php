<?php
    try{
        $pdo = new PDO ('mysql:host=localhost;dbname=academia', 'root', '');
    }catch (PDOException $e){
        echo 'error'. $e->getMessage();
    }

    /* Login */
    $inserirUsuario = "INSERT INTO usuarios (usuario, email, senha) VALUES (?,?,?)";
    $checarRepetido = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
    $checarLogin = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ?";

    /* Gerenciar */
    $inserirAluno = "INSERT INTO alunos (nome,cpf,data_nascimento,telefone,email) VALUES (?,?,?,?,?)";
    $checarAlunoRepetido = "SELECT COUNT(*) FROM alunos WHERE nome = ?";
    $selecionarTodosAlunos = "SELECT * FROM alunos";

?>