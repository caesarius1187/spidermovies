<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('cuentasclientes/informesumaysaldo',array('inline'=>false));
?>
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
        <?php echo $this->Form->button('Asientos',
            array('type' => 'button',
                'class' =>"btn_realizar_tarea",
                'div' => false,
                'style' => array('style' => 'float:right'),
                'onClick' => "realizarEventoCliente('".$periodo."',".$cliente["Cliente"]['id'].",'realizado')"
            )
        );
        echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );
        echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
    </div>
</div>

<div class="index">
    <table id="tblsys">
        <thead>
        <tr class="trnoclickeable">
            <td>Numero</td>
            <td>Nombre</td>
            <td>Debitos</td>
            <td>Creditos</td>
            <td>Calculo Saldo Actual</td>
            <td>Saldo Ej Anterior Guardado</td>
            <td>Saldo Ej Actual Guardado</td>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($cliente['Cuentascliente'] as $cuentascliente){?>
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['id']?>">
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['nombre']; ?>
                </td>
                <?php
                $saldoCalculado = 0;
                $debitos = 0;
                $creditos = 0;
                foreach ($cuentascliente['Movimiento'] as $movimiento){
                $debitos+= $movimiento['debe'];
                $creditos+= $movimiento['haber'];
                $saldoCalculado += $movimiento['debe'];
                $saldoCalculado -= $movimiento['haber'];
                }
                ?>
                <td>
                    <?php echo $debitos; ?>
                </td>
                <td>
                    <?php echo $creditos; ?>
                </td>
                <td>
                <?php echo $saldoCalculado; ?>
                </td>
                <?php
                $saldoanterior=0;
                if(isset($cuentascliente['Saldocuentacliente'][0]['saldoanterior'])){
                    $saldoanterior = $cuentascliente['Saldocuentacliente'][0]['saldoanterior'];
                }
                $saldoactual=0;
                if(isset($cuentascliente['Saldocuentacliente'][0]['saldoactual'])){
                    $saldoactual = $cuentascliente['Saldocuentacliente'][0]['saldoactual'];
                }
                ?>
                <td>
                    <?php echo $saldoanterior; ?>
                </td>
                <td>
                    <?php echo $saldoactual; ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
