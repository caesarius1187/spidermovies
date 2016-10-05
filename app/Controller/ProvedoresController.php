<?php
App::uses('AppController', 'Controller');
/**
 * Provedores Controller
 *
 * @property Provedore $Provedore
 * @property PaginatorComponent $Paginator
 */
class ProvedoresController extends AppController {

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
	public function index() {
		$this->Provedore->recursive = 0;
		$this->set('provedores', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Provedore->exists($id)) {
			throw new NotFoundException(__('Invalid provedore'));
		}
		$options = array('conditions' => array('Provedore.' . $this->Provedore->primaryKey => $id));
		$this->set('provedore', $this->Provedore->find('first', $options));
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
			$this->Provedore->create();
			if ($this->Provedore->save($this->request->data)) {
				$data['respuesta']='El Provedor ha sido Guardado..';
				$id = $this->Provedore->getLastInsertID();
				$options = array('conditions' => array('Provedore.' . $this->Provedore->primaryKey => $id));
				$data['provedor']=$this->Provedore->find('first', $options);
			} else {
				$data['respuesta']='El Provedor no ha sido Guardado. Por favor intente de nuevo mas tarde';
			}
		}else{
			$data['respuesta']='El Provedore no ha sido Guardado. Por favor intente de nuevo mas tarde';
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');
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
		if (!$this->Provedore->exists($id)) {
			throw new NotFoundException(__('Provedor Invalido'));
		}
		$data=array();
		if ($this->request->is('post')) {
			if ($this->Provedore->save($this->request->data)) {
				$data['provedore'] = $this->request->data['Provedore'];
				$data['respuesta']='El Provedor ha sido Guardado.';
			} else {
				$data['respuesta']='El Provedor no ha sido Guardado. Por favor intente de nuevo mas tarde 0.';
			}
			$showform = false;
		} else {

		}
		$options = array('conditions' => array('Provedore.' . $this->Provedore->primaryKey => $id));
		$this->request->data = $this->Provedore->find('first', $options);
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
		$this->Provedore->id = $id;
		if (!$this->Provedore->exists()) {
			throw new NotFoundException(__('Invalid provedore'));
		}

		$options = array(
			'contain'=>array('Compra'),
			'conditions' => array('Provedore.' . $this->Provedore->primaryKey => $id));
		$miprovedor = $this->Provedore->find('first', $options);

		if(count($miprovedor['Compra'])>0){
			$data['error'] = "El Provedor tiene ventas ya cargadas por favor eliminelas antes de borrar este provedor";
			$data['respuesta'] = "El Provedor tiene ventas ya cargadas por favor eliminelas antes de borrar este subcliente";
		}else{
			$this->request->onlyAllow('post', 'delete');
			$data=array();
			if ($this->Provedore->delete()) {
				$data['respuesta'] = "El Provedor ha a sido eliminado";
			}else {
				$data['respuesta'] = "El Provedor NO ha sido eliminado.Por favor intente de nuevo mas tarde";
			}
		}
		$this->set(compact('data'));
		$this->layout = 'ajax';
		$this->render('serializejson');
	}}
