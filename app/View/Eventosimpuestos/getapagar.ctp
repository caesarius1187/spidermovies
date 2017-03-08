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
<div>
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
			echo $this->Form->input('Eventosimpuesto.'.$i.'.montovtoreal',array(
				'type'=>'hidden','value'=>$eventosimpuesto['Eventosimpuesto']['montovto']));
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
		<?php echo $this->Form->end();?>
	</div>
</div>
<div>
	<div class="index">
		<h3><?php echo __('Contabilizar Pago de : '.$impclinombre); ?></h3>
		<?php
		$id = 0;
		$nombre = "Asiento devengamiento Pago de impuesto: ".$impclinombre;
		$descripcion = "Asiento automatico";
		$fecha = date('d-m-Y');
		$miAsiento=array();
		if(!isset($miAsiento['Movimiento'])){
			$miAsiento['Movimiento']=array();
		}

		if(isset($asientoyacargado['Asiento'])){
			$miAsiento = $asientoyacargado['Asiento'];
			$id = $miAsiento['id'];
			$nombre = $miAsiento['nombre'];
			$descripcion = $miAsiento['descripcion'];
			$fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
		}

		echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add']);
		echo $this->Form->input('Asiento.0.id',['default'=>$id]);
		echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
		echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
		echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
		echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliid,'type'=>'hidden']);
		echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
		echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcliid,'type'=>'hidden']);
		echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'pagos','type'=>'hidden']);
		/*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
		/*2. Si no existe se la crea y la traigo*/
		/*3. Si existe la traigo*/
		$i=0;
		echo "</br>";
		$cuentaclienteid = 0;
		$asientoestandares=$impuesto['Asientoestandare'];
		foreach ($asientoestandares as $asientoestandar) {
			$cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
			/*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                    con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
			$movid=0;
			$asiento_id=0;
			$debe=0;
			$haber=0;
			$key=0;


			/*Aca vamos a reescribir el debe y el haber si es que corresponde para esta cuenta con este cliente*/
			//Este switch controla todas las cuetnas que hay en "ventas" obligadamente
			switch ($asientoestandar['Cuenta']['id']){
				case '1518'/*110399001 Cliente xx*/:
				case '1868'/*110399001 Cliente xx*/:
				case '1500'/*110399001 Cliente xx*/:
				case '1468'/*110399001 Cliente xx*/:
				case '1492'/*110399001 Cliente xx*/:
				case '1426'/*110399001 Cliente xx*/:
				case '1427'/*110399001 Cliente xx*/:
				case '1397'/*110399001 Cliente xx*/:
				case '1403'/*110399001 Cliente xx*/:
				case '1406'/*110399001 Cliente xx*/:
				case '3375'/*110399001 Cliente xx*/:
				case '1412'/*110399001 Cliente xx*/:
				case '1428'/*110399001 Cliente xx*/:
				case '1401'/*110399001 Cliente xx*/:
				case '1402'/*110399001 Cliente xx*/:
				case '1414'/*110399001 Cliente xx*/:
				case '260'/*110399001 Cliente xx*/:
				case '2544'/*506140001 Autonomo*/:
				case '265'/*110403102 Ganancias - Anticipos a Computar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
					}
				$debe = $cuentaAPagar;
				break;
				case '1383'/*210302001 Ap. Seguridad Social a Pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='301EmpleadorAportesSegSocial'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1384'/*210302002 Ap. Obra Social a Pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='302AportesObrasSociales'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1419'/*210303001 Contr. Seg. Social a Pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='351ContribucionesSegSocial'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1420'/*210303002 Contr. Obra Social a Pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='352ContribucionesObraSocial'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1421'/*210303003 ART a Pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='312AsegRiesgodeTrabajoL24557'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1422'/*210303004 Seguro de Vida Colectivo a Pag*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='28SegurodeVidaColectivo'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '1423'/*210303005 RENATRE a pagar*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='935RENATEA'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				case '3377'/*210302062 Ap. RENATEA a Pagar */:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						//aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
						if($eventosimpuesto['Eventosimpuesto']['item']=='360ContribuciónRENATEA'){
							$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
						}
					}
					$debe = $cuentaAPagar;
					break;
				default:
					
					break;
			}
			if(isset($asientoyacargado['Movimiento'])) {
				foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento){
					if(!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
						$asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
					}
					if($cuentaclienteid==$movimiento['cuentascliente_id']){

						$key=$kMov;
						$movid=$movimiento['id'];
						$asiento_id=$movimiento['asiento_id'];
						$debe=$movimiento['debe'];
						$haber=$movimiento['haber'];
						$asientoyacargado['Movimiento'][$kMov]['cargado']=true;
					}
				}
			}
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
				'default'=>$cuentaclienteid,
				'defaultoption'=>$cuentaclienteid,
				'class'=>'chosen-select-cuenta',
			]);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
				'type'=>'hidden',
				'readonly','readonly',
				'value'=>date('d-m-Y'),
			));
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',['default'=>$debe]);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',['default'=>$haber]);
			echo "</br>";
			$i++;
		}
		//cuentas comun a todos los pagos
		foreach ($cuentaspagoimpuestos as $kMov => $cuentaspagoimpuesto) {
			$movid = 0;
			$asiento_id = 0;
			$debe = 0;
			$haber = 0;
			$cuentaclienteid = $cuentaspagoimpuesto['Cuentascliente'][0]['id'];
			switch ($cuentaspagoimpuesto['Cuenta']['id']){
				/*Casos comun a todas las ventas*/
				case '5' /*110101002 Caja Efectivo*/:
					$cuentaAPagar = 0;
					//Cargar la venta total
					foreach ($eventosimpuestos as $eventosimpuesto){
						$cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
					}
					$haber = $cuentaAPagar;
					break;
			}
			if(isset($asientoyacargado['Movimiento'])) {
				if(!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
					$asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
				}
				foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
					if($cuentaclienteid==$movimiento['cuentascliente_id']) {
						$movid = $movimiento['id'];
						$asiento_id = $movimiento['asiento_id'];
						$asientoyacargado['Movimiento'][$kMov]['cargado']=true;
					}
				}
			}

			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['default' => $movid]);
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', ['default' => $cuentaclienteid]);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
				'type'=>'hidden',
				'readonly','readonly',
				'value'=>date('d-m-Y'),
			));
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', ['default' => $debe]);
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', ['default' => $haber]);
			echo "</br>";
			$i++;
		}
		/*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
        entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
		if(isset($asientoyacargado['Movimiento'])) {
			foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
				$movid = 0;
				$debe = 0;
				$haber = 0;
				if(!$asientoyacargado['Movimiento'][$kMov]['cargado']) {
					$movid = $movimiento['id'];
					$asiento_id = $movimiento['asiento_id'];
					$debe = $movimiento['debe'];
					$haber = $movimiento['haber'];
					$asientoyacargado['Movimiento'][$kMov]['cargado']=true;
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id', ['default' => $movid]);
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id', ['default' => $movimiento['cuentascliente_id']]);
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
						'type'=>'hidden',
						'readonly','readonly',
						'value'=>date('d-m-Y'),
					));
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe', ['value' => $debe]);
					echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber', ['value' => $haber]);
					echo "</br>";
					$i++;
				}
			}
		}
		echo $this->Form->end('Guardar asiento');
		?>
	</div>

</div>




  

