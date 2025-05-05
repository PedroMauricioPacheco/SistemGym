<?php
    include __DIR__ . '/../Model/LoginModel.php';

    $action = $_POST['action'] ?? '';

    switch($action){
        case 'login':
            Login($action);
            break;
        case 'cadastro':
            Cadastro($action);
            break;
    }
    
    function Login($action){
        if($action=='login'){
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
        }
    }
    function Cadastro($action){
        if($action=='cadastro'){
            $usuario = $_POST['usuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
            global $pdo, $checarRepetido, $inserirClienteBanco;
            inserirCliente($action, $usuario, $email, $senha, $pdo, $checarRepetido, $inserirClienteBanco);
        }
    }
    function inserirCliente($action,$usuario, $email, $senha, $pdo, $checarRepetido, $inserirClienteBanco){
        if ($action =='cadastro'){
            if (camposVazios($usuario, $email, $senha)) {
                echo "Preencha todos os campos";
                return;
            }
            $stmt = $pdo ->prepare($checarRepetido);
            $stmt -> execute([$usuario]);
            $checouRepetido = $stmt ->fetchColumn();

            if($checouRepetido > 0){
                echo "<span> Usuário já cadastrado </span>";
                return;
            };
        }
            $stmt = $pdo ->prepare ($inserirClienteBanco);
            $stmt -> execute([$usuario, $senha]);
            echo "<span> Usuário cadastrado com sucesso</span>";
            header("Location: ../HTML/Login.html");
            
        }
        function camposVazios(...$campos) {
            foreach ($campos as $campo) {
                if (trim($campo) === '') {
                    return true;
                }
            }
            return false;
        }
        
?>