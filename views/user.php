<?php

session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">

    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/es.css">
    <title>Usuarios</title>
</head>

<div class="container is-fluid">

    <div class="col-xs-12">
        <h1>Bienvenido Administrador <?php echo $_SESSION['nombre']; ?></h1>
        <br>
        <h1>Lista de usuarios</h1>
        <br>
        <div>
            <a class="btn btn-warning" href="../includes/_sesion/cerrarSesion.php">Log Out
            </a>

        </div>
        <br>

        <br>

        </form>

        <p>Proximamente.</p>

    </div>
</div>

</html>
