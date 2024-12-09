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
    <title>Seleccionar comedor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_comedor.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<div class="navbar">
    <img src="../img/icon.png" class="icon">
    <div class="user-info">
        <div class="dropdown">
            <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
            <div class="dropdown-content">
                <a href="../private/logout.php">Cerrar Sesión</a>
            </div>
        </div>
        <span><?php echo  $_SESSION['nombre_usuario']; ?></span>
    </div>
</div>

<form action="gestion_mesas.php" method="post" class="options">
    <div class="option comedor1">
        <h2>Comedor Interior</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="comedor_interior" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option comedor2">
        <h2>Comedor Exterior</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="comedor_exterior" class="select-button">Seleccionar</button>
        </div>
    </div>
</form>

<script src="../js/dashboard.js"></script>
</body>
</html>