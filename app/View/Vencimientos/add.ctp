<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('vencimientos/add',array('inline'=>false));?>
<div class="vencimientos form index">
    <table style="width: 400px;"> 
        <tr>
            <td>
                <?php echo $this->Form->input('impuesto_id',array('display'=>'inline'));?>
            </td>
            <td>
                <?php 
                echo $this->Form->input('ano',
                    array(
                        'type'=>'year',
                        'label'=>'Año',
                        'display'=>'inline',
                        'selected' => array(
                                        'year' => date('Y'),
                                        ),
                    )
                );
                ?>
            </td>
            <td>
               <?php echo $this->Form->button('+ Condicion',array('onClick'=>'agregarColumna()','type'=>'button'));?>
            </td>
        </tr>
    </table>
<?php				
 echo $this->Form->create('Vencimiento',array('class'=>'formTareaCarga')); ?>
	<fieldset>
		<legend><?php echo __('Agregar Vencimientos'); ?></legend>
	<?php
	$i=0;
	?>
	</fieldset>

    <table cellpadding="0" cellspacing="0" border="0" class="tbl_ven" id="tbl_ven">
        <tr id="trCuit">
            <th>CUIT</th>
            <td>
            	<?php
            	echo $this->Form->input('Vencimiento.'.$i.'.orden',array('value'=>$i,'type'=>'hidden'));
            	echo $this->Form->input('Vencimiento.'.$i.'.desde',array( ));
				echo $this->Form->input('Vencimiento.'.$i.'.hasta',array( ));
				echo $this->Form->input('Vencimiento.'.$i.'.impuesto_id',array('type'=>'hidden','value'=>'0','class'=>'selectImpuestoId'));
				echo $this->Form->input('Vencimiento.'.$i.'.ano',array('type'=>'hidden','class'=>'selectAno'));
				?>
            </td>
        </tr>
        <tr id="tr1">
            <td>Enero</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p01',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr2">
            <td>Febrero</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p02',array('label'=>false)); 
				?>
			</td>	
        </tr>
        <tr id="tr3">
            <td>Marzo</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p03',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr4">
            <td>Abril</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p04',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr5">
            <td>Mayo</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p05',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr6">
            <td>Junio</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p06',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr7">
            <td>Julio</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p07',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr8">
            <td>Agosto</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p08',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr9">
            <td>Septiembre</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p09',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr10">
            <td>Octubre</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p10',array('label'=>false)); 
				?>
			</td>
        <tr id="tr11">
            <td>Noviembre</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p11',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr id="tr12">
            <td>Diciembre</td>
            <td>
            	<?php
				echo $this->Form->input('Vencimiento.'.$i.'.p12',array('label'=>false)); 
				?>
			</td>
        </tr>
        <tr>
			<td colspan="6">
		        		        
      		</td>
        </tr> 
	</table>
<?php echo $this->Form->end(__('Aceptar')); ?>
</div>

