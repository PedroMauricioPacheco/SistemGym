<?php
try{
    $pdo = new PDO ('mysql:host=localhost;dbname=consultas_cep', 'root', '');
}catch (PDOException $e){
    echo 'error'. $e->$getMessage();
}

?>