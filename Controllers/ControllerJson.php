<?php

// $retono = ["msg" => "Erro ao processar a requisicao."];
// $retono['cadastrado'] = false;
// echo json_encode($retono);
// die();

header('Content-Type: application/json');

$action = $_REQUEST['action'];
$dados = json_decode($_REQUEST['data'], true); // <- Aqui é o importante
if (method_exists('ControllerJson', $action)) {
    $controller = new ControllerJson($dados);
    $controller->$action();
} else {
    echo json_encode(['error' => 'Action not found']);
}

class ControllerJson
{
    private $banco;
    private $dados;

    public function __construct($dados)
    {
        error_log("Dados recebidos: " . json_encode($dados));
        $this->dados = $dados;
        $this->banco = new PDO('mysql:host=localhost;dbname=academia', 'root', '');
    }

    public function validaCadastro()
    {
        $usuario = $this->dados['usuario'];
        $cadastrado = $this->consultaUsuario($usuario);

        $retorno['cadastrado'] = $cadastrado;  

        if ($cadastrado) {
            $retorno['msg'] = 'Usuário já cadastrado.';
        }

        echo json_encode($retorno);
        return;
    }

    public function consultaUsuario($usuario) : bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$usuario]);

        $cadastrado = $stmt->fetchColumn() > 0;
        return $cadastrado;
    }
} 