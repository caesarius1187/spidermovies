<?php
App::uses('AppController', 'Controller');

class ControlesController extends AppController {

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
	public function cuentasdepuradas($ClienteId = null,$periodo=null) {
		$this->loadModel('Cliente');
		$this->loadModel('Venta');
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
			[
				'conditions' => [
					'Cliente.' . $this->Cliente->primaryKey => $ClienteId],
				'contain' => [
					'Impcli'=>[
						'Impuesto'=>[
							'conditions'=>[
								'Impuesto.tipo'=>'bancos'
							]
						],
						'Cbu'=>[
							'Movimientosbancario'=>[
								'conditions'=>[
									'SUBSTRING(Movimientosbancario.periodo,4,7)*1 = '.$añoPeriodo.'*1',
								]
							]
						]
					],
				],
			];

		$cliente = $this->Cliente->find('first', $options);
		$optionsVentas =
			[
				'contain' => [
					'Comprobante'=>[
						'fields'=>['tipodebitoasociado']
					],

				],
				'fields'=>[
					'Venta.cliente_id','Venta.periodo','SUM(neto) as neto','SUM(iva) as iva','SUM(total) as total'
				],
				'conditions'=>[
					'SUBSTRING(Venta.periodo,4,7)*1 = '.$añoPeriodo.'*1',
					'Venta.cliente_id' => $ClienteId
				],
				'group'=>[
					'Venta.periodo','Venta.comprobante_id',
				]
			];
		$ventas = $this->Venta->find('all', $optionsVentas);

        $this->set(compact('cliente','ventas','periodo'));
	}

}
