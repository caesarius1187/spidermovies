<?php
App::uses('AppController', 'Controller');
/**
 * Impcliprovincias Controller
 *
 * @property Impcliprovincia $Impcliprovincia
 * @property PaginatorComponent $Paginator
 */
class ImpcliprovinciasController extends AppController {

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
            $this->Impcliprovincia->recursive = 0;
            $this->set('impcliprovincias', $this->Paginator->paginate());
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
            if (!$this->Impcliprovincia->exists($id)) {
                    throw new NotFoundException(__('Invalid impcliprovincia'));
            }
            $options = array('conditions' => array('Impcliprovincia.' . $this->Impcliprovincia->primaryKey => $id));
            $this->set('impcliprovincia', $this->Impcliprovincia->find('first', $options));
    }

/**
 * add method
 *
 * @return void
 */
    public function add($impcliid = null) {
        $this->loadModel('Impcli');
        $this->loadModel('Actividadcliente');
        $this->loadModel('Localidade');
        $options = array('conditions' => array(
            'Impcli.' . $this->Impcli->primaryKey => $impcliid)
        );
        $impcli = $this->Impcli->find('first', $options);
            if ($this->request->is('post')) {
                $data = array();
                $this->Impcliprovincia->create();
                if ($this->Impcliprovincia->saveAssociated($this->request->data)) {
                    if($impcli['Impcli']['impuesto_id']==6/*Actividades Varias*/){
                        $data['respuesta']='La localidad se ha dado de alta.';
                    }else{
                        $data['respuesta']='La provincia se ha dado de alta.';
                    }
                } else {
                    $data['respuesta']='Error al guardar por favor intente mas tarde.';
                }
                $this->set(compact('data'));
                $this->autoRender=false; 				
                $this->layout = 'ajax';
                $this->render('serializejson');			
            }else{
                $mostrarLista=false;

                $conditionAlicuota=array();
                if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilateral*/){
                    $mostrarLista=true;
                    $options = array(
                        'conditions' => array('Impcliprovincia.impcli_id' => $impcliid),
                        'contain' => array(
                            'Encuadrealicuota'=>array(
                                'Actividadcliente'=>array(
                                    'Actividade',
                                    'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
                                    )
                                ),
                            'Partido',
                            )
                        );
                    $this->set('impcliprovincias', $this->Impcliprovincia->find('all', $options));
                    $partidos = $this->Impcliprovincia->Partido->find('list');
                    $this->set(compact('partidos'));
                    $conditionAlicuota=array(" (`Alicuota`.`localidade_id` is null OR `Alicuota`.`localidade_id` = '')");
                }else if($impcli['Impcli']['impuesto_id']==6/*Actividades Varias*/){
                    $mostrarLista=true;
                    $options = array(
                        'conditions' => array('Impcliprovincia.impcli_id' => $impcliid),
                        'contain' => array(
                            'Encuadrealicuota'=>array(
                                'Actividadcliente'=>array(
                                    'Actividade',
                                    'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
                                    )
                                ),
                            'Localidade',
                            )
                        );
                    $this->set('impcliprovincias', $this->Impcliprovincia->find('all', $options));

                    //estas localidades deben ser solamente las que pertenecen a provincias ya cargadas en Convenio Multilateral para este cliente
                    //primero busco el Impcli de Convenio Si existe lo uso sino deberia dar error y mostrar un cartel nomas supongo que se io
                    //Cabe destacar que tambien se deberan buscar provincias que esten inscriptas en Actividades Economicas
                    $optionsConvenio = array(
                        'contain'=>array(
                            'Impcliprovincia'
                            ),
                        'conditions' => array(
                            'Impcli.cliente_id' => $impcli['Impcli']['cliente_id'],
                            'Impcli.impuesto_id' => 174,//Convenio Multilateral
                        )
                    );
                    $impcliConvenio = $this->Impcli->find('first', $optionsConvenio);
                    if(!isset($impcliConvenio['Impcliprovincia'])||count($impcliConvenio['Impcliprovincia'])==0){
                        //NO hay Convenio Multilateral, preguntemos si hay Actividades Economicas
                        $optionsConvenio = array(
                            'contain'=>array(
                                    'Impcliprovincia'
                                    ),
                            'conditions' => array(
                                    'Impcli.cliente_id' => $impcli['Impcli']['cliente_id'],
                                    'Impcli.impuesto_id' => 21,//Actividades Economicas
                            )
                        );
                        $impcliConvenio = $this->Impcli->find('first', $optionsConvenio);
                        if(!isset($impcliConvenio['Impcliprovincia'])||count($impcliConvenio['Impcliprovincia'])==0){
                                $this->set('error','Debe relacionar provincia(s) al impuesto Convenio Multilateral o Actividades Economicas en el Organizmo DGR.');
                        }
                        $provinciasActivadas = array();
                        foreach ($impcliConvenio['Impcliprovincia'] as $key => $impcliprovincia) {
                                if(!in_array($impcliprovincia['partido_id'], $provinciasActivadas, true)){
                                array_push($provinciasActivadas, $impcliprovincia['partido_id']);
                            }
                        }
                        $conditionsLocalidades = array(
                            'contain'=>array(
                                    'Partido'
                            ),
                            'conditions'=>array('Localidade.partido_id'=>$provinciasActivadas),
                            'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
                            'order'=>array('Partido.nombre','Localidade.nombre')
                        );
                        $localidades = $this->Localidade->find('list',$conditionsLocalidades);
                        $this->set(compact('localidades'));
                        $this->set(compact('provinciasActivadas'));
                    }else{
                        $provinciasActivadas = array();
                        foreach ($impcliConvenio['Impcliprovincia'] as $key => $impcliprovincia) {
                                if(!in_array($impcliprovincia['partido_id'], $provinciasActivadas, true)){
                                array_push($provinciasActivadas, $impcliprovincia['partido_id']);
                            }
                        }
                        $conditionsLocalidades = array(
                            'contain'=>array(
                                    'Partido'
                            ),
                            'conditions'=>array('Localidade.partido_id'=>$provinciasActivadas),
                            'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
                            'order'=>array('Partido.nombre','Localidade.nombre')
                        );
                        $localidades = $this->Localidade->find('list',$conditionsLocalidades);
                        $this->set(compact('localidades'));
                        $this->set(compact('provinciasActivadas'));
                    }
                    $conditionAlicuota=array(" (`Alicuota`.`partido_id` is null OR `Alicuota`.`partido_id` = '')");
                }else{
//				$options = array(
//					'conditions' => array('Impcliprovincia.impcli_id' => $impcliid),
//					'contain' => array(
//						'Encuadrealicuota'=>array(
//							'Actividadcliente'=>array(
//								'Actividade',
//								'conditions'=>[
//									'Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']
//								],
//								)
//							),
//						'Partido',
//						)
//					);
//				$this->request->data = $this->Impcliprovincia->find('first', $options);
                    $partidos = $this->Impcliprovincia->Partido->find('list');
                    $this->set(compact('partidos'));
                    $conditionAlicuota=array(" (`Alicuota`.`localidade_id` is null OR `Alicuota`.`localidade_id` = '')");

                    $mostrarLista=true;
                    $options = array(
                        'conditions' => array('Impcliprovincia.impcli_id' => $impcliid),
                        'contain' => array(
                            'Encuadrealicuota'=>array(
                                'Actividadcliente'=>array(
                                    'Actividade',
                                    'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
                                )
                            ),
                            'Partido',
                        )
                    );
                    $this->set('impcliprovincias', $this->Impcliprovincia->find('all', $options));
                    $partidos = $this->Impcliprovincia->Partido->find('list');
                }
                //Aca vamos a llevas las actividades de los clientes con las alicuotas que tienen relacionadas
                //podriamos filtrar esto para traer solo las de Localidad o Partido segun corresponda al impuesto seleccionado
                $optionsActividadclientes = array(
                        'conditions' => array(
                                'Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']
                                ),
                        'contain'=>array(
                                'Actividade'=>array(
                                        'Alicuota'=>array(
                                                'conditions'=>$conditionAlicuota,
                                                )
                                        ),
                                )
                );
                $actividadclientes = $this->Actividadcliente->find('all', $optionsActividadclientes);
                $actividadclientesimpclid = $impcli['Impcli']['cliente_id'];

                $this->set(compact('actividadclientes'));
                $this->set('impuestoid',$impcli['Impcli']['impuesto_id']);
                $this->set(compact('actividadclientesimpclid'));
                $this->set(compact('impcliid'));
                $this->set(compact('mostrarLista'));

                $this->autoRender=false; 				
                $this->layout = 'ajax';
                $this->render('add');
            }

    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null,$impcliid = null){
		$this->loadModel('Impcli');
		$this->loadModel('Actividadcliente');
		$this->loadModel('Localidade');
		$options = array('conditions' => array(
			'Impcli.' . $this->Impcli->primaryKey => $impcliid)
		);
		$impcli = $this->Impcli->find('first', $options);
		if ($this->request->is('post')) {
			$data = array();
			$this->Impcliprovincia->create();
			if ($this->Impcliprovincia->saveAssociated($this->request->data)) {
				if($impcli['Impcli']['impuesto_id']==6/*Actividades Varias*/){
					$data['respuesta']='La localidad se ha dado de alta.';
				}else{
					$data['respuesta']='La provincia se ha dado de alta.';
				}
			} else {
				$data['respuesta']='Error al guardar por favor intente mas tarde.';
			}
			$this->set(compact('data'));
			$this->autoRender=false;
			$this->layout = 'ajax';
			$this->render('serializejson');
		}else{
			$mostrarLista=false;

			$conditionAlicuota=array();
			if($impcli['Impcli']['impuesto_id']==174/*Convenio Multilateral*/){
				$mostrarLista=true;
				$options = array(
					'conditions' => array(
						'Impcliprovincia.impcli_id' => $impcliid,
						'Impcliprovincia.id' => $id
					),
					'contain' => array(
						'Encuadrealicuota'=>array(
							'Actividadcliente'=>array(
								'Actividade',
								'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
							)
						),
						'Partido',
					)
				);
				$impcliprovincia = $this->Impcliprovincia->find('first', $options);
				$this->set('impcliprovincia',$impcliprovincia );
				$this->request->data=$impcliprovincia;
				$partidos = $this->Impcliprovincia->Partido->find('list');
				$this->set(compact('partidos'));
				$conditionAlicuota=array(" (`Alicuota`.`localidade_id` is null OR `Alicuota`.`localidade_id` = '')");
			}else if($impcli['Impcli']['impuesto_id']==6/*Actividades Varias*/){
				$mostrarLista=true;
				$options = array(
					'conditions' => array(
						'Impcliprovincia.impcli_id' => $impcliid,
						'Impcliprovincia.id' => $id
					),
					'contain' => array(
						'Encuadrealicuota'=>array(
							'Actividadcliente'=>array(
								'Actividade',
								'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
							)
						),
						'Localidade',
					)
				);
				$impcliprovincia=$this->Impcliprovincia->find('first', $options);
				$this->set('impcliprovincia',$impcliprovincia );
				$this->request->data=$impcliprovincia;
				//estas localidades deben ser solamente las que pertenecen a provincias ya cargadas en Convenio Multilateral para este cliente
				//primero busco el Impcli de Convenio Si existe lo uso sino deberia dar error y mostrar un cartel nomas supongo que se io
				//Cabe destacar que tambien se deberan buscar provincias que esten inscriptas en Actividades Economicas
				$optionsConvenio = array(
					'contain'=>array(
						'Impcliprovincia'
					),
					'conditions' => array(
						'Impcli.cliente_id' => $impcli['Impcli']['cliente_id'],
						'Impcli.impuesto_id' => 174,//Convenio Multilateral
					)
				);
				$impcliConvenio = $this->Impcli->find('first', $optionsConvenio);
				if(!isset($impcliConvenio['Impcliprovincia'])||count($impcliConvenio['Impcliprovincia'])==0){
					//NO hay Convenio Multilateral, preguntemos si hay Actividades Varias
					$optionsConvenio = array(
						'contain'=>array(
							'Impcliprovincia'
						),
						'conditions' => array(
							'Impcli.cliente_id' => $impcli['Impcli']['cliente_id'],
							'Impcli.impuesto_id' => 21,//Actividades Economicas
						)
					);
					$impcliConvenio = $this->Impcli->find('first', $optionsConvenio);
					if(!isset($impcliConvenio['Impcliprovincia'])||count($impcliConvenio['Impcliprovincia'])==0){
						$this->set('error','Debe relacionar provincia(s) al impuesto Convenio Multilateral o Actividades Economicas en el Organizmo DGR.');
					}
					$provinciasActivadas = array();
					foreach ($impcliConvenio['Impcliprovincia'] as $key => $impcliprovincia) {
						if(!in_array($impcliprovincia['partido_id'], $provinciasActivadas, true)){
							array_push($provinciasActivadas, $impcliprovincia['partido_id']);
						}
					}
					$conditionsLocalidades = array(
						'contain'=>array(
							'Partido'
						),
						'conditions'=>array('Localidade.partido_id'=>$provinciasActivadas),
						'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
						'order'=>array('Partido.nombre','Localidade.nombre')
					);
					$localidades = $this->Localidade->find('list',$conditionsLocalidades);
					$this->set(compact('localidades'));
					$this->set(compact('provinciasActivadas'));
				}else{
					$provinciasActivadas = array();
					foreach ($impcliConvenio['Impcliprovincia'] as $key => $impcliprovincia) {
						if(!in_array($impcliprovincia['partido_id'], $provinciasActivadas, true)){
							array_push($provinciasActivadas, $impcliprovincia['partido_id']);
						}
					}
					$conditionsLocalidades = array(
						'contain'=>array(
							'Partido'
						),
						'conditions'=>array('Localidade.partido_id'=>$provinciasActivadas),
						'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
						'order'=>array('Partido.nombre','Localidade.nombre')
					);
					$localidades = $this->Localidade->find('list',$conditionsLocalidades);
					$this->set(compact('localidades'));
					$this->set(compact('provinciasActivadas'));
				}
				$conditionAlicuota=array(" (`Alicuota`.`partido_id` is null OR `Alicuota`.`partido_id` = '')");
			}else{
                $mostrarLista=true;
                $options = array(
                    'conditions' => array(
                        'Impcliprovincia.impcli_id' => $impcliid,
                        'Impcliprovincia.id' => $id
                    ),
                    'contain' => array(
                        'Encuadrealicuota'=>array(
                            'Actividadcliente'=>array(
                                'Actividade',
                                'conditions'=>array('Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']),
                            )
                        ),
                        'Partido',
                    )
                );
                $impcliprovincia = $this->Impcliprovincia->find('first', $options);
                $this->set('impcliprovincia',$impcliprovincia );
                $this->request->data=$impcliprovincia;
                $partidos = $this->Impcliprovincia->Partido->find('list');
                $this->set(compact('partidos'));
                $conditionAlicuota=array(" (`Alicuota`.`localidade_id` is null OR `Alicuota`.`localidade_id` = '')");
			}
//			//Aca vamos a llevas las actividades de los clientes con las alicuotas que tienen relacionadas
//			//podriamos filtrar esto para traer solo las de Localidad o Partido segun corresponda al impuesto seleccionado
			$optionsActividadclientes = array(
				'conditions' => array(
					'Actividadcliente.cliente_id' => $impcli['Impcli']['cliente_id']
				),
				'contain'=>array(
					'Actividade'=>array(
						'Alicuota'=>array(
							'conditions'=>$conditionAlicuota,
						)
					),
				)
			);
			$actividadclientes = $this->Actividadcliente->find('all', $optionsActividadclientes);
			$actividadclientesimpclid = $impcli['Impcli']['cliente_id'];

			$this->set(compact('actividadclientes'));
			$this->set('impuestoid',$impcli['Impcli']['impuesto_id']);
			$this->set(compact('actividadclientesimpclid'));
			$this->set(compact('impcliid'));
			$this->set(compact('mostrarLista'));

			$this->autoRender=false;
			$this->layout = 'ajax';
			$this->render('edit');
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
		$this->loadModel("Encuadrealicuota");
		$this->Impcliprovincia->id = $id;
		if (!$this->Impcliprovincia->exists()) {
			throw new NotFoundException(__('Invalid impcliprovincia'));
		}
		
		$this->request->onlyAllow('post', 'delete');
		if ($this->Impcliprovincia->delete()) {
			if($this->Encuadrealicuota->deleteAll(array('Encuadrealicuota.impcliprovincia_id' => $id ), false)){
				$data['respuesta']='La provincia del impuesto y su información ha sido eliminado. ';
			}else{
				$data['respuesta']='La provincia del impuesto ha sido eliminada pero no su informacion. ';
			}
		} else {
			$data['respuesta']='La provincia del impuesto NO ha sido eliminado. Por favor intente de nuevo';
		}

		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}}
