function validaNombreCliente() {
    let nombre = document.getElementById("nombre_cliente").value;
    let inputNombre = document.getElementById("nombre_cliente");
    let nombreError = document.getElementById("nombre_cliente_error");

    if (nombre === "" || nombre === null) {
        nombreError.textContent = "El nombre del cliente es obligatorio.";
        inputNombre.classList.add("error-border");
        return false;
    } else {
        nombreError.textContent = "";
        inputNombre.classList.remove("error-border");
        return true;
    }
}

function validaSala() {
    let sala = document.getElementById("sala").value;
    let inputSala = document.getElementById("sala");
    let salaError = document.getElementById("sala_error");

    if (sala === "" || sala === null) {
        salaError.textContent = "Seleccionar una sala es obligatorio.";
        inputSala.classList.add("error-border");
        return false;
    } else {
        salaError.textContent = "";
        inputSala.classList.remove("error-border");
        return true;
    }
}

function validaMesa() {
    let mesa = document.getElementById("mesa").value;
    let inputMesa = document.getElementById("mesa");
    let mesaError = document.getElementById("mesa_error");

    if (mesa === "" || mesa === null) {
        mesaError.textContent = "Seleccionar una mesa es obligatorio.";
        inputMesa.classList.add("error-border");
        return false;
    } else {
        mesaError.textContent = "";
        inputMesa.classList.remove("error-border");
        return true;
    }
}



function validaFechaReserva() {
    let fecha = document.getElementById("fecha_reserva").value;
    let inputFecha = document.getElementById("fecha_reserva");
    let fechaError = document.getElementById("fecha_reserva_error");

    if (fecha === "" || fecha === null) {
        fechaError.textContent = "La fecha de reserva es obligatoria.";
        inputFecha.classList.add("error-border");
        return false;
    } else {
        fechaError.textContent = "";
        inputFecha.classList.remove("error-border");
        return true;
    }
}

function validaHoraInicio() {
    let horaInicio = document.getElementById("hora_inicio").value;
    let inputHoraInicio = document.getElementById("hora_inicio");
    let horaInicioError = document.getElementById("hora_inicio_error");

    if (horaInicio === "" || horaInicio === null) {
        horaInicioError.textContent = "La hora de inicio es obligatoria.";
        inputHoraInicio.classList.add("error-border");
        return false;
    } else {
        horaInicioError.textContent = "";
        inputHoraInicio.classList.remove("error-border");
        return true;
    }
}

function validaHoraFin() {
    let horaFin = document.getElementById("hora_fin").value;
    let inputHoraFin = document.getElementById("hora_fin");
    let horaFinError = document.getElementById("hora_fin_error");

    if (horaFin === "" || horaFin === null) {
        horaFinError.textContent = "La hora de fin es obligatoria.";
        inputHoraFin.classList.add("error-border");
        return false;
    } else {
        let horaInicio = document.getElementById("hora_inicio").value;
        if (horaInicio !== "" && horaFin <= horaInicio) {
            horaFinError.textContent = "La hora de fin debe ser mayor a la hora de inicio.";
            inputHoraFin.classList.add("error-border");
            return false;
        } else {
            horaFinError.textContent = "";
            inputHoraFin.classList.remove("error-border");
            return true;
        }
    }
}