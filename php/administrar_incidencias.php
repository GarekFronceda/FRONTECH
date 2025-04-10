<?php
// Incluir archivo para conectar a la base de datos
require_once '../bbdd/connect.php';
// Iniciar sesión
session_start();

// Verificar permisos del usuario (solo Admin o Técnico pueden acceder)
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] != 'Admin' && $_SESSION['rol'] != 'Tecnico')) {
    // Si no está logueado o no tiene permisos, redirigir al login
    header("Location: login.php");
    exit();
}

// Establecer conexión con la base de datos
$pdo = conectarConBaseDeDatos();
// Mensaje a mostrar al usuario
$mensaje = '';

// Procesar formulario al recibir una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar añadir un nuevo dispositivo
    if (isset($_POST['nuevo_dispositivo'])) {
        try {
            // Intentar añadir el dispositivo con los datos del formulario
            if (añadirDispositivo(
                $pdo,
                $_POST['id_cliente'],
                $_POST['tipo'],
                $_POST['marca'],
                $_POST['modelo'],
                $_POST['n_serie']
            )) {
                // Si el dispositivo se añade correctamente, mostrar mensaje de éxito
                $mensaje = "Dispositivo añadido correctamente";
            }
        } catch (PDOException $e) {
            // Si hay un error al añadir el dispositivo, mostrar el error
            $mensaje = "Error al añadir dispositivo: " . $e->getMessage();
        }
    } 
    // Procesar añadir o editar una incidencia
    else {
        // Obtener ID de la reparación, si existe, o null si es una nueva
        $id_reparacion = $_POST['id_reparacion'] ?? null;
        // Obtener el ID del dispositivo relacionado
        $id_dispositivo = $_POST['id_dispositivo'];
        // Obtener la descripción del problema y quitar espacios innecesarios
        $descripcion_problema = trim($_POST['descripcion_problema']);
        // Obtener el estado de la incidencia
        $estado = $_POST['estado'];
        // Si el estado es "Reparado" o "Entregado", establecer la fecha de salida
        $fecha_salida = ($estado == 'Reparado' || $estado == 'Entregado') ? date('Y-m-d') : null;

        try {
            // Si no existe ID de reparación (es una nueva incidencia), crear una nueva incidencia
            if (empty($id_reparacion)) {
                if (crearIncidencia($pdo, $id_dispositivo, $descripcion_problema, $estado)) {
                    $mensaje = "Incidencia creada correctamente";
                }
            } else {
                // Si existe ID de reparación (es una actualización), actualizar la incidencia
                if (actualizarIncidencia($pdo, $id_reparacion, $estado, $descripcion_problema, $fecha_salida)) {
                    $mensaje = "Incidencia actualizada correctamente";
                }
            }
        } catch (PDOException $e) {
            // Si hay un error, mostrarlo
            $mensaje = "Error: " . $e->getMessage();
        }
    }
}

// Procesar eliminación de una incidencia si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    // Obtener el ID de la incidencia a eliminar
    $id = $_GET['eliminar'];
    try {
        // Intentar eliminar la incidencia
        if (eliminarIncidencia($pdo, $id)) {
            $mensaje = "Incidencia eliminada correctamente";
        }
    } catch (PDOException $e) {
        // Si hay un error al eliminar la incidencia, mostrarlo
        $mensaje = "Error al eliminar incidencia: " . $e->getMessage();
    }
}

// Obtener todas las incidencias para mostrar en la vista
$incidencias = obtenerTodasIncidencias($pdo);
// Obtener los dispositivos para el formulario de selección
$dispositivos = obtenerDispositivosParaSelect($pdo);
// Obtener todos los clientes para el formulario de selección
$clientes = obtenerTodosLosClientes($pdo);

// Si se pasa un ID de incidencia a editar, cargar los datos de la incidencia
$incidencia_editar = null;
if (isset($_GET['editar'])) {
    $incidencia_editar = obtenerIncidenciaPorId($pdo, $_GET['editar']);
}

// Cargar la vista correspondiente para la administración de incidencias
require 'views/admin_incidencias_view.php';
?>
