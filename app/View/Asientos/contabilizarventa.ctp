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
    $explicacionAsiento ="";
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
        $explicacionAsiento = "El asiento ya estaba cargado asi que vamos a modificarlo.";
    }

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
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
        //Este switch controla todas las cuentas que hay en "ventas" obligadamente

        /*Casos comun a todas las ventas*/
        switch ($asientoestandar['Cuenta']['id']){
            //1 si somos monotributistas tenemos que usar 606050000
            case '235'/*110399001 Cliente xx*/:
                //3 esta cuenta se va a usar si estamos laburando con 3ra categoria empresas
                //sino hay que usar 130113001 1069

                $cuenta110399001 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta!='terceracateg'){
                        continue;
                    }
//                    if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
            case '1051'/*130111001 Clientes*/:
                //3 esta cuenta se va a usar si estamos laburando con 3ra categoria empresas
                //sino hay que usar 130113001 1069

                //Si esta cuenta aparece es por que estoy pagando 2da categoria
                //en almenos 1 actividad tengo que buscar las ventas de esas actividades y sumar el neto
                $cuenta1069 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
                    $suma = 1;
                    $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if($categoriaDeLaVenta=='terceracateg'){
                        continue;
                    }
//                    if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                        $suma=-1;
                    }
                    $cuenta1069+=$ventasgravada[0]['total']*$suma;
                }
                if($cuenta1069<0){
                    $haber = $cuenta1069*-1;
                }else{
                    $debe = $cuenta1069;
                }
            break;
            case '1469'/*210401403 IVA Percepciones realizadas*/:
                $cuenta210401403 = 0;
                //Cargar la venta total
                foreach ($ventasgravadas as $ventasgravada) {
//                    if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
//                    if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
//                    if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
//                    if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
//                    if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                        continue;
//                    }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                    $suma = 1;
                    if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
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
        }

        //dividimos en dos switchs por que si es monotributista van las ventas a la cuenta 606050001
        //si tiene monotributo el total de las ventas van a 606050000
        //ya no importa la categoria
        if(isset($impuestosactivos['4']/*Monotributo*/)&&($impuestosactivos['4']/*Monotributo*/== true)) {
            switch ($asientoestandar['Cuenta']['id']){
                case '3144'/*606050000 Ingreso proveniente de Monotributo*/:
                    $cuenta3144 = 0;
                    //Cargar la venta total
                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
//                        if(in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuenta3144 += $ventasgravada[0]['neto'] * $suma;
                    }
                    if ($cuenta3144 < 0) {
                        $debe = $cuenta3144 * -1;
                    } else {
                        $haber = $cuenta3144;
                    }
                break;
            }
        }else{
            //Switch que me separa el neto en las distintas categorias de ganancias
            switch ($asientoestandar['Cuenta']['id']){
                /*Casos Primera Categoria*/
                case '2901'/*602010001 a)Locacion de Inmueble*/:
                case '2902'/*602010002 b)Contraprestacion recibida por derechos reales*/:
                case '2909'/*602010009 Bien de uso*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentaprimera=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoGastos=[
                        '2901'=>['48'],/*Locacion de Inmueble*/
                        '2902'=>['49'],/*Contrapresacion recibida por derechos reales*/
                        '2909'=>['50'],/*Bien de uso*/
                    ];
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
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                       $cuentaprimera+=$ventasgravada[0]['neto']*$suma;
                    }
                    if($cuentaprimera<0){
                        $debe = $cuentaprimera*-1;
                    }else{
                        $haber = $cuentaprimera;
                    }
                    break;

                case '2911'/*602020001 a)Locacion de Inmueble*/:
                case '2912'/*602020002 b)Contraprestacion recibida por derechos reales*/:
                case '2919'/*602020009 c)Bien de Uso*/:
                    $cuentasTipoGastos=[
                        '2911'=>['48'],/*Locacion de Inmueble*/
                        '2912'=>['49'],/*Contrapresacion recibida por derechos reales*/
                        '2919'=>['50'],/*Bien de uso*/
                    ];

                    //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                    //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                    $cuentaprimeraexenta = 0;
                    //Cargar la venta total

                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        if($categoriaDeLaVenta!='primeracateg'){
                            continue;
                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuentaprimeraexenta+=$ventasgravada[0]['nogravados']*$suma;
                        $cuentaprimeraexenta+=$ventasgravada[0]['exentos']*$suma;
                    }
                    if($cuentaprimeraexenta<0){
                        $debe = $cuentaprimeraexenta*-1;
                    }else{
                        $haber = $cuentaprimeraexenta;
                    }
                    break;
                /*Casos Segunda Categoria*/
                case '2923'/*603010002 b)Locacion de cosa mueble*/:
                case '2929'/*603010008 h)Ingreso por derecho llave y patentes*/:
                case '2932'/*603010012 Bien de uso*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentasegunda=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoGastos=[
                        '2923'=>['51'],/*Locacion de Cosa mueble*/
                        '2929'=>['52'],/*Ingreso por derecho llave y patentes*/
                        '2932'=>['53'],/*Bien de uso*/
                    ];
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
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentasegunda+=$ventasgravada[0]['neto']*$suma;
                    }
                    if($cuentasegunda<0){
                        $debe = $cuentasegunda*-1;
                    }else{
                        $haber = $cuentasegunda;
                    }
                    break;

                case '2934'/*603020002 b)Locacion de cosa mueble*/:
                case '2941'/*603020008 h)Ingreso por derecho llave y patentes*/:
                case '2944'/*603020012 Bien de Uso*/:
                    $cuentasTipoGastos=[
                        '2934'=>['51'],/*Locacion de Cosa mueble*/
                        '2941'=>['52'],/*Ingreso por derecho llave y patentes*/
                        '2944'=>['53'],/*Bien de uso*/
                    ];

                    //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                    //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                    $cuentasegundaexenta = 0;
                    //Cargar la venta total

                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        if($categoriaDeLaVenta!='segundacateg'){
                            continue;
                        }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuentasegundaexenta+=$ventasgravada[0]['nogravados']*$suma;
                        $cuentasegundaexenta+=$ventasgravada[0]['exentos']*$suma;
                    }
                    if($cuentasegundaexenta<0){
                        $debe = $cuentasegundaexenta*-1;
                    }else{
                        $haber = $cuentasegundaexenta;
                    }
                    break;
                /*Casos Tercera Categoria*/
                case '2888'/*601010001 Venta neta*/:
                case '2889'/*601010002 Venta Bien de uso*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentatercera=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoGastos=[
                        '2888'=>['54'],/*Venta neta*/
                        '2889'=>['55'],/*Venta Bien de uso*/
                    ];
                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        $cuentadelaactiviadad =
                            $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                        if($cuentadelaactiviadad!=$asientoestandar['Cuenta']['id']){
                            continue;
                        }
                        if($categoriaDeLaVenta!='terceracateg'){
                            continue;
                        }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentatercera+=$ventasgravada[0]['neto']*$suma;
                    }
                    if($cuentatercera<0){
                        $debe = $cuentatercera*-1;
                    }else{
                        $haber = $cuentatercera;
                    }
                    break;

                case '3368'/*601011001 Venta exenta y no gravada*/:
                case '3369'/*601011002 Venta Bien de uso*/:
                    $cuentasTipoGastos=[
                        '3368'=>['54'],/*Venta neta*/
                        '3369'=>['55'],/*Venta Bien de uso*/
                    ];

                    //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                    //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                    $cuentaterceraexenta = 0;
                    //Cargar la venta total

                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        if($categoriaDeLaVenta!='terceracateg'){
                            continue;
                        }
