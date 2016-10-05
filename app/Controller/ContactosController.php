<?php
App::uses('AppController', 'Controller');
/**
 * Contactos Controller
 *
 * @property Contacto $Contacto
 * @property PaginatorComponent $Paginator
 */
class ContactosController extends AppController {

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
		$this->Contacto->recursive = 0;
		$this->set('contactos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Contacto->exists($id)) {
			throw new NotFoundException(__('Invalid contacto'));
		}
		$options = array('conditions' => array('Contacto.' . $this->Contacto->primaryKey => $id));
		$this->set('contacto', $this->Contacto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Contacto->create();
			if ($this->Contacto->save($this->request->data)) {
				$this->Session->setFlash(__('The contacto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contacto could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Contacto->Cliente->find('list');
		$this->set(compact('clientes'));
	}
	public function addajax($cliid = null,$razon = null,$valor= null,$valor2= null) {
	 	$this->request->onlyAllow('ajax');

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
		if (!$this->Contacto->exists($id)) {
			throw new NotFoundException(__('Invalid contacto'));
		}
		if ($this->request->is('post')) {
			if ($this->Contacto->save($this->request->data)) {
				$this->Session->setFlash(__('The contacto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contacto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Contacto.' . $this->Contacto->primaryKey => $id));
			$this->request->data = $this->Contacto->find('first', $options);
		}
		$clientes = $this->Contacto->Cliente->find('list');
		$this->set(compact('clientes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Contacto->id = $id;
		if (!$this->Contacto->exists()) {
			throw new NotFoundException(__('Invalid contacto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Contacto->delete()) {
			$this->Session->setFlash(__('The contacto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The contacto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
