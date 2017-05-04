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

<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
	<div style="width:30%; float: left;padding-top:10px">
		Cliente: <?php echo $cliente["Cliente"]['nombre'];
		echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
	</div>
	<div style="width:25%; float: left;padding-top:10px">
		Periodo: <?php echo $periodo;
		echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo]);
		echo $this->Form->input('isajaxrequest',['type'=>'hidden','value'=>$isajaxrequest])?>
	</div>
</div>
<div class="index" style="float:none;">
	<table >
		<tr>
			<td style="text-align: left;">
				<?php
				if(isset($cuentasclienteseleccionada)){
					echo "<h2>Mayor de cuenta: ".$cuentasclienteseleccionada['Cuenta']['nombre']."</h2>";
				}else{
					echo "<h2>Asientos</h2>";
				}
				echo "<h3>del periodo  ".$fechaInicioConsulta." hasta ".$fechaFinConsulta."</h3>";
				?>
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
	<table id="tblAsientos" cellpadding="0" cellspacing="0" border="0" class="tbl_border" >
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Nombre</th>
				<th>Descripcion</th>
				<th>Debe</th>
				<th>Haber</th>
				<th>Saldo</th>
				<th>Periodo</th>
				<th>Numero</th>
				<th>Tipo</th>
				<th class="actions" style="text-align:center"><?php echo __('Acciones'); ?></th>
			</tr>
		</thead>

		<tbody>
		<?php
		$totalDebe=0;
		$totalHaber=0;
		$saldoacumulado=0;
		foreach ($asientos as $asiento)
		{
			$debe=0;
			$haber=0;
			$colorTR="white";
			foreach ($asiento['Movimiento'] as $movimiento) {
				if(isset($cuentasclienteseleccionada)){
					if($cuentasclienteseleccionada['Cuentascliente']['id']==$movimiento['cuentascliente_id']){
						$debe+=$movimiento['debe'];
						$haber+=$movimiento['haber'];
						$totalDebe+=$movimiento['debe'];
						$totalHaber+=$movimiento['haber'];
					}
				}else{
					$debe+=$movimiento['debe'];
					$haber+=$movimiento['haber'];
					$totalDebe+=$movimiento['debe'];
					$totalHaber+=$movimiento['haber'];
				}
			}
			if(!isset($cuentasclienteseleccionada)){
				if($debe-$haber){
					$colorTR= "#ffae00";
				}
			}
			?>
			<tr class="rowasiento" style="background-color: <?php echo $colorTR?>">
				<td>
					<span style='display: none;'> <?php echo $asiento['Asiento']['fecha']?></span>
					<?php echo date("d-m-Y", strtotime($asiento['Asiento']['fecha'])); ?>
				</td>
				<td>
					<?php echo $asiento['Asiento']['nombre']; ?>
				</td>
				<td>
					<?php echo $asiento['Asiento']['descripcion']; ?>
				</td>
				<td class="tdWithNumber">
					<?php echo number_format($debe, 2, ",", "."); ?>
				</td>
				<td class="tdWithNumber">
					<?php echo number_format($haber, 2, ",", "."); ?>
				</td>
				<td class="tdWithNumber">
					<?php
						if(isset($cuentasclienteseleccionada)){
							$saldoacumulado += $debe-$haber;
							echo number_format($saldoacumulado, 2, ",", ".");
						}else{
							echo number_format($debe-$haber, 2, ",", ".");
						}
					?>
				</td>
				<td>
					<?php echo $asiento['Asiento']['periodo']; ?>
				</td>
				<td>

				</td>

				<td>
					<?php echo $asiento['Asiento']['tipoasiento']; ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->image(
						'edit_view.png',
						array(
							'alt' => 'edit',
							'class'=>'imgedit',
							'style'=>'color:red;float:left;margin-top:10px',
							'onClick' => 'editarMovimientos('.$asiento['Asiento']['id'].')'
						)
					);
					?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th> <?php echo number_format($totalDebe, 2, ",", "."); ?> </th>
			<th> <?php echo number_format($totalHaber, 2, ",", "."); ?> </th>
			<th> <?php echo number_format($totalDebe-$totalHaber, 2, ",", "."); ?> </th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</tfoot>
	</table>
	<div id="divEditarAsiento"></div>
</div>
	<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
	<div class="modal fade" id="myModalAsientos" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:90%;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--						<span aria-hidden="true">&times;</span>-->
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
<!--						<span aria-hidden="true">&times;</span>-->
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
								'options'=>['Devengamiento'=>'Devengamiento','Registro'=>'Registro','Apertura'=>'Apertura','Refundacion'=>'Refundacion','Cierre'=>'Cierre'],
								'style'=>"width:auto",
								'label'=>'Tipo']);
						echo "</br>";
						?>

						<table id="tablaasiento" class="tbl_border">
                            <tfoot>
                                <tr>
                                    <td>
                                        <?php
                                        echo $this->Form->label('','Total a debe: $',[
                                            'style'=>"display: inline;"
                                        ]);
                                        echo $this->Form->label('lblTotalDebe',
                                            number_format(0, 2, ".", ""),
                                            [
                                                'id'=>'lblTotalDebe',
                                                'style'=>"display: inline;"
                                            ]
                                        );
                                        echo $this->Form->label('',' Total Haber: $',['style'=>"display: inline;"]);
                                        echo $this->Form->label('lblTotalHaber',
                                            number_format(0, 2, ".", ""),
                                            [
                                                'id'=>'lblTotalHaber',
                                                'style'=>"display: inline;"
                                            ]
                                        );
                                        if(number_format(0, 2, ".", "")==number_format(0, 2, ".", "")){
                                            echo $this->Html->image('test-pass-icon.png',array(
                                                    'id' => 'iconDebeHaber',
                                                    'alt' => 'open',
                                                    'class' => 'btn_exit',
                                                    'title' => 'Debe igual al Haber diferencia: '.number_format((0-0), 2, ".", ""),
                                                )
                                            );
                                        }else{
                                            echo $this->Html->image('test-fail-icon.png',array(
                                                    'id' => 'iconDebeHaber',
                                                    'alt' => 'open',
                                                    'class' => 'btn_exit',
                                                    'title' => 'Debe distinto al Haber diferencia: '.number_format((0-0), 2, ".", ""),
                                                )
                                            );
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tfoot>
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
										array(
										),
										array('class' => 'buttonImpcli',
											'id'=>'cargarMovimiento',
											'style'=> 'margin-right: 8px;width: initial;'
										)
									);

									?>

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


<?php
