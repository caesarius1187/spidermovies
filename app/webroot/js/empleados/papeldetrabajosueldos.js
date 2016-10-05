jQuery(document).ready(function($) {
   
   
});
function cargarSueldoEmpleado(impcliid,periodo,empleadoid){
    var win = window.open(serverLayoutURL+'/empleados/papeldetrabajosueldos/'+impcliid+'/'+periodo+'/'+empleadoid , '_self');
}