<?php
App::uses('AppModel', 'Model');
/**
 * Notification Model
 *
 */
class Notification extends AppModel {


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(				
		
	);
        public function checkNotificationsFor($cliente){
            $this->createVencimientosNotifications($cliente);
            $this->createExclusionMonotributo($cliente);
           
        }
        public function createVencimientosNotifications($cliente){
            $ModelCliente = ClassRegistry::init('Cliente');
            $ModelVencimiento = ClassRegistry::init('Vencimiento');
            $ModelSession = ClassRegistry::init('Session');
            $today = date('Y-m-d', strtotime(date('Y-m-d')." -1 month"));
            $periodo = date('m-Y', strtotime(date('Y-m-d')." -1 month"));
            $peanio = date('Y', strtotime(date('Y-m-d')." -1 month"));
            $pemes = date('m', strtotime(date('Y-m-d')." -1 month"));
             
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
            $containCliAuth = array(
                'Impcli'=>[
                    'Eventosimpuesto'=>[
                        'conditions'=>['Eventosimpuesto.periodo'=>$periodo]
                    ],
                    'Periodosactivo' => array(
                        'conditions' => $conditionsImpCliHabilitados,
                    ),
                    'Impuesto'=>array(
                        'Vencimiento'=>array(
                            'conditions'=>array(
                                'SUBSTRING("'.$periodo.'",4,7) = Vencimiento.ano*1',
                                'Vencimiento.desde <= SUBSTRING("'.$cliente['Cliente']['cuitcontribullente'].'",-1)',
                                'Vencimiento.hasta >= SUBSTRING("'.$cliente['Cliente']['cuitcontribullente'].'",-1)',
                            ),
                        ),
                    ),
                ]
            );           
            $clientesAuth = $ModelCliente->find('first',array(
                    'contain' =>$containCliAuth,
                    'conditions' => array(                        
                        'Cliente.id' => $cliente['Cliente']['id'] ,                        
                    )
                )
            );
            //Debugger::dump("Cliente: ".$clientesAuth['Cliente']["nombre"]);
            foreach ($clientesAuth['Impcli'] as $kimpcli => $impcli) {
                if(count($impcli['Periodosactivo'])==0){
                    //No tiene activado este periodo
                    //Debugger::dump("no se creo notificacion para: ".$impcli["Impuesto"]["nombre"]." por que ya esta liquidado");
                    continue;
                }
                //Todo: aca tenemos que preguntar si ya hicimos notificacion para este impcli en este periodo      
                $conditions = array(
                    'Notification.impcli_id' => $impcli["id"],
                    'Notification.periodo' => $periodo,
                    'Notification.action' => 'vencimiento'
                );
                if ($this->hasAny($conditions)){
                    //Debugger::dump("no se creo notificacion para: ".$impcli["Impuesto"]["nombre"]." por que ya estaba creada");
                    continue;
                }
                //fin consulta Notificacion ya realizada
                if(count($impcli['Eventosimpuesto'])>0){
                    //tiene este evento impuesto creado asi que no hay notificacion para crear
                    //Debugger::dump("no se creo notificacion para: ".$impcli["Impuesto"]["nombre"]." por que ya esta liquidado");
                    continue;
                }
                $strfchvto = "";
                $fchvto = -1;
                switch ($pemes) {
                    case '12':
                        if($impcli["Impuesto"]["organismo"]=='sindicato'){
                            $strfchvto = strtotime('15-01-'.($peanio+1));
                            $fchvto = date('Y-m-d',$strfchvto);
                        }else{
                            $optionsVencimientoImpuesto = array(
                            'conditions'=>array(
                                    'SUBSTRING("'.$periodo.'",4,7)+1 = Vencimiento.ano*1',
                                    'Vencimiento.desde <= SUBSTRING("'.$cliente['cuitcontribullente'].'",-1)',
                                    'Vencimiento.hasta >= SUBSTRING("'.$cliente['cuitcontribullente'].'",-1)',
                                    'Vencimiento.impuesto_id'=>$impcli["Impuesto"]["id"],
                                ),
                            );
                            $vencimiento = $ModelVencimiento->find('first',$optionsVencimientoImpuesto);
                            if(isset($vencimiento['Vencimiento']['p01'])&&$vencimiento['Vencimiento']['p01']!=0){
                                $strfchvto = strtotime($vencimiento['Vencimiento']['ano'].'-01-'.$vencimiento['Vencimiento']['p01']);
                                $fchvto = date('Y-m-d',$strfchvto);
                            }
                        }
                    break;
                    default:
                        if($impcli["Impuesto"]["organismo"]=='sindicato'){
                            $strfchvto = strtotime($peanio.'-'.$pemes.'-01 +1 months');
                            $fchvto = date('d-m-Y',$strfchvto);
                        }else{
                            foreach ($impcli["Impuesto"]["Vencimiento"] as $vencimiento) {
                                $strfchvto = strtotime('01-'.$pemes.'-'.$peanio.' +1 months');
                                $periodoPosterior = date('m',$strfchvto);
                                if($vencimiento['p'.$periodoPosterior]!=0){                                    
                                    $fchvto = $peanio.'-'.$periodoPosterior.'-'.$vencimiento['p'.$periodoPosterior];
                                }
                            }	
                        } 
                    break;
                }
                //si no pudimos crear un $fchvto pasamos 
                if($fchvto==-1){
                    continue;
                }
                //si la feche de vencimiento recomendada dista en 2 dias de la fecha actual o esta pasada
                //creamos notificacion                
                $fecha1= new DateTime($fchvto);
                $fecha2= new DateTime(date("Y-m-d"));
                $diff = $fecha1->diff($fecha2);
                if($fecha1<$fecha2){
                    //Debugger::dump("Impuesto ya vencido por ".$diff->days*1 ." dias: ".$impcli["Impuesto"]["nombre"]);      
                    continue;
                }
                if(($diff->days*1)>2){
                    //Debugger::dump("Faltan ".$diff->days*1 ." para que venza este impuesto ".$impcli["Impuesto"]["nombre"]);
                    continue;
                }else{
                                  
                }                
                // El resultados sera 3 dias
               
                //bueno ahora vamos a crear la notificacion para este cliente sobre este impuesto en este periodo para todos los usuarios
                $this->create();
                $this->set('cliente_id',$impcli["cliente_id"]);
                $this->set('impcli_id',$impcli["id"]);
                $this->set('estudio_id',$cliente['Grupocliente']['estudio_id']);
                $this->set('fecha',date('Y-m-d', strtotime($fchvto)));
                $this->set('periodo',$periodo);
                $this->set('action','vencimiento');
                $this->set('texto',$impcli["Impuesto"]["nombre"].' del contribuyente '.$cliente['Cliente']['nombre'].' vence el '.date('d-m-Y', strtotime($fchvto)));
                if ($this->save())
                {
                    //Debugger::dump("se creo notificacion para: ".$impcli["Impuesto"]["nombre"]);
                    //Debugger::dump($diff->days . ' dias');
                    //Debugger::dump($fecha1);
                    //Debugger::dump($fecha2);
                    //Debugger::dump($diff);
                }
                else
                {
                    //Debugger::dump("ERROR NO se creo notificacion para: ".$impcli["Impuesto"]["nombre"]);
                }
            }
        }
        public function createExclusionMonotributo($cliente){
            $ModelCliente = ClassRegistry::init('Cliente');
            $ModelMonotributistasexcluido = ClassRegistry::init('Monotributistasexcluido');
            $ModelSession = ClassRegistry::init('Session');
            $today = date('Y-m-d', strtotime(date('Y-m-d')." -1 month"));
            $periodo = date('m-Y', strtotime(date('Y-m-d')." -1 month"));
            $peanio = date('Y', strtotime(date('Y-m-d')." -1 month"));
            $pemes = date('m', strtotime(date('Y-m-d')." -1 month"));
             
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
            $containCliAuth = array(
                'Impcli'=>[
                    'Periodosactivo' => array(
                        'conditions' => $conditionsImpCliHabilitados,
                    ),
                    'Impuesto'=>array(
                        'conditions' => [
                            'Impuesto.id'=>4/*Monotributo*/,
                            'Impuesto.id'=>'Impcli.impuesto_id'
                            ],
                    ),
                ]
            );           
            $clientesAuth = $ModelCliente->find('first',array(
                    'contain' =>$containCliAuth,
                    'conditions' => array(                        
                        'Cliente.id' => $cliente['Cliente']['id'] ,                        
                    )
                )
            );
            //Debugger::dump("Cliente: ".$clientesAuth['Cliente']["nombre"]);
            foreach ($clientesAuth['Impcli'] as $kimpcli => $impcli) {
                $cuitCliente = str_replace('-','',$cliente['Cliente']['cuitcontribullente']); 
                $conditionsMonotributistasexcluido = [
                    'conditions'=>[
                        'Monotributistasexcluido.cuit' => $cuitCliente ,
                    ]
                ];
                $conditionsMonotributistasexcluidoHASANY = [
                    'Monotributistasexcluido.cuit' => $cuitCliente ,
                ];         
                 
                if($impcli['impuesto_id']!='4'){
                    //No Es Monotributo
                    //Debugger::dump("no se creo notificacion para:  por no es monotributos");
                    continue;
                }
                if(count($impcli['Periodosactivo'])==0){
                    //No tiene activado este periodo
                    continue;
                }
                //Todo: aca tenemos que preguntar si ya hicimos notificacion para este impcli en este periodo      
                $conditions = array(
                    'Notification.impcli_id' => $impcli["id"],
                    'Notification.action' => 'ExclusionMonotributo'
                );
                if ($this->hasAny($conditions)){
                }
             
                
                //fin consulta Notificacion ya realizada                
                //**vamos a buscar si este Monotributista esta incluido en las listas de exclusion
                if ($ModelMonotributistasexcluido->hasAny($conditionsMonotributistasexcluidoHASANY)){
                    // El resultados sera 3 dias
                     $monotributistaExcluido = $ModelMonotributistasexcluido->find('first',$conditionsMonotributistasexcluido);
                    //bueno ahora vamos a crear la notificacion para este cliente sobre este impuesto en este periodo para todos los usuarios
                    $this->create();
                    $this->set('cliente_id',$impcli["cliente_id"]);
                    $this->set('impcli_id',$impcli["id"]);
                    $this->set('estudio_id',$cliente['Grupocliente']['estudio_id']);
                    $this->set('fecha',date('Y-m-d'));
                    $this->set('periodo',$periodo);
                    $this->set('action','ExclusionMonotributo');
                    $this->set('texto','El contribuyente '.$cliente['Cliente']['nombre'].' ha sido excluido del Monotributo por Causal: '.$monotributistaExcluido['Monotributistasexcluido']['causal']);
                    if ($this->save())
                    {
                        //Debugger::dump("se creo notificacion para: ".$impcli["Impuesto"]["nombre"]);
                        //Debugger::dump($diff->days . ' dias');
                        //Debugger::dump($fecha1);
                        //Debugger::dump($fecha2);
                        //Debugger::dump($diff);
                    }
                    else
                    {
                        //Debugger::dump("ERROR NO se creo notificacion para: ".$impcli["Impuesto"]["nombre"]);
                    }
                }
               
            }
        }
}
