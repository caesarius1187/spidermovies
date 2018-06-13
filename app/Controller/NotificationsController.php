<?php
App::uses('AppController', 'Controller');
/**
 * Notifications Controller
 *
 * @property Notification $Notification
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class NotificationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Notification->recursive = 0;
                $this->Paginator->settings = array(
                    'Notification' => array(
                        'contain' => array(
                                    'Estudio',
                                    'Cliente',
                                ),
                                'order' => [
                                    'Notification.id'=>'desc'
                                    ],
                                'conditions' => [
                                    'Notification.estudio_id'=>$this->Session->read('Auth.User.estudio_id'),		
                                ],     
                     )
                                   
                );
		$this->set('notifications', $this->Paginator->paginate());
	}
        public function setNotificationReaded($notid,$readed){
            $this->Notification->read(null, $notid);
            $this->Notification->set('readed',$readed);        
            $data=[];
            
            if($this->Notification->save()){
                if($readed){
                    $data['respuesta'] = 'Notificacion marcada como leida';
                }else{
                    $data['respuesta'] = 'Notificacion marcada como NO leida';
                }
                $data['error'] = 0;
            }else{
                $data['respuesta'] = 'Error al modificar la Notificacion';
                $data['error'] = 1;
            }
            $this->autoRender=false; 				
            $this->layout = 'ajax';
            $this->set('data',$data);
            $this->render('serializejson');
        }
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Notification->exists($id)) {
			throw new NotFoundException(__('Invalid notification'));
		}
		$options = array('conditions' => array('Notification.' . $this->Notification->primaryKey => $id));
		$this->set('notification', $this->Notification->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Notification->create();
			if ($this->Notification->save($this->request->data)) {
				$this->Session->setFlash(__('The notification has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The notification could not be saved. Please, try again.'));
			}
		}
		$estudios = $this->Notification->Estudio->find('list');
		$this->set(compact('estudios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Notification->exists($id)) {
			throw new NotFoundException(__('Invalid notification'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Notification->save($this->request->data)) {
				$this->Session->setFlash(__('The notification has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The notification could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Notification.' . $this->Notification->primaryKey => $id));
			$this->request->data = $this->Notification->find('first', $options);
		}
		$estudios = $this->Notification->Estudio->find('list');
		$this->set(compact('estudios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Notification->id = $id;
		if (!$this->Notification->exists()) {
			throw new NotFoundException(__('Invalid notification'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Notification->delete()) {
			$this->Session->setFlash(__('The notification has been deleted.'));
		} else {
			$this->Session->setFlash(__('The notification could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
