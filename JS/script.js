function validarCamposLogin() {
    const usuario = document.getElementById('usuarioLogin').value.trim();
    const senha = document.getElementById('senhaLogin').value.trim();
    const message = document.getElementById('msgLogin');

    if (!usuario || !senha) {
        message.textContent = 'Preencha todos os campos';
        message.style.color = 'red';
        setTimeout(() => message.textContent = '', 5000);
        return false;
    }
    message.textContent = '';
    return { usuario, senha };
}

function VerificaLogin(event) {
    event.preventDefault();
    const campos = validarCamposLogin();
    if (!campos) return;

    enviarLogin(campos);
}

function enviarLogin(dados) {
    const message = document.getElementById('msgLogin');
    const formData = new FormData();
    formData.append('action', 'validaLogin');
    formData.append('data', JSON.stringify(dados));

    fetch('../Controllers/ControllerJson.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'Home.html';
        } else {
            message.textContent = data.msg || 'Usuário ou senha inválidos.';
            message.style.color = 'red';
        }
    })
    .catch(error => {
        message.textContent = 'Erro ao tentar logar.';
        message.style.color = 'red';
        console.error(error);
    });
}
function validaFormCadastraUsuario(event) {
    event.preventDefault();
    const usuarioCadastro = document.getElementById('usuarioCadastro');
    const emailCadastro = document.getElementById('emailCadastro');
    const senhaCadastro = document.getElementById('senhaCadastro');
    const msgCadastro = document.getElementById('msgCadastro');
    let errors = [];

    if (usuarioCadastro.value.trim() === '') {
        errors.push('O campo "Usuário" é obrigatório.');
    }
    if (emailCadastro.value.trim() === '') {
        errors.push('O campo "Email" é obrigatório.');
    } else if (!/\S+@\S+\.\S+/.test(emailCadastro.value)) {
        errors.push('O campo "Email" deve conter um endereço de email válido.');
    }
    if (senhaCadastro.value.trim() === '') {
        errors.push('O campo "Senha" é obrigatório.');
    } else if (senhaCadastro.value.length < 6) {
        errors.push('A senha deve ter pelo menos 6 caracteres.');
    }

    if (errors.length > 0) {
        msgCadastro.innerHTML = errors.join('<br>');
        msgCadastro.style.color = 'red';
        return;
    } else {
        msgCadastro.innerHTML = '';
    }

    const data = {
        usuario: usuarioCadastro.value,
        email: emailCadastro.value,
        senha: senhaCadastro.value,
    };

    const dados = {
        action: "validaCadastro",
        data: data
    };

    fetchCadastroUsuario(dados);
}

function fetchCadastroUsuario(dados) {
    const formData = new FormData();
    formData.append('action', dados.action);
    formData.append('data', JSON.stringify(dados.data));

    fetch('../Controllers/ControllerJson.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msgCadastro = document.getElementById('msgCadastro');
        if (data.cadastrado === false) {
            msgCadastro.textContent = 'Usuário já cadastrado';
            msgCadastro.style.color = 'red';
        } else if (data.cadastrado === true || data.success) {
            msgCadastro.textContent = 'Usuário cadastrado com sucesso!';
            msgCadastro.style.color = 'green';
        }
    })
    .catch(error => {
        const msgCadastro = document.getElementById('msgCadastro');
        msgCadastro.textContent = 'Erro ao cadastrar usuário.';
        msgCadastro.style.color = 'red';
        console.error('Error:', error);
    });
}


function iniciarEventos() {
    document.getElementById('formLogin')?.addEventListener('submit', VerificaLogin);
    document.getElementById('formCadastro')?.addEventListener('submit', validaFormCadastraUsuario);
    document.getElementById('formCadastroAluno')?.addEventListener('submit', aoEnviarCadastroAluno);
    document.getElementById('formBuscarAlunos')?.addEventListener('submit', aoBuscarAlunos);
    document.getElementById('formEditarAluno')?.addEventListener('submit', aoEnviarEdicaoAluno);
    document.getElementById('formCadastroTreino')?.addEventListener('submit', aoEnviarCadastroTreino);
    document.getElementById('formBuscarTreinos')?.addEventListener('submit', aoBuscarTreinos);
}

