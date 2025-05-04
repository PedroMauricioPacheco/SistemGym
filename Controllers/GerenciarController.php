<?php
    print_r($_POST);
    exit;
    function PesquisaAluno(){
        $action = isset($_POST['action'])? ($_POST['action']) : "";

        if ($action == 'pesquisar'){
            $nome = $_POST['nome'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $contato = $_POST['contato'] ?? '';
        }
    }
?>