function validateNombre() {
    let errorNombre = document.getElementById("nombreError");
    let nombre = document.getElementById("nombre_usuario");
    let errorMessages = "";

    if (nombre.value.trim().length < 3) {
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

function validateForm() {
    let isNombreValid = validateNombre();
    let isEmailValid = validateEmail();

    return isNombreValid && isEmailValid;
}

document.getElementById("editUserForm").addEventListener("submit", function(event) {
    if (!validateForm()) {
        event.preventDefault();
    }
});