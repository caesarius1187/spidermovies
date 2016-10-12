<script type="text/javascript">
	$(document).ready(function() {
		var iTableHeight = $(window).height();
		iTableHeight = (iTableHeight < 200) ? 200 : (iTableHeight - 320);
		//alert(iTableHeight)
		$("#tblListaUsuarios").dataTable( { 
			"sPaginationType": "full_numbers",
			"sScrollY": iTableHeight + "px",
		    "bScrollCollapse": true,
		    "iDisplayLength":50,
		});	
	});

	function agregarUsuario()
	{
		location.href='#agregar_usuario';
	}
	function editarUsuario(UsuarioId)
	{
		//alert(UsuarioId)		
		var data ="";
	    $.ajax({
	        type: "post",  // Request method: post, get
	        url: serverLayoutURL+"/users/editajax/"+UsuarioId,

	        // URL to request
	        data: data,  // post data
	        success: function(response) {
	         					$("#form_editar_usuario").html(response);
	         					location.href='#editar_usuario';
	         					$("#form_editar_usuario").width("auto");

	                       },	                  
	       error:function (XMLHttpRequest, textStatus, errorThrown) {
	            alert(textStatus);
			 	alert(XMLHttpRequest);
			 	alert(errorThrown);
	       }
	    });
	}
</script>
<div class="index">
	<table>
		<tr>
			<td style="text-align: left;">
				<h2><?php echo __('Usuarios'); ?></h2>
			</td>	
			<td style="text-align: right; cursor:pointer;" title="Agregar Usuario">
			
			<div class="fab blue">
	            <core-icon icon="add" align="center">
	                
	                <?php echo $this->Form->button('+', 
	                                            array('type' => 'button',
	                                                'class' =>"btn_add",
	                                                'onClick' => "agregarUsuario()"
	                                                )
	                        );?> 
	            </core-icon>
	            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
	       	</div>


			</td>
		</tr>
	</table>

	<table id="tblListaUsuarios" cellpadding="0" cellspacing="0" border="0" class="display">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>DNI</th>
				<th>Telefono</th>
				<th>Cel</th>
				<!--<th>Mail</th>-->
				<th>Usuario</th>
				<!--<th>Contraseña</th>-->
				<th>Tipo</th>
				<th>Estado</th>
				<th class="actions" style="text-align:center"><?php echo __('Acciones'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
                <th></th>  
                <th></th>
				<!--<th></th>-->
				<th></th>
                <!--<th></th>-->
                <th></th>
				<th></th>				             	
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($users as $user): ?>
		<tr>
			<td><?php echo h($user['User']['nombre']); ?>&nbsp;</td>
			<!--<td><?php echo h($user['User']['id']); ?>&nbsp;</td>-->
			<td><?php echo h($user['User']['dni']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['telefono']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['cel']); ?>&nbsp;</td>
			<!--<td><?php echo h($user['User']['mail']); ?>&nbsp;</td>-->
			<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
			<!--<td><?php echo h($user['User']['password']); ?>&nbsp;</td>-->
			<td><?php echo h($user['User']['tipo']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['estado']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Form->button('Editar', array('onClick' => 'editarUsuario('.$user["User"]["id"].')' )); ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>		
</div>

<!-- Inicio Popin Agregar Usuario-->
<a href="#x" class="overlay" id="agregar_usuario"></a>
<div class="popup">
        <div id="form_agregar_usuario">

			<?php echo $this->Form->create('User',array('action'=>'add')); ?>
				<h3><?php echo __('Agregar Usuario'); ?></h3>			
				<table style="width:85%;margin-bottom:0px">
				<tr>
					<td><?php echo $this->Form->input('nombre', array('style' => 'width:200px')); ?></td>
					<td><?php echo $this->Form->input('dni', array('style' => 'width:200px')); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Form->input('telefono', array('style' => 'width:200px')); ?></td>
					<td><?php echo $this->Form->input('cel', array('label'=>'Celular','style' => 'width:200px')); ?></td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $this->Form->input('mail', array('style' => 'width:423px', 'label'=>'E-mail')); ?></td>			
				</tr>
				<tr>
					<td>
						<?php echo $this->Form->input('estudio_id',array('type'=>'hidden','default'=>$this->Session->read('Auth.User.estudio_id'))); ?>				
						<?php echo $this->Form->input('username' , array('label'=>'Usuario', 'style' => 'width:200px')); ?>
					</td>
					<td><?php echo $this->Form->input('password', array('style' => 'width:200px')); ?></td>
					
			    </tr>
			    <tr>
			    	<td><?php echo $this->Form->input('tipo', array(
			            'options' => array('administrador' => 'Administrador', 'cliente' => 'Cliente', 'operario' => 'Operario'),
			            'style' => array('width:200px')
			        	)); ?>
			    	</td>
					<td>
						<?php echo $this->Form->input('estado',array(
						'type'=>'select',
						'options'=>array(
							'habilitado'=>'habilitado',
							'deshabilitado'=>'deshabilitado'
						),
						'style' => array('width:200px')
						)); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table style="margin-bottom:0px">
							<tr>
								<td width="300">&nbsp;</td>
								<td><div class="submit"><a href="#close" class="btn_cancelar"style="margin-top:3px;">Cancelar</a></div></td>
								<td><?php echo $this->Form->end(__('Aceptar')); ?></td>
							</tr>
						</table>
					</td>	
				</tr>			
				</table> 
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Agregar Usuario-->
<!-- Inicio Popin Editar Usuario-->
<a href="#x" class="overlay" id="editar_usuario"></a>
	<div class="popup">
    
        <div id="form_editar_usuario">
           
        </div>
  
    	<a class="close" href="#close"></a>
	</div>
<!-- Fin Popin Editar Usuario-->

