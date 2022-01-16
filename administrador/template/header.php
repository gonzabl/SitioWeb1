<?php 

session_start();

// si no hay usuario logueado me redirige al login de administracion
if(!isset($_SESSION['usuario'])){
    header("Location:../index.php");
}else{
    if($_SESSION['usuario']=="ok"){
        $nombreUsuario=$_SESSION['nombreUsuario'];
    }
}

?>

<!doctype html>
<html lang="en">
    <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    </head>
    <body>

    <?php $url="https://".$_SERVER['HTTP_HOST']."/sitiowebgt"?>

        <nav class="navbar navbar-expand navbar-light bg-light">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link active" href="#">Administrador del Sitio <span class="visually-hidden">(current)</span></a>
                <a class="nav-item nav-link" href="<?php echo $url ?>/administrador/inicio.php">Inicio</a>
                <a class="nav-item nav-link" href="<?php echo $url ?>/administrador/seccion/productos.php">Libros</a>
                <a class="nav-item nav-link" href="<?php echo $url ?>/administrador/seccion/cerrar.php">Cerrar</a>
                <a class="nav-item nav-link" href="<?php echo $url ?>">Ver sitio web</a>

            </div>
        </nav>

        <div class="container">
            <br>
            <div class="row">