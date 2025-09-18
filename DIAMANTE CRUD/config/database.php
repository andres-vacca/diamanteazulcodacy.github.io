<?php
/**
 * Configuración de Base de Datos - Unificada
 * Proporciona tanto PDO como MySQLi para compatibilidad
 */

// Configuración de la base de datos
$host = "localhost";
$dbname = "Diamante_Azul";
$username = "root";
$password = "";

// Conexión MySQLi para compatibilidad con archivos existentes
$conn = mysqli_connect($host, $username, $password, $dbname);

// Verificar conexión MySQLi
if (!$conn) {
    die("Error de conexión MySQLi: " . mysqli_connect_error());
}

// Establecer charset para MySQLi
mysqli_set_charset($conn, "utf8");

// Conexión PDO para las APIs
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", 
        $username, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión PDO: " . $e->getMessage());
}
?>