<?php
session_start();
require_once "../db/conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php"); 
    exit();
}

if ($_SESSION['rol_usuario'] !== "Administrador") {
    switch ($_SESSION['rol_usuario']) {
        case 'Camarero':
            header("Location: ./panelCamarero.php");
           exit();
        case 'Gerente':
            header("Location: ./panelGerente.php");
           exit();
        default:
            header("Location: ./paginaInicio.php");
           exit();
    }
}


$query = "SELECT u.id_usuario, u.nombre_usuario, u.email_usuario, r.nombre_rol 
          FROM tbl_usuario u
          JOIN tbl_rol r ON u.id_rol = r.id_rol";
$stmt = $conn->prepare($query);
$stmt->execute();  

$rolesQuery = "SELECT * FROM tbl_rol";
$rolesStmt = $conn->prepare($rolesQuery);
$rolesStmt->execute(); 

if (isset($_GET['delete'])) {
    $id_usuario = $_GET['delete'];

    try {
        $conn->beginTransaction();

        $deleteOcupacionesQuery = "DELETE FROM tbl_ocupacion WHERE id_usuario = :id_usuario";
        $deleteOcupacionesStmt = $conn->prepare($deleteOcupacionesQuery);
        $deleteOcupacionesStmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $deleteOcupacionesStmt->execute();

        $deleteReservasQuery = "DELETE FROM tbl_reserva WHERE id_usuario = :id_usuario";
        $deleteReservasStmt = $conn->prepare($deleteReservasQuery);
        $deleteReservasStmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $deleteReservasStmt->execute();

        $deleteUsuarioQuery = "DELETE FROM tbl_usuario WHERE id_usuario = :id_usuario";
        $deleteUsuarioStmt = $conn->prepare($deleteUsuarioQuery);
        $deleteUsuarioStmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $deleteUsuarioStmt->execute();

        $conn->commit();

        header("Location: panelAdmin.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al eliminar el usuario: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../css/panelAdmin.css">
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
            <a href="./dashboard-admin.php" class="logout">Gestionar Salas</a>
            <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
            </div>
    </div>
    <div class="banner">
        <h1>Quieres crear un usuario?</h1>
        <a href="form/form-añadir.php" class="add-button">Añadir Usuario</a>
    </div>

    <div class="users-list">
        <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="user-card">
                <h3><?php echo $user['nombre_usuario']; ?></h3>
                <p>Email: <?php echo $user['email_usuario']; ?></p>
                <p>Cargo: <?php echo $user['nombre_rol']; ?></p>
                <a href="form/form-edit.php?edit=<?php echo $user['id_usuario']; ?>" class="edit-button">Editar</a>
                <a href="#" class="delete-button" onclick="confirmarEliminacion(<?php echo $user['id_usuario']; ?>)">Eliminar</a>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="../js/sweet_alert.js"></script>
</body>
</html>
