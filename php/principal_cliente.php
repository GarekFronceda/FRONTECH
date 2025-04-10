<?php
// Se incluye el archivo para la conexión con la base de datos
include_once '../bbdd/connect.php';
// Se inicia la sesión para acceder a las variables de sesión del usuario
session_start();

// Verificación de autenticación y rol
// Si el usuario no está autenticado o no tiene el rol 'Cliente', se redirige a la página de login
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Cliente') {
    header("Location: login.php"); // Redirige al login si no está autenticado como cliente
    exit(); // Detiene la ejecución del script
}

// Obtener conexión y dispositivos
$pdo = conectarConBaseDeDatos(); // Establece la conexión a la base de datos
$dispositivos = obtenerDispositivosEnIncidencia($pdo, $_SESSION['id_cliente']); // Obtiene los dispositivos del cliente que están en reparación
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Define el charset para asegurar que los caracteres especiales se muestren correctamente -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace la página adaptable a dispositivos móviles -->
    <title>Mi Panel - Cliente</title> <!-- Título de la página -->
    <!-- Se incluyen las hojas de estilo de Bootstrap y el estilo personalizado de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
</head>
<body>
    <div class="container py-4">
        <!-- Encabezado con logo y saludo al cliente -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <img src="../images/logo_frontech.png" width="150"> <!-- Logo de la empresa -->
            <strong>
            <div class="text-end">
                <!-- Saludo con el nombre del cliente y botón para cerrar sesión -->
                <span class="me-3">¡Hola, <?= htmlspecialchars($_SESSION['usuario']) ?>!</span>
                <a href="logoff.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a> <!-- Enlace para cerrar sesión -->
            </div>
            </strong>
        </header>

        <!-- Título de la sección -->
        <h2 class="mb-4">Mis Dispositivos en Reparación</h2>
        
        <?php if (empty($dispositivos)): ?>
            <!-- Si no hay dispositivos en reparación, muestra un mensaje informativo -->
            <div class="alert alert-info">No tienes dispositivos en reparación actualmente.</div>
        <?php else: ?>
            <!-- Si hay dispositivos, se muestra una tabla con la información de cada uno -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <!-- Definición de las columnas de la tabla -->
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
                            <!-- Para cada dispositivo, se muestra una fila con su información -->
                            <tr>
                                <td><?= htmlspecialchars($dispositivo['tipo'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['marca'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['modelo'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['n_serie'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['fecha_ingreso'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($dispositivo['descripcion_problema'] ?? 'Sin descripción') ?></td>
                                <td>
                                    <!-- Estado del dispositivo, con un estilo diferente según su valor -->
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

        <!-- Pie de página -->
        <footer class="mt-5 text-center text-muted">
            <p>&copy; Frontech 2025 - Todos los derechos reservados</p>
        </footer>
    </div>

    <!-- Se incluyen los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
