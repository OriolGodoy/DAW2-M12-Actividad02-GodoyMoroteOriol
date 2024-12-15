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
            header("Location: ../paginaInicio.php");
            exit();
    }
}   

if (isset($_GET['delete'])) {
    $id_sala = $_GET['delete'];

    try {
        $conn->beginTransaction();

        $deleteOcupacionesQuery = "DELETE FROM tbl_ocupacion WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesa WHERE id_sala = :id_sala)";
        $deleteOcupacionesStmt = $conn->prepare($deleteOcupacionesQuery);
        $deleteOcupacionesStmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $deleteOcupacionesStmt->execute();

        $deleteReservasQuery = "DELETE FROM tbl_reserva WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesa WHERE id_sala = :id_sala)";
        $deleteReservasStmt = $conn->prepare($deleteReservasQuery);
        $deleteReservasStmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $deleteReservasStmt->execute();

        $deleteMesasQuery = "DELETE FROM tbl_mesa WHERE id_sala = :id_sala";
        $deleteMesasStmt = $conn->prepare($deleteMesasQuery);
        $deleteMesasStmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $deleteMesasStmt->execute();

        $deleteSalaQuery = "DELETE FROM tbl_sala WHERE id_sala = :id_sala";
        $deleteSalaStmt = $conn->prepare($deleteSalaQuery);
        $deleteSalaStmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $deleteSalaStmt->execute();

        $conn->commit();

        header("Location: choose_comedor_admin.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        die("Error al eliminar la sala: " . $e->getMessage());
    }
}


try {
    $query = "SELECT id_sala, nombre_sala, imagen_sala FROM tbl_sala WHERE tipo_sala = 'comedor'";
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
    <title>Seleccionar comedor</title>
    <link rel="stylesheet" href="../css/choose_todos">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="navbar">
    <a href="dashboard-admin.php"><img src="../img/icon.png" class="icon"></a>
    <div class="user-info">
        <span><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        <a href="./historial_ocupaciones.php" class="history-button">Ver Historial</a>
        <a href="./panelAdmin.php" class="logout">Gestionar Usuarios</a>
        <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>
</div>
<div class="banner">
    <h1>Quieres crear una sala?</h1>
    <a href="./form/form-añadir-sala.php" class="add-button">Añadir Sala</a>
</div>

<div class="options">
    <?php if ($salas): ?>
        <?php foreach ($salas as $sala): ?>
            <div class="option" style="background-image: url('<?php echo htmlspecialchars($sala['imagen_sala']); ?>');">
                <h2><?php echo htmlspecialchars($sala['nombre_sala']); ?></h2>
                <div class="button-container">
                    <a href="gestion_mesas.php?id_sala=<?php echo htmlspecialchars($sala['id_sala']); ?>" class="select-button">Seleccionar</a>
                    <a href="./form/form-editar-sala.php?id=<?php echo htmlspecialchars($sala['id_sala']); ?>" class="edit-button">Editar</a>
                    <a href="#" class="delete-button" onclick="confirmarEliminacionSalaComedor(<?php echo $sala['id_sala']; ?>)">Eliminar</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay comedores disponibles.</p>
    <?php endif; ?>
</div>

<script src="../js/sweet_alert.js"></script>

</body>
</html>
