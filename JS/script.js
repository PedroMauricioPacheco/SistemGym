function VerificaLogin(event) {
    const form = document.getElementById('formLogin');
    const usuario = document.getElementById('usuarioLogin').value.trim();
    const senha = document.getElementById('senhaLogin').value.trim();
    const message = document.querySelector('.msgErroLogin');

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

function iniciarEventos() {
    document.getElementById('formLogin')?.addEventListener('submit', VerificaLogin);
    document.getElementById('formCadastro')?.addEventListener('submit', VericaCadastro);
}

window.addEventListener("DOMContentLoaded", iniciarEventos)
