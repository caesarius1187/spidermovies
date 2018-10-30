<?php
echo $this->Html->script('notifications/index',array('inline'=>false));
?>
<div class="notifications index">
	<h2><?php echo __('Notificaciones'); ?></h2>
	<table cellpadding="0" cellspacing="0" id="tblNotifications">
	<thead>
	<tr>
            <th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
            <th><?php echo $this->Paginator->sort('texto'); ?></th>
            <th><?php echo $this->Paginator->sort('periodo'); ?></th>
            <th><?php echo $this->Paginator->sort('fecha'); ?></th>
            <th><?php echo $this->Paginator->sort('tipo'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($notifications as $notification): 
            $pemes = substr($notification['Notification']['periodo'],0,2);
            $peano = substr($notification['Notification']['periodo'],3);
        $trClass = $notification['Notification']['readed']?'notificationReaded':'';
            ?>
	<tr id="trNotification<?php echo $notification['Notification']['id']?>" class="<?php echo $trClass?>" impid="0"  notid="<?php echo $notification['Notification']['id']?>" cliid="<?php echo $notification['Cliente']['id']?>" action="<?php echo $notification['Notification']['action']?>" periodoMes="<?php echo $pemes?>" periodoAno="<?php echo $peano?>">
		<td><?php echo h($notification['Cliente']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($notification['Notification']['texto']); ?>&nbsp;</td>
		<td><?php echo h($notification['Notification']['periodo']); ?>&nbsp;</td>
                <td><?php echo date('d-m-Y', strtotime($notification['Notification']['fecha'])); ?>&nbsp;</td>		
                <td><?php echo $notification['Notification']['action']; ?>&nbsp;</td>		
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div id="Formhead" class="" style="">

  <!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
  <?php 
  echo $this->Form->create('clientes',array(
                'action' => 'avance',
                'target' => '_blank',
      )); ?> 
        <?php
        echo $this->Form->input('periodomes', array(
            'type' => 'hidden',
        ));
        ?>
        <?php echo $this->Form->input('periodoanio', array(
                'type' => 'hidden',
            )
        );
        echo $this->Form->input('gclis', array(
          //'multiple' => 'multiple',
            'type' => 'hidden',
        ));
          echo $this->Form->input('lclis', array(
            //'multiple' => 'multiple',
              'type' => 'hidden',
          ));
        echo $this->Form->input('filtrodeimpuestos', array(
          //'multiple' => 'multiple',
          'type' => 'hidden',          
        )); ?>

        <?php echo $this->Form->input('selectby',array('default'=>'none','type'=>'hidden'));// ?>
        <?php echo $this->Form->end(); ?>

</div> <!--End Clietenes_avance-->
