<?php
    try{
        $pdo = new PDO ('mysql:host=localhost;dbname=academia', 'root', '');
    }catch (PDOException $e){
        echo 'error'. $e->getMessage();
    }

    $inserirUsuario = "INSERT INTO usuarios (usuario, email, senha) VALUES (?,?,?)";
    $checarRepetido = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
    $checarLogin = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ?";
?>