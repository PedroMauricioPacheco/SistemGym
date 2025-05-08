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
            if(!validarLogin($usuario,$senha)){
                header("Location: ../HTML/Login.html");
            }else{
                header("Location: ../HTML/Home.html");
            }
    }
    function cadastro($action){
            $usuario = $_POST['usuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
            global $pdo, $checarRepetido, $inserirUsuario;
            
            if(!usuarioRepetido($pdo,$checarRepetido,$usuario)){
                cadastrandoUsuario($pdo,$inserirUsuario,$usuario,$email,$senha);
                header("Location: ../HTML/Login.html");
                exit;
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
        function validarLogin($usuario,$senha){
            global $pdo,$checarLogin;
            $stmt = $pdo -> prepare($checarLogin);
            $stmt -> execute([$usuario, $senha]);
            return $stmt -> rowCount() > 0;
        }
?>