<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 10/01/2017
 * Time: 11:43 AM
 */
//Debugger::dump($cuentascategoriaprimera);
//Debugger::dump($cuentascategoriasegunda);
//Debugger::dump($cuentascategoriatercera);
//Debugger::dump($cuentascategoriaterceraotros);
//Debugger::dump($cuentascategoriacuarta);

echo $this->Form->input('cuentascategoriaprimera',[
    'type'=>'hidden',
    'value'=> json_encode($cuentascategoriaprimera)
]);
echo $this->Form->input('cuentascategoriasegunda',[
    'type'=>'hidden',
    'value'=> json_encode($cuentascategoriasegunda)
]);
echo $this->Form->input('cuentascategoriatercera',[
    'type'=>'hidden',
    'value'=> json_encode($cuentascategoriatercera)
]);
echo $this->Form->input('cuentascategoriaterceraotros',[
    'type'=>'hidden',
    'value'=> json_encode($cuentascategoriaterceraotros)
]);
echo $this->Form->input('cuentascategoriacuarta',[
    'type'=>'hidden',
    'value'=> json_encode($cuentascategoriacuarta)
]);
?>
    <h2><?php echo __('Asignar Categorias a las actividades'); ?></h2>
<?php
echo $this->Form->create('Cuentasganancia',[
    'class'=>'formTareaCarga',
    'controller'=>'cuentasganancias',
    'action'=>'index'
]);
foreach ($cliente['Actividadcliente'] as $a => $actividade){
    ?>
    <div>
    <?php
    echo $this->Form->label($actividade['Actividade']['nombre']);
            //vamos a ver si ya esta creada esta cuenta cliente para esta actividad
    $numeroDecuentaYaseleccionada = 0;
    $idCuentasganancia = 0;
    $idCuentascliente = 0;
    $idCuentas = 0;
    $categoriaCuentasganancia = "";
    if(count($actividade['Cuentasganancia'])>0){
        $idCuentas = $actividade['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
        $idCuentascliente = $actividade['Cuentasganancia'][0]['Cuentascliente']['id'];
        $idCuentasganancia = $actividade['Cuentasganancia'][0]['id'] ;
        $nombreCuentasganancia = $actividade['Cuentasganancia'][0]['Cuentascliente']['nombre'];
        $categoriaCuentasganancia = $actividade['Cuentasganancia'][0]['categoria'];
    }
    echo $this->Form->input('Cuentasganancia.'.$a.'.id',[
        'type'=>'hidden',
        'value'=>$idCuentasganancia,
    ]);
    echo $this->Form->input('Cuentasganancia.'.$a.'.cuentascliente_id',[
        'type'=>'hidden',
        'value'=>$idCuentascliente,
    ]);
    echo $this->Form->input('Cuentasganancia.'.$a.'.actividadcliente_id',[
        'type'=>'hidden',
        'value'=>$actividade['id'],
    ]);
    echo $this->Form->input('Cuentasganancia.'.$a.'.cliente_id',[
        'type'=>'hidden',
        'value'=>$actividade['cliente_id'],
    ]);
    echo $this->Form->input('Cuentasganancia.'.$a.'.categoria',[
        'class'=>'inputcategoria',
        'default'=>$categoriaCuentasganancia,
        'posicion'=>$a,
    ]);
    echo $this->Form->input('Cuentasganancia.'.$a.'.cuenta_id',[
        'label'=>'Cuenta',
        'defaultoption'=>$idCuentas,
        'title'=>'Esta es la cuenta que se usara por defecto para asignar el neto de ventas en los asientos'
    ]);
    ?>
    </div>

    <?php
}
echo $this->Form->end('Aceptar');


?>