<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('asientos/crearapertura',array('inline'=>false));


/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016|
 * Time: 12:15 PM
 */
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
    $fechaInicioConsultaSiguiente =  date('d-m-Y', strtotime($fechaInicioConsulta." + 1 Years"));
?>
<div class="index" style="padding: 0px 1%; margin-bottom: 11px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:11px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);
        ?>
    </div>
    <div style="width:25%; float: left;padding-top:11px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo]);
        echo $this->Form->input('fechaInicioConsultaSiguiente',['type'=>'hidden','value'=>$fechaInicioConsultaSiguiente]);
        echo $this->Form->input('fechaInicioConsulta',['type'=>'hidden','value'=>$fechaInicioConsulta]);
        echo $this->Form->input('fechaFinConsulta',['type'=>'hidden','value'=>date('d-m-Y', strtotime($fechaFinConsulta))]);
        ?>
    </div>    
</div>
<div style="width:100%; height:30px; margin-left: 11px;"  class="Formhead noExl" id="divTabs" >
    <div id="tabSumasYSaldos" class="cliente_view_tab_active" onclick="CambiarTab('sumasysaldos');" style="width:14%;display:none">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Balance de Sumas y Saldos</label>
    </div>
    <div id="tabAsientoApertura" class="cliente_view_tab" onclick="CambiarTab('asientoapertura');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Asiento de Apertura</label>
    </div>    
