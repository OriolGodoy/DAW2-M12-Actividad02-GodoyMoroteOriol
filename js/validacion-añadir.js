function validateNombre() {
    let errorNombre = document.getElementById("nombreError");
    let nombre = document.getElementById("nombre_usuario");
    let errorMessages = "";

    if (nombre.value.trim().length === 0) {
        errorMessages += "El nombre no puede estar vacío.<br>";
    } else if (nombre.value.trim().length < 3) {
        errorMessages += "El nombre debe tener al menos 3 caracteres.<br>";
    }

    if (errorMessages !== "") {
        errorNombre.innerHTML = errorMessages;
        errorNombre.style.color = "red";
        nombre.style.borderColor = "red";
        return false;
    } else {
        errorNombre.innerHTML = "";
        nombre.style.borderColor = "";
        return true;
    }
}

function validateEmail() {
    let errorEmail = document.getElementById("emailError");
    let email = document.getElementById("email_usuario");
    let errorMessages = "";
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email.value.trim().length === 0) {
        errorMessages += "El correo no puede estar vacío.<br>";
    } else if (!emailRegex.test(email.value.trim())) {
        errorMessages += "El formato del correo es inválido.<br>";
    }

    if (errorMessages !== "") {
        errorEmail.innerHTML = errorMessages;
        errorEmail.style.color = "red";
        email.style.borderColor = "red";
        return false;
    } else {
        errorEmail.innerHTML = "";
        email.style.borderColor = "";
        return true;
    }
}

function validatePassword() {
    let errorPassword = document.getElementById("passwordError");
    let password = document.getElementById("password_usuario");
    let errorMessages = "";

    if (password.value.length === 0) {
        errorMessages += "La contraseña no puede estar vacía.<br>";
    } else if (password.value.length < 8) {
        errorMessages += "La contraseña debe tener al menos 8 caracteres.<br>";
    }

    if (errorMessages !== "") {
        errorPassword.innerHTML = errorMessages;
        errorPassword.style.color = "red";
        password.style.borderColor = "red";
        return false;
    } else {
        errorPassword.innerHTML = "";
        password.style.borderColor = "";
        return true;
    }
}

function validateConfirmPassword() {
    let errorConfirmPassword = document.getElementById("confirmPasswordError");
    let confirmPassword = document.getElementById("confirm_password");
    let password = document.getElementById("password_usuario");
    let errorMessages = "";

    if (confirmPassword.value.length === 0) {
        errorMessages += "Debes confirmar la contraseña.<br>";
    } else if (confirmPassword.value !== password.value) {
        errorMessages += "Las contraseñas no coinciden.<br>";
    }

    if (errorMessages !== "") {
        errorConfirmPassword.innerHTML = errorMessages;
        errorConfirmPassword.style.color = "red";
        confirmPassword.style.borderColor = "red";
        return false;
    } else {
        errorConfirmPassword.innerHTML = "";
        confirmPassword.style.borderColor = "";
        return true;
    }
}

function validateRole() {
    let errorRole = document.getElementById("roleError");
    let role = document.getElementById("id_rol");
    let errorMessages = "";

    if (role.value === "") {
        errorMessages += "Debes seleccionar un rol.<br>";
    }

    if (errorMessages !== "") {
        errorRole.innerHTML = errorMessages;
        errorRole.style.color = "red";
        role.style.borderColor = "red";
        return false;
    } else {
        errorRole.innerHTML = "";
        role.style.borderColor = "";
        return true;
    }
}

function validateForm() {
    let isNombreValid = validateNombre();
    let isEmailValid = validateEmail();
    let isPasswordValid = validatePassword();
    let isConfirmPasswordValid = validateConfirmPassword();
    let isRoleValid = validateRole();

    return isNombreValid && isEmailValid && isPasswordValid && isConfirmPasswordValid && isRoleValid;
}

document.getElementById("addUserForm").addEventListener("submit", function(event) {
    if (!validateForm()) {
        event.preventDefault();
    }
});