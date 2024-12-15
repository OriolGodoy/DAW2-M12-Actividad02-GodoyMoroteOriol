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


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = $_POST['nombre_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $imagen_sala = $_FILES['imagen_sala']['name'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_sala = $_POST['nombre_sala'];
        $tipo_sala = $_POST['tipo_sala'];
        $imagen_sala = $_FILES['imagen_sala']['name'];
        $upload_dir = "../../img/"; 
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); 
        }
        
        $nombre_unico = uniqid('sala_', true) . '.' . pathinfo($imagen_sala, PATHINFO_EXTENSION);
        $imagen_path = $upload_dir . $nombre_unico;
        
        if ($_FILES['imagen_sala']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['imagen_sala']['tmp_name'], $imagen_path)) {
                try {
                    $ruta_relativa = "../img/" . $nombre_unico; 
                    $query = "INSERT INTO tbl_sala (nombre_sala, tipo_sala, imagen_sala) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$nombre_sala, $tipo_sala, $ruta_relativa]);
        
                    header("Location: ../dashboard-admin.php");
                    exit();
                } catch (PDOException $e) {
                    $message = "Error en la base de datos: " . $e->getMessage();
                }
            } else {
                $message = "Error al mover la imagen al directorio de destino.";
            }
        } else {
            $message = "Error al subir la imagen. Código: " . $_FILES['imagen_sala']['error'];
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
            <?php if (!empty($message)): ?>
                <div class="message">
                    <p><?php echo $message; ?></p>
                </div>
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
