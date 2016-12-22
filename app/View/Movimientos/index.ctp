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
</script>
<div style="width:98%; margin:1%; height:20px">
	<div style="float:left"> Cliente: &nbsp;</div> 
	<div style="float:left">
		<?php echo $cliente['Cliente']['nombre']; ?>
		<input id="hdnClienteId" type="hidden" value="<?php echo $cliente['Cliente']['id']; ?>" />
	</div>
</div>
<div class="index">
	<table>
	<tr>
		<td style="text-align: left;">
			<h2><?php echo __('Movimientos'); ?></h2>
		</td>	
		<!--
		<td style="text-align: right; cursor:pointer;" title="Agregar Movimiento">		
		<div class="fab blue">
            <core-icon icon="add" align="center">
                
                <?php echo $this->Form->button('+', 
                                            array('type' => 'button',
                                                'class' =>"btn_add",
                                                'onClick' => "agregarMovimiento()"
                                                )
                        );?> 
            </core-icon>
            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
       	</div>
		</td>
		-->
	</tr>
	</table>	
	<table id="tblListaMovimientos" cellpadding="0" cellspacing="0" border="0" class="display">
		<thead>
			<tr>
				<th>Asiento</th>
				<th>fecha Asiento</th>
				<th>Cuenta</th>
				<th>Debe</th>
				<th>Haber</th>				
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
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($asientos as $asiento)
		{ 
		?>
            <tr class="rowasiento">
                <td>
                    <?php echo h($asiento['Asiento']['descripcion']); ?>
                </td>
                <td>
                    <?php echo h($asiento['Asiento']['fecha']); ?>
                </td>
            </tr>
            <?php
            foreach ($asientos as $asiento){ ?>
                <tr class="rowmovimientos">
                    <td>
                        <?php echo h($movimiento['Cuentascliente']['nombre']); ?>
                    </td>
                    <td>
                        <?php echo h($movimiento['Movimiento']['debe']); ?>
                    </td>
                    <td>
                        <?php echo h($movimiento['Movimiento']['haber']); ?>
                    </td>
                    <td class="actions">
                        <?php //echo $this->Form->button('Editar', array('onClick' => 'editarUsuario('')' )); ?>
                    </td>
                </tr>
            <?php }
		}
		?>
		</tbody>
	</table>		
</div>