//                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuentaterceraexenta+=$ventasgravada[0]['nogravados']*$suma;
                        $cuentaterceraexenta+=$ventasgravada[0]['exentos']*$suma;
                    }
                    if($cuentaterceraexenta<0){
                        $debe = $cuentaterceraexenta*-1;
                    }else{
                        $haber = $cuentaterceraexenta;
                    }
                    break;
                /*Casos Tercera Auxiliar Comercio Categoria*/
                case '2948'/*604011001 Fideicomisos */:
                case '2954'/*604012001 Loteos con findes de urbanizacion*/:
                case '2960'/*604013001 Otros*/:
                case '2966'/*604014001 Profesion u oficion con explotacion*/:
                case '2972'/*604015001 Enajenacion de inmueble según ley 13512*/:
                case '2978'/*604016001 Comisionista rematador y demas auxiliares de comercio*/:
                case '3492'/*604017001 Bien de Uso*/:
                    //Si esta cuenta aparece es por que estoy pagando 3ra Auxiliar comercio categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentaterceraauxiliarcomercio=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoGastos=[
                        '2948'=>['56'],
                        '2954'=>['57'],
                        '2960'=>['58'],
                        '2966'=>['59'],
                        '2972'=>['60'],
                        '2978'=>['61'],
                        '3492'=>['62'],
                    ];
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
//                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentaterceraauxiliarcomercio+=$ventasgravada[0]['neto']*$suma;
                    }
                    if($cuentaterceraauxiliarcomercio<0){
                        $debe = $cuentaterceraauxiliarcomercio*-1;
                    }else{
                        $haber = $cuentaterceraauxiliarcomercio;
                    }
                    break;

                case '2984'/*604021001 Fideicomisos */:
                case '2991'/*604022001 Loteos con findes de urbanizacion*/:
                case '2997'/*604023001 Otros*/:
                case '3003'/*604024001 Profesion u oficion con explotacion*/:
                case '3009'/*604025001 Enajenacion de inmueble según ley 13512*/:
                case '3015'/*604026001 Comisionista rematador y demas auxiliares de comercio*/:
                case '3494'/*604027001 Bien de Uso*/:
                    $cuentasTipoGastos=[
                        '2984'=>['56'],/*Fideicomisos*/
                        '2991'=>['57'],/*Loteos con findes de urbanizacion*/
                        '2997'=>['58'],/*Otros*/
                        '3003'=>['59'],/*Profesion u oficio con explotacion*/
                        '3009'=>['60'],/*Enajenacion de inmueble segun ley 13512*/
                        '3015'=>['61'],/*Comisionista rematador y demas auxiliares de comercio*/
                        '3494'=>['62'],/*Bien de uso*/
                    ];

                    //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                    //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                    $cuentaterceraauxiliarcomercioexenta = 0;
                    //Cargar la venta total

                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        if($categoriaDeLaVenta!='terceracateg45'){
                            continue;
                        }
