<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administrar Incidencias</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css"> <!-- Vincula el archivo CSS principal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Vincula la librería de iconos FontAwesome -->
    <style>
        /* Estilos base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
        }

        /* Estilos para el encabezado */
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

        /* Estilos para alertas */
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

        .alert i {
            font-size: 1.2rem;
        }

        /* Estilos para los formularios */
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

        input[type="text"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 15px;
            transition: all 0.3s;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus,
        textarea:focus {
            border-color: #5c67f2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(92, 103, 242, 0.2);
        }

        /* Botones y selectores */
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

        /* Estilos para los botones */
        button, .btn-primary {
            background-color: #5c67f2;
            color: white;
        }

        button:hover, .btn-primary:hover {
            background-color: #4a54e1;
            transform: translateY(-1px);
        }

        .btn-volver {
            background-color: #6c757d;
            color: white;
        }

        .btn-volver:hover {
            background-color: #5a6268;
        }

        .btn-nuevo {
            background-color: #28a745;
            color: white;
        }

        .btn-nuevo:hover {
            background-color: #218838;
        }

        /* Estilos para la tabla */
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

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        /* Acciones en la tabla */
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

        /* Estilos para los badges de estado */
        .estado-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            min-width: 90px;
            text-align: center;
        }

        .Pendiente { 
            background-color: #ffc107; 
            color: #000;
        }

        .En-proceso { 
            background-color: #17a2b8;
            color: white;
        }

        .Reparado { 
            background-color: #28a745;
            color: white;
        }

        .Entregado { 
            background-color: #6c757d;
            color: white;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: slideDown 0.3s;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #495057;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { 
                transform: translateY(-50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
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
            
            .modal-content {
                width: 95%;
                margin: 20% auto;
            }
        }
    </style>
</head>
<body>

<!-- Contenedor principal de la página -->
<div class="container">
    <!-- Acciones en el encabezado -->
    <div class="header-actions">
        <h2><i class="fa fa-cogs"></i> Administrar Incidencias</h2>
        <a href="index.php" class="btn btn-volver"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>

    <!-- Mensajes de alertas (si existen) -->
    <?php if (isset($mensaje)) { ?>
        <div class="alert <?php echo $tipo_alerta; ?>">
            <i class="fa <?php echo $icono_alerta; ?>"></i> <?php echo $mensaje; ?>
        </div>
    <?php } ?>

    <!-- Tabla de incidencias -->
    <h3>Listado de Incidencias</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada incidencia desde la base de datos
            while ($incidencia = mysqli_fetch_assoc($resultado_incidencias)) {
            ?>
                <tr>
                    <td><?php echo $incidencia['id']; ?></td>
                    <td><?php echo $incidencia['nombre']; ?></td>
                    <td><?php echo $incidencia['fecha']; ?></td>
                    <td><span class="estado-badge <?php echo $incidencia['estado']; ?>"><?php echo $incidencia['estado']; ?></span></td>
                    <td class="actions">
                        <a href="editar.php?id=<?php echo $incidencia['id']; ?>"><i class="fa fa-edit"></i> Editar</a>
                        <a href="eliminar.php?id=<?php echo $incidencia['id']; ?>"><i class="fa fa-trash-alt"></i> Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botón para agregar una nueva incidencia -->
    <a href="nuevo.php" class="btn btn-nuevo"><i class="fa fa-plus"></i> Nueva Incidencia</a>

</div>

</body>
</html>