async function aoBuscarAlunos(e) {
    e.preventDefault();
    const dados = coletarDadosFormularioAluno();
    const alunos = await buscarAlunos(dados);
    preencherTabela(alunos);
}
function coletarDadosEdicaoAluno() {
    return dados={
        nome: document.getElementById('nomeAlunoEditar').value,
        cpf: document.getElementById('cpfAlunoEditar').value,
        data_nascimento: document.getElementById('dataNascimentoAlunoEditar').value,
        contato: document.getElementById('telefoneAlunoEditar').value,
        email: document.getElementById('emailAlunoEditar').value,
        id_aluno: document.getElementById('idAlunoEditar').value
    };
}
async function editarAluno(dados) {
    const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=editarAluno', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `data=${encodeURIComponent(JSON.stringify(dados))}`
    });

    return await respostaFetch.json();
}
async function aoEnviarEdicaoAluno(event) {
    event.preventDefault();
    document.getElementById('confirmarEdicoes').textContent = ''; 

    const dados = coletarDadosEdicaoAluno();
    const resposta = await editarAluno(dados);
    if (resposta.success) {
        document.getElementById('confirmarEdicoes').textContent = 'Aluno editado com sucesso!';
        setTimeout(() => document.getElementById('confirmarEdicoes').textContent = '', 5000);
        const alunos = await buscarAlunos({});
        preencherTabela(alunos);
    } else {
        document.getElementById('confirmarEdicoes').textContent = 'Erro ao editar aluno. Verifique os dados.';
        setTimeout(() => document.getElementById('confirmarEdicoes').textContent = '', 5000);
    }
}

