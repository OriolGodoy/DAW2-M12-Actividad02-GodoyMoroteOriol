document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.select-button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const action = button.name === 'desocupar' ? 'desocupar' : 'reservar';
            const title = action === 'reservar' ? '¿Seguro quieres reservar esta mesa?' : '¿Seguro quieres desocupar esta mesa?';
            const confirmButtonText = action === 'reservar' ? 'Sí, reservar' : 'Sí, desocupar';
            const successTitle = action === 'reservar' ? 'Mesa reservada' : 'Mesa desocupada';
            const successMessage = action === 'reservar' ? 'La mesa ha sido reservada con éxito.' : 'La mesa ha sido desocupada con éxito.';

            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: successTitle,
                        icon: 'success',
                        text: successMessage,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (action === 'desocupar') {
                            button.closest('form').submit();
                        } else {
                            button.closest('form').submit();
                        }
                    });
                }
            });
        });
    });
});