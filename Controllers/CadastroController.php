<?php
    function CadastraAluno(){
        $action = isset($_POST['action'])? ($_POST['action']) : "";

        if ($action == 'cadastrar'){
            $nome = $_POST['nome']?? '';
            $cpf = $_POST['cpf']?? '';
            $data_nascimento = $_POST['data_nascimento']?? '';
            $telefone = $_POST['telefone']?? '';
            $email = $_POST['email']?? '';
        }
        return null;
    }
?>