<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Título y metadatos de la página -->
    <title>Administrar Clientes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* Estilos generales para la página */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #343a40;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
    }

    /* Contenedor principal */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    /* Encabezado con acciones */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    h2 {
        color: #2c3e50;
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    h2 i {
        font-size: 1.4rem;
    }

    h3 {
        color: #2c3e50;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    /* Estilo para las alertas */
    .alert {
        padding: 12px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Estilo para el formulario */
    form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }

    /* Estilo para los campos del formulario */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 15px;
        transition: all 0.3s;
    }

    textarea {
        min-height: 80px;
        resize: vertical;
    }

    /* Estilo para los botones */
    button, .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 15px;
        border: none;
    }

    button, .btn-primary {
        background-color: #5c67f2;
        color: white;
    }

    /* Estilo para la tabla de clientes */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    th {
        background-color: #5c67f2;
        color: white;
        font-weight: 600;
        position: sticky;
        top: 0;
    }

    /* Estilos para las acciones de la tabla */
    .actions {
        white-space: nowrap;
    }

    .actions a {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #5c67f2;
        text-decoration: none;
        margin-right: 15px;
        transition: color 0.3s;
        padding: 5px 0;
    }

    .actions a:hover {
        color: #4a54e1;
    }

    .actions a i.fa-trash-alt {
        color: #e74c3c;
    }

    .actions a:hover i.fa-trash-alt {
        color: #c0392b;
    }

    /* Responsividad */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        th, td {
            padding: 8px 10px;
            font-size: 0.9rem;
        }

        .actions a {
            display: block;
            margin-bottom: 10px;
        }

        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado y botones de acción -->
        <div class="header-actions">
            <h2><i class="fas fa-address-book"></i> Administrar Clientes</h2>
            <!-- Enlace para volver al panel principal según el rol del usuario -->
            <?php
            $returnUrl = ($_SESSION['rol'] == 'Admin') ? 'principal_admin.php' : 'principal_tecnico.php';
            ?>
            <a href="<?= $returnUrl ?>" class="btn btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>
        
        <!-- Mensajes de éxito o error -->
        <?php if ($mensaje): ?>
            <div class="alert <?= strpos($mensaje, 'Error') !== false ? 'alert-danger' : 'alert-success' ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para añadir o editar cliente -->
        <form method="POST" action="administrar_clientes.php">
            <input type="hidden" name="id_cliente" value="<?= $cliente_editar['id_cliente'] ?? '' ?>">
            
            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?= htmlspecialchars($cliente_editar['nombre'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($cliente_editar['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" 
                       value="<?= htmlspecialchars($cliente_editar['telefono'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion"><?= htmlspecialchars($cliente_editar['direccion'] ?? '') ?></textarea>
            </div>
            
            <!-- Botón de submit -->
            <button type="submit">
                <i class="fas fa-save"></i> <?= isset($cliente_editar) ? 'Actualizar Cliente' : 'Añadir Cliente' ?>
            </button>
            
            <!-- Enlace para cancelar -->
            <?php if (isset($cliente_editar)): ?>
                <a href="administrar_clientes.php" class="btn">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            <?php endif; ?>
        </form>

        <!-- Tabla de clientes -->
        <h3>Lista de Clientes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Mostrar los clientes -->
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id_cliente'] ?></td>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></td>
                        <td class="actions">
                            <!-- Enlaces para editar y eliminar clientes -->
                            <a href="administrar_clientes.php?editar=<?= $cliente['id_cliente'] ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <br>
                            <a href="administrar_clientes.php?eliminar=<?= $cliente['id_cliente'] ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
