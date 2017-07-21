<?php
App::uses('AppController', 'Controller');
/**
 * Estudios Controller
 *
 * @property Estudio $Estudio
 * @property PaginatorComponent $Paginator
 */
class SuperadminsController extends AppController {

	var $uses = false;
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() 
	{
		//$this->Estudio->recursive = 0;
		$this->loadModel('Estudio');
		$estudios = $this->Estudio->find('all');
		$this->set('estudios', $estudios);
	}

	/*
	public function edit($id = null) 
	{
		$this->loadModel('Estudio');
		if (!$this->Estudio->exists($id)) 
		{
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post')) 
		{
			if ($this->Estudio->save($this->request->data)) 
			{
				$this->Session->setFlash(__('El estudio se ha guardado con exito.'));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('El estudio no se pudo guardar. Por favor, intente nuevamente.'));
			}
		} 
		else 
		{			
			$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
			$this->request->data = $this->Estudio->find('first', $options);
		}
		//$estudios = $this->Estudio->find('list');
		//$this->set(compact('estudios'));
	}

	public function add() 
	{		
		if ($this->request->is('post')) 
		{
			$this->loadModel('Estudio');
			$this->Estudio->create();
			if ($this->Estudio->save($this->request->data)) 
			{
				$this->Session->setFlash(__('El Etudio se ha registrado con exito.'));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('No se pudo registrar el Estudio, intente más tarde.'));
			}
		}
		//$estudios = $this->User->Estudio->find('list');
		//$this->set(compact('estudios'));
	}
	*/
}
