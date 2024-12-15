<?php
session_start();
require_once "../db/conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

try {
    $query = "SELECT id_sala, nombre_sala, imagen_sala FROM tbl_sala WHERE tipo_sala = 'terraza'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar comedores: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Sala</title>
    <link rel="stylesheet" href="../css/choose_todos.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/sweet_alert.js"></script>
</head>

<body>
<div class="navbar">
<a href="panelCamarero.php"><img src="../img/icon.png" class="icon"></a>
    <div class="user-info">
        <span><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        <a href="./historial_ocupaciones.php" class="history-button">Ver Historial</a>
        <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>
</div>

<div class="options">
    <?php if ($salas): ?>
        <?php foreach ($salas as $sala): ?>
            <div class="option" style="background-image: url('<?php echo htmlspecialchars($sala['imagen_sala']); ?>');">
                <h2><?php echo htmlspecialchars($sala['nombre_sala']); ?></h2>
                <div class="button-container">
                    <a href="gestion_mesas-all.php?id_sala=<?php echo htmlspecialchars($sala['id_sala']); ?>" class="select-button">Seleccionar</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay comedores disponibles.</p>
    <?php endif; ?>
</div>

</body>

</html>
