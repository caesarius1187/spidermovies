<?php
App::uses('AppController', 'Controller');
/**
 * Bancosysindicatos Controller
 *
 * @property Bancosysindicato $Bancosysindicato
 * @property PaginatorComponent $Paginator
 */
class BancosysindicatosController extends AppController {

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
		$this->Bancosysindicato->recursive = 0;
		$this->set('bancosysindicatos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Bancosysindicato->exists($id)) {
			throw new NotFoundException(__('Invalid bancosysindicato'));
		}
		$options = array('conditions' => array('Bancosysindicato.' . $this->Bancosysindicato->primaryKey => $id));
		$this->set('bancosysindicato', $this->Bancosysindicato->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Bancosysindicato->create();
			if ($this->Bancosysindicato->save($this->request->data)) {
				$this->Session->setFlash(__('The bancosysindicato has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The bancosysindicato could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Bancosysindicato->Cliente->find('list');
		$this->set(compact('clientes'));
	}


	public function addajax(
		$cliid = null,$razon = null,$nombre= null,$usuario= null,$clave= null,
		$labeldatoadicional = null,$datoadicional= null) {

	 	$this->request->onlyAllow('ajax');

		$this->Bancosysindicato->create();
		$this->Bancosysindicato->set('cliente_id',$cliid);
		$this->Bancosysindicato->set('razon',$razon);
		$this->Bancosysindicato->set('nombre',$nombre);
		$this->Bancosysindicato->set('usuario',$usuario);
		$this->Bancosysindicato->set('clave',$clave);

		$this->Bancosysindicato->set('labeldatoadicional',$labeldatoadicional);
		$this->Bancosysindicato->set('datoadicional',$datoadicional);
		
		if ($this->Bancosysindicato->save($this->request->data)) {
			$this->set('respuesta','El Banco ha sido creado.');	
			$this->set('bancosysindicato_id',$this->Bancosysindicato->getLastInsertID());
		}
		else{

		}
		$this->layout = 'ajax';
		$this->render('addajax');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Bancosysindicato->exists($id)) {
			throw new NotFoundException(__('Invalid bancosysindicato'));
		}
		if ($this->request->is('post')) {
			if ($this->Bancosysindicato->save($this->request->data)) {
				$this->Session->setFlash(__('Las modificaciones han sido guardadas.'));
				return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $this->request->data['Bancosysindicato']['cliente_id']));
			} else {
				$this->Session->setFlash(__('The bancosysindicato could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Bancosysindicato.' . $this->Bancosysindicato->primaryKey => $id));
			$this->request->data = $this->Bancosysindicato->find('first', $options);
		}
		$clientes = $this->Bancosysindicato->Cliente->find('list');
		$this->set(compact('clientes'));
	}


	public function editajax(
				$id=null,$cliid = null) {

	 	//$this->request->onlyAllow('ajax');

		if (!$this->Bancosysindicato->exists($id)) {
			throw new NotFoundException(__('Dirección inválida.-'));
		}
		
		$options = array('conditions' => array('Bancosysindicato.' . $this->Bancosysindicato->primaryKey => $id));
		$this->request->data = $this->Bancosysindicato->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Bancosysindicato->Cliente->find('list', $optionsCli);

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
		$this->Bancosysindicato->id = $id;
		if (!$this->Bancosysindicato->exists()) {
			throw new NotFoundException(__('Invalid bancosysindicato'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Bancosysindicato->delete()) {
			$this->Session->setFlash(__('The bancosysindicato has been deleted.'));
		} else {
			$this->Session->setFlash(__('The bancosysindicato could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}