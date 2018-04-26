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
                    echo " del periodo  ".$periodo."</h2>";
                    ?>
                </td>
            </tr>
        </table>
	<?php
         echo $this->Form->create('Anexogasto',[
                        'class'=>'formTareaCarga formAsiento',
                        'controller'=>'anexogastos','action'=>'add',
                        'id'=>'FormAgregarAnexogastos',
                    ]);
        echo $this->Form->input('Anexogasto.id',[
            ]);
        echo $this->Form->input('Anexogasto.cliente_id',[
                'value'=>$cliente['Cliente']['id'],
                'type'=>'hidden',
            ]);
        echo $this->Form->input('Anexogasto.periodo',[
                'value'=>$periodo,
                'type'=>'hidden',
            ]);
        echo $this->Form->input('Anexogasto.cuentascliente_id',[
                'value'=>$cuentasclienteseleccionada['Cuentascliente']['id'],
                'type'=>'hidden',
            ]
        );
        echo $this->Form->input('Anexogasto.porcentajeventa',[
                'default'=>"0.00",
                'label'=>"Porcentaje Costo de venta",
                'required'=>"required",
                'style'=>"width:300px"]);
        echo $this->Form->input('Anexogasto.porcentajeadministracion',[
                'default'=>"0.00",
                'label'=>"Porcentaje Administracion",
                'required'=>"required",
                'style'=>"width:300px"]);
        echo $this->Form->input('Anexogasto.porcentajecomercializacion',[
                'default'=>"0.00",
                'label'=>"Porcentaje Comercializacion",
                'required'=>"required",
                'style'=>"width:300px"]);
        
        echo $this->Form->end('Guardar');
        ?>
</div>
