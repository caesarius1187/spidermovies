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
<h3><?php echo __('Conceptos a pagar de : '.$impclinombre); ?></h3>
<?php echo $this->Form->create('Eventosimpuesto',array('action'=>'realizartarea13', 'id'=>'FormPagarEventoImpuesto')); ?>
<table cellpadding="0" cellspacing="0" id="tablePapelesPreparados" class="tbl_getpagar" style="width: auto;">
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
			<th>A Pagar</th>
			<th>Monto Pagado</th>
			<th>Fch. Pagado</th>
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
          			<?php echo $this->Form->input('Eventosimpuesto.'.$i.'.partido_id',array(
						'value'=>$eventosimpuesto['Eventosimpuesto']['partido_id'],
						'label'=>false,
						'style' => 'width:160px',
						'disabled'=>'disabled')); ?>
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
      				<?php echo $this->Form->input('Eventosimpuesto.'.$i.'.item',array(
						'value'=>$eventosimpuesto['Eventosimpuesto']['item'],
						'label'=>false,
						'style' => 'width:185px',
						'orden' => $i,
						'disabled'=>'disabled')); ?>
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
				'type'=>'hidden',
				'value'=>$eventosimpuesto['Eventosimpuesto']['montovto']
			));
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
	echo '<div style="width:100%;" id="divAsientoDePagoEventoImpuesto"></div>';

	<div style="width:100%; float:right;">
<!--		<a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>-->
<!--		<a href="#" onclick="$('#FormPagarEventoImpuesto').submit();" class="btn_aceptar" style="margin-top:14px">Aceptar</a>-->
		<?php echo $this->Form->end();?>
	</div>
</div>

