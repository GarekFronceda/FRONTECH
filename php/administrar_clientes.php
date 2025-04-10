<?php
require_once '../bbdd/connect.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión para mantener el estado del usuario

// Versión segura que permite ambos roles pero con diferentes capacidades
$rolesPermitidos = ['Admin', 'Tecnico']; // Definimos los roles permitidos (Admin y Técnico)
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    // Si el usuario no está autenticado o su rol no está permitido, lo redirige al login
    header("Location: login.php");
    exit(); // Detiene la ejecución del código
}

// Luego puedes diferenciar funcionalidades:
if ($_SESSION['rol'] == 'Tecnico') {
    // Restringir ciertas acciones (como eliminar clientes) para el rol Técnico
    if (isset($_GET['eliminar'])) {
        $_SESSION['error'] = "No tienes permiso para esta acción"; // Mensaje de error
        header("Location: principal_tecnico.php"); // Redirige al panel del técnico
        exit(); // Detiene el script
    }
}

$pdo = conectarConBaseDeDatos(); // Conexión a la base de datos
$mensaje = ''; // Variable para mensajes de éxito o error

// Procesar formulario de añadir/editar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario para añadir o actualizar un cliente
    $id_cliente = $_POST['id_cliente'] ?? null; // Si no hay id, será null
    $nombre = trim($_POST['nombre']); // Elimina espacios en blanco alrededor del nombre
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    try {
        // Si no se ha proporcionado un id_cliente, se añade un nuevo cliente
        if (empty($id_cliente)) {
            if (añadirCliente($pdo, $nombre, $email, $telefono, $direccion)) {
                $mensaje = "Cliente añadido correctamente"; // Mensaje de éxito
            } else {
                $mensaje = "Error al añadir cliente"; // Mensaje de error
            }
        } else {
            // Si se ha proporcionado un id_cliente, se actualiza el cliente
            if (actualizarCliente($pdo, $id_cliente, $nombre, $email, $telefono, $direccion)) {
                $mensaje = "Cliente actualizado correctamente"; // Mensaje de éxito
            } else {
                $mensaje = "Error al actualizar cliente"; // Mensaje de error
            }
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage(); // Captura y muestra el error si ocurre
    }
}

// Procesar eliminación de cliente
if (isset($_GET['eliminar'])) {
    // Si se pasa un parámetro 'eliminar' en la URL, se intenta eliminar el cliente correspondiente
    $id = $_GET['eliminar'];
    try {
        if (eliminarCliente($pdo, $id)) {
            $mensaje = "Cliente eliminado correctamente"; // Mensaje de éxito
        } else {
            $mensaje = "Error al eliminar cliente"; // Mensaje de error
        }
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar cliente: " . $e->getMessage(); // Mensaje de error con detalles
    }
}

// Obtener datos para la vista
$clientes = obtenerTodosClientes($pdo); // Obtiene todos los clientes de la base de datos

// Obtener cliente para editar
$cliente_editar = null;
if (isset($_GET['editar'])) {
    // Si se pasa un parámetro 'editar' en la URL, se obtiene el cliente correspondiente
    $cliente_editar = obtenerClientePorId($pdo, $_GET['editar']);
    if (!$cliente_editar) {
        $mensaje = "Cliente no encontrado"; // Si no se encuentra el cliente, se muestra un mensaje
    }
}

// Cargar la vista
require 'views/admin_clientes_view.php'; // Incluye la vista donde se mostrará la información
?>
