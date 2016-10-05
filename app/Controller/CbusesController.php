<?php
App::uses('AppController', 'Controller');
/**
 * Cbuses Controller
 *
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class CbusesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	public $clitemp;

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Cbus->recursive = 0;
		$this->set('cbuses', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cbus->exists($id)) {
			throw new NotFoundException(__('Invalid cbus'));
		}
		$options = array('conditions' => array('Cbus.' . $this->Cbus->primaryKey => $id));
		$this->set('cbus', $this->Cbus->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cbus->create();
			if ($this->Cbus->save($this->request->data)) {
				$this->Session->setFlash(__('El CBU ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cbus could not be saved. Please, try again.'));
			}
		}
		$bancosysindicatos = $this->Cbus->Bancosysindicato->find('list');
		$this->set(compact('bancosysindicatos'));
	}

	public function addajax(
		$bysid = null,$tipo = null,$numero= null,$cbu= null) {

	 	$this->request->onlyAllow('ajax');

		$this->Cbus->create();
		$this->Cbus->set('bancosysindicato_id',$bysid);
		$this->Cbus->set('tipo',$tipo);
		$this->Cbus->set('numero',$numero);
		$this->Cbus->set('cbu',$cbu);
		
		if ($this->Cbus->save($this->request->data)) {
			$this->set('respuesta','El CBU ha sido creado.');	
			$this->set('cbus_id',$this->Cbus->getLastInsertID());
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
		if (!$this->Cbus->exists($id)) {
			throw new NotFoundException(__('Invalid cbus'));
		}
		if ($this->request->is('post')) {
			if ($this->Cbus->save($this->request->data)) {
				$this->Session->setFlash(__('The cbus has been saved.'));
				return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $this->request->data['Cbus']['clienteid']));
			} else {
				$this->Session->setFlash(__('The cbus could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cbus.' . $this->Cbus->primaryKey => $id));
			$this->request->data = $this->Cbus->find('first', $options);
		}
		$bancosysindicatos = $this->Cbus->Bancosysindicato->find('list');
		$this->set(compact('bancosysindicatos'));
	}

	public function editajax(
				$id=null,$cliid = null) {

		if (!$this->Cbus->exists($id)) {
			throw new NotFoundException(__('Dirección inválida.-'));
		}

		$options = array('conditions' => array('Cbus.' . $this->Cbus->primaryKey => $id));
		$this->request->data = $this->Cbus->find('first', $options);

		$this->set("clienteid",$cliid);

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
		$this->Cbus->id = $id;
		if (!$this->Cbus->exists()) {
			throw new NotFoundException(__('Invalid cbus'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cbus->delete()) {
			$this->Session->setFlash(__('The cbus has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cbus could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
