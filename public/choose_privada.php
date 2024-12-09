<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar sala privada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_privada.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>
<div class="navbar">
    <img src="../img/icon.png" class="icon">
    <div class="user-info">
        <div class="dropdown">
            <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
            <div class="dropdown-content">
                <a href="../private/logout.php">Cerrar Sesión</a>
            </div>
        </div>
        <span><?php echo $_SESSION['nombre_usuario']; ?></span>
    </div>
</div>

<form action="gestion_mesas.php" method="post" class="options">
    <div class="option privada1">
        <h2>Sala Privada 1</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="sala_privada_1" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option privada2">
        <h2>Sala Privada 2</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="sala_privada_2" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option privada3">
        <h2>Sala Privada 3</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="sala_privada_3" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option privada4">
        <h2>Sala Privada 4</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="sala_privada_4" class="select-button">Seleccionar</button>
        </div>
    </div>
</form>

    <script src="../js/dashboard.js"></script>
</body>

</html>