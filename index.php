<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title> Desglose de reporte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="generator" content="Geany 1.23.1" />
<!--script type="text/javascript" src="js/jquery-1.7.1.min.js"></script-->
<script src="https://www.jarboss.com/Jquery/v2/jquery.min.js"></script>
<link rel="stylesheet" href="https://www.jarboss.com/Bootstrap/v3/css/bootstrap.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="https://www.jarboss.com/Plugins/Alertify/alertify.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./css/estilos.css?v=1.0.0">
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
<style>
.title{
  font-size:20px;
  text-align:center;
  margin-bottom:15px;
padding-bottom:15px;
border-bottom:1px solid #d8d8d8;
}

.content_filtroreportes{
  width:600px;
  border:1px solid #d8d8d8;
  borer-radius:5px;
  -webit-borer-radius:5px;
  -moz-borer-radius:5px;
padding:15px;
margin:0 auto;

}
.box_op{
  padding:10px;
font-size:14px;
color:#192535;
}
.name_op, .area_text{
  display:inline-block;
  vertical-align:middle;
}
.name_op{
width:120px;
text-align:right;
}
select{
  border:1px solid #d8d8d8;
  font-size:14px;
  padding:5px;
}
.box_op button{
  background-color:#337CC1;
  color:white;
  padding:10px;
  vertical-align:middle;
cursor:pointer;
border:0;
float:right;
}
.box_op a{
margin-top:10px;
cursor:pointer;
color:#B4B2B2;
float:right;
margin-right:10px;
}
.selectOp select{
width:400px;
height:auto;
}
.TableResult{margin-top:10px;}
.TableResult table{border-collapse:collapse;text-align:center;width:100%;border:1px solid #d8d8d8}
.TableResult table tr th{font-weight:bold;padding:5px;background-color:#d8d8d8;}
.TableResult table tr td{padding:8px 5px;}
.TableResult table tr td a{font-weight:bold;color:#337CC1;text-decoration:none;cursor:pointer;transition-durarion:0.4s;-moz-transition-durarion:0.4s;-webkit-transition-durarion:0.4s;}
.TableResult table tr td a:hover{color:#29649A}
.TableResult table tr td a:active{color:#133352}
</style>
</head>

<div class="container">
 <div>
    <div class="title">Opciones de filtrado</div>
  <div class="col-sm-4 form-group" style="display:none;">
     <label for="nombre">Tipo de reporte: **</label>
     <select id="jb_rps_flt_tiporeporte" class="form-control">
       <!-- <option value="62d199e0dc25d">PetCoffee</option> -->
     </select>
  </div>
  <div class="col-sm-4 form-group">
     <label for="nombre">De:</label>
     <input type="text" id="from" name="from" class="form-control" autocomplete="off">
  </div>
  <div class="col-sm-4 form-group">
     <label for="nombre">Hasta:</label>
     <input type="text" id="to" name="to" class="form-control" autocomplete="off">
  </div>
  <div class="col-sm-4 form-group">
     <label for="nombre">Grupo:</label>
     <select id="rps_flt_idg" class="form-control"><select>
  </div>
  <div class="col-sm-4 form-group">
     <label for="nombre">Sucursal:</label>
        <select id="rps_flt_nombre" class="form-control"/>            <option value="">Todos</option>        </select>
  </div>
  <div class="col-sm-4 form-group">
     <label for="nombre">Usuario:</label>
          <select id="rps_flt_id_usuario" class="form-control">            <option value="">Todos</option>          </select>
  </div>
  <div class="col-sm-4 form-group" style="display:none;">
     <label for="nombre">Dispositivo:</label>
        <select id="slcDispositivo" class="form-control"/>
            <option value="">Cualquiera</option>
            <option value="1">Android</option>
            <option value="2">Ios</option>
            <option value="3">Web</option>
        </select>
  </div>
  <div class="col-sm-4 form-group" style="display:none;">
     <label for="nombre">Estatus:</label>
        <select id="slcEstatus" class="form-control"/>
            <option value="0">Cualquiera</option>
            <option value="1">Terminado</option>
            <option value="2">En proceso</option>
            <option value="4">Deshabilitado</option>
        </select>
  </div>
  <div class="col-sm-12 form-group text-center">
    <button id="btnFiltrar" class="btn btn-info brand"> Filtrar </button>
    <button id="botonExportar" type="button" class="btn btn-success" name="button" style="background:#299D24;"> Exportar a exel </button>
  </div>
  <div class="col-sm-4 form-group" style="display:none;">
     <input type="text" id="ordenar">
     <input type="text" id="ordenartipo">
  </div>
  <div class="box_op">
      <a style="cursor:pointer;display:none;" id="jb_btn_exel"  onclick="exeExelConverter()">Convertir a Excel</a>
      <div style="float:none;clear:both;"></div>
  </div>
  <!--button onclick="exePdfConverter(1)">Convertir a Pdf 1 </button>
  <button onclick="exePdfConverter(2)">Convertir a Pdf 2</button-->
 </div>
</div>
<!-- <script charset="utf-8">
</script> -->
<body>
<div style="display:none;" id="jb_lista_ids">

</div>
<div id="jb_body_content"></div>
<div class="container">
<div class="TableResult">
<button id="left-button" class="btn btn-primary" type="button" name="button" style="padding: 5px 10px;display:none;"><i class="fa fa-arrow-left"></i> </button>
<button id="right-button" class="btn btn-primary" type="button" name="button" style="padding: 5px 10px;float:right;display:none;"><i class="fa fa-arrow-right"></i> </button>
<div id="total_reportes"></div>
<div id="element" class="table-responsive scroll-box ">
 <table id="tablaFiltro" class="table tablaFiltro table-bordered table-hover" >
   <thead>
     <tr>
       <th>Checklist</th>
       <th>Sucursal</th>
       <th >Fecha</th>
       <!-- <th>Fecha fin</th>
       <th>Hora fin</th>
       <th>Duración</th>
       <th>Resultado</th> -->
       <th>Grupo</th>
       <th>Usuario</th>
       <!-- <th>Actividad</th> -->
       <th title="Nombre del colaborador">Nombre del colaborador</th>
       <th title="Nombre del Jefe Inmediato">Nombre del Jefe Inmediato</th>
       <th title="Estafeta">Estafeta</th>
       <th title="Puesto actual">Puesto actual</th>
       <th title="¿Cómo te sientes en la empresa?">¿Cómo te sientes en la empresa?</th>
       <th title="¿Qué te gustaría que mejorara en mi liderazgo contigo?">¿Qué te gustaría que mejorara en mi liderazgo contigo?</th>
       <th title="¿Qué oportunidades crees que tienes tú?">¿Qué oportunidades crees que tienes tú?</th>
       <th title="¿A qué te Comprometes?">¿A qué te Comprometes?</th>
       <th title="¿Qué fecha estableces para cumplir los compromisos?">¿Qué fecha estableces para cumplir los compromisos?</th>
       <th title="Cuéntame, ¿Qué antigüedad tienes en la empresa?">Cuéntame, ¿Qué antigüedad tienes en la empresa?</th>
       <th title="¿Cómo se encuentra tu familia?">¿Cómo se encuentra tu familia?</th>
       <th title="¿Qué estudiaste?">¿Qué estudiaste?</th>
       <th title="¿Te gustaría seguir estudiando?">¿Te gustaría seguir estudiando?</th>
       <th title="¿Que?">¿Que?</th>
       <th title="¿En cuanto tiempo?">¿En cuanto tiempo?</th>
       <th title="¿Cuál es tu estado Civil?">¿Cuál es tu estado Civil?</th>
       <th title="¿Tienes Hijos?">¿Tienes Hijos?</th>
       <th title="¿Qué edades tienen?">¿Qué edades tienen?</th>
       <th title="¿Alguien depende económicamente de ti?">¿Alguien depende económicamente de ti?</th>
       <th title="¿Quién?">¿Quién?</th>
       <th title="¿A qué tiempo vives de la sucursal?">¿A qué tiempo vives de la sucursal?</th>
       <th title="¿Hablas Ingles?">¿Hablas Ingles?</th>
       <th title="¿En qué Nivel?">¿En qué Nivel?</th>
       <th title="¿Tienes disponibilidad para cambiar de residencia?">¿Tienes disponibilidad para cambiar de residencia?</th>
       <th title="¿En cuánto tiempo?">¿En cuánto tiempo?</th>
       <th title="¿Dónde te gustaría vivir?">¿Dónde te gustaría vivir?</th>
       <th title="¿Me Puedes proporcionar tu teléfono y algún contacto para emergencias?">¿Me Puedes proporcionar tu teléfono y algún contacto para emergencias?</th>
       <th title="¿Me puedes compartir tu correo electrónico?">¿Me puedes compartir tu correo electrónico?</th>
       <th title="¿En qué puesto te gustaría desarrollarte en la empresa?">¿En qué puesto te gustaría desarrollarte en la empresa?</th>
       <th title="Otro">Otro</th>
       <th title="¿Te gustaría certificarte en alguna área?">¿Te gustaría certificarte en alguna área?</th>
       <th title="¿Con qué certificaciones cuentas?">¿Con qué certificaciones cuentas?</th>
       <th title="¿Qué necesitas para realizar de manera más eficiente tu trabajo?">¿Qué necesitas para realizar de manera más eficiente tu trabajo?</th>
       <th title="¿Qué podría mejorar en la organización?">¿Qué podría mejorar en la organización?</th>
       <th title="¿Cuál consideras que es el mayor obstáculo para que el trabajo no se ejecute correctamente?">¿Cuál consideras que es el mayor obstáculo para que el trabajo no se ejecute correctamente?</th>
       <th title="¿Qué sugerencia o comentario tiene el colaborador?">¿Qué sugerencia o comentario tiene el colaborador?</th>
       <th title="¿El colaborador se apega a las políticas de la organización? (conoce y cumple los procedimientos y el código de ética)">¿El colaborador se apega a las políticas de la organización? (conoce y cumple los procedimientos y el código de ética)</th>
       <th title="¿El colaborador cuenta con proactividad, iniciativa, disposición y disponibilidad en sus labores diarias?">¿El colaborador cuenta con proactividad, iniciativa, disposición y disponibilidad en sus labores diarias?</th>
       <th title="¿El colaborador cuenta con una comunicación efectiva, es asertivo en sus comentarios y escucha de manera adecuada a sus compañeros y jefe inmediato?">¿El colaborador cuenta con una comunicación efectiva, es asertivo en sus comentarios y escucha de manera adecuada a sus compañeros y jefe inmediato?</th>
       <th title="¿El colaborador se acerca, detecta y entiende las necesidades del cliente? (NPS, Guest+)">¿El colaborador se acerca, detecta y entiende las necesidades del cliente? (NPS, Guest+)</th>
       <th title="¿Es una persona que se exige mucho, busca sobrepasar los objetivos de la operación y colabora con su equipo de trabajo?">¿Es una persona que se exige mucho, busca sobrepasar los objetivos de la operación y colabora con su equipo de trabajo?</th>
       <th title="¿El colaborador trabaja con orden, realizar sus actividades de manera correcta, cumpliendo con todos los procedimientos y estándares de calidad?">¿El colaborador trabaja con orden, realizar sus actividades de manera correcta, cumpliendo con todos los procedimientos y estándares de calidad?</th>
       <th title="¿Cómo evalúas el desempeño del colaborador?">¿Cómo evalúas el desempeño del colaborador?</th>
     </tr>
   </thead>
   <tbody id="resultados">
   </tbody>
 </table>
</div>
</div>
</div>
<div class="container">
 <table class="table">
  <thead>
   <tr>
    <td>© Software Jarboss. Todos los derechos reservados.</td><td id="version">3.0.0</td>
   </tr>
  </thead>
 </table>
</div>

<!-- modal de vision -->
<div class="modal" id="VisionModal">
      <div class="modal-dialog">
        <div class="modal-content bordeModal">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-md-4">
                  <h4 class="modal-title"><strong>Enviar correo de:</strong></h4>
                  <br>
                  <!-- <form class=""> -->
                    <div class="form-group">
                      <label for="usuario">Ciente / Contrato</label>
                      <!-- <input class="form-control" type="text" name="" value="" placeholder="Ingresa tu nombre"> -->
                    </div>

                    <div class="form-group">
                      <label for="password">Centro de Trabajo</label>
                      <!-- <input class="form-control" type="password" name="" value="" placeholder="Ingresa tu contraseña"> -->
                    </div>
                    <div class="form-group">
                      <label for="usuario">Usuarios</label>
                      <!-- <input class="form-control" type="text" name="" value="" placeholder="Ingresa tu nombre"> -->
                    </div>

                    <div class="form-group">
                      <label for="password">Fecha</label>
                      <!-- <input class="form-control" type="password" name="" value="" placeholder="Ingresa tu contraseña"> -->
                    </div>
                    <div class="form-group">
                      <label for="usuario">Tipo de Reporte</label>
                      <!-- <input class="form-control" type="text" name="" value="" placeholder="Ingresa tu nombre"> -->
                    </div>

                    <div class="form-group">
                      <label for="password">Jefe de Operaciones</label>
                      <!-- <input class="form-control" type="password" name="" value="" placeholder="Ingresa tu contraseña"> -->
                    </div>
                    <div class="form-group">
                      <label for="usuario">Facilitador</label>
                      <!-- <input class="form-control" type="text" name="" value="" placeholder="Ingresa tu nombre"> -->
                    </div>

                    <div class="form-group">
                      <label for="password">Reclutador</label>
                      <!-- <input class="form-control" type="password" name="" value="" placeholder="Ingresa tu contraseña"> -->
                    </div>
                  <!-- </form> -->
                </div>
                <div class="col-md-3">
                  <h4 class="modal-title"><strong>Enviar correo a:</strong></h4>
                  <div class="form-group">
                    <!-- <label for="password">Reclutador</label> -->
                    <input class="form-control" type="email" name="" value="" placeholder="Ingrese correo electronico">
                  </div>
                </div>

              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-primary VisionModal" data-dismiss="modal">Enviar Correo</button>
          </div>
        </div>
      </div>
    </div>
</body>
 <script src="https://www.jarboss.com/Bootstrap/v3/js/bootstrap.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://www.jarboss.com/Plugins/Alertify/alertify.min.js"></script>
 <script src="https://www.jarboss.com/js/Extensiones.js"></script>
 <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
 <script src="./js/Filtros.js"></script>
</html>
