<?php
class Filtro extends Conexion
{
 var $token;
 var $conexion;
 var $insertId;
 private $paginador = 20;
 function __construct($token)
 {
   $this->token = $token;
 }
 function getEmpleadosWithReports()
 {
 $listado = array();
 $mysqli = $this->fullConnect($this->token);
  $query =  "SELECT DISTINCT(je.ID_EMPLEADO),CONCAT(je.NOMBRES,' ',je.A_PATERNO,' ',je.A_MATERNO) nombre FROM jb_empleado je INNER JOIN jb_reporte_mantenimiento jrm ON jrm.id_usuario = je.ID_EMPLEADO WHERE je.id_empresa > 0 order by je.NOMBRES desc";
  if ($stmt = $mysqli->prepare($query))
  {
//    $stmt->bind_param('s',$idChecklist);
    if ($stmt->execute())
     $stmt->bind_result($col1,$col2);
      while ($stmt->fetch())
      {
        $registro['col1'] = $col1;
        $registro['col2'] = $col2;
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
   return $mysqli->error;
 }
 function getNombresSucursales()
 {
 $listado = array();
 $mysqli = $this->fullConnect($this->token);
  $query = "SELECT DISTINCT(nombre) FROM jb_empresa_sucursales ORDER BY nombre ASC";
  if ($stmt = $mysqli->prepare($query))
  {
//    $stmt->bind_param('s',$idChecklist);
    if ($stmt->execute())
     $stmt->bind_result($col1);
      while ($stmt->fetch())
      {
        $registro['col1'] = $col1;
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
   return $mysqli->error;
 }
 function getGruposWithReports()
 {
 $listado = array();
 $mysqli = $this->fullConnect($this->token);
  $query = "SELECT DISTINCT(jg.id),jg.nombre  FROM jb_grupos jg INNER JOIN jb_reporte_mantenimiento jrm ON jrm.id_grupo = jg.id WHERE jg.id >0";
  if ($stmt = $mysqli->prepare($query))
  {
//    $stmt->bind_param('s',$idChecklist);
    if ($stmt->execute())
     $stmt->bind_result($col1,$col2);
      while ($stmt->fetch())
      {
        $registro['col1'] = $col1;
        $registro['col2'] = $col2;
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
   return $mysqli->error;
 }
 function getFilteredReports($id,$id_plantilla,$nombre,$fechaInicio,$fechaFin,$id_usuario,$status,$resultado,$id_grupo,$tipoReporte,$tipeConection,$ordenar,$ordenarTipo,$dispositivo)
 {
   $ordernarPor  = "";
   if($status == "0")
   $status= " r.status > ".$status;
   else
   $status= " r.status = ".$status;
   if(!empty($ordenar))
   {
      if(empty($ordenarTipo) || $ordenarTipo == "1")
        $ordenarPor = " ORDER BY r.".$ordenar." ASC";
      else
        $ordenarPor = " ORDER BY r.".$ordenar." DESC";
   }
   if($id!=0)
 	{
 		$id1= " r.id = '".$id."' AND";
 	}
 	if($nombre!="")
 	{
 	 $nombre = str_replace("-"," ",$nombre);
 		$nombre= "%".$nombre."%";
 		$nom1= " r.nombre LIKE '".$nombre."'  AND";
 	}
 	if($id_usuario!=0 && is_numeric($id_usuario))
 	{
 		$idUser= " r.id_usuario = ".$id_usuario." AND";
 	}
 	if($resultado!=0)
 	{
 		$resultado= "%".$resultado."%";
 		$resut= " r.resultado LIKE '".$resultado."' AND";
 	}
 	if($id_grupo!=0  && is_numeric($id_grupo))
 	{

 		$idGr= " r.id_grupo = ".$id_grupo." AND ";
 	}
 	if($tipoReporte!=0  && $tipoReporte!="")
 	{
    $tipoReporte = '"'.str_replace(',','","',$tipoReporte).'"';
 		$tipoR= " r.id_plantilla IN ($tipoReporte) AND ";
 	}
 	if($fechaInicio!=0 && $fechaFin!=0)
 	{

 	  $fechaInicio= $fechaInicio.' 00:00:00';
 	  $fechaFin= $fechaFin.' 23:59:59';
 	  $fecha1= " r.fecha BETWEEN '".$fechaInicio."' AND '".$fechaFin."' AND ";
 	}
    $listado = array();
    $mysqli = $this->fullConnect($this->token);
    $query = 'SELECT distinct(r.id),r.nombre,r.fecha,r.resultado,r.id_plantilla,g.nombre,CONCAT(e.nombres," ",e.a_paterno), f.fecha
    FROM jb_reporte_mantenimiento r
    LEFT JOIN jb_grupos g ON g.id = r.id_grupo
    LEFT JOIN jb_empleado e ON e.id_empleado = r.id_usuario
    LEFT JOIN jb_grupos_mensajes f ON f.id_result = r.id
    WHERE '.$id1.''.$nom1.''.$idUser.''.$statu1.''.$resut.''.$idGr.''.$tipoR.''.$fecha1.''.$status.''.$ordenarPor;
    if ($stmt = $mysqli->prepare($query))
    {
      //$stmt->bind_param('s',$idChecklist);
      if ($stmt->execute())
       $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8);
        while ($stmt->fetch())
        {
          $sigue = true;
          $registro['col1'] = $col1;
          $registro['col2'] = $col2;
          $registro['col3'] = $col3;
          $registro['col4'] = $col4;
          $registro['col5'] = $col6;
          $registro['col6'] = $col7;
          $registro['col7'] = $col5;
          $registro['col8'] = $col8;
          $registro['col9'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19a9d717a7" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Nombre del colaborador
          $registro['col10'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19aa0cd6e2" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Nombre del Jefe Inmediato
          $registro['col11'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19aa583428" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Estafeta
          $registro['col12'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19aabc4019" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Puesto actual

          $registro['col13'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19be8323d8" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Cómo te sientes en la empresa?
          $registro['col14'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19bfcc8045" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué te gustaría que mejorara en mi liderazgo contigo?
          $registro['col15'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c0873ac6" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué oportunidades crees que tienes tú?
          $registro['col16'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c117aeb3" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿A qué te Comprometes?

          $registro['col17'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c322a69e" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué fecha estableces para cumplir los compromisos?
          $registro['col18'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c464a5b5" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Cuéntame, ¿Qué antigüedad tienes en la empresa?
          $registro['col19'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c4fc3359" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Cómo se encuentra tu familia?
          $registro['col20'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c546142f" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué estudiaste?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col20']))
          $registro['col20'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col20'],0,0,0,'s',1,$this->token);

          $registro['col21'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19c62a4da6" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Te gustaría seguir estudiando?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col21']))
          $registro['col21'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col21'],0,0,0,'s',1,$this->token);
          $registro['col22'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19d5237de0" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Que?
          $registro['col23'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19d5def071" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿En cuanto tiempo?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col23']))
          $registro['col23'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col23'],0,0,0,'s',1,$this->token);
          $registro['col24'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19d687afa9" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Cuál es tu estado Civil?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col24']))
          $registro['col24'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col24'],0,0,0,'s',1,$this->token);
          $registro['col25'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19da5dac07" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Tienes Hijos?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col25']))
          $registro['col25'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col25'],0,0,0,'s',1,$this->token);
          $registro['col26'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19dad13b5e" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué edades tienen?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col26']))
          $registro['col26'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col26'],0,0,0,'s',1,$this->token);

          $registro['col27'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19db62803b" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Alguien depende económicamente de ti?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col27']))
          $registro['col27'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col27'],0,0,0,'s',1,$this->token);
          $registro['col28'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19dbd5aa86" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Quién?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col28']))
          $registro['col28'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col28'],0,0,0,'s',1,$this->token);
          $registro['col29'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e0b51a03" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿A qué tiempo vives de la sucursal?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col29']))
          $registro['col29'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col29'],0,0,0,'s',1,$this->token);
          $registro['col30'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e1387fca" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Hablas Ingles?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col30']))
          $registro['col30'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col30'],0,0,0,'s',1,$this->token);
          $registro['col31'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e1a04f94" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿¿En qué Nivel?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col31']))
          $registro['col31'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col31'],0,0,0,'s',1,$this->token);
          $registro['col32'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e1fb853e" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Tienes disponibilidad para cambiar de residencia?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col32']))
          $registro['col32'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col32'],0,0,0,'s',1,$this->token);

          $registro['col33'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e25970ab" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿En cuánto tiempo?
          $registro['col34'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e2998dd7" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Dónde te gustaría vivir?
          $registro['col35'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e35c9490" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Me Puedes proporcionar tu teléfono y algún contacto para emergencias?
          $registro['col36'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19e3c62a23" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Me puedes compartir tu correo electrónico?

          $registro['col37'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d19fed9e2ad" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿En qué puesto te gustaría desarrollarte en la empresa?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col37']))
          $registro['col37'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col37'],0,0,0,'s',1,$this->token);
          $registro['col38'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d1a061ac89c" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//Otro
          $registro['col39'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d1a06e62db6" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Te gustaría certificarte en alguna área?
          $registro['col40'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d1a071f24ce" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Con qué certificaciones cuentas?
          $registro['col41'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d1a0ec4856e" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué necesitas para realizar de manera más eficiente tu trabajo?
          $registro['col42'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "6397fc3c943fc" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué podría mejorar en la organización?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col42']))
          $registro['col42'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col42'],0,0,0,'s',1,$this->token);
          $registro['col43'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "6397fc8652829" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Cuál consideras que es el mayor obstáculo para que el trabajo no se ejecute correctamente?
          if (preg_match('/^[a-z0-9]{13}$/', $registro['col43']))
          $registro['col43'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$registro['col43'],0,0,0,'s',1,$this->token);
          $registro['col44'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_rows WHERE id_column LIKE "62d1a0fbc8b90" AND id_reporte LIKE ?',$col1,0,0,0,'s',1,$this->token);//¿Qué sugerencia o comentario tiene el colaborador?          
          $registro['datos'] = $this->getRegistrosIdr($col1);
          if(is_numeric($dispositivo))
          {
            $sigue = true;
            switch (intval($dispositivo)) {
              case 1:
                if(strlen($col1) != 19 && strlen($col1) != 18)
                  $sigue = false;
                break;
              case 2:
              if(strlen($col1) < 32)
                $sigue = false;
                break;
              case 3:
              if(strlen($col1) > 13)
                $sigue = false;
                break;
              default:
                $sigue = true;
                break;
            }
          }
          if($sigue)
          $listado[] = $registro;
        }
       $stmt->free_result();
       $stmt->close();
     return $listado;
    }
    else
     return $mysqli->error;
 }
 function getChecklistsUsuario($idcs)
 {
 $listado = array();
 $mysqli = $this->fullConnect('');
  $query = "SELECT id,nombre,type_section FROM jb_dinamic_section WHERE id IN ($idcs) AND status = 1 ORDER BY nombre ASC";
  if ($stmt = $mysqli->prepare($query))
  {
    if ($stmt->execute())
     $stmt->bind_result($col1,$col2,$col3);
      while ($stmt->fetch())
      {
        $registro['id'] = $col1;
        $registro['nombre'] = $col2;
        $registro['tipo'] = $col3;
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
   return $mysqli->error;
 }
 function getRegistrosPorIdReporte($id)
 {
 $listado = array();
 $mysqli = $this->fullConnect($this->token);
  $query =  "SELECT a.id,a.id_row,a.id_reporte,a.id_column,a.contenido,b.contenido,b.position from jb_dinamic_tables_rows a
  LEFT JOIN jb_dinamic_tables_option_row b ON b.id = a.contenido WHERE a.id_reporte = ?";
  if ($stmt = $mysqli->prepare($query))
  {
    $stmt->bind_param('s',$id);
    if ($stmt->execute())
     $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
      while ($stmt->fetch())
      {
        $registro['id'] = $col1;
        $registro['idRow'] = $col2;
        $registro['idReporte'] = $col3;
        $registro['elemento'] = $col4;
        $registro['contenido'] = $col5;
        $registro['contenido2'] = $col6;
        $registro['contenido3'] = $col7;
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
   return $mysqli->error;
 }
 function getGrupos($id)
{
 $listado = array();
 $mysqli = $this->fullConnect($this->token);
 // $query = "SELECT id,nombre FROM jb_grupos WHERE id_usuario_creador = ?";
 $query = "SELECT DISTINCT(a.id_grupo),b.nombre  FROM jb_grupos_miembros a INNER JOIN jb_grupos b ON a.id_grupo = b.id WHERE a.id_usuario =?";
 if ($stmt = $mysqli->prepare($query))
 {
   $stmt->bind_param('i',$id);
   if ($stmt->execute())
    $stmt->bind_result($col1,$col2);
     while ($stmt->fetch())
     {
       $registro['id'] = $col1;
       $registro['nombre'] = $col2;
       $listado[] = $registro;
     }
    $stmt->free_result();
    $stmt->close();
  return $listado;
 }
 else
  return $mysqli->error;
}
function updTokenEmpresa($token)
{
$mysqli = $this->fullConnect($this->token);
if ($stmt = $mysqli->prepare("UPDATE jb_empresa_login SET token = ? WHERE id = 200000"))
{
 $stmt->bind_param('s', $token);
 if ($stmt->execute())
 {
  $stmt->free_result();
  $stmt->close();
  return true;
 }
 else
 {
   $this->mysqlError = $stmt->error;
  $stmt->close();
  return false;
 }
}
else {
  $this->mysqlError = $mysqli->error;
}

}
function updSincronizacionSenda($respuesta,$idReporte)
{
$mysqli = $this->fullConnect($this->token);
if ($stmt = $mysqli->prepare("UPDATE jb_dinamic_tables_rows SET contenido = ? WHERE id_reporte = ? AND id_column = '642479108d1f6' "))
{
 $stmt->bind_param('ss', $respuesta,$idReporte);
 if ($stmt->execute())
 {
  $stmt->free_result();
  $stmt->close();
  return true;
 }
 else
 {
   $this->mysqlError = $stmt->error;
  $stmt->close();
  return false;
 }
}
else {
  $this->mysqlError = $mysqli->error;
}
}
function getRegistrosIdr($idReporte)
 {
 $mysqli = $this->fullConnect($this->token);
 $listado = array();
  $query = 'SELECT id_row, contenido FROM jb_dinamic_tables_rows WHERE id_table = "62d19f0acf695" AND id_column = "62d19f140efd7" AND id_reporte LIKE ? ORDER BY fecha ASC';
  if ($stmt = $mysqli->prepare($query))
  {
  $stmt->bind_param("s", $idReporte);
    if ($stmt->execute())
     $stmt->bind_result($col1,$col2);
      while ($stmt->fetch())
      {
       $registro['id'] = $col1;
       $registro['idColumna'] = $col2;
       $registro['respuesta'] = $this->flexibleSingleBind('SELECT contenido FROM jb_dinamic_tables_option_row WHERE id LIKE ? ',$col2,0,0,0,'s',1,$this->token);
        $listado[] = $registro;
      }
     $stmt->free_result();
     $stmt->close();
   return $listado;
  }
  else
  return $listado;
 }
}//CIERRE DE LA CLASE
