/**
 * Created by coto1 on 07/08/2017.
 */
function consultaCondicionAfip(cuit){
    var data ="";
    $.ajax({
        type: "get",
        crossDomain: true,
        url: 'https://soa.afip.gob.ar/sr-padron/v2/persona/' + cuit,
        async: true,
        cache: false,
        dateType: 'JSON',
        data: {},
        success: function (transport) {
            if (transport.success) {
                alert('Nombre: ' + transport.data.nombre + "\n"
                    + 'Direccion: ' + transport.data.domicilioFiscal.direccion + "\n"
                    + 'CP: ' + transport.data.domicilioFiscal.codPostal+ "\n"
                    + 'Condicion: ' + evaluar_condicion(transport.data.impuestos,transport.data.categoriasMonotributo)
                ) ;
            }else{
                alert('No se pudo procesar el CUIT solicitado') ;
            }
        }
    });
}
function evaluar_condicion(impuestos, categoriasMonotributo)
{
    if (typeof impuestos == 'object' && impuestos.indexOf(32) != -1)
        var idtipoiva = 'Exento'; // Exento
    else if (typeof categoriasMonotributo == 'object')
        var idtipoiva = 'Monotributista'; // Monotributo
    else if (typeof impuestos == 'object'
        && (impuestos.indexOf(30) != -1 || impuestos.indexOf(103) != -1))
        var idtipoiva = 'Responsable Inscripto'; // Responsable Inscripto
    else
        var idtipoiva = 'Consumidor Final'; // Consumidor Final
    return idtipoiva;
}