//                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuentaterceraauxiliarcomercioexenta+=$ventasgravada[0]['nogravados']*$suma;
                        $cuentaterceraauxiliarcomercioexenta+=$ventasgravada[0]['exentos']*$suma;
                    }
                    if($cuentaterceraauxiliarcomercioexenta<0){
                        $debe = $cuentaterceraauxiliarcomercioexenta*-1;
                    }else{
                        $haber = $cuentaterceraauxiliarcomercioexenta;
                    }
                    break;
                /*Casos Cuarta Categoria*/
                case '3047'/*605015001 Servicios personales de soc cooperativas*/:
                case '3054'/*605016002 Profesiones liberales u oficios*/:
                case '3059'/*605017001 Corredores, viajante de comercio y despachante de aduana*/:
                case '3496'/*605019001 Bien de Uso*/:
                    //Si esta cuenta aparece es por que estoy pagando 3ra Auxiliar comercio categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentacuarta=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoGastos=[
                        '3047'=>['63'],
                        '3054'=>['64'],
                        '3059'=>['65'],
                        '3496'=>['66'],
                    ];
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
//                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }

                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }

                        $cuentacuarta+=$ventasgravada[0]['neto']*$suma;
                    }
                    if($cuentacuarta<0){
                        $debe = $cuentacuarta*-1;
                    }else{
                        $haber = $cuentacuarta;
                    }
                    break;

                case '3096'/*605025001 Servicios personales de soc cooperativas*/:
                case '3102'/*605026002 Profesiones liberales u oficios*/:
                case '3108'/*605027001 Corredores, viajante de comercio y despachante de aduana*/:
                case '3498'/*605029001 Bien de Uso*/:
                    $cuentasTipoGastos=[
                        '3096'=>['63'],
                        '3102'=>['64'],
                        '3108'=>['65'],
                        '3498'=>['66'],
                    ];

                    //Si esta cuenta aparece es por que estoy pagando la parte no gravada de la 1ra categoria
                    //en almenos 1 actividad tengo que buscar las ventas no gravadas de esas actividades y sumar el neto
                    $cuentacuartaexenta = 0;
                    //Cargar la venta total

                    foreach ($ventasgravadas as $ventasgravada) {
                        $suma = 1;
                        $categoriaDeLaVenta = $ventasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        if($categoriaDeLaVenta!='cuartacateg'){
                            continue;
                        }
//                        if(!in_array($ventasgravada['Venta']['tipogasto_id'],$ingresosBienDeUso)){
//                            continue;
//                        }
//                    if($ventasgravada['Venta']['tipodebito']=='Bien de uso'){
//                        continue;
//                    }
                        $suma = 1;
                        if($ventasgravada['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $suma=-1;
                        }
                        $cuentacuartaexenta+=$ventasgravada[0]['nogravados']*$suma;
                        $cuentacuartaexenta+=$ventasgravada[0]['exentos']*$suma;
                    }
                    if($cuentacuartaexenta<0){
                        $debe = $cuentacuartaexenta*-1;
                    }else{
                        $haber = $cuentacuartaexenta;
                    }
                    break;


            }
        }
        //si el debe y el haber son 0 y el movimiento no estaba previamente guardado no tengo por que mostrar este movimiento
        if((($debe*1) != 0) || (($haber*1) != 0)||($movid!= 0)){
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
    echo $explicacionAsiento ;
    ?>
</div>