function coletarDadosFormularioAluno(){
    return {
        nomeAluno : document.getElementById('nomeAlunoBusca').value,
        cpfAluno : document.getElementById('cpfAlunoBusca').value,
        contatoAluno : document.getElementById('contatoAlunoBusca').value,
        dataNascimentoAluno : document.getElementById('dataNascimentoAlunoBusca').value
    };
}
async function buscarAlunos(dados){
    const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=buscarAlunos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `data=${encodeURIComponent(JSON.stringify(dados))}`
  });

  return await respostaFetch.json();
}
function preencherTabela(alunos) {
  const tbody = document.querySelector('#tabelaAlunos tbody');
  tbody.innerHTML = '';
  alunos.forEach((aluno, index) => {
    tbody.innerHTML += `
      <tr>
        <th scope="row">${index + 1}</th>
        <td>${aluno.nome}</td>
        <td>${aluno.cpf}</td>
        <td>${aluno.contato}</td>
        <td>${aluno.data_nascimento || ''}</td>
        <td>
          <button
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#modalEditarAluno"
            onclick="popularModalEditarAluno('${aluno.id_aluno}')"
            title="Editar aluno"
            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;"
          >
            <i class="fa-solid fa-pen"></i>
          </button>
        </td>
      <td>
          <button
            type="button"
            onclick="apagarAluno('${aluno.id_aluno}')"
            title="Apagar aluno"
            style="background: none; border: none; padding: 0; margin: 0; cursor: pointer; color: red;"
          >
            <i class="fa-solid fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });
}
async function apagarAluno(id) {
    if (confirm('Tem certeza que deseja apagar este aluno? Os treinos associados a ele também serão apagados.')) {
        const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=apagarAluno', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `data=${encodeURIComponent(JSON.stringify({ id_aluno: id }))}`
        });

        const resposta = await respostaFetch.json();
        if (resposta.success) {
            alert('Aluno apagado com sucesso!');
            const alunos = await buscarAlunos({
                nomeAluno: '',
                cpfAluno: '',
                contatoAluno: '',
                dataNascimentoAluno: ''
            });
            preencherTabela(alunos);
        } else {
            alert('Erro ao apagar aluno.');
        }
    }
    
}
async function buscarAlunoPorId(id) {
    const resposta = await fetch('../Controllers/ControllerJson.php?action=popularCampoEditarAluno', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `data=${encodeURIComponent(JSON.stringify({ id_aluno: id }))}`
    });
    return await resposta.json();
}
async function popularModalEditarAluno(id) {
    const aluno = await buscarAlunoPorId(id);
    if (aluno) {
        document.getElementById('idAlunoEditar').value = aluno.id_aluno;
        document.getElementById('nomeAlunoEditar').value = aluno.nome;
        document.getElementById('cpfAlunoEditar').value = aluno.cpf;
        document.getElementById('dataNascimentoAlunoEditar').value = aluno.data_nascimento || '';
        document.getElementById('telefoneAlunoEditar').value = aluno.contato;
        document.getElementById('emailAlunoEditar').value = aluno.email || '';
    }
}
function coletarDadosCadastroAluno() {
    return {
        nome: document.getElementById('nomeAlunoCadastro').value,
        cpf: document.getElementById('cpfAlunoCadastro').value,
        telefone: document.getElementById('telefoneAlunoCadastro').value,
        data_nascimento: document.getElementById('dataNascimentoAlunoCadastro').value,
        email: document.getElementById('emailAlunoCadastro').value
    };
}
async function cadastrarAlunos(dados) {
    const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=cadastrarAlunos', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `data=${encodeURIComponent(JSON.stringify(dados))}`
    });

    return await respostaFetch.json();
}
async function aoEnviarCadastroAluno(event) {
    event.preventDefault();
    const dados = coletarDadosCadastroAluno();
    const resposta = await cadastrarAlunos(dados);
    
    if (resposta.success) {
        document.getElementById('msgErroCadastroAluno').textContent = 'Aluno cadastrado com sucesso!';
        document.getElementById('msgErroCadastroAluno').style.color = 'green';
        document.getElementById('formCadastroAluno').reset();
    } else {
        document.getElementById('msgErroCadastroAluno').textContent = 'Aluno já cadastrado';
    }
}
// Treinos
async function coletarDadosCadastroTreino(){
    return dados={
        formCadastroTreino: document.getElementById('formCadastroTreino').value,
        nomeAlunoTreinoCadastro: document.getElementById('nomeAlunoTreinoCadastro').value,
        nomeTreinoCadastro: document.getElementById('nomeTreinoCadastro').value,
        descricaoTreinoCadastro: document.getElementById('descricaoTreinoCadastro').value,
        dataInicioTreinoCadastro: document.getElementById('dataInicioTreinoCadastro').value,
        dataFimTreinoCadastro: document.getElementById('dataFimTreinoCadastro').value
    }
}
async function CadastroTreino(dados) {
    const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=cadastrarTreino', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `data=${encodeURIComponent(JSON.stringify(dados))}`
    });

    return await respostaFetch.json();
}
async function aoEnviarCadastroTreino(event) {
    event.preventDefault();
    const dados = await coletarDadosCadastroTreino();
    const resposta = await CadastroTreino(dados);
    
    if (resposta.success) {
        document.getElementById('msgCadastroTreino').textContent = 'Treino cadastrado com sucesso!';
        document.getElementById('msgCadastroTreino').style.color = 'green';
        document.getElementById('formCadastroTreino').reset();
    } else if(resposta.error === 'Aluno não encontrado') {
        document.getElementById('msgCadastroTreino').textContent = 'Aluno não encontrado. Verifique o nome.';
        document.getElementById('msgCadastroTreino').style.color = 'red';
    }
    else {
        document.getElementById('msgCadastroTreino').textContent = 'Erro ao cadastrar treino. Verifique os dados.';
        document.getElementById('msgCadastroTreino').style.color = 'red';
    }
}
function coletarDadosFormularioTreinos(){
    return {
        nomeAlunoTreino: document.getElementById('nomeAlunoTreinoBusca').value,
        dataInicioTreino: document.getElementById('dataInicioTreinoBusca').value,
    };
}
async function buscarTreinos(dados){
    const respostaFetch = await fetch('../Controllers/ControllerJson.php?action=buscarTreinos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `data=${encodeURIComponent(JSON.stringify(dados))}`
  });

  return await respostaFetch.json();
}
async function aoBuscarTreinos(e) {
    e.preventDefault();
    const dados = coletarDadosFormularioTreinos();
    const treinos = await buscarTreinos(dados);
    preencherTabelaTreinos(treinos);
}
function preencherTabelaTreinos(treinos) {
    const tbody = document.querySelector('#tabelaTreinos tbody');
    tbody.innerHTML = '';
    treinos.forEach((treino, index) => {
        tbody.innerHTML += `
            <tr>
                <th scope="row">${index + 1}</th>
                <td>${treino.nomeAlunoTreino}</td>
                <td>${treino.nomeTreino}</td>
                <td>${treino.descricao_treino}</td>
                <td>${treino.dataInicioTreino || ''}</td>
                <td>${treino.dataFimTreino || ''}</td>
            </tr>
        `;
    });
}

window.addEventListener("DOMContentLoaded", iniciarEventos)