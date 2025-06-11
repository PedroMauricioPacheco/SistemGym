CREATE database Academia;
USE Academia;
CREATE TABLE Alunos
(
	id_aluno INT auto_increment PRIMARY KEY,
    nome VARCHAR(40),
    cpf  VARCHAR(14),
    data_nascimento DATE, 
    telefone VARCHAR(20),
    email VARCHAR(40),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_alteracao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE Treinos 
(
	id INT auto_increment PRIMARY KEY,
    id_aluno INT,
    nome_treino VARCHAR(25),
    descricao VARCHAR(100),
    data_inicio DATE,
    data_fim DATE,
    dia_semana VARCHAR (20),
    exercicio VARCHAR (40),
    series INT,
    repeticoes INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_alteracao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aluno) REFERENCES Alunos (id_aluno)
)
CREATE TABLE usuarios
(
    id_usuario INT auto_increment PRIMARY KEY,
    usuario VARCHAR(35),
    senha VARCHAR(20),
    email VARCHAR(35),
)