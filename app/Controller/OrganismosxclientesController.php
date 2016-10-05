<?php
App::uses('AppController', 'Controller');
/**
 * Organismosxclientes Controller
 *
 * @property Organismosxcliente $Organismosxcliente
 * @property PaginatorComponent $Paginator
 */
class OrganismosxclientesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Organismosxcliente->recursive = 0;
		$this->set('organismosxclientes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Organismosxcliente->exists($id)) {
			throw new NotFoundException(__('Invalid organismosxcliente'));
		}
		$options = array('conditions' => array('Organismosxcliente.' . $this->Organismosxcliente->primaryKey => $id));
		$this->set('organismosxcliente', $this->Organismosxcliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Organismosxcliente->create();
			if ($this->Organismosxcliente->save($this->request->data)) {
				$this->Session->setFlash(__('The organismosxcliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The organismosxcliente could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Organismosxcliente->Cliente->find('list');
		$this->set(compact('clientes'));
	}
	/*
	**************************************************************************************************************
	funcion utilizada unicamente para relacionar los organizmos de los clientes que fueron insertados por fuera del sistema 
	**************************************************************************************************************
	*/
	public function relateOrganismos() {
		$this->loadModel('Cliente');

		$conditionsCliAuth = array(
								 'Grupocliente',
								 );
		$clientesAuth = $this->Cliente->find('all');
		foreach ($clientesAuth as $cliente ) {
				/*$organismo = $this->Organismosxcliente->find('all',array(
															'conditions' => array(
													 			'cliente_id' => $cliente['Cliente']['id'] )
															)
														);
	 			if(count($organismo)!=4){	 						
					$this->Organismosxcliente->create();
					$this->Organismosxcliente->set('cliente_id',$cliente['Cliente']['id']);
					$this->Organismosxcliente->set('tipoorganismo','sindicato');
					$this->Organismosxcliente->save();

					$this->Organismosxcliente->create();
					$this->Organismosxcliente->set('cliente_id',$cliente['Cliente']['id']);
					$this->Organismosxcliente->set('tipoorganismo','dgr');
					$this->Organismosxcliente->save();

					$this->Organismosxcliente->create();
					$this->Organismosxcliente->set('cliente_id',$cliente['Cliente']['id']);
					$this->Organismosxcliente->set('tipoorganismo','dgrm');
					$this->Organismosxcliente->save();

					$this->Organismosxcliente->create();
					$this->Organismosxcliente->set('cliente_id',$cliente['Cliente']['id']);
					$this->Organismosxcliente->set('tipoorganismo','sindicato');
					$this->Organismosxcliente->save();
	 			}*/
	 			$this->Organismosxcliente->create();
					$this->Organismosxcliente->set('cliente_id',$cliente['Cliente']['id']);
					$this->Organismosxcliente->set('tipoorganismo','banco');
					$this->Organismosxcliente->save();
			}	
			return $this->redirect(array('action' => 'index'));

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->autoRender=false; 
		if($this->RequestHandler->isAjax()){ 
			Configure::write('debug', 0); } 
		$resp="";
		if(!empty($this->request->data)){  
			if ($this->Organismosxcliente->save($this->request->data)) {
				$resp= "Guardado con Exito";
			} else {
				$resp= "NO guardado con Exito. Por favor intente mas tarde.";
			}
		} 
		$this->set('respuesta',$resp);
		$this->layout = 'ajax';
		$this->render('edit');
	}
public function editAjax($id = null) {
		if (!$this->Organismosxcliente->exists($id)) {
			throw new NotFoundException(__('Invalid organismosxcliente'));
		}
		if ($this->request->is('post')) {
			if ($this->Organismosxcliente->save($this->request->data)) {
				$this->Session->setFlash(__('The organismosxcliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The organismosxcliente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Organismosxcliente.' . $this->Organismosxcliente->primaryKey => $id));
			$this->request->data = $this->Organismosxcliente->find('first', $options);
		}
		$clientes = $this->Organismosxcliente->Cliente->find('list');
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
		$this->Organismosxcliente->id = $id;
		if (!$this->Organismosxcliente->exists()) {
			throw new NotFoundException(__('Invalid organismosxcliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Organismosxcliente->delete()) {
			$this->Session->setFlash(__('The organismosxcliente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The organismosxcliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
