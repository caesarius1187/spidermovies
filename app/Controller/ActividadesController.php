<?php
App::uses('AppController', 'Controller');
/**
 * Actividades Controller
 *
 * @property Actividade $Actividade
 * @property PaginatorComponent $Paginator
 */
class ActividadesController extends AppController {

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
		$this->Actividade->recursive = 0;
		$this->set('actividades', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Actividade->exists($id)) {
			throw new NotFoundException(__('Invalid actividade'));
		}
		$options = array('conditions' => array('Actividade.' . $this->Actividade->primaryKey => $id));
		$this->set('actividade', $this->Actividade->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Actividade->create();
			if ($this->Actividade->save($this->request->data)) {
				$id = $this->Actividade->getLastInsertID();
				$options = array('conditions' => array('Actividade.' . $this->Actividade->primaryKey => $id));
				$this->set('actividade', $this->Actividade->find('first', $options));
			} else {
				$this->set('respuesta','La Actividad No ha sido creado.');	
			}
		}
		$this->layout = 'ajax';
		$this->render('add');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Actividade->exists($id)) {
			throw new NotFoundException(__('Invalid actividade'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Actividade->save($this->request->data)) {
				$this->Session->setFlash(__('The actividade has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The actividade could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Actividade.' . $this->Actividade->primaryKey => $id));
			$this->request->data = $this->Actividade->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $cliid=null) {
		$this->Actividade->id = $id;
		if (!$this->Actividade->exists()) {
			throw new NotFoundException(__('Actividad invalida'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Actividade->delete()) {
		}
		return $this->redirect(array(
			'controller'=>'clientes',
			'action' => 'view',
			$cliid));
	}}
