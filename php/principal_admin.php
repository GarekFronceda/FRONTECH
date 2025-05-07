<?php
include_once '../bbdd/connect.php';
session_start();

// Verificación más robusta de sesión y rol
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == null || $_SESSION["rol"] != 'Admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de administración</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
    <!-- Añadir íconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionales específicos para admin */
        .admin-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px auto;
            max-width: 1200px;
        }
        .admin-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .admin-card i {
            font-size: 2.5rem;
            color: #5c67f2;
            margin-bottom: 15px;
        }
        .admin-card h3 {
            color: #5c67f2;
            margin-bottom: 15px;
        }
        .quick-stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            min-width: 150px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header-admin">
            <img src="../images/logo_frontech.png" alt="Logo Frontech" class="admin-logo">
            <div class="admin-info">
                <h4>¡Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h4>
            </div>
        </header>

        <h2 class="admin-title">Panel de administración</h2>
        
        <!-- Estadísticas rápidas -->
        <div class="quick-stats">
            <div class="stat-box">
                <h4>Usuarios</h4>
                <p><?php echo obtenerCantidadUsuarios($pdo); ?></p>
            </div>
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

        <!-- Menú de administración -->
        <div class="admin-dashboard">
            <a href="administrar_usuarios.php" class="admin-card">
                <i class="fas fa-users-cog"></i>
                <h3>Administrar Usuarios</h3>
                <p>Gestiona todos los usuarios del sistema</p>
            </a>
            
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

        <form action='logoff.php' method='post' class="logout-form">
            <button type='submit' name='desconectar' class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </form>
        <br>
        <footer class="admin-footer">
            <p>&copy; Frontech 2025 - Todos los derechos reservados</p>
        </footer>
    </div>
</body>
</html>