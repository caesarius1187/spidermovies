<div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:10px; margin-top:10px; font-family: 'Arial'">
<?php echo $this->Form->create('Honorario',array(
								'id'=>'formAddHonoraio',
								'controller'=>'honorarios',
								'action'=>'add',
								'class'=>'formTareaCarga',
								)
							); ?>	
    <fieldset>
    	<legend><?php echo __('Honorario del periodo: '.$periodo); ?></legend>
    </fieldset>
    <?php
      echo $this->Form->input('id',array('type'=>'hidden'));
      echo $this->Form->input('evento_id',array('type'=>'hidden',));
      echo $this->Form->input('cliente_id',array('type'=>'hidden','value'=>$cliid,));
      if(empty($this->request->data)){
      	echo $this->Form->input('monto',array(      									
	      									'value'=>$honorariocliente,
	      									'div' => array(
		        									'style'=>'width:15%;'
	      								  			),
	      									)
      									);
      }else{
      	echo $this->Form->input('monto',array(
      										'div' => array(
		        								'style'=>'width:15%;'
	      								  			),	      									
      										));      	
      }
      echo $this->Form->input('fecha', array(
                                      'class'=>'datepicker', 
                                      'type'=>'text',
                                      'label'=>'Fecha',                                    
                                      'readonly'=>'readonly',
                                      'style' => 'width:80px',
                                      'div' => array(
	        									'style'=>'width:20%;'
      								  			),
                                      )
                                 );
      echo $this->Form->input('descripcion',array(
      										'style' => 'width:100%',
      										'div' => array(
	        									'style'=>'width:35%;'
      								  			),
      						 				)
      						 );
      echo $this->Form->input('periodo',array('type'=>'hidden','value'=>$periodo));
      echo $this->Form->input('estado',array('type'=>'hidden'));
    ?>
    
  <?php echo $this->Form->end(__('Agregar')); ?>
<?php /*Este es el formulario para Cargar Recibos(depositos) */ ?> 	
</div>
