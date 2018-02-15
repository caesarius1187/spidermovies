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
        $this->Components->unload('DebugKit.Toolbar');
        $options = array(
			'contain'=>[],
			'fields'=> [
                'Cuenta.id,Cuenta.numero,Cuenta.nombre,Cuenta.tipo,Cuenta.level,
                Cuentascliente.id,Cuentascliente.nombre'],
			'joins'=>array(
				array('table'=>'cuentasclientes', 
	                  'alias' => 'Cuentascliente',
	                  'type'=>'left',
	                  'conditions'=> [
	                 		'Cuentascliente.cuenta_id = Cuenta.id',
						    'Cuentascliente.cliente_id'=>$ClienteId
	           		   ]
                 	),
				),
//            'limit'=>1000,
//            'page'=>1,
            'order'=>['numero'],
        );
		$cuentas = $this->Cuenta->find('all', $options);

        $this->set('cuentas',$cuentas);
		$this->set('clienteId',$ClienteId);
	}
    public function loadCuentas($ClienteId,$pagina)
    {
        $this->Components->unload('DebugKit.Toolbar');
        ini_set('max_execution_time', 300);

        $options = array(
            'contain'=>[],
            'order'=>['numero'],
            'fields'=> [
                'Cuenta.id,Cuenta.numero,Cuenta.nombre,Cuenta.tipo,Cuenta.level,
                Cuentascliente.id,Cuentascliente.nombre'],
            'joins'=>array(
                array('table'=>'cuentasclientes',
                    'alias' => 'cuentascliente',
                    'type'=>'left',
                    'conditions'=> [
                        'cuentascliente.cuenta_id = Cuenta.id',
                        'cuentascliente.cliente_id'=>$ClienteId
                    ]
                ),
            ),
            'limits'=>1000,
            'page'=>$pagina
        );
        $cuentas = $this->Cuenta->find('all', $options);
        $this->set('data',$cuentas);
        $this->layout = 'ajax';
        $this->render('serializejson');
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
				'conditions' => [
                                    'Cuentascliente.cuenta_id' => $CuentaId,
                                    'Cuentascliente.cliente_id' => $ClienteId
                                ],
				'fields'=> ['Cuentascliente.nombre']
			];
			$CuentaClienteDesc = $this->Cuentascliente->find('first', $optionsCuentascliente);
			if(count($CuentaClienteDesc['Movimiento'])==0){
                            if ($this->Cuentascliente->deleteAll(array(
                                    'Cuentascliente.cuenta_id' => $CuentaId,
                                    'Cuentascliente.cliente_id' => $ClienteId
                                    ),false
                                )
                            )
                                $data['respuesta']='Cuenta desactivada correctamente.';
                            else
                                $data['respuesta']='Error al desactivar cuenta.';
			}else{
                            $data['error']=1;
                            $data['respuesta']='Error al desactivar cuenta. Esta cuenta tiene Movimientos Cargados y no se puede borrar hasta que se eliminen';
			}

		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');		
	}
}
