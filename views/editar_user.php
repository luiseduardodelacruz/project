<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

// Obtener el ID del usuario de la URL de forma segura
$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : die('ID de usuario no válido.');

// Establecer conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "r_user");

// Preparar la consulta utilizando una sentencia preparada
$consulta = mysqli_prepare($conexion, "SELECT * FROM user WHERE id = ?");

// Vincular el parámetro
mysqli_stmt_bind_param($consulta, "i", $id);

// Ejecutar la consulta
mysqli_stmt_execute($consulta);

// Obtener el resultado de la consulta
$resultado = mysqli_stmt_get_result($consulta);

// Obtener el usuario
$usuario = mysqli_fetch_assoc($resultado);

// Cerrar consulta preparada
mysqli_stmt_close($consulta);

?>

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>

    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/es.css">
</head>

<body id="page-top">

    <form action="../includes/_functions.php" method="POST">
        <div id="login">
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <br>
                            <br>
                            <h3 class="text-center">Editar usuario</h3>
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo:</label><br>
                                <input type="email" name="correo" id="correo" class="form-control" value="<?php echo $usuario['correo']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo $usuario['telefono']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label><br>
                                <input type="password" name="password" id="password" class="form-control" value="<?php echo $usuario['password']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="rol" class="form-label">Rol de usuario *</label>
                                <input type="number" id="rol" name="rol" class="form-control" placeholder="Escribe el rol, 1 admin, 2 lector.." value="<?php echo $usuario['rol']; ?>" required>
                                <input type="hidden" name="accion" value="editar_registro">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                            </div>
                            <br>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success">Editar</button>
                                <a href="user.php" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
