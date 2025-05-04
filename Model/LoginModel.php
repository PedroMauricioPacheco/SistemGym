<?php
    try{
        $pdo = new PDO ('mysql:host=localhost;dbname=academia', 'root', '');
    }catch (PDOException $e){
        echo 'error'. $e->getMessage();
    }

    $inserirCliente = "INSERT INTO usuarios (usuarios, senha) VALUES (?,?)";
    $checarRepetido = "SELECT COUNT(*) FROM usuarios WHERE usuarios = ?";
?>