<?php
echo $this->Html->script('movimientos/index',array('inline'=>true));
$asiento = $asientos[0];
?>
<div class="index" style="float: none;">
	<h3>Agregar movimiento al asiento</h3>
	<?php
	echo $this->Form->create('Movimiento',['class'=>'formTareaCarga','id'=>'FormAgregaMovimiento']);
	echo $this->Form->input('cuentascliente',[
		'id'=>'FormEditaMovimientoCuentascliente',
		'style'=>'width:300px',
		'div'=>['style'=>'width:300px'],
		'type'=>'select',
		'class'=>'chosen-select',
	]);
	echo $this->Form->input('debe');
	echo $this->Form->input('haber');
	echo $this->Form->input('fecha',['type'=>'hidden','value'=>date('d-m-Y')]);
	echo $this->Form->end('Agregar');
	?>
</div>
<div class="index" style="float: none;">
	<h3>Modificar Asiento</h3>
	<?php
	echo $this->Form->create('Asiento',[
		'class'=>'formTareaCarga formAsiento',
		'action'=>'add','controller'=>'asientos',
		'id'=>'FormEditAsiento']);
	echo $this->Form->input('Asiento.0.id',[
		'value'=>$asiento['Asiento']['id'],
		]
	);
	echo $this->Form->input('Asiento.0.cliente_id',[
		'value'=>$asiento['Asiento']['cliente_id'],
		'type'=>'hidden',
		]
	);
    echo $this->Form->input('Asiento.0.impcli_id',[
		'value'=>$asiento['Asiento']['impcli_id'],
		'type'=>'hidden',
		]
	);
    echo $this->Form->input('Asiento.0.cbu_id',[
		'value'=>$asiento['Asiento']['cbu_id'],
		'type'=>'hidden',
		]
	);
	echo $this->Form->input('Asiento.0.numero',
		['value'=>$asiento['Asiento']['numero'],
			'style'=>"width:100px"]);
	echo $this->Form->input('Asiento.0.fecha',
		['value'=>$asiento['Asiento']['fecha'],
			'style'=>"width:120px"]);

	echo $this->Form->input('Asiento.0.periodo',
		['value'=>$asiento['Asiento']['periodo'],
			'style'=>"width:120px"]);
	echo $this->Form->input('Asiento.0.nombre',
		['value'=>$asiento['Asiento']['nombre'],
			'style'=>"width:300px"]);
	echo $this->Form->input('Asiento.0.descripcion',
		['value'=>$asiento['Asiento']['descripcion'],
			'style'=>"width:300px"]);
    echo $this->Form->input('Asiento.0.tipoasiento',
        [
            'type'=>"select",
            'default'=>$asiento['Asiento']['tipoasiento'],
            'val'=>$asiento['Asiento']['tipoasiento'],
            'options'=>[
                'Devengamiento'=>'Devengamiento',
                'Registro'=>'Registro',
                'Apertura'=>'Apertura',
                'Refundacion'=>'Refundacion',
                'Cierre'=>'Cierre',
                'impuestos'=>'Impuestos',
                'impuestos2'=>'Impuestos 2',
                'compras'=>'Compras',
                'ventas'=>'Ventas',
                'cobros'=>'Cobros',
                'pagos'=>'Pagos',
                'bancos'=>'Bancos Acreditaciones',
                'bancosretiros'=>'Bancos Retiros',
                'otros'=>'Otros',
                'retencionessufridas'=>'Retenciones Sufridas',
                'retencionesrealizadas'=>'Retenciones Realizadas',
            ],
            'style'=>"width:auto",
            'label'=>'Tipo',
            'title'=>'No cambiar el tipo de asiento en asientos automaticos por que el sistema intentará crearlos de 
            nuevo cuando se generen en los asientos automaticos'
        ]);
	echo "</br>";
	?>

	<table id="tablaModificarAsiento" class="tbl_border" style="border-spacing: 0px;"><?php
		$totalDebe=0;
		$totalHaber=0;
		foreach ($asiento['Movimiento'] as $m => $movimiento){
		?>
		<tr id="movimientoeditnumero<?php echo $m ?>" >
			<td style="width: 100px"><?php
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.id',
					['value'=>$movimiento['id']]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.cuentascliente_id',
					[
						'value'=>$movimiento['cuentascliente_id'],
						'type'=>'hidden',
					]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.fecha',
					[
						'value'=>$movimiento['fecha'],
						'default'=>date('d-m-Y'),
						'type'=>'hidden',
					]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.cuenta_id',
					[
						'value'=>$movimiento['Cuentascliente']['cuenta_id'],
						'type'=>'hidden',
					]);
				echo $this->Form->label('numeroCuenta',
					$movimiento['Cuentascliente']['Cuenta']['numero'],
					[
						'style'=>"display:initial",
					]
				);
				?>

				</td>
				<td style="width: 200px"><?php


				echo " ".$this->Form->label('nombreCuenta',
					$movimiento['Cuentascliente']['nombre'],
					[
						'style'=>"display:initial",
					]
				);
				?>
			</td>
			<td><?php
				$movimientoConValor = "movimientoSinValor";
				if(($movimiento['debe']*1) != 0 || ($movimiento['haber']*1) != 0){
					$movimientoConValor = "movimientoConValor";
				}
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.debe',
					[
						'value'=> number_format($movimiento['debe'], 2, ".", ""),
						'style'=>"width:auto",
						'class'=>"inputDebe ".$movimientoConValor,
						'label'=>false]);
				$totalDebe+=number_format($movimiento['debe'], 2,'.','');
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.haber',
					['value'=>number_format($movimiento['haber'], 2, ".", ""),
						'class'=>"inputHaber ". $movimientoConValor,
						'style'=>"width:auto",
						'label'=>false]);
				$totalHaber+=number_format($movimiento['haber'], 2, ".", "");
				echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20'
				,'onClick'=>"deleteRowMovimientoEdit(".$m.")"));

				?>
			</td>
		</tr>
		<?php
	}
	?>
		
		<tr>
			<td colspan="2">Totales</td>
			<td><?php
				echo $this->Form->label('','&nbsp; $',[
					'style'=>"display: -webkit-inline-box;"
				]);
				echo $this->Form->label('lblTotalDebe',
				number_format($totalDebe, 2, ".", ""),
					[
						'id'=>'lblTotalDebe',
						'style'=>"display: inline;"
					]
				);
				echo $this->Form->label('','&nbsp; $',[
					'style'=>"display: -webkit-inline-box;width: 128px;"
				]);
				echo $this->Form->label('lblTotalHaber',
					number_format($totalHaber, 2, ".", ""),
					[
						'id'=>'lblTotalHaber',
						'style'=>"display: -webkit-inline-box;"
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
			</td>
		</tr>
	</table>
	<?php
	echo $this->Form->end('Modificar');
	if(isset($m))
		echo $this->Form->input('topmovimiento',['value'=>$m,'type'=>'hidden']);
	?>
</div>
