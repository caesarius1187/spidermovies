/**
 * Created by caesarius on 04/01/2017.
 */
var tblsys ;
$(document).ready(function() {
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();
    $( "#clickExcel" ).click(function() {
        $("#tblsys").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Conveniomultilateral",
            filename:$('#clientenombre').val()+"-"+ $('#periodo').val()+"-"+"IVA",
        });
    });

    $("#tblsys tr").each(function(){
        if($(this).hasClass('trclickeable')){
            $(this).dblclick(function () {
                //vamos a preguntar si es visible el Div que tenemos que mostrar para no recibir el click si no se tiene el foco

                var cuecliid = $(this).attr('cuecliid');
                var cliid = $("#cliid").val();
                var periodo = $("#periodo").val();
                var data = "";
                $.ajax({
                    type: "post",  // Request method: post, get
                    url: serverLayoutURL+"/asientos/index/"+cliid+"/"+periodo+"/"+cuecliid, // URL to request
                    data: data,  // post data
                    success: function(response) {

                        $('#myModal').on('show.bs.modal', function() {
                            if ($('#myModal').is(":visible")){
                                return;
                            }
                            $('#myModal').find('.modal-title').html('Libro Mayor');
                            $('#myModal').find('.modal-body').html(response);
                            // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                        });
                        $('#myModal').modal('show');
                        $("#tblListaMovimientos").DataTable( {
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [ 10, 25, 50, -1 ],
                                [ '10', '25', '50', 'todas' ]
                            ],
                            buttons: [
                                {
                                    extend: 'pageLength',
                                    text: 'Ver',
                                },
                                {
                                    extend: 'csv',
                                    text: 'CSV',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: 'Excel',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    },
                                    orientation: 'landscape',
                                    download: 'open',
                                    message: 'Asientos-'+nombrecliente+'-'+periodo,

                                },
                                {
                                    extend: 'copy',
                                    text: 'Copiar',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    exportOptions: {
                                        columns: '.printable'
                                    },
                                    orientation: 'landscape',
                                    footer: true,
                                    autoPrint: true,
                                    message: 'Asientos-'+nombrecliente+'-'+periodo,
                                    customize: function ( win ) {
                                    },
                                },
                            ],
                        });
                        $("#myModal #cargarAsiento").hide();
                        $("#tblAsientos").DataTable( {
                            iDisplayLength: -1,
                            scrollCollapse: true,
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [ 10, 25, 50, -1 ],
                                [ '10', '25', '50', 'todas' ]
                            ],
                            buttons: [
                                {
                                    extend: 'pageLength',
                                    text: 'Ver',
                                },
                                {
                                    extend: 'csv',
                                    text: 'CSV',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: 'Excel',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'Asientos-'+nombrecliente+'-'+periodo,
                                    exportOptions: {
                                        columns: ':visible'
                                    },
                                    orientation: 'landscape',
                                    download: 'open',
                                    message: 'Asientos-'+nombrecliente+'-'+periodo,

                                },
                                {
                                    extend: 'copy',
                                    text: 'Copiar',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    exportOptions: {
                                        columns: '.printable'
                                    },
                                    orientation: 'landscape',
                                    footer: true,
                                    autoPrint: true,
                                    message: 'Asientos-'+nombrecliente+'-'+periodo,
                                    customize: function ( win ) {
                                    },
                                },
                            ],
                        });
                        $('#tblAsientos tbody').on( 'click', 'tr', function () {
                            if ( $(this).hasClass('selected') ) {
                                $(this).removeClass('selected');
                            }
                            else {
                                tblAsientos.$('tr.selected').removeClass('selected');
                                $(this).addClass('selected');
                            }
                        } );
                           

                    },
                    error:function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            });
        }
    });
    tblsys = $('#tblsys').DataTable( {
        dom: 'Bfrtip',
        fixedHeader: true,
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10', '25', '50', 'todas' ]
        ],
        buttons: [
            {
                extend: 'pageLength',
                text: 'Ver',
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'SumasYSaldo-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'SumasYSaldo-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'SumasYSaldo-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                download: 'open',
                message: 'SumasYSaldo-'+nombrecliente+'-'+periodo,
            },
            {
                extend: 'copy',
                text: 'Copiar',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                exportOptions: {
                    columns: 'visible'
                },
                orientation: 'landscape',
                footer: true,
                autoPrint: true,
                message: 'SumasYSaldo-'+nombrecliente+'-'+periodo,
                customize: function ( win ) {
                },
            },
            {
                text: 'Ver saldo mensual',
                action: function ( e, dt, node, config ) {
                    toogleColumns();
                }
            },
        ],
        iDisplayLength: -1
    });
    //$('#tblsys').floatThead();
});
var visiblesaldosMensuales = 1;
function toogleColumns(){    
    if(visiblesaldosMensuales){
        tblsys.columns( '.saldosMensuales' ).visible( false );
        visiblesaldosMensuales = 0;
    }else{
        tblsys.columns( '.saldosMensuales' ).visible( true );
        visiblesaldosMensuales = 1;    
    }
    $('#tblsys').width("100%");
}
function imprimir(){
    window.print();
}