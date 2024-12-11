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
    <title>Seleccionar sala</title>
    <link rel="stylesheet" href="../css/dashboard-admin.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="navbar">
        <img src="../img/icon.png" class="icon">
        <div class="user-info">
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
            <a href="./historial_ocupaciones.php" class="history-button">Ver Historial</a>
            <a href="./panelAdmin.php" class="logout">Gestionar Usuarios</a>
            <a href="../private/logout.php" class="logout">Cerrar Sesión</a>
        </div>
    </div>
<div class="options">
    <div class="option terraza">
        <h2>Terraza</h2>
        <div class="button-container">
            <a href="./choose_terraza.php" class="select-button">Seleccionar</a>
        </div>
    </div>
    <div class="option comedor">
        <h2>Comedor</h2>
        <div class="button-container">
            <a href="./choose_comedor.php" class="select-button">Seleccionar</a>
        </div>
    </div>
    <div class="option privadas">
        <h2>Sala privada</h2>
        <div class="button-container">
            <a href="./choose_privada.php" class="select-button">Seleccionar</a>
        </div>
    </div>
</div>

<script src="../js/dashboard.js"></script>
</body>
</html>
