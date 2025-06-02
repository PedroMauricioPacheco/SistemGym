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
function VericaCadastro(event){
    const form = document.getElementById('formCadastro');
    const usuario = document.getElementById('usuarioCadastro').value.trim();
    const email = document.getElementById('emailCadastro').value.trim();
    const senha = document.getElementById('senhaCadastro').value.trim();
    const message = document.getElementById('msgErroCadastro')

    if(!usuario || !email || !senha){
        event.preventDefault();
        message.textContent = 'Preencha todos os campos';
       setTimeout(() => message.textContent = '', 5000)
   }
}
function spanUsuarioRepetido (){
   const usuarioRepetido = new URLSearchParams(window.location.search);
    if (usuarioRepetido.get('erro')=='usuario'){
        document.getElementById('msgErroLogin').textContent = 'Usuário já cadastrado';
   }
}

function iniciarEventos() {
    document.getElementById('formLogin')?.addEventListener('submit', VerificaLogin);
    document.getElementById('formCadastro')?.addEventListener('submit', VerificaCadastro);
    document.getElementById('formCadastroAluno')?.addEventListener('submit', aoEnviarCadastroAluno);
    document.getElementById('formBuscarAlunos')?.addEventListener('submit', async function (e) {
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
        <td></td>
      </tr>
    `;
  });
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

window.addEventListener("DOMContentLoaded", iniciarEventos)