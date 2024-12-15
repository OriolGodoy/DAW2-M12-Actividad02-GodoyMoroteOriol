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
            header("Location: ../paginaInicio.php");
            exit();
    }
}

$message = "";

if (isset($_GET['id'])) {
    $id_sala = $_GET['id'];

    $query = "SELECT * FROM tbl_sala WHERE id_sala = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_sala]);

    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sala) {
        $message = "Sala no encontrada.";
    }
} else {
    header("Location: ../dashboard-admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = trim($_POST['nombre_sala']);
    $imagen_sala = $_FILES['imagen_sala']['name'];

    if (empty($nombre_sala)) {
        $message = "Por favor, complete el campo del nombre de la sala.";
    } else {
        $checkNombreQuery = "SELECT COUNT(*) FROM tbl_sala WHERE nombre_sala = ? AND id_sala != ?";
        $checkStmt = $conn->prepare($checkNombreQuery);
        $checkStmt->execute([$nombre_sala, $id_sala]);
        $nombreExiste = $checkStmt->fetchColumn();

        if ($nombreExiste > 0) {
            $message = "Error: El nombre de la sala ya existe.";
        } else {
            $imagen_path = $sala['imagen_sala'];
            $upload_dir = "../../img/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (!empty($imagen_sala) && $_FILES['imagen_sala']['error'] === UPLOAD_ERR_OK) {
                $nombre_unico = uniqid('sala_', true) . '.' . pathinfo($imagen_sala, PATHINFO_EXTENSION);
                $imagen_path = $upload_dir . $nombre_unico;

                if (!move_uploaded_file($_FILES['imagen_sala']['tmp_name'], $imagen_path)) {
                    $message = "Error al mover el archivo de la imagen.";
                } else {
                    $ruta_relativa = "../img/" . $nombre_unico;
                }
            } else {
                $ruta_relativa = $sala['imagen_sala'];
            }

            if (empty($message)) {
                try {
                    $updateQuery = "UPDATE tbl_sala SET nombre_sala = ?, imagen_sala = ? WHERE id_sala = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->execute([$nombre_sala, $ruta_relativa, $id_sala]);

                    header("Location: ../dashboard-admin.php");
                    exit();
                } catch (PDOException $e) {
                    $message = "Error al actualizar la sala: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="../../css/form-editar.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="../../js/validacion-editar-sala.js" defer></script>
</head>
<body>
<div class="main-container">
    <div class="logo-container">
        <img src="../../img/icon.png" class="icon" alt="Logo">
    </div>

    <div class="container">
        <h1>Editar Sala</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message" style="color: red;">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form id="editSalaForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_sala">Nombre de la Sala</label>
                <input type="text" id="nombre_sala" name="nombre_sala" value="<?php echo htmlspecialchars($sala['nombre_sala']); ?>" onblur=" validateNombreSala()">
                <span id="nombreSalaError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="imagen_sala">Imagen de la Sala</label>
                <input type="file" id="imagen_sala" name="imagen_sala" accept="image/*">
                <p>Imagen actual: <a href="<?php echo $sala['imagen_sala']; ?>" target="_blank">Ver Imagen</a></p>
            </div>
            <button type="submit">Guardar Cambios</button>
            <a href="javascript:void(0);" onclick="window.history.back();" class="cancel-button">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
