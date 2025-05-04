<?php
    include __DIR__ . '/../Model/LoginModel.php';

    $action = $_POST['action'] ?? '';
    
    function Login($action){
        if($action=='login'){
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
        }
    }
    function Cadastro($action){
        if($action=='cadastro'){
            $usuario = $_POST['isuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
        }
    }
    function inserirCliente($action,$usuario, $email, $senha, $pdo, $checarRepetido, $inserirCliente){
        if ($action =='cadastrar'){
            if (CampoVazio($usuario, $senha, $email)){
                echo "<span> Preenche todos os campos </span>";
                return;
            }
            $stmt = $pdo ->prepare($checarRepetido);
            $stmt -> execute([$usuario]);
            $checouRepetido = $stmt ->fetchColumn();

            if($checouRepetido >0){
                echo "<span> Usuário já cadastrado </span>";
            };
        }
            else{
                $inserirCliente;
                $stmt = $pdo ->prepare ($inserirCliente);
                $stmt -> execute([$usuario, $senha]);
            }
        }
    function CampoVazio(...$campos){
        foreach($campos as $campo){
            if(trim($campo)==''){
                return true;
            }
        }
        return false;
    }
?>