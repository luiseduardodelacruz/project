<?php
   
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

function editar_registro() {
    global $conexion;
    extract($_POST);
    
    $consulta = "UPDATE user SET nombre = ?, correo = ?, telefono = ?, password = ?, rol = ? WHERE id = ?";
    
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("ssssii", $nombre, $correo, $telefono, $password, $rol, $id);
    $stmt->execute();
    
    header('Location: ../views/user.php');
}

function eliminar_registro() {
    global $conexion;
    $id = $_POST['id'];
    
    $consulta = "DELETE FROM user WHERE id = ?";
    
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header('Location: ../views/user.php');
}

function acceso_user() {
    global $conexion;
    session_start();
    
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    
    try {
        $consulta = "SELECT * FROM user WHERE nombre = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 0) {
            throw new Exception("El usuario '$nombre' no existe.");
        }
        
        $filas = $resultado->fetch_assoc();
        
        if ($filas['password'] != $password) {
            throw new Exception("La contraseña es incorrecta.");
        }
        
        $_SESSION['nombre'] = $nombre;
        
        if ($filas['rol'] == 1) {
            header('Location: ../views/user.php');
        } elseif ($filas['rol'] == 2) {
            header('Location: ../views/lector.php');
        } else {
            throw new Exception("Rol no definido para el usuario '$nombre'.");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: error.php');
        session_destroy();
    }
}
