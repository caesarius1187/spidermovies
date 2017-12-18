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
	public function add($impcliid,$periodo=null) {
            if ($this->request->is('post')) {
                if ($this->Quebranto->saveAll($this->request->data['Quebranto'])) {
                    $data['respuesta'] = 'El Quebranto ha sido Guardado.';
                    $data['error'] = 0;
                } else {
                    $data['respuesta'] = 'ERROR: El Quebranto no ha sido Guardado. Por favor intente de nuevo mas tarde';
                    $data['error'] = 1;
                }
                $this->set('data', $data);
            }
            $optionQuebrantos=[
                'contain'=>[],
                'conditions'=>[
                    'Quebranto.impcli_id'=>$impcliid
                ]
            ];
            if($periodo!=null){
                $peanio = substr($periodo, 3);
                $optionQuebrantos['conditions'][]=['SUBSTRING(Quebranto.periodo,4,7)'=>$peanio];
            }
            $this->set('quebrantos', $this->Quebranto->find('all',$optionQuebrantos));
            $this->set('impcliid', $impcliid);

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
	public function edit($impcliid,$periodo) {
            if ($this->request->is('post')) {
                if ($this->Quebranto->saveAll($this->request->data['Quebranto'])) {
                    $data['respuesta'] = 'El Quebranto ha sido Guardado.';
                    $data['error'] = 0;
                } else {
                    $data['respuesta'] = 'ERROR: El Quebranto no ha sido Guardado. Por favor intente de nuevo mas tarde';
                    $data['error'] = 1;
                }
                $this->set('data', $data);
            }
            $peanio = substr($periodo, 3);
            $optionQuebrantos=[
                'contain'=>[],
                'conditions'=>[
                    'Quebranto.impcli_id'=>$impcliid,
                    'SUBSTRING(Quebranto.periodo,4,7)'=>$peanio
                ]
            ];
            $this->set('quebrantos', $this->Quebranto->find('all',$optionQuebrantos));
            $this->set('impcliid', $impcliid);
            $this->set('periodo', $periodo);

            $this->layout = 'ajax';
            $this->render('edit');	
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
                throw new NotFoundException(__('Quebranto Invalida'));
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->Quebranto->delete()) {			
            $data['respuesta']='La Quebranto del impuesto ha sido eliminada. ';			
        } else {
            $data['respuesta']='La Quebranto del impuesto NO ha sido eliminado. Por favor intente de nuevo';
        }
        $this->set('data',$data);
        $this->layout = 'ajax';
        $this->render('serializejson');
    }}
