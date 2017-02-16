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
		$this->set(compact('cliente','cliid','impcliid','cbuid','cliid','periodo','folderMovimientosbancarios'));

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
