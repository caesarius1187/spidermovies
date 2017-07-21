<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */
$cakeDescription = __d('conta.com.ar', 'Conta');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<!-- <script src="//code.jquery.com/jquery-1.12.0.min.js"></script> -->
	<?php
		echo $this->Html->meta(
			    'icon',
			    '/img/sigesec.png',
			    array('type' => 'icon')
			);
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery.dataTables');
		echo $this->Html->script('floatHead/dist/jquery.floatThead');
		echo $this->Html->script('menu');
		echo $this->Html->script('chosen.jquery');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('demo_table');
		echo $this->Html->css('md_buttons');
		echo $this->Html->css('popin');
		echo $this->Html->css('chosen');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<SCRIPT TYPE="text/javascript">
	var serverLayoutURL = "/contasynfotech";
		function callAlertPopint(message){
			//todo callAlertPopint
			//deberiamos cambiar la forma en la que mostramos esto por que te mueve el scroll
			//cuando muestra el alerta
			location.hash ="#PopupMessage";
			jQuery(document).ready(function($) {
				$(lblMessagePP).html(message);
			});	
			setTimeout(function(){
			  if(location.hash === "#PopupMessage")
			    {
			  		location.hash ="#x";
			    }
			}, 3000); 
		}	
	jQuery(document).ready(function($) {
		$(document).load(function () {
			$("#loading").css('visibility','visible')
		});
		$(document).ready(function () {
			$("#loading").css('visibility','hidden')
		});
		$(document).ajaxStart(function () {
			$("#loading").css('visibility','visible')
		});
		$(document).ajaxComplete(function () {
			checkPendingRequest();
		});
		$( document ).ajaxError(function( event, request, settings ) {
			callAlertPopint("La sesion ha finalizado. Por favor inicie sesion en otra pestaña y continue.");
		});
		$('#ui-datepicker-div').hide();
		function checkPendingRequest() {
			if ($.active > 0) {
				window.setTimeout(checkPendingRequest, 1000);
				//Mostrar peticiones pendientes ejemplo: $("#control").val("Peticiones pendientes" + $.active);
			}
			else {
				$("#loading").css('visibility','hidden')
			}
		};
	});
	</SCRIPT>
</head>
<body>
	<div id="container">
		<div id="header">
			<div id='cssmenu' style="text-align: center">
				<div style="float:left;">
					<?php echo $this->Html->image('logoBig.png', array('style' => 'width: 266px;')) ?>
				</div>
				<ul style="float:left;" >
					<!--<li><a>
						<span>
							<?php echo $this->Html->image('SIGESEC.png', array('width' => '120')) ?>
						</span>
						</a>
					</li>	-->			  
				   	<li class='has-sub ' id='liparametros'><a href='#'><span>Configuracion</span></a>
				      <ul>
					    <li class='has-sub'>
							<?php
								echo $this->Html->link("Mi cuenta",
																array(
																	'controller' => 'estudios',
																	'action' => 'view',
																	$this->Session->read('Auth.User.estudio_id')
																	)
													); 	
							?>	
					    </li>
					  	<li class='has-sub'>
							<?php
								echo $this->Html->link("Usuarios",
																array(
																	'controller' => 'users',
																	'action' => 'index',
																	)
													);
							?>
					    </li>
						<li class='has-sub'>
				         	<?php
								echo $this->Html->link("Tareas",
																array(
																	'controller' => 'tareasxclientesxestudios', 
																	'action' => 'index', 
																	)
													); 	
							?>		
				        </li>
				      </ul>
				   	</li>			   
				   	<li class=' has-sub' id='liclientes'><a href='#'><span>Contribuyentes</span></a>
				      <ul>
				        <li class='has-sub'>
			         		<?php
								echo $this->Html->link("Contribuyentes",
																array(
																	'controller' => 'clientes', 
																	'action' => 'index', 
																	)
													); 	
							?>	
				         </li>
			         	<li class='has-sub'>		
				         	<?php
								echo $this->Html->link("Grupos",
																array(
																	'controller' => 'grupoclientes', 
																	'action' => 'index', 
																	)
													); 	
							?>	
						</li>	
				      </ul>
				   	</li>
					<li class='has-sub' id='liinformes'>
						<?php
								echo $this->Html->link("Informes",
																array(
																	'controller' => 'clientes',
																	'action' => 'index', 
																	)
													); 	
							?>	
						<ul>							
							<li class='has-sub'>
								<?php
								echo $this->Html->link("Tributario Financiero",
																array(
																	'controller' => 'clientes', 
																	'action' => 'informefinancierotributario', 
																	)
													); 	
								?>
							</li>	 
							<li class='has-sub'>
								<?php
								echo $this->Html->link("Pagos del Mes",
																array(
																	'controller' => 'clientes', 
																	'action' => 'pagosdelmes', 
																	)
													); 	
								?>
							</li>	 
							<li class='has-sub'>
								<?php
								echo $this->Html->link("Comparativo",
																array(
																	'controller' => 'clientes', 
																	'action' => 'comparativo', 
																	)
													); 	
								?>
							</li>
						</ul>
					</li>
					<li class=' has-sub' id='ligestion'><a href='#'><span>Gestion</span></a>
				      	<ul>
					    	<li class='has-sub'>
								<?php
									echo $this->Html->link("Mi Estudio",
																	array(
																		'controller' => 'clientes', 
																		'action' => 'avance', 
																		)
														); 	
								?>	
							</li>
							<li class='has-sub'>
								<?php
									echo $this->Html->link("Planes de Pago",
																	array(
																		'controller' => 'plandepagos', 
																		'action' => 'index', 
																		)
														); 	
								?>	
							</li>	       						
				      	</ul>
				   	</li>

				</ul>
				<div style="float:right;">
					<?php if ($this->Session->read('Auth.User.username')) { ?>
						<?php echo 'Bienvenido '.$this->Session->read('Auth.User.username').'!'; ?>
						<?php echo $this->Html->image('exit.png',array(
								'alt' => 'open',
								'class' => 'btn_exit',
								'onClick' => "window.location.href='".Router::url(array(
											'controller'=>'users',
											'action'=>'logout')
									)."'"
							)

						);?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div id="content">
			
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>

		<div id="footer">
<!--			<div style="/*position:fixed;*/ top:92%; right:0; z-index:9999;">-->
<!--				<a href="http://www.cakephp.org/">-->
<!--					<img src="http://www.cakephp.org/img/flags/Baked-with-CakePHP.png" width="100px;">-->
<!--				</a>-->
<!--			</div>-->
		</div>

	</div>
	<a href="#x" class="overlay" id="PopupLoading"></a>
		<div class="popupNews" id="loading">
        	<div id=""><?php echo $this->Html->image('progress.gif',array('style'=>'width:60px;')); ?></div>
        <div id="result"></div>
    </div>
    <a href="#x" class="overlay" id="PopupMessage"></a>
		<div class="popup" id="alertMessage" style="padding:0px">
        	<label id="lblMessagePP" style="margin:20px">this is a message</label>
        <div id="result"></div>
    </div>
</div>
	<!--<?php //echo $this->element('sql_dump'); ?>-->
</body>
</html>
