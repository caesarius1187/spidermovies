<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Peliculas Controller
 *
 * @property Pelicula $Pelicula
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PeliculasController extends AppController {
	public $api_key = "d0e381b2a4e25c26a59e45acfea7d7d4";
/**
 * Components
 *
 * @var array
 */
 	
	public $components = array('Paginator', 'Session');
	/*public $paginate = array(
        'limit' => 25,
        
        
    );*/
	public $paginate = array('Pelicula' => array(
		'limit' => 25, 
		'contain' => ['Genero','Formato'],
		/*'joins' => array( 
		    array( 
		        'table' => 'generos_peliculas', 
		        'alias' => 'GeneroPelicula', 
		        'type' => 'inner',  
		        'conditions'=> array('GeneroPelicula.pelicula_id = Pelicula.id') 
		    ), 
		    array( 
		        'table' => 'generos', 
		        'alias' => 'Genero', 
		        'type' => 'inner',  
		        'conditions'=> array( 
		            'Genero.id = GeneroPelicula.genero_id', 		            
		        ) 
		    )
		),*/
		'order' => array(
            'created' => 'desc'
        ) 
	) );
	function beforeFilter() {
   		parent::beforeFilter();
	    $this->Auth->allow('lista','view','index');
	}
/**
 * index method
 *
 * @return void
 */
	public function lista() {
		
		$optionsPagination = [];
		$titulo = 'Novedades';
		if ($this->request->is('post')) {
			if(isset($this->request->data['Pelicula']['filter'])){
				$filtro = $this->request->data['Pelicula']['filter'];
				$optionsPagination = [
					'OR'=>[
						'Pelicula.titulo like' => '%'.$filtro.'%',				
						'Pelicula.overview like' => '%'.$filtro.'%',				
					],							
				];

				$this->set('filter', $filtro);
			}
			if(isset($this->request->data['Pelicula']['genero'])){
				$genero = $this->request->data['Pelicula']['genero'];
				$this->paginate = array('Pelicula' => array(
					'limit' => 25, 
					'contain' => ['Genero','Formato'],
					'joins' => array( 
					    array( 
					        'table' => 'generos_peliculas', 
					        'alias' => 'GeneroPelicula', 
					        'type' => 'inner',  
					        'conditions'=> array('GeneroPelicula.pelicula_id = Pelicula.id') 
					    ), 
					    array( 
					        'table' => 'generos', 
					        'alias' => 'Genero', 
					        'type' => 'inner',  
					        'conditions'=> array( 
					            'Genero.id = GeneroPelicula.genero_id', 	
					            'Genero.id'=>$genero,
					        ) 
					    )
					),
					'order' => array(
			            'created' => 'desc'
			        ) 
				) );   
				$this->loadModel('Genero');
				$migenero = $this->Genero->find('first',[
					'conditions'=>[
						'Genero.id'=>$genero
					],
					'contain'=>[]
				]);
				$this->set('migenero', $migenero);
			}
		}else{
			$optionsPagination = [
				
			];
		}
		$this->Pelicula->recursive = 0;
		$this->Paginator->settings = $this->paginate;
		$paginations = $this->Paginator->paginate($optionsPagination);

 		// $paginations =  $this->Paginator->paginate(optionsPagination,$conditionsPaginations)
		$this->set('peliculas',$paginations);
		$folderPeliculas = WWW_ROOT.'img'.DS.'peliculas'.DS;
		$this->set('folderPeliculas',$folderPeliculas);
	}
	public function index() {
		return $this->redirect(array('action' => 'lista'));
		$this->Pelicula->recursive = 0;
		$this->set('peliculas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Pelicula->exists($id)) {
			throw new NotFoundException(__('Invalid pelicula'));
		}
		$folderPeliculas = WWW_ROOT.'img'.DS.'peliculas'.DS.$id;
		$options = array('conditions' => array('Pelicula.' . $this->Pelicula->primaryKey => $id));
		$this->set('pelicula', $this->Pelicula->find('first', $options));
		$this->set('folderPeliculas', $folderPeliculas);
	}

/**
 * add method
 *
 * @return void
 */
	public function add($idtmdb=null) {
		if ($this->request->is('post')) {
			$this->Pelicula->create();
			if ($this->Pelicula->save($this->request->data)) {
				$this->Session->setFlash(__('La pelicula ha sido guardad con exito.'));
				//ahora vamos a guardar el archivo
				$pid = $this->Pelicula->getLastInsertId();
				$folderPeliculas = WWW_ROOT.'img'.DS.'peliculas'.DS.$pid;
				$dirPeliculas = new Folder($folderPeliculas, true, 0777);
				$fileNamePelicula = null;
				$tmpNamePelicula= $this->request->data['Pelicula']['imagenpersonalizada']['tmp_name'];
				Debugger::dump($this->request->data);
				if (!empty($tmpNamePelicula)&& is_uploaded_file($tmpNamePelicula)) {
				    // Strip path information
				    $fileNamePelicula = $this->request->data['Pelicula']['imagenpersonalizada']['name'];
				    
                    move_uploaded_file($tmpNamePelicula, $folderPeliculas.DS.$fileNamePelicula);
					//chmod($folderPeliculas.DS.$fileNamePelicula, 0777);
				}
				return $this->redirect(array('action' => 'lista'));
			} else {
				$this->Session->setFlash(__('Error no se pudo importar'));
			}
		}
		if($idtmdb!=null){
			$curl = curl_init();
			$busquedaconID = "https://api.themoviedb.org/3/movie/".$idtmdb."?api_key=".$this->api_key."&language=es";
		}
		$formatos = $this->Pelicula->Formato->find('list');
		$generos = $this->Pelicula->Genero->find('list');
		$this->set(compact('formatos','generos','idtmdb','busquedaconID'));
	}
	public function tmdbbusqueda() {
		if ($this->request->is('post')) {
			$filtro = $this->request->data['Pelicula']['filter'];
		}
		$query = str_replace(" ", "+", $filtro);
		$formatos = $this->Pelicula->Formato->find('list');
		$api_key = $this->api_key;
		$this->set(compact('formatos','query','api_key'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Pelicula->exists($id)) {
			throw new NotFoundException(__('Invalid pelicula'));
		}
		$folderPeliculas = WWW_ROOT.'img'.DS.'peliculas'.DS.$id;

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Pelicula->save($this->request->data)) {
				$this->Session->setFlash(__('Se ha guardado la pelicula.'));

				$dirPeliculas = new Folder($folderPeliculas, true, 0777);
				$fileNamePelicula = null;
				$tmpNamePelicula= $this->request->data['Pelicula']['imagenpersonalizada']['tmp_name'];
				if (!empty($tmpNamePelicula)&& is_uploaded_file($tmpNamePelicula)) {
				    // Strip path information
				    $fileNamePelicula = $this->request->data['Pelicula']['imagenpersonalizada']['name'];
				    
	                            move_uploaded_file($tmpNamePelicula, $folderPeliculas.DS.$fileNamePelicula);
					//chmod($folderPeliculas.DS.$fileNamePelicula, 0777);
				}

				return $this->redirect(array('action' => 'lista'));
			} else {
				$this->Session->setFlash(__('No se pudo guardar la pelicula, por favor intente de nuevo mas tarde'));
			}
		} else {
			$options = array('conditions' => array('Pelicula.' . $this->Pelicula->primaryKey => $id));
			$this->request->data = $this->Pelicula->find('first', $options);
		}
		$formatos = $this->Pelicula->Formato->find('list');
		$generos = $this->Pelicula->Genero->find('list');
		$pid=$id;
		$this->set(compact('formatos','generos','folderPeliculas','pid'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Pelicula->id = $id;
		if (!$this->Pelicula->exists()) {
			throw new NotFoundException(__('Invalid pelicula'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Pelicula->delete()) {
			$this->Session->setFlash(__('La pelicula ha sido eliminada'));
		} else {
			$this->Session->setFlash(__('No se pudo borrar la pelicula'));
		}
		return $this->redirect(array('action' => 'lista'));
	}
}
