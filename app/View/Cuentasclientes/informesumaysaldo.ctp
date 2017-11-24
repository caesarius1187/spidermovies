<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('cuentasclientes/informesumaysaldo',array('inline'=>false));
echo $this->Html->script('asientos/index',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('buttons.print.min.js',array('inline'=>false));
echo $this->Html->script('buttons.flash.min.js',array('inline'=>false));
echo $this->Html->script('jszip.min.js',array('inline'=>false));
echo $this->Html->script('pdfmake.min.js',array('inline'=>false));
echo $this->Html->script('vfs_fonts.js',array('inline'=>false));
echo $this->Html->script('buttons.html5.min.js',array('inline'=>false));?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>-->
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>-->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css"/>

<!--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>-->

<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016
 * Time: 12:15 PM
 */
?>

<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:10px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo])?>
    </div>
    <div style="float:right; width:45%">
        <?php
        echo $this->Html->link(
            "Asientos",
            array(
                'controller' => 'asientos',
                'action' => 'index',
                $cliente["Cliente"]['id'],
                $periodo
            ),
            array('class' => 'buttonImpcli',
                'style'=> 'margin-right: 8px;width: initial;'
            )
        );
        echo $this->Html->link(
            "Agregar Asiento",
            array(
            ),
            array('class' => 'buttonImpcli',
                'id'=>'cargarAsiento',
                'style'=> 'margin-right: 8px;width: initial;'
            )
        );
        echo $this->Form->button('Plan de cuentas',
            array('type' => 'button',
                'class' =>"buttonImpcli",
                'style' =>"width: initial;",
                'onClick' => "location.href = serverLayoutURL+'/cuentasclientes/plancuentas/". $cliente["Cliente"]['id']."';"
            )
        );
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
        );?>
    </div>
</div>

<div class="index">
    <?php
    echo "<h2>Informe de Sumas y Saldos</h2>";
    echo "<h3>del periodo  ".$fechaInicioConsulta." hasta ".$fechaFinConsulta."</h3>";
    ?>
    <table id="tblsys"  class="tbl_border" cellspacing="0">
        <thead>
            <tr class="trnoclickeable">
                <td rowspan="2">N&uacute;mero</td>
                <td rowspan="2">Cuenta</td>
                <?php
                $arrayPeriodos=[];
                $mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
                while($mesAMostrar<$fechaFinConsulta){
                    $periodoMesAMostrar = date('m-Y', strtotime($mesAMostrar));
                    echo "<td>Saldo</td>";
                    $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
                }
                ?>
<!--                <td colspan="2">Total</td>-->
                <td rowspan="2">Saldo Acumulado</td>
            </tr>
            <tr class="trnoclickeable">
                <?php
                $arrayPeriodos=[];
                $mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
                while($mesAMostrar<$fechaFinConsulta){
                    $periodoMesAMostrar = date('m-Y', strtotime($mesAMostrar));
                    echo "<td>".$periodoMesAMostrar."</td>";
                    $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
                }
                ?>

<!--                <td >Debe</td>-->
<!--                <td >Haber</td>-->
            </tr>
        </thead>

        <tbody>
        <?php
        $arrayTotales=[];
        foreach ($cliente['Cuentascliente'] as $cuentascliente){
            //si no hay movimientos para esta cuentacliente no la vamos a mostrar en el suma y saldos
            if(count($cuentascliente['Movimiento'])==0){
                continue;
            }
            $saldoCalculado = 0;
            $debes = 0;
            $haberes = 0;
            $arrayPeriodos = [];
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                $periodoAImputar = date('m-Y', strtotime($movimiento['Asiento']['fecha']));
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                }
                if(!isset($arrayTotales[$periodoAImputar])){
                    $arrayTotales[$periodoAImputar]=[];
                    $arrayTotales[$periodoAImputar]['debes']=0;
                    $arrayTotales[$periodoAImputar]['haberes']=0;
                }
                $arrayPeriodos[$periodoAImputar]['debes']+=$movimiento['debe'];
                $arrayPeriodos[$periodoAImputar]['haberes']+=$movimiento['haber'];
                $debes+= $movimiento['debe'];
                $haberes+= $movimiento['haber'];
                $saldoCalculado += $movimiento['debe'];
                $saldoCalculado -= $movimiento['haber'];
                $arrayTotales[$periodoAImputar]['debes']+= $movimiento['debe'];
                $arrayTotales[$periodoAImputar]['haberes']+= $movimiento['haber'];
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
            switch ($charinicial){
                case "1":
                case "3":
                case "5":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
                case "2":
                case "6":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
            }

            ?>
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['id']?>" style="background-color: <?php echo $colorTR?>">
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['nombre']; ?>
                </td>

                
                <?php
                $mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
                while($mesAMostrar<$fechaFinConsulta){
                    $periodoMesAMostrar = date('m-Y', strtotime($mesAMostrar));
                    if(!isset($arrayPeriodos[$periodoMesAMostrar])){
                        $arrayPeriodos[$periodoMesAMostrar]=[];
                        $arrayPeriodos[$periodoMesAMostrar]['debes']=0;
                        $arrayPeriodos[$periodoMesAMostrar]['haberes']=0;
                    }
                    echo '<td  class="numericTD">'.number_format($arrayPeriodos[$periodoMesAMostrar]['debes']-$arrayPeriodos[$periodoMesAMostrar]['haberes'], 2, ",", ".")."</td>";
                    $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
                }

