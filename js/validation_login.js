document.getElementById("email_usuario").onblur = validaEmail;
document.getElementById("pwd").onblur = validaPassword;
document.getElementById("loginForm").onsubmit = validaForm;

function validaEmail() {
    let email = document.getElementById("email_usuario").value;
    let inputEmail = document.getElementById("email_usuario");
    let emailError = document.getElementById("email_usuario_error");

    if (email === "" || email === null) {
        emailError.textContent = "El email es obligatorio.";
        inputEmail.classList.add("error-border");
        return false;
    } else if (!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/)) {
        emailError.textContent = "Por favor, introduce un email válido.";
        inputEmail.classList.add("error-border");
        return false;
    } else {
        emailError.textContent = "";
        inputEmail.classList.remove("error-border");
        return true;
    }
}

function validaPassword() {
    let pwd = document.getElementById("pwd").value;
    let inputPwd = document.getElementById("pwd");
    let pwdError = document.getElementById("pwd_error");

    if (pwd === "" || pwd === null) {
        pwdError.textContent = "La contraseña es obligatoria.";
        inputPwd.classList.add("error-border");
        return false;
    } else if (pwd.length < 8) {
        pwdError.textContent = "La contraseña debe tener 8 caracteres mínimo.";
        inputPwd.classList.add("error-border");
        return false;
    } else if (!pwd.match(/[A-Z]/) || !pwd.match(/[a-z]/) || !pwd.match(/[0-9]/)) {
        pwdError.textContent = "La contraseña debe contener al menos una letra mayúscula, una minúscula y un número.";
        inputPwd.classList.add("error-border");
        return false;
    } else {
        pwdError.textContent = "";
        inputPwd.classList.remove("error-border");
        return true;
    }
}

function validaForm(event) {
    event.preventDefault();
    let validEmail = validaEmail();
    let validPassword = validaPassword();

    if (validEmail && validPassword) {
        document.getElementById("loginForm").submit();
    }
}