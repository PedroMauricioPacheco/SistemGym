<?php
    include __DIR__ . '/../Model/LoginModel.php';
    
    $action = $_POST['action'] ?? '';

    switch($action){
        case 'login':
            login($action);
            break;
        case 'cadastro':
            cadastro($action);
            break;
    }
    
    function login($action){
        global $checarLogin;
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
            validarLogin($usuario, $senha, $checarLogin);
    }
    function cadastro($action){
            $usuario = $_POST['usuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
            global $pdo, $checarRepetido, $inserirUsuario;
            
            if(!usuarioRepetido($pdo,$checarRepetido,$usuario)){
                cadastrandoUsuario($pdo,$inserirUsuario,$usuario,$email,$senha);
            }
        
    }
    function usuarioRepetido($pdo,$checarRepetido,$usuario){
        $stmt = $pdo ->prepare($checarRepetido);
        $stmt -> execute([$usuario]);
        return $stmt ->fetchColumn() > 0;
    }
    function cadastrandoUsuario($pdo,$inserirUsuario,$usuario,$email,$senha){
        $stmt = $pdo->prepare($inserirUsuario);
        $stmt->execute([$usuario, $email, $senha]);
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
?>