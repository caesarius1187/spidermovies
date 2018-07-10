    <?php
        echo $this->Html->css('bootstrap/bootstrap.min.css');
        echo $this->Html->css('scrolling-nav.css');
        echo $this->Html->css('bootstrap/bootstrapmodal');
        echo $this->Html->script('home/jquery/jquery.min.js',array('inline'=>false));
        echo $this->Html->script('home/bootstrap/bootstrap.bundle.min.js',array('inline'=>false));
        echo $this->Html->script('home/jquery-easing/jquery.easing.min.js',array('inline'=>false));
        ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <div class="container">
        <!--
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Start Bootstrap</a>
        -->
        <img src="img/logosolo.png" width="68" height="50">

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
               <?php
                echo $this->Html->link("Iniciar Sesion",
                    array(
                        'controller' => 'users',
                        'action' => 'login',                        
                    ),
                    [
                        'class'=>'nav-link js-scroll-trigger'
                    ]
                ); 	
                ?>	
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="bg-primary text-white">
      <div class="container text-center">
        
        <h1>Bienvenidos CONTA</h1>
        <p class="lead">Sistema de Gestion de Estudios Contables</p>
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
            <p class="lead">Conta es un sistema WEB que satisface todas las necesidades en la vida de un Estudio Contable.</p>
            <ul>
              <li>
                Sin inversion inicial, actualizaciones ni instalacion de software, ofrece inmediata implementación y disponibilidad los 365 dias del año.
              </li>
              <li>
                Propone pasos sucesivos para la organización y liquidación de impuestos, sueldos y contabilidad. Generando asientos automáticos e informes para enviar al contribuyente.
              </li>              
            </ul>

            <p class="lead">Permitir a los Estudios Contables, el control en tiempo real del proceso.</p>
            <ul>
              <li>
                Las fechas de vencimiento de los impuestos, convenios colectivos de trabajo (fórmulas y escalas salariales) estan pre cargadas y son actualizadas por CONTA
              </li>              
            </ul>

            <p class="lead">Todos los archivos son generados en la web por lo cual no necesita resguardar la información.</p>
            <ul>
              <li>
                Tambien cuenta con soporte en tiempo real y backup automáticos diarios.
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
            <p class="lead">Conta integra sueldos, impuestos y contabilidad en un solo 
                proceso.</p>                        
          </div>
          <div class="col-lg-8 mx-auto">
            <p class="lead">Importando Ventas.</p>
            <div class="video">
              <iframe src="https://drive.google.com/file/d/17CHpfeaX0C-jJj3mkavsZ4QKgQsX9WL8/preview" width="640" height="480"></iframe>
            </div>
            <p class="lead">Importando Compras.</p>
            <div class="video">
              <iframe src="https://drive.google.com/file/d/1Z-g8QI4DVxtTjeL_P1E6Lp2TggJzGH2t/preview" width="640" height="480"></iframe>
            </div>
            <p class="lead">Liquidando IVA.</p>
            <div class="video">
              <iframe src="https://drive.google.com/file/d/1cQqpdF-Gd6SCGTSoPtiGKToQzvfY_IyO/preview" width="640" height="480"></iframe>
            </div>
            <p class="lead">Generando Balances con CONTA.</p>
            <div class="video">
              <iframe src="https://drive.google.com/file/d/1TUvuHA13psciK6HLT7VVFKMETDjsW5am/preview" width="640" height="480"></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Contactanos completando el formulario de la derecha</h2>
            <p class="lead">Estamos ansiosos por conocelos y darnos a conocer. No dude en 
            enviarnos un mensaje, nos comunicaremos con usted a la brevedad</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Conta S.R.L. 2018</p>
      </div>
      <!-- /.container -->
    </footer>

   
