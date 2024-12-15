<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

require_once '../db/conexion.php'; 

try {
    $stmt = $conn->prepare("
        SELECT u.nombre_usuario, u.email_usuario, r.nombre_rol
        FROM tbl_usuario u
        INNER JOIN tbl_rol r ON u.id_rol = r.id_rol
        WHERE u.id_usuario = :id_usuario
    ");

    $stmt->bindParam(':id_usuario', $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit();
    }

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../css/pagIn.css">
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
    <img src="../img/icon.png" class="icon">
    <div class="user-info">
        <span><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>
</div>

<div class="container">
    <h1>Perfil de Usuario</h1>
    <div class="profile">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre_usuario']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email_usuario']); ?></p>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['nombre_rol']); ?></p>
    </div>
</div>
</body>
</html>
