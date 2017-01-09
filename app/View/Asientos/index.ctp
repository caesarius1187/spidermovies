<?php
	echo $this->Html->css('bootstrapmodal');

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
<script type="text/javascript">
	$(document).ready(function() {
		$('.chosen-select').chosen({search_contains:true});
	});
	function agregarMovimiento()
	{
		var CuentaCliente = $("#chsnCuentasClientes").val();
		var sDebe = $("#txtDebe").val();
		var sHaber = $("#txtHaber").val();
		alert(CuentaCliente);
	}
	function editarMovimientos(asientoID){
		var data ="";
		$.ajax({
			type: "post",  // Request method: post, get
			url: serverLayoutURL+"/movimientos/index/"+asientoID,

			// URL to request
			data: data,  // post data
			success: function(response) {
					$('#myModal').on('show.bs.modal', function() {
						$('#myModal').find('.modal-title').html('Editar Asiento');
						$('#myModal').find('.modal-body').html(response);
						// $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
					});
					$('#myModal').modal('show');
					$('.chosen-select').chosen({search_contains:true});
					$('#AsientoAddForm').submit(function(){
						//serialize form data
						var formData = $(this).serialize();
						//get form action
						var formUrl = $(this).attr('action');
						$.ajax({
							type: 'POST',
							url: formUrl,
							data: formData,
							success: function(data,textStatus,xhr){
								var respuesta = JSON.parse(data);
								$('#myModal').modal('hide');
								callAlertPopint(respuesta.respuesta);
								editarMovimientos(asientoID);
							},
							error: function(xhr,textStatus,error){
								$('#myModal').modal('hide');
								callAlertPopint(respuesta.error);
								alert(textStatus);
							}
						});
						return false;
					});
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
		});
	}
</script>
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
		<div class="fab blue">
            <core-icon icon="add" align="center">

                <?php echo $this->Form->button('+',
				array('type' => 'button',
					'class' =>"btn_add",
					'onClick' => "agregarAsiento()"
				)
			);?>
            </core-icon>
            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
       	</div>
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
