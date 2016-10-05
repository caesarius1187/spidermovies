<?php
App::uses('AppController', 'Controller');
/**
 * Ingresos Controller
 *
 * @property Ingresos $Ingresos
 * @property PaginatorComponent $Paginator
 */
class IngresosController extends AppController {

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
		$this->Ingreso->recursive = 0;
		$this->set('ingresos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ingreso->exists($id)) {
			throw new NotFoundException(__('Invalid ingreso'));
		}
		$options = array('conditions' => array('Ingreso.' . $this->Ingreso->primaryKey => $id));
		$this->set('deposito', $this->Ingreso->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Ingreso->create();
			if ($this->Ingreso->save($this->request->data)) {
				$this->Session->setFlash(__('The Ingreso has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Ingreso could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Ingreso->Cliente->find('list');
		$this->set(compact('clientes'));
	}
	public function addajax($cliid = null,$fecha = null,$monto= null,$periodo= null,$desc= null
		,$motivo= null,$tipo= null) {
	 	$this->request->onlyAllow('ajax');

		$this->Ingreso->create();
		$this->Ingreso->set('cliente_id',$cliid);

		$this->Ingreso->set('registro',date('Y-m-d',strtotime($fecha)));

		$this->Ingreso->set('importe',$monto);
		$this->Ingreso->set('periodo',$periodo);
		$this->Ingreso->set('comentario',$desc);
		$this->Ingreso->set('motivo',$motivo);
		$this->Ingreso->set('tipo',$tipo);

		if ($this->Ingreso->save($this->request->data)) {
			$this->set('respuesta','El Ingreso ha sido creado.');	
			$this->set('ingreso_id',$this->Ingreso->getLastInsertID());
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
		if (!$this->Ingreso->exists($id)) {
			throw new NotFoundException(__('Ingreso invalido'));
		}
		if ($this->request->is('post')) {
			$this->request->data['Ingreso']['periodo'] = $this->request->data['Ingreso']['mesdesde'].'-'.$this->request->data['Ingreso']['aniodesde'];
			$this->request->data['Ingreso']['registro'] =date('Y-m-d',strtotime($this->request->data['Ingreso']['registro']));

			if ($this->Ingreso->save($this->request->data)) {
				$this->Session->setFlash(__('El Ingreso ha sido modificado.'));
				return $this->redirect(array('controller'=>'clientes','action' => 'view',$this->request->data['Ingreso']['cliente_id']));
			} else {
				$this->Session->setFlash(__('El Ingreso no ha sido modificado. Por favor, trate de nuevo mas tarde.'));
			}
		} else {
			$options = array('conditions' => array('Ingreso.' . $this->Ingreso->primaryKey => $id));
			$this->request->data = $this->Ingreso->find('first', $options);
		}
		$clientes = $this->Ingreso->Cliente->find('list');
		$this->set(compact('clientes'));
	}
	public function editajax($id=null) {

	 	//$this->request->onlyAllow('ajax');
		if (!$this->Ingreso->exists($id)) {
			throw new NotFoundException(__('Ingreso Invalido'));
		}
		
		$options = array('conditions' => array('Ingreso.' . $this->Ingreso->primaryKey => $id));
		$this->request->data = $this->Ingreso->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $this->request->data['Ingreso']['cliente_id']));
		$clientes = $this->Ingreso->Cliente->find('list', $optionsCli);
	
		$this->set(compact('clientes'));

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
	public function delete($id = null) {
		$this->Ingreso->id = $id;
		if (!$this->Ingreso->exists()) {
			throw new NotFoundException(__('Ingreso Invalido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Ingreso->delete()) {
			$this->Session->setFlash(__('The Ingreso has been deleted.'));
		} else {
			$this->Session->setFlash(__('The Ingreso could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
