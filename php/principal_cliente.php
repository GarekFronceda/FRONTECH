<?php
include_once '../bbdd/connect.php';
session_start();

// Verificar autenticación y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php");
    exit();
}

// Obtener conexión y dispositivos
$pdo = conectarConBaseDeDatos();
$dispositivos = obtenerDispositivosEnIncidencia($pdo, $_SESSION['id_cliente']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
</head>
<body>
    <div class="container py-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <img src="../images/logo_frontech.png" width="150">
            <strong>
            <div class="text-end">
                <span class="me-3">¡Hola, <?= htmlspecialchars($_SESSION['usuario']) ?>!</span>
                <a href="logoff.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
            </div>
            </strong>
        </header>

        <h2 class="mb-4">Mis Dispositivos en Reparación</h2>
        
        <?php if (empty($dispositivos)): ?>
            <div class="alert alert-info">No tienes dispositivos en reparación actualmente.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Nº Serie</th>
                            <th>Fecha Ingreso</th>
                            <th>Problema</th>
                            <th>Estado</th>
                            <th>Fecha Salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dispositivos as $dispositivo): ?>
                            <tr>
                                <td><?= htmlspecialchars($dispositivo['tipo'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['marca'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['modelo'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['n_serie'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['fecha_ingreso'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['descripcion_problema'] ?? 'Sin descripción') ?></td>
                                <td>
                                    <span class="badge rounded-pill 
                                        <?= match($dispositivo['estado'] ?? 'Pendiente') {
                                            'Reparado' => 'bg-success',
                                            'En proceso' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        } ?>">
                                        <?= htmlspecialchars($dispositivo['estado'] ?? 'Pendiente') ?>
                                    </span>
                                </td>
                                <td><?= !empty($dispositivo['fecha_salida']) ? htmlspecialchars($dispositivo['fecha_salida']) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <footer class="mt-5 text-center text-muted">
            <p>&copy; Frontech 2025 - Todos los derechos reservados</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>