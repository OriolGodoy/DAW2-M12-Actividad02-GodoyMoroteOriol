function confirmarEliminacion(idUsuario) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Si eliminas no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#d4a373',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `panelAdmin.php?delete=${idUsuario}`;
        }
    });
}

function confirmarEliminacionSala(idSala) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Si eliminas no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#d4a373',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `choose_privada_admin.php?delete=${idSala}`;
        }
    });
}

function confirmarEliminacionSalaComedor(idSala) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Si eliminas no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#d4a373',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `choose_comedor_admin.php?delete=${idSala}`;
        }
    });
}

function confirmarEliminacionSalaTerraza(idSala) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Si eliminas no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#d4a373',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `choose_terraza_admin.php?delete=${idSala}`;
        }
    });
}

function confirmarEliminacionMesa(idMesa) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Si eliminas no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#d4a373',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `gestion_mesas.php?delete=true&id_mesa=${idMesa}&id_sala=<?php echo $id_sala; ?>`;
        }
    });
}

function cerrarSesion() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Vas a cerrar sesión!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../private/logout.php';
        }
    });
}