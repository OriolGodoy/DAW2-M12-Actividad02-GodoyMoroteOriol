<?php
session_start();
require_once "../../db/conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['rol_usuario'] !== "Administrador") {
    switch ($_SESSION['rol_usuario']) {
        case 'Camarero':
            header("Location: ../panelCamarero.php");
            exit();
        case 'Gerente':
            header("Location: ../panelGerente.php");
            exit();
        default:
            header("Location: ../dashboard.php");
            exit();
    }
}   

if (isset($_GET['id'])) {
    $id_mesa = $_GET['id'];

    try {
        $query = "SELECT * FROM tbl_mesa WHERE id_mesa = :id_mesa";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt->execute();
        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mesa) {
            die("Mesa no encontrada.");
        }

        $querySalas = "SELECT id_sala, nombre_sala FROM tbl_sala";
        $stmtSalas = $conn->prepare($querySalas);
        $stmtSalas->execute();
        $salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error al obtener los datos: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $num_sillas = $_POST['num_sillas'] ?? $mesa['num_sillas_mesa'];
        $id_sala = $_POST['id_sala'] ?? $mesa['id_sala'];
        try {
            $query = "UPDATE tbl_mesa 
                      SET num_sillas_mesa = :num_sillas, id_sala = :id_sala 
                      WHERE id_mesa = :id_mesa";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':num_sillas', $num_sillas, PDO::PARAM_INT);
            $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../gestion_mesas.php?id_sala=" . $id_sala);
            exit();
        } catch (PDOException $e) {
            $message = "Error al actualizar la mesa: " . $e->getMessage();
        }
    }
} else {
    die("ID de mesa no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link rel="stylesheet" href="../../css/form-edit-mesas.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<div class="main-container">
    <div class="logo-container">
        <img src="../../img/icon.png" class="icon" alt="Logo">
    </div>
    <div class="container">
        <h1>Editar Mesa <?php echo htmlspecialchars($mesa['id_mesa']); ?></h1>

        <?php if (isset($message)): ?>
            <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="post" class="editMesa">
            <label for="num_sillas">Número de Sillas:</label>
            <select name="num_sillas" id="num_sillas" onblur=" validateNumSillas()">
                <?php
                $opciones_sillas = [2, 3, 4, 5, 6, 10]; 
                foreach ($opciones_sillas as $opcion) {
                    $selected = $opcion == $mesa['num_sillas_mesa'] ? 'selected' : '';
                    echo "<option value=\"$opcion\" $selected>$opcion</option>";
                }
                ?>
            </select>
            <span id="numSillaError" class="error-message"></span>

            <label for="id_sala">Sala:</label>
            <select name="id_sala" id="id_sala" onblur=" validateSala()">
                <?php
                foreach ($salas as $sala) {
                    $selected = $sala['id_sala'] == $mesa['id_sala'] ? 'selected' : '';
                    echo "<option value=\"{$sala['id_sala']}\" $selected>{$sala['nombre_sala']}</option>";
                }
                ?>
            </select>
            <span id="salaError" class="error-message"></span>

            <button type="submit">Guardar Cambios</button>
            <a href="../gestion_mesas.php?id_sala=<?php echo htmlspecialchars($mesa['id_sala']); ?>" class="cancel-button">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
