<?php
header("Content-type: application/json");
include("../clases/Conexion.php");
include("../clases/Filtro.php");
ini_set("display_errors",0);
$token = $_POST['source'];
$arts = new Filtro($token);
if(isset($_POST["opt"]))
$opt = $_POST["opt"];
else
$opt = 0;
if(isset($_POST["id"]))
$id = $_POST["id"];
else
$id = "0";
if(isset($_POST["id_plantilla"]))
$id_plantilla = $_POST["id_plantilla"];
else
$id_plantilla = "0";
if(isset($_POST["nombre"]))
$nombre = $_POST["nombre"];
else
$nombre = "0";
if(isset($_POST["fechaInicio"]))
$fechaInicio = $_POST["fechaInicio"];
else
$fechaInicio = "0";
if(isset($_POST["fechaFin"]))
$fechaFin = $_POST["fechaFin"];
else
$fechaFin = "0";
if(isset($_POST["id_usuario"]))
$id_usuario = $_POST["id_usuario"];
else
$id_usuario = "0";
/*if(isset($_POST["status"]))
$status = $_POST["status"];
else
$status = "0";*/
if(isset($_POST["resultado"]))
$resultado = $_POST["resultado"];
else
$resultado = "0";
if(isset($_POST["id_grupo"]))
$id_grupo = $_POST["id_grupo"];
else
$id_grupo = "0";
if(isset($_POST["tipoReporte"]))
$tipoReporte = $_POST["tipoReporte"];
else
$tipoReporte = "0";
switch($opt)
{
   case 0:
       $server['col1'] = $arts->flexibleSingleBind("SELECT barra FROM jb_logos LIMIT 1",0,0,0,0,'',0,$token);
   break;
   case 1:
       $server = $arts->getFilteredReports($id,$id_plantilla,$nombre,$fechaInicio,$fechaFin,$id_usuario,$_POST["status"],$resultado,$id_grupo,$tipoReporte,$empresa,$_POST['ordenar'],$_POST["ordenartipo"],$_POST["dispositivo"]);
   break;
   case 2:
       $server = $arts->getEmpleadosWithReports($empresa);//($_SESSION['nombre_empresa']

   break;
   case 3:
       $server = $arts->getGruposWithReports($empresa);//($_SESSION['nombre_empresa']

   break;
   case 4:
       $server = $arts->getNombresZonas($empresa);//($_SESSION['nombre_empresa']
   break;
   case 5:
       $server = $arts->getNombresSucursales($empresa);//($_SESSION['nombre_empresa']
   break;
   case 6:
       $server = $arts->getNombresTipoReporte($id_empresa);//id de soriana (metadata)
   break;
   case 7:
       $server = $arts->getComentariosReporte($_POST['source'],$empresa);//id de soriana (metadata)
   break;
   default:
   break;
}
echo json_encode($server);
?>
