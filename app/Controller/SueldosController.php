<?php
App::uses('AppController', 'Controller');
/**
 * Sueldos Controller
 *
 * @property Deposito $Deposito
 * @property PaginatorComponent $Paginator
 */
class SueldosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


/**
 * add method
 *
 * @return void
 */
	public function add() {
		$resp ="";
		$this->autoRender=false; 
		if ($this->request->is('post')) {

			$this->request->data('Sueldo.fecha',date('Y-m-d',strtotime($this->request->data['Sueldo']['fecha'])));				

			$this->Sueldo->create();
			$id = 0;
			$options = array(
					'conditions' => array(
						'Sueldo.id'=> $this->request->data['Sueldo']['id'],
						)
					);
			$createdSuel = $this->Sueldo->find('first', $options);
			$suelCreado= false;
			
			if(count($createdSuel)>0){
				//el impcli ya esta creado por lo que ahora resta buscar los periodos activos y ver si se puede crear uno
				$suelCreado= true;
				$this->set('suelCreado','Error1: El sueldo ya esta relacionado.');	
				$id = $createdSuel['Sueldo']['id'];
				$this->set('ruta','sueldo ya creado');
			}else{
				unset($this->request->data['Sueldo']['id']);
				$this->set('ruta','sueldo NO creado');
				
			}
			if ($this->Sueldo->save($this->request->data)) {
				if(!$suelCreado){
					$id = $this->Sueldo->getLastInsertID();
				}
				$options = array(
					'conditions' => array(
						'Sueldo.' . $this->Sueldo->primaryKey => $id
						)
					);
				$createdSuel = $this->Sueldo->find('first', $options);	
				$this->set('sueldo',$createdSuel);
				$this->autoRender=false; 		
				$this->layout = 'ajax';
				$this->render('add');		
				return;									
			}
			else {
				$this->set('respuesta','Error: NO se creo el sueldo. Intente de nuevo.');	
				$this->autoRender=false; 
				$this->layout = 'ajax';
				$this->render('add');
				return;
			}
		}	else {
			$this->set('respuesta','Error: NO se creo el sueldo. Intente de nuevo. (500)');	
			$this->autoRender=false; 
			$this->layout = 'ajax';
			$this->render('add');
			return;
		}		
	}
	public function addajax(){
	 	$this->autoRender=false; 
	 	if ($this->request->is('post')) { 		
			$this->Sueldo->create();
			if($this->request->data['Sueldo']['fecha']!="")
				$this->request->data('Sueldo.fecha',date('Y-m-d',strtotime($this->request->data['Sueldo']['fecha'])));				
			if ($this->Sueldo->save($this->request->data)) {
				
				$data = array(
		            "respuesta" => "El Sueldo ha sido creado.".$this->request->data['Sueldo']['fecha'],
		            "sueldo_id" => $this->Sueldo->getLastInsertID(),
		            "sueldo"=> $this->request->data
		        );
			}
			else{
				$data = array(
		        	"respuesta" => "El Sueldo NO ha sido creado.Intentar de nuevo mas tarde",
		            "sueldo_id" => $this->Sueldo->getLastInsertID()
		        );
			}
			$this->layout = 'ajax';
	        $this->set('data', $data);
			$this->render('serializejson');
			
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
		if (!$this->Sueldo->exists($id)) {
			throw new NotFoundException(__('Sueldo No Existe'));
			return;
		}
		$mostrarForm=true;
		if(!empty($this->data)){ 
			$id = $this->request->data['Sueldo']['id'];
			$this->request->data['Sueldo']['fecha'] = $this->request->data['Sueldo']['sueldofecha'.$id];
			$this->request->data('Sueldo.fecha',date('Y-m-d',strtotime($this->request->data['Sueldo']['fecha'])));
			if ($this->Sueldo->save($this->request->data)) {
				$this->Session->setFlash(__('El Sueldo ha sido Modificado.'));				
			} else {
				$this->Session->setFlash(__('El Sueldo no ha sido Modificado. Por favor, intente de nuevo mas tarde.'));
			}
			$options = array('conditions' => array('Sueldo.' . $this->Sueldo->primaryKey => $id));
			$this->set('sueldo',$this->Sueldo->find('first', $options));
			$mostrarForm=false;			
		}
		$this->set('mostrarForm',$mostrarForm);	
		$options = array('conditions' => array('Sueldo.' . $this->Sueldo->primaryKey => $id));
		$this->request->data = $this->Sueldo->find('first', $options);
		$this->set('sueid',$id);
		$this->layout = 'ajax';
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Sueldo->id = $id;
		if (!$this->Sueldo->exists()) {
			throw new NotFoundException(__('Invalid Sueldo'));
		}
		$this->request->onlyAllow('post');
		$data = array();
		if ($this->Sueldo->delete()) {
			$data['respuesta'] = 'Sueldo eliminado con exito.';
			$data['error'] = 0;
		} else {
			$data['respuesta'] = 'Sueldo NO eliminado. Por favor intente mas tarde.';
			$data['error'] = 1;

		}
		$this->layout = 'ajax';
        $this->set('data', $data);
		$this->render('serializejson');

	}

}