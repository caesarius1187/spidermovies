<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<?php
if(isset($error)){ ?>
    <h2><?php echo $error; ?></h2>
<?php
    return;
}
?>
<div class="index">
    <h3><?php echo __('Contabilizar Ventas: '.$cliente['Cliente']['nombre']); ?></h3>
    <?php
    $id = 0;
    $nombre = "Venta: ".$periodo;
    $descripcion = "Automatico";
    $fecha = date('t-m-Y',strtotime('01-'.$periodo));
    $miAsiento=array();
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    if(isset($asientoyacargado['Asiento'])){
        $miAsiento = $asientoyacargado['Asiento'];
        $id = $miAsiento['id'];
        $nombre = $miAsiento['nombre'];
        $descripcion = $miAsiento['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
    }

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'ventas','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    $totalDebe=0;
    $totalHaber=0;
    foreach ($asientoestandares as $asientoestandar) {
        $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $key=0;

        if(isset($asientoyacargado['Movimiento'])) {
            foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento){
                if(!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
                    $asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
                }
                if($cuentaclienteid==$movimiento['cuentascliente_id']){

                    $key=$kMov;
                    $movid=$movimiento['id'];
                    $asiento_id=$movimiento['asiento_id'];
                    $debe=$movimiento['debe'];
                    $haber=$movimiento['haber'];
                    $asientoyacargado['Movimiento'][$kMov]['cargado']=true;
                }
            }
        }
        /*Aca vamos a reescribir el debe y el haber si es que corresponde para esta cuenta con este cliente*/
        //Este switch controla todas las cuetnas que hay en "ventas" obligadamente
        switch ($asientoestandar['Cuenta']['id']){
            /*Casos comun a todas las ventas*/
            case '235'/*110399001 Cliente xx*/:
                $cuenta110399001 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110399001+=$ventasgravada[0]['total']*$suma;
                }
                if($cuenta110399001<0){
                    $haber = $cuenta110399001*-1;
                }else{
                    $debe = $cuenta110399001;
                }
            break;
            case '1469'/*210401403 IVA Percepciones realizadas*/:
                $cuenta210401403 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }$suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta210401403+=$ventasgravada[0]['ivapercep']*$suma;
                }
                if($cuenta210401403<0){
                    $debe = $cuenta210401403*-1;
                }else{
                    $haber = $cuenta210401403;
                }
            break;
            case '1467'/*210401401 IVA Debito Fiscal General*/:
                $cuenta210401401 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta210401401+=$ventasgravada[0]['iva']*$suma;
                }
                if($cuenta210401401<0){
                    $debe = $cuenta210401401*-1;
                }else{
                    $haber = $cuenta210401401;
                }
            break;
            case '1493'/*210402102 Ingresos Brutos Percepciones Realizadas*/:
                $cuenta210402102 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta210402102+=$ventasgravada[0]['iibbpercep']*$suma;
                }
                if($cuenta210402102<0){
                    $debe = $cuenta210402102*-1;
                }else{
                    $haber = $cuenta210402102;
                }
            break;
            case '1519'/*210403102 Actividades Varias Percepciones Realizadas*/:
                $cuenta210403102 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta210403102+=$ventasgravada[0]['actvspercep']*$suma;
                }
                if($cuenta210403102<0){
                    $debe = $cuenta210403102*-1;
                }else{
                    $haber = $cuenta210403102;
                }
            break;
            case '1478'/*210401802 Imp Internos Percepciones Realizadas*/:
                $cuenta210401802 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta210401802+=$ventasgravada[0]['impinternos']*$suma;
                }
                if($cuenta210401802<0){
                    $debe = $cuenta210401802*-1;
                }else{
                    $haber = $cuenta210401802;
                }
                break;
            /*Casos Primera Categoria*/
            case '2901'/*602010001 a)Locacion de Inmueble*/:
            case '2902'/*602010002 b)Contraprestacion recibida por derechos reales*/:
            case '2903'/*602010003 c)Valor de mejoras en inmuebles introducidas por inquilinos*/:
            case '2904'/*602010004 d)Grabamenes a cargo del inquilino*/:
            case '2905'/*602010005 e)Cobro por el uso de muebles que suministra el propietario*/:
            case '2906'/*602010006 f)Valor locativo de inmueble de veraneo*/:
            case '2907'/*602010007 g)Valor locativo de inmueble cedidos gratuitamente*/:
            case '2908'/*602010008 Sublocaciones*/:
            case '2909'/*602010009 Otros*/:
                //Si esta cuenta aparece es por que estoy pagando 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta601010001 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad =
                        $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($cuentadelaactiviadad!=$asientoestandar['Cuenta']['id']){
                        continue;
                    }
                    if($categoriaDeLaVenta!='primeracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta601010001+=$ventasgravada[0]['neto']*$suma;
                }
                if($cuenta601010001<0){
                    $debe = $cuenta601010001*-1;
                }else{
                    $haber = $cuenta601010001;
                }
                break;
            case '2911'/*602020001 a)Locacion de Inmueble*/:
            case '2912'/*602020002 b)Contraprestacion recibida por derechos reales*/:
            case '2913'/*602020002 c)Valor de mejoras en inmuebles introducidas por inquilinos*/:
            case '2914'/*602020003 d)Grabamenes a cargo del inquilino*/:
            case '2915'/*602020004 e)Cobro por el uso de muebles que suministra el propietario*/:
            case '2916'/*602020006 f)Valor locativo de inmueble de veraneo*/:
            case '2917'/*602020007 g)Valor locativo de inmueble cedidos gratuitamente*/:
            case '2918'/*602020008 Sublocaciones*/:
            case '2919'/*602020009 Otros*/:
                $nogravado1racategoria = ['2911'=>'2901','2912'=>'2902','2913'=>'2903','2914'=>'2904','2915'=>'2905',
                    '2916'=>'2906','2917'=>'2907','2918'=>'2908','2919'=>'2909'];
                //este array muestra la relacion entre las cuentas de la primera categoria y las cuentas de la primer categoria
                //para las ventas no gravadas

                //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                $cuenta602020001 = 0;
                //Cargar la venta total

                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($nogravado1racategoria[$asientoestandar['Cuenta']['id']] != $cuentadelaactiviadad){
                        continue;
                    }
                    if($categoriaDeLaVenta!='primeracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta602020001+=$ventasgravada[0]['nogravados']*$suma;
                    $cuenta602020001+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuenta602020001<0){
                    $debe = $cuenta602020001*-1;
                }else{
                    $haber = $cuenta602020001;
                }
                break;
            /*Casos Segunda Categoria*/
            case '2922'/*603010001 a)Renta de titulo*/:
            case '2923'/*603010002 b)Locacion de cosa mueble*/:
            case '2924'/*603010003 c)Renta vitalicia y de seguro de vida*/:
            case '2925'/*603010004 d)Seguro de retiro privado*/:
            case '2926'/*603010005 e)Rescates de planes de seguro de retiro*/:
            case '2927'/*603010006 f)Obligaciones de no hacer*/:
            case '2928'/*603010007 g)Interes de cooperativas excepto las de consumo*/:
            case '2929'/*603010008 h)Ingreso por derecho llave y patentes*/:
            case '2930'/*603010009 i)Dividendos que distribuyan soc del Ins a) Art 69*/:
            case '2931'/*603010011 k)Compraventa, acciones, cuotas sociales y titulos*/:
            case '2932'/*603010012 Otros*/:
                //Si esta cuenta aparece es por que estoy pagando 2da categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta2da = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad =
                        $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($cuentadelaactiviadad!=$asientoestandar['Cuenta']['id']){
                        continue;
                    }
                    if($categoriaDeLaVenta!='segundacateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta2da+=$ventasgravada[0]['neto']*$suma;
                }
                if($cuenta2da<0){
                    $debe = $cuenta2da*-1;
                }else{
                    $haber = $cuenta2da;
                }
                break;
            case '2934'/*603010001 a)Renta de titulo*/:
            case '2935'/*603010002 b)Locacion de cosa mueble*/:
            case '2936'/*603010003 c)Renta vitalicia y de seguro de vida*/:
            case '2937'/*603010004 d)Seguro de retiro privado*/:
            case '2938'/*603010005 e)Rescates de planes de seguro de retiro*/:
            case '2939'/*603010006 f)Obligaciones de no hacer*/:
            case '2940'/*603010007 g)Interes de cooperativas excepto las de consumo*/:
            case '2941'/*603010008 h)Ingreso por derecho llave y patentes*/:
            case '2942'/*603010009 i)Dividendos que distribuyan soc del Ins a) Art 69*/:
            case '2943'/*603010011 k)Compraventa, acciones, cuotas sociales y titulos*/:
            case '2944'/*602020009 Otros*/:
                $nogravado2racategoria = [
                    '2922'=>'2934','2923'=>'2935','2924'=>'2936','2925'=>'2937','2926'=>'2938','2927'=>'2939',
                    '2928'=>'2940','2929'=>'2941','2930'=>'2942','2931'=>'2943','2932'=>'2944'];
                //este array muestra la relacion entre las cuentas de la primera categoria y las cuentas de la primer categoria
                //para las ventas no gravadas

                //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                $cuenta2danogravada = 0;
                //Cargar la venta total

                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($nogravado2racategoria[$asientoestandar['Cuenta']['id']] != $cuentadelaactiviadad){
                        continue;
                    }
                    if($categoriaDeLaVenta!='primeracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta2danogravada+=$ventasgravada[0]['nogravados']*$suma;
                    $cuenta2danogravada+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuenta2danogravada<0){
                    $debe = $cuenta2danogravada*-1;
                }else{
                    $haber = $cuenta2danogravada;
                }
                break;
            /*Casos Tercera Empresas Categoria*/
            case '2888'/*601010001 Venta Neta*/:
                //Si esta cuenta aparece es por que estoy pagando 3ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta601010001 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta!='terceracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta601010001+=$ventasgravada[0]['neto']*$suma;
                }
                if($cuenta601010001<0){
                    $debe = $cuenta601010001*-1;
                }else{
                    $haber = $cuenta601010001;
                }
                break;
            case '2889'/*601010002 Venta Bien de uso */:
                //Si esta cuenta aparece es por que estoy pagando 3ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta601010002 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta!='terceracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        $cuenta601010002+=$ventasgravada[0]['neto']*$suma;
                    }
                }
                if($cuenta601010002<0){
                    $debe = $cuenta601010002*-1;
                }else{
                    $haber = $cuenta601010002;
                }
                break;
            case '3368'/*601011001 Venta Exenta */:
                //Si esta cuenta aparece es por que estoy pagando 3ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta601011001 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta!='terceracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta601011001+=$ventasgravada[0]['nogravados']*$suma;
                    $cuenta601011001+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuenta601011001<0){
                    $debe = $cuenta601011001*-1;
                }else{
                    $haber = $cuenta601011001;
                }
                break;
            case '3369'/*601011002 Venta Exenta Bien de uso*/:
                //Si esta cuenta aparece es por que estoy pagando 3ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta601011002 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta!='terceracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        $cuenta601011002+=$ventasgravada[0]['nogravados']*$suma;
                        $cuenta601011002+=$ventasgravada[0]['exentos']*$suma;
                    }
                }
                if($cuenta601011002<0){
                    $debe = $cuenta601011002*-1;
                }else{
                    $haber = $cuenta601011002;
                }
                break;
            /*Casos Tercera Otros Categoria*/
            case '2948'/*604011001 Fideicomisos xX*/:
            case '2949'/*604011002 Fideicomisos xX*/:
            case '2950'/*604011003 Fideicomisos xX*/:
            case '2951'/*604011004 Fideicomisos xX*/:
            case '2952'/*604011005 Fideicomisos xX*/:
            case '2954'/*604012001 Loteos con findes de urbanizacion xX*/:
            case '2955'/*604012002 Loteos con findes de urbanizacion xX*/:
            case '2956'/*604012003 Loteos con findes de urbanizacion xX*/:
            case '2957'/*604012004 Loteos con findes de urbanizacion xX*/:
            case '2958'/*604012005 Loteos con findes de urbanizacion xX*/:
            case '2960'/*604013001 Otros xX*/:
            case '2961'/*604013002 Otros xX*/:
            case '2962'/*604013003 Otros xX*/:
            case '2963'/*604013004 Otros xX*/:
            case '2964'/*604013005 Otros xX*/:
            case '2966'/*604014002 Profesion u oficion con explotacion xX*/:
            case '2967'/*604014003 Profesion u oficion con explotacion xX*/:
            case '2968'/*604014004 Profesion u oficion con explotacion xX*/:
            case '2969'/*604014005 Profesion u oficion con explotacion xX*/:
            case '2970'/*604014001 Profesion u oficion con explotacion xX*/:
            case '2972'/*604015001 Enajenacion de inmueble según ley 13512 xX*/:
            case '2973'/*604015002 Enajenacion de inmueble según ley 13512 xX*/:
            case '2974'/*604015003 Enajenacion de inmueble según ley 13512 xX*/:
            case '2975'/*604015004 Enajenacion de inmueble según ley 13512 xX*/:
            case '2976'/*604015005 Enajenacion de inmueble según ley 13512 xX*/:
            case '2978'/*604016001 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '2979'/*604016002 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '2980'/*604016003 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '2981'/*604016004 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '2982'/*604016005 Comisionista rematador y demas auxiliares de comercio xX*/:
                //Si esta cuenta aparece es por que estoy pagando 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuentaterceraotros = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad =
                        $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($cuentadelaactiviadad!=$asientoestandar['Cuenta']['id']){
                        continue;
                    }
                    if($categoriaDeLaVenta!='terceracateg45'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuentaterceraotros+=$ventasgravada[0]['neto']*$suma;
                }
                if($cuentaterceraotros<0){
                    $debe = $cuentaterceraotros*-1;
                }else{
                    $haber = $cuentaterceraotros;
                }
                break;
            case '2985'/*604011001 Fideicomisos xX*/:
            case '2986'/*604011002 Fideicomisos xX*/:
            case '2987'/*604011003 Fideicomisos xX*/:
            case '2988'/*604011004 Fideicomisos xX*/:
            case '2989'/*604011005 Fideicomisos xX*/:
            case '2991'/*604012001 Loteos con findes de urbanizacion xX*/:
            case '2992'/*604012002 Loteos con findes de urbanizacion xX*/:
            case '2993'/*604012003 Loteos con findes de urbanizacion xX*/:
            case '2994'/*604012004 Loteos con findes de urbanizacion xX*/:
            case '2995'/*604012005 Loteos con findes de urbanizacion xX*/:
            case '2997'/*604013001 Otros xX*/:
            case '2998'/*604013002 Otros xX*/:
            case '2999'/*604013003 Otros xX*/:
            case '3000'/*604013004 Otros xX*/:
            case '3001'/*604013005 Otros xX*/:
            case '3003'/*604014003 Profesion u oficion con explotacion xX*/:
            case '3004'/*604014004 Profesion u oficion con explotacion xX*/:
            case '3005'/*604014005 Profesion u oficion con explotacion xX*/:
            case '3006'/*604014001 Profesion u oficion con explotacion xX*/:
            case '3007'/*604014002 Profesion u oficion con explotacion xX*/:
            case '3009'/*604015002 Enajenacion de inmueble según ley 13512 xX*/:
            case '3010'/*604015003 Enajenacion de inmueble según ley 13512 xX*/:
            case '3011'/*604015004 Enajenacion de inmueble según ley 13512 xX*/:
            case '3012'/*604015005 Enajenacion de inmueble según ley 13512 xX*/:
            case '3013'/*604015001 Enajenacion de inmueble según ley 13512 xX*/:
            case '3015'/*604016001 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '3016'/*604016002 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '3017'/*604016003 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '3018'/*604016004 Comisionista rematador y demas auxiliares de comercio xX*/:
            case '3019'/*604016005 Comisionista rematador y demas auxiliares de comercio xX*/:
                $nogravado3racategoria = [
                    '2948'=>'2985','2949'=>'2986','2950'=>'2987','2951'=>'2988','2952'=>'2989','2954'=>'2991',
                    '2955'=>'2992','2956'=>'2993','2957'=>'2994','2958'=>'2995','2960'=>'2997','2961'=>'2998',
                    '2962'=>'2999','2963'=>'3000','2964'=>'3001','2966'=>'3003','2967'=>'3004','2968'=>'3005',
                    '2969'=>'3006','2970'=>'3007','2972'=>'3009','2973'=>'3010','2974'=>'3011','2975'=>'3012',
                    '2976'=>'3013','2978'=>'3015','2979'=>'3016','2980'=>'3017','2981'=>'3018','2982'=>'3019'];
                //este array muestra la relacion entre las cuentas de la primera categoria y las cuentas de la primer categoria
                //para las ventas no gravadas

                //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                $cuenta3ranogravada = 0;
                //Cargar la venta total

                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($nogravado3racategoria[$asientoestandar['Cuenta']['id']] != $cuentadelaactiviadad){
                        continue;
                    }
                    if($categoriaDeLaVenta!='primeracateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta3ranogravada+=$ventasgravada[0]['nogravados']*$suma;
                    $cuenta3ranogravada+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuenta3ranogravada<0){
                    $debe = $cuenta3ranogravada*-1;
                }else{
                    $haber = $cuenta3ranogravada;
                }
                break;
            /*Casos Cuarta Categoria*/
            case '3023'/*605011001 Cargos publicos xX*/:
            case '3024'/*605011002 Cargos publicos xX*/:
            case '3025'/*605011003 Cargos publicos xX*/:
            case '3026'/*605011004 Cargos publicos xX*/:
            case '3027'/*605011005 Cargos publicos xX*/:
            case '3029'/*605012001 Trabajo relacion de dependencia xX*/:
            case '3030'/*605012002 Trabajo relacion de dependencia xX*/:
            case '3031'/*605012003 Trabajo relacion de dependencia xX*/:
            case '3032'/*605012004 Trabajo relacion de dependencia xX*/:
            case '3033'/*605012005 Trabajo relacion de dependencia xX*/:
            case '3035'/*605013001 Jubilaciones xX*/:
            case '3036'/*605013002 Jubilaciones xX*/:
            case '3037'/*605013003 Jubilaciones xX*/:
            case '3038'/*605013004 Jubilaciones xX*/:
            case '3039'/*605013005 Jubilaciones xX*/:
            case '3041'/*605014001 Beneficio neto de seguro de retiro privado xX*/:
            case '3042'/*605014002 Beneficio neto de seguro de retiro privado xX*/:
            case '3043'/*605014003 Beneficio neto de seguro de retiro privado xX*/:
            case '3044'/*605014004 Beneficio neto de seguro de retiro privado xX*/:
            case '3045'/*605014005 Beneficio neto de seguro de retiro privado xX*/:
            case '3047'/*605015001 Servicios personales de soc cooperativas xX*/:
            case '3048'/*605015002 Servicios personales de soc cooperativas xX*/:
            case '3049'/*605015003 Servicios personales de soc cooperativas xX*/:
            case '3050'/*605015004 Servicios personales de soc cooperativas xX*/:
            case '3051'/*605015005 Servicios personales de soc cooperativas xX*/:
            case '3053'/*605016001 Profesiones liberales u oficios xX*/:
            case '3054'/*605016002 Profesiones liberales u oficios xX*/:
            case '3055'/*605016003 Profesiones liberales u oficios xX*/:
            case '3056'/*605016004 Profesiones liberales u oficios xX*/:
            case '3057'/*605016005 Profesiones liberales u oficios xX*/:
            case '3059'/*605017001 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3060'/*605017002 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3061'/*605017003 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3062'/*605017004 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3063'/*605017005 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3065'/*605018001 Socio administrador de SRL xX*/:
            case '3066'/*605018002 Socio administrador de SRL xX*/:
            case '3067'/*605018003 Socio administrador de SRL xX*/:
            case '3068'/*605018004 Socio administrador de SRL xX*/:
            case '3069'/*605018005 Socio administrador de SRL xX*/:
                //Si esta cuenta aparece es por que estoy pagando 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuentacuartaotros = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad =
                        $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if($cuentadelaactiviadad!=$asientoestandar['Cuenta']['id']){
                        continue;
                    }
                    if($categoriaDeLaVenta!='cuartacateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuentacuartaotros+=$ventasgravada[0]['neto']*$suma;
                    $cuentacuartaotros+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuentacuartaotros<0){
                    $debe = $cuentacuartaotros*-1;
                }else{
                    $haber = $cuentacuartaotros;
                }
                break;
            case '3071'/*605011001 Cargos publicos xX*/:
            case '3072'/*605011002 Cargos publicos xX*/:
            case '3073'/*605011003 Cargos publicos xX*/:
            case '3074'/*605011004 Cargos publicos xX*/:
            case '3075'/*605011005 Cargos publicos xX*/:
            case '3078'/*605012001 Trabajo relacion de dependencia xX*/:
            case '3079'/*605012002 Trabajo relacion de dependencia xX*/:
            case '3080'/*605012003 Trabajo relacion de dependencia xX*/:
            case '3081'/*605012004 Trabajo relacion de dependencia xX*/:
            case '3082'/*605012005 Trabajo relacion de dependencia xX*/:
            case '3084'/*605013001 Jubilaciones xX*/:
            case '3085'/*605013002 Jubilaciones xX*/:
            case '3086'/*605013003 Jubilaciones xX*/:
            case '3087'/*605013004 Jubilaciones xX*/:
            case '3088'/*605013005 Jubilaciones xX*/:
            case '3090'/*605014001 Beneficio neto de seguro de retiro privado xX*/:
            case '3091'/*605014002 Beneficio neto de seguro de retiro privado xX*/:
            case '3092'/*605014003 Beneficio neto de seguro de retiro privado xX*/:
            case '3093'/*605014004 Beneficio neto de seguro de retiro privado xX*/:
            case '3094'/*605014005 Beneficio neto de seguro de retiro privado xX*/:
            case '3096'/*605015001 Servicios personales de soc cooperativas xX*/:
            case '3097'/*605015002 Servicios personales de soc cooperativas xX*/:
            case '3098'/*605015003 Servicios personales de soc cooperativas xX*/:
            case '3099'/*605015004 Servicios personales de soc cooperativas xX*/:
            case '3100'/*605015005 Servicios personales de soc cooperativas xX*/:
            case '3102'/*605016001 Profesiones liberales u oficios xX*/:
            case '3103'/*605016002 Profesiones liberales u oficios xX*/:
            case '3104'/*605016003 Profesiones liberales u oficios xX*/:
            case '3105'/*605016004 Profesiones liberales u oficios xX*/:
            case '3106'/*605016005 Profesiones liberales u oficios xX*/:
            case '3108'/*605017001 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3109'/*605017002 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3110'/*605017003 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3111'/*605017004 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3112'/*605017005 Corredores, viajante de comercio y despachante de aduana xX*/:
            case '3114'/*605018001 Socio administrador de SRL xX*/:
            case '3115'/*605018002 Socio administrador de SRL xX*/:
            case '3116'/*605018003 Socio administrador de SRL xX*/:
            case '3117'/*605018004 Socio administrador de SRL xX*/:
            case '3118'/*605018005 Socio administrador de SRL xX*/:
                $nogravado4tacategoria = [
                    '3023'=>'3071',
                    '3024'=>'3072',
                    '3025'=>'3073',
                    '3026'=>'3074',
                    '3027'=>'3075',
                    '3029'=>'3078',
                    '3030'=>'3079',
                    '3031'=>'3080',
                    '3032'=>'3081',
                    '3033'=>'3082',
                    '3035'=>'3084',
                    '3036'=>'3085',
                    '3037'=>'3086',
                    '3038'=>'3087',
                    '3039'=>'3088',
                    '3041'=>'3090',
                    '3042'=>'3091',
                    '3043'=>'3092',
                    '3044'=>'3093',
                    '3045'=>'3094',
                    '3047'=>'3096',
                    '3048'=>'3097',
                    '3049'=>'3098',
                    '3050'=>'3099',
                    '3051'=>'3100',
                    '3053'=>'3102',
                    '3054'=>'3103',
                    '3055'=>'3104',
                    '3056'=>'3105',
                    '3057'=>'3106',
                    '3059'=>'3108',
                    '3060'=>'3109',
                    '3061'=>'3110',
                    '3062'=>'3111',
                    '3063'=>'3112',
                    '3065'=>'3114',
                    '3066'=>'3115',
                    '3067'=>'3116',
                    '3068'=>'3117',
                    '3069'=>'3118'];
                //este array muestra la relacion entre las cuentas de la primera categoria y las cuentas de la primer categoria
                //para las ventas no gravadas

                //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                $cuenta4tanogravada = 0;
                //Cargar la venta total

                foreach ($ventasgravadas as $ventasgravada) {
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    $cuentadelaactiviadad = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                    if(isset($nogravado4tacategoria[$asientoestandar['Cuenta']['id']])){
                        Debugger::dump("Usted ha seleccionado una cuenta para la cuerta categoria que pertenece a los NO GRAVADOS.
                         Por favor seleccione la correspondiente en el orden de los gravados.");
                    }
                    if($nogravado4tacategoria[$asientoestandar['Cuenta']['id']] != $cuentadelaactiviadad){
                        continue;
                    }
                    if($categoriaDeLaVenta!='cuartacateg'){
                        continue;
                    }
                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
                        continue;
                    }
                    $suma = 1;
                    if($ventasgravada['Venta']['tipodebito']=='Restitucion debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta4tanogravada+=$ventasgravada[0]['nogravados']*$suma;
                    $cuenta4tanogravada+=$ventasgravada[0]['exentos']*$suma;
                }
                if($cuenta4tanogravada<0){
                    $debe = $cuenta4tanogravada*-1;
                }else{
                    $haber = $cuenta4tanogravada;
                }
                break;
        }

        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
            'label'=>($i!=0)?false:'Cuenta',
            'default'=>$cuentaclienteid,
            'defaultoption'=>$cuentaclienteid,
            'class'=>'chosen-select-cuenta',
        ]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
            'type'=>'hidden',
            'readonly','readonly',
            'value'=>date('d-m-Y'),
            'label'=>($i!=0)?false:'Fecha',
        ));
        $movimientoConValor = "movimientoSinValor";
        if(($debe*1) != 0 || ($haber*1) != 0){
            $movimientoConValor = "movimientoConValor";
        }
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
            'class'=>"inputDebe ".$movimientoConValor,
            'label'=>($i!=0)?false:'Debe',
            'default'=>number_format($debe, 2, ".", ""),]);

        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
            'class'=>"inputHaber ".$movimientoConValor,
            'label'=>($i!=0)?false:'Haber',
            'default'=>number_format($haber, 2, ".", ""),]);
        echo "</br>";
        $totalDebe +=$debe;
        $totalHaber +=$haber;
        $i++;
    }
    /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
    entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
    if(isset($asientoyacargado['Asiento'])) {
        foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
            $movid = 0;
            $asiento_id = 0;
            $debe = 0;
            $haber = 0;
            $cuentaclienteid = 0;
            if ($asientoyacargado['Movimiento'][$kMov]['cargado'] == false) {
                $movid = $movimiento['id'];
                $asiento_id = $movimiento['asiento_id'];
                $debe = $movimiento['debe'];
                $haber = $movimiento['haber'];
                $cuentaclienteid = $movimiento['cuentascliente_id'];
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['default' => $movid]);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', [
                    'label'=>($i!=0)?false:'Cuenta',
                    'default' => $cuentaclienteid]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                    'type'=>'hidden',
                    'label'=>($i!=0)?false:'Fecha',
                    'readonly','readonly',
                    'value'=>date('d-m-Y'),
                ));
                $movimientoConValor = "movimientoSinValor";
                if(($debe*1) != 0 || ($haber*1) != 0){
                    $movimientoConValor = "movimientoConValor";
                }
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', [
                    'label'=>($i!=0)?false:'Debe',
                    'class'=>"inputDebe ".$movimientoConValor,
                    'default' => number_format($debe, 2, ".", "")]);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', [
                    'label'=>($i!=0)?false:'Haber',
                    'class'=>"inputHaber ".$movimientoConValor,
                    'default' => number_format($haber, 2, ".", "")]);
                echo "</br>";
                $totalDebe +=$debe;
                $totalHaber +=$haber;
                $i++;
            }
        }
    }
    echo $this->Form->end('Guardar asiento');
    echo $this->Form->label('','&nbsp; ',[
        'style'=>"display: -webkit-inline-box;width:330px;"
    ]);
    echo $this->Form->label('lblTotalDebe',
        "$".number_format($totalDebe, 2, ".", ""),
        [
            'id'=>'lblTotalDebe',
            'style'=>"display: inline;"
        ]
    );
    echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:100px;"]);
    echo $this->Form->label('lblTotalHaber',
        "$".number_format($totalHaber, 2, ".", ""),
        [
            'id'=>'lblTotalHaber',
            'style'=>"display: inline;"
        ]
    );
    if(number_format($totalDebe, 2, ".", "")==number_format($totalHaber, 2, ".", "")){
        echo $this->Html->image('test-pass-icon.png',array(
                'id' => 'iconDebeHaber',
                'alt' => 'open',
                'class' => 'btn_exit',
                'title' => 'Debe igual al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
            )
        );
    }else{
        echo $this->Html->image('test-fail-icon.png',array(
                'id' => 'iconDebeHaber',
                'alt' => 'open',
                'class' => 'btn_exit',
                'title' => 'Debe distinto al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
            )
        );
    }
    ?>
</div>