</div>
<?php
if(count($cliente['Asiento'])>0){
    ?>
    <div class="index" id="" style="">
        Ya se ha creado un asiento de apertura para este periodo. Si desea volver a generarlo, elimine el actual y recargue esta pagina.
    </div>
    <?php
}else{
?>
<div class="index estadocontable" id="divContenedorBSyS" style="display:none">
    <?php
    echo "<h3>Balance de Sumas y Saldos del periodo  ".date('d-m-Y', strtotime($fechaInicioConsulta))." hasta ".date('d-m-Y', strtotime($fechaFinConsulta))."</h3>";
    ?>
    <table id="tblsys"  class="toExcelTable tbl_border tblEstadoContable splitForPrint" cellspacing="0">
        <thead>
            <tr class="trnoclickeable">
                <td>N&uacute;mero</td>
                <td>Cuenta</td>
                <td>Apertura</td>
                <td>Saldo Anterior</td>
            </tr>
        </thead>
        <tbody>
        <?php
        //$arrayTotales=[];
        $arrayCuentasxPeriodos=[];/*En este array vamos a guardar los valores de cada cuenta
        con su periodo(asociado el valor al numero de cuenta)*/        
        foreach ($cuentasclientes as $kc => $cuentascliente){
            $numerodecuenta = $cuentascliente['Cuenta']['numero'];
            //si no hay movimientos para esta cuentacliente no la vamos a mostrar en el suma y saldos
            if((count($cuentascliente['Movimiento'])==0)&&(!in_array($cuentascliente['Cuentascliente']['cuenta_id'], ['2212']))){
                continue;
            }
            $saldoCalculado = 0;
            $arrayPeriodos = [];
            $periodoAImputar = date('Y', strtotime($fechaInicioConsulta));

            if(!isset($arrayPeriodos[$periodoAImputar])){
                $arrayPeriodos[$periodoAImputar]=[];
                $arrayPeriodos[$periodoAImputar]['debes']=0;
                $arrayPeriodos[$periodoAImputar]['haberes']=0;
                $arrayPeriodos[$periodoAImputar]['apertura']=0;
            }
            if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                $arrayCuentasxPeriodos[$numerodecuenta] = [];
                $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['Cuentascliente']['nombre'];
                $arrayCuentasxPeriodos[$numerodecuenta]['cuentaclienteid'] = $cuentascliente['Cuentascliente']['id'];
                $arrayCuentasxPeriodos[$numerodecuenta]['cuentaid'] = $cuentascliente['Cuentascliente']['cuenta_id'];
                $arrayCuentasxPeriodos[$periodoAImputar] = 0;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'] = [];
            }       
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                if($movimiento['Asiento']['tipoasiento']=='Apertura'){
                    $arrayPeriodos[$periodoAImputar]['apertura']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['apertura']-=round($movimiento['haber'], 2);
                }else{
                    //$arrayPeriodos[$periodoAImputar]['movimiento']+=round($movimiento['debe'], 2);
                    //$arrayPeriodos[$periodoAImputar]['movimiento']-=round($movimiento['haber'], 2);
                }
                $arrayPeriodos[$periodoAImputar]['debes']+=round($movimiento['debe'], 2);
                $arrayPeriodos[$periodoAImputar]['haberes']+=round($movimiento['haber'], 2);
                $saldoCalculado += round($movimiento['debe'], 2);
                $saldoCalculado -= round($movimiento['haber'], 2);
            }
            //Saldos de cuentas esperados
            //1 +
            //2 -
            //3 +
            //4 +/-
            //5 +
            //6 -
            //Blanco saldo esperado
            //Naranja saldo fuera de contexto
            $charinicial = substr($cuentascliente['Cuenta']['numero'], 0, 1);
            $colorTR = "";
            $saldoCalculado = round($saldoCalculado, 2);
            switch ($charinicial){
                case "1":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
                case "2":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;    
                case "3":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
                case "4":
                break;
                case "5":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                     $arrayPeriodos[$periodoActual]['debes']=0;
                     $arrayPeriodos[$periodoActual]['haberes']=0;
                    $saldoCalculado = 0;
                    
                    break;
                
                case "6":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                     $arrayPeriodos[$periodoActual]['debes']=0;
                     $arrayPeriodos[$periodoActual]['haberes']=0;
                    $saldoCalculado = 0;
                    break;
            }            
            ?>
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['Cuentascliente']['id']?>" style="background-color: <?php echo $colorTR?>">
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['Cuentascliente']['nombre']; ?>
                </td>
                <?php
                if(!isset($arrayPeriodos[$periodoActual])){
                    $arrayPeriodos[$periodoActual]=[];
                    $arrayPeriodos[$periodoActual]['debes']=0;
                    $arrayPeriodos[$periodoActual]['haberes']=0;
                    $arrayPeriodos[$periodoActual]['apertura']=0;
                }
                $saldo = $arrayPeriodos[$periodoActual]['debes']-$arrayPeriodos[$periodoActual]['haberes'];
                $apertura = $arrayPeriodos[$periodoActual]['apertura'];
                echo '<td  class="numericTD">'.
                    number_format($apertura, 2, ",", ".")
                    ."</td>";
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual])){
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual] = 0;
                }
                $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=$saldo;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual]=$arrayPeriodos[$periodoActual]['apertura'];
                ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr class="trnoclickeable">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="index estadocontable" id="divAsientoApertura">
    <h3><?php echo __('Asiento de Apertura a crear'); ?></h3>
    <?php
    
    $id = 0;
    $nombre = "Asiento de Apertura";
    $descripcion = "Manual";
    $fecha = date('01-01-Y');
    //esta fecha debe ser en funcion del periodo en el que estamos consultando
    
    $miAsiento=array();
    
    if(isset($asientoyacargado['Asiento'])){
        $miAsiento = $asientoyacargado;
        $id = $miAsiento['Asiento']['id'];
        $nombre = $miAsiento['Asiento']['nombre'];
        $descripcion = $miAsiento['Asiento']['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['Asiento']['fecha']));
    }
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    $totalDebe=0;
    $totalHaber=0;
    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliente['Cliente']['id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'Apertura','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
     foreach ($arrayCuentasxPeriodos as $kc => $cuentacliente) {
        $key=$kc;
        $movid= 0;
        $asiento_id= $id;
        $debe=($cuentacliente[$periodoActual]>0)?$cuentacliente[$periodoActual]:0;
        $haber=($cuentacliente[$periodoActual]<0)?$cuentacliente[$periodoActual]*-1:0;
        $cuentaclienteid=$cuentacliente["cuentaclienteid"];
        $cuentaid=$cuentacliente["cuentaid"];
        if(($debe==0&&$haber==0)&&(!array_key_exists($cuentaid, $cuentasdeapertura)))
                continue;
        //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
        //echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.key',['default'=>$key]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha',['default'=>$fecha,'type'=>'hidden']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
            'default'=>$cuentaclienteid,
            'options'=>$allcuentasclientes,
            'class'=>'chosen-select-cuenta',
            'label' => ($i != 0) ? false : 'Cuenta',
                ]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',[
            'readonly'=>'readonly',
            'type'=>'hidden',
            'orden'=>$i,
            //'value'=>$asientoestandaractvs['cuenta_id'],
            'label' => ($i != 0) ? false : 'Cuenta',
            'id'=>'cuenta'.$cuentaid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
            'default'=>number_format($debe, 2, ".", ""),
            'class'=>'inputDebe',
            'label' => ($i != 0) ? false : 'Debe',
            ]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
            'default'=>number_format($haber, 2, ".", ""),
            'class'=>'inputHaber',
            'label' => ($i != 0) ? false : 'Haber',
            ]);
        echo "</br>";
        $i++;
        $totalDebe+=$debe;
        $totalHaber+=$haber;
        //if($i>30)die();
    }
    echo $this->Form->end("Guardar");
    
    ?>
    <div id="asientofooter" style="width:500px">
        <?php 
        echo  $this->Form->label('','Total ',[
        'style'=>"display: -webkit-inline-box;width:355px;"
        ]);?>
            <?php
            echo $this->Form->label('lblTotalDebe',
                number_format($totalDebe, 2, ".", ""),
                [
                    'id'=>'lblTotalDebe',
                    'style'=>"display: inline;"
                ]
            );
            ?>
            <?php
            echo $this->Form->label('lblTotalHaber',
                number_format($totalHaber, 2, ".", ""),
                [
                    'id'=>'lblTotalHaber',
                    'style'=>"display: inline;margin-left: 20px;"
                ]
            );
            ?>
        <?php
        if(number_format($totalDebe, 2, ".", "")==number_format($totalHaber, 2, ".", "")){
                echo $this->Html->image('test-pass-icon.png',array(
                        'id' => 'iconDebeHaber',
                        'alt' => 'open',
                        'class' => 'btn_exit',
                        'style' => 'float:right',
                        'title' => 'Debe igual al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                    )
                );
        }else{
                echo $this->Html->image('test-fail-icon.png',array(
                        'id' => 'iconDebeHaber',
                        'alt' => 'open',
                        'class' => 'btn_exit',
                        'style' => 'float:right',
                        'title' => 'Debe distinto al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                    )
                );
        }
        ?>
    </div>
    
</div>
<?php
}
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--                    <span aria-hidden="true">&times;</span>-->
                </button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!--                <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


