/**
 * Created by caesarius on 04/04/2017.
 */
var numeroliquidacion = [];
var nombreliquidacion = [];
var ajaxAbierto = false;
var empleado1=0;


$(document).ready(function() {
    numeroliquidacion["liquidaprimeraquincena"] = 1;
    numeroliquidacion["liquidasegundaquincena"] = 2;
    numeroliquidacion["liquidamensual"] = 3;
    numeroliquidacion["liquidapresupuestoprimera"] = 4;
    numeroliquidacion["liquidapresupuestosegunda"] = 5;
    numeroliquidacion["liquidapresupuestomensual"] = 6;
    numeroliquidacion["liquidasac"] = 7;
    
    nombreliquidacion[1]="liquidaprimeraquincena" ;
    nombreliquidacion[2]="liquidasegundaquincena" ;
    nombreliquidacion[3]="liquidamensual";
    nombreliquidacion[4]="liquidapresupuestoprimera" ;
    nombreliquidacion[5]="liquidapresupuestosegunda" ;
    nombreliquidacion[6]="liquidapresupuestomensual" ;
    nombreliquidacion[7]="liquidasac" ;
    
    jQuery(document).ready(function($) {
        $(document).ajaxStart(function () {
            ajaxAbierto = true;
        });
        $(document).ajaxComplete(function () {
            checkPendingRequest();
        });
        $('#ui-datepicker-div').hide();
        function checkPendingRequest() {
            if ($.active > 0) {
                window.setTimeout(checkPendingRequest, 1000);
                //Mostrar peticiones pendientes ejemplo: $("#control").val("Peticiones pendientes" + $.active);
            }
            else {
                ajaxAbierto = false;
            }
        };
    });
    //$("#tipoliquidacion").val('0');       
    $('.chosen-select').chosen({search_contains:true});
    $("#conceptos").on('change',function(){
        var options = $(this).val();
        $(options).each(function(i,o){
            if($.inArray(o,seleccionados)==-1){
                //nuevo seleccionado
                //alert(o);
                seleccionados.push(o);
                var conNom = $('#conceptos option[value="' + o + '"]').html();
                //loadNewTR(conNom,o);
                loadNewTD(conNom,o);
            }
        });
        $(seleccionados).each(function(i,s){
            if($.inArray(s,options)==-1){
                //nuevo DesSeleccionado
                //alert(s);
                seleccionados.remove(s);
                unloadTR(s);
            }
        });
    });
    if($("#tipoliquidacionAguardar").length>0){
        $("#tipoliquidacion").val(nombreliquidacion[$("#tipoliquidacionAguardar").val()]);
        $("#tipoliquidacion").trigger('change');
    }
    
    $("#ValorreciboGuardardatosmasivosForm").submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){                
                callAlertPopint("Datos Guardados.");
                
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });
    empleadosArray = JSON.parse($("#empleadosJson").val());   
});
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

var seleccionados = [];
var numeroVR = 0;
function cargarTodosLosSueldos(convenio, pagina){
    var liquidacion = $("#tipoliquidacion").val();
    var cliid = $("#cliid").val();
    var periodo = $("#periododefault").val();
    $("#divSueldoForm").html("");
    $("#divSueldoForm").css('width','5000px');
    //$("#divSueldoForm").css('width','100%');
    var url = serverLayoutURL+"/empleados/liquidacionmasiva/"+cliid+"/"+periodo+"/"+convenio+"/"+numeroliquidacion[liquidacion];
    document.location = url;
}
function loadNewTD(nombreConcepto,cctxconceptoID){
    var empleados = JSON.parse($("#empleadosALiquidar").val());   
    var periodo = $("#periododefault").val();
    var tipoliquidacion = $("#tipoliquidacionAguardar").val();
    
    //a la primer fila le vamos a agregar el nombre del concepto
    
    var VRtr = $("#tblLiquidacionMasiva tr:first")
            .append(
                $('<td>')
                    .html(nombreConcepto)
                    .addClass('tdCctxc'+cctxconceptoID)
                );
    $("#tblLiquidacionMasiva tr").each(function(){
        var header = $(this).attr('header');
        if(header==1){
            return true;
        }
        var empid = $(this).attr('empid');
        var index = $(this).index();
        $(this).append(loadValorRecibo(index,empid,cctxconceptoID,periodo,tipoliquidacion));
    });
    //despues para cada fila vamos a agregar un td
    /*$(empleados).each(function(i,e){
        VRtr.append(loadValorRecibo(e,cctxconceptoID,periodo,tipoliquidacion));
    });
    $("#tblLiquidacionMasiva").append(VRtr);
    */
}
function unloadTR(cctxcid){
   $(".tdCctxc"+cctxcid).each(function(){
               $(this).remove();
    });   
}
var empleadosArray;
function getValorGuardado(empid,cctxconceptoID){
    var valorGuardado = 0;
    $(empleadosArray).each(function(i,e){
        i;
        if(e.Empleado.id==empid){
            $(e.Valorrecibo).each(function(j,d){
               if(d.cctxconcepto_id==cctxconceptoID){
                   //encontre el cctxconceptoID
                   valorGuardado =  d.valor;
               }
            });
        }
    });
    return valorGuardado * 1;
}

