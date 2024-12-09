<?php
$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "elmanantial";

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$dbserver;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);

    // Configurar el modo de errores para que genere excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}
?>
