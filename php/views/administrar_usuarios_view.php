<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administrar Usuarios</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilo_pagprincipal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
/* Estilos base */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    color: #333;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 30px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

h2, h3 {
    color: #2c3e50;
    margin-bottom: 20px;
}

h2 {
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Alertas */
.alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Formulario */
form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #495057;
}

input[type="text"],
input[type="password"],
select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 15px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="password"]:focus,
select:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

/* Botones */
button, .btn-cancelar, .btn-volver {
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

button {
    background-color: #3498db;
    color: white;
}

button:hover {
    background-color: #2980b9;
}

.btn-cancelar {
    background-color: #6c757d;
    color: white;
    margin-left: 10px;
}

.btn-cancelar:hover {
    background-color: #5a6268;
}

.btn-volver {
    background-color: #6c757d;
    color: white;
    margin-top: 20px;
}

.btn-volver:hover {
    background-color: #5a6268;
}

/* Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

th {
    background-color: #3498db;
    color: white;
    font-weight: 600;
}

tr:nth-child(even) {
    background-color: #f8f9fa;
}

tr:hover {
    background-color: #e9ecef;
}

.actions {
    white-space: nowrap;
}

.actions a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #3498db;
    text-decoration: none;
    margin-right: 15px;
    transition: color 0.3s;
}

.actions a:hover {
    color: #2980b9;
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
        padding: 20px;
    }
    
    .actions a {
        display: block;
        margin-bottom: 8px;
    }
}
</style>
</head>
<body>
    <div class="container">
        
        <!-- Título de la página -->
        <h2><i class="fas fa-users-cog"></i> Administrar Usuarios</h2>
        
        <!-- Mostrar mensaje de éxito o error, si existe -->
        <?php if ($mensaje): ?>
            <div class="alert <?= strpos($mensaje, 'Error') !== false ? 'alert-danger' : 'alert-success' ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para añadir o editar un usuario -->
        <form method="POST" action="administrar_usuarios.php">
            <!-- Campo oculto para identificar el usuario a editar (si existe) -->
            <input type="hidden" name="id_usuario" value="<?= $usuario_editar['id_usuario'] ?? '' ?>">
            
            <!-- Campo para el nombre de usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" 
                       value="<?= htmlspecialchars($usuario_editar['nombre_usuario'] ?? '') ?>" required>
            </div>
            
            <!-- Campo para la contraseña -->
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" 
                       placeholder="<?= isset($usuario_editar) ? 'Dejar en blanco para no cambiar' : '' ?>" 
                       <?= !isset($usuario_editar) ? 'required' : '' ?>>
            </div>
            
            <!-- Campo para seleccionar el rol del usuario -->
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <!-- Opciones de rol (Admin, Técnico, Cliente) -->
                    <option value="Admin" <?= isset($usuario_editar) && $usuario_editar['rol'] == 'Admin' ? 'selected' : '' ?>>Administrador</option>
                    <option value="Tecnico" <?= isset($usuario_editar) && $usuario_editar['rol'] == 'Tecnico' ? 'selected' : '' ?>>Técnico</option>
                    <option value="Cliente" <?= isset($usuario_editar) && $usuario_editar['rol'] == 'Cliente' ? 'selected' : '' ?>>Cliente</option>
                </select>
            </div>

            <!-- Campo para seleccionar el cliente asociado, solo visible si el rol es 'Cliente' -->
            <div class="form-group" id="cliente-group" style="<?= (isset($usuario_editar) && $usuario_editar['rol'] == 'Cliente') || !isset($usuario_editar) ? '' : 'display: none;' ?>">
                <label for="id_cliente">Cliente asociado:</label>
                <select id="id_cliente" name="id_cliente">
                    <option value="">Seleccionar cliente...</option>
                    <!-- Mostrar opciones de clientes -->
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>" 
                            <?= (isset($usuario_editar) && $usuario_editar['id_cliente'] == $cliente['id_cliente']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Botón para guardar los cambios o añadir un nuevo usuario -->
            <button type="submit">
                <i class="fas fa-save"></i> <?= isset($usuario_editar) ? 'Actualizar Usuario' : 'Añadir Usuario' ?>
            </button>
            
            <!-- Botón de cancelar si se está editando un usuario -->
            <?php if (isset($usuario_editar)): ?>
                <a href="administrar_usuarios.php" class="btn-cancelar">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            <?php endif; ?>
        </form>
        <br>
        
        <!-- Tabla que muestra la lista de usuarios -->
        <h3>Lista de Usuarios</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de usuario</th>
                    <th>Rol</th>
                    <th>Cliente asociado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterar a través de los usuarios y mostrar en la tabla -->
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['rol']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre_cliente'] ?? 'N/A') ?></td>
                        <td class="actions">
                            <!-- Enlace para editar el usuario -->
                            <a href="administrar_usuarios.php?editar=<?= $usuario['id_usuario'] ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <br>
                            <!-- Enlace para eliminar el usuario -->
                            <a href="administrar_usuarios.php?eliminar=<?= $usuario['id_usuario'] ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón para volver al panel de administración -->
        <div class="header-actions">
            <a href="principal_admin.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>

    </div>

    <!-- Script para mostrar/ocultar el campo de cliente según el rol seleccionado -->
    <script>
        document.getElementById('rol').addEventListener('change', function() {
            const clienteGroup = document.getElementById('cliente-group');
            // Mostrar el campo 'cliente' solo si el rol seleccionado es 'Cliente'
            clienteGroup.style.display = this.value === 'Cliente' ? 'block' : 'none';
        });
    </script>
</body>

</html>