//                <td>
//                    <?php echo number_format($debes, 2, ",", ".");
//                </td>
//                <td>
//                    <?php echo number_format($haberes, 2, ",", ".");
//                </td>
                ?>
                <td class="numericTD">
                <?php echo number_format($saldoCalculado, 2, ",", "."); ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
        <tr class="trnoclickeable">
            <td></td>
            <td></td>
            <?php
            $mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
            while($mesAMostrar<$fechaFinConsulta){
                $periodoMesAMostrar = date('m-Y', strtotime($mesAMostrar));
                echo "<td >"./*$periodoMesAMostrar.*/"</td>";
                $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
            }
            ?>
            <!--                <td></td>-->
            <!--                <td></td>-->
            <td></td>
        </tr>
        </tfoot>
    </table>
</div>
<?php
$hiddenCuentasValue= "";
$hiddenDebe=0;
$hiddenHaber=0;

if(isset($movimiento['cuentascliente_id'])){
    $hiddenCuentasValue= $movimiento['cuentascliente_id'];
    $hiddenDebe=$movimiento['debe'];
    $hiddenHaber=$movimiento['haber'];
}
echo $this->Form->input('Asiento.0.Movimiento.kkk.hidencuentascliente_id',
    [
        'type'=>'select',
        'options'=>$cuentasclientes,
        'value'=>$hiddenCuentasValue ,
        'class'=>'chosen-select',
        'div'=>['style'=>'display:none'],
    ]);?>
<!-- Popin Modal para Agregar Asientos-->
<div class="modal fade" id="myModalFormAgregarAsiento" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--                    <span aria-hidden="true">&times;</span>-->
                </button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="index" style="float: none;">
                    <h3>Agregar Asiento</h3>
                    <?php
                    echo $this->Form->create('Asiento',[
                        'class'=>'formTareaCarga formAsiento',
                        'controller'=>'asientos','action'=>'add',
                        'id'=>'FormAgregarAsiento',
                    ]);
                    echo $this->Form->input('Asiento.0.id',[
                            'value'=>0,
                        ]
                    );
                    echo $this->Form->input('Asiento.0.cliente_id',[
                            'value'=>$cliente['Cliente']['id'],
                            'type'=>'hidden',
                        ]
                    );
                    echo $this->Form->input('Asiento.0.nombre',
                        ['value'=>"",'required'=>"required",
                            'style'=>"width:300px"]);
                    echo $this->Form->input('Asiento.0.periodo',
                        ['value'=>$periodo,'type'=>"hidden",
                            'style'=>"width:300px"]);
                    echo $this->Form->input('Asiento.0.descripcion',
                        ['value'=>"",
                            'required'=>"required",
                            'style'=>"width:300px"]);
                    echo $this->Form->input('Asiento.0.fecha',
                        ['value'=>"",'class'=>"datepicker",
                            'required'=>"required",
                            'readonly'=>"readonly",
                            'style'=>"width:120px"]);
                    echo $this->Form->input('Asiento.0.tipoasiento',
                        [
                            'type'=>"select",
                            'options'=>[
                                'Devengamiento'=>'Devengamiento',
                                'Registro'=>'Registro',
                                'Apertura'=>'Apertura',
                                'Refundacion'=>'Refundacion',
                                'Cierre'=>'Cierre',
                                'Devengamiento'=>'Devengamiento',                                                            
                                'Distribucion de dividendos'=>'Distribucion de dividendos',
                                'Absorcion de perdida acumulada'=>'Absorcion de perdida acumulada'
                                ],
                            'style'=>"width:auto",
                            'label'=>'Tipo']);
                    echo "</br>";
                    ?>

                    <table id="tablaasiento">

                        <thead></thead>
                        <tbody>
                            <tr id="rowdecarga">
                                <td ><?php
                                    echo $this->Form->input('Asiento.0.Movimiento.kkk.id',
                                        ['value'=>0]);
                                    echo $this->Form->input('Asiento.0.Movimiento.kkk.cuentascliente_id',
                                        [
                                            'value'=>$hiddenCuentasValue,
                                            'class'=>'chosen-select',
                                        ]);

                                    echo $this->Form->input('Asiento.0.Movimiento.kkk.fecha',
                                        [
                                            'value'=>date('d-m-Y'),
                                            'default'=>date('d-m-Y'),
                                            'type'=>'hidden',
                                            'class'=>"datepicker",
                                        ]);

                                    echo $this->Form->input('Asiento.0.Movimiento.kkk.debe',
                                        ['value'=>$hiddenDebe,
                                            'style'=>"width:auto",
                                            'label'=>'Debe']);
                                    echo $this->Form->input('Asiento.0.Movimiento.kkk.haber',
                                        ['value'=>$hiddenHaber,
                                            'style'=>"width:auto",
                                            'label'=>'Haber']);

                                    ?>
                                </td>
                                <td>
                                    <?php  echo $this->Html->link(
                                        "Agregar",
                                        "#",
                                        array('class' => 'buttonImpcli',
                                            'id'=>'cargarMovimiento',
                                            'style'=> 'margin-right: 8px;width: initial;'
                                        )
                                    );?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="20">

                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <?php
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
                                                'id'=>'lblTotalDebeAdd',
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
                                                'id'=>'lblTotalHaberAdd',
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
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!--                <button type="button" class="btn btn-primary">Save changes</button>-->
                <input type="submit" value="Guardar" class="btn btn-default">
                <?php echo $this->Form->end();?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Popin Modal para edicion de Libro Mayor a utilizar por datatables-->
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
<!-- /.modal -->


<?php echo $this->Form->input('nextmovimiento',['value'=>0,'type'=>'hidden']);?>
