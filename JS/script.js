function pegarValores(form) {
    return {
        usuario: form.querySelector('[name="usuario"]')?.value.trim(),
        email: form.querySelector('[name="email"]')?.value.trim(),
        senha: form.querySelector('[name="senha"]')?.value.trim(),
        msgErro: form.querySelector('.msgErro'),
    };
}

function validarLogin(event) {
    const form = event.target;
    const { usuario, senha, msgErro } = pegarValores(form);

    if (!usuario || !senha) {
        event.preventDefault();
        if (msgErro) {
            msgErro.textContent = "Preencha todos os campos";
            msgErro.classList.add("mostrar");
            setTimeout(() => msgErro.textContent = "", 3000);
        }
    } else {
        if (msgErro) msgErro.textContent = "";
    }
}

function validarCadastro(event) {
    const form = event.target;
    const { usuario, email, senha, msgErro } = pegarValores(form);

    if (!usuario || !email || !senha) {
        event.preventDefault();
        if (msgErro) {
            msgErro.textContent = "Preencha todos os campos";
            msgErro.classList.add("mostrar");
            setTimeout(() => msgErro.textContent = "", 3000);
        }
    } else {
        if (msgErro) msgErro.textContent = "";
    }
}

function iniciarEventos() {
    document.getElementById('formLogin')?.addEventListener('submit', validarLogin);
    document.getElementById('formCadastro')?.addEventListener('submit', validarCadastro);
}

window.addEventListener("DOMContentLoaded", iniciarEventos);
