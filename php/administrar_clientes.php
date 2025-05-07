<?php
require_once '../bbdd/connect.php';
session_start();

// Versión segura que permite ambos roles pero con diferentes capacidades
$rolesPermitidos = ['Admin', 'Tecnico'];
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("Location: login.php");
    exit();
}

// Luego puedes diferenciar funcionalidades:
if ($_SESSION['rol'] == 'Tecnico') {
    // Restringir ciertas acciones (como eliminar clientes)
    if (isset($_GET['eliminar'])) {
        $_SESSION['error'] = "No tienes permiso para esta acción";
        header("Location: principal_tecnico.php");
        exit();
    }
}

$pdo = conectarConBaseDeDatos();
$mensaje = '';

// Procesar formulario de añadir/editar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'] ?? null;
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    try {
        if (empty($id_cliente)) {
            if (añadirCliente($pdo, $nombre, $email, $telefono, $direccion)) {
                $mensaje = "Cliente añadido correctamente";
            } else {
                $mensaje = "Error al añadir cliente";
            }
        } else {
            if (actualizarCliente($pdo, $id_cliente, $nombre, $email, $telefono, $direccion)) {
                $mensaje = "Cliente actualizado correctamente";
            } else {
                $mensaje = "Error al actualizar cliente";
            }
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Procesar eliminación de cliente
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        if (eliminarCliente($pdo, $id)) {
            $mensaje = "Cliente eliminado correctamente";
        } else {
            $mensaje = "Error al eliminar cliente";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar cliente: " . $e->getMessage();
    }
}

// Obtener datos para la vista
$clientes = obtenerTodosClientes($pdo);

// Obtener cliente para editar
$cliente_editar = null;
if (isset($_GET['editar'])) {
    $cliente_editar = obtenerClientePorId($pdo, $_GET['editar']);
    if (!$cliente_editar) {
        $mensaje = "Cliente no encontrado";
    }
}

// Cargar la vista
require 'views/admin_clientes_view.php';