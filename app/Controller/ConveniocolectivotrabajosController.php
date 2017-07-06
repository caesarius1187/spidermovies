<?php
App::uses('AppController', 'Controller');
/**
 * Conveniocolectivotrabajos Controller
 *
 * @property Conveniocolectivotrabajo $Conveniocolectivotrabajo
 * @property PaginatorComponent $Paginator
 */
class ConveniocolectivotrabajosController extends AppController {

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
		$this->Conveniocolectivotrabajo->recursive = 0;
		$this->set('conveniocolectivotrabajos', $this->Conveniocolectivotrabajo->find('all',[
			'contain'=>[
				'Impuesto',
				'Cctxconcepto',
			],
		]));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Conveniocolectivotrabajo->exists($id)) {
			throw new NotFoundException(__('Invalid conveniocolectivotrabajo'));
		}
		$options = array(
			'conditions' => array('Conveniocolectivotrabajo.' . $this->Conveniocolectivotrabajo->primaryKey => $id),
			'contain'=> [
				'Impuesto',
				'Cctxconcepto'=>[
					'Concepto'
				]
			],
		);
		$this->set('conveniocolectivotrabajo', $this->Conveniocolectivotrabajo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Conveniocolectivotrabajo->create();
			if ($this->Conveniocolectivotrabajo->save($this->request->data)) {
				$this->Session->setFlash(__('The conveniocolectivotrabajo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The conveniocolectivotrabajo could not be saved. Please, try again.'));
			}
		}
		$impclis = $this->Conveniocolectivotrabajo->Impcli->find('list');
		$this->set(compact('impclis'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Conveniocolectivotrabajo->exists($id)) {
			throw new NotFoundException(__('Invalid conveniocolectivotrabajo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Conveniocolectivotrabajo->save($this->request->data)) {
				$this->Session->setFlash(__('The conveniocolectivotrabajo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The conveniocolectivotrabajo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Conveniocolectivotrabajo.' . $this->Conveniocolectivotrabajo->primaryKey => $id));
			$this->request->data = $this->Conveniocolectivotrabajo->find('first', $options);
		}
		$impclis = $this->Conveniocolectivotrabajo->Impcli->find('list');
		$this->set(compact('impclis'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Conveniocolectivotrabajo->id = $id;
		if (!$this->Conveniocolectivotrabajo->exists()) {
			throw new NotFoundException(__('Invalid conveniocolectivotrabajo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Conveniocolectivotrabajo->delete()) {
			$this->Session->setFlash(__('The conveniocolectivotrabajo has been deleted.'));
		} else {
			$this->Session->setFlash(__('The conveniocolectivotrabajo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
