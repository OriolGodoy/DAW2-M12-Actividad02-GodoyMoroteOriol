<?php
require_once "../db/conexion.php";
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$reservaQuery = "SELECT r.nombre_cliente, u.nombre_usuario, r.id_mesa, r.fecha_reserva, r.hora_inicio, r.hora_fin
                 FROM tbl_reserva r
                 JOIN tbl_usuario u ON r.id_usuario = u.id_usuario
                 ORDER BY r.fecha_reserva DESC";
$reservaStmt = $conn->prepare($reservaQuery);
$reservaStmt->execute();

$ocupacionQuery = "SELECT o.id_mesa, u.nombre_usuario, o.fecha_hora_ocupacion, o.fecha_hora_desocupacion
                   FROM tbl_ocupacion o
                   JOIN tbl_usuario u ON o.id_usuario = u.id_usuario
                   ORDER BY o.fecha_hora_ocupacion DESC";
$ocupacionStmt = $conn->prepare($ocupacionQuery);
$ocupacionStmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservas y Ocupaciones</title>
    <link rel="stylesheet" href="../css/historial.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/validacion-reserva.js"></script>
</head>
<body>
<div class="navbar">
    <a href="dashboard-admin.php"><img src="../img/icon.png" class="icon"></a>
    <div class="user-info">
        <span><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        <a href="./panelAdmin.php" class="logout">Gestionar Usuarios</a>
        <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
    </div>
</div>

<div class="container">
    <h1>Historial de Reservas</h1>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Usuario</th>
                <th>Mesa</th>
                <th>Fecha Reserva</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $reservaStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['nombre_cliente']}</td>
                        <td>{$row['nombre_usuario']}</td>
                        <td>{$row['id_mesa']}</td>
                        <td>{$row['fecha_reserva']}</td>
                        <td>{$row['hora_inicio']}</td>
                        <td>{$row['hora_fin']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h1>Historial de Ocupaciones</h1>
    <table>
        <thead>
            <tr>
                <th>Mesa</th>
                <th>Usuario</th>
                <th>Fecha y Hora Ocupación</th>
                <th>Fecha y Hora Desocupación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $ocupacionStmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id_mesa']}</td>
                        <td>{$row['nombre_usuario']}</td>
                        <td>{$row['fecha_hora_ocupacion']}</td>
                        <td>{$row['fecha_hora_desocupacion']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script src="../js/sweet_alert.js"></script>

</body>
</html>
