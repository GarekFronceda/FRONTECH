<?php
// Se incluye el archivo de conexión a la base de datos
include_once '../bbdd/connect.php';
// Se inicia la sesión para poder acceder a las variables de sesión
session_start();

// Verificación de sesión y rol de usuario
// Si no hay usuario en la sesión o no es un 'Admin', se redirige al login
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == null || $_SESSION["rol"] != 'Admin') {
    header("Location: login.php"); // Redirección a la página de login
    exit(); // Detiene la ejecución del script si la condición se cumple
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de administración</title> <!-- Título de la página -->
    <meta charset="UTF-8"> <!-- Definición del charset para evitar problemas con caracteres especiales -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuración para diseño adaptable a dispositivos móviles -->
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css"> <!-- Se incluye el archivo de estilos de la página principal -->
    <!-- Se incluye la hoja de estilos de Font Awesome para usar iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionales específicos para admin */
        .admin-dashboard {
            display: grid; /* Usamos grid para organizar los elementos de manera flexible */
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Las tarjetas se ajustan dependiendo del tamaño de la pantalla */
            gap: 20px; /* Espacio entre las tarjetas */
            margin: 30px auto; /* Espaciado alrededor del contenido */
            max-width: 1200px; /* Limitar el ancho máximo */
        }
        .admin-card {
            background: white; /* Fondo blanco para las tarjetas */
            border-radius: 10px; /* Bordes redondeados */
            padding: 25px; /* Relleno dentro de las tarjetas */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra para dar un efecto de profundidad */
            transition: transform 0.3s ease; /* Animación para el efecto hover */
            text-align: center; /* Centra el texto dentro de las tarjetas */
        }
        .admin-card:hover {
            transform: translateY(-5px); /* Efecto de movimiento al hacer hover */
            box-shadow: 0 6px 12px rgba(0,0,0,0.15); /* Sombra más intensa al hacer hover */
        }
        .admin-card i {
            font-size: 2.5rem; /* Tamaño de los iconos */
            color: #5c67f2; /* Color de los iconos */
            margin-bottom: 15px; /* Espacio debajo del icono */
        }
        .admin-card h3 {
            color: #5c67f2; /* Color del texto de los títulos */
            margin-bottom: 15px; /* Espacio debajo de los títulos */
        }
        .quick-stats {
            display: flex; /* Usamos flexbox para organizar las estadísticas */
            justify-content: space-around; /* Distribuye las estadísticas de manera equidistante */
            margin: 30px 0; /* Espacio arriba y abajo de las estadísticas */
            flex-wrap: wrap; /* Permite que los elementos se acomoden en nuevas líneas si no caben en una fila */
        }
        .stat-box {
            background: white; /* Fondo blanco para cada caja de estadística */
            padding: 15px; /* Relleno dentro de cada caja */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra ligera */
            text-align: center; /* Centra el contenido */
            min-width: 150px; /* Ancho mínimo para las cajas */
            margin: 10px; /* Espacio entre las cajas */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado con el logo y saludo -->
        <header class="header-admin">
            <img src="../images/logo_frontech.png" alt="Logo Frontech" class="admin-logo"> <!-- Logo de la empresa -->
            <div class="admin-info">
                <!-- Saludo con el nombre del usuario que está logueado -->
                <h4>¡Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h4>
            </div>
        </header>

        <!-- Título principal de la página -->
        <h2 class="admin-title">Panel de administración</h2>
        
        <!-- Sección de estadísticas rápidas -->
        <div class="quick-stats">
            <!-- Caja para la cantidad de usuarios -->
            <div class="stat-box">
                <h4>Usuarios</h4>
                <p><?php echo obtenerCantidadUsuarios($pdo); ?></p> <!-- Llama a la función que devuelve el número de usuarios -->
            </div>
            <!-- Caja para la cantidad de clientes -->
            <div class="stat-box">
                <h4>Clientes</h4>
                <p><?php echo obtenerCantidadClientes($pdo); ?></p> <!-- Llama a la función que devuelve el número de clientes -->
            </div>
            <!-- Caja para la cantidad de incidencias -->
            <div class="stat-box">
                <h4>Incidencias</h4>
                <p><?php echo obtenerCantidadIncidencias($pdo); ?></p> <!-- Llama a la función que devuelve el número de incidencias -->
            </div>
            <!-- Caja para la cantidad de incidencias activas -->
            <div class="stat-box">
                <h4>Incidencias activas</h4>
                <p><?php echo obtenerIncidenciasActivas($pdo); ?></p> <!-- Llama a la función que devuelve el número de incidencias activas -->
            </div>
        </div>

        <!-- Menú de administración con enlaces a diferentes secciones -->
        <div class="admin-dashboard">
            <!-- Enlace para administrar usuarios -->
            <a href="administrar_usuarios.php" class="admin-card">
                <i class="fas fa-users-cog"></i> <!-- Icono de Font Awesome -->
                <h3>Administrar Usuarios</h3>
                <p>Gestiona todos los usuarios del sistema</p>
            </a>
            
            <!-- Enlace para administrar clientes -->
            <a href="administrar_clientes.php" class="admin-card">
                <i class="fas fa-address-book"></i> <!-- Icono de Font Awesome -->
                <h3>Administrar Clientes</h3>
                <p>Gestiona la información de clientes</p>
            </a>
            
            <!-- Enlace para administrar incidencias -->
            <a href="administrar_incidencias.php" class="admin-card">
                <i class="fas fa-tools"></i> <!-- Icono de Font Awesome -->
                <h3>Administrar Incidencias</h3>
                <p>Supervisa y gestiona todas las incidencias</p>
            </a>
            
        </div>

        <!-- Formulario para cerrar sesión -->
        <form action='logoff.php' method='post' class="logout-form">
            <button type='submit' name='desconectar' class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </form>
        <br>
        <!-- Pie de página -->
        <footer class="admin-footer">
            <p>&copy; Frontech 2025 - Todos los derechos reservados</p> <!-- Mensaje de derechos reservados -->
        </footer>
    </div>
</body>
</html>
