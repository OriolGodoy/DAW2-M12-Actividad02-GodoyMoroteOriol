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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = trim($_POST['nombre_sala']); 
    $tipo_sala = $_POST['tipo_sala'];
    $imagen_sala = $_FILES['imagen_sala'];

    $errores = []; 

    if (empty($nombre_sala)) {
        $errores[] = "El nombre de la sala es obligatorio.";
    }
    if (empty($tipo_sala)) {
        $errores[] = "El tipo de sala es obligatorio.";
    }

    if (!empty($nombre_sala) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre_sala)) {
        $errores[] = "El nombre de la sala solo puede contener letras y espacios.";
    }

    if (empty($errores)) {
        $queryNombre = "SELECT COUNT(*) FROM tbl_sala WHERE nombre_sala = ?";
        $stmtNombre = $conn->prepare($queryNombre);
        $stmtNombre->execute([$nombre_sala]);

        if ($stmtNombre->fetchColumn() > 0) {
            $errores[] = "El nombre de la sala ya existe. Por favor, elige otro.";
        }
    }

    if (empty($errores)) {
        try {
            $upload_dir = "../../img/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $imagen_path = ""; 

            if (!empty($imagen_sala['name']) && $imagen_sala['error'] === UPLOAD_ERR_OK) {
                $nombre_unico = uniqid('sala_', true) . '.' . pathinfo($imagen_sala['name'], PATHINFO_EXTENSION);
                $imagen_path = $upload_dir . $nombre_unico;

                if (!move_uploaded_file($imagen_sala['tmp_name'], $imagen_path)) {
                    throw new Exception("Error al mover la imagen al directorio.");
                }

                $imagen_path = "../img/" . $nombre_unico;
            }

            $query = "INSERT INTO tbl_sala (nombre_sala, tipo_sala, imagen_sala) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$nombre_sala, $tipo_sala, $imagen_path]);

            header("Location: ../dashboard-admin.php");
            exit();
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        } catch (Exception $e) {
            $errores[] = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Sala</title>
    <link rel="stylesheet" href="../../css/form-añadir.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="../../js/validacion-añadir-sala.js" defer></script>
</head>
<body>
<div class="main-container">
    <div class="logo-container">
        <img src="../../img/icon.png" class="icon" alt="Logo">
    </div>
    <div class="container">
        <h1>Añadir Sala</h1>

        <?php if (!empty($errores)): ?>
            <div class="errors">
                    <?php foreach ($errores as $error): ?>
                        <?php echo $error; ?>
                    <?php endforeach; ?>
        <?php endif; ?>

        <form id="addSalaForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_sala">Nombre de la Sala</label>
                <input type="text" id="nombre_sala" name="nombre_sala" placeholder="Ingresa el nombre de la sala" onblur="validateNombreSala()">
                <span id="nombreSalaError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="tipo_sala">Tipo de Sala</label>
                <select id="tipo_sala" name="tipo_sala" onblur="validateTipoSala()">
                    <option value="">Selecciona un tipo</option>
                    <option value="terraza">Terraza</option>
                    <option value="comedor">Comedor</option>
                    <option value="privada">Privada</option>
                </select>
                <span id="tipoSalaError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="imagen_sala">Imagen de la Sala</label>
                <input type="file" id="imagen_sala" name="imagen_sala" accept="image/*" onblur="validateImagenSala()">
                <span id="imagenSalaError" class="error-message"></span>
            </div>
            <button type="submit">Añadir Sala</button>
        </form>
    </div>
</div>
</body>
</html>
