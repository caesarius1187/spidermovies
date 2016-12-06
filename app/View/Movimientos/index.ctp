<script type="text/javascript">
	
	$(document).ready(function() {		
		$('.chosen-select').chosen({search_contains:true});
	});
	
	function agregarMovimiento()
	{
		var CuentaCliente = $("#chsnCuentasClientes").val();
		var sDebe = $("#txtDebe").val();
		var sHaber = $("#txtHaber").val();
		alert(CuentaCli);
	}

</script>
<div style="width:98%; margin:1%; height:20px">
	<div style="float:left"> Cliente: &nbsp;</div> 
	<div style="float:left">
		<?php echo $cliente['Cliente']['nombre']; ?>
		<input id="hdnClienteId" type="hidden" value="<?php echo $cliente['Cliente']['id']; ?>" />
	</div>
</div>

<div style="width:98%; margin:1%; height:25px">
	<div style="float:left;">		
		<?php
        echo $this->Form->input('asientos', array(          
            'type' => 'select',
            'class' =>'chosen-select',
            'label' => '',
            'empty' => 'Seleccione Asiento',
            'style' => 'width:200px',
            'div' => false,
            'id' => 'chsnAsientos'
        ));
        ?>
	</div>
	<div style="float:left;">		
		<?php
        echo $this->Form->input('cuentasclientes', array(          
            'type' => 'select',
            'class' =>'chosen-select',
            'label' => '',
            'empty' => 'Seleccione cuenta',
            'style' => 'width:200px',
            'div' => false,
            'id' => 'chsnCuentasClientes'
        ));
        ?>
	</div>		
	<div style="float:left; width:160px; margin-left:20px;">
		<span>Debe: </span>
		<input id="txtDebe" style="width:100px;" type="text"/>
	</div>
	<div style="float:left; width:160px; margin-left:5px;">
		<span>Haber: </span>
		<input id="txtHaber" style="width:100px;" type="text"/>
	</div>
	<div style="float:left; width:160px; margin-left:5px;">
		<a href="#" onclick="agregarMovimiento();" style="">Agregar</a>
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
		<?php foreach ($movimientos as $movimiento)
		{ 
		?>
		<tr>
			<td>				
				<?php echo h($movimiento['Asiento']['descripcion']); ?>
			</td>
			<td>
				<?php echo h($movimiento['Asiento']['fecha']); ?>
			</td>
			<td>
				<?php echo h($movimiento['Cuentascliente']['Cuenta']['nombre']); ?>
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
		<?php 
		} 
		?>
		</tbody>
	</table>		
</div>
