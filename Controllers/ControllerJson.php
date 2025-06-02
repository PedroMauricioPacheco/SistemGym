<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    public function buscarAlunos(){        
        $nomeAluno = '%'.$this->dados['nomeAluno']. '%';
        $cpfAluno = '%'.$this->dados['cpfAluno']. '%';
        $contatoAluno = '%'.$this->dados['contatoAluno']. '%';
        $dataNascimentoAluno = $this->dados['dataNascimentoAluno'];

        $sql = "SELECT nome, cpf, telefone as contato, data_nascimento FROM alunos WHERE nome LIKE ? AND cpf LIKE ? AND telefone LIKE ?";
        $params = [$nomeAluno, $cpfAluno, $contatoAluno];

        $stmt = $this->banco->prepare($sql);
        $stmt->execute($params);
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alunos as &$aluno) {
        if (!empty($aluno['data_nascimento'])) {
            $aluno['data_nascimento'] = date('d/m/Y', strtotime($aluno['data_nascimento']));
        }
    }

        echo json_encode($alunos);
    }
    public function cadastrarAlunos(){
        $nome = $this->dados['nome'];
        $cpf = $this->dados['cpf'];
        $telefone = $this->dados['telefone'];
        $dataNascimento = $this->dados['data_nascimento'];
        $email = $this->dados['email'];

        // Verifica repetido
        $sql = "SELECT COUNT(*) FROM alunos WHERE cpf = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$cpf]);
        $cadastrado = $stmt->fetchColumn() > 0;
        if ($cadastrado) {
            echo json_encode(['success' => false, 'error' => 'Aluno já cadastrado.']);
            return;
        }
        try {
            $sql = "INSERT INTO alunos (nome, cpf, telefone, data_nascimento, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->banco->prepare($sql);
            $stmt->execute([$nome, $cpf, $telefone, $dataNascimento, $email]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}