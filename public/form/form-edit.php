<?php
session_start();

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

require_once "../../db/conexion.php";

$message = "";
$rolesQuery = "SELECT * FROM tbl_rol";
$rolesStmt = $conn->query($rolesQuery);

if (isset($_GET['edit'])) {
    $id_usuario = $_GET['edit'];

    $query = "SELECT u.id_usuario, u.nombre_usuario, u.email_usuario, u.id_rol 
              FROM tbl_usuario u
              WHERE u.id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Error: Usuario no encontrado.");
    }
} else {
    die("Error: ID de usuario no proporcionado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $email_usuario = trim($_POST['email_usuario']);
    $id_rol = $_POST['id_rol'];

    if (empty($nombre_usuario) || strlen($nombre_usuario) < 3) {
        $message = "Error: El nombre debe tener al menos 3 caracteres.";
    } elseif (empty($email_usuario) || !filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $message = "Error: El correo electrónico no es válido.";
    } else {
        $checkEmailQuery = "SELECT COUNT(*) FROM tbl_usuario WHERE email_usuario = :email_usuario AND id_usuario != :id_usuario";
        $emailStmt = $conn->prepare($checkEmailQuery);
        $emailStmt->bindParam(':email_usuario', $email_usuario);
        $emailStmt->bindParam(':id_usuario', $id_usuario);
        $emailStmt->execute();
        $emailExists = $emailStmt->fetchColumn();

        if ($emailExists > 0) {
            $message = "Error: El correo electrónico ya está en uso.";
        } else {
            $updateQuery = "UPDATE tbl_usuario 
                            SET nombre_usuario = :nombre_usuario, email_usuario = :email_usuario, id_rol = :id_rol 
                            WHERE id_usuario = :id_usuario";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':nombre_usuario', $nombre_usuario);
            $updateStmt->bindParam(':email_usuario', $email_usuario);
            $updateStmt->bindParam(':id_rol', $id_rol);
            $updateStmt->bindParam(':id_usuario', $id_usuario);

            if ($updateStmt->execute()) {
                header("Location: ../panelAdmin.php");
                exit();
            } else {
                $message = "Error: No se pudo actualizar el usuario.";
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
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../css/form-editar.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="../../js/validacion-edit.js" defer></script>

</head>
<body>
<div class="main-container">
    <div class="logo-container">
        <img src="../../img/icon.png" class="icon" alt="Logo">
    </div>

    <div class="container">
        <h1>Editar Usuario</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="form-edit.php?edit=<?php echo $user['id_usuario']; ?>" method="POST" id="editUserForm">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($user['nombre_usuario']); ?>" onblur="validateNombre()">
                <span id="nombreError"></span>
            </div>

            <div class="form-group">
                <label for="email_usuario">Email</label>
                <input type="email" id="email_usuario" name="email_usuario" value="<?php echo htmlspecialchars($user['email_usuario']); ?>" onblur="validateEmail()">
                <span id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="id_rol">Rol</label>
                <select id="id_rol" name="id_rol" onblur="validateRol()">
                    <?php while ($role = $rolesStmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $role['id_rol']; ?>" <?php echo ($role['id_rol'] == $user['id_rol']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($role['nombre_rol']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <span id="roleError"></span>
            </div>

            <input type="hidden" name="id_usuario" value="<?php echo $user['id_usuario']; ?>">
            <button type="submit" name="update">Actualizar</button>
        </form>
    </div>
</div>
</body>
</html>
