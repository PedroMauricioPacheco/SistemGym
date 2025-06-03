function VerificaLogin(event) {
     const form = document.getElementById('formLogin');
    const usuario = document.getElementById('usuarioLogin').value.trim();
     const senha = document.getElementById('senhaLogin').value.trim();
     const message = document.getElementById('msgErroLogin');

    if(!usuario || !senha){
        event.preventDefault();
         message.textContent = 'Preencha todos os campos';
         setTimeout(() => message.textContent = '', 5000);
     }
}
function validaFormCadastraUsuario(){
            
            const formCadastro = document.getElementById('formCadastro');
            const usuarioCadastro = document.getElementById('usuarioCadastro');
            const emailCadastro = document.getElementById('emailCadastro');
            const senhaCadastro = document.getElementById('senhaCadastro');
            const msgErroCadastro = document.getElementById('msgErroCadastro');
            const action = document.getElementById('action');

            formCadastro.addEventListener('submit', function(event) {

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
                    event.preventDefault();
                    msgErroCadastro.innerHTML = errors.join('<br>');
                    msgErroCadastro.style.color = 'red';
                } else {
                    msgErroCadastro.innerHTML = '';
                }
            });


            data = {
                "usuario": usuarioCadastro.value,
                "email": emailCadastro.value,
                "senha": senhaCadastro.value,
            }

            dados = {
                "action": "validaCadastro",
                "data": data
            };

            fetchCadastroUsuario(dados);

            return;
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
                if (data.cadastrado == true) {
                    alert(data.msg);
                }
            })
            .catch(error => console.error('Error:', error));
        }


function iniciarEventos() {
    document.getElementById('formLogin')?.addEventListener('submit', VerificaLogin);
    document.getElementById('formCadastro')?.addEventListener('submit', VerificaCadastro);
    document.getElementById('formCadastroAluno')?.addEventListener('submit', aoEnviarCadastroAluno);
    document.getElementById('formBuscarAlunos')?.addEventListener('submit', async function (e) {
    document.getElementById('formLogin')?.addEventListener('submit', validaFormLogin);

        e.preventDefault();
        const dados = coletarDadosFormularioAluno();
        const alunos = await buscarAlunos(dados);
        console.log(alunos); // Adicione esta linha para depurar
        preencherTabela(alunos);
    });
    spanUsuarioRepetido();
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
      </tr>
    `;
  });
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
        document.getElementById('formCadastroAluno').reset();
    } else {
        document.getElementById('msgErroCadastroAluno').textContent = 'Aluno já cadastrado';
    }
}
function fetchValidaLogin(dados) {
    const formData = new FormData();
    formData.append('action', 'validaLogin');
    formData.append('data', JSON.stringify(dados));

    return fetch('../Controllers/ControllerJson.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json());
}

function validaFormLogin(event) {
    event.preventDefault();
    const usuario = document.getElementById('usuarioLogin').value.trim();
    const senha = document.getElementById('senhaLogin').value.trim();
    const msgErroLogin = document.getElementById('msgErroLogin');
    let errors = [];

    if (!usuario) errors.push('O campo "Usuário" é obrigatório.');
    if (!senha) errors.push('O campo "Senha" é obrigatório.');

    if (errors.length > 0) {
        msgErroLogin.innerHTML = errors.join('<br>');
        msgErroLogin.style.color = 'red';
        return;
    } else {
        msgErroLogin.innerHTML = '';
    }

    const dados = { usuario, senha };

    fetchValidaLogin(dados)
        .then(data => {
            if (data.success) {
                window.location.href = 'Gerenciar.html'; // ou a página desejada
            } else {
                msgErroLogin.textContent = data.msg || 'Usuário ou senha inválidos.';
                msgErroLogin.style.color = 'red';
            }
        })
        .catch(error => {
            msgErroLogin.textContent = 'Erro ao tentar logar.';
            msgErroLogin.style.color = 'red';
        });
}

window.addEventListener("DOMContentLoaded", iniciarEventos)