<?php
App::uses('AppController', 'Controller');
/**
 * Periodosactivos Controller
 *
 * @property Periodosactivo $Periodosactivo
 * @property PaginatorComponent $Paginator
 */
class PeriodosactivosController extends AppController {

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
	public function index($impcli_id=null) {
		$this->loadModel('Impcli');
		$this->Periodosactivo->recursive = 0;
		$options= array('conditions' => array('Periodosactivo.impcli_id'=>$impcli_id), );
		$periodosactivos = $this->Periodosactivo->find('all',$options);
		$this->set('periodosactivos', $periodosactivos);
		
		$this->Periodosactivo->recursive=0;
		$optionsImpCli= array('conditions' => array('Impcli.id'=>$impcli_id), );		
		$impcli = $this->Periodosactivo->Impcli->find('first',$optionsImpCli);
		$this->set(compact('impcli'));
		
		$this->layout = 'ajax';
		$this->render('index');	
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Periodosactivo->exists($id)) {
			throw new NotFoundException(__('Invalid periodosactivo'));
		}
		$options = array('conditions' => array('Periodosactivo.' . $this->Periodosactivo->primaryKey => $id));
		$this->set('periodosactivo', $this->Periodosactivo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->autoRender=false; 
		$data=array();
		if ($this->request->is('post')) {
			$this->Periodosactivo->create();
			if ($this->Periodosactivo->save($this->request->data)) {
				$data['respuesta']='Guardado con Exito';
			} else {
				$data['respuesta']='Error al guardar por favor intente mas tarde.';
			}
		}
		$optionsPA=array(
			'contain'=>array(
				'Impcli'=>array(
					'Impuesto'
					)
				),
			'conditions'=>array(
				'Periodosactivo.id'=>$this->request->data['Periodosactivo']['id']
				)
			);
		$data['periodosactivo']=$this->Periodosactivo->find('first',$optionsPA);
		$this->set('data', $data);
		$this->layout = 'ajax';
		$this->render('serializejson');	
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Periodosactivo->exists($id)) {
			throw new NotFoundException(__('Invalid periodosactivo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Periodosactivo->save($this->request->data)) {
				$this->Session->setFlash(__('The periodosactivo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The periodosactivo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Periodosactivo.' . $this->Periodosactivo->primaryKey => $id));
			$this->request->data = $this->Periodosactivo->find('first', $options);
		}
		$impclis = $this->Periodosactivo->Impcli->find('list');
		$this->set(compact('impclis'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null , $cliid = null) {
		$this->Periodosactivo->id = $id;
		if (!$this->Periodosactivo->exists()) {
			throw new NotFoundException(__('Invalid periodosactivo'));
		}
		
		$this->request->onlyAllow('post', 'delete');
		if ($this->Periodosactivo->delete()) {
			$this->Session->setFlash(__('The periodosactivo has been deleted.'));
		} else {
			$this->Session->setFlash(__('The periodosactivo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('controller'=>'clientes', 'action' => 'index',$cliid));
	}}
