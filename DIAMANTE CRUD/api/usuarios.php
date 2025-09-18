<?php
/**
 * API de Usuarios - DIAMANTE CRUD
 * Maneja todas las operaciones CRUD para usuarios
 */

session_start();
require_once '../config/database.php';

// Configurar respuesta JSON
header('Content-Type: application/json');

// Verificar autorización (solo administradores - rol 1)
if (!isset($_SESSION['usuario_id']) || $_SESSION['id_rol'] != 1) {
    sendError(403, 'No autorizado - Solo administradores');
}

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Manejar diferentes métodos HTTP
switch ($method) {
    case 'GET':
        obtenerUsuarios();
        break;
    case 'POST':
        crearUsuario();
        break;
    case 'PUT':
        actualizarUsuario();
        break;
    case 'DELETE':
        desactivarUsuario();
        break;
    default:
        sendError(405, 'Método no permitido');
}

/**
 * Obtener todos los usuarios con información de rol
 */
function obtenerUsuarios() {
    global $pdo;
    
    try {
        $sql = "SELECT u.*, r.nombre_rol 
                FROM Usuario u 
                INNER JOIN Rol r ON u.id_rol = r.id_rol 
                ORDER BY u.id_usuario DESC";
        
        $stmt = $pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ocultar contraseñas en la respuesta
        foreach ($usuarios as &$usuario) {
            unset($usuario['contrasena_usuario']);
        }
        
        echo json_encode($usuarios);
        
    } catch (PDOException $e) {
        sendError(500, 'Error al obtener usuarios');
    }
}

/**
 * Crear nuevo usuario
 */
function crearUsuario() {
    global $pdo;
    
    // Obtener datos del request
    $data = getJsonData();
    
    // Validar datos requeridos
    $required = ['nombre_usuario', 'contrasena_usuario', 'email_usuario', 'id_rol'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            sendError(400, "Campo requerido: $field");
        }
    }
    
    try {
        // Verificar si el email ya existe
        if (emailExists($data['email_usuario'])) {
            sendError(400, 'El email ya está registrado');
        }
        
        $sql = "INSERT INTO Usuario 
                (nombre_usuario, contrasena_usuario, tipo_documento_usuario, 
                 documento_usuario, telefono_usuario, email_usuario, id_rol) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_usuario'],
            $data['contrasena_usuario'], // En producción debería usar password_hash()
            $data['tipo_documento_usuario'] ?? 'CC',
            $data['documento_usuario'] ?? '',
            $data['telefono_usuario'] ?? '',
            $data['email_usuario'],
            $data['id_rol']
        ]);
        
        sendSuccess(['id' => $pdo->lastInsertId()], 'Usuario creado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al crear usuario');
    }
}

/**
 * Actualizar usuario existente
 */
function actualizarUsuario() {
    global $pdo;
    
    // Obtener datos del request
    $data = getJsonData();
    
    // Validar ID del usuario
    if (!isset($data['id_usuario'])) {
        sendError(400, 'ID del usuario requerido');
    }
    
    try {
        // Si se proporciona nueva contraseña
        if (!empty($data['contrasena_usuario'])) {
            $sql = "UPDATE Usuario 
                    SET nombre_usuario = ?, contrasena_usuario = ?, tipo_documento_usuario = ?, 
                        documento_usuario = ?, telefono_usuario = ?, email_usuario = ?, 
                        id_rol = ?, estado_usuario = ? 
                    WHERE id_usuario = ?";
            
            $params = [
                $data['nombre_usuario'],
                $data['contrasena_usuario'], // En producción debería usar password_hash()
                $data['tipo_documento_usuario'],
                $data['documento_usuario'],
                $data['telefono_usuario'],
                $data['email_usuario'],
                $data['id_rol'],
                $data['estado_usuario'] ?? 'ACTIVO',
                $data['id_usuario']
            ];
        } else {
            // Actualizar sin cambiar contraseña
            $sql = "UPDATE Usuario 
                    SET nombre_usuario = ?, tipo_documento_usuario = ?, documento_usuario = ?, 
                        telefono_usuario = ?, email_usuario = ?, id_rol = ?, estado_usuario = ? 
                    WHERE id_usuario = ?";
            
            $params = [
                $data['nombre_usuario'],
                $data['tipo_documento_usuario'],
                $data['documento_usuario'],
                $data['telefono_usuario'],
                $data['email_usuario'],
                $data['id_rol'],
                $data['estado_usuario'] ?? 'ACTIVO',
                $data['id_usuario']
            ];
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        sendSuccess(null, 'Usuario actualizado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al actualizar usuario');
    }
}

/**
 * Desactivar usuario (soft delete)
 */
function desactivarUsuario() {
    global $pdo;
    
    // Obtener ID del usuario
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        sendError(400, 'ID del usuario requerido');
    }
    
    try {
        $sql = "UPDATE Usuario SET estado_usuario = 'INACTIVO' WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        sendSuccess(null, 'Usuario desactivado exitosamente');
        
    } catch (PDOException $e) {
        sendError(500, 'Error al desactivar usuario');
    }
}

/**
 * Verificar si un email ya existe
 */
function emailExists($email) {
    global $pdo;
    
    $sql = "SELECT COUNT(*) FROM Usuario WHERE email_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    return $stmt->fetchColumn() > 0;
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