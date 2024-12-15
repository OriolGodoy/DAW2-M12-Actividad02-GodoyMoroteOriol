function validateNumSillas() {
    const numSillas = document.getElementById("num_sillas").value;
    const numSillasError = document.getElementById("numSillaError");
    const numSillasInput = document.getElementById("num_sillas");

    if (numSillas.trim() === "") {
        numSillasError.textContent = "El numero de sillas es obligatorio.";
        numSillasError.style.color = "red";
        numSillasInput.style.borderColor = "red";
        return false;
    } else {
        numSillasError.textContent = "";
        numSillasInput.style.borderColor = "";
        return true;
    }
}

function validateForm() {
    let isNumSillas = validateNumSillas();
    return isNumSillas;
}

document.getElementById("addMesa").addEventListener("submit", function(event) {
    if (!validateNumSillas()) {
        event.preventDefault();
    }
});