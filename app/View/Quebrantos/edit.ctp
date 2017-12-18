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
                    'value'=>$periodo,
                    'label'=>'Periodo de aplicacion',                                    
                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
               
            ?>
            </div>
	</td>
    </tr>
    
    <?php
    $q1=[];
    $q2=[];
    $q3=[];
    $q4=[];
    $q5=[];
    $aniosARestar=1;
    $pos=0;
    foreach ($quebrantos as $kque => $quebranto) {
        $periodo1=date('Y', strtotime('01-'.$periodo.' -'.$aniosARestar.' Years'));
        $periodoq1=date('Y', strtotime('01-'.$quebranto['Quebranto']['periodogenerado']));
        if($periodo1==$periodoq1){
            ?>
            <tr>
                <td colspan=3>
                    <div class="div_view">
                                <?php
                                echo $this->Form->input('Quebranto.'.$pos.'.id',array(
                                    'type'=>'hidden',
                                    'value'=>$quebranto['Quebranto']['id']
                                    ));
                                echo  $this->Form->input('Quebranto.'.$pos.'.impcli_id',array(
                                    'type'=>'hidden','value'=>$impcliid,
                                    'value'=>$quebranto['Quebranto']['impcli_id']
                                    ));                    

                                echo $this->Form->input('Quebranto.'.$pos.'.periodo', array(
                                    'class'=>'datepicker-month-year periodoaplicacion', 
                                    'type'=>'text',
                                    'value'=>$quebranto['Quebranto']['periodo'],
                                    'label'=>'Periodo de aplicacion',                                    
                                    'title'=>'Periodo en el que se va a usar este quebranto',                                    
                                    'readonly'=>'readonly'
                                    )
                                );
                                echo $this->Form->input('Quebranto.'.$pos.'.periodogenerado', array(
                                    'class'=>'datepicker-month-year', 
                                    'type'=>'text',
                                    'value'=>$quebranto['Quebranto']['periodogenerado'],
                                    'label'=>'Periodo de generacion',                                    
                                    'title'=>'Periodo en el que se genero el quebranto',                                    
                                    'readonly'=>'readonly'
                                    )
                                );
                                echo $this->Form->input('Quebranto.'.$pos.'.monto', array(
                                    'value'=>$quebranto['Quebranto']['monto'],
                                    'title'=>'Monto original',                                                        
                                    )
                                );
                                echo $this->Form->input('Quebranto.'.$pos.'.usado', array(
                                    'value'=>$quebranto['Quebranto']['usado'],
                                    'title'=>'Cantidad usada en este periodo',
                                    )
                                );
                                echo $this->Form->input('Quebranto.'.$pos.'.saldo', array(
                                    'value'=>$quebranto['Quebranto']['saldo'],
                                    'title'=>'Saldo a utilizar en periodos restantes',                                                        
                                    )
                                );
                                 ?>
                         </div>
                </td>
            </tr>
        <?php
            }
        $aniosARestar++;
        $pos++;

        }
    ?>
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
}?>
</div>
