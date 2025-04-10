<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administrar Clientes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

/* Encabezado */
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

/* Alertas */
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

/* Formulario */
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

input[type="text"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
textarea:focus {
    border-color: #5c67f2;
    outline: none;
    box-shadow: 0 0 0 3px rgba(92, 103, 242, 0.2);
}

/* Botones */
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

/* Tabla */
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

/* Acciones */
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

/* Responsive */
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
    
    /* Hacer tabla responsive */
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

/* Estilos específicos para campos */
input[type="email"] {
    direction: ltr; /* Para emails se alinean a la izquierda */
}

/* Mejorar visualización de datos vacíos */
td:empty::before {
    content: "N/A";
    color: #6c757d;
    font-style: italic;
}
</style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2><i class="fas fa-address-book"></i> Administrar Clientes</h2>
            <?php
            $returnUrl = ($_SESSION['rol'] == 'Admin') ? 'principal_admin.php' : 'principal_tecnico.php';
            ?>
            <a href="<?= $returnUrl ?>" class="btn btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>
        
        <?php if ($mensaje): ?>
            <div class="alert <?= strpos($mensaje, 'Error') !== false ? 'alert-danger' : 'alert-success' ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para añadir/editar cliente -->
        <form method="POST" action="administrar_clientes.php">
            <input type="hidden" name="id_cliente" value="<?= $cliente_editar['id_cliente'] ?? '' ?>">
            
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
            
            <button type="submit">
                <i class="fas fa-save"></i> <?= isset($cliente_editar) ? 'Actualizar Cliente' : 'Añadir Cliente' ?>
            </button>
            
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
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id_cliente'] ?></td>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></td>
                        <td class="actions">
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