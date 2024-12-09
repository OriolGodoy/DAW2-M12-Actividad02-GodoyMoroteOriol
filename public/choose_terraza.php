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
    <title>Seleccionar terraza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_terraza.css">
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
            <span><?php echo  $_SESSION['nombre_usuario']; ?></span>
        </div>
    </div>
</div>

<form action="gestion_mesas.php" method="post" class="options">
    <div class="option terraza1">
        <h2>Terraza 1</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="terraza_principal" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option terraza2">
        <h2>Terraza 2</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="terraza_secundaria" class="select-button">Seleccionar</button>
        </div>
    </div>
    <div class="option terraza3">
        <h2>Terraza 3</h2>
        <div class="button-container">
            <button type="submit" name="sala" value="terraza_terciaria" class="select-button">Seleccionar</button>
        </div>
    </div>
</form>

<script src="../js/dashboard.js"></script>
</body>

</html>