function loadValorRecibo(index,empid,cctxconceptoID,periodo,tipoliquidacion){
    var valorReciboGuardado = getValorGuardado(empid,cctxconceptoID,periodo,tipoliquidacion);
    var VRtd = $('<td>')
                .css('width','30px')
                .css('height','30px')
                .addClass('tdvalor')
                .addClass('tdCctxc'+cctxconceptoID)
                .append(
                    $('<input>')
                        .attr('type','hidden')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][id]')
                        .attr('id','Valorrecibo'+numeroVR+'Id')
                )
                .append(
                    $('<input>')
                        .attr('type','hidden')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][periodo]')
                        .attr('id','Valorrecibo'+numeroVR+'Periodo')
                        .val(periodo)
                )
                .append(
                    $('<input>')
                        .attr('type','hidden')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][tipoliquidacion]')
                        .attr('id','Valorrecibo'+numeroVR+'Tipoliquidacion')
                        .val(tipoliquidacion)
                )
                .append(
                    $('<input>')
                        .attr('type','hidden')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][cctxconcepto_id]')
                        .attr('id','Valorrecibo'+numeroVR+'CctxconceptoId')
                        .val(cctxconceptoID)
                )
                .append(
                    $('<input>')
                        .attr('type','hidden')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][empleado_id]')
                        .attr('id','Valorrecibo'+numeroVR+'EmpleadoId')
                        .val(empid)
                )
                .append(
                    $('<input>')
                        .attr('type','text')
                        .css('width','100%')
                        .attr('name','data[Valorrecibo]['+numeroVR+'][valor]')
                        .attr('id','Valorrecibo'+numeroVR+'Valor')
                        .val(valorReciboGuardado)
                        .addClass('inputValue')
                        .addClass('inputCctxc'+cctxconceptoID)
                );
        if(index==1){
            var span = $('<span />')
                .attr('class', 'tooltiptext') 
                //.attr('style', 'padding: 0px')                         
                .html(
                    $('<input />',{
                        'type':'button',
                        'value':"Aplicar a todos",
                        'onclick':"aplicarATodos('"+cctxconceptoID+"')"
                    })
                );
            VRtd.addClass( "tooltip" )
                .append(
                    span
                )
                .css('display','table-cell');
        }
    numeroVR++;
    return VRtd;
}
function cargarPaginasPorConvenio (oObj)
{   
    var sLisquidacionSeleccionada = oObj.value;
    $("#divSueldoForm").html('');
    if (sLisquidacionSeleccionada == '0')
    {
        $("div[id^='divPaginasConvenio_']").each(function() {
            $(this).html('');
        });        
        return;
    }

    var sConvenioEmpleadoLiquidacion = $("#hdnConvEmp_"+sLisquidacionSeleccionada).val();    
    var aConvenioEmpleadoLiquidacion = sConvenioEmpleadoLiquidacion.split(',');
    var sConvenioId = '';     
    var sConvenioNombre = '';   
    $("#form_FuncionImprimir").find("input[id^='hdnConvenioNombre_']").each(function() {         
         sConvenioId = ($(this).attr('id')).split('_')[1];
         $("#divPaginasConvenio_"+sConvenioId).html('');
         sConvenioNombre = ($(this).val()).toUpperCase();
         if (sConvenioEmpleadoLiquidacion.indexOf(','+sConvenioId+'_') > -1)
         {
            var iCantPorConvenio = 0;
            var sEmpleadosIds = '';
            var sHtmlPag = '';            
            for (var i=0; i < aConvenioEmpleadoLiquidacion.length; i++)
            {
                if (sConvenioId == aConvenioEmpleadoLiquidacion[i].split('_')[0])//aConvenioEmpleadoLiquidacion[i].split('_')[0]: ConvenioId
                {
                    iCantPorConvenio = iCantPorConvenio + 1;
                    sEmpleadosIds = sEmpleadosIds + aConvenioEmpleadoLiquidacion[i].split('_')[1] +','; //aConvenioEmpleadoLiquidacion[i].split('_')[1]: EmpleadoId
                }
            }

            if (iCantPorConvenio > 0)
            {
                var iCantPaginas = Math.ceil(iCantPorConvenio/100);
                sHtmlPag = "<span>" + sConvenioNombre + "</span></br>";
                sHtmlPag += "<span>Paginas: </span>";
                for (var j=1; j <= iCantPaginas; j++)
                {                
                    sHtmlPag += "<button class='btn_realizar_tarea' style='width:40px; margin:2px' onclick='cargarTodosLosSueldos("+sConvenioId+","+j+");'>"+j+"</button>";
                    sHtmlPag += "<input type='hidden' id='hdnConvenioEmpleados_"+sConvenioId+"_"+j+"' value='"+sEmpleadosIds+"' />"
                }
                $("#divPaginasConvenio_"+sConvenioId).html(sHtmlPag);
            }
         }
     });   
}
var EmpleadosLiquidados = 0;

