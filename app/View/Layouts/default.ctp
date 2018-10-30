<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 */
$cakeDescription = __d('spidermovies.net', 'SpiderMovies');
?>
<!DOCTYPE html>
<html>
<head>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-5940627790406647",
        enable_page_level_ads: true
      });
    </script>
        <!-- Etiqueta global de sitio (gtag.js) de Google Analytics -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-115639849-1"></script>
    <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-115639849-1');
    </script>
	<meta name="theme-color" content="#1e88e5">
        <meta name="robots" content="index, follow">
        <meta name="author" content="SpiderMovies">
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<!-- <script src="//code.jquery.com/jquery-1.12.0.min.js"></script> -->
	<?php
		/*echo $this->Html->meta(
			    'icon',
			    '/img/logosolo.png',
			    array('type' => 'icon')
			);*/
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('jquery.dataTables.js');
		echo $this->Html->script('floatHead/dist/jquery.floatThead');
		echo $this->Html->script('menu');
		echo $this->Html->script('chosen.jquery');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('datatables.min');
		echo $this->Html->css('demo_table');
		echo $this->Html->css('md_buttons');
		echo $this->Html->css('popin');
		echo $this->Html->css('chosen');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<SCRIPT TYPE="text/javascript">
	   var serverLayoutURL = "/spidermovies";       
		function callAlertPopint(message){
			//todo callAlertPopint
			//deberiamos cambiar la forma en la que mostramos esto por que te mueve el scroll
			//cuando muestra el alerta
			location.hash ="#PopupMessage";
			jQuery(document).ready(function($) {
                            $(lblMessagePP).html(message);
			});	
			/*setTimeout(function(){
			  if(location.hash === "#PopupMessage")
			    {
			  		location.hash ="#x";
			    }
			}, 3000); */
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
			callAlertPopint("La sesion ha finalizado. Por favor inicie sesion en otra pesta&ncaron;a y continue.");
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
        <div style="float:left;">
            <?php echo $this->Html->image('Spider-Movies-Logo-Blanco-Grande.png', array('style' => 'width: 148px;height:26px;margin-top: 6px;')) ?>
        </div>
		<div id="header">            
            <div id='cssmenu' style="text-align: center">                

                <ul class="nav">                            
                    <li class='has-sub ' id='liparametros'><a href='#'><span>Configuracion</span></a>
                        <ul>
                            <li class=''>
                                <?php
                                echo $this->Html->link("Mi cuenta",
                                    array(
                                        'controller' => 'users',
                                        'action' => 'view',
                                        $this->Session->read('Auth.User.estudio_id')
                                    )
                                );  
                                ?>  
                            </li>
                            <li class=''>
                                <?php
                                echo $this->Html->link("Terminos y Condiciones",
                                        array(
                                            'controller' => 'users', 
                                            'action' => 'terminosycondiciones', 
                                        )
                                );  
                              ?>        
                            </li>                                      
                        </ul>    
                    </li>
                    <li class='has-sub ' id='liparametros'><a href='#'><span>Peliculas</span></a>
                        <ul><?php
                            if ($this->Session->read('Auth.User.tipo') === 'administrador') { ?>
                              <li class=''>
                                  <?php
                                  echo $this->Html->link("Agregar Pelicula",
                                      array(
                                          'plugin' => null,
                                          'controller' => 'peliculas',
                                          'action' => 'add',
                                          )
                                      );
                                  ?>
                              </li>
                        <?php } ?>  
                            <li class=''>
                                <?php
                                    echo $this->Html->link(__('Novedades'), array(
                                        'admin' => false, 'plugin' => null, 'controller' => 'peliculas', 'action' => 'lista','novedades'));
                                ?>
                            </li>
                            <li class=''>
                                <?php
                                    echo $this->Form->create('Pelicula',array('action'=>'lista', 'plugin' => null,));
                                    echo $this->Form->input('genero',[
                                        'value'=>"1",
                                        'type'=>"text",
                                        'label'=>false,
                                        'style'=>'display:none'
                                    ]);
                                    echo $this->Form->submit('Accion',[
                                        'class'=>'submitlist',
                                        'div'=>[
                                            'class'=>'divsubmitlist'
                                        ]
                                    ]);
                                    echo $this->Form->end();                                   
                                ?>
                            </li>
                            <li class=''>
                                <?php
                                    echo $this->Form->create('Pelicula',array('action'=>'lista', 'plugin' => null,));
                                    echo $this->Form->input('genero',[
                                        'value'=>"2",
                                        'type'=>"text",
                                        'label'=>false,
                                        'style'=>'display:none'
                                    ]);
                                    echo $this->Form->submit('Romance',[
                                        'class'=>'submitlist',
                                        'div'=>[
                                            'class'=>'divsubmitlist'
                                        ]
                                    ]);
                                    echo $this->Form->end();                                   
                                ?>
                            </li>
                            <li class=''>
                                <?php
                                    echo $this->Form->create('Pelicula',array('action'=>'lista', 'plugin' => null,));
                                    echo $this->Form->input('genero',[
                                        'value'=>"3",
                                        'type'=>"text",
                                        'label'=>false,
                                        'style'=>'display:none'
                                    ]);
                                    echo $this->Form->submit('Comedia',[
                                        'class'=>'submitlist',
                                        'div'=>[
                                            'class'=>'divsubmitlist'
                                        ]
                                    ]);
                                    echo $this->Form->end();                                   
                                ?>
                            </li>
                            <li class=''>
                                <?php
                                    echo $this->Form->create('Pelicula',array('action'=>'lista', 'plugin' => null,));
                                    echo $this->Form->input('genero',[
                                        'value'=>"4",
                                        'type'=>"text",
                                        'label'=>false,
                                        'style'=>'display:none'
                                    ]);
                                    echo $this->Form->submit('Terror',[
                                        'class'=>'submitlist',
                                        'div'=>[
                                            'class'=>'divsubmitlist'
                                        ]
                                    ]);
                                    echo $this->Form->end();                                   
                                ?>
                            </li>                            
                        </ul>    
                    </li>
                    <?php
                    if ($this->Session->read('Auth.User.tipo') === 'administrador') { ?>
                          <li class=''>
                              <?php
                              echo $this->Html->link("Usuarios",
                                  array(
                                      'controller' => 'users',
                                      'action' => 'index',
                                      )
                                  );
                              ?>
                          </li>
                    <?php } ?>                                                                        
                    <li>
                        <?php
                            echo $this->Html->link(__('Carro') . ' (' . $this->Cart->count() . ')', array(
                                'admin' => false, 'plugin' => 'cart', 'controller' => 'carts', 'action' => 'view'));
                        ?>
                    </li>
                    <?php            
                       if ($this->Session->read('Auth.User.tipo') === 'administrador') { ?>
                        <li>
                            <?php
                                echo $this->Html->link(__('Orders'), array(
                                    'admin' => true, 'plugin' => 'cart', 'controller' => 'orders', 'action' => 'index'));
                            ?>
                        </li>
                        <li>
                            <?php
                                echo $this->Html->link(__('Carts'), array(
                                    'admin' => true, 'plugin' => 'cart', 'controller' => 'carts', 'action' => 'index'));
                            ?>
                        </li>
                    <?php } ?>                     
                    <?php if ($this->Session->read('Auth.User.username')) { 

                        if($cantNotifications>0){
                            echo $cantNotifications;
                        }                                                
                        $bellicon = $this->Html->image('bell_icon.png',array(
                                'alt' => 'notificaciones',
                                'class' => 'btn_exit',
                                'title' => "Tienes ".$cantNotifications." notificaciones sin leer",
                                'style' => 'width:20px;height:20px;',
                            )

                        );                                  
                        ?>
                        <li>
                            <?php
                            echo $this->Html->link($bellicon, array('plugin'=>null,'controller'=>'notifications','action' => 'index'), array('escape' => false)); 
                            ?>                            
                        </li>
                        <li>
                            <?php
                            $logoutIcon = $this->Html->image('exit.png',array(
                                            'alt' => 'logput',
                                            'class' => 'btn_exit',                                        
                                                    )
                                    );
                            echo $this->Html->link('Bienvenido '.$this->Session->read('Auth.User.username').'!'.$logoutIcon, array('plugin'=>null,'controller'=>'users','action' => 'logout'), array('escape' => false)); 
                            ?>                            
                        </li>
                        <?php } else{
                                 ?>
                               <li><?php
                                 echo $this->Html->link("Iniciar Sesion",
                                    array(
                                        'plugin' => null,
                                        'controller' => 'users',
                                        'action' => 'login',                        
                                    ),
                                    [
                                        'class'=>'nav-link js-scroll-trigger'
                                    ]
                                );  
                                  ?></li><?php
                            } ?>
                        
                </ul>
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
