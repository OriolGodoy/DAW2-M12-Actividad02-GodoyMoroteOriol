<?php
include_once '../db/conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
    // Recoge la sala seleccionada por POST 
    $sala = $_POST['sala'];
    try {
        // ====== QUERY PARA OBTENER EL ID DE LA SALA ========
        $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = :sala";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sala', $sala, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $id_sala = $row['id_sala'];
            
            // ====== QUERY PARA OBTENER MESAS DE LA SALA SELECCIONADA ========
            $queryMesas = "SELECT * FROM tbl_mesa WHERE id_sala = :id_sala";
            $stmtMesas = $conn->prepare($queryMesas);
            $stmtMesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmtMesas->execute();
            $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "No se ha encontrado ninguna sala con el nombre especificado.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ====== PROCESO DE MANEJO PARA LA OCUPACIÓN DE LAS MESAS SEGÚN LA RESERVA ========
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserva'])) {
    $usuario_id = $_SESSION['usuario'];
    $nombre = $_POST['nombre'];
    $personas = $_POST['personas'];
    $mesa_id = $_POST['mesa_id'];

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Obtén el número de sillas de la mesa asociada
        $query = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = :mesa_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
        $stmt->execute();
        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si hay más personas que sillas, se muestra un error
        if ($personas > $mesa['num_sillas_mesa']) {
            $error = "El número de personas no puede ser mayor al número de sillas disponibles en la mesa.";
        } else {
            // Inserta la reserva
            $queryReserva = "INSERT INTO tbl_ocupacion (id_cliente, id_mesa, fecha_hora_ocupacion) VALUES (:usuario_id, :mesa_id, NOW())";
            $stmtReserva = $conn->prepare($queryReserva);
            $stmtReserva->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtReserva->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
            $stmtReserva->execute();

            // Actualiza el estado de la mesa
            $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = :mesa_id";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $success = "Reserva realizada con éxito.";

            // Confirma la transacción
            $conn->commit();
        }
    } catch (PDOException $e) {
        // Deshacer cambios en caso de error
        $conn->rollBack();
        $error = $e->getMessage();
        echo "ERROR: " . $error;
    }
    header("Location: ../public/gestion_mesas.php");
    exit();
}

// ===== QUERY PARA EL MANEJO DE DESOCUPACIÓN DE UNA MESA =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
    $mesa_id = $_POST['desocupar'];

    try {
        // Actualiza el estado de la mesa a libre
        $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = :mesa_id";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $success = "Mesa desocupada con éxito.";
    } catch (PDOException $e) {
        $error = "Hubo un error al desocupar la mesa: " . $e->getMessage();
        echo "ERROR: " . $error;
    }
    header("Location: ../public/gestion_mesas.php");
    exit();
}
?>
