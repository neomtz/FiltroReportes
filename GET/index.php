<?php
 header('Content-type: application/json');
 ini_set('display_errors',0);
 include '../clases/Conexion.php';
 $server = array();
 if($_GET['source1'] == 'indicadores')
   include 'controladorIndicadores.php';

 echo json_encode($server);
?>
