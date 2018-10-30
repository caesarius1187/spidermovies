<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersControllerBK extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $components = array('Paginator','Auth');

/**
 * index method
 *
 * @return void
 */
	
    public function beforeFilter() {
    	parent::beforeFilter();
    	$this->Auth->allow('logout');
    }

    public function login() {
        $this->loadModel('Notification');
    	$this->layout="default_login";
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                //vamos a cargar las notificaciones del cliente
                $today = date('Y-m-d');
                $peanio = date('Y');
                $pemes = date('m');
                
                //primero vamos a preguntar si este estudio ya genero sus notificaciones
                $optionsUser=[
                    'contain'=>[],
                    'conditions'=>[
                        'User.id'=>$this->Session->read('Auth.User.id')
                    ],
                ];
                $miUser = $this->User->find('first', $optionsUser);
                //Antes de generar las notificaciones vamos a controlar que los terminos y condiciones
                //se hayan leido y aceptado
                Debugger::dump($this->Session->read('Auth.User.id'));
                if($miUser['User']['terminosycondiciones']==0){
                    //entonces no se leyo TyC
                    return $this->redirect(
                            array('controller' => 'users', 'action' => 'terminosycondiciones',$miUser['User']['id'])
                            );
                }
                $ultimaNotificacionGenerada = date('Y-m-d', strtotime($miUser['User']['notificaciongenerada']));
                if($ultimaNotificacionGenerada<$today){
                    /*$containCliAuth = array(
                        'Grupocliente',
                    );
                    $clientesAuth = $this->Cliente->find('all',array(
                            'contain' =>$containCliAuth,
                            'conditions' => array(
                                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),                            
                                'Grupocliente.estado' => 'habilitado' ,              
                                'Cliente.estado' => 'habilitado' ,
                            )
                        )
                    );
                    foreach ($clientesAuth as $kcli => $cliente) {
                        $this->Notification->checkNotificationsFor($cliente);
                    }
                    $this->Estudio->id = $this->Session->read('Auth.User.estudio_id');
                    $this->Estudio->saveField('notificaciongenerada' , $today);*/
                }else{
                    /*Debugger::dump("no genere");
                    Debugger::dump($miEstudio);
                    Debugger::dump($ultimaNotificacionGenerada);
                    Debugger::dump($today);
                    die();*/
                }
                //fin creacion de notificaciones
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__('Nombre de usuario o contrase&ntilde;a inv&aacute;lida.'));
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        
        $users = $this->User->find('all',array());
        $this->set('users', $users);
    }
    public function terminosycondiciones($userid=null) {
    	$this->layout="default_login";
        if ($this->request->is('post')) {
            $userid = $this->request->data['User']['id'];
            $this->User->id = $userid;
            $this->User->saveField('terminosycondiciones' , 1);
            $this->Session->setFlash(__('Se han aceptado los Terminos y Condiciones de Spidermovies'.$userid));
            return $this->redirect($this->Auth->redirectUrl());
        }
        $this->set('userid', $userid);
    }
     public function preguntas(){
        $this->layout="default_home";
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        if (!$this->User->exists($id)) {
                throw new NotFoundException(__('Invalid user'));
        }
        $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
        $this->set('user', $this->User->find('first', $options));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
            if ($this->request->is('post')) {
                    $this->User->create();
                    if ($this->User->save($this->request->data)) {
                            $this->Session->setFlash(__('Su usuario ha sido registrado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('No se pudo registrar, intente m&aacute;s tarde.'));
                    }
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
            if (!$this->User->exists($id)) {
                    throw new NotFoundException(__('Invalid user'));
            }
            if ($this->request->is('post')) {
                    if ($this->User->save($this->request->data)) {
                            $this->Session->setFlash(__('El usuario se ha guardado con exito.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
                    }
            } else {
                    $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
                    $this->request->data = $this->User->find('first', $options);
            }
    }
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                    throw new NotFoundException(__('Invalid user'));
            }
            $this->request->onlyAllow('post', 'delete');
            if ($this->User->delete()) {
                    $this->Session->setFlash(__('The user has been deleted.'));
            } else {
                    $this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
            }
            return $this->redirect(array('action' => 'index'));
    }
    public function editajax($UsuarioId=null) 
    {
            if (!$this->User->exists($UsuarioId)) {
                    throw new NotFoundException(__('Usuario invalido'));
            }

            $options = array('conditions' => array('User.' . $this->User->primaryKey => $UsuarioId));
            $this->request->data = $this->User->find('first', $options);

            //$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
            //$clientes = $this->Domicilio->Cliente->find('list', $optionsCli);

            //$localidades = $this->Domicilio->Localidade->find('list');
            //$this->set(compact('clientes', 'localidades'));

            //$data = array('conditions' => array('User.' . $this->User->primaryKey => $UsuarioId));
            //$this->set('data', $this->User->find('first', $data));

            //$data = array(
        //        "respuesta" => "El Grupo ha sido guardado con exito.",
        //        "Grupocliente_id" => $this->Grupocliente->getLastInsertID()
        //    );
        //    $this->set('data', $data);

            $this->layout = 'ajax';
            //$this->render('serializejson');	
            $this->render('edit');	
    }
}