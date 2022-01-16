<?php include '../template\header.php';?>

<?php 
    // vemos a traves de esos metodos que la informacion esta siendo almacenada
    // print_r($_POST);
    // print_r($_FILES);

    //No se esta validando el formulario,se esta viendo que los datos llegan:
    $txtID= (isset($_POST['txtId']))? $_POST['txtId']:"";
    $txtNombre= (isset($_POST['txtNombre']))? $_POST['txtNombre']:"";
    $txtImagen= (isset($_FILES['txtImagen']['name']))? $_FILES['txtImagen']['name']:"";
    $accion= (isset($_POST['accion']))? $_POST['accion']:"";


    // veo que los datos que salen:
    // echo $txtID."<br/>";
    // echo $txtNombre."<br/>";
    // echo $txtImagen."<br/>";
    // echo $accion."<br/>";

    // archivo que contiene la conexion a bd
    include '../config/db.php';


    // Acciones a realizar al presionar los botones correspondientes
    switch($accion){
        case "Agregar":
            //INSERT INTO `libros` (`ID`, `Nombre`, `Imagen`) VALUES (NULL, 'Libro de PHP', 'libro_php.jpeg'); 
            $sentenciaSQL=$conexion->prepare("INSERT INTO libros (Nombre, Imagen) VALUES (:nombre,:imagen);");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);

            //Generamos una instruccion de subida
            // guardamos y movemos el archivo subido a la carpeta de imagenes
            
            $fecha = new DateTime();
            $nombreArchivo =($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){
                move_uploaded_file($tmpImagen,"../../img/$nombreArchivo");
            }

            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();

            header("Location:productos.php");

           // echo "Presionado el boton Agregar";
            break;

        case "Modificar":
            
            $sentenciaSQL = $conexion->prepare("UPDATE libros SET Nombre=:nombre WHERE ID=:idLibro;");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':idLibro',$txtID);
            $sentenciaSQL->execute();

            if($txtImagen != ""){

                // generamos un archivo nuevo de guardado
                $fecha = new DateTime();
                $nombreArchivo =($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
                $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
                move_uploaded_file($tmpImagen,"../../img/$nombreArchivo");

                //Borramos el archivo anterior
                $sentenciaSQL= $conexion->prepare("SELECT Imagen FROM libros WHERE id=:idLibro;");
                $sentenciaSQL->bindParam(':idLibro',$txtID);
                $sentenciaSQL->execute();
                $libro= $sentenciaSQL->fetch(PDO::FETCH_LAZY);
    
                //Si existe esa imagen y es diferente a imagen.jpg
                if(isset($libro['Imagen']) && ($libro['Imagen'] != "imagen.jpg")){
                    // Buscamos si existe esa imagen en la carpeta 
                    if(file_exists("../../img/" . $libro['Imagen'])){
                        // Borramos la imagen de la carpeta
                        unlink("../../img/" . $libro['Imagen']);
                    }
                }
            
                //actualizamos el archivo con el nombre del archivo nuevo
                $sentenciaSQL = $conexion->prepare("UPDATE libros SET Imagen=:imagen WHERE ID=:idLibro;");
                $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
                $sentenciaSQL->bindParam(':idLibro',$txtID);
                $sentenciaSQL->execute();
            }

            header("Location:productos.php");

            //echo "Presionado el boton Modificar";
            break;

        case "Cancelar":
            //Redireccionamos a la pagina de productos al cancelar
            header("Location:productos.php");
            //echo "Presionado el boton Cancelar";
            break;

        case "Seleccionar":
            $sentenciaSQL= $conexion->prepare("SELECT * FROM libros WHERE id=:idLibro;");
            $sentenciaSQL->bindParam(':idLibro',$txtID);
            $sentenciaSQL->execute();
            $libro= $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            
            $txtNombre = $libro['Nombre'] ?? "sin nombre";
            $txtImagen = $libro['Imagen'] ?? "sin imagen";
            //echo "Presionado el boton Seleccionar";
            break;

        case "Borrar": 
            //Busco la imagen con el id correspondiente
            $sentenciaSQL= $conexion->prepare("SELECT Imagen FROM libros WHERE id=:idLibro;");
            $sentenciaSQL->bindParam(':idLibro',$txtID);
            $sentenciaSQL->execute();
            $libro= $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            //Si existe esa imagen y es diferente a imagen.jpg
            if(isset($libro['Imagen']) && ($libro['Imagen'] != "imagen.jpg")){
                // Buscamos si existe esa imagen en la carpeta 
                if(file_exists("../../img/" . $libro['Imagen'])){
                    // Borramos la imagen de la carpeta
                    unlink("../../img/" . $libro['Imagen']);
                }
            }

            
            //Borra los libros de la tabla:
            $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE ID=:idLibro;");
            $sentenciaSQL->bindParam(':idLibro',$txtID);
            $sentenciaSQL->execute();

            header("Location:productos.php");
            // echo "Presionado el boton Borrar";
            break;
    }


    // Selecciono todos los libros de la base y con fetchAll recupero todos los registros para poder mostrarlos en la variable
    $sentenciaSQL= $conexion->prepare("SELECT * FROM libros;");
    $sentenciaSQL->execute();
    $listaLibros= $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

    <!-- Formulario para agregar libros -->

<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos de Libros
        </div>
        <div class="card-body">
            
            <form method="POST" enctype="multipart/form-data">
                
                <div class = "form-group">
                    <label>ID:</label>
                    <input type="text" required readonly class="form-control" name="txtId" value="<?php echo $txtID; ?>" id="txtId"  placeholder="ID">
                </div>
                
                <div class = "form-group">
                    <label>Nombre:</label>
                    <input type="text" required class="form-control" name="txtNombre" value="<?php echo $txtNombre; ?>" id="txtNombre"  placeholder="Nombre de Libro">
                </div>
            
                <div class = "form-group">
                    <label>Imagen:</label>
                    <br>
                    <?php if($txtImagen!=""){?>
                        <img src="../../img/<?php echo $txtImagen; ?>" class="img-thumbnail rounded" width="50" alt="imagenLibro">
                    <?php } ?>

                    <input type="file" class="form-control" name="txtImagen"  id="txtImagen"  placeholder="Imagen de libro">
                </div>
                <br>
                <div class="btn-group" role="group" aria-label="">
                    <!--Ocultamos botones dependiendo de la seleccion -->
                    <button type="submit" name="accion" value="Agregar" <?php echo($accion=="Seleccionar")?"disabled":""; ?> class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" value="Modificar" <?php echo($accion!="Seleccionar")?"disabled":""; ?> class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" value="Cancelar" <?php echo($accion!="Seleccionar")?"disabled":""; ?> class="btn btn-info">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>


    <!-- Tabla de libros-->

<div class="col-md-7">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Mediante foreach creamos todas las columnas con los datos de la bd respetando el nombre que figura en la bd -->
            <?php foreach($listaLibros as $libro){ ?>
                <tr>
                    <td><?php echo $libro['ID']; ?></td>
                    <td><?php echo $libro['Nombre']; ?></td>
                    <td>
                    <img src="../../img/<?php echo $libro['Imagen']; ?>" class="img-thumbnail rounded" width="50" alt="imagenLibro">
                    </td>
                    <td>
                        <form method="POST">

                            <!--Linea de input necesaria para recibir los datos y realizar la seleccion y el borrado -->
                            <input type="hidden" name="txtId" id="txtId" value="<?php echo $libro['ID']; ?>" />

                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    

</div>

<?php include '../template\footer.php';?>