function GenerarYGuardarTodosLosSueldos(){
    //cargar el primer sueldo
    var empleados = JSON.parse($("#empleadosALiquidar").val());   
    var periodo = $("#periododefault").val();
    var tipoliquidacion = nombreliquidacion[$("#tipoliquidacionAguardar").val()];
    var clienteid = $("#cliid").val();
    cargarSueldoEmpleado(clienteid,periodo,empleados[0],tipoliquidacion,0)
    //guardar liquidacion
    //mostrar respuesta
    //cargar siguiente sueldo y repetir       
}
function guardarElSueldo(empid){
    $('#ValorreciboPapeldetrabajosueldosForm'+empid).submit();
    //mostrar respuesta
    //cargar siguiente sueldo y repetir   
}
function showHideEmpleadoOnClick(){
    $(".snapempleado").click(function(){
        $(this).unbind();
        var empid = $(this).attr('data-identificacion');
        if(!$(this).hasClass("shown")){
            showHideColumnsEmpleado(empid);
            $(this).addClass('shown');
        }else{
            showHideColumnsEmpleado(empid);
            $(this).removeClass('shown');
        }
    });
}
function showHideColumnsEmpleado(empid){
    var empleado = $( "div[data-identificacion="+empid+"]" );
    var show = false;
    if($(empleado).hasClass("shown")){
        show = false;
        $(empleado).removeClass('shown');
    }else{
        show = true;
        $(empleado).addClass('shown');
    }
    if( $("#indiceCargaEmpleado"+empid).val()!=0){
        $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdconcepto').each(function(){
            if(!show){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
        $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdseccion').each(function(){
            if(!show){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    }

    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdalicuota').each(function(){
        if(!show){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdformula').each(function(){
        if(!show){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdcodigo').each(function(){
        if(!show){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdcodigoalicuota').each(function(){
        if(!show){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
}

function cargarSueldoEmpleado(clienteid,periodo,empid,liquidacion,indice){
    
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajosueldos/"+clienteid+"/"+periodo+"/"+empid+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {            
            if(indice==0){
                $("#divSueldoForm").prepend(
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                ).prepend(
                    $('<div />',{
                        'style':"width:100%",
                    }).append(
                        $('<label />',{
                            'text':"Mostrar Todo",
                            'for':"mostrarcamposencero",
                            'style':"display:inline",
                        })
                    ) .append(
                        $('<input />',{
                            'type':'checkbox',
                            'id':"mostrarcamposencero",
                            'style':"display:inline",
                            'onclick':"ocultarFunciones()",
                        })
                    )
                );

                

                $("#divEmpleado_"+empid).removeClass("divempleado");
                $("#divConvenio_"+empid).removeClass("divempleado");
                
            }else if(indice==99) { //if(indice==empleados.length-1) {  
                //indice==4 Maximo valor, ya que son 5 por pagina.
                $("#divSueldoForm").append(
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                );
                // }else if(indice==empleados.length-1){
            }else{
                if($( ".divsueldomasivo").first().length>0){
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                        .insertAfter($( ".divsueldomasivo").first())
                }else{
                    $("#divSueldoForm").append(
                        $("<div>")
                            .append(response)
                            .addClass('divsueldomasivo')
                    );
                }

            }
            $("#indiceCargaEmpleado"+empid).val(indice);

            activarCalXOnSueldos(empid);
            showHideColumnsEmpleado(empid);
            showHideEmpleadoOnClick()
            
            //guardar liquidacion
            guardarElSueldo(empid);
            
            //mostrar respuesta
            //cargar siguiente sueldo y repetir     
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}
function aplicarATodos(cctxcid){
    var value = $('.inputCctxc'+cctxcid+":first").val()
    $("#loading").css('visibility','visible');
    alert(value);
    $('.inputCctxc'+cctxcid).each(function() {
        $(this).val(value);
    });
    $("#loading").css('visibility','hidden');
}
var tablaSueldoCalx=[];
function ocultarFunciones(){    
    var  deferredCollection = [];    
    $("div[id^='divEmpleado_']").each(function() {        
        var sEmpleadoId = ($(this).attr('id')).split('_')[1];
        var indice = $("#indiceCargaEmpleado"+sEmpleadoId).val();        
        if(indice==0)        
        {            
            deferredCollection.push(ocultarFuncinesDeUnFormulario(sEmpleadoId));
            return;
        }
    });  
}
function ocultarFuncinesDeUnFormulario(empid){
    if(ajaxAbierto){
       return false;
    }
    //console.log("inicio de Ocultar: "+empid);
    var empidFuncion = 0;
    $("#ValorreciboPapeldetrabajosueldosForm"+empid+" .funcionAAplicar").each(function() {
        empidFuncion++;
        var posicion = $(this).attr('posicion');
        var seccion = $(this).attr('seccion');
        var headsection = $(this).attr('headseccion');
        var dataCodigo = $(this).attr('data-codigo');

        //aca tengo que preguntar si todos los valoresrecibos tienen valor 0 sino no oculto
        var todosvacios = true;
        $('.'+dataCodigo).each(function() {
            $mysheet = $(this).closest('form').calx("getSheet");
            if(dataCodigo=="R33"){
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+"= "+$mysheet.getCellValue("R33"));
            }
            if(dataCodigo=="R10"){
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+"= "+$mysheet.getCellValue("R10"));
            }
            if(dataCodigo.charAt(0)=='O') {
                 // $mysheet.getCell(dataCodigo).calculate();
                // var myCell = $mysheet.getCell(dataCodigo);
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormat() + "'");
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormattedValue() + "'");
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormula() + "'");
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.evaluateFormula() + "'");
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getValue() + "'");
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.renderComputedValue() + "'");
            }

            if(($mysheet.getCellValue(dataCodigo)*1)!=0){
                todosvacios = false;
                // console.log(empid+"-Este es distinto de 0 != "+$mysheet.getCellValue(dataCodigo));
                return false;
            }
        });
        //por alguna razon los que son "O"(otros) no se estan autocalculando (beto a saber por que)
        //asi que aca vamos a forzar un recalculo

        if(todosvacios){
            //tengo que ocultar el row solo si no es cabeza de seccion por que sino me oculta toda la seccion
            if(headsection=="0"){
                if($("#mostrarcamposencero").is(':checked')){
                    showRow(empid,dataCodigo,seccion);
                }else{
                    hideRow(empid,dataCodigo,seccion);
                }
            }
        }else{
            showRow(empid,dataCodigo,seccion)
        }
    });
}
function hideRow(empid,dataCodigo,seccion){
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input[valdata-codigo="'+dataCodigo+'"]').each(function() {
        //si esta visible tengo que mermar en 1 el rowspan
        var rowspan = 0;
        var rowVisible =  $(this).closest('tr').is(':visible');
        if(rowVisible==true) {
            rowspan = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan');
            rowspan = rowspan - 1;
            // $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan',rowspan);
            $('.seccion'+seccion).each(function(){
                $(this).attr('rowspan',rowspan);
            });
        }
        $('input[valdata-codigo="'+dataCodigo+'"]').each(function() {
            $(this).closest('tr').hide();
        });
    });
}
function showRow(empid,dataCodigo,seccion){
    //si esta oculto tengo que aumentar en 1 el rowspan
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input[valdata-codigo="'+dataCodigo+'"]').each(function() {
        //si esta visible tengo que mermar en 1 el rowspan
        var rowspan = 0;
        var rowVisible = $(this).closest('tr').is(':visible');
        if(rowVisible==false ) {
            rowspan = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion' + seccion).attr('rowspan')*1;
            rowspan = rowspan + 1;
            // $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan',rowspan);
            $('.seccion'+seccion).each(function(){
                $(this).attr('rowspan',rowspan);
            });
        }
        //tengo que mostrar el row
        $('input[valdata-codigo="'+dataCodigo+'"]').each(function() {
            $(this).closest('tr').show();
        });
    });
}
function activarCalXOnSueldos(empid){

    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .funcionAAplicar').on('change', function() {
        var posicion = $(this).attr('posicion');
        $('#ValorreciboPapeldetrabajosueldosForm'+empid).calx(
            'getCell',
            $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #Valorrecibo'+posicion+'Valor').attr('data-cell')
        ).setFormula($(this).val());
        tablaSueldoCalx[empid].calx('calculate');
        $('#Valorrecibo'+posicion+'Formulamodificada').prop('checked', true);
        $('#Valorrecibo'+posicion+'Nuevaformula').val( $('#Valorrecibo'+posicion+'Formula').val());
    });

    tablaSueldoCalx[empid] = $('#ValorreciboPapeldetrabajosueldosForm'+empid).calx({
        language : 'id',
        'autoCalculate'         : true,
        onAfterRender : function(){
            ocultarFuncinesDeUnFormulario(empid);
        } ,
        onBeforeRender : function(){
        }
    });
    tablaSueldoCalx[empid].submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){                
                var empleados = JSON.parse($("#empleadosALiquidar").val());   
                var indice = $("#indiceLiquidacion").val();
                callAlertPopint("Se han guardado "+(indice+1)+" de "+empleados.length+" sueldos");
                //var empleados = JSON.parse($("#arrayEmpleados").val());
                
                //de este formulario borrar el div mas cercano con clase divsueldomasivo
                $('#ValorreciboPapeldetrabajosueldosForm'+empid).closest('div.divsueldomasivo').remove();
                //Update DDL Empleados                
               
                if(indice < empleados.length){
                    var empleados = JSON.parse($("#empleadosALiquidar").val());   
                    var periodo = $("#periododefault").val();
                    var tipoliquidacion = nombreliquidacion[$("#tipoliquidacionAguardar").val()];
                    var clienteid = $("#cliid").val();
                    indice++;
                    cargarSueldoEmpleado(clienteid,periodo,empleados[indice],tipoliquidacion);
                    $("#indiceLiquidacion").val(indice);                    
                }else{
                    callAlertPopint("Se han guardado "+empleados.length+' sueldos, puede controlar las liquidaciones presionando el boton "volver"');
                }
                //mostrar respuesta
                //cargar siguiente sueldo y repetir   
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });


    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input').change(function(){
        var mysheet = $(this).closest('form').calx("getSheet");
        mysheet.calculate();
    });
}
function reloadInputDates(){
    var d = new Date( );
    d.setMonth( d.getMonth( ) - 1 );
    (function($){
        $( "input.datepicker" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: true,
            changeYear: true,
            constrainInput: false,
            dateFormat: 'dd-mm-yy',
            defaultDate: d,
        });
        $( "input.datepicker-dia" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: false,
            changeYear: false,
            constrainInput: false,
            dateFormat: 'dd',
            defaultDate: d,

        });
    })(jQuery);

}
function realizarEventoCliente(periodo,clienteid,estadotarea){
    var datas =  "0/tarea3/"+periodo+"/"+clienteid;
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/novedadescargadas/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
        data: data,  // post data
        success: function(response) {
            var resp = response.split("&&");
            var respuesta=resp[1];
            var error=resp[0];
            if(error!=0){
                callAlertPopint('Error por favor intente mas tarde');
                return;
            }else{
                callAlertPopint('Estado de la tarea modificado');
            }
            return false;
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}

