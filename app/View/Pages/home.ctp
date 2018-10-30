    <?php
    echo $this->Html->css('bootstrap/bootstrap.min.css');
    echo $this->Html->css('scrolling-nav.css');
    echo $this->Html->css('bootstrap/bootstrapmodal');
    echo $this->Html->script('home/jquery/jquery.min.js',array('inline'=>false));
    echo $this->Html->script('home/bootstrap/bootstrap.bundle.min.js',array('inline'=>false));
    echo $this->Html->script('home/jquery-easing/jquery.easing.min.js',array('inline'=>false));
    echo $this->Html->script('home/scrolling-nav.js',array('inline'=>false));
    ?>  
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top hideOnTop" id="mainNav" >
      <div class="container">
        <!--
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Start Bootstrap</a>
        -->
        <img src="../img/logosolo.png" width="68" height="50" class="" style="/*display: none*/">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">Nosotros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#services">Funcionalidades</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contact">Contactanos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#pricing">Precios</a>
            </li>
            <li class="nav-item">
              <?php 
              echo $this->Html->link("Preguntas Frecuentes",
                    array(
                        'controller' => 'users',
                        'action' => 'preguntas',                        
                    ),
                    [
                        'class'=>'nav-link'
                    ]
                );?> 	
            </li>
            <li class="nav-item">
               <?php if ($this->Session->read('Auth.User.username')) { 

                    if($cantNotifications>0){
                        echo $cantNotifications." ";
                    }                                                
                    echo $this->Html->image('cli_view.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                            'style' => 'width:20px;height:20px;margin-top: 5px;',
                            'onClick' => "window.location.href='".Router::url(array(
                                                    'controller'=>'clientes',
                                                    'action'=>'view')
                                    )."'"
                        )

                    );
                    //echo ' Bienvenido '.$this->Session->read('Auth.User.username').'!'; 
                    echo $this->Html->image('exit.png',array(
                                    'alt' => 'open',
                                    'class' => 'btn_exit',
                                    'onClick' => "window.location.href='".Router::url(array(
                                                            'controller'=>'users',
                                                            'action'=>'logout')
                                            )."'"
                            )

                    );?>
            <?php } else{
                 echo $this->Html->link("Iniciar Sesion",
                    array(
                        'controller' => 'users',
                        'action' => 'login',                        
                    ),
                    [
                        'class'=>'nav-link js-scroll-trigger'
                    ]
                ); 	
            } ?>                               
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="bg-primary text-white">
      <div class="container text-center">
        
        <h1>Bienvenidos a CONTA</h1>
        <p class="lead">Sistema de Gesti&oacute;n de Estudios Contables</p>
        <img src="img/logosolo.png" style="width:27.32vw;height:20vw">        

      </div>
    </header>
    <section id="about">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <!--
            <h2>About this page</h2>
            <p class="lead">This is a great place to talk about your webpage. This template is purposefully unstyled so you can use it as a boilerplate or starting point for you own landing page designs! This template features:</p>
            <ul>
              <li>Clickable nav links that smooth scroll to page sections</li>
              <li>Responsive behavior when clicking nav links perfect for a one page website</li>
              <li>Bootstrap's scrollspy feature which highlights which section of the page you're on in the navbar</li>
              <li>Minimal custom CSS so you are free to explore your own unique design options</li>
            </ul>
            -->
            
            
            <h2>CONTA</h2>
            <p class="lead">CONTA es un Sistema de Gesti&oacute;n de estudios contables
                online, que integra, en un mismo proceso, liquidación de sueldos
                , impuestos y contabilidad.</p>
            <ul>
              <li>
                  Sin inversi&oacute;n inicial, actualizaciones ni instalaci&oacute;n de 
                software, ofrece inmediata implementación y disponibilidad los 
                365 d&iacute;as del año.
              </li>
              <li>
                Propone pasos sucesivos para la organización y liquidación de 
                impuestos, sueldos y contabilidad. Generando asientos 
                automáticos e informes para enviar al contribuyente.
              </li>              
              <li>
                Cuenta con papeles de trabajos de IVA, Act. Economicas, Conv. 
                Multilateral, Act. Varias, Autonomo, Ganancias PF, Bienes 
                Personales y genera estados contables como EERR, ESP, EFE, EEPN.
              </li>              
            </ul>

            <p class="lead">Permitir a los Estudios Contables, el control en tiempo real del proceso.</p>
            <ul>
                <li>
                    Las fechas de vencimiento de los impuestos son notificadas, 
                    al igual que las exclusiones del monotributo por parte de AFIP 
                    de los contribuyentes cargados en el sistema.
                </li>          
                <li>
                    Convenios colectivos de trabajo (fórmulas de los conceptos 
                    y escalas salariales) est&aacute;n pre cargadas y son actualizadas 
                    autom&aacute;ticamente por CONTA.
                </li>
            </ul>

            <p class="lead">Todos los archivos son generados en la web por lo 
                cual no necesita resguardar la información.</p>
            <ul>
              <li>
                Tambi&eacute;n cuenta con soporte en tiempo real y backup 
                autom&aacute;ticos diarios.
              </li>              
            </ul>
            
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Modulos del sistema</h2>
            <p class="lead">Conta integra sueldos, impuestos y contabilidad en 
                un solo proceso.</p>                        
          </div>
          <div class="col-lg-8 mx-auto">
            <div class="contenedorvideo" style="">
                <p class="lead">Importando Ventas.</p>
                <div class="video" style="width: fit-content">
                    <iframe src="https://drive.google.com/file/d/17CHpfeaX0C-jJj3mkavsZ4QKgQsX9WL8/preview" style="width: 100%;"></iframe>
                </div>
            </div>
            <div class="contenedorvideo" style="">
                <p class="lead">Importando Compras.</p>
                <div class="video" style="width: fit-content">
                  <iframe src="https://drive.google.com/file/d/1Z-g8QI4DVxtTjeL_P1E6Lp2TggJzGH2t/preview" style="width: 100%;"></iframe>
                </div>
            </div>
            <div class="contenedorvideo" style="">
                <p class="lead">Liquidando IVA.</p>
                <div class="video" style="width: fit-content">
                  <iframe src="https://drive.google.com/file/d/1cQqpdF-Gd6SCGTSoPtiGKToQzvfY_IyO/preview" style="width: 100%;"></iframe>
                </div>
            </div>
            <div class="contenedorvideo" style="">
                <p class="lead">Generando Balances con CONTA.</p>
                <div class="video" style="width: fit-content">
                  <iframe src="https://drive.google.com/file/d/1TUvuHA13psciK6HLT7VVFKMETDjsW5am/preview" style="width: 100%;"></iframe>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>-Cont&aacute;ctanos completando el formulario de la derecha</h2>
            <h2>-Escribe a soporte@conta.com.ar</h2>
            <h2>-Llama o env&iacute;a Whatsapp a <a href="tel:387-5512481">387-5512481
                </a></h2>
            <p class="lead">Estamos ansiosos por conocerlos y darnos a conocer. 
                No dude en enviarnos un mensaje, nos comunicaremos con usted a 
                la brevedad</p>
          </div>
        </div>
      </div>
    </section>
    
    <section id="pricing">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Planes de CONTA!</h2>
            <img src="img/planesconta.png">
            <p class="lead">Contamos con planes para el tamaño de todos los 
                estudios. </p>   
            <p class="lead">No se pierda esta oportunidad! Consulte por planes 
                superiores! </p>   
            <a href="tel:387-5512481">LL&aacute;menos!</a>


          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Conta S.R.L. 
            2018</p>
      </div>
      <!-- /.container -->
    </footer>

   
