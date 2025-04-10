<?php
include_once '../bbdd/connect.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión para mantener el estado del usuario

// Verificación mejorada del rol y la sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Tecnico') {
    $_SESSION['error'] = "Acceso no autorizado"; // Si no está autenticado o el rol no es 'Tecnico', redirige

    // Redirige al usuario al lugar donde vino antes (o login si no tiene un origen válido)
    $referer = $_SERVER['HTTP_REFERER'] ?? 'login.php'; 
    header("Location: $referer");
    exit(); // Termina el script para evitar que se siga ejecutando
}

// Obtener todas las incidencias disponibles y las asignadas al técnico
$pdo = conectarConBaseDeDatos(); // Establece la conexión con la base de datos
$incidencias = obtenerIncidenciasDisponibles($pdo); // Obtiene todas las incidencias disponibles
$misIncidencias = obtenerIncidenciasPorTecnico($pdo, $_SESSION['id_usuario']); // Obtiene las incidencias asignadas al técnico
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel Técnico</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css"> <!-- Estilos personalizados -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <style>
        /* Estilos para el diseño del panel técnico */
        .dashboard-tecnico {
            display: grid; /* Uso de Grid Layout para distribuir los elementos */
            grid-template-columns: 1fr 1fr; /* Dos columnas */
            gap: 20px;
            margin: 30px auto;
            max-width: 1200px;
        }
        .incidencias-section {
            background: white; /* Fondo blanco para las secciones */
            border-radius: 10px; /* Bordes redondeados */
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra sutil */
        }
        .incidencia-item {
            padding: 15px;
            border-bottom: 1px solid #eee; /* Línea divisoria */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-asignar {
            background: #5c67f2; /* Color de fondo del botón */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer; /* Muestra que es interactivo */
        }
        /* Estilos para los badges de estado de la incidencia */
        .estado-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        .pendiente { background: #ffc107; color: #000; }
        .en-proceso { background: #17a2b8; color: #fff; }
        .reparado { background: #28a745; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="../images/logo_frontech.png" width="20%"> <!-- Logo de la empresa -->
            <h4>¡Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h4> <!-- Nombre del usuario -->
        </header>

        <h3>Panel Técnico</h3>

        <!-- Estadísticas rápidas -->
        <div class="quick-stats">
            <!-- Estadísticas de clientes, incidencias y incidencias activas -->
            <div class="stat-box">
                <h4>Clientes</h4>
                <p><?php echo obtenerCantidadClientes($pdo); ?></p>
            </div>
            <div class="stat-box">
                <h4>Incidencias</h4>
                <p><?php echo obtenerCantidadIncidencias($pdo); ?></p>
            </div>
            <div class="stat-box">
                <h4>Incidencias activas</h4>
                <p><?php echo obtenerIncidenciasActivas($pdo); ?></p>
            </div>
        </div>

        <div class="dashboard-tecnico">
            <!-- Sección de incidencias asignadas al técnico -->
            <div class="incidencias-section">
                <h4><i class="fas fa-tasks"></i> Mis Incidencias</h4>
                <?php if (empty($misIncidencias)): ?>
                    <p>No tienes incidencias asignadas</p>
                <?php else: ?>
                    <?php foreach ($misIncidencias as $incidencia): ?>
                        <div class="incidencia-item">
                            <div>
                                <strong><?php echo htmlspecialchars($incidencia['tipo']); ?></strong>
                                <small>(<?php echo htmlspecialchars($incidencia['marca']); ?>)</small><br>
                                <span><?php echo htmlspecialchars($incidencia['descripcion_problema']); ?></span>
                            </div>
                            <span class="estado-badge <?php echo str_replace(' ', '-', strtolower($incidencia['estado'])); ?>">
                                <?php echo htmlspecialchars($incidencia['estado']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Sección de incidencias disponibles para asignar -->
            <div class="incidencias-section">
                <h4><i class="fas fa-list"></i> Incidencias Disponibles</h4>
                <?php if (empty($incidencias)): ?>
                    <p>No hay incidencias disponibles</p>
                <?php else: ?>
                    <?php foreach ($incidencias as $incidencia): ?>
                        <div class="incidencia-item">
                            <div>
                                <strong><?php echo htmlspecialchars($incidencia['tipo']); ?></strong>
                                <small>(<?php echo htmlspecialchars($incidencia['marca']); ?>)</small><br>
                                <span><?php echo htmlspecialchars($incidencia['descripcion_problema']); ?></span>
                            </div>
                            <!-- Botón para asignarse a la incidencia -->
                            <button class="btn-asignar" onclick="asignarIncidencia(<?php echo $incidencia['id_reparacion']; ?>)">
                                Asignar
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Menú de administración para otros técnicos (si aplica) -->
        <div class="admin-dashboard">
            <!-- Enlaces a páginas para administrar clientes e incidencias -->
            <a href="administrar_clientes.php" class="admin-card">
                <i class="fas fa-address-book"></i>
                <h3>Administrar Clientes</h3>
                <p>Gestiona la información de clientes</p>
            </a>
            
            <a href="administrar_incidencias.php" class="admin-card">
                <i class="fas fa-tools"></i>
                <h3>Administrar Incidencias</h3>
                <p>Supervisa y gestiona todas las incidencias</p>
            </a>
        </div>

        <!-- Formulario para cerrar sesión -->
        <form action='logoff.php' method='post'>
            <input type='submit' name='desconectar' class="btn" value='Cerrar sesión' />
        </form>

        <p class="mt-3 text-muted">&copy; Frontech 2025</p>
    </div>

    <!-- Script para asignar una incidencia -->
    <script>
    function asignarIncidencia(idReparacion) {
        if (confirm('¿Deseas asignarte esta incidencia?')) {
            fetch('asignar_incidencia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id_reparacion=' + idReparacion
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Incidencia asignada con éxito');
                    location.reload(); // Recarga la página para ver la actualización
                } else {
                    alert('Error: ' + data.message); // Muestra el error si ocurre
                }
            });
        }
    }
    </script>
</body>
</html>
