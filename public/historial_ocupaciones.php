<?php
session_start();
include_once '../db/conexion.php';

// Consultar ocupaciones con filtros opcionales
$whereClauses = [];
$params = [];
$types = '';

// Filtros
if (!empty($_GET['camarero'])) {
    $whereClauses[] = 'c.nombre_camarero LIKE ?';
    $params[] = '%' . $_GET['camarero'] . '%';
    $types .= 's';
}

if (!empty($_GET['cliente'])) {
    $whereClauses[] = 'cl.nombre LIKE ?';
    $params[] = '%' . $_GET['cliente'] . '%';
    $types .= 's';
}

if (!empty($_GET['num_personas'])) {
    $whereClauses[] = 'cl.num_personas = ?';
    $params[] = $_GET['num_personas'];
    $types .= 'i';
}

if (!empty($_GET['mesa'])) {
    $whereClauses[] = 'm.id_mesa = ?';
    $params[] = $_GET['mesa'];
    $types .= 'i';
}

// Construir la consulta con los filtros
$query = "SELECT o.id_ocupacion, c.nombre_camarero, cl.nombre AS cliente, cl.num_personas, m.id_mesa, o.fecha_hora_ocupacion, o.fecha_hora_desocupacion
        FROM tbl_ocupacion o
        JOIN tbl_camarero c ON o.id_camarero = c.id_camarero
        JOIN tbl_cliente cl ON o.id_cliente = cl.id_cliente
        JOIN tbl_mesa m ON o.id_mesa = m.id_mesa";

if (!empty($whereClauses)) {
    $query .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = mysqli_prepare($conn, $query);
if ($types) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ocupaciones</title>
    <link rel="stylesheet" href="../css/historial.css">
</head>
<body>
    <div class="container">
        <h1>Historial de Ocupaciones</h1>

        <!-- Formulario de filtros -->
        <form method="GET">
            <label for="camarero">Camarero:</label>
            <input type="text" name="camarero" id="camarero" placeholder="Nombre del camarero">

            <label for="cliente">Cliente:</label>
            <input type="text" name="cliente" id="cliente" placeholder="Nombre del cliente">

            <label for="num_personas">Nº Personas:</label>
            <input type="number" name="num_personas" id="num_personas" placeholder="Número de personas">

            <label for="mesa">Mesa:</label>
            <input type="number" name="mesa" id="mesa" placeholder="ID de la mesa">

            <button type="submit">Filtrar</button>
        </form>

        <!-- Tabla de ocupaciones -->
        <table>
            <tr>
                <th>ID Ocupación</th>
                <th>Camarero</th>
                <th>Cliente</th>
                <th>Nº Personas</th>
                <th>Mesa</th>
                <th>Fecha Ocupación</th>
                <th>Fecha Desocupación</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_ocupacion']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_camarero']) ?></td>
                    <td><?= htmlspecialchars($row['cliente']) ?></td>
                    <td><?= htmlspecialchars($row['num_personas']) ?></td>
                    <td><?= htmlspecialchars($row['id_mesa']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_hora_ocupacion']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_hora_desocupacion'] ?? 'Sin desocupar') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
