<?php
App::uses('AppController', 'Controller');
/**
 * Basesprorrateadas Controller
 *
 * @property Basesprorrateada $Basesprorrateada
 * @property PaginatorComponent $Paginator
 */
class BasesprorrateadasController extends AppController {

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
		$this->Basesprorrateada->recursive = 0;
		$this->set('basesprorrateadas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Basesprorrateada->exists($id)) {
			throw new NotFoundException(__('Invalid basesprorrateada'));
		}
		$options = array('conditions' => array('Basesprorrateada.' . $this->Basesprorrateada->primaryKey => $id));
		$this->set('basesprorrateada', $this->Basesprorrateada->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Basesprorrateada->create();
			if ($this->Basesprorrateada->save($this->request->data)) {
				$this->Session->setFlash(__('The basesprorrateada has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The basesprorrateada could not be saved. Please, try again.'));
			}
		}
		$eventos = $this->Basesprorrateada->Evento->find('list');
		$impcliprovincias = $this->Basesprorrateada->Impcliprovincium->find('list');
		$actividadclientes = $this->Basesprorrateada->Actividadcliente->find('list');
		$impclis = $this->Basesprorrateada->Impcli->find('list');
		$this->set(compact('eventos', 'impcliprovincias', 'actividadclientes', 'impclis'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Basesprorrateada->exists($id)) {
			throw new NotFoundException(__('Invalid basesprorrateada'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Basesprorrateada->save($this->request->data)) {
				$this->Session->setFlash(__('The basesprorrateada has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The basesprorrateada could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Basesprorrateada.' . $this->Basesprorrateada->primaryKey => $id));
			$this->request->data = $this->Basesprorrateada->find('first', $options);
		}
		$eventos = $this->Basesprorrateada->Evento->find('list');
		$impcliprovincias = $this->Basesprorrateada->Impcliprovincium->find('list');
		$actividadclientes = $this->Basesprorrateada->Actividadcliente->find('list');
		$impclis = $this->Basesprorrateada->Impcli->find('list');
		$this->set(compact('eventos', 'impcliprovincias', 'actividadclientes', 'impclis'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Basesprorrateada->id = $id;
		if (!$this->Basesprorrateada->exists()) {
			throw new NotFoundException(__('Invalid basesprorrateada'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Basesprorrateada->delete()) {
			$this->Session->setFlash(__('The basesprorrateada has been deleted.'));
		} else {
			$this->Session->setFlash(__('The basesprorrateada could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
