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
                $params['data']['Movimientosbancario'][$k]['fecha'] = date('Y-m-d',strtotime($mifecha));
                if(strtotime($mifecha)===false){
                    die("fallo al leer la fecha ".$mifecha);
                }
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
}
