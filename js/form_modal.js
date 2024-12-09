    function openPopup(mesaId, numSillas) {
        document.getElementById('mesa_id').value = mesaId;
        document.getElementById('personas').setAttribute('max', numSillas);
        document.getElementById('popup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }