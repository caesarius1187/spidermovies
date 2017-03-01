<?php
App::uses('AppController', 'Controller');
/**
 * Subclientes Controller
 *
 * @property Subcliente $Subcliente
 * @property PaginatorComponent $Paginator
 */
class SubclientesController extends AppController {

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
	public function index($cliid=null) {
		$optionsSubcliente=[
			'contain',
			'condition'=>[
				'Subcliente.cliente_id'=>$cliid
			]
		];
		$subclientes = $this->Subcliente->find('all',$optionsSubcliente) = 0;
		$this->set('data',$subclientes);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		/*if (!$this->Puntosdeventa->exists($id)) {
			throw new NotFoundException(__('Invalid Puntosdeventa'));
		}
		$options = array('conditions' => array('Contacto.' . $this->Contacto->primaryKey => $id));
		$this->set('contacto', $this->Contacto->find('first', $options));*/
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->autoRender=false; 
		$data = array();
		if ($this->request->is('post')) {
			$conditionsSubcliente = array(
				'Subcliente.cuit' => $this->request->data['Subcliente']['cuit'],
				'Subcliente.cliente_id' => $this->request->data['Subcliente']['cliente_id'],
			);
			if ($this->Subcliente->hasAny($conditionsSubcliente)){
                $data['respuesta']='Este cliente ya fue creado para este contribuyente';
                $data['subcliente']=0;
            }else{
                $this->Subcliente->create();
                if ($this->Subcliente->save($this->request->data)) {
                    $data['respuesta']='El Subcliente ha sido Guardado..';
                    $id = $this->Subcliente->getLastInsertID();
                    $options = array('conditions' => array('Subcliente.' . $this->Subcliente->primaryKey => $id));
                    $data['subcliente']=$this->Subcliente->find('first', $options);
                } else {
                    $data['respuesta']='El Subcliente no ha sido Guardado. Por favor intente de nuevo mas tarde';
                }
            }
        }else{
			$data['respuesta']='El Subcliente no ha sido Guardado. Por favor intente de nuevo mas tarde';
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');
	}
	
	public function addajax($cliid = null,$razon = null,$valor= null,$valor2= null) {
	 	/*$this->request->onlyAllow('ajax');

		$this->Contacto->create();
		$this->Contacto->set('cliente_id',$cliid);
		$this->Contacto->set('razon',$razon);
		$this->Contacto->set('valor',$valor);
		$this->Contacto->set('valor2',$valor2);
		$this->Contacto->set('estado','habilitado');
		if ($this->Contacto->save($this->request->data)) {
			$this->set('respuesta','El Contacto ha sido creado.');	
			$this->set('contacto_id',$this->Contacto->getLastInsertID());
		}
		else{

		}
		$this->layout = 'ajax';
		$this->render('addajax');*/
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$showform = true;
		if (!$this->Subcliente->exists($id)) {
			throw new NotFoundException(__('Invalid Subcliente'));
		}
		$data=array();
		if ($this->request->is('post')) {
			if ($this->Subcliente->save($this->request->data)) {
				$data['subcliente'] = $this->request->data['Subcliente'];
				$data['respuesta']='El Cliente ha sido Guardado.';
			} else {
				$data['respuesta']='El Cliente no ha sido Guardado. Por favor intente de nuevo mas tarde 0.';
			}
			$showform = false;
		} else {
			
		}
		$options = array('conditions' => array('Subcliente.' . $this->Subcliente->primaryKey => $id));
		$this->request->data = $this->Subcliente->find('first', $options);
		$clientes = $this->Subcliente->Cliente->find('list');
		$this->set(compact('clientes'));
		$this->set('showform',$showform);
		$this->layout = 'ajax';
		$this->render('edit');
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$id = substr($id,0, -5);
		$this->Subcliente->id = $id;
		if (!$this->Subcliente->exists()) {
			throw new NotFoundException(__('Invalid Subcliente'));
		}

		$options = array(
			'contain'=>array('Venta'),
			'conditions' => array('Subcliente.' . $this->Subcliente->primaryKey => $id));
		$micliente = $this->Subcliente->find('first', $options);

		if(count($micliente['Venta'])>0){
			$data['error'] = "El Subcliente tiene ventas ya cargadas por favor eliminelas antes de borrar este subcliente";
			$data['respuesta'] = "El Subcliente tiene ventas ya cargadas por favor eliminelas antes de borrar este subcliente";
		}else{
			$this->request->onlyAllow('post', 'delete');
			$data=array();
			if ($this->Subcliente->delete()) {
				$data['respuesta'] = "El Subcliente ha a sido eliminado";
			}else {
				$data['respuesta'] = "El Subcliente NO ha sido eliminado.Por favor intente de nuevo mas tarde";
			}
		}


		$this->set(compact('data'));
		$this->layout = 'ajax';
		$this->render('serializejson');
	}}
