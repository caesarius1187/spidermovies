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

echo $this->Html->script('asientos/index',array('inline'=>false));
?>

<div style="width:98%; margin:1%; height:20px">
	<div style="float:left"> Cliente: &nbsp;</div>
	<div style="float:left">
		<?php
		echo $cliente['Cliente']['nombre']; ?>
		<input id="hdnClienteId" type="hidden" value="<?php echo $cliente['Cliente']['id']; ?>" />
	</div>
</div>
<div class="index" style="float:none;">
	<table>
		<tr>
			<td style="text-align: left;">
				<h2><?php echo __('Asientos'); ?></h2>
			</td>
		<td style="text-align: right; cursor:pointer;" title="Agregar Movimiento">
                <?php  echo $this->Html->link(
					"Agregar Asiento",
					array(
					),
					array('class' => 'buttonImpcli',
						'id'=>'cargarAsiento',
						'style'=> 'margin-right: 8px;width: initial;'
					)
				);?>
		</td>

		</tr>
	</table>
	<table id="tblListaMovimientos" cellpadding="0" cellspacing="0" border="0" class="" >
		<thead>
		<tr>
			<th>Asiento</th>
			<th>fecha Asiento</th>
			<th>Cuenta</th>
			<th>Debe</th>
			<th>Haber</th>
			<th>Saldo</th>
			<th class="actions" style="text-align:center"><?php echo __('Acciones'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($asientos as $asiento)
		{
			?>
			<tr class="rowasiento">
				<td>
					<?php echo $asiento['Asiento']['descripcion']; ?>
				</td>
				<td>
					<?php echo $asiento['Asiento']['fecha']; ?>
				</td>
				<td>
					<?php echo $asiento['Asiento']['nombre']; ?>
				</td>
				<?php
				$debe=0;
				$haber=0;
				foreach ($asiento['Movimiento'] as $movimiento) {
					$debe+=$movimiento['debe'];
					$haber+=$movimiento['haber'];
				}
				?>
				<td>
					<?php echo $debe; ?>
				</td>
				<td>
					<?php echo $haber; ?>
				</td>
				<td>
					<?php echo $debe-$haber; ?>
				</td>
				<td class="actions">
					<?php
					echo $this->Form->button('Movimientos',
						array('onClick' => 'editarMovimientos('.$asiento['Asiento']['id'].')' )
					); ?>
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
<?php
echo $this->Form->input('Asiento.0.Movimiento.kkk.hidencuentascliente_id',
[
'type'=>'select',
'options'=>$cuentasclientes,
'value'=>$movimiento['cuentascliente_id'],
'class'=>'chosen-select',
'div'=>['style'=>'display:none'],
]);?>
<!-- Popin Modal para Agregar Asientos-->
<div class="modal fade" id="myModalFormAgregarAsiento" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="width:90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
							'options'=>['Devengamiento'=>'Devengamiento','Registro'=>'Registro','Apertura'=>'Apertura','Refundacion'=>'Refundacion','Cierre'=>'Cierre'],
							'style'=>"width:auto",
							'label'=>'Tipo']);
					echo "</br>";
					?>

					<table id="tablaasiento" class="tbl_border">
						<tr id="rowdecarga">
							<td ><?php
								echo $this->Form->input('Asiento.0.Movimiento.kkk.id',
									['value'=>0]);
								echo $this->Form->input('Asiento.0.Movimiento.kkk.cuentascliente_id',
									[
										'value'=>$movimiento['cuentascliente_id'],
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
									['value'=>$movimiento['debe'],
										'style'=>"width:auto",
										'label'=>'Debe']);
								echo $this->Form->input('Asiento.0.Movimiento.kkk.haber',
									['value'=>$movimiento['haber'],
										'style'=>"width:auto",
										'label'=>'Haber']);

								?>
							</td>
							<td>
								<?php  echo $this->Html->link(
									"Agregar",
									array(
									),
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
<?php echo $this->Form->input('nextmovimiento',['value'=>0,'type'=>'hidden']);?>


