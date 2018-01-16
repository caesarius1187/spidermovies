<?php
App::uses('AppController', 'Controller');
/**
 * Ajustescontables Controller
 *
 * @property Ajustescontable $Ajustescontable
 * @property PaginatorComponent $Paginator
 */
class AjustescontablesController extends AppController {

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
            $optionAjustescontables=[];
            $this->set('ajustescontables', $this->Ajustescontable->find('all',$optionAjustescontables));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ajustescontable->exists($id)) {
			throw new NotFoundException(__('Invalid ajustescontable'));
		}
		$options = array('conditions' => array('Ajustescontable.' . $this->Ajustescontable->primaryKey => $id));
		$this->set('ajustescontable', $this->Ajustescontable->find('first', $options));
                $this->layout = 'ajax';
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
            if ($this->request->is('post')) {
                if ($this->Ajustescontable->saveAll($this->request->data['Ajustescontable'])) {
                    $data['respuesta'] = 'El Ajustescontable ha sido Guardado.';
                    $data['error'] = 0;
                } else {
                    $data['respuesta'] = 'ERROR: El Ajustescontable no ha sido Guardado. Por favor intente de nuevo mas tarde';
                    $data['error'] = 1;
                }
                $this->set('data', $data);
            }
            $this->set('data',$data);
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
	public function edit() {
            if ($this->request->is('post')) {
                if ($this->Ajustescontable->saveAll($this->request->data['Ajustescontable'])) {
                    $data['respuesta'] = 'El Ajustescontable ha sido Guardado.';
                    $data['error'] = 0;
                } else {
                    $data['respuesta'] = 'ERROR: El Ajustescontable no ha sido Guardado. Por favor intente de nuevo mas tarde';
                    $data['error'] = 1;
                }
                $this->set('data', $data);
                $this->layout = 'ajax';
                $this->render('serializejson');	
                return;
            }
            /*$peanio = substr($periodo, 3);
            $optionAjustescontables=[
                'contain'=>[],
                'conditions'=>[
                    'Ajustescontable.impcli_id'=>$impcliid,
                    'Ajustescontable.periodo'=>$peanio
                ]
            ];
            $this->set('ajustescontables', $this->Ajustescontable->find('all',$optionAjustescontables));
            $this->set('impcliid', $impcliid);
            $this->set('periodo', $periodo);*/

            $this->layout = 'ajax';
            $this->render('edit');	
	}
	public function editajax(
				$id=null,$cliid = null) {
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
	 	//$this->request->onlyAllow('ajax');

		if (!$this->Ajustescontable->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Ajustescontable.' . $this->Ajustescontable->primaryKey => $id));
		$this->request->data = $this->Ajustescontable->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Ajustescontable->Cliente->find('list', $optionsCli);
		

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
        $this->Ajustescontable->id = $id;
        if (!$this->Ajustescontable->exists()) {
                throw new NotFoundException(__('Ajustescontable Invalida'));
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->Ajustescontable->delete()) {			
            $data['respuesta']='La Ajustescontable del impuesto ha sido eliminada. ';			
        } else {
            $data['respuesta']='La Ajustescontable del impuesto NO ha sido eliminado. Por favor intente de nuevo';
        }
        $this->set('data',$data);
        $this->layout = 'ajax';
        $this->render('serializejson');
    }}
