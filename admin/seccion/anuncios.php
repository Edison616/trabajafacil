<?php include("../template/cabecera.php"); ?>
<?php 

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$email1=(isset($_POST['email1']))?$_POST['email1']:"";
$archivo1=(isset($_FILES['archivo1']['name']))?$_FILES['archivo1']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");

switch($accion){

     case"Agregar":
        $sentenciaSQL= $conexion->prepare("INSERT INTO postulantes (ci,email,archivo) VALUES (:ci,:email,:archivo);");
        $sentenciaSQL->bindParam(':ci',$txtID);
        $sentenciaSQL->bindParam(':email',$email1);

        $fecha= new DateTime();
        $nombreArchivo=($archivo1!="")?$fecha->getTimestamp()."_".$_FILES["archivo1"]["name"]:"archivo.nulo";

        $tmpImagen=$_FILES["archivo1"]["tmp_name"];
        
        if($tmpImagen!=""){
        
            move_uploaded_file($tmpImagen,"../../arc/".$nombreArchivo);

        }

        $sentenciaSQL->bindParam(':archivo',$nombreArchivo);
        $sentenciaSQL->execute();
        break;


         case"Seleccionar":
          $sentenciaSQL= $conexion->prepare("SELECT * FROM postulantes WHERE ci=:ci");
          $sentenciaSQL->bindparam(':ci',$txtID);
          $sentenciaSQL->execute();
          $postulante=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

         
          $email1=$postulante['email'];
          $archivo1=$postulante['archivo'];
          
          break;

          
        case"Modificar":

          $sentenciaSQL= $conexion->prepare("UPDATE postulantes SET email=:email WHERE ci=:ci ");
          $sentenciaSQL->bindparam(':ci',$txtID);
          $sentenciaSQL->bindparam(':email',$email1);
          $sentenciaSQL->execute();
          
          if($archivo1!=""){    

            $fecha= new DateTime();
            $nombreArchivo=($archivo1!="")?$fecha->getTimestamp()."_".$_FILES["archivo1"]["name"]:"archivo.nulo";
             
            $tmpImagen=$_FILES["archivo1"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../arc/".$nombreArchivo);

            $sentenciaSQL= $conexion->prepare("SELECT archivo FROM postulantes WHERE ci=:ci");
            $sentenciaSQL->bindparam(':ci',$txtID);
            $sentenciaSQL->execute();
            $postulante=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if( isset($postulante["archivo"])&&($postulante["archivo"]!="archivo.nulo")){

              if(file_exists("../../arc/".$postulante["archivo"])){

                  unlink("../../arc/".$postulante["archivo"]);

                }
            }  

          $sentenciaSQL= $conexion->prepare("UPDATE postulantes SET archivo=:archivo WHERE ci=:ci ");
          $sentenciaSQL->bindparam(':ci',$txtID);
          $sentenciaSQL->bindparam(':archivo',$nombreArchivo);
         $sentenciaSQL->execute();

              } 
          break;

          case"Cancelar":
          header("Location:anuncios.php");
      
          break;
          
        

          case"Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT archivo FROM postulantes WHERE ci=:ci");
            $sentenciaSQL->bindparam(':ci',$txtID);
            $sentenciaSQL->execute();
            $postulante=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if( isset($postulante["archivo"])&&($postulante["archivo"]!="archivo.nulo")){

              if(file_exists("../../arc/".$postulante["archivo"])){

                  unlink("../../arc/".$postulante["archivo"]);

                }
            }  
           
           
            $sentenciaSQL= $conexion->prepare("DELETE FROM postulantes WHERE ci=:ci");
            $sentenciaSQL->bindparam(':ci',$txtID);
            $sentenciaSQL->execute();
            header("Location:anuncios.php");
            break;
              
}

$sentenciaSQL= $conexion->prepare("SELECT*FROM postulantes");
$sentenciaSQL->execute();
$listaPostulantes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="col-md-5">

   <div class="card">
    <div class="card-header">
       Datos de los Postulantes
    </div>
    <div class="card-body">
    <form method="POST" enctype="multipart/form-data">

<div class = "form-group">
<label for="txtID">CI:</label>
<input type="text" required class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID"  placeholder="ingresar cédula">

</div>

<div class="form-group">
  <label for="email">CORREO:</label>
  <input type="email1"  class="form-control " value="<?php echo $email1; ?> " name="email1" id="email1" aria-describedby="emailHelpId" placeholder="Correo Electrónico">
  <small id="emailHelpId" required class="form-text text-muted">Porfavor ingresar un correo válido.</small>
    </div>

<div class = "form-group">
<label for="archivo1">CURRICULUM:</label>
<?php echo $archivo1; ?>
<input type="file"  class="form-control"  name="archivo1" id="archivo1"  placeholder="">
<small id="archivo1" class="form-text text-muted">se aceptan archivos pdf de hasta 3MB.</small>

</div>


<div class="btn-group" role="group" aria-label="">
  <button type="submit" name="accion"<?php echo($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
  <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
  <button type="submit" name="accion"<?php echo($accion!="Seleccionar")?"disabled":""; ?> value="Cancelar"  class="btn btn-info">Cancelar</button>
</div>
</form>
  
</div>
</div>
 
</div>
<div class="col-md-7">
     <table class="table-bordered">
      <thead>
        <tr>
          <th>CEDULA</th>
          <th>CORREO</th>
          <th>CURRICULUM</th>
          <th>ACCIONES</th>
        </tr>
      </thead>
      <tbody>
        
        <?php 
         foreach($listaPostulantes as $postulante){
        ?>
        <tr>

          <td>d<?php echo $postulante['ci']; ?></td>
          <td><?php echo $postulante['email']; ?></td>
          <td><?php echo $postulante['archivo']; ?></td>
          <td>
      
          
          <form method="post">
         
          <input type="hidden" name="txtID" id="txtID" value="<?php echo $postulante['ci']; ?>"/>

          <input type="submit" name="accion" value="Seleccionar" class= "btn btn-primary"/>

          <input type="submit" name="accion" value="Borrar" class= "btn btn-danger"/>
      
        </form>

        </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    
    </div>
<?php include("../template/pie.php"); ?>