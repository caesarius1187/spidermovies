<?php
App::uses('AppController', 'Controller');
/**
 * Tareasximpxestudios Controller
 *
 * @property Tareasximpxestudio $Tareasximpxestudio
 * @property PaginatorComponent $Paginator
 */
class TareasximpxestudiosController extends AppController {

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
		$this->Tareasximpxestudio->recursive = 0;
		$this->set('tareasximpxestudios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tareasximpxestudio->exists($id)) {
			throw new NotFoundException(__('Invalid tareasximpxestudio'));
		}
		$options = array('conditions' => array('Tareasximpxestudio.' . $this->Tareasximpxestudio->primaryKey => $id));
		$this->set('tareasximpxestudio', $this->Tareasximpxestudio->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tareasximpxestudio->create();
			if ($this->Tareasximpxestudio->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasximpxestudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasximpxestudio could not be saved. Please, try again.'));
			}
		}
		$tareasimpuestos = $this->Tareasximpxestudio->Tareasimpuesto->find('list');
		$estudios = $this->Tareasximpxestudio->Estudio->find('list');
		$this->set(compact('tareasimpuestos', 'estudios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Tareasximpxestudio->exists($id)) {
			throw new NotFoundException(__('Invalid tareasximpxestudio'));
		}
		if ($this->request->is('post')) {
			if ($this->Tareasximpxestudio->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasximpxestudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasximpxestudio could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tareasximpxestudio.' . $this->Tareasximpxestudio->primaryKey => $id));
			$this->request->data = $this->Tareasximpxestudio->find('first', $options);
		}
		$tareasimpuestos = $this->Tareasximpxestudio->Tareasimpuesto->find('list');
		$estudios = $this->Tareasximpxestudio->Estudio->find('list');
		$this->set(compact('tareasimpuestos', 'estudios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Tareasximpxestudio->id = $id;
		if (!$this->Tareasximpxestudio->exists()) {
			throw new NotFoundException(__('Invalid tareasximpxestudio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tareasximpxestudio->delete()) {
			$this->Session->setFlash(__('The tareasximpxestudio has been deleted.'));
		} else {
			$this->Session->setFlash(__('The tareasximpxestudio could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
