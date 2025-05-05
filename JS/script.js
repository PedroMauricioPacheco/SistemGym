function pegarValores() {
    return{
        usuario: document.getElementById ('usuario').value.trim(),
        email: document.getElementById ('email').value.trim(),
        senha: document.getElementById ('senha').value.trim(),
        msgErro: document.getElementById('msgErro'),
    }
    
}
function validarCadastro(event){
    const {usuario,email,senha, msgErro} = pegarValores();

    if(!usuario || !email || !senha){
        event.preventDefault();
        msgErro.textContent = "Preencha todos os campos";
        msgErro.classList.add("mostrar");

        setTimeout(() => {
            msgErro.textContent = "";
        }, 3000);
    }else{
        msgErro.textContent = "";
    }
}
function iniciarEventosCadastro(){
    document.getElementById('formCadastro').addEventListener('submit',validarCadastro)
}

window.addEventListener("DOMContentLoaded",iniciarEventosCadastro);