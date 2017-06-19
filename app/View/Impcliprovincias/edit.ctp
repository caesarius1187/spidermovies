<div class="impcliprovincias form edit" style="width: 100%;">
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
			echo __('Modificar Provincia del Impuesto');
		}else{
			echo __('Modificar Localidad del Impuesto');
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
					echo $this->Form->input('Impcliprovincia.ano',array(
							'label'=>'Año',
							'class'=>'chosen-select',
							'options' => array(
								'2014'=>'2014',
								'2015'=>'2015',
								'2016'=>'2016',
								'2017'=>'2017',
								'2018'=>'2018',
								'2019'=>'2019',
								'2020'=>'2020',
							),
							'empty' => 'Elegir año',
							'required' => true,
							'placeholder' => 'Por favor seleccione año',
							'default' =>  date("Y")
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
						$actividadClienteNombre=$actividadcliente['Actividade']['nombre'];
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
						echo '<div class="div_view">';
						echo $this->Form->input('Encuadrealicuota.'.$key.'.id',array('type'=>'hidden','value'=>$encuadreAlicuotaId));
						echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_id',array('type'=>'hidden','value'=>$actividadClienteId));
						echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_codigo',array('label'=>'Codigo','readonly'=>'readonly','value'=>$actividadClienteCodigo));
						echo $this->Form->input('Encuadrealicuota.'.$key.'.actividadcliente_nombre',array(
							'label'=>'Nombre',
							'style'=>'width:400px',
							'readonly'=>'readonly',
							'value'=>$actividadClienteNombre,
							'div'=>array(
								'style'=>'width:410px'
							),
						));
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
			</td>
			<td>
				<?php echo $this->Form->end(__('Aceptar')); ?>
			</td>
		</tr>
	</table>

<?php
}