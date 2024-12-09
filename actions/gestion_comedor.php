<?php
include_once '../db/conexion.php';

// Inicializamos la variable $comedor como vacía
$comedor = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Si la acción es 'reservar'
        if (isset($_POST['mesa']) && isset($_POST['reservar'])) {
            $usuario_id = $_SESSION['usuario'];
            $mesa_id = $_POST['mesa'];
            $nombre = $_POST['nombre']; // Asegúrate de recibir el nombre si lo necesitas
            $personas = $_POST['personas']; // Asegúrate de recibir el número de personas

            // Iniciar una transacción
            $conn->beginTransaction();

            // Verificar si el número de personas es válido para la mesa seleccionada
            $query = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = :mesa_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
            $stmt->execute();
            $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mesa && $personas > $mesa['num_sillas_mesa']) {
                $conn->rollBack(); // Revertir si hay un error lógico
                $error = "El número de personas no puede ser mayor al número de sillas disponibles en la mesa.";
            } else {
                // Realizar la reserva
                $query = "INSERT INTO tbl_ocupacion (id_cliente, id_mesa, fecha_hora_ocupacion) 
                          VALUES (:usuario_id, :mesa_id, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                $stmt->execute();

                // Actualizar el estado de la mesa a 'ocupada'
                $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = :mesa_id";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                $stmt->execute();

                // Confirmar la transacción
                $conn->commit();
                $success = "Reserva realizada con éxito.";
            }
        }

        // Si la acción es 'desocupar'
        if (isset($_POST['desocupar'])) {
            $mesa_id = $_POST['desocupar'];

            // Actualizar el estado de la mesa a 'libre'
            $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = :mesa_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
            $stmt->execute();

            $success = "Mesa desocupada con éxito.";
        }
    } catch (PDOException $e) {
        // Si hay un error, revertir cualquier cambio
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $error = "Error en la operación: " . $e->getMessage();
    }

    // Redirigir a la página de mesas con el resultado
    header("Location: ../public/gestion_mesas.php");
    exit();
}
?>
