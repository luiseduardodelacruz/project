<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    exit();
}

require_once("_db.php");

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        // Casos de registros
        case 'editar_registro':
            editar_registro();
            break;

        case 'eliminar_registro':
            eliminar_registro();
            break;

        case 'acceso_user':
            acceso_user();
            break;
    }
}

function editar_registro()
{
    $conexion = establecer_conexion_db();

    // Verificar la conexión
    if (!$conexion) {
        mostrar_error("No se pudo establecer la conexión a la base de datos.");
        return;
    }

    // Verificar datos recibidos
    if (!isset($_POST['nombre'], $_POST['correo'], $_POST['telefono'], $_POST['password'], $_POST['rol'], $_POST['id'])) {
        mostrar_error("Faltan datos para actualizar el registro.");
        return;
    }

    // Preparar la consulta utilizando una sentencia preparada
    $consulta = "UPDATE user SET nombre = ?, correo = ?, telefono = ?, password = ?, rol = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $consulta);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        mostrar_error("Error al preparar la consulta.");
        return;
    }

    extract($_POST);

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "ssssii", $nombre, $correo, $telefono, $password, $rol, $id);

    // Ejecutar la consulta
    if (!mysqli_stmt_execute($stmt)) {
        mostrar_error("Error al ejecutar la consulta de actualización.");
        return;
    }

    // Cerrar consulta preparada
    mysqli_stmt_close($stmt);

    header('Location: ../views/user.php');
}

function eliminar_registro()
{
    $conexion = establecer_conexion_db();

    // Verificar la conexión
    if (!$conexion) {
        mostrar_error("No se pudo establecer la conexión a la base de datos.");
        return;
    }

    // Verificar datos recibidos
    if (!isset($_POST['id'])) {
        mostrar_error("Falta el ID del usuario a eliminar.");
        return;
    }

    // Preparar la consulta utilizando una sentencia preparada
    $consulta = "DELETE FROM user WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $consulta);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        mostrar_error("Error al preparar la consulta.");
        return;
    }

    extract($_POST);
    $id = $_POST['id'];

    // Vincular parámetro
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Ejecutar la consulta
    if (!mysqli_stmt_execute($stmt)) {
        mostrar_error("Error al ejecutar la consulta de eliminación.");
        return;
    }

    // Cerrar consulta preparada
    mysqli_stmt_close($stmt);

    header('Location: ../views/user.php');
}

function acceso_user()
{
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    session_start();

    try {
        $conexion = establecer_conexion_db();

        // Verificar la conexión
        if (!$conexion) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        // Preparar la consulta utilizando una sentencia preparada
        $consulta = "SELECT * FROM user WHERE nombre=?";
        $stmt = mysqli_prepare($conexion, $consulta);

        // Verificar la preparación de la consulta
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta.");
        }

        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        // Verificar el resultado de la consulta
        if (!$resultado) {
            throw new Exception("Error al realizar la consulta en la base de datos.");
        }

        $filas = mysqli_fetch_array($resultado);

        // Verificar si el usuario existe
        if (!$filas) {
            throw new Exception("El usuario '$nombre' no existe.");
        }

        // Verificar la contraseña
        if ($filas['password'] != $password) {
            throw new Exception("La contraseña es incorrecta.");
        }

        $_SESSION['nombre'] = $nombre;

        // Redireccionar según el rol del usuario
        if ($filas['rol'] == 1) {
            header('Location: ../views/user.php');
        } elseif ($filas['rol'] == 2) {
            header('Location: ../views/lector.php');
        } else {
            throw new Exception("Rol no definido para el usuario '$nombre'.");
        }
    } catch (Exception $e) {
        mostrar_error($e->getMessage());
        session_destroy();
    }
}

function mostrar_error($mensaje)
{
    $_SESSION['error_message'] = $mensaje;
    header('Location: error.php');
    exit();
}

function establecer_conexion_db()
{
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $base_de_datos = "r_user";

    // Crear conexión
    $conexion = mysqli_connect($host, $usuario, $contrasena, $base_de_datos);

    return $conexion;
}
?>
