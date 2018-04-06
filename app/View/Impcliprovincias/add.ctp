<div class="impcliprovincias form" style="width: 100%;">
<?php if(isset($error)){
	echo $error;
}else{	
 	echo $this->Form->create('Impcliprovincia',array('class'=>'formTareaCarga formAddImpcliprovincia','type' => 'post')); ?>
	<h3><?php
	$cargarProvincia=true;
	if(isset($partidos)){
		$cargarProvincia=true;
	}else{
		$cargarProvincia=false;
	}
	if($cargarProvincia){
		echo __('Relacionar Provincia al Impuesto');
	}else{
		echo __('Relacionar Localidad al Impuesto');
	}
		?></h3>

<table class="tabla">
	<tr>
		<td colspan=3>
			<div class="div_view">
				<?php
					echo $this->Form->input('Impcliprovincia.id',array('type'=>'hidden'));
					echo  $this->Form->input('Impcliprovincia.impcli_id',array('type'=>'hidden','value'=>$impcliid));
					if($cargarProvincia){
						echo $this->Form->input('Impcliprovincia.partido_id',array('label'=>'Provincia','class'=>'chosen-select'));
					}else{
						echo $this->Form->input('Impcliprovincia.localidade_id',array('label'=>'Localidad','class'=>'chosen-select'));
					}
					echo $this->Form->input('Impcliprovincia.periodo', array(
							'class'=>'datepicker-month-year',
							'type'=>'text',
							'label'=>'Periodo Desde',
							'required'=>true,
							'style' => 'width:100px',
							'readonly'=>'readonly',
							'default' =>  date("m-Y")
						)
					);
					//si el impuesto es actividades varias o acti
					if($impuestoid==174/*Convenio Multilateral*/) {
						echo $this->Form->input('Impcliprovincia.coeficiente');
						echo $this->Form->input('Impcliprovincia.sede',array(
								'label'=>'Sede',
								'options' => array(
									0=>'NO',
									1=>'SI',
								),
								'required' => true,
							)
						);
						echo $this->Form->input('ejercicio',array('options'=>array('Resto'=>'Resto','Primero'=>'Primero','Ultimo'=>'Ultimo')));
					}else if($impuestoid==6/*Actividades Varias*/||$impuestoid==21/*Actividades Economicas*/){
						echo $this->Form->input('Impcliprovincia.coeficiente',array('type'=>'hidden','value'=>1));
						echo $this->Form->input('Impcliprovincia.sede',array(
								'label'=>'Sede',
								'type'=>'hidden',
								'value' => 1,
								'required' => true,
							)
						);
						echo $this->Form->input('ejercicio',array('value'=>'Resto','type'=>'hidden'));
					}
//					if($impuestoid==6/*Actividades Varias*/){
//						echo $this->Form->input('Impcliprovincia.minimo');
//					}
					echo $this->Form->input('jsonactividadcliente',array('type'=>'hidden','value'=>json_encode($actividadclientes)))."</br>";

		foreach ($actividadclientes as $key => $actividadcliente) {
			//aca tengo que preguntar si ya existe la actividad guardada en un
			// encuadrealicuota si no existe deberia mostrar un fomulario para agregarla
			// y si existe deberia mostrar un formulario para modificarla|
			$encuadreAlicuotaId=0;
			$encuadreAlicuotaAlicuota=0;
			$encuadreAlicuotaConcepto=0;
			$encuadreAlicuotaMinimo=0;
			$actividadClienteId=$actividadcliente['Actividadcliente']['id'];
			$actividadClienteCodigo=$actividadcliente['Actividade']['descripcion'];
			$actividadClienteNombre=$actividadcliente['Actividade']['nombre']."-".$actividadcliente['Actividadcliente']['descripcion'];
			$precargado = 0;
			if(isset($this->request->data['Encuadrealicuota'])){
				if(count($this->request->data['Encuadrealicuota'])>0){
					foreach ($this->request->data['Encuadrealicuota'] as $encuadrealicuota) {
						if ($encuadrealicuota['actividadcliente_id']==$actividadcliente['Actividadcliente']['id']) {
							$encuadreAlicuotaId = $encuadrealicuota['id'];
							$actividadClienteCodigo = $actividadcliente['Actividade']['descripcion'];
							$actividadClienteNombre = $actividadcliente['Actividade']['nombre']."-".$actividadcliente['Actividadcliente']['descripcion'];
							$encuadreAlicuotaAlicuota = $encuadrealicuota['alicuota'];
							$encuadreAlicuotaConcepto = $encuadrealicuota['concepto'];
							$encuadreAlicuotaMinimo = $encuadrealicuota['minimo'];
							$precargado = 1;
							echo $this->Form->input('Encuadrealicuota.'.$key.'.precargado',array('type'=>'hidden','value'=>1));
						}
					}
				}
			}
			echo '<div class="div_view index">';
			echo $this->Form->input('Encuadrealicuota.'.$key.'.id',array('type'=>'hidden','value'=>$encuadreAlicuotaId));
			echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_id',array('type'=>'hidden','value'=>$actividadClienteId));
			echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_codigo',array(
					'style'=>'width:50px',
					'div'=>['style'=>'width:56px'],
					'label'=>'Codigo',
					'readonly'=>'readonly',
					'value'=>$actividadClienteCodigo
				)
			);
			echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_nombre',array(
				'label'=>'Nombre',
				'style'=>'width:400px',
				'readonly'=>'readonly',
				'value'=>$actividadClienteNombre,
				'div'=>array(
					'style'=>'width:410px'
				),
			));
			echo $this->Form->input('Encuadrealicuota.'.$key.'.alicuota_id',array('type' => 'select','label'=>'Sugerida'));
			echo $this->Form->input('Encuadrealicuota.'.$key.'.alicuota',array('value'=>$encuadreAlicuotaAlicuota));
			if($impuestoid==21/*Actividades Economicas*/||$impuestoid==6/*Actividades Varias*/){
                $title = "Para actividades no conexas: cargar un minimo para cada actividad.</br>
                          Para actividades conexas: solo cargar el minimo cuyo importe sea mayor, el resto en 0";
				echo $this->Form->input('Encuadrealicuota.'.$key.'.minimo',array(
                    'value'=>$encuadreAlicuotaMinimo,
                    'title'=>$title
                ));
			}
			echo $this->Form->input('Encuadrealicuota.'.$key.'.concepto',array(
					'precargado'=>$precargado,
					'style'=>'width:400px',
					'value'=>$encuadreAlicuotaConcepto,
					'div'=>array(
						'style'=>'width:410px'
					)
				))."</br>";
			echo '</div>';
		}
	?>
	</div>
	</td>
	<tr>
		<td>&nbsp;</td>
		<td>
			<a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>  
		</td>s
		<td>
			<?php echo $this->Form->end(__('Aceptar')); ?>
		</td>
	</tr>
