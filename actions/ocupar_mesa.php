<?php
session_start();
require_once "../db/conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mesa'], $_POST['id_sala'], $_POST['estado_actual'])) {
    $id_mesa = $_POST['id_mesa'];
    $id_sala = $_POST['id_sala'];
    $estado_actual = $_POST['estado_actual'];

    $nuevo_estado = $estado_actual === 'libre' ? 'ocupada' : 'libre';

    $id_usuario =  $_SESSION['usuario_id'];  

    try {
        $conn->beginTransaction();

        if ($nuevo_estado === 'ocupada') {
            $fecha_hora_ocupacion = date('Y-m-d H:i:s');

            $insertQuery = "INSERT INTO tbl_ocupacion (id_mesa, id_usuario, fecha_hora_ocupacion) 
                            VALUES (:id_mesa, :id_usuario, :fecha_hora_ocupacion)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $insertStmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $insertStmt->bindParam(':fecha_hora_ocupacion', $fecha_hora_ocupacion, PDO::PARAM_STR);
            $insertStmt->execute();
        } else {
            $fecha_hora_desocupacion = date('Y-m-d H:i:s');
            $updateQuery = "UPDATE tbl_ocupacion 
                            SET fecha_hora_desocupacion = :fecha_hora_desocupacion
                            WHERE id_mesa = :id_mesa AND fecha_hora_desocupacion IS NULL";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':fecha_hora_desocupacion', $fecha_hora_desocupacion, PDO::PARAM_STR);
            $updateStmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $updateStmt->execute();
        }

        $query = "UPDATE tbl_mesa SET estado_mesa = :nuevo_estado WHERE id_mesa = :id_mesa";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_STR);
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();

        header("Location: ../public/gestion_mesas.php?id_sala=" . $id_sala);
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Error al procesar la solicitud: " . $e->getMessage());
    }
} else {
    header("Location: ../public/gestion_mesas.php?error=missing_parameters");
    exit();
}
?>
