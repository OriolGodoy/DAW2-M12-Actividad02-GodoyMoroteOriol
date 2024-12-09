<?php
session_start();
include '../db/conexion.php'; // Asegúrate de que $conn es una instancia de PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email_usuario = trim($_POST['email_usuario']);
    $pwd = trim($_POST['pwd']);

    // Guardar valores en sesión temporal para mostrar errores si ocurre algo
    $_SESSION['email_usuario'] = $email_usuario;

    // Validar campos vacíos
    if (empty($email_usuario) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../index.php");
        exit();
    }

    try {
        // Consulta para verificar el usuario y obtener el nombre del rol
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.password_usuario, r.nombre_rol 
                FROM tbl_usuario u 
                INNER JOIN tbl_rol r ON u.id_rol = r.id_rol 
                WHERE u.email_usuario = :email_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email_usuario', $email_usuario, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($pwd, $usuario['password_usuario'])) {
                // Iniciar sesión
                $_SESSION['loggedin'] = true;
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol_usuario'] = $usuario['nombre_rol'];

                // Limpiar datos temporales
                unset($_SESSION['email_usuario']);
                unset($_SESSION['error']);

                // Redirigir según el rol del usuario
                switch ($usuario['nombre_rol']) {
                    case 'Administrador':
                        header("Location: public/dashAdmin.php");
                        exit();
                    case 'Camarero':
                        header("Location: public/dashCamarero.php");
                        exit();
                    case 'Gerente':
                        header("Location: public/dashGerente.php");
                        exit();
                    default:
                        // Si el rol no es ninguno de los anteriores, redirigir a una página común
                        header("Location: ../public/dashboard.php");
                        exit();
                }
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
                header("Location: ../index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "El usuario no existe.";
            header("Location: ../index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la base de datos: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
?>
