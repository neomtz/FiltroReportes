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

$( document ).ready(function()
{
   getUsuarioChecklist();
});

function getUsuarioChecklist()
{
  $.ajax(
  {
      type: "POST",
      dataType: "JSON",
      url: "https://www.jarboss.com/Asignaciones/GET/?source1=ticket&source2=checklists",
      data: { 'source': sessionStorage.getItem("source"), 'source1': sessionStorage.getItem("idUsuario") },
      beforeSend: function(event)
      {
      },
      success: function(server)
      {
        if(server.length > 0)
        {
          var ids = []
          $.each(server,function(a,b)
          {
            if(b.tipo == 0)
              $("#jb_rps_flt_tiporeporte").append('<option value="'+b.id+'">'+b.nombre+'</option>')
              ids.push(b.id);
          });
          $("#jb_rps_flt_tiporeporte").prepend('<option value="'+ids.join(",")+'" selected>Todos</option>')
          if(sessionStorage.getItem("filtrar") == 1)
          {
            $("#ordenar").val(sessionStorage.getItem("filtroOrdenar"))
            $("#ordenartipo").val(sessionStorage.getItem("filtroOrdenarTipo"))
            $("#from").val(sessionStorage.getItem("filtroFechai"))
            $("#to").val(sessionStorage.getItem("filtroFechaf"))
            $("#jb_rps_flt_tiporeporte option[value="+ sessionStorage.getItem("filtroChecklist") +"]").attr("selected",true);
          }
          getValidateId();

        }
      },
      timeout: 10000,
      error: function(e)
       {
         $.LoadingOverlay("hide");
         if(e.statusText != 'timeout')
            alertify.error("Ocurrió en el servidor '> "+e.statusText);
          else
          {
            alertify.confirm('La petición tardo demasiado ¿deseas volver a intentarlo?', function()
            {
              getValidateId();
            },
            function()
            {
              alertify.warning('Intentalo más tarde.')
            });
          }
       }
   });
}
