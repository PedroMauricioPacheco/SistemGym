<?php
    print_r($_POST);
    exit;
    function Login(){
        $action = isset($_POST['login'])?($_POST['login']) : '';

        if($action=='login'){
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
        }
    }
    function Cadastro(){
        $action = isset($_POST['cadastro'])?($_POST['cadastro']) : '';

        if($action=='cadastro'){
            $usuario = $_POST['isuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
        }
    }
?>