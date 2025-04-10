<?php
include_once '../bbdd/connect.php';
session_start();

// Verificar permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Admin') {
    header("Location: login.php");
    exit();
}

$pdo = conectarConBaseDeDatos();
$mensaje = '';

// Procesar formulario de añadir/editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = trim($_POST['password']);
    $rol = $_POST['rol'];
    $id_cliente = ($rol === 'Cliente') ? $_POST['id_cliente'] : null;

    try {
        if (empty($id_usuario)) {
            // Añadir nuevo usuario
            if (añadirUsuario($pdo, $nombre_usuario, $password, $rol, $id_cliente)) {
                $mensaje = "Usuario añadido correctamente";
            } else {
                $mensaje = "Error al añadir usuario";
            }
        } else {
            // Editar usuario existente
            $actualizado = actualizarUsuario(
                $pdo, 
                $id_usuario, 
                $nombre_usuario, 
                $rol, 
                $id_cliente, 
                empty($password) ? null : $password
            );
            
            $mensaje = $actualizado ? "Usuario actualizado correctamente" : "Error al actualizar usuario";
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        if (eliminarUsuario($pdo, $id)) {
            $mensaje = "Usuario eliminado correctamente";
        } else {
            $mensaje = "Error al eliminar usuario";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar usuario: " . $e->getMessage();
    }
}

// Obtener datos para la vista
$usuarios = obtenerTodosUsuarios($pdo);
$clientes = obtenerTodosClientes($pdo);

// Obtener datos de usuario para editar
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $usuario_editar = obtenerUsuarioPorId($pdo, $_GET['editar']);
    if (!$usuario_editar) {
        $mensaje = "Usuario no encontrado";
    }
}

// La parte HTML sigue igual que en el código anterior
include 'views/administrar_usuarios_view.php';