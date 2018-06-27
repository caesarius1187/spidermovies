<?php
App::uses('AppController', 'Controller');
/**
 * Estudios Controller
 *
 * @property Estudio $Estudio
 * @property PaginatorComponent $Paginator
 */
class EstudiosController extends AppController {

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
		$this->Estudio->recursive = 0;
		$this->set('estudios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null,$periodo = null) {
        $this->loadModel('Venta');
        $this->loadModel('Compra');
        $this->loadModel('Valorrecibo');
        $this->loadModel('Empleado');
        $this->loadModel('Cctxconcepto');
        $this->loadModel('Valorrecibo');
        $this->loadModel('Impcli');
        $this->loadModel('Eventosimpuesto');
        if (!$this->Estudio->exists($id)) {
                throw new NotFoundException(__('Invalid estudio'));
        }
        if($periodo!=null){
            $pemes = substr($periodo, 0, 2);
            $peanio = substr($periodo, 3);
        }else{
            $pemes = date('m', strtotime('-1 months'));   
            $peanio = date('Y', strtotime('-1 months'));   
        }

        $this->set('periodomes', $pemes);
        $this->set('periodoanio', $peanio);
        //A: Es menor que periodo Hasta
        $esMenorQueHasta = array(
            //HASTA es mayor que el periodo
            'OR' => array(
                'SUBSTRING(Periodosactivo.hasta,4,7)*1 > ' . $peanio . '*1',
                'AND' => array(
                    'SUBSTRING(Periodosactivo.hasta,4,7)*1 >= ' . $peanio . '*1',
                    'SUBSTRING(Periodosactivo.hasta,1,2) >= ' . $pemes . '*1'
                ),
            )
        );
        //B: Es mayor que periodo Desde
        $esMayorQueDesde = array(
            'OR' => array(
                'SUBSTRING(Periodosactivo.desde,4,7)*1 < ' . $peanio . '*1',
                'AND' => array(
                    'SUBSTRING(Periodosactivo.desde,4,7)*1 <= ' . $peanio . '*1',
                    'SUBSTRING(Periodosactivo.desde,1,2) <= ' . $pemes . '*1'
                ),
            )
        );
        $periodoNull = array(
            'OR' => array(
                array('Periodosactivo.hasta' => null),
                array('Periodosactivo.hasta' => ""),
            )
        );
        //C: Tiene Periodo Hasta 0 NULL
        $conditionsImpCliHabilitados = array(
            //El periodo esta dentro de un desde hasta
            'AND' => array(
                $esMayorQueDesde,
                'OR' => array(
                    $esMenorQueHasta,
                    $periodoNull
                )
            )
        );

        $options = array(
            'contain'=>[
                'Grupocliente'=>[
                    'Cliente'=>[
                        'Empleado'=>[
                            'conditions'=>[
                                'OR'=>[
                                        'Empleado.fechaegreso >= ' => date('Y-m-d',strtotime("01-".$pemes.'-'.$peanio)),
                                        'Empleado.fechaegreso is null' ,
                                ],
                                'Empleado.fechaingreso <= '=>date('Y-m-d',strtotime("28-".$pemes.'-'.$peanio)),
                            ]
                        ],
                        'Impcli'=>[
                            'Periodosactivo'=>[
                                'conditions'=>$conditionsImpCliHabilitados
                            ]
                        ],
                        'conditions'=>[
                            'Cliente.estado'=>'habilitado'
                        ]
                    ],
                    'conditions'=>[
                        'Grupocliente.estado'=>'habilitado'
                    ]
                ],
            ],
            'conditions' => array(
                'Estudio.' . $this->Estudio->primaryKey => $id
                )
            );
        $miEstudio = $this->Estudio->find('first', $options);
        $this->set('estudio', $miEstudio);
        $this->request->data = $miEstudio;

        $idClientesDelEstudio=[];
        foreach ($miEstudio['Grupocliente'] as $grupocliente) {
            foreach ($grupocliente['Cliente'] as $cliente) {
               $idClientesDelEstudio[] = $cliente['id'];
            }
        }

        $optionsVentas = array(
            'contain'=>[],
            'fields'=>[
                'Venta.cliente_id , count(*) as total'
            ],
            'group'=>[
                'Venta.cliente_id'
            ],
            'conditions' => array(
                'Venta.cliente_id' => $idClientesDelEstudio,
                'Venta.periodo' =>  $pemes.'-'.$peanio
                )
            );
        $misVentas = $this->Venta->find('all', $optionsVentas);
        $optionsVentasDiarias = array(
            'contain'=>[],
            'fields'=>[
                'Venta.created,count(*) as diario'
            ],
            'group'=>[
                'DAY(Venta.created)'
            ],
            'conditions' => array(
                'Venta.cliente_id' => $idClientesDelEstudio,
                'Venta.periodo' =>  $pemes.'-'.$peanio
                )
            );
        $misVentasDiarias = $this->Venta->find('all', $optionsVentasDiarias);
        $optionsCompras = array(
            'fields'=>[
                'Compra.cliente_id , count(*) as total'
            ],                    
            'group'=>[
                'Compra.cliente_id'
            ],
            'conditions' => array(
                'Compra.cliente_id' => $idClientesDelEstudio,
                'Compra.periodo' =>  $pemes.'-'.$peanio
                )
            );
        $misCompras = $this->Compra->find('all', $optionsCompras);
        $optionsComprasDiarias = array(
            'contain'=>[],
            'fields'=>[
                'Compra.created,count(*) as diario'
            ],
            'group'=>[
                'DAY(Compra.created)'
            ],
            'conditions' => array(
                'Compra.cliente_id' => $idClientesDelEstudio,
                'Compra.periodo' =>  $pemes.'-'.$peanio
                )
            );
        $misComprasDiarias = $this->Compra->find('all', $optionsComprasDiarias);
        //impuestos
         $optionsImpclis = array(
            'contain'=>[],
            'conditions' => array(
                'Impcli.cliente_id' => $idClientesDelEstudio,
                )
            );
        $misImpclis = $this->Impcli->find('all', $optionsImpclis);
        $idImpclisdelEstudio=[];
        foreach ($misImpclis as $impcli) {
           $idImpclisdelEstudio[] = $impcli['Impcli']['id'];
        }
        
        $optionsImpuestosDiarias = array(
            'contain'=>[],
            'fields'=>[
                'Eventosimpuesto.created,count(*) as diario'
            ],
            'group'=>[
                'DAY(Eventosimpuesto.created)'
            ],
            'conditions' => array(
                'Eventosimpuesto.impcli_id' => $idImpclisdelEstudio,
                'Eventosimpuesto.periodo' =>  $pemes.'-'.$peanio
                )
            );
        $misImpuestosDiarias = $this->Eventosimpuesto->find('all', $optionsImpuestosDiarias);
        //Vamos a totalizar por dia los sueldos liquidados
        $optionsEmpleados = array(
            'contain'=>[],
            'conditions' => array(
                'Empleado.cliente_id' => $idClientesDelEstudio,
                )
            );
        $misEmpleados = $this->Empleado->find('all', $optionsEmpleados);
        $idEmpleadosDelEstudio=[];
        foreach ($misEmpleados as $empleado) {
           $idEmpleadosDelEstudio[] = $empleado['Empleado']['id'];
        }
        $optionsCctxconceptos = array(
            'contain'=>[],
            'conditions' => array(
                'Cctxconcepto.concepto_id' => 46,
                )
            );
        $misCctxconceptos = $this->Cctxconcepto->find('all', $optionsCctxconceptos);
        $idCctxconceptoNetos=[];
        foreach ($misCctxconceptos as $cctxc) {
           $idCctxconceptoNetos[] = $cctxc['Cctxconcepto']['id'];
        }
        //ahora los valores recivo (neto) del periodo de los empleados
        $optionsValorecciboDiarias = array(
            'contain'=>[],
            'fields'=>[
                'Valorrecibo.created,count(*) as diario'
            ],
            'group'=>[
                'DAY(Valorrecibo.created)'
            ],
            'conditions' => array(
                'Valorrecibo.empleado_id' => $idEmpleadosDelEstudio,
                'Valorrecibo.periodo' =>  $pemes.'-'.$peanio,
                'Valorrecibo.cctxconcepto_id' =>  $idCctxconceptoNetos
                )
            );
        $misSueldosDiarias = $this->Valorrecibo->find('all', $optionsValorecciboDiarias);
        
        $this->set('misImpuestosDiarias', $misImpuestosDiarias);
        $this->set('misSueldosDiarias', $misSueldosDiarias);
        $this->set('ventas', $misVentas);
        $this->set('misVentasDiarias', $misVentasDiarias);
        $this->set('compras', $misCompras);
        $this->set('misComprasDiarias', $misComprasDiarias);
    }

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Estudio->create();
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('The estudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estudio could not be saved. Please, try again.'));
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
		if (!$this->Estudio->exists($id)) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		if ($this->request->is('post')) {
			$inicioactividades =$this->request->data['Estudio']['inicioactividades'];
			$this->request->data('Estudio.inicioactividades',date('Y-m-d',strtotime($inicioactividades)));
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos del estudio se han guardado.'));
				return $this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('No se pudieron guardar los datos del estudio por favor intente de nuevo mas tarde'));
			}
		} else {
			$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
			$this->request->data = $this->Estudio->find('first', $options);
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
		$this->Estudio->id = $id;
		if (!$this->Estudio->exists()) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estudio->delete()) {
			$this->Session->setFlash(__('The estudio has been deleted.'));
		} else {
			$this->Session->setFlash(__('The estudio could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function superadminestudioadd() 
	{		
		if ($this->request->is('post')) 
		{			
			$this->Estudio->create();

			$EtudioNombre = $this->request->data['Estudio']['nombre'];
			$PrimerUsuario = $this->request->data['Estudio']['usuario'];
			$PassPrimerUsuario = $this->request->data['Estudio']['password'];
			$EstudioEmail = $this->request->data['Estudio']['email'];
			$dniUsuario = $this->request->data['Estudio']['dni'];
			$telefonoUsuario = $this->request->data['Estudio']['telefono'];
			$celUsuario = $this->request->data['Estudio']['cel'];
			$matriculaUsuario = $this->request->data['Estudio']['matricula'];
			$folioUsuario = $this->request->data['Estudio']['folio'];

			if ($this->Estudio->save($this->request->data)) 
			{
				$EstudioId = $this->Estudio->getLastInsertID();
				$this->loadModel('User');
				$this->User->create();
				$this->User->set('estudio_id', $EstudioId);
				$this->User->set('mail', $EstudioEmail);
				$this->User->set('dni', $dniUsuario);
				$this->User->set('telefono', $telefonoUsuario);
				$this->User->set('cel', $celUsuario);
				$this->User->set('matricula', $matriculaUsuario);
				$this->User->set('folio', $folioUsuario);
				$this->User->set('nombre', $EtudioNombre);
				$this->User->set('username',$PrimerUsuario);
				//$RandomPass = 'Conta2017_'.rand(1,4);
				$this->User->set('password',$PassPrimerUsuario); 
				$this->User->set('tipo','administrador'); 
				$this->User->set('estado','habilitado'); 
                                if($this->User->save()){
                                    $UserInertedId = $this->User->getLastInsertID();
                                    //cargar la tareas.
                                    $this->loadModel('Tareascliente');
                                    $tareasclientesOpciones = array(
                                        'conditions' => array('Tareascliente.estado' => 'habilitado'), 
                                        'fields' => array('Tareascliente.id','Tareascliente.orden','Tareascliente.descripcion', 'Tareascliente.tipo')
                                        //'group' => 'Deposito.id'
                                        );
                                    $tareascliente = $this->Tareascliente->find('all',$tareasclientesOpciones);
                                    $this->loadModel('Tareasxclientesxestudio');

                                    foreach ($tareascliente as $tareacliente) {
                                            $this->Tareasxclientesxestudio->create();
                                            $this->Tareasxclientesxestudio->set('orden',$tareacliente['Tareascliente']['orden']); 
                                            $this->Tareasxclientesxestudio->set('descripcion',$tareacliente['Tareascliente']['descripcion']); 
                                            $this->Tareasxclientesxestudio->set('tareascliente_id',$tareacliente['Tareascliente']['id']); 
                                            $this->Tareasxclientesxestudio->set('estado','habilitado'); 
                                            $this->Tareasxclientesxestudio->set('tipo',$tareacliente['Tareascliente']['tipo']); 
                                            $this->Tareasxclientesxestudio->set('estudio_id',$EstudioId);
                                            $this->Tareasxclientesxestudio->set('user_id', $UserInertedId);
                                            if($this->Tareasxclientesxestudio->save()){
                                                
                                            }else{
                                                $this->Session->setFlash(__('No se pudo registrar la tarea, intente más tarde.'));
                                            }
                                    }
                                    $this->Session->setFlash(__('El Etudio se ha registrado con exito.'));
                                    return $this->redirect(array('controller' => 'superadmins',
                                                                                             'action' => 'index'));
                                }else{
                                    $this->Session->setFlash(__('No se pudo registrar el Usuario, intente más tarde.'));
                                }
				
			} 
			else 
			{
				$this->Session->setFlash(__('No se pudo registrar el Estudio, intente más tarde.'));
			}
		}
		//$estudios = $this->User->Estudio->find('list');
		//$this->set(compact('estudios'));
	}
}
