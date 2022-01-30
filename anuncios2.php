<?php include("template/cabecera.php") ?>

    <?php
    
      include("admin/config/bd.php");

      $sentenciaSQL= $conexion->prepare("SELECT*FROM postulantes");
      $sentenciaSQL->execute();
      $listaPostulantes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

    ?>
 <?php foreach($listaPostulantes as $postulante ) { ?>
<div class="col-md-3">
    <div class="card">
        <img class="card-img-top" src="holder.js/100x180/" alt="">
        <div class="card-body">
            <h4 class="card-title"><?php echo $postulante['ci']; ?></h4>
            <p class="card-text">cédula postulante</p>

            <h4 class="card-title"><?php echo $postulante['email']; ?></h4>
            <p class="card-text">email postulante</p>


        </div>
    </div>
    </div>
    
<?php } ?>

 
<?php include("template/piepag.php") ?>