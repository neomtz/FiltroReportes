<?php
ini_set('display_errors',0);
include '../clases/Filtro.php';
$arts = new Filtro($_POST['source']);

if($_GET['source2'] == 'initconf')
{
  $correo =  $arts->flexibleSingleBind('SELECT email FROM jb_empleado WHERE id_empleado = ? AND id_empresa > 0 LIMIT 1',array($_POST['source1']),"i",$_POST['source']);
  $idcs = trim($idcs,',');
  $idcs = '"'.str_replace(',','","',$idcs).'"';

  $server['checklists'] = $arts->getChecklistsUsuario($idcs);
}
if($_GET['source2'] == 'getChecklists')
{
  /*corrección necesaria para que funcione email como correo alterno*/
  $correo =  $arts->flexibleSingleBind('SELECT l.usuario FROM jb_empleado e INNER JOIN jb_empleado_login l ON l.id_empleado = e.id_empleado WHERE e.id_empleado = ? AND e.id_empresa > 0 LIMIT 1',$_POST['source1'],0,0,0,"s",1,$_POST['source']);
  $idcs =  $arts->flexibleSingleBind('SELECT id_section FROM jb_dinamic_relations WHERE mail = ? LIMIT 1',$correo,0,0,0,"s",1,'');
  $idcs = trim($idcs,',');
  $idcs = '"'.str_replace(',','","',$idcs).'"';
  $server = $arts->getChecklistsUsuario($idcs);
}
if($_GET['source2'] == 'mostrarGrupos'){
  $server = $arts->getGrupos($_POST['source2']);
}

?>
