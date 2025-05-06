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
        global $checarLogin;
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
            validarLogin($usuario, $senha, $checarLogin);
    }
    function Cadastro($action){
            $usuario = $_POST['usuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
            global $pdo, $checarRepetido, $inserirClienteBanco;
            inserirCliente($action, $usuario, $email, $senha, $pdo, $checarRepetido, $inserirClienteBanco);
        
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
                header("Location: ../HTML/Login.html?");
                exit;
            };
        }
            $stmt = $pdo ->prepare ($inserirClienteBanco);
            $stmt -> execute([$usuario, $senha]);
            header("Location: ../HTML/Login.html");
            echo "<span> Usuário cadastrado com sucesso</span>";
            
        }
        function validarLogin($usuario,$senha,$checarLogin){
            global $pdo;
            $stmt = $pdo -> prepare($checarLogin);
            $stmt -> execute([$usuario, $senha]);
            $resultado = $stmt -> fetch();
            if($resultado !== false){
                header("Location: ../HTML/Gerenciar.html");
                exit;
            }else{
                header("Location: ../HTML/Login.html?");
                exit;
            }
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