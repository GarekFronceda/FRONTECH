<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administrar Incidencias</title>
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

/* Select con botón */
.form-group > div {
    display: flex;
    gap: 10px;
    align-items: center;
}

.form-group > div select {
    flex: 1;
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

.btn-nuevo {
    background-color: #28a745;
    color: white;
}

.btn-nuevo:hover {
    background-color: #218838;
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

/* Badges de estado */
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

/* Modal */
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
    
    .modal-content {
        width: 95%;
        margin: 20% auto;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2><i class="fas fa-tools"></i> Administrar Incidencias</h2>
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

        <!-- Formulario para añadir/editar incidencia -->
        <form method="POST" action="administrar_incidencias.php">
            <input type="hidden" name="id_reparacion" value="<?= $incidencia_editar['id_reparacion'] ?? '' ?>">
            
            <div class="form-group">
                <label for="id_dispositivo">Dispositivo:</label>
                <div style="display: flex; gap: 10px;">
                    <select id="id_dispositivo" name="id_dispositivo" required <?= isset($incidencia_editar) ? 'disabled' : '' ?> style="flex: 1;">
                        <?php foreach ($dispositivos as $dispositivo): ?>
                            <option value="<?= $dispositivo['id_dispositivo'] ?>"
                                <?= (isset($incidencia_editar) && $incidencia_editar['id_dispositivo'] == $dispositivo['id_dispositivo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars("{$dispositivo['cliente_nombre']} - {$dispositivo['tipo']} {$dispositivo['marca']} {$dispositivo['modelo']}") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!isset($incidencia_editar)): ?>
                        <button type="button" id="btnNuevoDispositivo" class="btn btn-nuevo">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso:</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required 
                       value="<?= isset($incidencia_editar) ? htmlspecialchars($incidencia_editar['fecha_ingreso']) : date('Y-m-d') ?>">
            </div>
            
            <div class="form-group">
                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" id="fecha_salida" name="fecha_salida" 
                       value="<?= isset($incidencia_editar) && !empty($incidencia_editar['fecha_salida']) ? htmlspecialchars($incidencia_editar['fecha_salida']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="descripcion_problema">Descripción del problema:</label>
                <textarea id="descripcion_problema" name="descripcion_problema" rows="4" required placeholder="(Indique el técnico encargado de la reparación. Ejemplo: Reparación asignada a Ángel)"><?= htmlspecialchars($incidencia_editar['descripcion_problema'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Pendiente" <?= (isset($incidencia_editar) && $incidencia_editar['estado'] == 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
                    <option value="En proceso" <?= (isset($incidencia_editar) && $incidencia_editar['estado'] == 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                    <option value="Reparado" <?= (isset($incidencia_editar) && $incidencia_editar['estado'] == 'Reparado') ? 'selected' : '' ?>>Reparado</option>
                    <option value="Entregado" <?= (isset($incidencia_editar) && $incidencia_editar['estado'] == 'Entregado') ? 'selected' : '' ?>>Entregado</option>
                </select>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> <?= isset($incidencia_editar) ? 'Actualizar Incidencia' : 'Añadir Incidencia' ?>
            </button>
            
            <?php if (isset($incidencia_editar)): ?>
                <a href="administrar_incidencias.php" class="btn">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            <?php endif; ?>
        </form>

        <!-- Tabla de incidencias -->
        <h3>Lista de Incidencias</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Salida</th>
                    <th>Problema</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidencias as $incidencia): ?>
                    <tr>
                        <td><?= $incidencia['id_reparacion'] ?></td>
                        <td><?= htmlspecialchars($incidencia['cliente_nombre']) ?></td>
                        <td><?= htmlspecialchars("{$incidencia['tipo']} {$incidencia['marca']} {$incidencia['modelo']}") ?></td>
                        <td><?= htmlspecialchars($incidencia['fecha_ingreso']) ?></td>
                        <td><?= !empty($incidencia['fecha_salida']) ? htmlspecialchars($incidencia['fecha_salida']) : 'N/A' ?></td>
                        <td><?= htmlspecialchars(substr($incidencia['descripcion_problema'], 0, 50)) ?>...</td>
                        <td>
                            <span class="estado-badge <?= str_replace(' ', '-', $incidencia['estado']) ?>">
                                <?= htmlspecialchars($incidencia['estado']) ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="administrar_incidencias.php?editar=<?= $incidencia['id_reparacion'] ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <br>
                            <a href="administrar_incidencias.php?eliminar=<?= $incidencia['id_reparacion'] ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar esta incidencia?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal para nuevo dispositivo -->
        <div id="modalDispositivo" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h3><i class="fas fa-laptop"></i> Añadir Nuevo Dispositivo</h3>
                <form method="POST" action="administrar_incidencias.php">
                    <input type="hidden" name="nuevo_dispositivo" value="1">
                    
                    <div class="form-group">
                        <label for="modal_id_cliente">Cliente:</label>
                        <select id="modal_id_cliente" name="id_cliente" required>
                            <option value="">Seleccionar cliente...</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['id_cliente'] ?>">
                                    <?= htmlspecialchars($cliente['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_tipo">Tipo:</label>
                        <select id="modal_tipo" name="tipo" required>
                            <option value="">Seleccionar tipo...</option>
                            <option value="Portátil">Portátil</option>
                            <option value="PC">PC</option>
                            <option value="Móvil">Móvil</option>
                            <option value="Tablet">Tablet</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_marca">Marca:</label>
                        <input type="text" id="modal_marca" name="marca" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_modelo">Modelo:</label>
                        <input type="text" id="modal_modelo" name="modelo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_n_serie">Número de Serie:</label>
                        <input type="text" id="modal_n_serie" name="n_serie">
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> Guardar Dispositivo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Control del modal
        const modal = document.getElementById('modalDispositivo');
        const btn = document.getElementById('btnNuevoDispositivo');
        const span = document.querySelector('.close-modal');
        
        if (btn) {
            btn.onclick = function() {
                modal.style.display = 'block';
            }
        }
        
        span.onclick = function() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Recargar la página después de añadir dispositivo para actualizar el select
        <?php if (isset($_POST['nuevo_dispositivo']) && strpos($mensaje, 'Error') === false): ?>
            setTimeout(() => {
                window.location.href = 'administrar_incidencias.php';
            }, 1500);
        <?php endif; ?>
    </script>
</body>
</html>