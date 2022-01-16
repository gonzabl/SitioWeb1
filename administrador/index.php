<?php 
    // condicional para indicar si hicimos un envio de formulario via post para que nos redirija a otro sitio
    session_start();
    if($_POST){

        //validamos el usuario y la contrasenia para poder acceder a la pagina de administrador de manera simple
        // usario y contrasenia en los array salen de los inputs con el campo name
        if($_POST['usuario']=="adminGT" && $_POST['contrasenia']=='12345'){

            //inicializamos las variables de session que van a ser pedidas en  header.php
            $_SESSION['usuario']="ok";
            $_SESSION['nombreUsuario'] = "adminGT";
            header("Location:inicio.php");
        }else{
            $mensaje="Error: el usuario o contraseña son incorrectos.";
        }
    } 


?>


<!doctype html>
<html lang="en">
    <head>
        <title>Login</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS v5.0.2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <div class="row">

                <div class="col-md-4">
                    
                </div>

                <div class="col-md-4">
                    <br><br><br>
                    <div class="card">
                        <div class="card-header">
                            Login
                        </div>
                        <div class="card-body">

                        <?php if(isset($mensaje)){ ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $mensaje;?>
                            </div>
                        <?php } ?>

                            <form method="POST">
                                <div class = "form-group">
                                <label>Usuario</label>
                                <input type="text" class="form-control" name="usuario"  placeholder="Ingresar nombre de usuario">
                                </div>
                                <br>
                                <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" class="form-control" name="contrasenia"  placeholder="Ingresar contraseña">
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Entrar al administrador</button>
                            </form>
                            
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </body>
</html>