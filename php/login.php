<?php
include_once '../bbdd/connect.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($password)) {
        $error = "Debes introducir un nombre de usuario y una contraseña.";
    } else {
        try {
            $pdo = conectarConBaseDeDatos();
            $user = obtenerUsuarioPorNombre($pdo, $usuario);

            if ($user && md5($password) === $user['password']) {
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['usuario'] = $user['nombre_usuario'];
                $_SESSION['rol'] = $user['rol'];
                
                // Para clientes, también almacenamos el id_cliente
                if ($user['rol'] === 'Cliente') {
                    $_SESSION['id_cliente'] = $user['id_cliente'];
                }

                $redireccion = match ($user['rol']) {
                    'Admin' => "principal_admin.php",
                    'Tecnico' => "principal_tecnico.php",
                    'Cliente' => "principal_cliente.php",
                    default => "login.php"
                };

                header("Location: $redireccion");
                exit();
            } else {
                $error = "¡Usuario o contraseña no válidos!";
            }
        } catch (Exception $e) {
            $error = "Error en el sistema. Por favor, inténtalo más tarde.";
            error_log("Error de login: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login FRONTECH 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="text-center mb-4">
                    <img src="../images/logo_frontech.png" alt="Logo" style="width: 300px;">
                    <h3 class="mt-3">Inicio de sesión</h3>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario" required autofocus>
                </div>
                
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                
                <p class="mt-3 text-muted text-center">&copy; Frontech 2025 - Servidor 2</p>
            </form>
        </div>
    </div>
</body>
</html>