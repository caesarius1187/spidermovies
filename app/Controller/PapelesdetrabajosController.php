<?php
App::uses('AppController', 'Controller');
/**
 * Archivos Controller
 *
 * @property Archivo $Archivo
 * @property PaginatorComponent $Paginator
 */
class PapelesdetrabajosController extends AppController {

	var $uses = false;
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
	public function iva($ClienteId = null,$periodo=null) {
		$this->loadModel('Cliente');
		$this->loadModel('Venta');
		$this->loadModel('Actividadcliente');
		$this->loadModel('Compra');
		//$this->Archivo->recursive = 0;
		//$this->set('archivos', $this->Paginator->paginate());
		$añoPeriodo="SUBSTRING( '".$periodo."',4,7)";
		$mesPeriodo="SUBSTRING( '".$periodo."',1,2)";
		$esMenorQuePeriodo = array(
			'OR'=>array(
	    		'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 < '.$añoPeriodo.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 = '.$añoPeriodo.'*1',
	    			'SUBSTRING(Eventosimpuesto.periodo,1,2) < '.$mesPeriodo.'*1'
	    			),												            		
	    		)
		);
		$options = 
			array(
				'conditions' => array('Cliente.' . $this->Cliente->primaryKey => $ClienteId),
				'contain' => array(
						'Impcli'=>array(
							'Eventosimpuesto'=>array(
								'conditions'=>array(
									"SUBSTRING(Eventosimpuesto.periodo,4,7) = ".$añoPeriodo."*1",//que tengan el mismo año
									$esMenorQuePeriodo,
									),
								),
							'conditions'=>array(
								'Impcli.impuesto_id'=>19//IVA
								)
							),
						'Conceptosrestante'=>array(
							'conditions'=>array(
								'Conceptosrestante.periodo' => $periodo,
							),
						),	 
						'Actividadcliente' => array(
							'Actividade',
							)
						),
					);

		$Cliente = $this->Cliente->find('first', $options);
		$this->set('cliente', $Cliente);

		$opcionesActividad = array(
								   'conditions'=>array('Actividadcliente.cliente_id' => $ClienteId),
								   'contain'=> array(
								   	  'Actividade',
									  'Venta' => array(
										  'conditions' => array(
											 'Venta.cliente_id' => $ClienteId,
											 'Venta.periodo' => $periodo,
											)
									  )
								 	)
							 );
		$actividades = $this->Actividadcliente->find('all', $opcionesActividad);

		/*
		SELECT * FROM actividadclientes 
		INNER JOIN actividades ON actividades.id = actividadclientes.actividade_id
		INNER JOIN ventas ON ventas.actividadcliente_id = actividadclientes.id 
		AND ventas.cliente_id = actividadclientes.cliente_id
		WHERE actividadclientes.cliente_id = 4
		*/

		$opcionesVenta = array(
								'conditions'=>array(
									'Venta.cliente_id' => $ClienteId,
									'Venta.periodo' => $periodo,
									),
								'contain' => array(
													'Actividadcliente' => array(																				
																				'Actividade'
																				)
													//'actividades' => array(
													//						'conditions' => array('Actividade.id' => 'Actividadcliente.actividade_id')
													//					   )
												  )
							  );
		$ventas = $this->Venta->find('all', $opcionesVenta);

		$opcionesCompra = array(
								'conditions'=>array(
									'Compra.cliente_id' => $ClienteId,
									'Compra.periodo' => $periodo,
									),
								'contain' => array(
													'Actividadcliente' => array(																				
																				'Actividade'
																				)
													//'actividades' => array(
													//						'conditions' => array('Actividade.id' => 'Actividadcliente.actividade_id')
													//					   )
												  )
							  );
		$compras = $this->Compra->find('all', $opcionesCompra);

		$this->set('actividades', $actividades); 
		$this->set('ventas', $ventas); 
		$this->set('compras', $compras); 
		//$CondicionVenta = array('conditions' => array('Venta.cliente_id' => $ClienteId));
		//$Ventas = $this->Venta->find('all', $CondicionVenta);
		//$this->set('venta', $Ventas);
	}

}
