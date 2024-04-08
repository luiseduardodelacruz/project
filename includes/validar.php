<?php
require_once("config.php");

$conexion = mysqli_connect($host, $user, $password, $database);

if(!$conexion) {
    echo "No se pudo establecer la conexión a la base de datos. Error: " . mysqli_connect_error();
    exit;
}

if(isset($_POST['registrar'])) {

    if(strlen($_POST['nombre']) >=1 && strlen($_POST['correo'])  >=1 && strlen($_POST['telefono'])  >=1 
    && strlen($_POST['password'])  >=1 && strlen($_POST['rol']) >= 1 ) {

        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $telefono = trim($_POST['telefono']);
        $password = trim($_POST['password']);
        $rol = trim($_POST['rol']);

        // Sentencia preparada
        $consulta = mysqli_prepare($conexion, "INSERT INTO user (nombre, correo, telefono, password, rol) VALUES (?, ?, ?, ?, ?)");

        // Vincular parámetros
        mysqli_stmt_bind_param($consulta, "sssss", $nombre, $correo, $telefono, $password, $rol);

        // Ejecutar consulta
        mysqli_stmt_execute($consulta);

        // Cerrar consulta preparada
        mysqli_stmt_close($consulta);

        mysqli_close($conexion);

        header('Location: ../views/user.php');
    }
}
?>
