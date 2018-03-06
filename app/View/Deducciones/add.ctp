<div class="impcliprovincias form" style="width: 100%;">
<?php if(isset($error)){
    echo $error;
}else{	
    echo $this->Form->create('Deduccione',array('class'=>'formTareaCarga formAddImpcliprovincia','type' => 'post')); ?>
    <h3><?php
    echo __('Cargar Deducciones para Ganancias PF');
?></h3>
<table class="tabla">
    <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Deduccione.id',array('type'=>'hidden'));
                echo  $this->Form->input('Deduccione.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                echo $this->Form->input('Deduccione.tipo', array(
                        'options'=>$optiontipoDeduccion,          
                        'default'=>'personal',          
                        'value'=>'personal',          
                    )
                );
                $optionsDeduccionesGanancias=[
                    'personal'=>[
                        'Deduccion especial'=>'Deduccion especial',
                        'Deduccion incrementada'=>'Deduccion incrementada',
                        'Conyuge' =>'Conyuge',
                        'Hijos'=>'Hijos',
                        'Otras Cargas'=>'Otras Cargas'
                    ],
                    //'general'=>[
                        /*'Seguro de vida' =>'Seguro de vida',
                        'Gastos de sepelio' =>'Gastos de sepelio',
                        'Aportes a Obras Sociales' =>'Aportes a Obras Sociales',
                        'Servicio Domestico' =>'Servicio Domestico',
                        'Aportes al Regimen de Trabajadores Autonomos' =>'Aportes al Regimen de Trabajadores Autonomos',
                        'Aportes a sociedades de garantia reciproca' =>'Aportes a sociedades de garantia reciproca',
                        'Otras' =>'Otras',
                        'Donaciones' =>'Donaciones',
                        'Cuota medico-asistencial' =>'Cuota medico-asistencial',
                        'Honorarios por servicios de asistencia sanitaria, medica y paramedica',*/
                  //  ],
                    
                ];
                
                echo $this->Form->input('Deduccione.clase', array(
                        'options'=>$optionsDeduccionesGanancias,      
                    )
                );
                echo $this->Form->input('Deduccione.nombre', array(
                     'class'=>'personal general hijo conyuge otrascargas'
                    )
                );
                echo $this->Form->input('Deduccione.real', array(
                     'class'=>'general'
                    )
                );
                echo $this->Form->input('Deduccione.tope', array(
                     'class'=>'general'
                    )
                );
                echo $this->Form->input('Deduccione.computable', array(
                     'class'=>'general'
                    )
                );
                echo $this->Form->input('Deduccione.nodeducible', array(
                    'class'=>'general',
                    'label'=>'No deducible'
                    )
                );
                echo $this->Form->input('Deduccione.cuit', array(
                    'label'=>'CUIT',
                    'class'=>'general serviciodomestico hijo conyuge otrascargas',
                    )
                );
                echo $this->Form->input('Deduccione.cuil', array(
                    'label'=>'CUIL',
                    'class'=>'general serviciodomestico hijo conyuge otrascargas',
                    )
                );
                echo $this->Form->input('Deduccione.importepagadoaldependiente', array(
                    'label'=>'Importe pagado al dependiente',
                    'class'=>'general importepagadoaldependiente'
                    )
                );
                echo $this->Form->input('Deduccione.importedeaportespagados', array(
                    'label'=>'Importe de aportes pagados',
                    'class'=>'general importepagadoaldependiente'
                    )
                );
                echo $this->Form->input('Deduccione.beneficiario', array(
                    'class'=>'general donaciones'
                    )
                );
                echo $this->Form->input('Deduccione.tipodonacion', array(
                    'label'=>'Tipo donacion',
                    'class'=>'general donaciones'
                    )
                );
                echo $this->Form->input('Deduccione.documento', array(
                    'class'=>'personal hijo conyuge otrascargas',
                    )
                );
                echo $this->Form->input('Deduccione.iniciorelacion', array(
                    'class'=>'datepicker personal hijo conyuge otrascargas', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Desde',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Deduccione.finrelacion', array(
                    'class'=>'datepicker personal hijo conyuge otrascargas', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Hasta',                                    
                    'readonly'=>'readonly'
                    )
                );
            ?>
            </div>
	</td>
	<tr>
		<td>&nbsp;</td>
		<td>
			<a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>  
		</td>
		<td>
			<?php echo $this->Form->end(__('Aceptar')); ?>
		</td>
	</tr>
</table>
		
<?php	
}
if(!isset($error)){ ?>
	<table cellpadding="0" cellspacing="0" border="0">
            <thead>
                <td>Clase</td>
                <td>Descripcion</td>
                <td>Acciones</td>
            </thead>
            <tbody>
                <?php
                foreach ($deducciones as $deduccion) { ?>
                    <tr id="#rowDeduccion<?php echo $deduccion['Deduccione']['id']; ?>">  
                            <td><?php echo $deduccion['Deduccione']['clase'];?></td>
                            <td><?php echo $deduccion['Deduccione']['nombre']
                                    ." CUIT: ".$deduccion['Deduccione']['cuit']
                                    ." CUIL: ".$deduccion['Deduccione']['cuil']
                                    ." DNI: ".$deduccion['Deduccione']['documento']
                                    ?></td>
                            <td>
                                <a href="#"  onclick="deleteDeduccion(<?php echo $deduccion['Deduccione']['id']; ?>)" class="button_view"> 
                                    <?php echo $this->Html->image('delete.png', array('alt' => 'open','title' => 'Eliminar','class'=>'imgedit'));?>
                                </a>
                            </td>
                    </tr>            
                    <?php } ?>
            </tbody>
	</table>
<?php } ?>
</div>
