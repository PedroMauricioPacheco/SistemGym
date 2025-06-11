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
        else{

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
        if ($cadastrado) {
            error_log("Usuário já cadastrado: " . $usuario);
        } else {
            $this->cadastrarUsuario();
        }
        return $cadastrado;
    }
    public function cadastrarUsuario(){
        $usuario = $this->dados['usuario'];
        $email = $this->dados['email'];
        $senha = $this->dados['senha'];
        
        $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->banco->prepare($sql);
        try{
            $stmt->execute([$usuario, $email, $senha]);
            echo json_encode(['success' => true, 'msg' => 'Usuário cadastrado com sucesso!']);
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erro ao cadastrar usuário.']);
        }
    }
    public function validaLogin(){
    $usuario = $this->dados['usuario'];
    $senha = $this->dados['senha'];

    // Consulta o usuário e senha
    $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ? AND senha = ?";
    $stmt = $this->banco->prepare($sql);
    $stmt->execute([$usuario, $senha]);
    $existe = $stmt->fetchColumn() > 0;

    if ($existe) {
        echo json_encode(['success' => true, 'msg' => 'Login realizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Usuário ou senha inválidos.']);
    }
    return;
}
    public function buscarAlunos(){        
        $nomeAluno = '%'.$this->dados['nomeAluno']. '%';
        $cpfAluno = '%'.$this->dados['cpfAluno']. '%';
        $contatoAluno = '%'.$this->dados['contatoAluno']. '%';
        $dataNascimentoAluno = $this->dados['dataNascimentoAluno'];

        $sql = "SELECT id_aluno, nome, cpf, telefone as contato, data_nascimento FROM alunos WHERE nome LIKE ? AND cpf LIKE ? AND telefone LIKE ?";
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
    public function popularCampoEditarAluno(){
        $idAluno = $this->dados['id_aluno'];

        $sql = "SELECT id_aluno, nome, cpf, telefone as contato, data_nascimento,email FROM alunos WHERE id_aluno = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$idAluno]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            if (!empty($aluno['data_nascimento'])) {
                $aluno['data_nascimento'] = date('d/m/Y', strtotime($aluno['data_nascimento']));
            }
            echo json_encode($aluno);
        } else {
            echo json_encode(['error' => 'Aluno não encontrado.']);
        }
    }
    public function editarAluno(){
        $nome = $this->dados['nome'];
        $cpf = $this->dados['cpf'];
        $data_nascimento = $this->dados['data_nascimento'];
        $telefone = $this->dados['contato'];
        $email = $this->dados['email'];

        $sql = "UPDATE alunos SET nome = ?, cpf = ?, data_nascimento = ?, telefone = ?, email = ? WHERE id_aluno = ?";
        $stmt = $this->banco->prepare($sql);
        try {
            $stmt->execute([$nome, $cpf, $data_nascimento, $telefone, $email, $this->dados['id_aluno']]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function apagarAluno(){
        $idAluno = $this->dados['id_aluno'];

        $sql = "SELECT COUNT(*) FROM alunos WHERE id_aluno = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$idAluno]);
        $existe = $stmt->fetchColumn() > 0;

        if (!$existe) {
            echo json_encode(['success' => false, 'error' => 'Aluno não encontrado.']);
            return;
        }
        $sql = "DELETE FROM treinos WHERE id_aluno = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$idAluno]);

        $sql = "DELETE FROM alunos WHERE id_aluno = ?";
        $stmt = $this->banco->prepare($sql);
        try {
            $stmt->execute([$idAluno]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
// Treinos
    public function cadastrarTreino(){
        $nomeAlunoTreinoCadastro = $this->dados['nomeAlunoTreinoCadastro'];
        $nomeTreinoCadastro = $this->dados['nomeTreinoCadastro'];
        $descricaoTreinoCadastro = $this->dados['descricaoTreinoCadastro'];
        $dataInicioTreinoCadastro = $this->dados['dataInicioTreinoCadastro'];
        $dataFimTreinoCadastro = $this->dados['dataFimTreinoCadastro'];

        $sql = "SELECT id_aluno FROM alunos WHERE nome = ?";
        $stmt = $this->banco->prepare($sql);
        $stmt->execute([$nomeAlunoTreinoCadastro]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$aluno) {
            echo json_encode(['success' => false, 'error' => 'Aluno não encontrado.']);
            return;
        }
        $sql = "INSERT INTO treinos (nome_treino, descricao, data_inicio, data_fim, id_aluno) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->banco->prepare($sql);
        try {
            $stmt->execute([
                $nomeTreinoCadastro,
                $descricaoTreinoCadastro,
                $dataInicioTreinoCadastro,
                $dataFimTreinoCadastro,
                $aluno['id_aluno']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function buscarTreinos(){
        $nomeAlunoTreino = '%'.$this->dados['nomeAlunoTreino'].'%';
        $dataInicioTreino = $this->dados['dataInicioTreino'];
        $sql = "SELECT 
                    a.nome AS nomeAlunoTreino,
                    t.nome_treino AS nome_treino,
                    t.descricao AS descricao_treino,
                    t.data_inicio AS dataInicioTreino,
                    t.data_fim AS dataFimTreino
                FROM treinos t
                INNER JOIN alunos a ON t.id_aluno = a.id_aluno
                WHERE a.nome LIKE ?";

        $params = [$nomeAlunoTreino];

        if (!empty($dataInicioTreino)) {
            $sql .= " AND t.data_inicio >= ?";
            $params[] = $dataInicioTreino;
        }
        if (!empty($dataFimTreino)) {
            $sql .= " AND t.data_fim <= ?";
            $params[] = $dataFimTreino;
        }

        $stmt = $this->banco->prepare($sql);
        $stmt->execute($params);
        $treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($treinos);
    }
}