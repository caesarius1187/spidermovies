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
    <h3><?php echo __('Contabilizar Compras:'.$cliente['Cliente']['nombre'] ); ?></h3>
    <?php
    $id = 0;
    $nombre = "Compra";
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
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'compras','type'=>'hidden']);
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
        //Este switch controla todas las cuentas que hay en "compras" obligadamente
        switch ($asientoestandar['Cuenta']['id']){
            /*Casos comun a todas las compras*/
            case '286'/*110403401 IVA Credito Fiscal*/:
                $cuenta110403401 = 0;
                //Cargar la compra ivapercep
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110403401+=$comprasgravada[0]['iva']*$suma;
                }
                $debe = $cuenta110403401;
                break;
            case '288'/*110403403 IVA - Percepciones*/:
                $cuenta110403403 = 0;
                //Cargar la compra ivapercep
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110403403+=$comprasgravada[0]['ivapercep']*$suma;
                }
                $debe = $cuenta110403403;
                break;
            case '2330'/*504990001 Impuestos Internos*/:
                $cuenta504990001 = 0;
                //Cargar la compra impinternos
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta504990001+=$comprasgravada[0]['impinternos']*$suma;
                }
                $debe = $cuenta504990001;
                break;
            case '300'/*110403804 Imp Combustible*/:
                $cuenta110403804 = 0;
                //Cargar la compra impcombustible
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110403804+=$comprasgravada[0]['impcombustible']*$suma;
                }
                $debe = $cuenta110403804;
                break;
            case '333'/*110405102 Act. Vs. - Percepciones*/:
                $cuenta110405102 = 0;
                //Cargar la compra actvspercep
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110405102+=$comprasgravada[0]['actvspercep']*$suma;
                }
                $debe = $cuenta110405102;
                break;
            case '316'/*110404201 IIBB Percepciones*/:
                $cuenta110404201 = 0;
                //Cargar la compra actvspercep
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110404201+=$comprasgravada[0]['iibbpercep']*$suma;
                }
                $debe = $cuenta110404201;
                break;
            case '1202'/*110499001 Proveedor "XXX" */:
                $cuenta110499001 = 0;
                //Cargar la compra ivapercep
                foreach ($comprasgravadas as $comprasgravada) {
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    $suma = 1;
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta110499001+=$comprasgravada[0]['total']*$suma;
                }
                $haber = $cuenta110499001;
                break;
            /*Casos Primera Categoria*/
            case '2645'/*510001001 Gastos de mantenimiento del inmueble*/:
                //Si esta cuenta aparece es por que estoy pagando 1ra categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuenta510001001 = 0;
                //Cargar la compra neto + no gravado + exento
                foreach ($comprasgravadas as $comprasgravada) {
                    $suma = 1;
                    $categoriaDeLaCompra = $comprasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if("compra".$categoriaDeLaCompra!='compraprimeracateg'){
                        continue;
                    }
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta510001001+=$comprasgravada[0]['neto']*1+$comprasgravada[0]['nogravados']*1+$comprasgravada[0]['exentos']*$suma;
                }
                $debe = $cuenta510001001;
                break;
            /*Casos Segunda Categoria*/
            case '2650'/*511001101 Gasto 1*/:
                //Si esta cuenta aparece es por que estoy pagando 2da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuenta510001001=0;
                //Cargar la compra neto + no gravado + exento
                foreach ($comprasgravadas as $comprasgravada) {
                    $suma = 1;
                    $categoriaDeLaCompra = $comprasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if("compra".$categoriaDeLaCompra!='comprasegundacateg'){
                        continue;
                    }
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta510001001+=$comprasgravada[0]['neto']*1+$comprasgravada[0]['nogravados']*1+$comprasgravada[0]['exentos']*$suma;
                }
                $debe = $cuenta510001001;
                break;
            /*Casos Tercera Empresas Categoria*/
            case '2222'/*501000001 Compras*/:
            case '2286'/*504010001 Combus, Lubric y FM*/:
            case '2288'/*504020001 Luz, Gas, Tel. y Otros*/:
            case '2290'/*504030001 Alquileres y Expensas*/:
            case '2305'/*504050001 Gastos de Traslados*/:
            case '2307'/*504060001 Seguros*/:
            case '2309'/*504070001 Gtos Cort y Homenaje*/:
            case '2311'/*504080001 Manten, Repar etc*/:
            case '2313'/*504090001 Gastos de Librería*/:
            case '2315'/*504100001 Gastos Varios*/:
            case '2334'/*504990005 Honorarios*/:
            case '2336'/*504990007 Publicidad y Propaganda*/:
            case '2345'/*504990016 Otros*/:
                //Si esta cuenta aparece es por que estoy pagando 2da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuenta504070001=0;
                //Cargar la compra neto + no gravado + exento
                $cuentasTipoGastos=[
                    '2222'=>['1'],/*Mercaderia-Insumos*/
                    '2286'=>['2'],/*Combustibles, Lubric y FM*/
                    '2288'=>['3','19','20','21'],/*Servicios Publicos*/
                    /*Luz*/
                    /*Agua*/
                    /*Alquiler*/
                    /*Bien de uso*/
                    '2290'=>['4'],/*Expensas*/
                    '2305'=>['5'],/*Traslados*/
                    '2307'=>['6'],/*Seguros*/
                    '2309'=>['7'],/*Gastos de Cortesia y Homenaje*/
                    '2311'=>['8'],/*Mantenimiento, Reparacion*/
                    '2313'=>['9'],/*Libreria*/
                    '2315'=>['10'],/*Gastos Varios*/
                    '2336'=>['11'],/*Publicidad y Propaganda*/
                    '2334'=>['12'],/*Honorarios*/
                    '2345'=>['18']/*Otros*/
                ];

            foreach ($comprasgravadas as $comprasgravada) {
                    $suma = 1;
                    $categoriaDeLaCompra = $comprasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if("compra".$categoriaDeLaCompra!='compraterceracateg'){
                        continue;
                    }
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    if(!in_array($comprasgravada['Compra']['tipogasto_id'],$cuentasTipoGastos[$asientoestandar['Cuenta']['id']])){
                        continue;
                    }
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta504070001+=$comprasgravada[0]['neto']*$suma+$comprasgravada[0]['nogravados']*$suma+$comprasgravada[0]['exentos']*$suma;
                }
                $debe = $cuenta504070001;
                break;
            /*Casos Tercera Otros Categoria*/
            case '3371'/*505010000 Gtos. Financ. de Act. Operativ*/:
                //Si esta cuenta aparece es por que estoy pagando 3da categoria otros
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuenta505010000=0;
                //Cargar la compra neto + no gravado + exento
                foreach ($comprasgravadas as $comprasgravada) {
                    $suma = 1;
                    $categoriaDeLaCompra = $comprasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if("compra".$categoriaDeLaCompra!='compraterceracateg45'){
                        continue;
                    }
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta505010000+=$comprasgravada[0]['neto']*$suma+$comprasgravada[0]['nogravados']*$suma+$comprasgravada[0]['exentos']*$suma;
                }
                $debe = $cuenta505010000;
                break;
            /*Casos Cuarta Categoria*/
            case '2768'/*513000001 Que implican erogacion de fondos*/:
                //Si esta cuenta aparece es por que estoy pagando 3da categoria otros
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuenta513000001=0;
                //Cargar la compra neto + no gravado + exento
                foreach ($comprasgravadas as $comprasgravada) {
                    $suma = 1;
                    $categoriaDeLaCompra = $comprasgravada['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    if("compra".$categoriaDeLaCompra!='compracuartacateg'){
                        continue;
                    }
                    if($comprasgravada['Compra']['imputacion']=='Bs Uso'){
                        continue;
                    }
                    if($comprasgravada['Compra']['tipocredito']=='Restitucion credito fiscal'){
                        $suma=-1;
                    }
                    $cuenta513000001+=$comprasgravada[0]['neto']*$suma+$comprasgravada[0]['nogravados']*$suma+$comprasgravada[0]['exentos']*$suma;
                }
                $debe = $cuenta513000001;
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
            'label'=>($i!=0)?false:'Fecha',
            'readonly','readonly',
            'value'=>date('d-m-Y'),
        ));
        $movimientoConValor = "movimientoSinValor";
        if(($debe*1) != 0 || ($haber*1) != 0){
            $movimientoConValor = "movimientoConValor";
        }
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
            'label'=>($i!=0)?false:'Debe',
            'class'=>"inputDebe ".$movimientoConValor,
            'default'=>number_format($debe, 2, ".", ""),]);
        $totalDebe+=$debe;
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
            'class'=>"inputHaber ".$movimientoConValor,
            'label'=>($i!=0)?false:'Haber',
            'default'=>number_format($haber, 2, ".", ""),]);
        $totalHaber +=$haber;
        echo "</br>";
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
                    'class'=>"inputDebe ".$movimientoConValor,
                    'label'=>($i!=0)?false:'Debe',
                    'default'=>number_format($debe, 2, ".", ""),]);
                $totalDebe+=$debe;
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', [
                    'label'=>($i!=0)?false:'Haber',
                    'class'=>"inputHaber ".$movimientoConValor,
                    'default'=>number_format($haber, 2, ".", ""),]);
                $totalHaber +=$haber;
                echo "</br>";
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
    echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:70px;"]);
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
