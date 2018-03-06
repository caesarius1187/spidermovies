<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('papelesdetrabajos/bienespersonales',array('inline'=>false));


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
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:11px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo]);
        echo $this->Form->input('fechaInicioConsultaSiguiente',['type'=>'hidden','value'=>$fechaInicioConsultaSiguiente]);
        echo $this->Form->input('fechaInicioConsulta',['type'=>'hidden','value'=>$fechaInicioConsulta]);
        echo $this->Form->input('fechaFinConsulta',['type'=>'hidden','value'=>date('d-m-Y', strtotime($fechaFinConsulta))]);
        ?>
    </div>
    <div style="float:right; width:45%">
        <?php                       
        echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );
        ?>
        <div style="display: none">
            <?php
            echo $this->Form->create('cuentascliente',['id'=>'plandecuentasForm','action' => 'plancuentas']); ?>
            <?php
            echo $this->Form->input('clis', array(
                //'multiple' => 'multiple',
                'type' => 'hidden',
                'value' => $cliente["Cliente"]['id'],
                'class'=>'btn_imprimir',
                'label' => false,
            )); ?>
            <?php echo $this->Form->end(__('Plan de cuentas')); ?>
        </div>
        <?php
        echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );
        ?>
    </div>
</div>
<div style="width:100%; height:30px; margin-left: 11px;"  class="Formhead noExl" id="divTabs" >
    <div id="tabSumasYSaldos" class="cliente_view_tab_active" onclick="CambiarTab('sumasysaldos');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Balance de Sumas y Saldos</label>
    </div>
    <div id="tabPrimeraCategoria" class="cliente_view_tab" onclick="CambiarTab('primeracategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">1&#176;,2&#176;,3&#176;,3&#176;EU,4&#176; Cat.</label>
    </div>    
</div>
<div class="index estadocontable" id="divContenedorBSyS" >
    <?php
    echo "<h3>Balance de Sumas y Saldos del periodo  ".date('d-m-Y', strtotime($fechaInicioConsulta))." hasta ".date('d-m-Y', strtotime($fechaFinConsulta))."</h3>";
    ?>
    <table id="tblsys"  class="toExcelTable tbl_border tblEstadoContable splitForPrint" cellspacing="0">
        <thead>
            <tr class="trnoclickeable">
                <td>N&uacute;mero</td>
                <td>Cuenta</td>
                <td>Saldo Actual</td>
                <td>Saldo Bienes Personales</td>
                <td>Saldo Exento</td>
            </tr>
        </thead>
        <tbody>
        <?php
        //$arrayTotales=[];
        $arrayCuentasxPeriodos=[];/*En este array vamos a guardar los valores de cada cuenta
        con su periodo(asociado el valor al numero de cuenta)*/        
        foreach ($cuentasclientes as $kc => $cuentascliente){
            $numerodecuenta = $cuentascliente['Cuenta']['numero'];
            $periodoAImputar = date('Y', strtotime($fechaInicioConsulta));

            //si no hay movimientos para esta cuentacliente no la vamos a mostrar en el suma y saldos
            if(count($cuentascliente['Movimiento'])==0){
                continue;
            }
            $saldoCalculado = 0;
            $arrayPeriodos = [];
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                    $arrayCuentasxPeriodos[$numerodecuenta] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['Cuentascliente']['nombre'];
                    $arrayCuentasxPeriodos[$periodoAImputar] = 0;
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
                    break;
                
                case "6":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
            }            
            if(!isset($arrayPeriodos[$periodoActual])){
                $arrayPeriodos[$periodoActual]=[];
                $arrayPeriodos[$periodoActual]['debes']=0;
                $arrayPeriodos[$periodoActual]['haberes']=0;
            }
            $saldo = $arrayPeriodos[$periodoActual]['debes']-$arrayPeriodos[$periodoActual]['haberes'];
            $bienespersonales =$saldo;
            $exento = 0;
            if(count($cuentascliente['Bienespersonale'])>0){
                if($cuentascliente['Bienespersonale'][0]['periodo']==$periodo){
                    $bienespersonales = $cuentascliente['Bienespersonale'][0]['monto'];
                    $exento = $cuentascliente['Bienespersonale'][0]['exento'];
                }
            }
            ?>
            <tr 
                class="trclickeable" 
                cuecliid="<?php echo $cuentascliente['Cuentascliente']['id']?>" 
                saldoactual="<?php echo $saldo?>" 
                style="background-color: <?php echo $colorTR?>" >
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['Cuentascliente']['nombre']; ?>
                </td>
                <?php
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual])){
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['bienespersonales'])){
                    $arrayCuentasxPeriodos[$numerodecuenta]['bienespersonales']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['exento'])){
                    $arrayCuentasxPeriodos[$numerodecuenta]['exento']=0;
                }
                $arrayCuentasxPeriodos[$numerodecuenta]['bienespersonales']=$bienespersonales;
                $arrayCuentasxPeriodos[$numerodecuenta]['exento']=$exento;
                ?>
                <td class="numericTD" id="bienespersonales<?php echo $cuentascliente['Cuentascliente']['id']?>" ><?php echo number_format($bienespersonales, 2, ",", ".")?></td>
                <td class="numericTD" id="exento<?php echo $cuentascliente['Cuentascliente']['id']?>" ><?php echo number_format($exento, 2, ",", ".")?></td>
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
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

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


