<div class="impcliprovincias form" style="width: 100%;">
<?php if(isset($error)){
    echo $error;
}else{	
    echo $this->Form->create('Quebranto',array('class'=>'formTareaCarga formAddImpcliprovincia','type' => 'post')); ?>
    <h3><?php
    echo __('Cargar Quebrantos para Ganancias PF');
?></h3>
<table class="tabla">
    <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                                
                echo $this->Form->input('Quebranto.0.periodogeneral', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Periodo de aplicacion',                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
               
            ?>
            </div>
	</td>
    </tr>
    <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.0.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.0.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Quebranto.0.periodo', array(
                    'class'=>'datepicker-month-year periodoaplicacion', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Periodo de aplicacion',                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.0.periodogenerado', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Periodo de generacion',                                    
                    'title'=>'Periodo en el que se genero el quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.0.monto', array(
                    'title'=>'Monto original',                                                        
                    )
                );
                echo $this->Form->input('Quebranto.0.usado', array(
                    'title'=>'Cantidad usada en este periodo',
                    )
                );
                echo $this->Form->input('Quebranto.0.saldo', array(
                    'title'=>'Saldo a utilizar en periodos restantes',                                                        
                    )
                );
            ?>
            </div>
	</td>
    </tr>
     <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.1.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.1.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Quebranto.1.periodo', array(
                    'class'=>'datepicker-month-year periodoaplicacion', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.1.periodogenerado', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                     
                    'title'=>'Periodo en el que se genero el quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.1.monto', array(
                    'title'=>'Monto original',          
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.1.usado', array(
                    'title'=>'Cantidad usada en este periodo',
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.1.saldo', array(
                    'title'=>'Saldo a utilizar en periodos restantes',    
                    'label'=>false,          
                    )
                );
            ?>
            </div>
	</td>
    </tr>
     <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.2.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.2.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Quebranto.2.periodo', array(
                    'class'=>'datepicker-month-year periodoaplicacion', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.2.periodogenerado', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                     
                    'title'=>'Periodo en el que se genero el quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.2.monto', array(
                    'title'=>'Monto original',             
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.2.usado', array(
                    'title'=>'Cantidad usada en este periodo',
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.2.saldo', array(
                    'title'=>'Saldo a utilizar en periodos restantes',     
                    'label'=>false,          
                    )
                );
            ?>
            </div>
	</td>
    </tr>
     <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.3.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.3.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Quebranto.3.periodo', array(
                    'class'=>'datepicker-month-year periodoaplicacion', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.3.periodogenerado', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                     
                    'title'=>'Periodo en el que se genero el quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.3.monto', array(
                    'title'=>'Monto original',                    
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.3.usado', array(
                    'title'=>'Cantidad usada en este periodo',
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.3.saldo', array(
                    'title'=>'Saldo a utilizar en periodos restantes',   
                    'label'=>false,          
                    )
                );
            ?>
            </div>
	</td>
    </tr>
     <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.4.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.4.impcli_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Quebranto.4.periodo', array(
                    'class'=>'datepicker-month-year periodoaplicacion', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.4.periodogenerado', array(
                    'class'=>'datepicker-month-year', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>false,                                     
                    'title'=>'Periodo en el que se genero el quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.4.monto', array(
                    'title'=>'Monto original',             
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.4.usado', array(
                    'title'=>'Cantidad usada en este periodo',
                    'label'=>false,          
                    )
                );
                echo $this->Form->input('Quebranto.4.saldo', array(
                    'title'=>'Saldo a utilizar en periodos restantes',   
                    'label'=>false,          
                    )
                );
            ?>
            </div>
	</td>
    </tr>
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
            <td>Periodo</td>
            <td>Periodo Origen</td>
            <td>Monto</td>
            <td>Saldo</td>
            <td>Usado</td>
        </thead>
        <tbody>
            <?php
            foreach ($quebrantos as $quebranto) { ?>
                <tr id="#rowDeduccion<?php echo $quebranto['Quebranto']['id']; ?>">  
                        <td><?php echo $quebranto['Quebranto']['periodo'];?></td>
                        <td><?php echo $quebranto['Quebranto']['periodogenerado'];?></td>
                        <td><?php echo $quebranto['Quebranto']['monto'];?></td>
                        <td><?php echo $quebranto['Quebranto']['usado'];?></td>
                        <td><?php echo $quebranto['Quebranto']['saldo'];?></td>
                        <td>
                            <a href="#"  onclick="deleteQuebranto(<?php echo $quebranto['Quebranto']['id']; ?>)" class="button_view"> 
                                <?php echo $this->Html->image('delete.png', array('alt' => 'open','title' => 'Eliminar','class'=>'imgedit'));?>
                            </a>
                        </td>
                </tr>            
                <?php } ?>
        </tbody>
    </table>
<?php } ?>
</div>
