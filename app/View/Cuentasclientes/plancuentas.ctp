<script type="text/javascript">
	$(document).ready(function() {
	    var iTamPantalla = $(window).height();
	    var iTamTabla = iTamPantalla - 160;
	    iTamTabla = (iTamTabla < 100) ? 100 : iTamTabla;
	    $("#divPlanCuentasCliente").attr("style", "max-height:" + iTamTabla + "px; width:100%; overflow:auto");	    
	});
</script>

<div style="width:98%; margin:2%;">
	<div style='float:left; width:70%'> 
		Cliente: <?php echo $cliente['Cliente']['nombre']; ?>
	</div>
	<div style="float:right; width:28%;margin-bottom:10px">
		<!--<button class="btn_realizar_tarea" type="button" style="float:right;" onclick="">
			Agregar Cuentas
		</button>-->
		<?php
		echo $this->Html->link(
                                'Agregar Cuentas', 
                                array(
                                    'controller' => 'Cuentas', 
                                    'action' => 'view', $cliente['Cliente']['id']
                                ),
                                array(
                                	'style' => 'float:right; margin-right:40px'
                                )                               
                              );  
        ?>

	</div>
</div>
<div style="width:99%;">	
	<table style="margin-bottom:0px">			
		<tr>
			<th style="width:30%;text-align:left">Cuenta Nro.</th>
			<th style="width:50%;text-align:left">Descripcion</th>			
			<th style="width:10%;text-align:center">Inputable</th>			
			<th style="width:10%;text-align:center">Ajuste</th>
		</tr>		
	</table>	
</div>	
<div id="divPlanCuentasCliente"  style="width:100%;">	
	<table>			
		<?php foreach ($cuentasclientes as $cuentascliente)
		{ ?>
		<tr>
			<td style="width:30%;text-align:left">
				<?php echo h($cuentascliente['Cuenta']['numero']); ?>
			</td>
			<td style="width:50%;text-align:left"> 
				<?php echo h($cuentascliente['Cuenta']['nombre']); ?>
			</td>		
			<td style="width:10%;text-align:center">
				<?php 
				if ($cuentascliente['Cuenta']['tipo'] == 'cuenta') 
					echo 'SI';
				else
					echo 'NO';
				?>
			</td>		
			<td style="width:10%;text-align:center">
				<?php 
				if ($cuentascliente['Cuenta']['ajuste'] == 0) 
					echo 'NO';
				else
					echo 'SI';
				?>
			</td>		
		</tr>		
		<?php 
		} ?>		
	</table>
</div>