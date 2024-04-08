<?php
   
require_once ("_db.php");




if (isset($_POST['accion'])){ 
    switch ($_POST['accion']){
        //casos de registros
        case 'editar_registro':
            editar_registro();
            break; 

            case 'eliminar_registro';
            eliminar_registro();
    
            break;

            case 'acceso_user';
            acceso_user();
            break;


		}

	}

    function editar_registro() {
		$conexion=mysqli_connect("localhost","root","","r_user");
		extract($_POST);
		$consulta="UPDATE user SET nombre = '$nombre', correo = '$correo', telefono = '$telefono',
		password ='$password', rol = '$rol' WHERE id = '$id' ";

		mysqli_query($conexion, $consulta);


		header('Location: ../views/user.php');

}

function eliminar_registro() {
    $conexion=mysqli_connect("localhost","root","","r_user");
    extract($_POST);
    $id= $_POST['id'];
    $consulta= "DELETE FROM user WHERE id= $id";

    mysqli_query($conexion, $consulta);


    header('Location: ../views/user.php');

}

function acceso_user() {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    session_start();

    try {
        $conexion = mysqli_connect("localhost", "root", "", "r_user");

        if (!$conexion) {
            throw new Exception("No se pudo establecer la conexión a la base de datos.");
        }

        $consulta = "SELECT * FROM user WHERE nombre='$nombre'";
        $resultado = mysqli_query($conexion, $consulta);

        if (!$resultado) {
            throw new Exception("Error al realizar la consulta en la base de datos.");
        }

        $filas = mysqli_fetch_array($resultado);

        if (!$filas) {
            throw new Exception("El usuario '$nombre' no existe.");
        }

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







