<?php
App::uses('AppController', 'Controller');
/**
 * Eventosimpuestos Controller
 *
 * @property Eventosimpuesto $Eventosimpuesto
 * @property PaginatorComponent $Paginator
 */
class EventosimpuestosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');



	public function realizareventoimpuesto($id = null,$tarea = null,$periodo= null,$implcid= null,$estadoTarea=null) {
	 	$this->request->onlyAllow('ajax');
		
		//Configure::write('debug', 2);
		if (!$this->Eventosimpuesto->exists($id)) {
			//throw new NotFoundException(__('Evento de cliente invalido'));
			$this->Eventosimpuesto->create();
			$this->Eventosimpuesto->set('impcli_id',$implcid);
			$this->Eventosimpuesto->set('periodo',$periodo);
			$this->Eventosimpuesto->set($tarea,$estadoTarea);
			$this->Eventosimpuesto->set('user_id',$this->Session->read('Auth.User.estudio_id'));
			if($this->Eventosimpuesto->save()){
				$this->set('error',0);
				$this->set('respuesta','La tarea ha sido realizada.1');	
				$this->set('evento_id',$this->Eventosimpuesto->getLastInsertID());
			}else{
				$this->set('error',1);
  				$this->set('respuesta','La tarea NO ha sido realizada.2:   ');
			}
		}else{
			$this->Eventosimpuesto->read(null, $id);
			$this->Eventosimpuesto->set($tarea,$estadoTarea);
			$this->Eventosimpuesto->set('plan',0);
			if ($this->Eventosimpuesto->save()) {
				$this->set('error',0);
				$this->set('respuesta','La tarea ha sido realizada.3');	
				$this->set('evento_id',$id);
			} else {
				$this->set('error',1);
  				$this->set('respuesta','La tarea NO ha sido realizada.4:   ');
			    //debug($this->Eventosimpuesto->invalidFields()); die();
			}
		}
		$this->layout = 'ajax';
		$this->render('realizartarea5');
	}
	
	public function realizartarea5() {
		//guardar todos los eventos no importa si tienen ID previo o no

		//Llamado desde getPapelesDeTrabajo
	 	$this->request->onlyAllow('ajax');
		$this->loadModel('Impcli');
        $this->loadModel('Basesprorrateada');
        $this->loadModel('Conceptosrestante');
		$impcliid=0;
		$periodo=0;
		$evenimpPos=0;
		foreach ($this->request->data['Eventosimpuesto'] as $even) {
				$this->request->data['Eventosimpuesto'][$evenimpPos]['fchvto']=date('Y-m-d',strtotime($even['fchvto']));
				$this->request->data['Eventosimpuesto'][$evenimpPos]['tarea5']='realizado';
				$this->request->data['Eventosimpuesto'][$evenimpPos]['user_id']=$this->Session->read('Auth.User.estudio_id');
				$periodo=$even['periodo'];
				$impcliid=$even['impcli_id'];
				$evenimpPos++;
			}

		$options = array(
			'contain'=>array(
				'Impuesto'
			),
			'conditions' => array(
				'Impcli.' . $this->Impcli->primaryKey => $impcliid,
			),
		);
		$impCli = $this->Impcli->find('first', $options);

		$data=array();
		$this->Eventosimpuesto->create();
		if ($this->Eventosimpuesto->saveAll($this->request->data['Eventosimpuesto'])) {
			$data['respuesta']='Registro guardado con exito.';
			$data['error']=0;
			$optionseventosAnteriores = array(
				'conditions' => array(
					'Eventosimpuesto.impcli_id'=> $impcliid,
					'Eventosimpuesto.periodo'=>$periodo
					),	
				'contain' => array()
				);
			$eventosAnteriores = $this->Eventosimpuesto->find('all', $optionseventosAnteriores);
			$numeroAMostrar = 0;
			foreach ($eventosAnteriores as $even) {
				$numeroAMostrar += $even['Eventosimpuesto']['montovto'];
			}
			$data['numero']=$numeroAMostrar;
			$data['requestData']=$this->request->data;
			foreach ($this->request->data['Eventosimpuesto'] as $evenData) {
				switch ($impCli['Impcli']['impuesto_id']){
					case 174/*Convenio Multilateral*/:
					case 21/*Actividades Economicas*/:
						//aca vamos a controlar que si le mandamos una lista de Bases Prorrateadas para guardar
						// las guardemos despues de que el evento impuesto haya sido creado
						if(isset($evenData['Basesprorrateada'])){
							if($evenData['id']==0){//si evenData tiene ID significa que en el formulario ya le pusimos el ID a las bases prorrateadas
								foreach ($eventosAnteriores as $evenSaved) {
									if($evenData['partido_id']==$evenSaved['Eventosimpuesto']['partido_id']){
										foreach ($evenData['Basesprorrateada'] as $key => $basesprorrateada) {
											$evenData['Basesprorrateada'][$key]['eventosimpuesto_id']=$evenSaved['Eventosimpuesto']['id'];//guardo el id del evento guardado en la base prorrateada y ahora si esta lista para ser guardad
											$evenData['id']=$evenSaved['Eventosimpuesto']['id'];
										}
									}
								}
							}
							if($this->Basesprorrateada->saveAll($evenData['Basesprorrateada'])){
								$data['respuesta2'.$evenData['id']]='Registro de Basesprorrateada guardado con exito.';
							}else{
								$data['error']=4;
								$data['respuesta2'.$evenData['id']]='Registro de Basesprorrateada NO guardado. ERROR';
							}
							$data['respuesta3'.$evenData['id']]='tenia baseprorrateada';
						}else{
							$data['respuesta3'.$evenData['id']]='no tenia base';
						}
						break;
					case 6/*Actividades Varias*/:

						break;
					default:
						break;
				}
				//aca vamos a controlar que si le mandamos una lista de conceptos restantes tipo saldo a favor para guardar
				// aquellos que tengas saldo a favor positivo
				if(isset($evenData['Conceptosrestante'])){
                    //Debugger::dump($evenData['Conceptosrestante']);
					if($this->Conceptosrestante->saveAll($evenData['Conceptosrestante'])){
						$data['respuesta22'.$evenData['id']]='Registro de Conceptos restantes guardado con exito.';
					}else{
						$data['error']=5;
						$data['respuesta22'.$evenData['id']]='Registro de Conceptos restantes NO guardado. ERROR';
					}
					$data['respuesta33'.$evenData['id']]='tenia Conceptos restantes';
				}else{
					$data['respuesta33'.$evenData['id']]='no tenia Conceptos restantes';
				}
			}
		}else{
			$data['respuesta']='Error al guardar. Por favonr intente mas tarde';
			$data['error']=1;
			$data['validationErrors']=$this->validationErrors;
			$data['invalidFields']=$this->Eventosimpuesto->invalidFields();
			$data['getLog']=$this->Eventosimpuesto->getDataSource()->getLog(false, false);
			//debug($this->Eventosimpuesto->validationErrors); //show validationErrors
			//debug($this->Eventosimpuesto->getDataSource()->getLog(false, false)); //show last sql query
		}

		$data['getLog']=$this->Eventosimpuesto->getDataSource()->getLog(false, false);
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
	public function getpapelestrabajo($periodo,$impcli){
		$this->loadModel('Cliente');
		$this->loadModel('Impcli');
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
		$this->loadModel('Vencimiento');
		$this->loadModel('Basesprorrateada');
		//4 formas de pagar impuestos Provincia, Municipio, Item , unico
		
		$options = array(
			'contain'=>array(
				'Impuesto'
			),
			'conditions' => array(
				'Impcli.' . $this->Impcli->primaryKey => $impcli,
			),
		);
		$impCli = $this->Impcli->find('first', $options);
		//buscamos el cliente y el organismo del impuesto para mostrar el titulo
		$optionsCli = array(
			'conditions' => array('Cliente.' . $this->Cliente->primaryKey => $impCli['Impcli']['cliente_id']),
			'contain' => array(
                'Venta'=>array(
					'fields'=>array('Count(*) as misventas'),
                    'conditions'=>array('Venta.periodo'=>$periodo)
                ),
				'Compra'=>array(
					'fields'=>array('Count(*) as miscompras'),
					'conditions'=>array('Compra.periodo'=>$periodo)
				),
				'Conceptosrestante'=>array(
					'fields'=>array('Count(*) as misconceptos'),
					'conditions'=>array('Conceptosrestante.periodo'=>$periodo)
				),
				'Actividadcliente',
				'Organismosxcliente'=>array(
					'conditions'=>array('Organismosxcliente.tipoorganismo'=>$impCli['Impuesto']['organismo']),
					)
			),
			'fields'=>array('Cliente.id','Cliente.nombre','Cliente.cuitcontribullente')
			);
		$cliente = $this->Cliente->find('first', $optionsCli);
		$this->set('cliente',$cliente);
		$timePeriodo = strtotime("01-".$periodo ." +1 months");
		$periodoNext = date("m-Y",$timePeriodo);
        $options = array(
			'contain'=>array(
				'Impuesto'=>array(
					'Vencimiento'=>array(
						'conditions'=>array(
							'SUBSTRING("'.$periodo.'",4,7) = Vencimiento.ano*1',
							'Vencimiento.desde <= SUBSTRING("'.$cliente['Cliente']['cuitcontribullente'].'",-1)',
							'Vencimiento.hasta >= SUBSTRING("'.$cliente['Cliente']['cuitcontribullente'].'",-1)',
						),
					),
				),
				'Impcliprovincia'=>array(
					'conditions'=>array(
							"Impcliprovincia.ano = SUBSTRING('".$periodo."',4,7)"
						)
					),
				'Conceptosrestante'=>array(
					'conditions'=>array(
						'Conceptosrestante.periodo'=>$periodoNext,
						'Conceptosrestante.conceptostipo_id'=>1,
					)
				),
			),
			'conditions' => array(
				'Impcli.' . $this->Impcli->primaryKey => $impcli,
			),
		);
		$myImpCli = $this->Impcli->find('first', $options);

		$this->set('conceptosrestantesimpcli',$myImpCli["Conceptosrestante"]);

		$options = array(
			'contain'=>array(
				'Conceptosrestante'=>array(
					'conditions'=>array(
						'Conceptosrestante.periodo'=>$periodo,
						'Conceptosrestante.conceptostipo_id'=>1,
					)
				),
			),
			'conditions' => array(
				'Impcli.' . $this->Impcli->primaryKey => $impcli,
			),
		);
		$myImpCliPeriodoActual = $this->Impcli->find('first', $options);
		$this->set('SaldosLibreDisponibilidadimpcli',$myImpCliPeriodoActual["Conceptosrestante"]);

		$eventoId= 0;
		$fchvtoOrigen="diaDeHoy";
		$fchvto=date('d-m-Y');//Esta fecha debe ser una de las siguientes opciones:
			/* 
			1. La fecha guardada en el evento
			2. La fecha cargada en los Vencimientos del impuesto
			3. La fecha de hoy*/

		$eventosimpuestos=array();
		//Si el impuesto es tipo Sindicato entonces vence el 15(esto es aproximado y cuando tengamos la posibilidad de cargar los vencimientos de todos los sindicatos deberiamos hacerlo)

		$options = array(
			'conditions' => array(
				'Eventosimpuesto.impcli_id'=> $impcli,
				'Eventosimpuesto.periodo'=>$periodo
				),
			'contain'=>array(
				'Basesprorrateada' => array(
					'conditions' =>array ('Basesprorrateada.periodo' => $periodo ),
				),
			)
		);
		$eventosimpuestos = $this->Eventosimpuesto->find('all', $options);

		if(count($eventosimpuestos)!=0){
			//hay fecha guardada en el evento
			$fchvto=date('d-m-Y',strtotime($eventosimpuestos[0]['Eventosimpuesto']['fchvto']));
			$fchvtoOrigen="guardadaEnImpuesto";
		}else{
			//como no hay fecha guardada vamos a buscar el Vencimiento si existe para este impuesto
			
			$mesperiodo = substr($periodo, 0, 2);
			$anoperiodo = substr($periodo, 3, 7);
			switch ( $mesperiodo) {
				case '12':
					
					if($myImpCli["Impuesto"]["organismo"]=='Sindicato'){
						$fchvto = '15-01-'.($anoperiodo+1);
						$fchvtoOrigen="VencimientoRecomendado";
					}else{
						$optionsVencimientoImpuesto = array(
						'conditions'=>array(
								'SUBSTRING("'.$periodo.'",4,7)+1 = Vencimiento.ano*1',
								'Vencimiento.desde <= SUBSTRING("'.$cliente['Organismosxcliente'][0]['usuario'].'",-1)',
								'Vencimiento.hasta >= SUBSTRING("'.$cliente['Organismosxcliente'][0]['usuario'].'",-1)',
								'Vencimiento.impuesto_id'=>$myImpCli["Impuesto"]["id"],
							),
						);
						$vencimiento = $this->Vencimiento->find('first',$optionsVencimientoImpuesto);
						if(isset($vencimiento['Vencimiento']['p01'])&&$vencimiento['Vencimiento']['p01']!=0){
							$strfchvto = strtotime($vencimiento['ano'].'-01-'.$vencimiento['Vencimiento']['p01']);
							$fchvto = date('d-m-Y',$strfchvto);
							$fchvtoOrigen="VencimientoRecomendado";
						}
					}
				break;
				default:
					$this->set('myImpCliImpuestoorganismo',$myImpCli["Impuesto"]["organismo"]);
					if($myImpCli["Impuesto"]["organismo"]=='sindicato'){
						$strfchvto = strtotime($anoperiodo.'-'.$mesperiodo.'-01 +1 months');
						$this->set('strfchvto',$strfchvto);
						$periodoPosterior = date('m',$strfchvto);
						$strfchvto = strtotime($anoperiodo.'-'.$periodoPosterior.'-15');
						$fchvto = date('d-m-Y',$strfchvto);
						$fchvtoOrigen="VencimientoRecomendado";
					}else{
						foreach ($myImpCli["Impuesto"]["Vencimiento"] as $vencimiento) {
							$strfchvto = strtotime('01-'.$mesperiodo.'-'.$anoperiodo.' +1 months');
							$periodoPosterior = date('m',$strfchvto);
							if($vencimiento['p'.$periodoPosterior]!=0){
								$fchvto = $vencimiento['p'.$periodoPosterior].'-'.$periodoPosterior.'-'.$anoperiodo;
								$fchvtoOrigen="VencimientoRecomendado";
							}
						}	
					} 
				break;
			}
		}
		$this->set('eventosimpuestos',$eventosimpuestos);
		$this->set('fchvto',$fchvto);
		$this->set('fchvtoOrigen',$fchvtoOrigen);
		$this->set('partidos',$this->Partido->find('list'));
		$this->set('localidades',$this->Localidade->find('list'));

		$this->set('tipopago',$myImpCli["Impuesto"]["tipopago"]);
		$this->set('clienteid',$myImpCli["Impcli"]["cliente_id"]);
		$this->set('impcliid',$myImpCli["Impcli"]["id"]);
		$this->set('impuesto',$myImpCli["Impuesto"]);
		$this->set('impcliprovincias',$myImpCli["Impcliprovincia"]);
		/*aqui vamos a mandar las opciones para el campo ITEM si es que el impuesto es uno de los siguientes:
		IVA:	Saldo Tecnico 	
				Saldo de Libre Disponibilidad 	
		SUSS:	351	Contribuciones Seg. Social
				301	Empleador - Aportes Seg. Social
				360	Contribución RENATEA
				352	Contribuciones Obra Social
				935	RENATEA
				302	Aportes Obras Sociales
				270	Contrib. Vales Aliment.l.24700
				312	Aseg. Riesgo de Trabajo L 24557
				28	Seguro de Vida Colectivo
		*/
		switch ($myImpCli["Impuesto"]['id']) {
			case 19/*IVA*/:
				$optionsIVA = array(
					'Saldo Tecnico' => 'Saldo Tecnico',
					//'Saldo de Libre Disponibilidad'=>'Saldo de Libre Disponibilidad' ,
					);
				$this->set('optionsIVA',$optionsIVA);
				break;
			case 10/*SUSS*/:
				$optionsSUSS = array(
					'351ContribucionesSegSocial' => '351 Contribuciones Seg. Social',
					'301EmpleadorAportesSegSocial' => '301 Empleador - Aportes Seg. Social',
					'360ContribuciónRENATEA' => '360 Contribución RENATEA',
					'352ContribucionesObraSocial' => '352 Contribuciones Obra Social',
					'935RENATEA' => '935 RENATEA',
					'302AportesObrasSociales' => '302 Aportes Obras Sociales',
					'270ContribValesAlimentl24700' => '270 Contrib. Vales Aliment.l.24700',
					'312AsegRiesgodeTrabajoL24557' => '312 Aseg. Riesgo de Trabajo L 24557',
					'28SegurodeVidaColectivo'=>'28 Seguro de Vida Colectivo' ,
				);
				$this->set('optionsSUSS',$optionsSUSS);
				break;
			case 4/*Monotributo*/:
				$optionsMono = array(
					'Monotributo' => 'Monotributo',
					'Monotributo Autonomo' => 'Monotributo Autonomo',	
					'Monotributo Obra Social' => 'Monotributo Obra Social',	
					);
				$this->set('optionsMono',$optionsMono);
				break;	
			default:
				# code...
				break;
		}
		$this->set('periodo',$periodo);
		$this->layout = 'ajax';
		$this->render('getpapelestrabajo');
	}
	public function getapagar($periodo,$impcli){
		$this->loadModel('Impcli');
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
		$this->loadModel('Deposito');
		//4 formas de pagar impuestos Provincia, Municipio, Item , unico
		
		//Elementos del Fomulario de Pagar Papeles de Trabajo ya generados
		$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcli));
		$myImpCli = $this->Impcli->find('first', $options);
		$eventoId= 0;
		
		$options = array(
			'conditions' => array(
				'Eventosimpuesto.impcli_id'=> $impcli,
				'Eventosimpuesto.periodo'=>$periodo
				)
			);
		$eventosimpuestos = $this->Eventosimpuesto->find('all', $options);
		$this->set('eventosimpuestos',$eventosimpuestos);
					
		$this->set('partidos',$this->Partido->find('list'));
		$this->set('localidades',$this->Localidade->find('list'));

		$this->set('impuesto',$myImpCli["Impuesto"]);
		
		$this->set('clienteid',$myImpCli["Impcli"]["cliente_id"]);
		$this->set('impcliid',$myImpCli["Impcli"]["id"]);
		$this->set('impclinombre',$myImpCli["Impuesto"]["nombre"]);

		$this->set('periodo',$periodo);
		//Elementos del Fomulario de Recibos(Depositos)
		
		$options = array(
			'conditions' => array(
				'Deposito.cliente_id' => $myImpCli["Impcli"]["cliente_id"],
				'Deposito.periodo' => $periodo
				)
		);
		$depositos = $this->Deposito->find('all', $options);
		$this->set('depositos', $depositos);
		$this->set('periodo', $periodo);
		$this->set('cliid', $myImpCli["Impcli"]["cliente_id"]);

		$this->layout = 'ajax';
		$this->render('getapagar');
	}
	public function realizartarea13() {/*PAGAR*/
	 	$this->request->onlyAllow('ajax');
		//Configure::write('debug', 2);
		//throw new NotFoundException(__('Evento de cliente invalido'));
		$this->Eventosimpuesto->create();
		$respuesta = array(
			'error'=>' ',
			'sinerror'=>' '
			);
		foreach ($this->request->data['Eventosimpuesto'] as $even) {
			$even['fchrealizado'] = date('Y-m-d',strtotime($even['fchrealizado']));

		}
		if ($this->Eventosimpuesto->saveAll($this->request->data)) {	
			$respuesta['sinerror'] = 'Los pagos han sido registrados';
			$this->set('data',$respuesta);	
		}else{
		   // debug($this->Eventosimpuesto->invalidFields()); die();
			//$this->set('data',$this->validationErrors.print_r($this->validationErrors, true));
			
			//No pude hacer andar el Save All
			foreach ($this->request->data['Eventosimpuesto'] as $even) {
				$this->Eventosimpuesto->set($even);
				$this->Eventosimpuesto->set('fchrealizado',date('Y-m-d',strtotime($even['fchrealizado'])));
				$this->Eventosimpuesto->set('tarea13','realizado');
				if($this->Eventosimpuesto->save()){
					$respuesta['sinerror'] = $respuesta['sinerror']. 'El pago '.$even['fchrealizado'].' ha sido registrado. ';
				}
			}
			$this->set('data',$respuesta);
			//.print_r($this->Eventosimpuesto->validationErrors, true));	
		}
		
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
	
	public function delete($id = null) {
		$this->Eventosimpuesto->id = $id;
		if (!$this->Eventosimpuesto->exists()) {
			throw new NotFoundException(__(' Evento Impuesto Invalido'));
		}
		$this->request->onlyAllow('post', 'delete');
		$data=array();

        $mieventoimpuesto = $this->Eventosimpuesto->find('first', array(
            'conditions' => array(
                'Eventosimpuesto.id'=> $id,
            ),
        ));
        $impcliid = $mieventoimpuesto['Eventosimpuesto']['impcli_id'];
        $periodo = $mieventoimpuesto['Eventosimpuesto']['periodo'];

		if ($this->Eventosimpuesto->delete()) {

			$optionseventosAnteriores = array(
				'conditions' => array(
					'Eventosimpuesto.impcli_id'=> $impcliid,
					'Eventosimpuesto.periodo'=>$periodo
				),
				'contain' => array()
			);
			$eventosAnteriores = $this->Eventosimpuesto->find('all', $optionseventosAnteriores);
			$numeroAMostrar = 0;
			foreach ($eventosAnteriores as $even) {
				$numeroAMostrar += $even['Eventosimpuesto']['montovto'];
			}

            $data['impcliid'] = $impcliid;
            $data['periodo'] = $periodo;
            $data['numeroAMostrar'] = $numeroAMostrar;
            $data['respuesta'] = "El evento ha sido eliminado";
		} else {
			$data['respuesta'] = "El evento NO a sido eliminado";
		}
		$this->set(compact('data'));
		$this->layout = 'ajax';
		$this->render('serializejson');
	}

}