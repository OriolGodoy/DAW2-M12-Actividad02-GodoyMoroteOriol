<?php
session_start();
include '../../db/conexion.php';  

$salaSeleccionada = isset($_POST['sala']) ? $_POST['sala'] : '';
$mesaSeleccionada = isset($_POST['mesa']) ? $_POST['mesa'] : '';
$horaInicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : '';
$horaFin = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : '';
$nombreCliente = isset($_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : '';
$fechaReserva = isset($_POST['fecha_reserva']) ? $_POST['fecha_reserva'] : '';

$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($nombreCliente) || empty($salaSeleccionada) || empty($mesaSeleccionada) || empty($horaInicio) || empty($horaFin) || empty($fechaReserva)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!empty($fechaReserva) && !DateTime::createFromFormat('Y-m-d', $fechaReserva)) {
        $errores[] = "La fecha ingresada no es válida.";
    }

    if (!empty($fechaReserva)) {
        $fechaActual = new DateTime();
        $fechaIngresada = new DateTime($fechaReserva);
        if ($fechaIngresada <= $fechaActual) {
            $errores[] = "La fecha de la reserva no puede ser en el pasado.";
        }
    }

    if (!empty($horaInicio) && !empty($horaFin)) {
        $horaInicioObj = DateTime::createFromFormat('H:i', $horaInicio);
        $horaFinObj = DateTime::createFromFormat('H:i', $horaFin);

        if (!$horaInicioObj || !$horaFinObj) {
            $errores[] = "El formato de las horas no es válido.";
        } elseif ($horaInicioObj >= $horaFinObj) {
            $errores[] = "La hora de inicio debe ser menor que la hora de fin.";
        }
    }

    if (empty($errores) && !empty($mesaSeleccionada) && !empty($fechaReserva)) {
        $query = "SELECT hora_inicio, hora_fin FROM tbl_reserva WHERE id_mesa = :id_mesa AND fecha_reserva = :fecha_reserva";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_mesa', $mesaSeleccionada, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_reserva', $fechaReserva, PDO::PARAM_STR);
        $stmt->execute();
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reservas as $reserva) {
            $inicioReservado = new DateTime($reserva['hora_inicio']);
            $finReservado = new DateTime($reserva['hora_fin']);

            if (
                ($horaInicioObj >= $inicioReservado && $horaInicioObj < $finReservado) ||
                ($horaFinObj > $inicioReservado && $horaFinObj <= $finReservado) ||
                ($horaInicioObj <= $inicioReservado && $horaFinObj >= $finReservado)
            ) {
                $errores[] = "El horario seleccionado ya está ocupado.";
                break;
            }
        }
    }

    if (empty($errores)) {
        $id_usuario = $_SESSION['usuario_id']; 

        try {
            $sql = "INSERT INTO tbl_reserva (id_usuario, id_mesa, id_sala, nombre_cliente, fecha_reserva, hora_inicio, hora_fin) 
                    VALUES (:id_usuario, :id_mesa, :id_sala, :nombre_cliente, :fecha_reserva, :hora_inicio, :hora_fin)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_mesa', $mesaSeleccionada, PDO::PARAM_INT);
            $stmt->bindParam(':id_sala', $salaSeleccionada, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_cliente', $nombreCliente, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_reserva', $fechaReserva, PDO::PARAM_STR);
            $stmt->bindParam(':hora_inicio', $horaInicio, PDO::PARAM_STR);
            $stmt->bindParam(':hora_fin', $horaFin, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: ../panelCamarero.php");
        exit();
        } catch (PDOException $e) {
            $errores[] = "Error al guardar la reserva: " . $e->getMessage();
        }
    }
}

$query = "SELECT id_sala, nombre_sala FROM tbl_sala";
$resultSalas = $conn->query($query);

$queryMesas = "SELECT id_mesa, num_sillas_mesa, estado_mesa FROM tbl_mesa WHERE id_sala = :id_sala AND estado_mesa = 'libre'";
$stmtMesas = $conn->prepare($queryMesas);
$stmtMesas->bindParam(':id_sala', $salaSeleccionada, PDO::PARAM_INT);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Mesa</title>
    <link rel="stylesheet" href="../../css/form-añadir.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/validacion-reserva.js"></script>
</head>
<body>
    <div class="main-container">
        <div class="logo-container">
            <a href="../panelCamarero.php"><img src="../../img/icon.png" class="icon" alt="Logo"></a>
        </div>
        <div class="container">
            <h1>Formulario de Reserva de Mesa</h1>

            <?php if (!empty($errores)): ?>
                <div style="color: red;">
                        <?php foreach ($errores as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <label for="nombre_cliente">Nombre del cliente:</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($nombreCliente); ?>" onblur="validaNombreCliente()" >
                <span id="nombre_cliente_error" class="error-message"></span>
                <br>
                <label for="fecha_reserva">Fecha de la reserva:</label>
                <input type="date" name="fecha_reserva" id="fecha_reserva" value="<?php echo htmlspecialchars($fechaReserva); ?>" onblur="validaFechaReserva()">
                <span id="fecha_reserva_error" class="error-message"></span>
                <br>
                <label for="sala">Selecciona una sala:</label>
                <select name="sala" id="sala"  onchange="this.form.submit()" onblur="validaSala()">
                    <option value="">Selecciona una sala</option>
                    <?php while ($row = $resultSalas->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['id_sala']; ?>" <?php echo ($salaSeleccionada == $row['id_sala']) ? 'selected' : ''; ?>>
                            <?php echo $row['nombre_sala']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <span id="sala_error" class="error-message"></span>
                <?php
                if ($salaSeleccionada) {
                    $stmtMesas->execute();
                    $resultMesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);
                    echo '<br><label for="mesa">Selecciona una mesa:</label>';
                    echo '<select name="mesa" id="mesa" onblur="validaMesa()">';
                    echo '<option value="">Selecciona una mesa</option>';
                    foreach ($resultMesas as $mesa) {
                        echo '<option value="' . $mesa['id_mesa'] . '" ' . (($mesaSeleccionada == $mesa['id_mesa']) ? 'selected' : '') . '>';
                        echo 'Mesa ' . $mesa['id_mesa'] . ' (' . $mesa['num_sillas_mesa'] . ' sillas) - Estado: ' . $mesa['estado_mesa'];
                        echo '</option>';
                    }
                    echo '</select>';
                }
                ?>
                <span id="mesa_error" class="error-message"></span>
                <br>
                <label for="hora_inicio">Hora de inicio:</label>
                <select name="hora_inicio" id="hora_inicio" onblur="validaHoraInicio()">
                    <?php for ($i = 8; $i <= 22; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>" 
                            <?php echo ($horaInicio == str_pad($i, 2, '0', STR_PAD_LEFT) . ':00') ? 'selected' : ''; ?>>
                            <?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <span id="hora_inicio_error" class="error-message"></span>
                <label for="hora_fin">Hora de fin:</label>
                <select name="hora_fin" id="hora_fin" onblur="validaHoraFin()">
                    <?php for ($i = 8; $i <= 22; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>" 
                            <?php echo ($horaFin == str_pad($i, 2, '0', STR_PAD_LEFT) . ':00') ? 'selected' : ''; ?>>
                            <?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <span id="hora_fin_error" class="error-message"></span>
                <br>
                <button type="submit">Reservar</button>
            </form>
        </div>
    </div>
</body>
</html>
