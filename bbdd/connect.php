<?php

// Incluye el archivo con las constantes de conexión
include_once 'constantes.php';

// Función para establecer la conexión con la base de datos usando PDO
function conectarConBaseDeDatos() {
    try {
        // Establece la conexión PDO con los datos de conexión definidos en constantes
        $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
        // Configura el manejo de errores de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Si ocurre un error, muestra el mensaje de error y detiene la ejecución
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
}

// Función para obtener un usuario por su nombre de usuario
function obtenerUsuarioPorNombre($pdo, $nombreUsuario) {
    // Consulta SQL para obtener un usuario y su información relacionada
    $query = "SELECT u.id_usuario, u.nombre_usuario, u.rol, u.password, c.id_cliente 
              FROM Usuarios u
              LEFT JOIN clientes c ON u.id_cliente = c.id_cliente
              WHERE u.nombre_usuario = :nombreUsuario";
    // Prepara la consulta y asigna el parámetro de nombre de usuario
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
    $stmt->execute();

    // Devuelve el resultado como un array asociativo
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener dispositivos en incidencia de un cliente
function obtenerDispositivosEnIncidencia($pdo, $id_cliente) {
    // Verifica que el ID de cliente sea un valor numérico
    if (!is_numeric($id_cliente)) {
        return [];
    }

    try {
        // Consulta para obtener los dispositivos en reparación de un cliente
        $sql = "SELECT d.tipo, d.marca, d.modelo, d.n_serie, 
                       r.fecha_ingreso, r.descripcion_problema, r.estado, r.fecha_salida
                FROM dispositivos d
                JOIN reparaciones r ON d.id_dispositivo = r.id_dispositivo
                WHERE d.id_cliente = :id_cliente
                ORDER BY r.fecha_ingreso DESC";
        
        // Prepara y ejecuta la consulta
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        
        // Devuelve todos los resultados o un array vacío si no hay resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        
    } catch (PDOException $e) {
        // Si ocurre un error en la base de datos, lo registra en el log y devuelve un array vacío
        error_log("Error en la base de datos: " . $e->getMessage());
        return [];
    }
}

// Función para obtener la cantidad de usuarios
function obtenerCantidadUsuarios($pdo) {
    // Ejecuta una consulta para contar el número de usuarios
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    return $stmt->fetchColumn();
}

// Función para obtener la cantidad de clientes
function obtenerCantidadClientes($pdo) {
    // Ejecuta una consulta para contar el número de clientes
    $stmt = $pdo->query("SELECT COUNT(*) FROM clientes");
    return $stmt->fetchColumn();
}

// Función para obtener la cantidad de incidencias
function obtenerCantidadIncidencias($pdo) {
    // Ejecuta una consulta para contar el número de incidencias
    $stmt = $pdo->query("SELECT COUNT(*) FROM reparaciones");
    return $stmt->fetchColumn();
}

// Función para obtener la cantidad de incidencias activas (pendientes o en proceso)
function obtenerIncidenciasActivas($pdo) {
    // Ejecuta una consulta para contar las incidencias activas
    $stmt = $pdo->query("SELECT COUNT(*) FROM reparaciones WHERE estado IN ('Pendiente', 'En proceso')");
    return $stmt->fetchColumn();
}

// Función para obtener todas las incidencias pendientes
function obtenerIncidenciasDisponibles($pdo) {
    // Consulta para obtener incidencias pendientes junto con la información de los dispositivos
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.estado = 'Pendiente'";
    
    // Ejecuta la consulta y devuelve todos los resultados
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener incidencias asignadas a un técnico
function obtenerIncidenciasPorTecnico($pdo, $id_tecnico) {
    // Verifica si hay incidencias asignadas al técnico desde la sesión
    if (isset($_SESSION['incidencias_asignadas']) && !empty($_SESSION['incidencias_asignadas'])) {
        $ids = implode(",", $_SESSION['incidencias_asignadas']);
        // Consulta para obtener las incidencias asignadas
        $sql = "SELECT r.*, d.tipo, d.marca, d.modelo 
                FROM reparaciones r
                JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
                WHERE r.id_reparacion IN ($ids)";
        
        // Ejecuta la consulta y devuelve los resultados
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Devuelve un array vacío si no hay incidencias asignadas
    return [];
}

// Función para obtener todos los usuarios con información del cliente asociado
function obtenerTodosUsuarios($pdo) {
    // Consulta para obtener todos los usuarios y su cliente asociado
    $sql = "SELECT u.*, c.nombre as nombre_cliente 
            FROM usuarios u 
            LEFT JOIN clientes c ON u.id_cliente = c.id_cliente";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener un usuario por su ID
function obtenerUsuarioPorId($pdo, $id_usuario) {
    // Consulta para obtener un usuario por su ID
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para añadir un nuevo usuario
function añadirUsuario($pdo, $nombre_usuario, $password, $rol, $id_cliente = null) {
    // Cifra la contraseña (usar password_hash() en producción)
    $hashed_password = md5($password);
    // Inserta el nuevo usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, password, rol, id_cliente) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nombre_usuario, $hashed_password, $rol, $id_cliente]);
}

// Función para actualizar un usuario existente
function actualizarUsuario($pdo, $id_usuario, $nombre_usuario, $rol, $id_cliente = null, $password = null) {
    if ($password) {
        // Si se proporciona una nueva contraseña, la cifra y actualiza el usuario
        $hashed_password = md5($password);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, password = ?, rol = ?, id_cliente = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $hashed_password, $rol, $id_cliente, $id_usuario]);
    } else {
        // Si no se proporciona una nueva contraseña, actualiza solo los demás campos
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, rol = ?, id_cliente = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $rol, $id_cliente, $id_usuario]);
    }
}

// Función para eliminar un usuario
function eliminarUsuario($pdo, $id_usuario) {
    // Elimina el usuario por su ID
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    return $stmt->execute([$id_usuario]);
}

// Función para obtener todos los clientes
function obtenerTodosClientes($pdo) {
    // Consulta para obtener todos los clientes ordenados por su nombre
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener un cliente por su ID
function obtenerClientePorId($pdo, $id_cliente) {
    // Consulta para obtener un cliente por su ID
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para añadir un nuevo cliente
function añadirCliente($pdo, $nombre, $email, $telefono, $direccion) {
    // Inserta un nuevo cliente en la base de datos
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nombre, $email, $telefono, $direccion]);
}

// Función para actualizar un cliente existente
function actualizarCliente($pdo, $id_cliente, $nombre, $email, $telefono, $direccion) {
    // Actualiza los datos de un cliente
    $stmt = $pdo->prepare("UPDATE clientes SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id_cliente = ?");
    return $stmt->execute([$nombre, $email, $telefono, $direccion, $id_cliente]);
}

// Función para eliminar un cliente
function eliminarCliente($pdo, $id_cliente) {
    // Elimina un cliente de la base de datos
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    return $stmt->execute([$id_cliente]);
}

// Función para obtener todas las incidencias con la información de dispositivos y clientes
function obtenerTodasIncidencias($pdo) {
    // Consulta para obtener todas las incidencias con detalles de dispositivos y clientes
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo, c.nombre as cliente_nombre 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            JOIN clientes c ON d.id_cliente = c.id_cliente
            ORDER BY r.fecha_ingreso DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener una incidencia por su ID
function obtenerIncidenciaPorId($pdo, $id_reparacion) {
    // Consulta para obtener una incidencia por su ID
    $sql = "SELECT r.*, d.tipo, d.marca, d.modelo, d.id_cliente 
            FROM reparaciones r
            JOIN dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.id_reparacion = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_reparacion]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para actualizar el estado de una incidencia
function actualizarIncidencia($pdo, $id_reparacion, $estado, $descripcion_problema, $fecha_salida = null) {
    // Actualiza los detalles de una incidencia
    $sql = "UPDATE reparaciones SET estado = ?, descripcion_problema = ?, fecha_salida = ? WHERE id_reparacion = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$estado, $descripcion_problema, $fecha_salida, $id_reparacion]);
}

// Función para eliminar una incidencia
function eliminarIncidencia($pdo, $id_reparacion) {
    // Elimina una incidencia por su ID
    $stmt = $pdo->prepare("DELETE FROM reparaciones WHERE id_reparacion = ?");
    return $stmt->execute([$id_reparacion]);
}

// Función para obtener todos los dispositivos para select
function obtenerDispositivosParaSelect($pdo) {
    // Consulta para obtener todos los dispositivos ordenados por cliente y tipo
    $sql = "SELECT d.id_dispositivo, d.tipo, d.marca, d.modelo, c.nombre as cliente_nombre 
            FROM dispositivos d
            JOIN clientes c ON d.id_cliente = c.id_cliente
            ORDER BY c.nombre, d.tipo";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Función para añadir un nuevo dispositivo
function añadirDispositivo($pdo, $id_cliente, $tipo, $marca, $modelo, $n_serie) {
    // Inserta un nuevo dispositivo en la base de datos
    $stmt = $pdo->prepare("INSERT INTO dispositivos (id_cliente, tipo, marca, modelo, n_serie) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$id_cliente, $tipo, $marca, $modelo, $n_serie]);
}

// Función para obtener todos los clientes para select
function obtenerTodosLosClientes($pdo) {
    // Consulta para obtener todos los clientes con su ID y nombre
    return $pdo->query("SELECT id_cliente, nombre FROM clientes ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
}

// Función para crear una nueva incidencia
function crearIncidencia($pdo, $id_dispositivo, $descripcion_problema, $estado) {
    // Inserta una nueva incidencia para un dispositivo
    $stmt = $pdo->prepare("INSERT INTO reparaciones (id_dispositivo, fecha_ingreso, descripcion_problema, estado) VALUES (?, NOW(), ?, ?)");
    return $stmt->execute([$id_dispositivo, $descripcion_problema, $estado]);
}

// Conexión inicial a la base de datos
$pdo = conectarConBaseDeDatos();

?>
