<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

require_once "../db/conexion.php"; 

try {
    $query = "SELECT id_sala, nombre_sala FROM tbl_sala WHERE tipo_sala = 'terraza'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    die("Error al consultar salas: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Sala</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_terraza.css"> 
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <img src="../img/icon.png" class="icon">
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
        </div>
    </div>

    <form action="gestion_mesas.php" method="post" class="options">
        <?php if ($salas): ?>
            <?php foreach ($salas as $sala): ?>
                <div class="option">
                    <h2><?php echo htmlspecialchars($sala['nombre_sala']); ?></h2>
                    <div class="button-container">
                        <button type="submit" name="sala" value="<?php echo htmlspecialchars($sala['id_sala']); ?>" class="select-button">
                            Seleccionar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay salas disponibles.</p>
        <?php endif; ?>
    </form>

    <script src="../js/dashboard.js"></script>
</body>

</html>
