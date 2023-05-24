// GrupoSenda
sessionStorage.setItem("nombreEmpresa", "Petco.com.mx")
sessionStorage.setItem("source", "87ced100f91dbfb34afa295e39e8cebb802cebc2464f5a4eaf5fbd479ca19ce2ede7be20")
sessionStorage.setItem("idUsuario", "16310000")
$( document ).ready(function() {
  $( function() {
      var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1,
          dateFormat: "yy-mm-dd"
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
	      dateFormat: "yy-mm-dd"
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });

    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    }
  } );
  ///////////
  $("#filtros").css("font-weight","bold")
  $("#filtros").find("i").css("color","white")
  $("#filtros").find("a").css("color","white")
  if($_get("v") == null)
  $("#indicadores").find("a").attr('href','./Indicadores/?source='+$_get("source"))
  else
   $(".navbar-inverse").remove();
   ////////////
  getInitConf()
});
$("#btnFiltrar").click(function(event) {
  getReportesFiltrados()
});
$("#jb_rps_flt_tiporeporte").change(function(event) {
  getGruposChecklist(0)
});
$("#rps_flt_idg").change(function(event) {
  getUsuariosGrupos(0)
});
function getInitConf()
{
  $.LoadingOverlay("show",
  {
    image       : "",
    fontawesome : "fa fa-spinner fa-pulse fa-1x fa-fw",
    text        :  "Cargando configuraciones ... "
  });
 $.ajax({
   type: "POST",
   dataType: "JSON",
   // url: "https://jarboss.com/API/GET/?source1=checklist&source2=getsec",
   url: "./GET/?source1=indicadores&source2=getChecklists",
   data: {"source":sessionStorage.getItem("source"), "source1":sessionStorage.getItem("idUsuario")},
   success: function(server)
   {
     if(server.length > 0)
     {
       var ids = [];
       $.each(server,function(a,b)
       {
         ids.push(b.id);
         $("#jb_rps_flt_tiporeporte").append('<option value="'+b.id+'">'+b.nombre+'</option>')
       });
     }
     $("#jb_rps_flt_tiporeporte").prepend('<option value="'+ids.join(",")+'" selected>Todas las auditorias</option>')
     // $.LoadingOverlay("hide");
     getGruposChecklist(1)
   }
 }); //ajax
}
function getGruposChecklist(opt)
{
  $("#rps_flt_idg").empty();
 $.ajax({
   type: "POST",
   dataType: "JSON",
   url: "https://www.jarboss.com/GruposI/GET/?source1=grupos&source2=gruposchecklist",
   // url: "./GET/?source1=indicadores&source2=mostrarGrupos",
   data: {"token":sessionStorage.getItem("source"), "source1": $("#jb_rps_flt_tiporeporte").val(),"source2":sessionStorage.getItem("idUsuario")},
   success: function(server)
   {
     if(server.length > 0)
     {
       var ids = [];
       server.sort(ordenarNombres); // Ordena en orden alfabetico el arreglo por el nombre del grupo
       $.each(server,function(a,b)
       {
         $("#rps_flt_idg").append('<option value="'+b.id+'">'+b.nombre+'</option>')
         ids.push(b.id);
       });
       $("#rps_flt_idg").prepend('<option value="'+ids.join(",")+'" selected>Todos los grupos</option>')
     }else {
       $("#rps_flt_idg").append('<option value="">Sin grupos</option>')
     }
     if(opt == 1)
     getUsuariosGrupos(opt)
   }
 }); //ajax
}
function getUsuariosGrupos(tipo)
{
  $("#rps_flt_id_usuario").empty();
 $.ajax({
   type: "POST",
   dataType: "JSON",
   url: "https://www.jarboss.com/GruposI/GET/?source1=grupos&source2=mostrarMiembros",
   data: {"token":sessionStorage.getItem("source"), "source": $("#rps_flt_idg").val()},
   success: function(server)
   {
     if(server.length > 0)
     {
       var ids = [];
       $.each(server,function(a,b)
       {
         if($("#"+b.id_usuario+"").length == 0)
         {
           $("#rps_flt_id_usuario").append('<option value="'+b.id_usuario+'" id="'+b.id_usuario+'">'+b.usuario+'</option>')
           ids.push(b.id_usuario);
         }
       });
       $("#rps_flt_id_usuario").prepend('<option value="'+ids.join(",")+'" selected>Todos los usuarios</option>')
     }
     if(tipo == 1)
     {
       $.LoadingOverlay("hide");
       getNombresSucursales()
     }
   }
 }); //ajax
}
function getNombresSucursales()
{
  var data = "source="+sessionStorage.getItem("source")+"&opt=5";
  $.ajax({
       datatype: "JSON",
       type: "POST",
       url: "GET/procesarReportes.php",
       data: data,
       success: function(server)
      {
        var content = "";
        //getGrupos();
        $.each(server,function(i,t){
          let sucursal = t.col1.replace('-',' ');
          content = '<option value="'+sucursal.trim()+'">'+t.col1+'</option>';
          $("#rps_flt_nombre").append(content);
        });
        //getTiposReportes();
      },
      complete: function()
      {
      },
      error:   function()
      {
      }
  });
}
var noFactura = []
function getReportesFiltrados()
{
 noFactura = []
  $.LoadingOverlay("show",
  {
    image	: "",
    fontawesome : "fa fa-spinner fa-pulse fa-1x fa-fw",
    text        :  "Cargando.. "
  });
  $("#jb_lista_ids").empty();
  $("#jb_body_content").empty();
  var id= $("#rps_flt_id").val();
  var id_plantilla= $("#rps_flt_idplan").val();
  var nombre= $("#rps_flt_nombre").val();
  // var tipoReporte = $("#jb_rps_flt_tiporeporte").val();
  var tipoReporte = '62d199e0dc25d';
  var fIniDi = $("#rps_flt_finiDi").val();
  var fIniMe = $("#rps_flt_finiMe").val();
  var fIniAn = $("#rps_flt_finiAn").val();

  var fFinDi = $("#rps_flt_ffinDi").val();
  var fFinMe = $("#rps_flt_ffinMe").val();
  var fFinAn = $("#rps_flt_ffinAn").val();
  /*
    var fFinDi = 30;
    var fFinMe = 12;
    var fFinAn = 2030;
  */
  if(fFinAn == "2014")
  fIniAn  = 0;
  var fechaInicio = $("#from").val();
  // var fechaInicio = "2021-10-27";
  var fechaFin = $("#to").val() ;
  // var fechaFin = "2022-01-24";
  if(fechaInicio == '' || fechaFin == '')
  {
   alertify.warning("Elige rango de fechas");
   $.LoadingOverlay("hide");
   return false;
  }
  var id_usuario= $("#rps_flt_id_usuario").val();
  var status= $("#slcEstatus").val();
  //var resultado= $("#rps_flt_resultado").val();
  var resultado = "";
  var id_grupo = $("#rps_flt_idg").val();
  /*if( nombre== "" && id_usuario == "")
  {
  alert("Debes llenar más campos.");
  return false;
  }*/
  var data = "source="+sessionStorage.getItem("source")+"&opt=1&id="+id+"&id_plantilla="+id_plantilla+"&nombre="+nombre+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&id_usuario="+id_usuario+"&status="+status+"&resultado="+resultado+"&id_grupo="+id_grupo+"&tipoReporte="+tipoReporte+"&ordenar="+$("#ordenar").val()+"&ordenartipo="+$("#ordenartipo").val()+"&dispositivo="+$("#slcDispositivo").val();
  $.ajax({
       datatype: "JSON",
       type: "POST",
       url: "GET/procesarReportes.php",
       data: data,
       success: function(server){
         $.LoadingOverlay("hide");
         $("#total_reportes").text('')
         $(".reportesEncontrados").remove();
         if(server== 0)
         alertify.warning("No hay reportes con estas especificaciones. ");
         $("#total_reportes").text("Se encontraron "+server.length+" resultados");

         $.each(server,function(i,t){
           //getServerReporte(t.col1,count );
           $("#jb_lista_ids").append(t.col1+',');
           var dt = t.col3.split(" ");
           // var df = t.col8.split(" ");
           const fechaInicio = new Date(t.col3);
           const hora_inicio = {
              hora: fechaInicio.getHours(),
              minutos: fechaInicio.getMinutes(),
              segundos: fechaInicio.getSeconds()
              };
              const fechaFin = new Date(t.col8);
              const hora_final = {
               hora: fechaFin.getHours(),
               minutos: fechaFin.getMinutes(),
               segundos: fechaFin.getSeconds()
               };
        function prefijo(num) {
         return num < 10 ? ("0" + num) : num;
        }
        var horaInicio = prefijo(hora_inicio.hora);
        var minutosInicio = prefijo(hora_inicio.minutos);
        var horaFinal = prefijo(hora_final.hora);
        var minutosFinal = prefijo(hora_final.minutos);

        inicio = ''+horaInicio+':'+minutosInicio+'';
        fin = ''+horaFinal+':'+minutosFinal+'';

        inicioMinutos = parseInt(inicio.substr(3,2));
        inicioHoras = parseInt(inicio.substr(0,2));

        finMinutos = parseInt(fin.substr(3,2));
        finHoras = parseInt(fin.substr(0,2));

        transcurridoMinutos = finMinutos - inicioMinutos;
        transcurridoHoras = finHoras - inicioHoras;

        if (transcurridoMinutos < 0) {
          transcurridoHoras--;
          transcurridoMinutos = 60 + transcurridoMinutos;
        }
        horas = transcurridoHoras.toString();
        minutos = transcurridoMinutos.toString();
        if (isNaN(horas)) {
          horas = 0
        }
        if (isNaN(minutos)) {
          minutos = 0
        }
        if (minutos < 10) {
          minutos = "0"+minutos;
        }
        if (minutos != 00 ) {
          resultado = minutos+' Minutos'
        }
        if (horas > 0) {
          resultado = horas+' Horas con '+minutos+' minutos'
        }
        var hora_fin = t.col8
        horaI = ''
        horaF = ''
        if (hora_fin !=null){
          hora_fin=hora_fin.split(" ")
          horaI = hora_fin[0]
          horaF = hora_fin[1]
        }
        var nombreSucursal = t.col2;
        nombreSucursal = (nombreSucursal == null)? '': nombreSucursal;
        var colorTd = '';
        var colorLink = '';
        var certificarte = t.col39;
        var certificaciones = t.col40;
        var eficiente = t.col41;
        certificarte = verificarOpcionMultiple(certificarte);
        certificaciones = verificarOpcionMultiple(certificaciones);
        eficiente = verificarOpcionMultiple(eficiente);
        t.col42 = verificarOpcionMultiple(t.col42);
        t.col43 = verificarOpcionMultiple(t.col43);
        t.col9 = (t.col9 == null || t.col9 == '')? 'N/A': t.col9;
        t.col10 = (t.col10 == null || t.col10 == '')? 'N/A': t.col10;
        t.col11 = (t.col11 == null || t.col11 == '')? 'N/A': t.col11;
        t.col12 = (t.col12 == null || t.col12 == '')? 'N/A': t.col12;
        t.col13 = (t.col13 == null || t.col13 == '')? 'N/A': t.col13;
        t.col14 = (t.col14 == null || t.col14 == '')? 'N/A': t.col14;
        t.col15 = (t.col15 == null || t.col15 == '')? 'N/A': t.col15;
        t.col16 = (t.col16 == null || t.col16 == '')? 'N/A': t.col16;
        t.col17 = (t.col17 == null || t.col17 == '')? 'N/A': t.col17;
        t.col18 = (t.col18 == null || t.col18 == '')? 'N/A': t.col18;
        t.col19 = (t.col19 == null || t.col19 == '')? 'N/A': t.col19;
        t.col20 = (t.col20 == null || t.col20 == '')? 'N/A': t.col20;
        t.col21 = (t.col21 == null || t.col21 == '')? 'N/A': t.col21;
        t.col22 = (t.col22 == null || t.col22 == '')? 'N/A': t.col22;
        t.col23 = (t.col23 == null || t.col23 == '')? 'N/A': t.col23;
        t.col24 = (t.col24 == null || t.col24 == '')? 'N/A': t.col24;
        t.col25 = (t.col25 == null || t.col25 == '')? 'N/A': t.col25;
        t.col26 = (t.col26 == null || t.col26 == '')? 'N/A': t.col26;
        t.col27 = (t.col27 == null || t.col27 == '')? 'N/A': t.col27;
        t.col28 = (t.col28 == null || t.col28 == '')? 'N/A': t.col28;
        t.col29 = (t.col29 == null || t.col29 == '')? 'N/A': t.col29;
        t.col30 = (t.col30 == null || t.col30 == '')? 'N/A': t.col30;
        t.col31 = (t.col31 == null || t.col31 == '')? 'N/A': t.col31;
        t.col32 = (t.col32 == null || t.col32 == '')? 'N/A': t.col32;
        t.col33 = (t.col33 == null || t.col33 == '')? 'N/A': t.col33;
        t.col34 = (t.col34 == null || t.col34 == '')? 'N/A': t.col34;
        t.col35 = (t.col35 == null || t.col35 == '')? 'N/A': t.col35;
        t.col36 = (t.col36 == null || t.col36 == '')? 'N/A': t.col36;
        t.col37 = (t.col37 == null || t.col37 == '')? 'N/A': t.col37;
        t.col38 = (t.col38 == null || t.col38 == '')? 'N/A': t.col38;
        certificarte = (certificarte == null || certificarte == '')? 'N/A': certificarte;
        certificaciones = (certificaciones == null || certificaciones == '')? 'N/A': certificaciones;
        eficiente = (eficiente == null || eficiente == '')? 'N/A': eficiente;
        t.col42 = (t.col42 == null || t.col42 == '')? 'N/A': t.col42;
        t.col43 = (t.col43 == null || t.col43 == '')? 'N/A': t.col43;
        t.col44 = (t.col44 == null || t.col44 == '')? 'N/A': t.col44;
        var actividad = ''
        // actividad = '<td class="col11" style="'+colorTd+'"><a class="col11Text" href="https://www.jarboss.com/'+sessionStorage.getItem("nombreEmpresa")+'/reporteTemporal.php?source='+t.col1+'" target="_blank" style="'+colorLink+'">Ir al reporte</a><a href="https://www.jarboss.com/'+sessionStorage.getItem("nombreEmpresa")+'/reporteTemporal.php?source='+t.col1+'" target="_blank" style="display:none;">https://www.jarboss.com/'+sessionStorage.getItem("nombreEmpresa")+'/reporteTemporal.php?source='+t.col1+'</a></th>'

        if($("#"+t.col1+"").length == 0){
          $("#resultados").append('<tr class="reportesEncontrados" id="'+t.col1+'"><td class="col1'+t.col10+'">'+$("#jb_rps_flt_tiporeporte option[value='"+t.col7+"']").text()+'</td><td title="'+nombreSucursal+'"> '+nombreSucursal+' </th><td> '+dt[0]+' </td><td> '+t.col5+' </td><td title="'+t.col6+'"> '+t.col6+' </td>'+actividad+'<td title="'+t.col9+'">'+t.col9+'</td><td title="'+t.col10+'">'+t.col10+'</td><td title="'+t.col11+'">'+t.col11+'</td><td title="'+t.col12+'">'+t.col12+'</td><td title="'+t.col13+'">'+t.col13+'</td><td title="'+t.col14+'">'+t.col14+'</td><td title="'+t.col15+'">'+t.col15+'</td><td title="'+t.col16+'">'+t.col16+'</td><td title="'+t.col17+'">'+t.col17+'</td><td title="'+t.col18+'">'+t.col18+'</td><td title="'+t.col19+'">'+t.col19+'</td><td title="'+t.col20+'">'+t.col20+'</td><td title="'+t.col21+'">'+t.col21+'</td><td title="'+t.col22+'">'+t.col22+'</td><td title="'+t.col23+'">'+t.col23+'</td><td title="'+t.col24+'">'+t.col24+'</td><td title="'+t.col25+'">'+t.col25+'</td><td title="'+t.col26+'">'+t.col26+'</td><td title="'+t.col27+'">'+t.col27+'</td><td title="'+t.col28+'">'+t.col28+'</td><td title="'+t.col29+'">'+t.col29+'</td><td title="'+t.col30+'">'+t.col30+'</td><td title="'+t.col31+'">'+t.col31+'</td><td title="'+t.col32+'">'+t.col32+'</td><td title="'+t.col33+'">'+t.col33+'</td><td title="'+t.col34+'">'+t.col34+'</td><td title="'+t.col35+'">'+t.col35+'</td><td title="'+t.col36+'">'+t.col36+'</td><td title="'+t.col37+'">'+t.col37+'</td><td title="'+t.col38+'">'+t.col38+'</td><td title="'+certificarte+'">'+certificarte+'</td><td title="'+certificaciones+'">'+certificaciones+'</td><td title="'+eficiente+'">'+eficiente+'</td><td title="'+t.col42+'">'+t.col42+'</td><td title="'+t.col43+'">'+t.col43+'</td><td title="'+t.col44+'" class="col44'+t.col1+'">'+t.col44+'</td></tr>');
          $.each(t.datos.reverse(), function(a, b) {
            $("#"+t.col1+"").find('td.col44'+t.col1+'').after('<td title="'+b.respuesta+'" id="'+b.id+'">'+b.respuesta+'</td>')
          });
        }
        });

        sessionStorage.setItem("filtrar",0);
        sessionStorage.setItem("filtroOrdenar","");
        sessionStorage.setItem("filtroOrdenarTipo","");
        sessionStorage.setItem("filtroFechai","");
        sessionStorage.setItem("filtroFechaf","");
        sessionStorage.setItem("filtroChecklist","");
    },
    complete: function()
    {
	    $("#jb_btn_exel").attr("onclick","exeExelConverter()");
		  $("#jb_btn_exel").text('Convertir Excel');
		  $("#jb_btn_exel").removeAttr("href");
    },
     error:   function()
    {
    }
  });
}
(function($) {
    $.get = function(key)   {
        key = key.replace(/[\[]/, '\\[');
        key = key.replace(/[\]]/, '\\]');
        var pattern = "[\\?&]" + key + "=([^&#]*)";
        var regex = new RegExp(pattern);
        var url = unescape(window.location.href);
        var results = regex.exec(url);
        if (results === null) {
            return null;
        } else {
            return results[1];
        }
    }
})(jQuery);
// Este código contiene una función llamada ExportToExcel que se encarga de exportar una tabla HTML con id "tablaFiltro" a un archivo Excel. Ademas se esta utilizando una promesa para poder indicar cuando la exportación se ha terminado y se esta cambiando el texto del elemento con clase "col11Text" a "Ir al reporte" una vez que se ha exportado correctamente.
$("#botonExportar").click(function(event) {
  function ExportToExcel(type, fn, dl) {
     $(".reportesEncontrados").find('.col11Text').text('')
    var elt = document.getElementById('tablaFiltro');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return new Promise((resolve, reject) => {
      if(dl) {
        resolve(XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' })); //XLSX.write: escribe el libro de Excel a una cadena de base64.
      } else {
        resolve(XLSX.writeFile(wb, fn || ('tabla-filtro.' + (type || 'xlsx')))); //XLSX.writeFile: escribe el libro de Excel a un archivo en el disco.
      }
    });
  }
  ExportToExcel()
    .then(() => {
      $(".reportesEncontrados").find('.col11Text').text('Ir al reporte');
    });
    // Aqui se esta esperando a que se ejecute completamente para cambiar el texto del elemento mencionado
});
// Seleccionamos el elemento que queremos hacer scrolleable
var element = $("#element");
// Configuramos el evento click en un botón para desplazarnos hacia la derecha
$("#right-button").click(function() {
  // Obtenemos la posición actual del scroll
  var currentScroll = element.scrollLeft();
  // Desplazamos el scroll hacia la derecha en 100 píxeles
  element.scrollLeft(currentScroll + 100);
});
// Configuramos el evento click en un botón para desplazarnos hacia la izquierda
$("#left-button").click(function() {
  // Obtenemos la posición actual del scroll
  var currentScroll = element.scrollLeft();
  // Desplazamos el scroll hacia la izquierda en 100 píxeles
  element.scrollLeft(currentScroll - 100);
});
function ordenarNombres(a, b) {// Funcion para ordenar un arreglo de objetos en orden alfabético
  if (a.nombre < b.nombre) {
    return -1;
  } else if (a.nombre > b.nombre) {
    return 1;
  } else {
    return 0;
  }
}
$(window).scroll(function() {
  if (sessionStorage.getItem("nombreEmpresa") == "Jarboss.com") {

  }else {
    return 0;
  }
  const scrollTop = $(window).scrollTop();
  if (scrollTop > 500) {
    $("#left-button,#right-button").show()
    $("#left-button,#right-button").css('top', '3%');

  }else {
    $("#left-button,#right-button").hide()
  }
});
function iterarOpcionMultiple(server)
{
 server = JSON.parse(server);
 var respuesta = '';
 var turno = 1;
  $.each(server,function(i,t)
  {
   var checked = '';
    if(t.is_checked == 1)
    {
    respuesta += turno+'.- '+t.name+' ';
        turno ++;
    }
  });
 return respuesta;
}
function verificarOpcionMultiple(variable) {
  if(variable && variable.indexOf('[{') > -1 && variable.indexOf('}]') > -1) {
    return iterarOpcionMultiple(variable);
  }
  return variable;
}
