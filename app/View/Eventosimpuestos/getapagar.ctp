<SCRIPT TYPE="text/javascript">
$(document).ready(function() {
    $( "input.datepicker" ).datepicker({
      yearRange: "-100:+50",
      changeMonth: true,
      changeYear: true,
      constrainInput: false,
      dateFormat: 'dd-mm-yy',
    });
});
</SCRIPT>   
<?php /*Este es el formulario para PAGAR papeles de Trabajo YA generados */ ?> 	
<h3><?php echo __('Papeles preparados para pagar en : '.$impclinombre); ?></h3>
<?php echo $this->Form->create('Eventosimpuesto',array('action'=>'realizartarea13', 'id'=>'FormPagarEventoImpuesto')); ?>
<table cellpadding="0" cellspacing="0" id="tablePapelesPreparados" class="tbl_getpagar">
	<tr>
			<?php
			switch ($impuesto['tipopago']) {
          		case 'provincia':
	          		echo "<th>Provincia</th>";
	          		break;
	          	case 'municipio':
	          		echo "<th>Municipio</th>";
	          		break;
          		case 'item':
          			echo "<th>Item</th>";
          		break;	          	
          	}?>
			<th>Fch. Vto.</th>
			<th>Monto</th>
			<th>Monto Realizado</th>
			<th>Fch. Realizado</th>
			<?php 
			//los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
			if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14){ ?>
				<th>Monto a Favor</th>
  			<?php } ?>				                      
			<th>Descripcion</th>
	</tr>
	<?php 
	$i = 0;
	foreach ($eventosimpuestos as $eventosimpuesto): ?>
	<?php 
		echo $this->Form->input('Eventosimpuesto.'.$i.'.impcliid',array('value'=>$impcliid,'type'=>'hidden')); 
		echo $this->Form->input('Eventosimpuesto.'.$i.'.eventoId',array('type'=>'hidden'));
		echo $this->Form->input('Eventosimpuesto.'.$i.'.id',array('type'=>'hidden','value'=>$eventosimpuesto['Eventosimpuesto']['id']));
		echo $this->Form->input('Eventosimpuesto.'.$i.'.clienteid',array('value'=>$clienteid,'type'=>'hidden'));
		?>
	<tr>
		<?php				
		switch ($impuesto['tipopago']) {
      		case 'provincia':
          		?>
          		<td>

          			<?php echo $this->Form->input('Eventosimpuesto.'.$i.'.partido_id',array('value'=>$eventosimpuesto['Eventosimpuesto']['partido_id'],'label'=>false, 'style' => 'width:80px','disabled'=>'disabled')); ?>
      			</td>
          		<?php 
          		break;
          	case 'municipio':
          		?>
          		<td>
          			<?php echo $this->Form->input('Eventosimpuesto.'.$i.'.localidade_id',array('value'=>$eventosimpuesto['Eventosimpuesto']['localidade_id'],'label'=>false, 'style' => 'width:80px','disabled'=>'disabled')); ?>
      			</td>
      			<?php 
          		break;
      		case 'item':
      			?>
      			<td>
      				<?php echo $this->Form->input('Eventosimpuesto.'.$i.'.item',array('value'=>$eventosimpuesto['Eventosimpuesto']['item'],'label'=>false, 'style' => 'width:80px','disabled'=>'disabled')); ?>
      			</td>
      			<?php 
      		break;	          	
      	}?>
		<td>
			<?php 
			echo $this->Form->input('Eventosimpuesto.'.$i.'.fchvto', array(
							                      'type'=>'text',
							                      'label'=>false,
							                      'style' => 'width:72px',
							                      'readonly'=>'readonly',
							                      'value'=>date('d-m-Y',strtotime($eventosimpuesto['Eventosimpuesto']['fchvto'])),
							                      'disabled'=>'disabled'));	       
			?></td>
		<td ><?php 
			echo $this->Form->input('Eventosimpuesto.'.$i.'.montovtoreal',array('type'=>'hidden','value'=>$eventosimpuesto['Eventosimpuesto']['montovto']));
			echo $this->Form->input('Eventosimpuesto.'.$i.'.montovto',array(
												'value'=>"$".number_format($eventosimpuesto['Eventosimpuesto']['montovto'], 2, ",", "."),
												'disabled'=>'disabled',												
												'type'=>'text',												
												'label'=>false, 
												'class'=>'inputmontovto',
												'style' => 'width:70px')); ?></td>
		<td id="TDEventosimpuesto.".$i.".montorealizado"><?php 
			echo $this->Form->input('Eventosimpuesto.'.$i.'.montorealizado',array(
							'value'=>$eventosimpuesto['Eventosimpuesto']['montorealizado'],
							'label'=>false, 
							'style' => 'width:70px',
							'onChange'=>'checkVencimientoPagado('.$i.')',
							'class'=>'inputmontorealizado')
							); 
							?>
		</td>
		<td><?php 
			$fchrealizadotoShow= date('d-m-Y');
			if($eventosimpuesto['Eventosimpuesto']['fchrealizado']!=null&&$eventosimpuesto['Eventosimpuesto']['fchrealizado']!=''){
				$fchrealizadotoShow=date('d-m-Y',strtotime($eventosimpuesto['Eventosimpuesto']['fchrealizado']));
			}
			echo $this->Form->input('Eventosimpuesto.'.$i.'.fchrealizado', array(
							                      'class'=>'datepicker', 
							                      'type'=>'text',
							                      'label'=>false,
							                      'style' => 'width:72px',
							                      'readonly'=>'readonly',
							                      'value'=>$fchrealizadotoShow,
							                      ));
            ?></td>
		
		<?php 
		//los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
		if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14){ ?>
			<td><?php echo $this->Form->input('Eventosimpuesto.'.$i.'.monc',array('value'=>$eventosimpuesto['Eventosimpuesto']['monc'],'label'=>false, 'style' => 'width:70px')); ?></td>
			<?php } ?>			
		<td><?php echo $this->Form->input('Eventosimpuesto.'.$i.'.descripcion',array('value'=>$eventosimpuesto['Eventosimpuesto']['descripcion'],'label'=>false, 'style' => 'width:100px')); ?>
		</td>					
	</tr>
	<?php $i=$i+1;
	 endforeach; ?>
	</table>
	<div style="width:100%; float:right;">
		<a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>
		<a href="#" onclick="$('#FormPagarEventoImpuesto').submit();" class="btn_aceptar" style="margin-top:14px">Aceptar</a>
		<?php  $this->Form->end();?>
	</div>



  

