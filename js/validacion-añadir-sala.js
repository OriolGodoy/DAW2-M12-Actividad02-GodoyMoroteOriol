function validateNombreSala() {
    const nombreSala = document.getElementById("nombre_sala").value;
    const nombreSalaError = document.getElementById("nombreSalaError");
    const nombreSalaInput = document.getElementById("nombre_sala");

    if (nombreSala.trim() === "") {
        nombreSalaError.textContent = "El nombre de la sala es obligatorio.";
        nombreSalaError.style.color = "red";
        nombreSalaInput.style.borderColor = "red";
        return false;
    } else {
        nombreSalaError.textContent = "";
        nombreSalaInput.style.borderColor = "";
        return true;
    }
}

function validateTipoSala() {
    const tipoSala = document.getElementById("tipo_sala").value;
    const tipoSalaError = document.getElementById("tipoSalaError");
    const tipoSalaInput = document.getElementById("tipo_sala");

    // Validación del tipo de sala
    if (tipoSala === "") {
        tipoSalaError.textContent = "Debe seleccionar un tipo de sala.";
        tipoSalaError.style.color = "red";
        tipoSalaInput.style.borderColor = "red";
        return false;
    } else {
        tipoSalaError.textContent = "";
        tipoSalaInput.style.borderColor = "";
        return true;
    }
}

function validateImagenSala() {
    const imagenSala = document.getElementById("imagen_sala").files[0];
    const imagenSalaError = document.getElementById("imagenSalaError");
    const imagenSalaInput = document.getElementById("imagen_sala");

    if (!imagenSala) {
        imagenSalaError.textContent = "Debe seleccionar una imagen para la sala.";
        imagenSalaError.style.color = "red";
        imagenSalaInput.style.borderColor = "red";
        return false;
    }

    imagenSalaError.textContent = "";
    imagenSalaInput.style.borderColor = "";
    return true;
}

function validateForm() {
    let isNombreValid = validateNombreSala();
    let isTipoSala = validateTipoSala();
    let isImagenSala = validateImagenSala();

    return isNombreValid && isTipoSala && isImagenSala;
}

document.getElementById("addSalaForm").addEventListener("submit", function(event) {
    if (!validateNombreSala() || !validateTipoSala() || !validateImagenSala()) {
        event.preventDefault();
    }
});