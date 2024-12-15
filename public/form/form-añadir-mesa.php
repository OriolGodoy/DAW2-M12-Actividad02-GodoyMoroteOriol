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

if (isset($_GET['id_sala'])) {
    $id_sala = $_GET['id_sala'];

    try {
        $query = "SELECT nombre_sala FROM tbl_sala WHERE id_sala = :id_sala";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt->execute();
        $sala = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener los detalles de la sala: " . $e->getMessage());
    }
} else {
    echo "No se ha seleccionado una sala válida.";
    exit();
}

$errores = []; // Array para almacenar errores

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_sillas = $_POST['num_sillas'];

    if (empty($num_sillas)) {
        $errores[] = "Debe seleccionar un número de sillas.";
    } elseif (!is_numeric($num_sillas) || $num_sillas < 2 || $num_sillas > 10) {
        $errores[] = "El número de sillas debe ser un valor válido entre 2 y 10.";
    }

    if (empty($errores)) {
        try {
            $insertQuery = "INSERT INTO tbl_mesa (id_sala, num_sillas_mesa) VALUES (:id_sala, :num_sillas)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $insertStmt->bindParam(':num_sillas', $num_sillas, PDO::PARAM_INT);
            $insertStmt->execute();

            // Redireccionar después de insertar
            header("Location: ../gestion_mesas.php?id_sala=" . $id_sala);
            exit();
        } catch (PDOException $e) {
            $errores[] = "Error al añadir la mesa: " . $e->getMessage();
        }
    }
}

try {
    $query = "SELECT id_mesa, num_sillas_mesa, estado_mesa FROM tbl_mesa WHERE id_sala = :id_sala";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt->execute();
    $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar las mesas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Mesa</title>
    <link rel="stylesheet" href="../../css/form-añadir-mesas.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/validacion-añadir-mesa.js" defer></script>
</head>
<body>
<div class="main-container">      
    <div class="logo-container">
        <img src="../../img/icon.png" class="icon" alt="Logo">
    </div>
    <div class="container">
        <form action="" method="post" id="addMesa" class="addMesa">
            <h1>AÑADIR MESA</h1>

            <!-- Mostrar errores PHP -->
            <?php if (!empty($errores)): ?>
                <div class="error-container" style="color: red;">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <label for="num_sillas">Número de Sillas:</label>
            <select id="num_sillas" name="num_sillas" onblur="validateNumSillas()">
                <option value="">Seleccione el número de sillas</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="10">10</option>
            </select>
            <span id="numSillaError" class="error-message"></span>

            <button type="submit" class="add-button">Añadir Mesa</button>
        </form>

        <div class="mesas-list">
            <h2>Mesas en la Sala "<?php echo htmlspecialchars($sala['nombre_sala']); ?>"</h2>
            <?php if (!empty($mesas)): ?>
                <ul>
                    <?php foreach ($mesas as $mesa): ?>
                        <li>
                            <strong>ID Mesa:</strong> <?php echo htmlspecialchars($mesa['id_mesa']); ?> - 
                            <strong>Sillas:</strong> <?php echo htmlspecialchars($mesa['num_sillas_mesa']); ?> - 
                            <strong>Estado:</strong> <?php echo htmlspecialchars($mesa['estado_mesa']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay mesas disponibles en esta sala.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
