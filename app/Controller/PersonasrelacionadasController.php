<?php
App::uses('AppController', 'Controller');
/**
 * Personasrelacionadas Controller
 *
 * @property Personasrelacionada $Personasrelacionada
 * @property PaginatorComponent $Paginator
 */
class PersonasrelacionadasController extends AppController {

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
		$this->Personasrelacionada->recursive = 0;
		$this->set('personasrelacionadas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Personasrelacionada->exists($id)) {
			throw new NotFoundException(__('Invalid personasrelacionada'));
		}
		$options = array('conditions' => array('Personasrelacionada.' . $this->Personasrelacionada->primaryKey => $id));
		$this->set('personasrelacionada', $this->Personasrelacionada->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Personasrelacionada->create();
			if ($this->Personasrelacionada->save($this->request->data)) {
				$id = $this->Personasrelacionada->getLastInsertID();
				$options = array('conditions' => array('Personasrelacionada.' . $this->Personasrelacionada->primaryKey => $id));
				$this->set('persona', $this->Personasrelacionada->find('first', $options));
			} else {
				$this->set('respuesta','La Persona Relacionada no ha sido Guardada. Por favor intente de nuevo mas tarde.');	
			}
		}
		$clientes = $this->Personasrelacionada->Cliente->find('list');
		$localidades = $this->Personasrelacionada->Localidade->find('list');
		$this->set(compact('clientes', 'localidades'));

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
		if (!$this->Personasrelacionada->exists($id)) {
			throw new NotFoundException(__('Invalid personasrelacionada'));
		}
		if ($this->request->is('post')) {
			if ($this->Personasrelacionada->save($this->request->data)) {
				$options = array('conditions' => array('Personasrelacionada.' . $this->Personasrelacionada->primaryKey => $id));
				$this->set('persona', $this->Personasrelacionada->find('first', $options));
			} else {
				$this->set('respuesta','La Persona Relacionada no ha sido Guardada. Por favor intente de nuevo mas tarde.');	
			}
		} else {
			$options = array('conditions' => array('Personasrelacionada.' . $this->Personasrelacionada->primaryKey => $id));
			$this->request->data = $this->Personasrelacionada->find('first', $options);
		}
		$clientes = $this->Personasrelacionada->Cliente->find('list');
		$this->set(compact('clientes'));

		$this->layout = 'ajax';
		$this->render('add');
	}
	public function editajax(
				$id=null,$cliid = null) {
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
	 	//$this->request->onlyAllow('ajax');

		if (!$this->Personasrelacionada->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Personasrelacionada.' . $this->Personasrelacionada->primaryKey => $id));
		$this->request->data = $this->Personasrelacionada->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Personasrelacionada->Cliente->find('list', $optionsCli);
		

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
		$this->Personasrelacionada->id = $id;
		if (!$this->Personasrelacionada->exists()) {
			throw new NotFoundException(__('Invalid personasrelacionada'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Personasrelacionada->delete()) {
			$this->Session->setFlash(__('La persona relacionada ha sido eliminada'));
		} else {
			$this->Session->setFlash(__('La persona relacionada NO ha sido eliminada. Por favor intentelo mas tarde'));
		}
		return $this->redirect(array(
			'controller'=>'clientes',
			'action' => 'view',
			$cliid));
	}}
