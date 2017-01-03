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
	<script type="text/javascript">
		
	</script>
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
			$("#loading").css('visibility','hidden')
		});
		$( document ).ajaxError(function( event, request, settings ) {
			callAlertPopint("La sesion ha finalizado. Por favor inicie sesion en otra pestaña y continue.");
		});
		$('#ui-datepicker-div').hide();
	});
	</SCRIPT>
</head>
<body>
	<div id="container">
		<div id="header">
			<!--<h1><?php echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
			<?php if ($this->Session->read('Auth.User.username')) { ?>
			
			<p align="right">
			<?php echo 'Bienvenido '.$this->Session->read('Auth.User.username').'!'; ?>
			<?php echo $this->Html->link("Salir",array(
														'controller' => 'users', 
														'action' => 'logout', 
														)
										); 	?>
			<?php echo $this->Html->image('exit.png',array(
							'alt' => 'open',
							'class' => 'btn_exit',
							'onClick' => "window.location.href='".Router::url(array(
                                                                                    'controller'=>'users', 
                                                                                    'action'=>'logout')
                                                                                    )."'"
                                                )

			);?>

			
			</p>
			<?php } ?>-->
			
			<div id='cssmenu'>
				<ul>
					<!--<li><a>
						<span>
							<?php echo $this->Html->image('SIGESEC.png', array('width' => '120')) ?>
						</span>
						</a>
					</li>	-->			  
				   	<li class='has-sub ' id='liparametros'><a href='#'><span>Parametros</span></a>
				      <ul>
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
				   	<li class=' has-sub' id='liclientes'><a href='#'><span>Clientes</span></a>
				      <ul>
				        <li class='has-sub'>
			         		<?php
								echo $this->Html->link("Clientes",
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
							<li class='has-sub'>
								<?php
								echo $this->Html->link("Plan de cuentas",
									array(
										'controller' => 'cuentasclientes',
										'action' => 'plancuentas',
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
				   	
				   	<li style="width:34%;"> <!--li vacio-->
				   		<span>
				   			&nbsp;
				   		</span>
				   	</li>
				   	<li style="padding: 4px 0"> <!--li logo-->
				   		
				   		<span>
				   		<p style="margin-top:3px">
				   			<?php echo $this->Html->image('sigesec.png', array('width' => '120')) ?>
				   			<?php if ($this->Session->read('Auth.User.username')) { ?>
				   		</p>
			
						<p align="right" style="margin-botton:3px">
						<?php echo 'Bienvenido '.$this->Session->read('Auth.User.username').'!'; ?>
						<!--<?php echo $this->Html->link("Salir",array(
																	'controller' => 'users', 
																	'action' => 'logout', 
																	)
													); 	?>-->
						<?php echo $this->Html->image('exit.png',array(
										'alt' => 'open',
										'class' => 'btn_exit',
										'onClick' => "window.location.href='".Router::url(array(
			                                                                                    'controller'=>'users', 
			                                                                                    'action'=>'logout')
			                                                                                    )."'"
			                                                )

						);?>

						
						</p>
						<?php } ?>
						</span>
						
				   	</li>				 
				</ul>
				</div>
		</div>
		<div id="content">
			
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<!--<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>-->
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
	<!--<?php echo $this->element('sql_dump'); ?>-->
</body>
</html>
