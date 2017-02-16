<?php
App::uses('AppController', 'Controller');
/**
 * Actividadclientes Controller
 *
 * @property Actividadcliente $Actividadcliente
 * @property PaginatorComponent $Paginator
 */
class ActividadclientesController extends AppController {

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
		$this->Actividadcliente->recursive = 0;
		$this->set('actividadclientes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Actividadcliente->exists($id)) {
			throw new NotFoundException(__('Invalid actividadcliente'));
		}
		$options = array('conditions' => array('Actividadcliente.' . $this->Actividadcliente->primaryKey => $id));
		$this->set('actividadcliente', $this->Actividadcliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	 	$this->autoRender=false; 		
		$respuesta ="";
		$error = 0;
		if ($this->request->is('post')) {
			$this->Actividadcliente->create();
			if ($this->Actividadcliente->save($this->request->data)) {
				$respuesta='The actividadcliente has been saved.';
				$opcActividades = array('conditions' => array('Actividadcliente.id' =>  $this->Actividadcliente->getLastInsertID() ));
				$actividad = $this->Actividadcliente->find('first', $opcActividades);
				$error = 0;
			} else {
				$respuesta='The actividadcliente could not be saved. Please, try again.';
				$error = 1;
			}
		}
		$this->set(compact('respuesta','actividad','error'));
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
		if (!$this->Actividadcliente->exists($id)) {
			throw new NotFoundException(__('Invalid actividadcliente'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Actividadcliente->save($this->request->data)) {
				$this->Session->setFlash(__('The actividadcliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The actividadcliente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Actividadcliente.' . $this->Actividadcliente->primaryKey => $id));
			$this->request->data = $this->Actividadcliente->find('first', $options);
		}
		$clientes = $this->Actividadcliente->Cliente->find('list');
		$actividades = $this->Actividadcliente->Actividade->find('list');
		$this->set(compact('clientes', 'actividades'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null,$cliid = null) {
		$this->Actividadcliente->id = $id;
		if (!$this->Actividadcliente->exists()) {
			throw new NotFoundException(__('Invalid actividadcliente'));
		}
		$this->request->onlyAllow('post');

		//No tenemos que eliminar la actividad si tiene cosas relacionadas a ella.
		if (!$this->Actividadcliente->exists($id)) {
			throw new NotFoundException(__('Invalid actividadcliente'));
		}
		$options = [
			'contain'=>[
				'Venta'=>[
				],
				'Compra'=>[
				],
				'Encuadrealicuota'=>[
				],
				'Basesprorrateada'=>[
				],
				'Cuentasganancia'=>[
				],

			],
			'conditions' => [
				'Actividadcliente.' . $this->Actividadcliente->primaryKey => $id
			]
		];
		$actividadcliente = $this->Actividadcliente->find('first', $options);
		Debugger::dump($actividadcliente);
		if(count($actividadcliente['Venta'])>0){
			$this->Session->setFlash(__('La Actividad del Cliente tiene Ventas relacionadas y por eso no se puede eliminar. 
			Eliminelas y luego intente borrar esta actividad.'));
			return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
		}
		if(count($actividadcliente['Compra'])>0){
			$this->Session->setFlash(__('La Actividad del Cliente tiene Compras relacionadas y por eso no se puede eliminar. 
			Eliminelas y luego intente borrar esta actividad.'));
			return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
		}
		if(count($actividadcliente['Encuadrealicuota'])>0){
			$this->Session->setFlash(__('La Actividad del Cliente tiene Encuadre alicuotas de IIBB relacionados y por eso no se puede eliminar. 
			Eliminelos y luego intente borrar esta actividad.'));
			return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
		}
		if(count($actividadcliente['Cuentasganancia'])>0){
			$this->Session->setFlash(__('La Actividad del Cliente tiene Cuentas de Ganancia y por eso no se puede eliminar. 
			Eliminelas y luego intente borrar esta actividad.'));
			return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
		}

		if ($this->Actividadcliente->delete()) {
			$this->Session->setFlash(__('La Actividad del Cliente ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La Actividad del Cliente NO ha sido eliminada.'));
		}
		return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
	}}
