<?php include 'template\header.php'; ?>

<!--Agregando la bd -->
<?php 
    include 'administrador/config/db.php';

    $sentenciaSQL= $conexion->prepare("SELECT * FROM libros;");
    $sentenciaSQL->execute();
    $listaLibros= $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Recorremos la lista de libros de la tabla y generamos una card por cada uno -->

<?php foreach($listaLibros as $libro){ ?>
<div class="col-md-3">
    <div class="card">
        <img class="card-img-top" src="./img/<?php echo $libro['Imagen']; ?>" alt="Card image">
        <div class="card-body">
            <h4 class="card-title"><?php echo $libro['Nombre']; ?></h4>
            <a name="" id="" class="btn btn-primary" href="#" role="button">Ver más</a>
        </div>
    </div>
</div>
<?php }?>            

<?php include 'template\footer.php'; ?>