<?php
/**
 * API de Productos - DIAMANTE CRUD
 * Maneja todas las operaciones CRUD para productos
 */

session_start();
require_once '../config/database.php';

// Configurar respuesta JSON
header('Content-Type: application/json');

// Verificar autorización (solo usuarios con rol <= 2)
if (!isset($_SESSION['usuario_id']) || $_SESSION['id_rol'] > 2) {
    sendError(403, 'No autorizado');
}

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Manejar diferentes métodos HTTP
switch ($method) {
    case 'GET':
        obtenerProductos();
        break;
    case 'POST':
        crearProducto();
        break;
    case 'PUT':
        actualizarProducto();
        break;
    case 'DELETE':
        eliminarProducto();
        break;
    default:
        sendError(405, 'Método no permitido');
}

/**
 * Obtener todos los productos
 */
function obtenerProductos() {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM Producto ORDER BY id_producto DESC";
        $stmt = $pdo->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($productos);
        
    } catch (PDOException $e) {
        sendError(500, 'Error al obtener productos');
    }
}

/**
 * Crear nuevo producto
 */
function crearProducto() {
    global $pdo;
    
    // Obtener datos del request
    $data = getJsonData();
    
    // Validar datos requeridos
    if (!isset($data['nombre_producto']) || !isset($data['precio_producto'])) {
        sendError(400, 'Faltan datos requeridos');
    }
    
    try {
        $sql = "INSERT INTO Producto (nombre_producto, descripcion_producto, precio_producto, estado_producto) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_producto'],
            $data['descripcion_producto'] ?? '',
            $data['precio_producto'],
            $data['estado_producto'] ?? 'ACTIVO'
        ]);
        
        sendSuccess(['id' => $pdo->lastInsertId()], 'Producto creado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al crear producto');
    }
}

/**
 * Actualizar producto existente
 */
function actualizarProducto() {
    global $pdo;
    
    // Obtener datos del request
    $data = getJsonData();
    
    // Validar ID del producto
    if (!isset($data['id_producto'])) {
        sendError(400, 'ID del producto requerido');
    }
    
    try {
        $sql = "UPDATE Producto 
                SET nombre_producto = ?, descripcion_producto = ?, precio_producto = ?, estado_producto = ? 
                WHERE id_producto = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_producto'],
            $data['descripcion_producto'],
            $data['precio_producto'],
            $data['estado_producto'],
            $data['id_producto']
        ]);
        
        sendSuccess(null, 'Producto actualizado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al actualizar producto');
    }
}

/**
 * Eliminar producto
 */
function eliminarProducto() {
    global $pdo;
    
    // Obtener ID del producto
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        sendError(400, 'ID del producto requerido');
    }
    
    try {
        $sql = "DELETE FROM Producto WHERE id_producto = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        sendSuccess(null, 'Producto eliminado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al eliminar producto');
    }
}

/**
 * Obtener datos JSON del request
 */
function getJsonData() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendError(400, 'JSON inválido');
    }
    
    return $data;
}

/**
 * Enviar respuesta de éxito
 */
function sendSuccess($data = null, $message = 'Operación exitosa') {
    $response = ['success' => true, 'message' => $message];
    if ($data) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

/**
 * Enviar respuesta de error
 */
function sendError($code, $message) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit();
}
?>