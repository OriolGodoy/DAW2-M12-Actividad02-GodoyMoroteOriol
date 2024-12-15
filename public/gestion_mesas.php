    <?php
    session_start();
    require_once "../db/conexion.php";

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../index.php");
        exit();
    }
    
if ($_SESSION['rol_usuario'] !== "Administrador") {
    switch ($_SESSION['rol_usuario']) {
        case 'Camarero':
            header("Location: ./panelCamarero.php");
            exit();
        case 'Gerente':
            header("Location: ./panelGerente.php");
            exit();
        default:
            header("Location: ./dashboard.php");
            exit();
    }
}   


    if (isset($_GET['id_sala'])) {
        $id_sala = $_GET['id_sala'];        

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mesa'])) {
            $id_mesa = $_POST['id_mesa'];
            
            try {
                $conn->beginTransaction();
    
                $query = "DELETE FROM tbl_ocupacion WHERE id_mesa = :id_mesa";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->execute();
    
                $query = "DELETE FROM tbl_mesa WHERE id_mesa = :id_mesa";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->execute();
    
                $conn->commit();
    
                header("Location: gestion_mesas.php?id_sala=" . $id_sala);
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                $message = "Error al eliminar la mesa: " . $e->getMessage();
            }
        }

        try {
            $query = "SELECT * FROM tbl_mesa WHERE id_sala = :id_sala";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt->execute();
            $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Si no hay resultados, devuelve un array vacío
        } catch (PDOException $e) {
            die("Error al consultar las mesas: " . $e->getMessage());
        }

        try {
            $query = "SELECT nombre_sala FROM tbl_sala WHERE id_sala = :id_sala";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt->execute();
            $sala = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            if ($sala === false) {
                die("Error: No se encontró la sala.");
            }
        } catch (PDOException $e) {
            die("Error al obtener los detalles de la sala: " . $e->getMessage());
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión Mesas</title>
        <link rel="stylesheet" href="../css/mesas_comedor.css">
        <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <div class="navbar">
        <a href="dashboard-admin.php"><img src="../img/icon.png" class="icon"></a>
        <div class="user-info">
            <span><?php echo ($_SESSION['nombre_usuario']); ?></span>
            <a href="./historial_ocupaciones.php" class="history-button">Ver Historial</a>
            <a href="./panelAdmin.php" class="logout">Gestionar Usuarios</a>
            <a href="#" class="logout" onclick="cerrarSesion()">Cerrar Sesión</a>
            </div>
    </div>

    <?php if (isset($sala)): ?>
        <div class="banner">
            <h1>Mesas en la sala: <?php echo htmlspecialchars($sala['nombre_sala']); ?></h1>
            <a href="./form/form-añadir-mesa.php?id_sala=<?php echo htmlspecialchars($id_sala); ?>" class="add-button">Añadir Mesa</a>
        </div>

        <?php if ($mesas): ?>
            <div class="slider-container">
                <button id="prevArrow" class="arrow-btn">&lt;</button>
                <div class="slider" id="mesaSlider">
                    <?php foreach ($mesas as $mesa): ?>
                        <div class="option <?php echo $mesa['estado_mesa'] == 'libre' ? 'libre' : 'ocupada'; ?>">
                                <h2>Mesa <?php echo htmlspecialchars($mesa['id_mesa']); ?></h2>
                                <p>Sillas: <?php echo htmlspecialchars($mesa['num_sillas_mesa']); ?></p>
                                <p>Estado: <?php echo htmlspecialchars($mesa['estado_mesa']); ?></p>
                            <div class="button-container">
                                <form action="../actions/ocupar_mesa.php" method="post">
                                    <input type="hidden" name="id_mesa" value="<?php echo htmlspecialchars($mesa['id_mesa']); ?>">
                                    <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($id_sala); ?>">
                                    <input type="hidden" name="estado_actual" value="<?php echo htmlspecialchars($mesa['estado_mesa']); ?>">
                                    <button type="submit" class="<?php echo $mesa['estado_mesa'] == 'libre' ? 'select-button' : 'free-button'; ?>">
                                    <?php echo $mesa['estado_mesa'] == 'libre' ? 'Ocupar' : 'Desocupar'; ?>
                                    </button>
                                </form>
                                <a href="./form/form-editar-mesa.php?id=<?php echo htmlspecialchars($mesa['id_mesa']); ?>" class="edit-button">Editar</a>
                                <form action="" method="post">
                                    <input type="hidden" name="id_mesa" value="<?php echo htmlspecialchars($mesa['id_mesa']); ?>">
                                    <button type="submit" class="delete-button">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button id="nextArrow" class="arrow-btn">&gt;</button>
            </div>
        <?php else: ?>
            <p class="no-mesas">No hay mesas disponibles en esta sala.</p>
        <?php endif; ?>
        <?php else: ?>
            <p>Por favor, selecciona una sala.</p>
        <?php endif; ?>

    <script src="../js/slider.js"></script>
    <script src="../js/sweet_alert.js"></script>
    </body>
    </html>
