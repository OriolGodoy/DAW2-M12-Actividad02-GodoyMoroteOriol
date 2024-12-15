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

function validateForm() {
    let isNombreValid = validateNombreSala();
    return isNombreValid;
}

document.getElementById("editSalaForm").addEventListener("submit", function(event) {
    if (!validateNombreSala()) {
        event.preventDefault();
    }
});