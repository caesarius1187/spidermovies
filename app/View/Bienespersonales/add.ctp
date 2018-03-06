<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui.js',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('buttons.print.min.js',array('inline'=>false));
echo $this->Html->script('buttons.flash.min.js',array('inline'=>false));
echo $this->Html->script('jszip.min.js',array('inline'=>false));
echo $this->Html->script('pdfmake.min.js',array('inline'=>false));
echo $this->Html->script('vfs_fonts.js',array('inline'=>false));
echo $this->Html->script('buttons.html5.min.js',array('inline'=>false));

?>
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
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
	<div style="width:30%; float: left;padding-top:10px">
		Cliente: <?php echo $cliente["Cliente"]['nombre'];
		echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
	</div>
	<div style="width:25%; float: left;padding-top:10px">
		Periodo: <?php echo $periodo;
		echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo]);
                $peanio = substr($periodo, 3);
		echo $this->Form->input('peranio',['type'=>'hidden','value'=>$peanio]);
		echo $this->Form->input('isajaxrequest',['type'=>'hidden','value'=>$isajaxrequest])?>
	</div>
</div>
<div class="index" style="float:none;">
	<table >
            <tr>
                <td style="text-align: left;">
                    <?php
                    echo "<h2>Cuenta: ".$cuentasclienteseleccionada['Cuentascliente']['nombre'];
                    echo "del periodo  ".$periodo."</h2>";
                    ?>
                </td>
            </tr>
        </table>
	<?php
         echo $this->Form->create('Bienespersonale',[
                        'class'=>'formTareaCarga formAsiento',
                        'controller'=>'bienespersonales','action'=>'add',
                        'id'=>'FormAgregarBienesPersonales',
                    ]);
        echo $this->Form->input('Bienespersonale.id',[
            ]);
        echo $this->Form->input('Bienespersonale.cliente_id',[
                'value'=>$cliente['Cliente']['id'],
                'type'=>'hidden',
            ]);
        echo $this->Form->input('Bienespersonale.periodo',[
                'value'=>$periodo,
                'type'=>'hidden',
            ]);
        echo $this->Form->input('Bienespersonale.cuentascliente_id',[
                'value'=>$cuentasclienteseleccionada['Cuentascliente']['id'],
                'type'=>'hidden',
            ]
        );
        echo $this->Form->label("saldoactual",'Saldo Actual $'.$saldoactual);
        echo $this->Form->input('Bienespersonale.monto',[
                'default'=>"0.00",
                'label'=>"Importe gravado",
                'required'=>"required",
                'style'=>"width:300px"]);
        echo $this->Form->input('Bienespersonale.exento',[
                'default'=>"0.00",
                'label'=>"Importe Exento/No Gravado",
                'required'=>"required",
                'style'=>"width:300px"]);
        echo $this->Form->end('Guardar');
        ?>
</div>
