<?php
    require_once __DIR__ . '/../Model/LoginModel.php';
    $action = $_POST['action'] ?? '';

    switch($action){
        case 'login':
            login($action);
            break;
        case 'cadastro':
            $usuario = $_POST['usuario']?? '';
            $email = $_POST['email']?? '';
            $senha = $_POST['senha']?? '';
            $usuarioOBJ = new Usuario($usuario,$email,$senha);
            cadastro($usuarioOBJ);
            break;
    }
    
    function login($action){
        global $checarLogin;
            $usuario = $_POST['usuario']?? '';
            $senha = $_POST['senha']?? '';
            if(!validarLogin($usuario,$senha)){
                header("Location: ../HTML/Login.php");
            }else{
                header("Location: ../HTML/Home.html");
            }
    }
    function cadastro($usuario){
            
            global $pdo, $checarRepetido, $inserirUsuario;
            
            if(!usuarioRepetido($pdo,$checarRepetido,$usuario->getUsuario())){
                cadastrandoUsuario($pdo,$inserirUsuario,$usuario->getUsuario(),$usuario->getEmail(),$usuario->getSenha());
                header("Location: ../HTML/Login.php");
                exit;
            }else{
                header("Location: /SistemGym/HTML/Login.php?erro=usuario");
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