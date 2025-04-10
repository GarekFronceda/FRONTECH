<?php

include_once 'constantes.php';

function conectarConBaseDeDatos() {
    try {
        $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
}

// Función para obtener un usuario por nombre de usuario
function obtenerUsuarioPorNombre($pdo, $nombreUsuario) {
    $query = "SELECT u.id_usuario, u.nombre_usuario, u.rol, u.password, c.id_cliente 
              FROM Usuarios u
              LEFT JOIN clientes c ON u.id_cliente = c.id_cliente
              WHERE u.nombre_usuario = :nombreUsuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerDispositivosEnIncidencia($pdo, $id_cliente) {
    if (!is_numeric($id_cliente)) {
        return [];
    }

    try {
        $sql = "SELECT d.tipo, d.marca, d.modelo, d.n_serie, 
                       r.fecha_ingreso, r.descripcion_problema, r.estado, r.fecha_salida
                FROM dispositivos d
                JOIN reparaciones r ON d.id_dispositivo = r.id_dispositivo
                WHERE d.id_cliente = :id_cliente
                ORDER BY r.fecha_ingreso DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        
    } catch (PDOException $e) {
        error_log("Error en la base de datos: " . $e->getMessage());
        return [];
    }
}

function obtenerCantidadUsuarios($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    return $stmt->fetchColumn();
}

function obtenerCantidadClientes($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM clientes");
    return $stmt->fetchColumn();
}

function obtenerCantidadIncidencias($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM reparaciones");
    return $stmt->fetchColumn();
}

function obtenerIncidenciasActivas($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM reparaciones WHERE estado IN ('Pendiente', 'En proceso')");
    return $stmt->fetchColumn();
}

function obtenerIncidenciasDisponibles($pdo) {
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.estado = 'Pendiente'";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerIncidenciasPorTecnico($pdo, $id_tecnico) {
    // En un sistema real, aquí buscaríamos en la tabla reparaciones con id_tecnico
    // Como no tenemos ese campo, simularemos con la sesión
    
    if (isset($_SESSION['incidencias_asignadas']) && !empty($_SESSION['incidencias_asignadas'])) {
        $ids = implode(",", $_SESSION['incidencias_asignadas']);
        $sql = "SELECT r.*, d.tipo, d.marca, d.modelo 
                FROM reparaciones r
                JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
                WHERE r.id_reparacion IN ($ids)";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    return [];
}

/**
 * Obtiene todos los usuarios con información de cliente asociado
 */
function obtenerTodosUsuarios($pdo) {
    $sql = "SELECT u.*, c.nombre as nombre_cliente 
            FROM usuarios u 
            LEFT JOIN clientes c ON u.id_cliente = c.id_cliente";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un usuario por su ID
 */
function obtenerUsuarioPorId($pdo, $id_usuario) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Añade un nuevo usuario
 */
function añadirUsuario($pdo, $nombre_usuario, $password, $rol, $id_cliente = null) {
    $hashed_password = md5($password); // En producción usa password_hash()
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, password, rol, id_cliente) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nombre_usuario, $hashed_password, $rol, $id_cliente]);
}

/**
 * Actualiza un usuario existente
 */
function actualizarUsuario($pdo, $id_usuario, $nombre_usuario, $rol, $id_cliente = null, $password = null) {
    if ($password) {
        $hashed_password = md5($password);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, password = ?, rol = ?, id_cliente = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $hashed_password, $rol, $id_cliente, $id_usuario]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, rol = ?, id_cliente = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $rol, $id_cliente, $id_usuario]);
    }
}

/**
 * Elimina un usuario
 */
function eliminarUsuario($pdo, $id_usuario) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    return $stmt->execute([$id_usuario]);
}

/**
 * Obtiene todos los clientes de la base de datos
 */
function obtenerTodosClientes($pdo) {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene un cliente por su ID
 */
function obtenerClientePorId($pdo, $id_cliente) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Añade un nuevo cliente a la base de datos
 */
function añadirCliente($pdo, $nombre, $email, $telefono, $direccion) {
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nombre, $email, $telefono, $direccion]);
}

/**
 * Actualiza un cliente existente
 */
function actualizarCliente($pdo, $id_cliente, $nombre, $email, $telefono, $direccion) {
    $stmt = $pdo->prepare("UPDATE clientes SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id_cliente = ?");
    return $stmt->execute([$nombre, $email, $telefono, $direccion, $id_cliente]);
}

/**
 * Elimina un cliente de la base de datos
 */
function eliminarCliente($pdo, $id_cliente) {
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    return $stmt->execute([$id_cliente]);
}

/**
 * Obtiene todas las incidencias con información de dispositivos y clientes
 */
function obtenerTodasIncidencias($pdo) {
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo, c.nombre as cliente_nombre 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            JOIN clientes c ON d.id_cliente = c.id_cliente
            ORDER BY r.fecha_ingreso DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene una incidencia por su ID
 */
function obtenerIncidenciaPorId($pdo, $id_reparacion) {
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo, d.id_cliente 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.id_reparacion = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_reparacion]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Actualiza el estado de una incidencia
 */
function actualizarIncidencia($pdo, $id_reparacion, $estado, $descripcion_problema, $fecha_salida = null) {
    $sql = "UPDATE reparaciones SET estado = ?, descripcion_problema = ?, fecha_salida = ? WHERE id_reparacion = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$estado, $descripcion_problema, $fecha_salida, $id_reparacion]);
}

/**
 * Elimina una incidencia
 */
function eliminarIncidencia($pdo, $id_reparacion) {
    $stmt = $pdo->prepare("DELETE FROM reparaciones WHERE id_reparacion = ?");
    return $stmt->execute([$id_reparacion]);
}

/**
 * Obtiene todos los dispositivos para select
 */
function obtenerDispositivosParaSelect($pdo) {
    $sql = "SELECT d.id_dispositivo, d.tipo, d.marca, d.modelo, c.nombre as cliente_nombre 
            FROM dispositivos d
            JOIN clientes c ON d.id_cliente = c.id_cliente
            ORDER BY c.nombre, d.tipo";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Añade un nuevo dispositivo a la base de datos
 */
function añadirDispositivo($pdo, $id_cliente, $tipo, $marca, $modelo, $n_serie) {
    $stmt = $pdo->prepare("INSERT INTO dispositivos (id_cliente, tipo, marca, modelo, n_serie) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$id_cliente, $tipo, $marca, $modelo, $n_serie]);
}

/**
 * Obtiene todos los clientes para select
 */
function obtenerTodosLosClientes($pdo) {
    return $pdo->query("SELECT id_cliente, nombre FROM clientes ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Crea una nueva incidencia
 */
function crearIncidencia($pdo, $id_dispositivo, $descripcion_problema, $estado) {
    $stmt = $pdo->prepare("INSERT INTO reparaciones (id_dispositivo, fecha_ingreso, descripcion_problema, estado) VALUES (?, NOW(), ?, ?)");
    return $stmt->execute([$id_dispositivo, $descripcion_problema, $estado]);
}

// conexión inicial
$pdo = conectarConBaseDeDatos();

?>