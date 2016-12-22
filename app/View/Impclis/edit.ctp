<?php
if(!$showTheForm){?>
    <td><?php echo $this->request->data['Impuesto']['nombre']; 
        if( $this->request->data['Impuesto']['id']==4/*Monotributo*/){ 
          echo $this->request->data['Impcli']['categoriamonotributo']; 
        }?></td>
    <td>
        <?php //echo $this->request->data['Impcli']['descripcion'];              
          if (isset($this->request->data['Periodosactivo'][0]['desde']))
            echo  $this->request->data['Periodosactivo'][0]['desde'];              
         ?>
      </td>
    <?php 
    if( $this->request->data['Impuesto']['organismo']=='banco'||$this->request->data['Impuesto']['organismo']=='sindicato'){ ?>
      <td><?php echo $this->request->data['Impcli']['usuario']; ?></td>
      <td><?php echo $this->request->data['Impcli']['clave']; ?></td>
    <?php } ?>
    <td >
        <a href="#"  onclick="
          loadFormImpuesto(<?php echo $this->request->data['Impcli']['id']; ?>,<?php echo $this->request->data['Impcli']['cliente_id'];?>)" class="button_view"> 
          <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
        <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view"> 
            <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
        <a href="#"  onclick="deleteImpcli(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view">
            <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
        <?php
        //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias
        if($this->request->data['Impuesto']['id']==6/*Actividades Varias*/){ ?>
            <a href="#"  onclick="loadFormImpuestoLocalidades(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view">
                <?php echo $this->Html->image('localidad.png', array('alt' => 'open','class'=>'imgedit'));?>
            </a>
        <?php }
        //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias
        if($this->request->data['Impuesto']['id']==174/*Convenio Multilateral*/||$this->request->data['Impuesto']['id']==21/*Convenio Multilateral*/){?>
            <a href="#"  onclick="loadFormImpuestoProvincias(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view">
                <?php echo $this->Html->image('mapa_regiones.png', array('alt' => 'open','class'=>'imgedit'));?>
            </a>
        <?php }
        if( $this->request->data['Impuesto']['organismo']=='banco'){ ?>
        <a href="#"  onclick="loadCbus(<?php echo  $this->request->data['Impuesto']['id']; ?>)" class="button_view">
            <?php echo $this->Html->image('cuentabancaria.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
        <?php } ?>
    </td>
<?php 
}else{ ?>
<td colspan="7">
 <?php   echo $this->Form->create('Impcli',array('controller'=>'Impcli','action'=>'edit',"id"=>"ImpcliEditForm".$this->request->data['Impcli']['id']));?>
      <table style="width:637px">
	    <?php echo $this->Form->input('id');?>
		      <tr>
            <?php echo $this->Form->input('cliente_id',array('type'=>'hidden'));?>
  		      <td colspan="2"><?php echo $this->Form->input('impuesto_id');?></td>
            <?php if( $this->request->data['Impuesto']['organismo']=='banco'||$this->request->data['Impuesto']['organismo']=='sindicato'){?>
            <td><?php echo $this->Form->input('usuario'); ?></td>
            <td><?php echo $this->Form->input('clave'); ?></td>
            <?php } ?>
            <?php if( $this->request->data['Impuesto']['id']==4/*Monotributo*/){?>
            <td>
                <?php
                echo $this->Form->input('categoriamonotributo',
                    [
                        'label' => 'Categori&oacute;a Monotributo',
                        'type'=>'select','options'=>$categoriasmonotributos
                    ]);?>
            </td>
            <td >
                <?php
                echo $this->Form->input('monotributoadherentes',
                    [
                        'label' => 'Adherentes',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
            </td>
            <td >
            <?php
                echo $this->Form->input('monotributojubilacion',
                    [
                        'label' => 'paga Jubilacion',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
            </td>
            <td >
                <?php
                echo $this->Form->input('monotributoobrasocial',
                    [
                        'label' => 'paga Obra Soc.',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
            </td>
            <?php } ?>
            <td width="275"><?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacute;n'));?></td>   

            <?php $options  = array(
                    'label' => 'ACEPTAR',
                    'div'   => array('class' => 'btn_clientes_view')
            );?>      
            <td><?php echo $this->Form->end(($options));?></td>
          </tr>
        </table>
<?php }?>
