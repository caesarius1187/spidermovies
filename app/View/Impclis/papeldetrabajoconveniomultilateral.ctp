<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajoconveniomultilateral',array('inline'=>false));
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));

$timePeriodo = strtotime("01-".$periodo ." +1 months");
$periodoNext = date("m-Y",$timePeriodo);
echo $this->Form->input('periodonext',array('value'=>$periodoNext,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('impcliid',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('impid',array('value'=>$impcli['Impcli']['impuesto_id'],'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));?>
<div id="index" class="index" style="margin-bottom:10px;">
    <div id="divLiquidarConvenioMultilateral">
    </div>
    <?php if (count($provinciasVentasDiff)!=0||count($provinciasComprasDiff)!=0){ ?>
    <div class="index">
        Las provincias dadas de alta, actualmente, son: </br><?php 
        foreach ($provinciasActivadas as $key => $value) {
             echo $this->Html->image('test-pass-icon.png',array(
                                'alt' => 'open',
                                'class' => 'btn_exit',						
                                )
            );
            echo $value."</br>";
        }
        if(count($provinciasVentasDiff)!=0){	
        ?>
            Las provincias que no estan dadas de alta, y que tienen ventas son: </br>
            <?php 
            foreach ($provinciasVentasDiff as $key => $value) {
                echo $this->Html->image('test-fail-icon.png',array(
                                'alt' => 'open',
                                'class' => 'btn_exit',						
                                )
                );
                echo $value."</br>";
            }
        }
        ?></br>
        <?php
        if(count($provinciasComprasDiff)!=0){	
        ?>
            Las provincias que no estan dadas de alta, y que tienen compras son: </br>
            <?php 
            foreach ($provinciasComprasDiff as $key => $value) {
                echo $this->Html->image('test-fail-icon.png',array(
                                'alt' => 'open',
                                'class' => 'btn_exit',						
                                )
                );
                echo $value."</br>";
            }
        }
        ?></br>
        Por favor haga click <?php echo
                                $this->Html->link("aca",
                                                    array(
                                                        'controller' => 'clientes', 
                                                        'action' => 'view', 
                                                        $impcli['Cliente']['id']),
                                                    array(
                                                            'target' => '_blank', 
                                                            'escape' => false
                                                            )
                                        ); 	
                            ?>	
                          y dé de alta las provincias listadas anteriormente para poder visualizar el informe correctamente</br>
    </div>
    <?php } else { ?>
        <?php
        $subtotalVentaxActividad = array();
        $totalGeneralVentas = 0;
        //echo json_encode($actividadclientes );
        foreach ($actividadclientes as $actividadcliente) {
            $subTotalProvincialxActividadVenta=0;
            foreach ($actividadcliente['Venta'] as $venta) {
                foreach ($impcli['Impcliprovincia'] as $impcliprovincia) {
                    if(($venta['Localidade']['partido_id']==$impcliprovincia['Partido']['id'])&&($impcliprovincia['ejercicio']!='Primero')){
                        if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                            $subTotalProvincialxActividadVenta +=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                        }else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $subTotalProvincialxActividadVenta -=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                        }
                    }
                    if (($actividadcliente['Actividade']['articulo']=='2')&&($venta['Localidade']['partido_id']==$impcliprovincia['Partido']['id'])&&($impcliprovincia['ejercicio']!='Primero')){
                        if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                            $totalGeneralVentas +=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                        }else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                            $totalGeneralVentas -=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                        }
                    }
                }
            }
            $subtotalVentaxActividad[$actividadcliente['Actividadcliente']['id']] = $subTotalProvincialxActividadVenta;
        }
        $conceptosxProvincia = array();
        foreach ($impcli['Impcliprovincia'] as $impcliprovincia) {
            $conceptosxProvincia[$impcliprovincia['Partido']['id']] = array();
            $conceptosxProvincia[$impcliprovincia['Partido']['id']]['retencion'] = 0;
            $conceptosxProvincia[$impcliprovincia['Partido']['id']]['percepionBancariaSubtotal'] = 0;
            $conceptosxProvincia[$impcliprovincia['Partido']['id']]['afavorSubtotal'] = 0;
            $conceptosxProvincia[$impcliprovincia['Partido']['id']]['otrosSubtotal'] = 0;
        }
    
        foreach ($conceptosrestantes as $conceptosrestante) {
            if(!isset($conceptosxProvincia[$conceptosrestante['Partido']['id']])){
                $conceptosxProvincia[$conceptosrestante['Partido']['id']]=array();
                $conceptosxProvincia[$conceptosrestante['Partido']['id']]['retencion'] = 0;
                $conceptosxProvincia[$conceptosrestante['Partido']['id']]['percepionBancariaSubtotal'] = 0;
                $conceptosxProvincia[$conceptosrestante['Partido']['id']]['afavorSubtotal'] = 0;
                $conceptosxProvincia[$conceptosrestante['Partido']['id']]['otrosSubtotal'] = 0;
            }
            switch ($conceptosrestante['Conceptosrestante']['conceptostipo_id']) {
                case '1'://Saldo a Favor/
                    $conceptosxProvincia[$conceptosrestante['Partido']['id']]['afavorSubtotal']+= $conceptosrestante['Conceptosrestante']['montoretenido'];
                    break;
                case '2'://Retencion
                    $conceptosxProvincia[$conceptosrestante['Partido']['id']]['retencion']+= $conceptosrestante['Conceptosrestante']['montoretenido'];
                    break;
                case '3'://Recaudacion Bancaria/
                    $conceptosxProvincia[$conceptosrestante['Partido']['id']]['percepionBancariaSubtotal']+= $conceptosrestante['Conceptosrestante']['montoretenido'];
                    break;
                case '4'://Otros/
                    $conceptosxProvincia[$conceptosrestante['Partido']['id']]['otrosSubtotal']+= $conceptosrestante['Conceptosrestante']['montoretenido'];
                    break;
                default:
                    break;
            }
        }
        ?>
        <div class="index" style="overflow:scroll;">
            <?php
            echo $this->Form->input('cantBaseRealJurisdiccionTD',array('value'=>count($actividadclientes)+1,'type'=>'hidden'));
            ?>
            <b style="display: inline">Papel de Trabajo</b>
            <?php echo $this->Form->button('Imprimir',
                array('type' => 'button',
                    'class' =>"btn_imprimir",
                    'onClick' => "imprimir()"
                )
            );?>
            <?php  echo $this->Form->button('Excel',
                array('type' => 'button',
                    'id'=>"clickExcel",
                    'class' =>"btn_imprimir",
                )
            );?>
            <table class="tbl_tareas aimprimir" style="border-collapse: collapse;" id="pdtconveniomultilateral">
                <tr id="1">
                    <td colspan="3"> </td>
                    <?php
                    //Si es Actividad Economica no distribuye base por articulo en las provincias, entonces toda esta zona esta demás
                    if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                        ?>
                        <td id="baseRealJurisdiccionTDTitle" colspan="<?php echo count($actividadclientes) + 1; ?>">
                        <span style="color:deepskyblue" onclick="showhideBaseRealJurisdiccion();">
                            Base real  <!--por jurisdiccion-->
                        </span>
                        </td>
                        <td rowspan="3">Coef.</td>
                        <td id="prorrateoPorAplicacionArticuloTDTitle"
                            colspan="<?php echo count($actividadclientes) + 1; ?>">
                        <span style="color:deepskyblue" onclick="showhideProrrateoPorAplicacionArticulo();"
                              title="Prorrateo a la Jurisdicción Sede por aplicación de regímenes especiales Art 6, 7, 10, 11 y 12 del Convenio">
                            Prorrateo a la Sede
                        </span>
                        </td>
                        <?php
                    }
                    $cuadrosPorActividad = 3;
                    if($impcli['Impcli']['impuesto_id']==21/*Actividades Economicas*/){
                        //elimino dos cuadro por que no voy a mostrar el minimo ni la comparacion de minimo con impuesto
                        $cuadrosPorActividad++;
                        $cuadrosPorActividad++;
                    }
                    echo $this->Form->input('cantBaseProrrateadaActividadTD',array('value'=>$cuadrosPorActividad,'type'=>'hidden'));
                    echo $this->Form->input('cantBaseProrrateadaTD',array('value'=>count($actividadclientes)*$cuadrosPorActividad+1,'type'=>'hidden'));
                    ?>
                    <td id="baseImponibleProrrateadaTDTitle" colspan="<?php echo count($actividadclientes)*$cuadrosPorActividad+1; ?>">
                       <span style="color:deepskyblue" onclick="showhideBaseImponibleProrrateada();"  >
                           <?php
                           if($impcli['Impcli']['impuesto_id']==21/*Actividades Economicas*/){
                                echo "Bases Imponibles";
                           }else{
                               echo "Bases Imponibles Prorrateadas";
                           }?>
                       </span>
                    </td>
                    <td colspan="6">Conceptos que restan</td>
                    <td colspan="1">Conceptos que suman</td>
                    <td colspan="6">A Favor</td>
                </tr>
                <tr id="2">
                    <td rowspan="2">Codigo</td>
                    <td rowspan="2">Provincia</td>
                    <td rowspan="2">Ejercicio</td>
                    <?php
                    if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/){
                        foreach ($actividadclientes as $actividadcliente) { ?>
                        <td class="baseRealJurisdiccion cursor" title="<?php echo $actividadcliente['Actividade']['nombre']."-". $actividadcliente['Actividadcliente']['descripcion'] ?>"><?php echo $actividadcliente['Actividade']['descripcion']; ?></td>
                        <?php }
                        ?>
                        <td rowspan="2">Total</td>
                        <?php
                        foreach ($actividadclientes as $actividadcliente) { ?>
                            <td class="prorrateoPorAplicacionArticulo cursor" title="<?php echo $actividadcliente['Actividade']['nombre']."-". $actividadcliente['Actividadcliente']['descripcion'] ?>"><?php echo $actividadcliente['Actividade']['descripcion']; ?></td>
                        <?php }
                        ?>
                        <td rowspan="2">Total</td>
    
                    <?php
                    }
                    foreach ($actividadclientes as $actividadcliente) { ?>
                        <td class="baseImponibleProrrateadaActividad" colspan="<?php echo $cuadrosPorActividad; ?>">
                            <label class="lbl_trunc"><?php echo $actividadcliente['Actividade']['descripcion']." - ".$actividadcliente['Actividade']['nombre']."-". $actividadcliente['Actividadcliente']['descripcion']; ?></label>
                        </td>
                    <?php }
                    ?>
                    <td rowspan="2">Total</td>
                    <td rowspan="2">Reten.</td>
                    <td rowspan="2">Percep.</td>
                    <td rowspan="2" title="Percepción Bancaria" style="width: 45px;">Percep.Ban.</td>
                    <td rowspan="2">Otros</td>
                    <td rowspan="2" title="A favor del contribuyente del periodo anterior">A favor</td>
                    <td rowspan="2">Total</td>
                    <td rowspan="2">Adicionales</td>
                    <td rowspan="2">Fisco</td>
                    <td rowspan="2">Contrib.</td>
                </tr>
                <tr id="3">
    
                    <?php
                    if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                        foreach ($actividadclientes as $actividadcliente) { ?>
                            <td class="baseRealJurisdiccion"><?php echo $actividadcliente['Actividade']['articulo']; ?></td>
                        <?php }
                        foreach ($actividadclientes as $actividadcliente) { ?>
                            <td class="prorrateoPorAplicacionArticulo"><?php echo $actividadcliente['Actividade']['articulo']; ?></td>
                        <?php }
                    }
                    foreach ($actividadclientes as $actividadcliente) { ?>
                        <td class="baseImponibleProrrateada" title="Base Prorrateada">Base</td>
                        <td class="baseImponibleProrrateada" title="Alicuota">%</td>
                        <?php if($impcli['Impcli']['impuesto_id']==21){ ?>
                            <td class="baseImponibleProrrateada">Impuesto</td>
                            <td class="baseImponibleProrrateada" title="Minimo Imputable">Min</td>
                         <?php } ?>
                        <td title="Impuesto Determinado">Impuesto</td>
                    <?php }?>
                </tr>
                <?php
                $liquidacionProvincia = array();
                $totalGeneralBaseImponibleProrrateada = 0;
                $totalGeneralRetenciones = 0;
                $totalGeneralPercepciones = 0;
                $totalGeneralPercepcionesBancarias = 0;
                $totalGeneralOtros = 0;
                $totalGeneralTotalRetenciones = 0;
                $totalGeneralTotalAdicionales = 0;
                $totalGeneralAPagar = 0;
                $totalGeneralAFavor = 0;
                $totalGeneralAFavorDelContribuyente = 0;
                $pagadoDuranteElAño = 0;
                $minimoACargado = 0;
    
                foreach ($impcli['Impcliprovincia'] as $impcliprovincia) { ?>
                <tr>
                    <td>
                        <?php echo $impcliprovincia['Partido']['codigo']; ?>
                    </td>
                    <td>
                        <?php echo $impcliprovincia['Partido']['nombre']; ?>
                    </td>
                    <td>
                        <?php echo $impcliprovincia['ejercicio']; ?>
                    </td>
                    <?php
                        $subTotalProvincialVenta = 0;
                        $liquidacionActividad = array();
                        foreach ($actividadclientes as $actividadcliente) {
                            $subTotalProvincialxActividadVenta = 0;
                            $subTotalProvincialxActividadVentaBienDeUso = 0;
                            foreach ($actividadcliente['Venta'] as $venta) {
                                if($venta['Localidade']['partido_id']==$impcliprovincia['Partido']['id']){
                                    if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                                        $subTotalProvincialxActividadVenta +=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                                    }else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
                                        $subTotalProvincialxActividadVenta -=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                                    }else {
                                        $subTotalProvincialxActividadVentaBienDeUso +=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadeseconomicas'];
                                    }
                                }
                            }
                            $subTotalProvincialVenta += $subTotalProvincialxActividadVenta;
                            $liquidacionActividad[$actividadcliente['Actividadcliente']['id']] = $subTotalProvincialxActividadVenta;
                            if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                                ?>
                            <td class="baseRealJurisdiccion"><?php echo number_format($subTotalProvincialxActividadVenta, 2, ",", ".") ?></td>
                        <?php }
                        }
                        $liquidacionProvincia[$impcliprovincia['id']."-TotalVentaxActividad"] = $liquidacionActividad;

                    if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                        ?>
                        <td><?php echo number_format($subTotalProvincialVenta, 2, ",", "."); ?></td>
                        <td><!-- Coeficiente -->
                            <?php echo $impcliprovincia['coeficiente']; ?>
                        </td>
                        <?php
                    }
                    //=SI(O(E$8=7;E$8=10;E$8=11;E$8=12);E9*80%;SI(E$8=6;E9*90%;SI(E$8=9;E9;0))) 
                    $subTotalProrrateoPorAplicacionArticulo  = 0;
                    $liquidacionActividadProrrateada = array();
                    $i=0;
                    foreach ($actividadclientes as $actividadcliente) {
                        $prorrateoPorAplicacionArticulo = 0;
                        $subtotalProvinciaxActividad = $liquidacionProvincia[$impcliprovincia['id']."-TotalVentaxActividad"][$actividadcliente['Actividadcliente']['id']];
                        switch ($actividadcliente['Actividade']['articulo']) {
                            case 2:
                            break;
                            case 6:
                            //E9*90%

                            $prorrateoPorAplicacionArticulo = $subtotalProvinciaxActividad * 0.90;
                            case 9:
                            //SI(Q8=9;E9;0)
                            $prorrateoPorAplicacionArticulo = $subtotalProvinciaxActividad;
                            break;
                            case 7:
                            case 8:
                            case 10:
                            case 11:
                            case 12:
                            //O(E$8=7;E$8=10;E$8=11;E$8=12);E9*80%
                            $prorrateoPorAplicacionArticulo = $subtotalProvinciaxActividad * 0.80;
                            break;
                            default:
                            //0
                            break;
                        }
                        $liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']]=$prorrateoPorAplicacionArticulo;
                        $subTotalProrrateoPorAplicacionArticulo  += $prorrateoPorAplicacionArticulo;
                        $i++;
                        if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                            ?>
                            <td class="prorrateoPorAplicacionArticulo">
                                <!-- Total Prorrateo por Provincia por actividad por aplicacion de articulo -->
                                <?php echo number_format($prorrateoPorAplicacionArticulo, 2, ",", "."); ?>
                            </td>
                            <?php
                        }
                    }
                    $liquidacionProvincia[$impcliprovincia['id']."-TotalVentaxActividadProrrateada"]=
                        $liquidacionActividadProrrateada;
                    if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                        ?>
                        <td><!-- Total Prorrateo por Provincia  por aplicacion de articulo-->
                            <?php echo number_format($subTotalProrrateoPorAplicacionArticulo, 2, ",", "."); ?>
                        </td>
                        <?php
                    }
                    $subTotalBaseImponibleProrrateada = array();
                    /*Recorremos actividades para calcular Bases Prorrateadas de la provincia que estamos recorriendo*/
                    foreach ($actividadclientes as $actividadcliente) {
                        $baseProrrateada = 0;
                        //este switch me aplica regimen especial o regimen comun para las bases segun las actividades pero esto no se debe hacer si
                        // estamos haciendo Actividades Economicas, solo si es Convenio
                        $title="Base Prorrateada";
                        if($impcli['Impcli']['impuesto_id']==21){/*Actividades Economicas*/
                            $baseProrrateada = $liquidacionActividad[$actividadcliente['Actividadcliente']['id']];
                            $title = "Base no prorrateada por articulo";
                        }else {
                            switch ($actividadcliente['Actividade']['articulo']) {
                                case 2/*Regimen General*/
                                :
                                    if ($impcliprovincia['ejercicio'] == 'Resto'/*No es primer ejercicio*/) {
                                        //Total De Ventas en la actividad multiplicado por el coeficiente de la provincia
                                        $baseProrrateada = $subtotalVentaxActividad[$actividadcliente['Actividadcliente']['id']] * $impcliprovincia['coeficiente'];
                                        $title = "Articulo 2, Ejercicio resto :".$subtotalVentaxActividad[$actividadcliente['Actividadcliente']['id']]."*".$impcliprovincia['coeficiente'];;
                                    } else if ($impcliprovincia['ejercicio'] == 'Primero') {
                                        $title = "Articulo distinto de 2, Ejercicio primero :"
                                            .$liquidacionActividad[$actividadcliente['Actividadcliente']['id']];
                                        $baseProrrateada = $liquidacionActividad[$actividadcliente['Actividadcliente']['id']];
                                    } else {
                                        $title = "Articulo distinto de 2, Ejercicio ultimo :".
                                            $liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']];
                                        $baseProrrateada = $liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']];
                                    }
                                    break;
                                case 7:
                                case 10:
                                case 11:
                                case 12:
                                    if ($impcliprovincia['sede'] == 1/*Es Sede*/) {
                                        $title = "Articulo 12, Sede :(".$liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']]."/ 0.8) * 0.2";
                                            $baseProrrateada = ($liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']] / 0.8) * 0.2;
                                    }
                                    break;
                                case 6:
                                    if ($impcliprovincia['ejercicio'] == 'Primero') {
                                        $baseProrrateada = $liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']] ;
                                        $title = "Articulo 6, Primer Ejercicio : ".$liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']];
                                    }else{
                                        if ($impcliprovincia['sede'] == 1/*Es Sede*/) {
                                            $title = "Articulo 6, Sede :(" . $liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']] . "/ 0.9) * 0.1";
                                            $baseProrrateada = ($liquidacionActividadProrrateada[$actividadcliente['Actividadcliente']['id']] / 0.9) * 0.1;
                                        } else {

                                        }
                                    }
                                    break;
                                case 9:

                                    $baseProrrateada = $liquidacionActividad[$actividadcliente['Actividadcliente']['id']];
                                    $title = "Articulo 9:".$liquidacionActividad[$actividadcliente['Actividadcliente']['id']];
                                    break;
                                default:
                                    $title = "Base = 0 por no aplicar en ningun articulo";
                                    $baseProrrateada = 0;
                                    break;
                            }
                        }
                        ?>
                    <td title="<?php echo $title ?>" class="baseImponibleProrrateada" id="<?php echo $impcliprovincia['id']."-baseimponible".$actividadcliente['Actividadcliente']['id'] ?>">
                        <!-- Base Prorrateada Bases Imponibles Prorrateadas -->
                        <span style="color:#0C0">
                        <?php echo number_format($baseProrrateada, 2, ",", ".");
                        $liquidacionProvincia[$impcliprovincia['id']."-baseimponible"][$actividadcliente['Actividadcliente']['id']]=$baseProrrateada;
                        echo $this->Form->input('baseProrrateada'.$impcliprovincia['id'].'actividadclienteid'.$actividadcliente['Actividadcliente']['id'] , array(
                            'type'=>'hidden','value'=>$baseProrrateada,));
                         ?>
                        </span>
                    </td>
                    <?php
                    $alicuotaAMostrar = 0 ;
                    $minimoAMostrar = 0 ;
                        if(!isset($liquidacionProvincia[$impcliprovincia['id']."-alicuotaAMostrar"][$actividadcliente['Actividadcliente']['id']])){
                    $liquidacionProvincia[$impcliprovincia['id']."-alicuotaAMostrar"][$actividadcliente['Actividadcliente']['id']]= 0;
                    $liquidacionProvincia[$impcliprovincia['id']."-minimoAMostrar"][$actividadcliente['Actividadcliente']['id']]= 0;
                    $liquidacionProvincia[$impcliprovincia['id']."-impuestoDeterminado"][$actividadcliente['Actividadcliente']['id']]= 0;
                    $liquidacionProvincia[$impcliprovincia['id']."-impuestoAMostrar"][$actividadcliente['Actividadcliente']['id']]= 0;
                        }
                    foreach ($actividadcliente['Encuadrealicuota'] as $encuadrealicuota) {
                        if(!isset($subTotalBaseImponibleProrrateada[$actividadcliente['Actividade']['articulo']])){
                            $subTotalBaseImponibleProrrateada[$actividadcliente['Actividade']['articulo']] = 0;
                        }
                        if($impcliprovincia['id']==$encuadrealicuota['impcliprovincia_id']){
                            $alicuotaAMostrar = $encuadrealicuota['alicuota'];
                            if($impcli['Impcli']['impuesto_id']==21){
                                $minimoAMostrar = $encuadrealicuota['minimo'];
                            }
                        }
                    }
                    //ya tenemos el minimo que vamos a mostrar(si existe, ahora vamos a comparar con lo ya cargado en el año para esta provincia
                    foreach ($eventosimpuestos as $evenimp){
                        if($evenimp['Eventosimpuesto']['partido_id']== $impcliprovincia['partido_id']){
                            $minimoACargado = $minimoAMostrar*12;
                            $pagadoDuranteElAño += $evenimp['0']['montovto']*1;
                            if(($evenimp['0']['montovto']*1)>($minimoAMostrar*12)){
                                $minimoAMostrar=0;
                            }
                        }
                    }
                        ?>
                    <td class="baseImponibleProrrateada" ><!-- Alicuota Bases Imponibles Prorrateadas -->
                        <?php
                            echo number_format($alicuotaAMostrar, 2, ",", ".");
                            $liquidacionProvincia[$impcliprovincia['id']."-alicuotaAMostrar"][$actividadcliente['Actividadcliente']['id']]+= $alicuotaAMostrar;
                        ?>
                    </td>
                    <?php
                        $impuestoAMostrar = $baseProrrateada*$alicuotaAMostrar/100;
                        $liquidacionProvincia[$impcliprovincia['id']."-impuestoAMostrar"][$actividadcliente['Actividadcliente']['id']]+=$impuestoAMostrar;
                        $liquidacionProvincia[$impcliprovincia['id']."-minimoAMostrar"][$actividadcliente['Actividadcliente']['id']]+= $minimoAMostrar;
                        if($impcli['Impcli']['impuesto_id']==21){ ?>
                        <td class="baseImponibleProrrateada"><!-- Impuesto Bases Imponibles Prorrateadas -->
                            <?php
                            echo number_format($impuestoAMostrar, 2, ",", ".");
                            ?>
                        </td>
                        <td class="baseImponibleProrrateada"><!-- Minimo Bases Imponibles Prorrateadas -->
                            <?php
                            echo number_format($minimoAMostrar, 2, ",", ".");
                            $liquidacionProvincia[$impcliprovincia['id']."-minimoAMostrar"][$actividadcliente['Actividadcliente']['id']]+= $minimoAMostrar;
                            ?>
                        </td>
                    <?php } ?>
                    <td ><!-- Impuesto Determinado Bases Imponibles Prorrateadas -->
                            <?php
                            //=SI(AD10<AE10;AE10;AD10)
                            $impuestoDeterminado = 0;
                            if($minimoAMostrar<$impuestoAMostrar){
                                $impuestoDeterminado = $impuestoAMostrar;
                            }else{
                                $impuestoDeterminado = $minimoAMostrar;
                            }
                            echo '<span style="color:red">'.number_format($impuestoDeterminado, 2, ",", ".")."</span>";
                            $liquidacionProvincia[$impcliprovincia['id']."-impuestoDeterminado"][$actividadcliente['Actividadcliente']['id']]+= $impuestoDeterminado;
                            $actividadArticulo=$actividadcliente['Actividade']['articulo'];
                            if(!isset($subTotalBaseImponibleProrrateada[$actividadcliente['Actividade']['articulo']])){
                                $subTotalBaseImponibleProrrateada[$actividadArticulo] = 0;
                            }
                            $subTotalBaseImponibleProrrateada[$actividadArticulo] +=$impuestoDeterminado;
                            ?>
                    </td>
                        <?php
                    }
                    ?>
                    <td><!-- Total Bases Imponibles Prorrateadas -->
                        <?php
                        $totalBaseImponibleProrrateada = 0;
                        foreach ($subTotalBaseImponibleProrrateada as $value) {
                            $totalBaseImponibleProrrateada += $value;
                        }
                        $liquidacionProvincia[$impcliprovincia['id'].'TotalBaseImponibleProrrateada'] = $subTotalBaseImponibleProrrateada;
                        echo number_format($totalBaseImponibleProrrateada, 2, ",", "."); ;
                        $totalGeneralBaseImponibleProrrateada += $totalBaseImponibleProrrateada ;

                        ?>
                    </td>
                    <?php //Calculos 
                        $retencionSubtotal=0;
                        $afavorSubtotal=0;
                        $percepionSubtotal=0;
                        $percepionBancariaSubtotal=0;
                        $otrosSubtotal=0;
                        $retencionSubtotal=$conceptosxProvincia[$impcliprovincia['Partido']['id']]['retencion'];

                        $percepionBancariaSubtotal= $conceptosxProvincia[$impcliprovincia['Partido']['id']]['percepionBancariaSubtotal'];
                        $titleTotalPercepcionesEnMovimientosBancarios="";
                        $TotalPercepcionesEnMovimientosBancarios = 0;
                        if($impcli['Impcli']['impuesto_id']==21){
                            //si estamos en actividades varias vamos a sumar a las percepciones bancarias los movimientos bancariios
                            // de la cuenta 317   110404298   I.I.B.B. - Percepciones Bancarias
                            //que buscamos

                            if(count($cuentascliente)>0){
                                foreach ($cuentascliente[0]['Movimientosbancario'] as $movimientosbancario) {
                                    $TotalPercepcionesEnMovimientosBancarios+=$movimientosbancario['debito'];
                                }
                            }
                            //este cartel tiene que mostrar por periodo el monto
                            //por que puede venir acumulado de varios periodos
                            //todo Acumular Rec. Bancarias de varios periodos
                            $titleTotalPercepcionesEnMovimientosBancarios = "Se han traido $".$TotalPercepcionesEnMovimientosBancarios.
                                " de los Movimientos Bancarios cargados en la cuenta 110404298   I.I.B.B. - Percepciones Bancarias en el periodo";
                        }

                        //solo vamos a aplicar el procedimiento de movimientos bancarios a partir de AGOSTO de 2017 para evitar
                        //duplicaciones de rec bancarias en las liquidaciones.
                        //EN EL ESTUDIO MANJON SOLAMENTE
                        if($impcli['Cliente']['Grupocliente']['estudio_id']=='2'/*Estudio Manjon*/){
                            $timePeriodoactual = "01-".$periodo;
                            $d = new DateTime( $timePeriodoactual );
                            $inicioDeAplicacion = new DateTime( "01-07-2017" );
                            if($d < $inicioDeAplicacion){
                                $titleTotalPercepcionesEnMovimientosBancarios = "No se tuvo en cuenta $".$TotalPercepcionesEnMovimientosBancarios.
                                    " de los Movimientos Bancarios cargados en la cuenta 110404298   I.I.B.B. - Percepciones Bancarias en el periodo";
                                $TotalPercepcionesEnMovimientosBancarios = 0;
                            }
                        }

                        $percepionBancariaSubtotal += $TotalPercepcionesEnMovimientosBancarios;
                        $afavorSubtotal=$conceptosxProvincia[$impcliprovincia['Partido']['id']]['afavorSubtotal'];
                        $otrosSubtotal=$conceptosxProvincia[$impcliprovincia['Partido']['id']]['otrosSubtotal'];
                        foreach ($actividadclientes as $actividadcliente) {
                            foreach ($actividadcliente['Compra'] as $compra) {
                                if($impcliprovincia['Partido']['id']==$compra['Localidade']['partido_id']){
                                    if($compra['tipocredito']=='Credito Fiscal'){
                                        $percepionSubtotal+=$compra['iibbpercep'];

                                    }else if($compra['tipocredito']=='Restitucion credito fiscal'){
                                        $percepionSubtotal-=$compra['iibbpercep'];
                                    }
                                }
                            }
                        }
                    ?>
                    <td><!-- Retencion -->
                        <?php
                        echo number_format($retencionSubtotal, 2, ",", ".");
                        $totalGeneralRetenciones += $retencionSubtotal;
                        if($impcli['Impcli']['impuesto_id']==21){
                            echo $this->Form->input('totalretenciones', array(
                                'type'=>'hidden',
                                'value'=>number_format($retencionSubtotal, 2, ".", "")));
                        }
                        ?>
                    </td>
                    <td><!-- Percepcion -->
                        <?php
                        echo number_format($percepionSubtotal, 2, ",", ".");
                        $totalGeneralPercepciones += $percepionSubtotal;
                        ?>
                    </td>
                    <td title="<?php echo $titleTotalPercepcionesEnMovimientosBancarios?>"><!-- Percepcion Bancaria -->
                        <?php

                        echo number_format($percepionBancariaSubtotal, 2, ",", ".");
                        $totalGeneralPercepcionesBancarias += $percepionBancariaSubtotal;

                        //si la provincia el tucuman voy a mostrar aca un total de percepciones de tucuman
                        if($impcliprovincia['Partido']['id']=='3'/*Tucuman*/){
                            echo $this->Form->input('totalpercepcionestucuman', array(
                                'type' => 'hidden',
                                'value' => number_format($percepionBancariaSubtotal, 2, ".", "")));
                        }

                        if($impcli['Impcli']['impuesto_id']==21) {
                            echo $this->Form->input('totalpercepciones', array(
                                'type' => 'hidden',
                                'value' => number_format($totalGeneralPercepciones, 2, ".", "")));
                            echo $this->Form->input('totalpercepcionesbancarias', array(
                                'type' => 'hidden',
                                'value' => number_format($totalGeneralPercepcionesBancarias, 2, ".", "")));
                            echo $this->Form->input('pagosACuentaTotal', array(
                                'type' => 'hidden',
                                'value' => number_format($otrosSubtotal, 2, ".", "")));
                        }
                        ?>
                    </td>
                    <td><!-- Otros -->
                        <?php
                            echo number_format($otrosSubtotal, 2, ",", ".");
                            $totalGeneralOtros += $otrosSubtotal;
                        ?>
                    </td>
                    <td><!-- A Favor Periodo Anterior -->
                        <?php
                            echo number_format($afavorSubtotal, 2, ",", ".");
                            if($impcli['Impcli']['impuesto_id']==21) {
                                echo $this->Form->input('totalAFavor', array(
                                    'type' => 'hidden',
                                    'value' => number_format($afavorSubtotal, 2, ".", "")));
                            }
                            $totalGeneralAFavor += $afavorSubtotal;
                        ?>
                    </td>
                    <td><!-- Total -->
                        <?php
                            $subTotalRetenciones = $retencionSubtotal+$percepionSubtotal+$percepionBancariaSubtotal+$otrosSubtotal+$afavorSubtotal;
                            echo number_format($subTotalRetenciones, 2, ",", ".");
                            $totalGeneralTotalRetenciones += $subTotalRetenciones;
                        ?>
                    </td>
                    <td><!-- Adicionales -->
                        <?php
                        $adicional = 0;
                        if($impcliprovincia['Partido']['id']==7){//Cordoba
                            $adicional = $totalBaseImponibleProrrateada*0.05;
                        }
                        if($impcliprovincia['Partido']['id']==9){//Chaco
                            $adicional = $totalBaseImponibleProrrateada*0.1;
                        }
                        if($impcliprovincia['Partido']['id']==19){//San Juan
                            $adicional = $totalBaseImponibleProrrateada*0.2;
                        }
                        $totalBaseImponibleProrrateada = $totalBaseImponibleProrrateada + $adicional;
                        echo number_format($adicional, 2, ",", ".");
                        $totalGeneralTotalAdicionales += $adicional;
    
                        /*Si es Actividades Economicas este es el valor que va a contener el valor de la cuenta 506210001*/
                        if($impcli['Impcli']['impuesto_id']==21){
                            echo $this->Form->input('impuestoDeterminadoTotal', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralBaseImponibleProrrateada, 2, ".", "")));
                        }
    
                        ?>
                    </td>
                    <td><!-- A Pagar -->
                        <?php 
                        $totalDefinitivo = $totalBaseImponibleProrrateada - $subTotalRetenciones; 
                        if($totalDefinitivo<=0){
                            echo number_format(0, 2, ",", ".");;
                            echo $this->Form->input('apagar'.$impcliprovincia['Partido']['id'], array('type'=>'hidden','value'=>0));
                            if($impcli['Impcli']['impuesto_id']==21){
                                //si es act econo esto va a aparecer una sola vez y es el campo que tengo que llevar a la cuenta 110404301
                                echo $this->Form->input('totalgeneralapagar', array('type'=>'hidden','value'=>0));
                            }
                        }else{
                            echo number_format($totalDefinitivo, 2, ",", ".");
                            $totalGeneralAPagar+= $totalDefinitivo;
                            echo $this->Form->input('apagar'.$impcliprovincia['Partido']['id'], array('type'=>'hidden','value'=>$totalDefinitivo));
                            if($impcli['Impcli']['impuesto_id']==21){
                                //si es act econo esto va a aparecer una sola vez y es el campo que tengo que llevar a la cuenta 110404301
                                echo $this->Form->input('totalgeneralapagar', array(
                                    'type'=>'hidden',
                                    'value'=>number_format($totalGeneralAPagar, 2, ".", "")));
                            }
                        }
    
                        ?>
                    </td>
                    <td><!-- A Favor Del Contribuyente -->
                        <?php 
                        if($totalDefinitivo>=0){
                            echo number_format(0, 2, ",", ".");;
                            echo $this->Form->input('afavor'.$impcliprovincia['Partido']['id'], array('type'=>'hidden','value'=>0));
                            if($impcli['Impcli']['impuesto_id']==21){
                                //si es act econo esto va a aparecer una sola vez y es el campo que tengo que llevar a la cuenta 110404301
                                echo $this->Form->input('saldoAFavorTotal', array('type'=>'hidden','value'=>0));
                            }
                        }else{
                            echo number_format($totalDefinitivo*-1, 2, ",", ".");
                            $totalGeneralAFavorDelContribuyente+= $totalDefinitivo*-1;
                            echo $this->Form->input('afavor'.$impcliprovincia['Partido']['id'], array(
                                'type'=>'hidden',
                                'value'=>number_format($totalDefinitivo, 2, ".", "")));
                            if($impcli['Impcli']['impuesto_id']==21){
                                //si es act econo esto va a aparecer una sola vez y es el campo que tengo que llevar a la cuenta 110404301
                                echo $this->Form->input('saldoAFavorTotal', array(
                                    'type'=>'hidden',
                                    'value'=>number_format(($totalDefinitivo*-1), 2, ".", "")));
                            }
                        }
                        echo $this->Form->input('afavorPartido'.$impcliprovincia['Partido']['id'], array(
                            'type'=>'hidden',
                            'value'=>$impcliprovincia['Partido']['nombre']));
                        ?>
                    </td>
                </tr>
                <?php 
                }
                //Estos Subtotales y totales son innecesarios para Actividad Economica por que se desarrolla en solo 1 provincia
                if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilareral*/) {
                            ?>
                    <tr id="Tot No Distribuye Base Art 2">
                        <td colspan="3">No Distribuye Base Art 2</td>
                            <?php
                            $subtotalesBaseReal = array();
                            $subtotalesProrrateados = array();
                            $subtotalDistribuyeBaseArt2 = 0;
                            $subtotalNoDistribuyeBaseArt2 = 0;
                            $subtotalOtrosArticulos = 0;
                            $subtotalNoDistribuyeBaseArt2Prorrateados = 0;
                            $subtotalDistribuyeBaseArt2Prorrateados = 0;
                            foreach ($actividadclientes as $actividadcliente) {
                                $actividadclienteid = $actividadcliente['Actividadcliente']['id'];
                                $subtotalesBaseReal[$actividadclienteid.'totalOtrosArticulos'] = 0;
                                $subtotalesBaseReal[$actividadclienteid.'totalDistribuyeBaseArt2'] = 0;
                                $subtotalesBaseReal[$actividadclienteid.'totalNoDistribuyeBaseArt2'] = 0;
                                $subtotalesProrrateados[$actividadclienteid.'totalNoDistribuyeBaseArt2'] = 0;
                                $subtotalesProrrateados[$actividadclienteid.'totalDistribuyeBaseArt2'] = 0;
                                $coeficienteTotal=0;
                                foreach ($impcli['Impcliprovincia'] as $impcliprovincia) {
                                    // Calculo de Base Real
                                    $subtotalProvinciaxActividad= $liquidacionProvincia[$impcliprovincia['id']."-TotalVentaxActividad"][$actividadclienteid];
    
                                    if($impcliprovincia['ejercicio']=='Primero'&&$actividadcliente['Actividade']['articulo']==2){
                                        $subtotalesBaseReal[$actividadclienteid.'totalNoDistribuyeBaseArt2'] += $subtotalProvinciaxActividad;
                                    }else if($impcliprovincia['ejercicio']=='Resto'&&$actividadcliente['Actividade']['articulo']==2){
                                        $subtotalesBaseReal[$actividadclienteid.'totalDistribuyeBaseArt2'] += $subtotalProvinciaxActividad;
                                    }
                                    if($actividadcliente['Actividade']['articulo']!=2){
                                        $subtotalesBaseReal[$actividadclienteid.'totalOtrosArticulos'] += $subtotalProvinciaxActividad;
                                    }
                                    //Calculo Coeficiente
                                    $coeficienteTotal+=$impcliprovincia['coeficiente'];
                                    //Calculo de Base Prorrateada
                                    $subtotalesProrrateados[$actividadclienteid.'totalNoDistribuyeBaseArt2'] += $subtotalProvinciaxActividad;
                                    if($actividadcliente['Actividade']['articulo']!=2){
                                        $subtotalesProrrateados[$actividadclienteid.'totalDistribuyeBaseArt2'] += $subtotalProvinciaxActividad;
                                    }
                                }
                                ?>
                        <td class="baseRealJurisdiccion">
                            <?php
                            echo number_format($subtotalesBaseReal[$actividadclienteid.'totalNoDistribuyeBaseArt2']*-1, 2, ",", ".");
                            $subtotalNoDistribuyeBaseArt2 += $subtotalesBaseReal[$actividadclienteid.'totalNoDistribuyeBaseArt2'];
                            ?>
                        </td><?php } ?>
                        <td>
                            <?php echo number_format($subtotalNoDistribuyeBaseArt2, 2, ",", "."); ?>
                        </td>
                        <td rowspan="4">
                            <?php echo number_format($coeficienteTotal, 4, ",", "."); ?>
                        </td>
                        <?php
                        foreach ($actividadclientes as $actividadcliente) {
                        ?>
                        <td class="prorrateoPorAplicacionArticulo">
                                <?php
                                echo number_format($subtotalesProrrateados[$actividadcliente['Actividadcliente']['id'].'totalNoDistribuyeBaseArt2'], 2, ",", ".");
                                $subtotalNoDistribuyeBaseArt2Prorrateados += $subtotalesProrrateados[$actividadcliente['Actividadcliente']['id'].'totalNoDistribuyeBaseArt2'];
                                ?>
                        </td>
                            <?php
                        }
                        ?>
                        <td>
                            <?php echo number_format($subtotalNoDistribuyeBaseArt2Prorrateados, 2, ",", ".");?>
                        </td>
                        <?php
                        foreach ($actividadclientes as $actividadcliente) {
                            $totalalicuota = 0;
                            $totalminimoAMostrar = 0;
                            $totalimpuestoDeterminado = 0;
                            $totalimpuestoAMostrar = 0;
                            $totalbaseimponible = 0;
                            foreach ($impcli['Impcliprovincia'] as $impcliprovincia) {
                                $totalbaseimponible += $liquidacionProvincia[$impcliprovincia['id']."-baseimponible"][$actividadcliente['Actividadcliente']['id']];
                                $totalalicuota += $liquidacionProvincia[$impcliprovincia['id']."-alicuotaAMostrar"][$actividadcliente['Actividadcliente']['id']];
                                $totalminimoAMostrar += $liquidacionProvincia[$impcliprovincia['id']."-minimoAMostrar"][$actividadcliente['Actividadcliente']['id']];
                                $totalimpuestoAMostrar += $liquidacionProvincia[$impcliprovincia['id']."-impuestoAMostrar"][$actividadcliente['Actividadcliente']['id']];
                                $totalimpuestoDeterminado += $liquidacionProvincia[$impcliprovincia['id']."-impuestoDeterminado"][$actividadcliente['Actividadcliente']['id']];
                            }
                        ?>
                        <td class="baseImponibleProrrateada">
                            <?php
                                echo number_format($totalbaseimponible, 2, ",", ".");
                            ?>
                        </td>
                        <td class="baseImponibleProrrateada">
                            <?php
                                echo number_format($totalalicuota, 2, ",", ".");
                            ?>
                        </td>
    
                        <?php
                        if($impcli['Impcli']['impuesto_id']==21){  ?>
                            <td class="baseImponibleProrrateada">
                                <?php
                                echo number_format($totalimpuestoAMostrar, 2, ",", ".");
                                ?>
                            </td>
                            <td class="baseImponibleProrrateada">
                                <?php
                                    echo number_format($totalminimoAMostrar, 2, ",", ".");
                                ?>
                            </td>
                        <?php } ?>
                        <td>
                            <?php
                                echo number_format($totalimpuestoDeterminado, 2, ",", ".");
                            ?>
                        </td>
                            <?php
                        }
                        ?>
                        <td>
                            <?php echo number_format($totalGeneralBaseImponibleProrrateada, 2, ",", ".");
                            //Si es Convenio Este es el campo que me va a mostrar el valor de la cuenta 506210001
                            echo $this->Form->input('impuestoDeterminadoTotal', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralBaseImponibleProrrateada+$totalGeneralTotalAdicionales, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralRetenciones, 2, ",", ".");
                            echo $this->Form->input('totalretenciones', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralRetenciones, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo number_format($totalGeneralPercepciones, 2, ",", ".");
                            echo $this->Form->input('totalpercepciones', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralPercepciones, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralPercepcionesBancarias, 2, ",", ".");
                            echo $this->Form->input('totalpercepcionesbancarias', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralPercepcionesBancarias, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo number_format($totalGeneralOtros, 2, ",", ".");
                            echo $this->Form->input('pagosACuentaTotal', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralOtros, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralAFavor, 2, ",", ".");
                            echo $this->Form->input('totalAFavor', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralAFavor, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralTotalRetenciones, 2, ",", ".");?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralTotalAdicionales, 2, ",", ".");?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralAPagar, 2, ",", ".");
                            echo $this->Form->input('totalgeneralapagar', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralAPagar, 2, ".", "")));
                            ?>
                        </td>
                        <td>
                            <?php echo number_format($totalGeneralAFavorDelContribuyente, 2, ",", ".");
                            echo $this->Form->input('saldoAFavorTotal', array(
                                'type'=>'hidden',
                                'value'=>number_format($totalGeneralAFavorDelContribuyente, 2, ".", "")));
                            ?>
                        </td>
                    </tr>
                    <tr id="Total Otros Art.">
                        <td colspan="3">Total Otros Art.</td>
                        <?php
                            foreach ($actividadclientes as $actividadcliente) {
                            ?>
                            <td  class="baseRealJurisdiccion">
                                <?php
                                echo number_format($subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalOtrosArticulos'], 2, ",", ".");
                                $subtotalOtrosArticulos += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalOtrosArticulos'];
                                ?>
                            </td>
                                <?php
                            }
                        ?>
                        <td>
                            <?php echo number_format($subtotalOtrosArticulos, 2, ",", ".");?>
                        </td>
                        <td class="prorrateoPorAplicacionArticulo" colspan="<?php echo count($actividadclientes); ?>"></td>
                    </tr>
                    <tr id="Total Que Distribuye Base">
                        <td colspan="3">Total Que Distribuye Base</td>
                        <?php
                        foreach ($actividadclientes as $actividadcliente) {
                        ?>
                        <td  class="baseRealJurisdiccion">
                            <?php
                            echo number_format($subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2'], 2, ",", ".");
                            $subtotalDistribuyeBaseArt2 += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2'];
                            ?>
                        </td>
                        <?php
                        }
                        ?>
                        <td>
                            <?php echo number_format($subtotalDistribuyeBaseArt2, 2, ",", "."); ?>
                        </td>
                        <?php
                        foreach ($actividadclientes as $actividadcliente) {
                        ?>
                        <td class="prorrateoPorAplicacionArticulo">
                            <?php
                            echo number_format($subtotalesProrrateados[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2'], 2, ",", ".");
                            $subtotalDistribuyeBaseArt2Prorrateados += $subtotalesProrrateados[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2'];
                            ?>
                        </td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr id="Total General">
                    <td colspan="3">Total General</td>
                    <?php
                    $totalGeneral = 0;
                    foreach ($actividadclientes as $actividadcliente) { 
                    ?>
                    <td  class="baseRealJurisdiccion">
                        <?php 
                        $subtotalGeneral = 0;
                        $subtotalGeneral += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2']; 
                        $subtotalGeneral += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalOtrosArticulos']; 
                        $subtotalGeneral += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalNoDistribuyeBaseArt2']; 
                        echo number_format($subtotalGeneral, 2, ",", ".");
                        $totalGeneral += $subtotalGeneral;
                        ?>
                    </td>
                    <?php
                    }
                    ?>
                    <td>
                        <?php echo number_format($totalGeneral, 2, ",", "."); ?>
                    </td>
                    <?php
                    foreach ($actividadclientes as $actividadcliente) { 
                    ?>
                    <td class="prorrateoPorAplicacionArticulo">
                        <?php 
                        $subtotalGeneralProrrateado = 0;
                        $subtotalGeneralProrrateado += $subtotalesProrrateados[$actividadcliente['Actividadcliente']['id'].'totalDistribuyeBaseArt2']; 
                        $subtotalGeneralProrrateado += $subtotalesBaseReal[$actividadcliente['Actividadcliente']['id'].'totalNoDistribuyeBaseArt2']; 
                        echo number_format($subtotalGeneralProrrateado, 2, ",", ".");
                        ?>
                    </td>
                    <?php
                    }
                    ?>	
                </tr>
                <?php
                } ?>
            </table>
        <?php
        if($impcli['Impcli']['impuesto_id']==21) {/*Actividades Economicas*/
        echo "Lo pagado durante el año fue $".number_format($pagadoDuranteElAño, 2, ",", ".").", y el minimo es $".number_format($minimoACargado, 2, ",", ".");
        }
        //echo json_encode($liquidacionProvincia);
        ?>	
        </div>
    
        <?php
        if(!$impuestosactivos['contabiliza']){ ?>
            <div id="divContenedorContabilidad" style="margin-top:10px">  </div>
        <?php
        }else{ ?>
            <div id="divContenedorContabilidad" style="margin-top:10px;width:100%;">
                <div style="width:100%;">
                    <b>Asiento de Devengamiento</b>
                    <?php
                    $Asientoid=0;
                    $movId=[];
                    if(isset($impcli['Asiento'])){
                        if(count($impcli['Asiento'])>0) {
                            $Asientoid = $impcli['Asiento'][0]['id'];
                            foreach ($impcli['Asiento'][0]['Movimiento'] as $mimovimiento) {
                                $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                            }
                        }
                    }
                    //ahora vamos a reccorer las cuentas relacionadas al IVA y las vamos a cargar en un formulario de Asiento nuevo
                    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','controller'=>'asientos','action'=>'add']);
                    echo $this->Form->input('Asiento.0.id',['value'=>$Asientoid]);
                    $d = new DateTime( '01-'.$periodo );
                    echo $this->Form->input('Asiento.0.fecha',array(
                        'class'=>'datepicker',
                        'type'=>'text',
                        'label'=>array(
                            'text'=>"Fecha:",
                        ),
                        'readonly','readonly',
                        'value'=>$d->format( 't-m-Y' ),
//                        'div' => false,
                        'style'=> 'width:82px'
                    ));
                    echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento Act. Economicas" ,'style'=>'width:250px']);
                    echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
                    echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
                    $i=0;
                    foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandarCM) {
                        if(!isset($movId[$asientoestandarCM['cuenta_id']])){
                            $movId[$asientoestandarCM['cuenta_id']]=0;
                        }
                        $cuentaclienteid=0;
                        $cuentaclientenombre="0";
                        foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaCm){
                            if($cuentaclientaCm['cuenta_id']==$asientoestandarCM['cuenta_id']){
                                $cuentaclienteid=$cuentaclientaCm['id'];
                                $cuentaclientenombre=$cuentaclientaCm['nombre'];
                                break;
                            }
                        }
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarCM['cuenta_id']],]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                            'type'=>'hidden',
                            'readonly','readonly',
                            'value'=>date('d-m-Y'),
                        ));
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarCM['cuenta_id'],'id'=>'cuenta'.$asientoestandarCM['cuenta_id']]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>false,'readonly'=>'readonly','value'=>$asientoestandarCM['Cuenta']['numero'],'style'=>'width:82px']);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>false,'readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                            'label'=>false,
                            'value'=>0,
                            'class'=>"inputDebe "
                        ]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                'label'=>false,
                                'value'=>0,
                                'class'=>"inputHaber "
                            ])."</br>";
                        $i++;
                    }
                    echo $this->Form->submit('Contabilizar',['style'=>'display:none']);
                    echo $this->Form->end();
                    $totalDebe=0;
                    $totalHaber=0;
                    echo $this->Form->label('','Total ',[
                        'style'=>"display: -webkit-inline-box;width:355px;"
                    ]);
                    ?>
                    <div style="width:98px;">
                        <?php
                        echo $this->Form->label('lblTotalDebe',
                            "$".number_format($totalDebe, 2, ".", ""),
                            [
                                'id'=>'lblTotalDebe',
                                'style'=>"display: inline;float:right"
                            ]
                        );
                        ?>
                    </div>
                    <div style="width:124px;">
                        <?php
                        echo $this->Form->label('lblTotalHaber',
                            "$".number_format($totalHaber, 2, ".", ""),
                            [
                                'id'=>'lblTotalHaber',
                                'style'=>"display: inline;float:right"
                            ]
                        );
                        ?>
                    </div>
                    <?php
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
            </div>
    <?php
        }
    } ?>
</div>
