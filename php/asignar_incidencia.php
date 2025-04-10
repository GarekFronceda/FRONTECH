<?php
include_once '../bbdd/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario']) && $_SESSION['rol'] === 'Tecnico') {
    $id_reparacion = $_POST['id_reparacion'] ?? null;
    
    if ($id_reparacion) {
        try {
            $pdo = conectarConBaseDeDatos();

            if (!isset($_SESSION['incidencias_asignadas'])) {
                $_SESSION['incidencias_asignadas'] = [];
            }
            
            $_SESSION['incidencias_asignadas'][] = $id_reparacion;
            
            echo json_encode(['success' => true]);
            exit();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);