<div id="divContenedorContabilidad" style="margin-top:10px;width: 100%;min-width: 600px;">
	<div class="index_pdt">
		<h3><?php echo __('Asiento de Pago de : '.$impclinombre); ?></h3>
		<?php
		$id = 0;
		$nombre = "Pago de impuesto: ".$impclinombre;
		$descripcion = "Automatico";
		$fecha = date('d-m-Y');
		$miAsiento=array();
        $totalDebe=0;
        $totalHaber=0;
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
                    $haberyacargado=0;
                    $debeyacargado=0;
                    $key=0;

                    $relacionar = "no";
                    $relacionarA = "";//este campo va a tomar valor solo cuando el debe tenga que tomar valor
                    //segun lo que se paga cuando se esta completando el formulario para registrar el pago.

                    /*Aca vamos a reescribir el debe y el haber si es que corresponde para esta cuenta con este cliente*/
                    //Este switch controla todas las cuetnas que hay en "ventas" obligadamente
                    switch ($asientoestandar['Cuenta']['id']){
                        case '260'/*110399001 Cliente xx*/:
                        case '265'/*110403102 Ganancias - Anticipos a Computar*/:
                        case '1389'/*110399001 Ap. SEC a Pagar*/:
                        case '1392'/*210303024 Ap. FAECYS a Pagar*/:
                        case '1397'/*110399001 Cliente xx*/:
                        case '1401'/*110399001 Cliente xx*/:
                        case '1402'/*110399001 Cliente xx*/:
                        case '1403'/*110399001 Cliente xx*/:
                        case '1406'/*110399001 Cliente xx*/:
                        case '1412'/*110399001 Cliente xx*/:
                        case '1414'/*110399001 Cliente xx*/:
                        case '1427'/*210303025 Contr. INACAP a Pagar*/:
                        case '1428'/*110399001 Cliente xx*/:
                        case '1443'/*110399001 Cliente xx*/:
                        case '1458'/*110399001 Cliente xx*/:
                        case '1468'/*110399001 Cliente xx*/:
                        case '1477'/*210401801 Autonomo A Pagar*/:
                        case '1481'/*110399001 Cliente xx*/:
                        case '1492'/*110399001 Cliente xx*/:
                        case '1496'/*110399001 Cliente xx*/:
                        case '1500'/*110399001 Cliente xx*/:
                        case '1518'/*110399001 Cliente xx*/:
                        //case '2798'/*599000002 Casas Particulares A Pagar*/:
                        case '3375'/*110399001 Cliente xx*/:
                                $cuentaAPagar = 0;
                                //Cargar la venta total
                                foreach ($eventosimpuestos as $eventosimpuesto){
                                        $cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
                                }
                            $debe = $cuentaAPagar;
                            break;
                        case '1383'/*210302001 Ap. Seguridad Social a Pagar*/:
                                $relacionar = "si";
                                $relacionarA = "301EmpleadorAportesSegSocial";
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
                        case '1392'/*210302024 Ap. FAECYS a Pagar*/:
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
                                $relacionar = "si";
                                $relacionarA = "302AportesObrasSociales";
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
                                $relacionar = "si";
                                $relacionarA = "351ContribucionesSegSocial";
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
                                $relacionar = "si";
                                $relacionarA = "352ContribucionesObraSocial";
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
                                $relacionar = "si";
                                $relacionarA = "312AsegRiesgodeTrabajoL24557";
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
                                $relacionar = "si";
                                $relacionarA = "28SegurodeVidaColectivo";
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
                                $relacionar = "si";
                                $relacionarA = "935RENATEA";
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
                        case '1404'/*210302062 Ap. RENATEA a Pagar */:
                                $relacionar = "si";
                                $relacionarA = "360ContribuciónRENATEA";
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
                        case '3383'/*230102010 Ley 260663 Aportes Servicios Domesticos a Pagar*/:
                                $cuentaAPagar = 0;
                                //Cargar la venta total
                                foreach ($eventosimpuestos as $eventosimpuesto){
                                    //aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
                                    $cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
                                }
                                switch ($cuentaAPagar){
                                    case 684:
                                        $debe = 419;
                                        break;
                                    case 1368:
                                        $debe = 838;
                                        break;
                                    case 252:
                                        $debe = 63;
                                        break;
                                    case 176:
                                        $debe = 34;
                                        break;
                                    default:
                                        $debe = $cuentaAPagar;
                                        break;
                                }
                                break;
                        case '3384'/*230102011 Ley 260663 Contribuciones Servicios Domesticos a Pagar*/:
                                $cuentaAPagar = 0;
                                //Cargar la venta total
                                foreach ($eventosimpuestos as $eventosimpuesto){
                                        //aca vamos a controlar que el item que tenga caqrgado coincida con el de la cuenta
                                        $cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
                                }
                                //vamos a tratar de dividir contribuciones de aportes si los montos coinciden sino la cantidad a aportes
                                switch (''.$cuentaAPagar.''){
                                    case 684:
                                        $debe = 265;
                                        break;
                                    case 1368:
                                        $debe = 530;
                                        break;
                                    case 252:
                                        $debe = 189;
                                        break;
                                    case 176:
                                        $debe = 142;
                                        break;
                                    default:
                                        $debe = 0;
                                        break;
                                }
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
                                            $debeyacargado=$movimiento['debe'];
                                            $haber=$movimiento['haber'];
                                            $haberyacargado=$movimiento['haber'];
                                            $asientoyacargado['Movimiento'][$kMov]['cargado']=true;
                                    }
                            }
                    }
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
                        'label' => ($i != 0) ? false : 'Cuenta',
                        'default'=>$cuentaclienteid,
                                        'defaultoption'=>$cuentaclienteid,
                                        'class'=>'chosen-select-cuenta',
                                ]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                            'type'=>'hidden',
                            'readonly','readonly',
                            'value'=>date('d-m-Y'),
                    ));
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                        'label' => ($i != 0) ? false : 'Debe',
                        'class'=>'inputDebe',
                        'yacargado'=>$debeyacargado,
                                        'default'=>number_format($debe, 2, ".", ""),
                                        'relacionara'=>$relacionarA,
                                        'relacionar'=>$relacionar,
                                        'orden'=>$i,
                    ]);
                    $totalDebe += $debe;
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                        'label' => ($i != 0) ? false : 'Haber',
                        'class'=>'inputHaber',
                        'yacargado'=>$haberyacargado,
                                        'default'=>number_format($haber, 2, ".", "")
                                ]);
                    $totalHaber += $haber;
                    echo "</br>";
                                $i++;
                    }
                    
                    //cuentas comun a todos los pagos
                    foreach ($cuentaspagoimpuestos as $cpi => $cuentaspagoimpuesto) {
			$movid = 0;
			$asiento_id = 0;
			$debe = 0;
			$haber = 0;
			$haberyacargado=0;
			$debeyacargado=0;
			$cuentaclienteid = $cuentaspagoimpuesto['Cuentascliente'][0]['id'];
			switch ($cuentaspagoimpuesto['Cuenta']['id']){
                            /*Casos comun a todas las ventas*/
                            case '5' /*110101002 Caja Efectivo*/:
                            case '1069' /*130113001 Dinero en Efectivo*/:
                                $cuentaAPagar = 0;
                                //Cargar la venta total
                                foreach ($eventosimpuestos as $eventosimpuesto){
                                    $cuentaAPagar+=$eventosimpuesto['Eventosimpuesto']['montovto']*1;
                                }
                                $haber = $cuentaAPagar;
                                break;
			}
			if(isset($asientoyacargado['Movimiento'])) {
                            foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
                                if(!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
                                    $asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
                                }
                                if($cuentaclienteid==$movimiento['cuentascliente_id']) {
                                    $movid = $movimiento['id'];
                                    $asiento_id = $movimiento['asiento_id'];
                                    $debe = $movimiento['debe'];
                                    $haber = $movimiento['haber'];
                                    $haberyacargado=$movimiento['debe'];
                                    $debeyacargado= $movimiento['haber'];
                                    $asientoyacargado['Movimiento'][$kMov]['cargado']=true;
                                }
                            }
			}
                        echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['default' => $movid]);
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', [
                            'label' => ($i != 0) ? false : 'Cuenta',
                            'default' => $cuentaclienteid,
                            'class'=>'chosen-select-cuenta',
                            ]
			);
			echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                            'type'=>'hidden',
                            'readonly','readonly',
                            'value'=>date('d-m-Y'),
			));
			echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', [
                            'label' => ($i != 0) ? false : 'Debe',
                            'class'=>'inputDebe',
                            'yacargado' => $debeyacargado,
                            'default' => $debe
			]);
                        $totalDebe += $debe;
                        echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', [
                            'label' => ($i != 0) ? false : 'Haber',
                            'class'=>'inputHaber',
                            'yacargado' => $haberyacargado,
                                            'default' => $haber,
                                            'cuentaasientopago' => 'si',
                                            'cuentacontable' => $cuentaspagoimpuesto['Cuenta']['id'],
                                            'orden' => $i,
                                    ]);
                        $totalHaber += $haber;
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
                            $haberyacargado=0;
                            $debeyacargado=0;
                            if(!$asientoyacargado['Movimiento'][$kMov]['cargado']) {
                                $movid = $movimiento['id'];
                                $asiento_id = $movimiento['asiento_id'];
                                $debe = $movimiento['debe'];
                                $haber = $movimiento['haber'];
                                $debeyacargado=$movimiento['debe'];
                                $haberyacargado=$movimiento['haber'];
                                $asientoyacargado['Movimiento'][$kMov]['cargado']=true;
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id', ['default' => $movid]);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id', [
                                    'label' => ($i != 0) ? false : 'Cuenta',
                                    'default' => $movimiento['cuentascliente_id'],
                                                            'class'=>'chosen-select-cuenta',
                                                    ]);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                                        'type'=>'hidden',
                                        'readonly','readonly',
                                        'value'=>date('d-m-Y'),
                                ));
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe', [
                                    'label' => ($i != 0) ? false : 'Debe',
                                    'class'=>'inputDebe',
                                    'yacargado' => $debeyacargado,
                                            'value' => number_format($debe, 2, ".", "")
                                    ]);
                                $totalDebe += $debe;
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber', [
                                    'label' => ($i != 0) ? false : 'Haber',
                                    'class'=>'inputHaber',
                                    'yacargado' => $haberyacargado,
                                                            'value' => number_format($haber, 2, ".", "")
                                                    ]);
                                $totalHaber += $haber;
                                echo "</br>";
                                $i++;
                            }
			}
                    }
                    echo $this->Form->end();
                    echo $this->Form->label('','&nbsp; ',[
                        'style'=>"display: -webkit-inline-box;width:330px;"
                    ]);
                    echo $this->Form->label('lblTotalDebe',
                        "$".number_format($totalDebe, 2, ".", ""),
                        [
                            'id'=>'lblTotalDebe',
                            'style'=>"display: inline;"
                        ]
                    );
                    echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:70px;"]);
                    echo $this->Form->label('lblTotalHaber',
                        "$".number_format($totalHaber, 2, ".", ""),
                        [
                            'id'=>'lblTotalHaber',
                            'style'=>"display: inline;"
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
	</div>
</div>




  

