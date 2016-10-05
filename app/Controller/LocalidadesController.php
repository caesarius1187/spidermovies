<?php
App::uses('AppController', 'Controller');
/**
 * Localidades Controller
 *
 * @property Localidade $Localidade
 * @property PaginatorComponent $Paginator
 */
class LocalidadesController extends AppController {
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');



	public function getbycategory() {
		$partido_id = $this->request->data['Domicilio']['partido_id'];
		 
		$localidades = $this->Localidade->find(
			'list', 
			array(
				'conditions' => array('Localidade.partido_id' => $partido_id),
				'recursive' => -1
			)
		);
		 
		$this->set('localidades',$localidades);
		$this->layout = 'ajax';
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Localidade->recursive = 0;
		$this->set('localidades', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Localidade->exists($id)) {
			throw new NotFoundException(__('Invalid Localidade'));
		}
		$options = array('conditions' => array('Localidade.' . $this->Localidade->primaryKey => $id));
		$this->set('localidades', $this->Localidade->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Localidade->create();
			if ($this->Localidade->save($this->request->data)) {
				$this->Session->setFlash(__('The Localidade has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Localidade could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Localidade->exists($id)) {
			throw new NotFoundException(__('Invalid Localidade'));
		}
		if ($this->request->is('post')) {
			if ($this->Localidade->save($this->request->data)) {
				$this->Session->setFlash(__('The Localidade has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Localidade could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Localidade.' . $this->Localidade->primaryKey => $id));
			$this->request->data = $this->Localidade->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Localidade->id = $id;
		if (!$this->Localidade->exists()) {
			throw new NotFoundException(__('Invalid Localidade'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Localidade->delete()) {
			$this->Session->setFlash(__('The Localidade has been deleted.'));
		} else {
			$this->Session->setFlash(__('The Localidade could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}