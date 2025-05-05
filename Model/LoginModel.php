<?php
    try{
        $pdo = new PDO ('mysql:host=localhost;dbname=academia', 'root', '');
    }catch (PDOException $e){
        echo 'error'. $e->getMessage();
    }

    $inserirClienteBanco = "INSERT INTO usuarios (usuario, senha) VALUES (?,?)";
    $checarRepetido = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
?>