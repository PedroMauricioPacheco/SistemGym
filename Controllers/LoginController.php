<?php

    require_once __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    switch($action){
        case 'cadastro':
            $usuario = $_POST['usuario'] ?: '';
            $email = $_POST['email'] ?: '';
            $senha = $_POST['senha'] ?: '';
            $usuarioOBJ = new Usuario($usuario,$email,$senha);
            cadastro($usuarioOBJ);
            break;
    }
    
    function cadastro($usuario){
    global $pdo, $inserirUsuario;
    $stmt = $pdo->prepare($inserirUsuario);
    $stmt->execute([
        $usuario->getUsuario(),
        $usuario->getEmail(),
        $usuario->getSenha()
    ]);
    header("Location: ../HTML/Login.php?sucesso=1");
    exit;
}
    
    class Usuario{
        private $usuario;
        private $email;
        private $senha;
    
    public function __construct($usuario,$email,$senha){
        $this->usuario = $usuario;
        $this->email = $email;
        $this->senha = $senha;
    }
    
    public function getUsuario(){
        return $this->usuario;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getSenha(){
        return $this->senha;
    }
    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setSenha($senha){
        $this->senha = $senha;
    }
}
?>