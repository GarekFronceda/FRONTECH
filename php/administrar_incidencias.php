<?php
require_once '../bbdd/connect.php';
session_start();

// Verificar permisos (Admin o Técnico)
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] != 'Admin' && $_SESSION['rol'] != 'Tecnico')) {
    header("Location: login.php");
    exit();
}

$pdo = conectarConBaseDeDatos();
$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar añadir dispositivo
    if (isset($_POST['nuevo_dispositivo'])) {
        try {
            if (añadirDispositivo(
                $pdo,
                $_POST['id_cliente'],
                $_POST['tipo'],
                $_POST['marca'],
                $_POST['modelo'],
                $_POST['n_serie']
            )) {
                $mensaje = "Dispositivo añadido correctamente";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al añadir dispositivo: " . $e->getMessage();
        }
    } 
    // Procesar añadir/editar incidencia
    else {
        $id_reparacion = $_POST['id_reparacion'] ?? null;
        $id_dispositivo = $_POST['id_dispositivo'];
        $descripcion_problema = trim($_POST['descripcion_problema']);
        $estado = $_POST['estado'];
        $fecha_salida = ($estado == 'Reparado' || $estado == 'Entregado') ? date('Y-m-d') : null;

        try {
            if (empty($id_reparacion)) {
                if (crearIncidencia($pdo, $id_dispositivo, $descripcion_problema, $estado)) {
                    $mensaje = "Incidencia creada correctamente";
                }
            } else {
                if (actualizarIncidencia($pdo, $id_reparacion, $estado, $descripcion_problema, $fecha_salida)) {
                    $mensaje = "Incidencia actualizada correctamente";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    }
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        if (eliminarIncidencia($pdo, $id)) {
            $mensaje = "Incidencia eliminada correctamente";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar incidencia: " . $e->getMessage();
    }
}

// Obtener datos para la vista
$incidencias = obtenerTodasIncidencias($pdo);
$dispositivos = obtenerDispositivosParaSelect($pdo);
$clientes = obtenerTodosLosClientes($pdo);

// Obtener incidencia para editar
$incidencia_editar = null;
if (isset($_GET['editar'])) {
    $incidencia_editar = obtenerIncidenciaPorId($pdo, $_GET['editar']);
}

// Cargar vista
require 'views/admin_incidencias_view.php';