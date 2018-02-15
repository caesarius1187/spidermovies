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
    <h3><?php echo __('Contabilizar Amortizacion:'.$cliente['Cliente']['nombre'] ); ?></h3>
    <?php
    $id = 0;
    $nombre = "Amortizacion";
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

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'amortizacion','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    $totalDebe=0;
    $totalHaber=0;
    
    //vamos a calcular las amortizaciones acumuladas y del periodo por cada bien de uso
    foreach ($bienesdeusos as $kbdu => $bienesdeuso) {
        if(isset($bienesdeuso['Bienesdeuso']['periodo'])){
            $periodoBDU = $bienesdeuso['Bienesdeuso']['periodo'];  
        }else{
            $periodoBDU = -1;
        }
        if($periodoBDU!=-1){
            $pemesBDU = substr($periodoBDU, 0, 2);
            $peanioBDU = substr($periodoBDU, 3);
        }else{
            $periodoBDU="01-1990";
            $peanioBDU="1990";
        }
        $periodoActual = date('Y', strtotime($fecha));
        $valororigen =  $bienesdeuso['Bienesdeuso']['valororiginal'];                
        $porcentajeamortizacion =  1;                

        $amortizacionacumulada =  $bienesdeuso['Bienesdeuso']['amortizacionacumulada'];
        $porcentajeamortizacion =  $bienesdeuso['Bienesdeuso']['porcentajeamortizacion'];
        $amortizacionEjercicio =  $bienesdeuso['Bienesdeuso']['importeamorteizaciondelperiodo'];
        //Debugger::dump("amort guardado en el BDU");
        //Debugger::dump($bienesdeuso['Bienesdeuso']['importeamorteizaciondelperiodo']);
        //calcular la amortizacion acumulada en funcion de la alicuota , el periodo y el valor original
        $d1 = new DateTime('01-'.$periodoBDU);
        $d2 = new DateTime($fecha);

        $interval = $d2->diff($d1);

        $aniosamortizados = $interval->format('%y')*1 + (($interval->format('%m')*1>0)?1:0);

        $topeAmortizacion = ($porcentajeamortizacion!=0)?(100/$porcentajeamortizacion):1000;

        if($aniosamortizados<$topeAmortizacion){
            if(($aniosamortizados)<=1){
                $amortizacionacumulada = 0;
            }else{
                $amortizacionacumulada = ($porcentajeamortizacion/100)*$valororigen*($aniosamortizados-1);
            }
            if(($aniosamortizados)==0){
                $amortizacionEjercicio = 0;
                //Debugger::dump("aniosamort = 0 => 0");
                //Debugger::dump($bienesdeuso['Bienesdeuso']['importeamorteizaciondelperiodo']);
            }else{
                $amortizacionEjercicio = ($porcentajeamortizacion/100)*$valororigen;
                //Debugger::dump("aniosamort < tope=>(".$porcentajeamortizacion."/100)*".$valororigen);
                //Debugger::dump($bienesdeuso['Bienesdeuso']['importeamorteizaciondelperiodo']);
            }
        }else{
            $amortizacionacumulada = $valororigen;
            $amortizacionEjercicio =  0;
            //Debugger::dump("aniosamort > tope=>0");
            //Debugger::dump($bienesdeuso['Bienesdeuso']['importeamorteizaciondelperiodo']);
        }    

        //si esta echo el asiento entonces la amortizacion acumulada tiene que restar la amortizacion del ejercicio
        //$amortizacionacumulada-=$amortizacionEjercicio;
        //$amortizacionAcumuladaAnterior-=$amortizacionEjercicio;

        //aca si esta definido la amortizacion especial
        //vamos a reemplazar todos los calculos por lo que se ha guardado
        $pemes = date('m', strtotime($fecha));
        $peanio = date('Y', strtotime($fecha));
        if(isset($bienesdeuso['Amortizacione'])){
            foreach ($bienesdeuso['Amortizacione'] as $kae => $amortespecial) {
                
                if($amortespecial['periodo']==$peanio){
                    //aca podemos estar seguros q hay una amortizacion esecial para este periodo 
                    $amortizacionEjercicio = $amortespecial['amortizacionejercicio'];
                    //Debugger::dump("alv todo tengo amort esp");
                    //Debugger::dump($amortespecial['amortizacionacumulada']);
                    $amortizacionacumulada = $amortespecial['amortizacionacumulada'];
                }else{
                     //Debugger::dump($amortespecial['periodo']."!=".$peanio);                    
                }
            }
        } 
        $bienesdeusos[$kbdu]['Bienesdeuso']['amortEjercicioCalculada']=$amortizacionEjercicio;
        $bienesdeusos[$kbdu]['Bienesdeuso']['amortAcumuladaCalculada']=$amortizacionacumulada;
        //die();
    }
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
        $title = "";
        
        
        switch ($asientoestandar['Cuenta']['id']){         
            /*Casos Primera Categoria*/
            //Debe
            case '3735'/*Inmuebles*/:
            case '3736'/*Automotor*/:
            case '3737'/*Naves, Yates y similares*/:
            case '3738'/*Aeronave*/:
            case '3739'/*Bien mueble registrable*/:
            case '3740'/*Otros bienes*/:
                //Si esta cuenta aparece es por que estoy pagando 1da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuentaprimera=0;
                //Cargar la compra neto + no gravado + exento

                $cuentasTipoInmueble=[
                    '3735'=>['Inmuebles'],
                    '3736'=>['Automotor'],
                    '3737'=>['Naves, Yates y similares'],
                    '3738'=>['Aeronave'],
                    '3739'=>['Bien mueble registrable'],
                    '3740'=>['Otros bienes'],
                ];
                $titleCuenta = "";
                foreach ($bienesdeusos as $bienesdeuso) {
                    //$categoriaDeLaCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                    //si el bien de uso tiene la compra != null o 0 o "" entonces ya se vendio no se debe amortizar.
                    if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                        continue;
                    }
                    /*if("amortizacion".$categoriaDeLaCompra!='amortizacionprimeracateg'){
                        continue;
                    }*/
                    if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                        continue;
                    }
                    $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                }
                $debe = $cuentaprimera;
                 $title = "";
                break;
            //Haber para todas las categorias menos 3ra
            case '3231'/*Inmuebles*/:
            case '3232'/*Automotor*/:
            case '3233'/*Naves, Yates y similares*/:
            case '3234'/*Aeronave*/:
            case '3778'/*Bien mueble registrable*/:
            case '3235'/*Otros bienes*/:
                //Si esta cuenta aparece es por que estoy pagando 1da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuentaprimera=0;
                //Cargar la compra neto + no gravado + exento

                $cuentasTipoInmueble=[
                    '3231'=>['Inmuebles'],
                    '3232'=>['Automotor'],
                    '3233'=>['Naves, Yates y similares'],
                    '3234'=>['Aeronave'],
                    '3778'=>['Bien mueble registrable'],
                    '3235'=>['Otros bienes'],
                ];

                foreach ($bienesdeusos as $bienesdeuso) {
                    //$categoriaDeLaCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                     if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                        continue;
                    }
                    /*if("amortizacion".$categoriaDeLaCompra=='amortizacionterceracateg'){
                        continue;
                    }*/
                    if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                        continue;
                    }
                    $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                }
                $debe = $cuentaprimera;
                break;

            /*Casos Segunda Categoria*/
           //Debe
            case '3748'/*Inmuebles*/:
            case '3749'/*Automotor*/:
            case '3750'/*Naves, Yates y similares*/:
            case '3751'/*Aeronave*/:
            case '3752'/*Bien mueble registrable*/:
            case '3753'/*Otros bienes*/:
                //Si esta cuenta aparece es por que estoy pagando 1da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuentaprimera=0;
                //Cargar la compra neto + no gravado + exento

                $cuentasTipoInmueble=[
                    '3748'=>['Inmuebles'],
                    '3749'=>['Automotor'],
                    '3750'=>['Naves, Yates y similares'],
                    '3751'=>['Aeronave'],
                    '3752'=>['Bien mueble registrable'],
                    '3753'=>['Otros bienes'],
                ];

                foreach ($bienesdeusos as $bienesdeuso) {
                   // $categoriaDeLaCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                     if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                        continue;
                    }
                    /*if("amortizacion".$categoriaDeLaCompra!='amortizacionsegundacateg'){
                        continue;
                    }*/
                    if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                        continue;
                    }
                    $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                }
                $debe = $cuentaprimera;
                break;
           
            /*Casos Tercera Otros Categoria*/
            //Debe
            case '3760'/*Inmuebles*/:
            case '3761'/*Automotor*/:
            case '3762'/*Naves, Yates y similares*/:
            case '3763'/*Aeronave*/:
            case '3764'/*Bien mueble registrable*/:
            case '3765'/*Otros bienes*/:
                //Si esta cuenta aparece es por que estoy pagando 1da categoria
                //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                $cuentaprimera=0;
                //Cargar la compra neto + no gravado + exento

                $cuentasTipoInmueble=[
                    '3760'=>['Inmuebles'],
                    '3761'=>['Automotor'],
                    '3762'=>['Naves, Yates y similares'],
                    '3763'=>['Aeronave'],
                    '3764'=>['Bien mueble registrable'],
                    '3765'=>['Otros bienes'],
                ];

                foreach ($bienesdeusos as $bienesdeuso) {
                    //$categoriaDeLaCompra = $bienesdeuso['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                     if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                        continue;
                    }
                    /*if("amortizacion".$categoriaDeLaCompra!='amortizacionterceracateg45'){
                        continue;
                    }*/
                    if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                        continue;
                    }
                    $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                }
                $debe = $cuentaprimera;
                break;
            /*Casos Cuarta Categoria*/
                //Debe
                case '3772'/*Inmuebles*/:
                case '3773'/*Automotor*/:
                case '3774'/*Naves, Yates y similares*/:
                case '3775'/*Aeronave*/:
                case '3776'/*Bien mueble registrable*/:
                case '3777'/*Otros bienes*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentaprimera=0;
                    //Cargar la compra neto + no gravado + exento

                    $cuentasTipoInmueble=[
                        '3772'=>['Inmuebles'],
                        '3773'=>['Automotor'],
                        '3774'=>['Naves, Yates y similares'],
                        '3775'=>['Aeronave'],
                        '3776'=>['Bien mueble registrable'],
                        '3777'=>['Otros bienes'],
                    ];

                    foreach ($bienesdeusos as $bienesdeuso) {
                        //$categoriaDeLaCompra = $bienesdeuso['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                            continue;
                        }
                        /*if("amortizacion".$categoriaDeLaCompra!='amortizacioncuartacateg'){
                            continue;
                        }*/
                        if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                    }
                    $debe = $cuentaprimera;
                break;
            /*Casos Tercera Empresas Categoria*/
                //Debe
                case '2293'/* 504041001 Inmuebles*/:
                case '2295'/* 504042001 Amortiz Rodados*/:
                case '2297'/* 504043001 Amoritz Instalaciones*/:
                case '2299'/* 504044001 Amortiz Mueb y Ut*/:
                case '2301'/* 504045001 Amortiz Maquinarias*/:
                case '2303'/* 504046001 Amortiz Activo Biológioco*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentaprimera=0;
                    //Cargar la compra neto + no gravado + exento
                    $cuentasTipoInmueble=[
                        '2293'=>['Inmueble'],
                        '2295'=>['Rodado'],
                        '2297'=>['Instalaciones'],
                        '2299'=>['Otros bienes de uso Muebles'],
                        '2301'=>['Otros bienes de uso Maquinas'],
                        '2303'=>['Otros bienes de uso Activos Biologicos'],
                    ];

                    foreach ($bienesdeusos as $bienesdeuso) {
                        //$categoriaDeLaCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                            continue;
                        }
                        /*if("amortizacion".$categoriaDeLaCompra!='amortizacionterceracateg'){
                            continue;
                        }*/
                        if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                    }
                    $debe = $cuentaprimera;
                break;
                //Haber
                case '773'/* 504041001 Inmuebles*/:
                case '815'/* 504042001 Amortiz Rodados*/:
                case '837'/* 504043001 Amoritz Instalaciones*/:
                case '887'/* 504044001 Amortiz Mueb y Ut*/:
                case '917'/* 504045001 Amortiz Maquinarias*/:
                case '895'/* 504046001 Amortiz Activo Biológioco*/:
                    //Si esta cuenta aparece es por que estoy pagando 1da categoria
                    //en almenos 1 actividad tengo que buscar las compras de esas actividades y sumar el neto
                    $cuentaprimera=0;
                    //Cargar la compra neto + no gravado + exento

                    $cuentasTipoInmueble=[
                        '773'=>['Inmueble'],
                        '815'=>['Rodado'],
                        '837'=>['Instalaciones'],
                        '887'=>['Otros bienes de uso Muebles'],
                        '917'=>['Otros bienes de uso Maquinas'],
                        '895'=>['Otros bienes de uso Activos Biologicos'],
                    ];

                    foreach ($bienesdeusos as $bienesdeuso) {
                        //$categoriaDeLaCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
                        if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                            continue;
                        }
                        /*if("amortizacion".$categoriaDeLaCompra!='amortizacionterceracateg'){
                            continue;
                        }*/
                        if(!in_array($bienesdeuso['Bienesdeuso']['tipo'],$cuentasTipoInmueble[$asientoestandar['Cuenta']['id']])){
                            continue;
                        }
                        $cuentaprimera+=$bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'];
                    }
                    $haber = $cuentaprimera;
                break;
        }

        //si el debe y el haber son 0 y el movimiento no estaba previamente guardado no tengo por que mostrar este movimiento
        showMovimiento($this,$debe,$haber,$movid,$i,$asiento_id,$cuentaclienteid);
        $i++;
        $totalDebe += $debe;
        $totalHaber += $haber;
    }
        
    /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
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
                showMovimiento($debe,$haber,$movid,$i,$asiento_id,$cuentaclienteid);
                $i++;
                $totalDebe += $debe;
                $totalHaber += $haber;
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
    if(count($bienesdeusos)>0){
        ?>
        <div id="modificarBienesdeuso" >
            <h3><?php echo __('Se Amortizaran los Siguientes Bienes de uso'); ?></h3>
            <?php
            echo $this->Form->create('Bienesdeuso',['class'=>'formTareaCarga formAsiento','controller'=>'Bienesdeuso','action'=>'amortizar']);
            foreach ($bienesdeusos as $c => $bienesdeuso) {
                if(!in_array($bienesdeuso['Bienesdeuso']['venta_id'],['',0,null])){
                    continue;
                }
                echo $this->Form->input('Bienesdeuso.'.$c.'.id',[
                    'type'=>'hidden',
                    'value'=> $bienesdeuso['Bienesdeuso']['id'],
                ]);
                $descripcionBDU = $bienesdeuso['Bienesdeuso']['tipo'].": ";
                //todo separar en case desc Bien de uso
                //esto seria mas correcto si lo separamos en un case
                switch ($bienesdeuso['Bienesdeuso']['tipo']){
                    //Empresa
                    case 'Rodado':
                        if($bienesdeuso['Bienesdeuso']['patente']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['patente'];
                        if($bienesdeuso['Bienesdeuso']['aniofabricacion']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['aniofabricacion'];

                        break;
                    case 'Inmueble':
                        if($bienesdeuso['Bienesdeuso']['calle']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['calle'];
                        if($bienesdeuso['Bienesdeuso']['numero']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['numero'];
                        if($bienesdeuso['Bienesdeuso']['catastro']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['catastro'];

                        break;
                    case 'Instalaciones':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];

                        break;
                    case 'Otros bienes de uso Muebles':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];

                        break;
                    case 'Otros bienes de uso Maquinas':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];

                        break;
                    case 'Otros bienes de uso Activos Biologicos':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];

                        break;
                    //NO empresa
                    case 'Inmuebles':
                        if($bienesdeuso['Bienesdeuso']['calle']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['calle'];
                        if($bienesdeuso['Bienesdeuso']['numero']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['numero'];
                        if($bienesdeuso['Bienesdeuso']['catastro']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['catastro'];
                        break;
                    case 'Automotor':
                        if($bienesdeuso['Bienesdeuso']['patente']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['patente'];
                        if($bienesdeuso['Bienesdeuso']['aniofabricacion']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['aniofabricacion'];
                        break;
                    case 'Naves, Yates y similares':
                        if($bienesdeuso['Bienesdeuso']['marca']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['marca'];
                        if($bienesdeuso['Bienesdeuso']['modelo']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['modelo'];

                        break;
                    case 'Aeronave':
                        if($bienesdeuso['Bienesdeuso']['matricula']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['matricula'];
                        if($bienesdeuso['Bienesdeuso']['fechaadquisicion']!="")
                            $descripcionBDU  .= " -".$bienesdeuso['Bienesdeuso']['fechaadquisicion'];

                        break;
                    case 'Bien mueble registrable':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];

                        break;
                    case 'Otros bienes':
                        if($bienesdeuso['Bienesdeuso']['descripcion']!="")
                            $descripcionBDU  .= $bienesdeuso['Bienesdeuso']['descripcion'];                                         
                        break;
                }                               
                ?> 
                <fieldset style="border: solid 1px #009AE1">
                    <legend align= "left" ><?php  echo $this->Form->label("",$descripcionBDU); ?> </legend>
                <?php
                echo $this->Form->label("","Amortizacion acumulada actual: "
                        .number_format($bienesdeuso['Bienesdeuso']['amortAcumuladaCalculada'], 2, ",", ".")
                        ."/ Amortizacion del periodo actual: "
                        .number_format($bienesdeuso['Bienesdeuso']['amortEjercicioCalculada'], 2, ",", ".")
                        ."/ Amortizacion acelerada del periodo actual: "
                        .number_format($bienesdeuso['Bienesdeuso']['importeamortizacionaceleradadelperiodo'], 2, ",", "."),
                        ['style'=>'font-size: 10px;']
                        );
                        $amortizacionacumulada = 
                        $bienesdeuso['Bienesdeuso']['amortAcumuladaCalculada']*1 +
                        $bienesdeuso['Bienesdeuso']['amortEjercicioCalculada']*1+
                        $bienesdeuso['Bienesdeuso']['importeamortizacionaceleradadelperiodo']*1;
                echo $this->Form->input('Bienesdeuso.'.$c.'.amortizacionacumulada',[
                    'value'=> number_format($amortizacionacumulada, 2, ".", ""),
                ]);
                $amortizaciondelperiodo = $bienesdeuso['Bienesdeuso']['amortEjercicioCalculada']*1+$bienesdeuso['Bienesdeuso']['importeamortizacionaceleradadelperiodo']*1;
                echo $this->Form->input('Bienesdeuso.'.$c.'.amortizaciondelperiodo',[
                    'value'=> number_format($amortizaciondelperiodo, 2, ".", ""),
                ])."</br>";
                ?> </fieldset><?php
            }
            echo $this->Form->end();
            ?>
        </div>
        <?php
    }
    ?>
    
    
</div>
<?php
function showMovimiento($context,$debe,$haber,$movid,$i,$asiento_id,$cuentaclienteid){
    if((($debe*1) != 0) || (($haber*1) != 0)||($movid!= 0)) {
        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['default' => $movid]);
        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', [
            'label' => ($i != 0) ? false : 'Cuenta',
            'default' => $cuentaclienteid,
            'defaultoption' => $cuentaclienteid,
            'class' => 'chosen-select-cuenta',
        ]);
        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.fecha', array(
            'type' => 'hidden',
            'label' => ($i != 0) ? false : 'Fecha',
            'readonly', 'readonly',
            'value' => date('d-m-Y'),
        ));

        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.debe', [
            'label' => ($i != 0) ? false : 'Debe',
            'class' => "inputDebe ",
            'default' => number_format($debe, 2, ".", ""),]);
        echo $context->Form->input('Asiento.0.Movimiento.' . $i . '.haber', [
            'class' => "inputHaber ",
            'label' => ($i != 0) ? false : 'Haber',
            'default' => number_format($haber, 2, ".", ""),]);
        echo "</br>";
    }
}
?>