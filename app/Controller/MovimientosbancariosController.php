<?php
App::uses('AppController', 'Controller');
/**
 * Movimientosbancarios Controller
 *
 * @property Movimientosbancarios $Cbus
 * @property PaginatorComponent $Paginator
 */
class MovimientosbancariosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $alicuotas = array("0" => '0',"2.5" => '2.5',"5" => '5',"10.5" => '10.5',"21" => '21',"27" => '27',);
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() 
	{				
		$movimientos = $this->Movimiento->find('all');
		$this->set('movimientos',$movimientos);				
	}
	public function edit($id ,$cliid = null) {

		$this->loadModel('Cuentascliente');

		$this->set('alicuotas', $this->alicuotas);
		if (!$this->Movimientosbancario->exists($id)) {
			throw new NotFoundException(__('Movimiento bancario No existe'));
			return;
		}
		$mostrarForm=true;
		if(!empty($this->request->data)){
			$this->request->data['Movimientosbancario']['fecha'] = $this->request->data['Movimientosbancario']['fecha'.$id];
			$this->request->data['Movimientosbancario']['fecha'] = date('Y-m-d',strtotime($this->request->data['Movimientosbancario']['fecha']));
			if ($this->Movimientosbancario->save($this->request->data)) {

				$data = [
					"respuesta" => "El Movimiento bancario ha sido modificado.",
					"error" => "0",
				];
				$options = [
					'contain'=>[
						'Cuentascliente'=>[
							'Cuenta'
						],
						'Cbu'
					],
					'conditions' => [
						'Movimientosbancario.' . $this->Movimientosbancario->primaryKey => $id
					]
				];
				$data['movimientosbancario'] = $this->Movimientosbancario->find('first', $options);
				$data['movimientosbancario']['Movimientosbancario']['fecha'] = date('d-m-Y',strtotime($data['movimientosbancario']['Movimientosbancario']['fecha']));
			} else {
				$data = [
					"respuesta" => "El Movimiento bancario NO ha sido modificado.",
					"error" => "1",
				];
			}


			$this->layout = 'ajax';
			$this->set('data',$data);
			$this->set('mostrarForm',false);
			return;
		}
		$this->set('mostrarForm',$mostrarForm);
		$options = [
			'contain'=>[
			],
			'conditions' => [
				'Movimientosbancario.' . $this->Movimientosbancario->primaryKey => $id
			]
		];
		$this->request->data = $this->Movimientosbancario->find('first', $options);

		$cuentaclienteOptions = [
			'conditions' => [
				'Cuentascliente.cliente_id'=> $cliid
			],
			'fields' => [
				'Cuentascliente.id',
				'Cuentascliente.nombre',
				'Cuenta.numero',
			],
			'order'=>['Cuenta.numero'],
			'joins'=>[
				[
					'table'=>'cuentas',
					'alias' => 'Cuenta',
					'type'=>'inner',
					'conditions'=> [
						'Cuentascliente.cuenta_id = Cuenta.id',
					]
				],
			],
		];
		$cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
		$this->set('cuentasclientes', $cuentasclientes);
		$this->layout = 'ajax';
	}
	public function cargar($id=null,$periodo=null){
		// PRIMERO CHEKIAR QUE EL CLIENTE QUE MUESTRA LAS VENTAS SEA PARTE DEL ESTUDIO ACTIVO

		$this->loadModel('Cliente');
		$this->loadModel('Cbu');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Cuenta');

		$pemes = substr($periodo, 0, 2);
		$peanio = substr($periodo, 3);
		//A: Es menor que periodo Hasta
		$esMenorQueHasta = array(
			//HASTA es mayor que el periodo
			'OR'=>array(
				'SUBSTRING(Periodosactivo.hasta,4,7)*1 > '.$peanio.'*1',
				'AND'=>array(
					'SUBSTRING(Periodosactivo.hasta,4,7)*1 >= '.$peanio.'*1',
					'SUBSTRING(Periodosactivo.hasta,1,2) >= '.$pemes.'*1'
				),
			)
		);
		//B: Es mayor que periodo Desde
		$esMayorQueDesde = array(
			'OR'=>array(
				'SUBSTRING(Periodosactivo.desde,4,7)*1 < '.$peanio.'*1',
				'AND'=>array(
					'SUBSTRING(Periodosactivo.desde,4,7)*1 <= '.$peanio.'*1',
					'SUBSTRING(Periodosactivo.desde,1,2) <= '.$pemes.'*1'
				),
			)
		);
		//C: Tiene Periodo Hasta 0 NULL
		$periodoNull = array(
			'OR'=>array(
				array('Periodosactivo.hasta'=>null),
				array('Periodosactivo.hasta'=>""),
			)
		);
		$conditionsImpCliHabilitados = array(
			//El periodo esta dentro de un desde hasta
			'AND'=> array(
				$esMayorQueDesde,
				'OR'=> array(
					$esMenorQueHasta,
					$periodoNull
				)
			)
		);
		$this->set('periodo',$periodo);
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
					'Impcli'=>[
						'Impuesto',
						'Cbu'=>[
							'Movimientosbancario'=>[
								'Cbu',
								'Cuentascliente',
								'conditions'=>[
									'Movimientosbancario.periodo'=>$periodo,
								]
							],
						],
						'Periodosactivo'=>[
							'conditions'=>$conditionsImpCliHabilitados
						]
					]
				),
				'conditions' => array(
					'id' => $id,
				),
			)
		);
		/*AFIP*/
		$tieneMonotributo=False;
		$tieneIVA=False;
		$tieneIVAPercepciones=False;
		$tieneImpuestoInterno=False;
		/*DGR*/
		$tieneAgenteDePercepcionIIBB=False;
		/*DGRM*/
		$tieneAgenteDePercepcionActividadesVarias=False;
		foreach ($cliente['Impcli'] as $impcli) {
			/*AFIP*/
			if($impcli['impuesto_id']==4/*Monotributo*/){
				//Tiene Monotributo asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que es un Monotributista Activo en este periodo
					//Tenemos que asegurarnos que no existan periodos activos que coincidan entre Monotributo e IVA
					$tieneMonotributo=True;
					$tieneIVA=False;
				}
			}
			if($impcli['impuesto_id']==19/*IVA*/){
				//Tiene IVA asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que es un Responsable Inscripto Activo en este periodo
					//Tenemos que asegurarnos que no existan periodos activos que coincidan entre Monotributo e IVA
					$tieneMonotributo=False;
					$tieneIVA=True;
				}
			}
			if($impcli['impuesto_id']==184/*IVA Percepciones*/){
				//Tiene IVA Percepciones asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene IVA Percepciones Activo en este periodo
					$tieneIVAPercepciones=True;
				}
			}
			if($impcli['impuesto_id']==185/*Impuesto Interno*/){
				//Tiene Impuesto Interno asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Impuesto Interno Activo en este periodo
					$tieneImpuestoInterno=True;
				}
			}
			/*DGR*/
			if($impcli['impuesto_id']==173/*Agente de Percepcion IIBB*/){
				//Tiene Agente de Percepcion IIBB asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Agente de Percepcion IIBB Activo en este periodo
					$tieneAgenteDePercepcionIIBB=True;
				}
			}
			/*DGRM*/
			if($impcli['impuesto_id']==186/*Agente de Percepcion de Actividades Varias*/){
				//Tiene Agente de Percepcion IIBB asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Agente de Percepcion de Actividades Varias Activo en este periodo
					$tieneAgenteDePercepcionActividadesVarias=True;
				}
			}
		}
		$cliente['Cliente']['tieneMonotributo'] = $tieneMonotributo;
		$cliente['Cliente']['tieneIVA'] = $tieneIVA;
		$cliente['Cliente']['tieneIVAPercepciones'] = $tieneIVAPercepciones;
		$cliente['Cliente']['tieneImpuestoInterno'] = $tieneImpuestoInterno;
		$cliente['Cliente']['tieneAgenteDePercepcionIIBB'] = $tieneAgenteDePercepcionIIBB;
		$cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias'] = $tieneAgenteDePercepcionActividadesVarias;
		$this->set(compact('cliente'));

		/*Aca vamos a listar las cuentas clientes que se relacionan a los movimientos bancarios
        se supone que ya estan relacionadas al cliente cuando se les dio de alta alguna cuenta bancaria*/
		$cuentasDeMovimientoBancario = $this->Cuenta->cuentasDeMovimientoBancario;
		/*Tenemos que tener en cuenta las cuentas de movimientos grales y las cuentas de acreditaciones
        y extracciones relacionadas a los CBU*/
		$optioncuentascliente = [
			'conditions'=>[
				'Cuentascliente.cuenta_id'=> $cuentasDeMovimientoBancario,
				'Cuentascliente.cliente_id'=> $id,
			]
		];
		$cuentasclientes = $this->Cuentascliente->find('list',$optioncuentascliente);
		$this->set('cuentasclientes', $cuentasclientes);


		$conditionsCli = array(
			'Grupocliente',
		);

		$lclis = $this->Cliente->find('list',array(
			'contain' =>$conditionsCli,
			'conditions' => array(
				'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id')
			),
			'order' => array(
				'Grupocliente.nombre','Cliente.nombre'
			),
		));
		$this->set(compact('lclis'));
		$clienteCbuOptions = array(
			'contain'=>array(
			),
			'joins'=>array(
				array('table'=>'impclis',
					'alias' => 'Impcli',
					'type'=>'inner',
					'conditions'=> array(
						'Impcli.id = Cbu.impcli_id',
						'Impcli.cliente_id'=> $id
					)
				),
			)
		);
		$cbus = $this->Cbu->find('list',$clienteCbuOptions);
		$this->set(compact('cbus'));
		$this->set('alicuotas', $this->alicuotas);
	}

	public function addajax(){
		//$this->request->onlyAllow('ajax');
		$this->autoRender=false;
        $data= array();
		if ($this->request->is('post')) {
			$this->Movimientosbancario->create();
			if($this->request->data['Movimientosbancario']['fecha']!="")
				$this->request->data('Movimientosbancario.fecha',date('Y-m-d',strtotime($this->request->data['Movimientosbancario']['fecha'])));
			if ($this->Movimientosbancario->save($this->request->data)) {
				$data ["respuesta"] = "El Movimiento Bancario ha sido creado con exito.";
                $movimietnoId = $this->Movimientosbancario->getLastInsertId();
                $data['movimiento'] = $this->request->data;
                $data['movimientoid'] = $movimietnoId;
			}
			else{
				$data = array(
					"respuesta" => "El Movimiento Bancario NO ha sido creado con exito.Intentar de nuevo mas tarde",
				);
			}


		}
        $this->layout = 'ajax';
        $this->set('data', $data);
        $this->render('serializejson');
	}
	public function importar($cliid=null,$periodo=null,$impcliid=null,$cbuid=null){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$this->Components->unload('DebugKit.Toolbar');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Cliente');
		$this->loadModel('Cuenta');
		$this->loadModel('Cbu');

		$this->set('alicuotas', $this->alicuotas);

        $folderMovimientosbancarios = WWW_ROOT.'files'.DS.'movimientosbancarios'.DS.$cliid.DS.$cbuid.DS.$periodo.DS.'resumen';
		if ($this->request->is('post')) {
			$folderMovimientosbancarios = WWW_ROOT.'files'.DS.'movimientosbancarios'.DS.$this->request->data['Movimientosbancario']['cliid'].DS.$cbuid.DS.$this->request->data['Movimientosbancario']['periodo'].DS.'resumen';
			$fileNameMovimientosbancario = null;
			$tmpNameMovimientosbancario= $this->request->data['Movimientosbancario']['archivoresumen']['tmp_name'];
			if (!empty($tmpNameMovimientosbancario)&& is_uploaded_file($tmpNameMovimientosbancario)) {
				// Strip path information
				$fileNameMovimientosbancario = $this->request->data['Movimientosbancario']['archivoresumen']['name'];
				move_uploaded_file($tmpNameMovimientosbancario, $folderMovimientosbancarios.DS.$fileNameMovimientosbancario);
			}
		}
        $cuentasrelacionadasbancos = $this->Cuenta->cuentasDeMovimientoBancario;
		$cuentasclientes=$this->Cuentascliente->find('list', array(
				'contain' => array(
					'Cuenta'
				),
				'conditions' => array(
					'Cuentascliente.cliente_id' =>  $cliid,
                    'Cuentascliente.cuenta_id' => $cuentasrelacionadasbancos
				),
				'fields' => array(
					'Cuentascliente.id','Cuentascliente.nombre'
				)
			)
		);
		$this->set('cuentasclientes', $cuentasclientes);
        $cliente=$this->Cliente->find('first', array(
                'contain'=>array(
                ),
                'conditions' => array(
                    'id' => $cliid,
                ),
            )
        );
		$cbu=$this->Cbu->find('first', array(
                'contain'=>array(
					'Impcli'=>[
						'Impuesto'
					]
                ),
                'conditions' => array(
                    'Cbu.id' => $cbuid,
                ),
            )
        );
		$this->set(compact('cliente','cliid','impcliid','cbu','cbuid','cliid','periodo','folderMovimientosbancarios'));

        $optionsmovimientosbancariosperiodo=array(
			'contain'=>array(
			),
			'fields'=>array(
			),
			'conditions'=>array(
				'Movimientosbancario.periodo'=>$periodo,
				'Movimientosbancario.cbu_id'=>$cbuid
			)
		);
        $movimientosbancariosperiodo = $this->Movimientosbancario->find('all',$optionsmovimientosbancariosperiodo);
		$this->set('movimientosbancariosperiodo',$movimientosbancariosperiodo);
		$this->set('alicuotas', $this->alicuotas);
	}
	public function cargarmovimientosbancarios (){
		$data=array();
		if ($this->request->is('post')) {
			$params = array();
			$myParser = new ParserUnlimited();
			//Debugger::dump($this->request->data);
			$myParser->my_parse_str($this->request->data['Movimientosbancario'][0]['jsonencript'],$params);
			foreach ($params['data']['Movimientosbancario'] as $k => $miMovimientosbancario){
				$mifecha = $miMovimientosbancario['fecha'];
				if(strtotime($mifecha)===false){
					$mifecha = str_replace("/","-",$miMovimientosbancario['fecha']);
					if(strtotime($mifecha)===false){
						die("{'data':['respuesta'=>'fallo al leer la fecha ".$mifecha."']}");
					}
				}
				$params['data']['Movimientosbancario'][$k]['fecha'] = date('Y-m-d',strtotime($mifecha));
			}
			$this->Movimientosbancario->create();
			if ($this->Movimientosbancario->saveAll($params['data']['Movimientosbancario'])) {
				//if (1==1) {
				$data['respuesta'] = 'Los Movimientos bancarios han sido guardados.';
			} else {
				$data['respuesta'] = 'Error al guardar los movimientos bancarios, por favor intende de nuevo mas tarde.';
			}
		}else{
			$data['respuesta'] = 'Acceso denegado.';
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');
	}
	public function deletefile($name=null,$cliid=null,$folder=null,$periodo=null,$impcliid=null,$cbuid=null){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$file = WWW_ROOT.'files'.DS.'movimientosbancarios'.DS.$cliid.DS.$cbuid.DS.$periodo.DS.'resumen'.DS.$name;
        chmod($file, 0777);
		if( is_file( $file ) AND is_readable( $file ) ) {
			if (unlink($file)) {
				$this->Session->setFlash(__('El Archivo ha sido eliminado'));
			} else {
				$this->Session->setFlash(__('El Archivo NO ha sido eliminado.Por favor nuevamente intente mas tarde.'));
			}
		}else{
			$error="";
			if(!is_file( $file )){
				$error.=" No es archivo.";
			}
			if(!is_readable( $file ) ){
				$error.=" No se puede Leer.";
			}
			$this->Session->setFlash(__($error.": ".$file));
		}
		return $this->redirect(
			array(
				'controller'=>'movimientosbancarios',
				'action' => 'importar',
				$cliid,
				$periodo,
                $impcliid,
                $cbuid
			)
		);
	}
	public function delete($id = null) {
		$this->Movimientosbancario->id = $id;
		if (!$this->Movimientosbancario->exists()) {
			throw new NotFoundException(__('Invalid Movimientosbancario'));
		}
		$this->request->onlyAllow('post');
		$data = array();
		if ($this->Movimientosbancario->delete()) {
			$data['respuesta'] = 'Movimiento bancario eliminado con exito.';
			$data['error'] = 0;
		} else {
			$data['respuesta'] = 'Movimiento bancario NO eliminado. Por favor intente mas tarde.';
			$data['error'] = 1;
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');
	}
}
