 <SCRIPT> 
 $(document).ready(function() {
        $( "input.datepicker" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: true,
            changeYear: true,
            constrainInput: false,
            dateFormat: 'dd-mm-yy',
        });
})        
        </SCRIPT>
<?php echo $this->Form->create('Ingreso',array('action'=>'edit')); ?>
	<fieldset>
		<legend><?php echo __('Modificar Ingreso/Egreso'); ?></legend>
	<?php
		echo $this->Form->input('cliente_id',array('type'=>'hidden'));
		echo $this->Form->input('id',array('type'=>'hidden'));
        echo $this->Form->input('tipo', 
                        array(
                            'type'=>'select', 
                            'label'=>'Tipo', 
                            'options'=>array(
                                'ingreso'=>'Ingreso',
                                'egreso'=>'Egreso'), 
                            'default'=>'0'
                            )
                        );
        echo $this->Form->input('motivo');
        echo $this->Form->input('importe',array('type'=>'currency'));
        echo $this->Form->input('registro', array(
                        'class'=>'datepicker', 
                        'type'=>'text',
                        'label'=>'Registro',
                        'default'=>$this->request->data['Ingreso']['registro'],
                        'readonly'=>'readonly')
             );
		echo $this->Form->input('comentario');
		echo $this->Form->input('mesdesde', array('options' => array(
                                                                    '01'=>'Enero', 
                                                                    '02'=>'Febrero', 
                                                                    '03'=>'Marzo', 
                                                                    '04'=>'Abril', 
                                                                    '05'=>'Mayo', 
                                                                    '06'=>'Junio', 
                                                                    '07'=>'Julio', 
                                                                    '08'=>'Agosto', 
                                                                    '09'=>'Septiembre', 
                                                                    '10'=>'Octubre', 
                                                                    '11'=>'Noviembre', 
                                                                    '12'=>'Diciembre', 
                                                                    ),
                                                                'label'=> 'Mes',
                                                                'value'=> substr($this->request->data['Ingreso']['periodo'], 0, 2)
                                                            ));           
		echo $this->Form->input('aniodesde', array(
                                                    'options' => array(
                                                        '2014'=>'2014', 
                                                        '2015'=>'2015',     
                                                        ),
                                                    'label'=> 'Año',
                                                    'value'=> substr($this->request->data['Ingreso']['periodo'], 3, 4)
                                                    )
                                        );    
	?>
	</fieldset>
<?php echo $this->Form->end(__('Aceptar')); ?>
