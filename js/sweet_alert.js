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
            window.location.href = `dashAdmin.php?delete=${idUsuario}`;
        }
    });
}