</table>
		
<?php	 
}

if($mostrarLista&&!isset($error)){ ?>
	<table cellpadding="0" cellspacing="0" border="0">
	    	<thead>
 				<?php if($cargarProvincia){?>
 				<td>Provincia</td>
				<?php }else{ ?>
 				<td>Localidad</td>
				<?php } ?>
 				<td>Periodo Desde</td>
				<td>Coeficiente</td>
<!--				<td>Minimo</td>-->
 				<td>Sede</td>
 				<td>Ejercicio</td>
 				<?php
	           foreach ($actividadclientes as $key => $actividadcliente) {
					echo "<td>".$actividadcliente['Actividade']['descripcion']."</td>";
					}
				?>
 				<td>Acciones</td>
	    	</thead>
	    	<tbody>
		 	<?php
	            foreach ($impcliprovincias as $impcliprovincia) { ?>
         			<tr id="#rowImpcliProvincia<?php echo $impcliprovincia['Impcliprovincia']['id']; ?>">  
						<?php if($cargarProvincia){?>
						<td><?php echo $impcliprovincia['Partido']['nombre'];?></td>
						<?php }else{ ?>
						<td><?php echo $impcliprovincia['Localidade']['nombre'];?></td>
						<?php } ?>
         				<td><?php echo $impcliprovincia['Impcliprovincia']['periodo'];?></td>
						<td><?php echo $impcliprovincia['Impcliprovincia']['coeficiente'];?></td>
<!--						<td>--><?php //echo $impcliprovincia['Impcliprovincia']['minimo'];?><!--</td>-->
         				<td><?php echo $impcliprovincia['Impcliprovincia']['sede']? 'SI':'NO';?></td>
         				<td><?php echo $impcliprovincia['Impcliprovincia']['ejercicio'];?></td>
         				<?php
						foreach ($impcliprovincia['Encuadrealicuota'] as $key => $encuadrealicuota) {
							echo "<td>".$encuadrealicuota['alicuota']."</td>";
						}
						?>
         				<td>
         					<a href="#"  onclick="deleteImpcliProvincia(<?php echo $impcliprovincia['Impcliprovincia']['id']; ?>)" class="button_view"> 
                                 <?php echo $this->Html->image('delete.png', array('alt' => 'open','title' => 'Eliminar','class'=>'img_trash'));?>
                            </a>
							<a href="#"  onclick="editImpcliProvincia('<?php echo $impcliprovincia['Impcliprovincia']['id']?>','<?php echo $impcliprovincia['Impcliprovincia']['impcli_id']?>')" class="button_view">
								<?php echo $this->Html->image('edit_view.png', array('alt' => 'open','title' => 'Editar','class'=>'img_edit'));?>
							</a>
                    	</td>
         			</tr>            
		   	<?php } ?>
	    	</tbody>
	</table>
<?php }?>
</div>

