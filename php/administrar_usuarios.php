<?php
include_once '../bbdd/connect.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión para mantener el estado del usuario

// Verificar permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Admin') {
    // Si el usuario no está autenticado o no tiene rol de 'Admin', redirige al login
    header("Location: login.php");
    exit(); // Detiene la ejecución del script
}

$pdo = conectarConBaseDeDatos(); // Conecta con la base de datos
$mensaje = ''; // Variable para mensajes de éxito o error

// Procesar formulario de añadir/editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario para añadir o editar un usuario
    $id_usuario = $_POST['id_usuario'] ?? null; // Si no se proporciona id_usuario, será null
    $nombre_usuario = trim($_POST['nombre_usuario']); // Elimina espacios en blanco alrededor del nombre del usuario
    $password = trim($_POST['password']); // Elimina espacios en blanco alrededor de la contraseña
    $rol = $_POST['rol']; // Obtiene el rol del usuario (Admin, Cliente, etc.)
    $id_cliente = ($rol === 'Cliente') ? $_POST['id_cliente'] : null; // Si el rol es 'Cliente', se obtiene su id_cliente, si no, será null

    try {
        if (empty($id_usuario)) {
            // Si no hay id_usuario, se añade un nuevo usuario
            if (añadirUsuario($pdo, $nombre_usuario, $password, $rol, $id_cliente)) {
                $mensaje = "Usuario añadido correctamente"; // Mensaje de éxito
            } else {
                $mensaje = "Error al añadir usuario"; // Mensaje de error
            }
        } else {
            // Si existe un id_usuario, se actualiza el usuario existente
            $actualizado = actualizarUsuario(
                $pdo, 
                $id_usuario, 
                $nombre_usuario, 
                $rol, 
                $id_cliente, 
                empty($password) ? null : $password // Si no se proporciona una nueva contraseña, no se actualiza
            );
            
            // Mensaje según si la actualización fue exitosa o no
            $mensaje = $actualizado ? "Usuario actualizado correctamente" : "Error al actualizar usuario";
        }
    } catch (PDOException $e) {
        // Captura cualquier error en la base de datos y muestra un mensaje
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    // Si se pasa un parámetro 'eliminar' en la URL, se intenta eliminar el usuario correspondiente
    $id = $_GET['eliminar'];
    try {
        if (eliminarUsuario($pdo, $id)) {
            $mensaje = "Usuario eliminado correctamente"; // Mensaje de éxito
        } else {
            $mensaje = "Error al eliminar usuario"; // Mensaje de error
        }
    } catch (PDOException $e) {
        // Captura el error si la eliminación falla
        $mensaje = "Error al eliminar usuario: " . $e->getMessage();
    }
}

// Obtener datos para la vista
$usuarios = obtenerTodosUsuarios($pdo); // Obtiene todos los usuarios de la base de datos
$clientes = obtenerTodosClientes($pdo); // Obtiene todos los clientes de la base de datos

// Obtener datos de usuario para editar
$usuario_editar = null;
if (isset($_GET['editar'])) {
    // Si se pasa un parámetro 'editar' en la URL, se obtiene el usuario correspondiente
    $usuario_editar = obtenerUsuarioPorId($pdo, $_GET['editar']);
    if (!$usuario_editar) {
        $mensaje = "Usuario no encontrado"; // Si el usuario no se encuentra, se muestra un mensaje
    }
}

include 'views/administrar_usuarios_view.php'; // Incluye la vista donde se mostrará la información de los usuarios
?>
