<?php
App::uses('AppController', 'Controller');
/**
 * Deducciones Controller
 *
 * @property Deduccione $Deduccione
 * @property PaginatorComponent $Paginator
 */
class DeduccionesController extends AppController {

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
        $this->Deduccione->recursive = 0;
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
        if (!$this->Deduccione->exists($id)) {
                throw new NotFoundException(__('Invalid personasrelacionada'));
        }
        $options = array('conditions' => array('Deduccione.' . $this->Deduccione->primaryKey => $id));
        $this->set('personasrelacionada', $this->Deduccione->find('first', $options));
    }

/**
 * add method
 *
 * @return void
 */
    public function add($impcliid) {
        if ($this->request->is('post')) {
            $this->Deduccione->create();
            if ($this->Deduccione->save($this->request->data)) {
                $id = $this->Deduccione->getLastInsertID();
                $options = array('conditions' => array('Deduccione.' . $this->Deduccione->primaryKey => $id));
                $this->set('deduccion', $this->Deduccione->find('first', $options));
            } else {
                $this->set('respuesta','La Deduccion no ha sido Guardada. Por favor intente de nuevo mas tarde.');	
                $this->set('error',1);	
            }
        }else{
            
        }
        $optionsDeduccione=[
            'contain'=>[],
            'conditions'=>[
                'Deduccione.impcli_id'=>$impcliid
            ]
        ];
        $deducciones=$this->Deduccione->find('all',$optionsDeduccione);
        $this->set(compact('impcliid','deducciones'));
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }else{
        }
        
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
		if (!$this->Deduccione->exists($id)) {
			throw new NotFoundException(__('Invalid personasrelacionada'));
		}
		if ($this->request->is('post')) {
			if ($this->Deduccione->save($this->request->data)) {
				$options = array('conditions' => array('Deduccione.' . $this->Deduccione->primaryKey => $id));
				$this->set('persona', $this->Deduccione->find('first', $options));
			} else {
				$this->set('respuesta','La Persona Relacionada no ha sido Guardada. Por favor intente de nuevo mas tarde.');	
			}
		} else {
			$options = array('conditions' => array('Deduccione.' . $this->Deduccione->primaryKey => $id));
			$this->request->data = $this->Deduccione->find('first', $options);
		}
		$clientes = $this->Deduccione->Cliente->find('list');
		$this->set(compact('clientes'));

		$this->layout = 'ajax';
		$this->render('add');
	}
	public function editajax(
				$id=null,$cliid = null) {
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
	 	//$this->request->onlyAllow('ajax');

		if (!$this->Deduccione->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Deduccione.' . $this->Deduccione->primaryKey => $id));
		$this->request->data = $this->Deduccione->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Deduccione->Cliente->find('list', $optionsCli);
		

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
        $this->Deduccione->id = $id;
        if (!$this->Deduccione->exists()) {
                throw new NotFoundException(__('Deduccion Invalida'));
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->Deduccione->delete()) {			
            $data['respuesta']='La Deduccion del impuesto y ha sido eliminada. ';			
        } else {
            $data['respuesta']='La Deduccione del impuesto NO ha sido eliminada. Por favor intente de nuevo';
        }
        $this->set('data',$data);
        $this->layout = 'ajax';
        $this->render('serializejson');
    }}
