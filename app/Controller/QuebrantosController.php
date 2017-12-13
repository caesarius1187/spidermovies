<?php
App::uses('AppController', 'Controller');
/**
 * Quebrantos Controller
 *
 * @property Quebranto $Quebranto
 * @property PaginatorComponent $Paginator
 */
class QuebrantosController extends AppController {

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
            $optionQuebrantos=[];
            $this->set('quebrantos', $this->Quebranto->find('all',$optionQuebrantos));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Quebranto->exists($id)) {
			throw new NotFoundException(__('Invalid quebranto'));
		}
		$options = array('conditions' => array('Quebranto.' . $this->Quebranto->primaryKey => $id));
		$this->set('quebranto', $this->Quebranto->find('first', $options));
                $this->layout = 'ajax';
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Quebranto->create();
			if ($this->Quebranto->save($this->request->data)) {
				$id = $this->Quebranto->getLastInsertID();
				$options = array('conditions' => array('Quebranto.' . $this->Quebranto->primaryKey => $id));
				$this->set('persona', $this->Quebranto->find('first', $options));
			} else {
				$this->set('respuesta','El Quebranto no ha sido Guardado. Por favor intente de nuevo mas tarde.');	
			}
		}
                $optionQuebrantos=[];
                $this->set('quebrantos', $this->Quebranto->find('all',$optionQuebrantos));

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
		if (!$this->Quebranto->exists($id)) {
			throw new NotFoundException(__('Invalid quebranto'));
		}
		if ($this->request->is('post')) {
			if ($this->Quebranto->save($this->request->data)) {
				$options = array('conditions' => array('Quebranto.' . $this->Quebranto->primaryKey => $id));
				$this->set('persona', $this->Quebranto->find('first', $options));
			} else {
				$this->set('respuesta','La Persona Relacionada no ha sido Guardada. Por favor intente de nuevo mas tarde.');	
			}
		} else {
			$options = array('conditions' => array('Quebranto.' . $this->Quebranto->primaryKey => $id));
			$this->request->data = $this->Quebranto->find('first', $options);
		}
		$clientes = $this->Quebranto->Cliente->find('list');
		$this->set(compact('clientes'));

		$this->layout = 'ajax';
		$this->render('add');
	}
	public function editajax(
				$id=null,$cliid = null) {
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
	 	//$this->request->onlyAllow('ajax');

		if (!$this->Quebranto->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Quebranto.' . $this->Quebranto->primaryKey => $id));
		$this->request->data = $this->Quebranto->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Quebranto->Cliente->find('list', $optionsCli);
		

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
	public function delete($id = null, $cliid=null) {
		$this->Quebranto->id = $id;
		if (!$this->Quebranto->exists()) {
			throw new NotFoundException(__('Invalid quebranto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Quebranto->delete()) {
			$this->Session->setFlash(__('La persona relacionada ha sido eliminada'));
		} else {
			$this->Session->setFlash(__('La persona relacionada NO ha sido eliminada. Por favor intentelo mas tarde'));
		}
		return $this->redirect(array(
			'controller'=>'clientes',
			'action' => 'view',
			$cliid));
	}}
