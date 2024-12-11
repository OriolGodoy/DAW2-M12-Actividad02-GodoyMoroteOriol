<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../index.php");
    exit();
}

require_once "../../db/conexion.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email_usuario'];
    $password = $_POST['password_usuario'];
    $confirm_password = $_POST['confirm_password'];
    $rol = $_POST['id_rol'];

    if ($password !== $confirm_password) {
        $message = "Error: Las contraseñas no coinciden.";
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO tbl_usuario (nombre_usuario, email_usuario, password_usuario, id_rol) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$nombre, $email, $hashed_password, $rol]);


            header("Location: ../panelAdmin.php");
            exit();
        exit();
        } catch (PDOException $e) {
            $message = "Error: No se pudo añadir el usuario. Por favor, intenta de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario</title>
    <link rel="stylesheet" href="../../css/form-añadir.css">
    <link rel="shortcut icon" href="../../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <script src="../../js/validacion-añadir.js" defer></script>
</head>
<body>

    <div class="main-container">      
        <div class="logo-container">
            <img src="../../img/icon.png" class="icon" alt="Logo">
        </div>
        <div class="container">
            <h1>Añadir Usuario</h1>
            <?php if (!empty($message)): ?>
                <div class="message">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <form id="addUserForm" method="POST" action="">
                <div class="form-group">
                    <label for="nombre_usuario">Nombre</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa el nombre del usuario" onblur="validateNombre()">
                    <span id="nombreError" class="error-message"></span> 
                </div>
                <div class="form-group">
                    <label for="email_usuario">Correo Electrónico</label>
                    <input type="email" id="email_usuario" name="email_usuario" placeholder="Ingresa el correo del usuario" onblur="validateEmail()">
                    <span id="emailError" class="error-message"></span> 
                </div>
                <div class="form-group">
                    <label for="password_usuario">Contraseña</label>
                    <input type="password" id="password_usuario" name="password_usuario" placeholder="Ingresa la contraseña" onblur="validatePassword()">
                    <span id="passwordError" class="error-message"></span> 
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirma la contraseña" onblur="validateConfirmPassword()">
                    <span id="confirmPasswordError" class="error-message"></span> 
                </div>
                <div class="form-group">
                    <label for="id_rol">Rol</label>
                    <select id="id_rol" name="id_rol" onblur="validateRole()">
                        <option value="">Selecciona un rol</option>
                        <?php
                        $rolesQuery = "SELECT * FROM tbl_rol";
                        $rolesStmt = $conn->query($rolesQuery);
                        while ($rol = $rolesStmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$rol['id_rol']}'>{$rol['nombre_rol']}</option>";
                        }
                        ?>
                    </select>
                    <span id="roleError" class="error-message"></span>
                </div>
                <button type="submit">Añadir Usuario</button>
            </form>
        </div>
    </div>

</body>
</html>
