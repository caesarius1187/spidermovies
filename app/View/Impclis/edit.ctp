<?php
if(!$showTheForm){?>
    <td><?php echo $this->request->data['Impuesto']['nombre']; 
        if( $this->request->data['Impuesto']['id']==4/*Monotributo*/){ 
          echo $this->request->data['Impcli']['categoriamonotributo']; 
        }
        if( $this->request->data['Impuesto']['id']==14/*Autonomo*/){
          echo $this->request->data['Autonomocategoria']['codigo'];
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
            if($this->request->data['Impuesto']['id']==5/*Ganancias Sociedades*/||$this->request->data['Impuesto']['id']==160/*Ganancias Personas Físicas*/){?>
            <a href="#"  onclick="loadFormImpuestoCuentasganancias(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view">
                <?php echo $this->Html->image('cuentas.png', array('alt' => 'open','class'=>'imgedit'));?>
            </a>
        <?php }
        if( $this->request->data['Impuesto']['organismo']=='banco'){ ?>
        <a href="#"  onclick="loadCbus(<?php echo $this->request->data['Impcli']['id']; ?>)" class="button_view">
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
            <?php }
            if( $this->request->data['Impuesto']['id']==10/*SUSS*/){?>
                <td>
                <?php
                echo $this->Form->input('alicuotaart',
                    [
                        'label' => 'Alicuota ART',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
                <td>
                <?php
                echo $this->Form->input('fijoart',
                    [
                        'label' => 'Cuota Fija LRT',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
                <td>
                <?php
                echo $this->Form->input('segurodevida',
                    [
                        'label' => 'Seguro de vida',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
                <td>
                <?php
                echo $this->Form->input('padron',
                    [
                        'label' => 'Padron',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
            <?php }
            if( $this->request->data['Impuesto']['id']==11/*SEC*/){?>
                <td>
                <?php
                echo $this->Form->input('segurovidaobligatorio',
                    [
                        'label' => 'Contrato Seguro de Vida obligatorio',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
                <td>
                <?php
                echo $this->Form->input('primasvo',
                    [
                        'label' => 'Prima Seg. Vida Oblig. por empleado',
                        'div'=>['style'=>"width: 200px;"]
                    ]);?>
                </td>
            <?php }
            if( $this->request->data['Impuesto']['id']==4/*Monotributo*/){?>
                <td>
                    <?php
                    echo $this->Form->input('categoriamonotributo',
                        [
                            'label' => 'Categor&iacute;a Monotributo',
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
            <?php }
            if( $this->request->data['Impuesto']['id']==14/*Autonomo*/){?>
                <td>
                    <?php
                    echo $this->Form->input('autonomocategoria_id',
                        [
                            'label' => 'Categor&iacute;a Aut&oacute;nomo',
                            'type'=>'select',
                            'options'=>$autonomocategorias
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
