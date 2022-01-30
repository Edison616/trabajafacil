<?php $host="localhost";
$bd="bdtrabajafacil";
$usuario="root";
$password="";

try {
  $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$password);
  //if($conexion){ echo "conectado a Base de datos";
  
  
  
} catch (Exception $ex ) {

 echo $ex->getmessage();
}

?>