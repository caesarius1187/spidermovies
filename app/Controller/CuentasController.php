<?php
App::uses('AppController', 'Controller');
/**
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class CuentasController extends AppController {

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
	public function view($ClienteId) 
	{				
		$options = array(
			'contain'=>[],
			'fields'=> [
                'Cuenta.id,Cuenta.numero,Cuenta.nombre,Cuenta.tipo,Cuenta.ajuste,Cuenta.level,
                Cuentascliente.id,Cuentascliente.cuenta_id,Cuentascliente.nombre'],
			'joins'=>array(
				array('table'=>'cuentasclientes', 
	                  'alias' => 'cuentascliente',
	                  'type'=>'left',
	                  'conditions'=> [
	                 		'cuentascliente.cuenta_id = Cuenta.id',
						    'cuentascliente.cliente_id'=>$ClienteId
	           		   ]
                 	),
				)
		);
		$cuentas = $this->Cuenta->find('all', $options);
		$this->set('cuentas',$cuentas);				
		$this->set('clienteId',$ClienteId);
	}

	public function activar($ClienteId,$CuentaId,$Activo,$CuentaclienteId=null)
	{
		$this->loadModel('Cuentascliente');
		$this->loadModel('Cuenta');
		if ($Activo == "1")
		{
			//si ya existe 
			//Activar
			$optionsCuenta = [
				'contain'=>[],
				'conditions' => ['Cuenta.id' => $CuentaId], 
				'fields'=> ['Cuenta.nombre']				
			];
			$CuentaDesc = $this->Cuenta->find('first', $optionsCuenta);

			$this->Cuentascliente->create();
			$this->Cuentascliente->set('cliente_id',$ClienteId);
			$this->Cuentascliente->set('cuenta_id',$CuentaId);
			$this->Cuentascliente->set('nombre',$CuentaDesc['Cuenta']['nombre']);
			$this->Cuentascliente->set('activada',1);
			if ($this->Cuentascliente->save())
			{ 				
				$data['respuesta']='Cuenta activada correctamente.';
			}
			else
			{				
				$data['respuesta']='Error al guardar cuenta. Por favor intente nuevamente.';			
			}
		}
		else
		{
			/*todo: Borrar Asientos
			Esto deberia disparar un alerta cuando hay Asientos relacionados a esta cuenta por que podriamos
			eliminarlos y desactivarlos horriblemente*/
			//Desactivar



			$optionsCuentascliente = [
				'contain'=>['Movimiento'],
				'conditions' => ['Cuentascliente.id' => $CuentaclienteId],
				'fields'=> ['Cuentascliente.nombre']
			];
			$CuentaClienteDesc = $this->Cuentascliente->find('first', $optionsCuentascliente);

			if(count($CuentaClienteDesc)==0){
				if ($this->Cuentascliente->deleteAll(array(
						'Cuentascliente.cliente_id' => $ClienteId,
						'Cuentascliente.cuenta_id' => $CuentaId
					),false
					)
				)
					$data['respuesta']='Cuenta desactivada correctamente.';
				else
					$data['respuesta']='Error al desactivar cuenta.';
			}else{
				if(count($CuentaClienteDesc['Movimiento'])>0){
					$data['respuesta']='Error al desactivar cuenta. Esta cuenta tiene Movimientos Cargados y no se puede borrar hasta que se eliminen';
				}else{
					$deleted= true;
				$this->Cuentascliente->deleteAll([
					'Cuentascliente.id' => $CuentaclienteId,
				],false
				);
					if ($deleted)
						$data['respuesta']='Cuenta desactivada correctamente.';
					else
						$data['respuesta']='Error al desactivar cuenta.';
				}
			}

		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');		
	}
}
