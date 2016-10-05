<?php
App::uses('AppController', 'Controller');
/**
 * Archivos Controller
 *
 * @property Archivo $Archivo
 * @property PaginatorComponent $Paginator
 */
class ArchivosController extends AppController {

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
		$this->Archivo->recursive = 0;
		$this->set('archivos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Archivo->exists($id)) {
			throw new NotFoundException(__('Invalid archivo'));
		}
		$options = array('conditions' => array('Archivo.' . $this->Archivo->primaryKey => $id));
		$this->set('archivo', $this->Archivo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Archivo->create();
			if ($this->Archivo->save($this->request->data)) {
				$this->Session->setFlash(__('The archivo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The archivo could not be saved. Please, try again.'));
			}
		}
		$eventosimpuestos = $this->Archivo->Eventosimpuesto->find('list');
		$this->set(compact('eventosimpuestos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Archivo->exists($id)) {
			throw new NotFoundException(__('Invalid archivo'));
		}
		if ($this->request->is('post')) {
			if ($this->Archivo->save($this->request->data)) {
				$this->Session->setFlash(__('The archivo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The archivo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Archivo.' . $this->Archivo->primaryKey => $id));
			$this->request->data = $this->Archivo->find('first', $options);
		}
		$eventosimpuestos = $this->Archivo->Eventosimpuesto->find('list');
		$this->set(compact('eventosimpuestos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Archivo->id = $id;
		if (!$this->Archivo->exists()) {
			throw new NotFoundException(__('Invalid archivo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Archivo->delete()) {
			$this->Session->setFlash(__('The archivo has been deleted.'));
		} else {
			$this->Session->setFlash(__('The archivo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
