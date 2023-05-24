<?php 
include_once('Conexion.php');
include_once('../plugins/resize/ResizeImagen.php');
include_once('General.php');
date_default_timezone_set("America/Mexico_City");

class Reportes extends General
{
	function setDataBase($bd)
	{
		$this->baseDatos = $bd;
	}
	function getDatabase()
	{
		return $this->baseDatos;
	}
	function __construct()
	{
		$construccion = 'Objeto Creado';
		$this->conexion = new Conexion();
		parent::initMetaConn();
	}
	function mgConnect()
	{
		$conn = $this->conexion;
		$mysqli = $conn->getLink();
		return $mysqli;
	} 
	function getFilteredReports($id,$id_plantilla,$nombre,$fechaInicio,$fechaFin,$id_usuario,$status,$resultado,$id_grupo,$tipoReporte,$tipeConection)
	{
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;
		$packBind = "";
		$columnsUsed = " NOT r.status = 4 ";
	if($id!=0)
	{
		$packBind.="s";  
		$arrayVars[]= $id;
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.id = ? ";
	}
$id_plantilla = 0;
	if($id_plantilla!=0)
	{
		$packBind.="s"; 
		$arrayVars[]= "%".$id_plantilla."%";
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.nombre LIKE ? ";
	}
	if($nombre!="")
	{
		$packBind.="s"; 
		$arrayVars[]= "%".$nombre."%";
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.nombre LIKE ? ";
	}
	if($id_usuario!=0 && is_numeric($id_usuario))
	{
		$packBind.="i"; 
		$arrayVars[]= $id_usuario;
		if($columnsUsed!="")
		$columnsUsed.= " AND ";
		$columnsUsed.= " r.id_usuario = ? ";
	}
	if($status!=0 && is_numeric($status))
	{
		$packBind.="i"; 
		$arrayVars[]= $status;
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.status = ? ";
	}
	if($resultado!=0)
	{
		$packBind.="s"; 
		$arrayVars[]= "%".$resultado."%";
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.resultado LIKE ? ";
	}
	if($id_grupo!=0  && is_numeric($id_grupo))
	{
		$packBind.="i";  
		$arrayVars[]= $id_grupo;
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.id_grupo = ? ";
	}
	if($tipoReporte!=0  && $tipoReporte!="")
	{
		$packBind.="s";  
		$arrayVars[]= $tipoReporte;
		if($columnsUsed!="")
			$columnsUsed.= " AND ";
		$columnsUsed.= " r.id_plantilla = ? ";
	}
	if($fechaInicio!=0 && $fechaFin!=0)
	{
	  $packBind.="ss"; 
	  $arrayVars[]= $fechaInicio.' 00:00:00';
	  $arrayVars[]= $fechaFin.' 23:59:59';
          if($columnsUsed == "")
	  $columnsUsed.= " r.fecha BETWEEN ? AND ? ";
           else
	  $columnsUsed.= " AND r.fecha BETWEEN ? AND ? ";

	}


	$gQuery = "SELECT distinct(r.id),r.nombre,r.fecha,r.resultado,g.nombre,CONCAT(e.nombres,' ',e.a_paterno) FROM jb_reporte_mantenimiento r 
LEFT JOIN jb_grupos g ON g.id = r.id_grupo 
LEFT JOIN jb_empleado e ON e.id_empleado = r.id_usuario 
WHERE ".$columnsUsed; 
/*echo $gQuery;
print_r($arrayVars);*/
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,6,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
}
function getEmpleadosWithReports($tipeConection)
{
	
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;


        $gQuery = "SELECT DISTINCT(je.ID_EMPLEADO),CONCAT(je.NOMBRES,' ',je.A_PATERNO,' ',je.A_MATERNO) nombre FROM jb_empleado je INNER JOIN jb_reporte_mantenimiento jrm ON jrm.id_usuario = je.ID_EMPLEADO WHERE je.id_empleado > ? order by je.NOMBRES desc";

        $packBind = "i";
        $arrayVars[] = 0;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,2,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getGruposWithReports($tipeConection)
{
	
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;


        $gQuery = "SELECT DISTINCT(jg.id),jg.nombre  FROM jb_grupos jg INNER JOIN jb_reporte_mantenimiento jrm ON jrm.id_grupo = jg.id WHERE jg.id > ?";

        $packBind = "i";
        $arrayVars[] = 0;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,2,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getNombresZonas($tipeConection)
{
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;
$gQuery = "SELECT DISTINCT(nombre) FROM jb_reporte_mantenimiento WHERE id > ?  ";

        $packBind = "i";
        $arrayVars[] = 0;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,1,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getBrand($tipeConection)
{
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;
$gQuery = "SELECT barra FROM jb_logos WHERE id_empresa = ?";

        $packBind = "i";
        $arrayVars[] = 3;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,1,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getNombresSucursales($tipeConection)
{
		$database = str_replace('.','_',strtolower($tipeConection));
		$database = 'jarbos_'.$database;
$gQuery = "SELECT DISTINCT(nombre) FROM jb_empresa_sucursales WHERE id > ?  ";

        $packBind = "i";
        $arrayVars[] = 0;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,1,$database))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getNombresTipoReporte($id_empresa)//here alan to metadata
{
        $gQuery = "SELECT id,nombre FROM jb_dinamic_section WHERE id_empresa = ? ";

        $packBind = "s";
        $arrayVars[] = $id_empresa;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,2,0))
	{
		return  $rs; 
	}
	else
	{
		return 0;
	}
        return $rs;
}
function getComentariosReporte($id_reporte,$tipeConection)//here alan to metadata
{
                $database = str_replace('.','_',strtolower($tipeConection));
                $database = 'jarbos_'.$database;

        $gQuery = 'select gm.id,gm.comentario,CONCAT(e.nombres," ",e.a_paterno," ",e.a_materno) from jb_grupos_mensajes gm 
        INNER JOIN jb_empleado e ON e.id_empleado= gm.id_usuario where gm.id_result = ?;';

        $packBind = "s";
        $arrayVars[] = $id_reporte;
	if($rs = parent::generalMultiConsulta($gQuery,$packBind,$arrayVars,3,$database))
	{
		return  $rs;
	}
	else
	{
		return 0;
	}
        return $rs;
}
}
?>
