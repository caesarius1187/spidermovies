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
              <a class="nav-link js-scroll-trigger" href="#contribuyentes">Contribuyentes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#preguntas">Sueldos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#impuestos">Impuestos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contabilidad">Contabilidad</a>
            </li>
            <li class="nav-item" style="color:rgba(255, 255, 255, 0.5);">
               <?php if ($this->Session->read('Auth.User.username')) { 

                    if($cantNotifications>0){
                        echo $cantNotifications." ";
                    }                                                
                    echo $this->Html->image('cli_view.png',array(
                            'alt' => 'open',
                            'class' => 'nav-link js-scroll-trigger btn_exit',
                            'title' => 'Contribuyentes',
                            'style' => 'width:20px;height:20px;margin-top: 5px;padding:0px;    display: -webkit-inline-box;',
                            'onClick' => "window.location.href='".Router::url(array(
                                                    'controller'=>'clientes',
                                                    'action'=>'view')
                                    )."'"
                        )

                    );
                    //echo ' Bienvenido '.$this->Session->read('Auth.User.username').'!'; 
                    echo $this->Html->image('exit.png',array(
                                    'alt' => 'open',
                                    'class' => 'nav-link js-scroll-trigger btn_exit',
                                    'style' => 'display: -webkit-inline-box;',
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
    
    <section id="contribuyentes">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Contribuyentes</h2>
            <div id="primercliente">
                <h3>¿Como creo un contribuyente?</h3>
                <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; border-bottom: 2px solid #ebebeb; min-width: 100%" src="https://www.iorad.com/player/137385/Creando-mi-primer-contribuyente-en-Conta?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p>
                <p style="display: none;">
                  <p style="display: none;"><b>Step 1</b>: Primero vamos a entrar a la opcion del menu "Contribuyentes/Grupos" para crear el grupo al que va a pertenecer el contribuyente. Todos los contribuyentes deben pertenecer a un grupo</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=0" alt="Step 1 image" /></p>

                  <p style="display: none;"><b>Step 2</b>: con el boton que sale a la derecha podremos ver el formulario para agregar un grupo nuevo.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=1" alt="Step 2 image" /></p>

                  <p style="display: none;"><b>Step 3</b>: Cargamos los datos del Grupo</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=2" alt="Step 3 image" /></p>

                  <p style="display: none;"><b>Step 4</b>: y hacemos click en el boton Guardar(Se debe agregar una breve descripcion)</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=3" alt="Step 4 image" /></p>

                  <p style="display: none;"><b>Step 5</b>: nos mostrara un mensaje de que se guardo correctamente el grupo y luego debemos ir a la lista de contribuyentes<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=4" alt="Step 5 image" /></p>

                  <p style="display: none;"><b>Step 6</b>: Ahora vamos a agregar un contribuyente al grupo que recién creamos, hacemos click en el boton "+" de la derecha</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=5" alt="Step 6 image" /></p>

                  <p style="display: none;"><b>Step 7</b>: Seleccionamos el grupo de contribuyentes que creamos<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=6" alt="Step 7 image" /></p>

                  <p style="display: none;"><b>Step 8</b>: El tipo de persona que queremos crear<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=7" alt="Step 8 image" /></p>

                  <p style="display: none;"><b>Step 9</b>: cargamos el Apellido y nombre o razón social</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=8" alt="Step 9 image" /></p>

                  <p style="display: none;"><b>Step 10</b>: cargamos el CUIT del contribuyente sin guiones ni espacios</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=9" alt="Step 10 image" /></p>

                  <p style="display: none;"><b>Step 11</b>: el DNI si fuese una persona fisica</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=10" alt="Step 11 image" /></p>

                  <p style="display: none;"><b>Step 12</b>: Fecha de Nac. o Constitución.<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=11" alt="Step 12 image" /></p>

                  <p style="display: none;"><b>Step 13</b>: Cargamos Fecha de Corte de Ejercicio Fiscal. Esta fecha sera del formato "dia-Mes" y determinará el inicio y final de los ejercicios que se utilizarán en contabilidad.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=12" alt="Step 13 image" /></p>

                  <p style="display: none;"><b>Step 14</b>: Cargamos la fecha en la que el cliente entro al estudio, básicamente esto nos permitirá seleccionar el cliente en los distintos informes del sistema, si se necesita trabajar periodos previos, estos deben ser incluidos en esta fecha.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=13" alt="Step 14 image" /></p>

                  <p style="display: none;"><b>Step 15</b>: por ultimo hacemos Click en el botón&nbsp;<span class="component"><i><b>Aceptar</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=14" alt="Step 15 image" /></p>

                  <p style="display: none;"><b>Step 16</b>: nos redirige a la lista de contribuyentes y podemos ver y seleccionar el recién cargado</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=15" alt="Step 16 image" /></p>

                  <p style="display: none;"><b>Step 17</b>: Desde donde podremos ver y cargar la información restante del contribuyente.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137385&step_index=16" alt="Step 17 image" /></p>
                </p>


            </div>
          </div>
        </div>
      </div>
    </section>
    
    <section id="preguntas">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Sueldos</h2>
            <div>
                <h3>¿Como cambio el valor de un concepto de liquidación?</h3>
                <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;">
                    <iframe style="border: 0 none; border-bottom: 2px solid #ebebeb; min-width: 100%" src="https://www.iorad.com/player/137195/Como-cambio-el-valor-de-un-concepto-de-liquidaci-n-en-CONTA-?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe>
                </p>
                <p style="display: none;">
                  <p style="display: none;"><b>Step 1</b>: Primero entramos en la pagina de sueldos desde el informe Gestion/Mi Estudio ,para el periodo seleccionado.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=0" alt="Step 1 image" /></p>

                  <p style="display: none;"><b>Step 2</b>: Luego elegimos el tipo de liquidacion que vamos a modificar</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=1" alt="Step 2 image" /></p>

                  <p style="display: none;"><b>Step 3</b>: <font color="#1f8ceb">Por ejemplo liquidacion Mensual</font></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=2" alt="Step 3 image" /></p>

                  <p style="display: none;"><b>Step 4</b>: Ahora vamos a desplegar las liquidaciones de los empleados de la pagina 1 (por ejemplo)&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=3" alt="Step 4 image" /></p>

                  <p style="display: none;"><b>Step 5</b>: En este punto vamos a mostrar las formulas que les dan valor a los conceptos haciendo click en el nombre del empleado que sale el la liquidacion</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=4" alt="Step 5 image" /></p>

                  <p style="display: none;"><b>Step 6</b>: Ahora con las formulas a la vista vamos a buscar, por ejemplo, el concepto "Obra Social Extraordinario " dentro de los conceptos de "Aportes" y en la formula que esta a su derecha, vamos a poner el nuevo valor que queremos asignar a este concepto</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=5" alt="Step 6 image" /></p>

                  <p style="display: none;"><b>Step 7</b>: una vez editado esta formula se puede observar que el concepto toma el valor deseado y como es 0 en este caso se oculta&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=6" alt="Step 7 image" /></p>

                  <p style="display: none;"><b>Step 8</b>: por ultimo hacemos Click en el boton&nbsp;<span class="component"><i><b>Guardar liquidaciones</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137195&step_index=7" alt="Step 8 image" /></p>
                </p>
            </div>
  
          </div>
        </div>
      </div>
    </section>

    <section id="impuestos" class="bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Impuestos</h2>
            <div>
                <h3>¿Como exporto Ventas al S.I.Ap-Regimen de información de Compras Ventas?</h3>
                <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; border-bottom: 2px solid #ebebeb; min-width: 100%" src="https://www.iorad.com/player/137511/-Como-exporto-Ventas-al-CITI-?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p>
                <p style="display: none;">
                  <p style="display: none;"><b>Step 1</b>: Primero ingresamos al informe Mi Estudio desde el Menu superior</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=0" alt="Step 1 image" /></p>

                  <p style="display: none;"><b>Step 2</b>: Seleccionamos el periodo con el que queremos trabajar</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=1" alt="Step 2 image" /></p>

                  <p style="display: none;"><b>Step 3</b>: en este caso Enero</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=2" alt="Step 3 image" /></p>

                  <p style="display: none;"><b>Step 4</b>: buscamos el contribuyente del que queremos exportar las ventas, y hacemos click en el botón "Ventas" de la sección "Cargar"</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=3" alt="Step 4 image" /></p>

                  <p style="display: none;"><b>Step 5</b>: Luego hacemos click en el botón "TXT SIAP" para acceder a la pantalla de exportación de las ventas</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=4" alt="Step 5 image" /></p>

                  <p style="display: none;"><b>Step 6</b>: Desde esta pantalla vamos a poder descargar archivos de Facturas y de Alicuotas en el formato que lo solicita el S.I.Ap-Regimen de información de Compras Ventas</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=5" alt="Step 6 image" /></p>

                  <p style="display: none;"><b>Step 7</b>: al hacer click en los botones se descargarán los archivos automáticamente&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137511&step_index=6" alt="Step 7 image" /></p>
                </p>


            </div>                   
          </div>
          
        </div>
      </div>
    </section>

    <section id="contabilidad">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>Contabilidad</h2>
            <div id="agregarAsiento">
                <h3>¿Como agrego un asiento?</h3>
                <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; border-bottom: 2px solid #ebebeb; min-width: 100%" src="https://www.iorad.com/player/137697/-Como-agrego-un-asiento-?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p>
                <p style="display: none;">
                  <p style="display: none;"><b>Step 1</b>: Vamos a ingresar al informe Mi Estudio desde el menu superior "Gestion/Mi Estudio"</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=0" alt="Step 1 image" /></p>

                  <p style="display: none;"><b>Step 2</b>: hacemos Click en el boton&nbsp;<span class="component"><i><b>Aceptar&nbsp;</b></i>si el periodo seleccionado es el correcto, en su defecto elegimos otro periodo. También podemos buscar un contribuyente en particular.<i><b><br></b></i><i><b><br></b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=1" alt="Step 2 image" /></p>

                  <p style="display: none;"><b>Step 3</b>: una vez seleccionado el contribuyente hacemos click en el botón de Sumas y Saldos en la sección "Contabilizar"</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=2" alt="Step 3 image" /></p>

                  <p style="display: none;"><b>Step 4</b>: desde el Informe de Sumas y saldos hacemos Click en el botón&nbsp;<span class="component"><i><b>Agregar Asiento</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=3" alt="Step 4 image" /></p>

                  <p style="display: none;"><b>Step 5</b>: Ahora vamos a cargar el nombre del Asiento&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=4" alt="Step 5 image" /></p>

                  <p style="display: none;"><b>Step 6</b>: Luego la fecha del asiento, este campo es muy importante por que define en que ejercicio impactará el asiento.<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=5" alt="Step 6 image" /></p>

                  <p style="display: none;"><b>Step 7</b>: Seleccionamos el tipo de asiento, en este caso de Devengamiento.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=6" alt="Step 7 image" /></p>

                  <p style="display: none;"><b>Step 8</b>: Ahora vamos a agregar movimientos al asiendo, seleccionando la cuenta que queremos agregar</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=7" alt="Step 8 image" /></p>

                  <p style="display: none;"><b>Step 9</b>: asignandole un valor en debe/haber&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=8" alt="Step 9 image" /></p>

                  <p style="display: none;"><b>Step 10</b>: y haciendo Click en el botón&nbsp;<span class="component"><i><b>Agregar&nbsp;</b></i>para que la cuenta seleccionada se agregue al asiento que estamos creando&nbsp;<i><b></b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=9" alt="Step 10 image" /></p>

                  <p style="display: none;"><b>Step 11</b>: Luego vamos a agregar otra cuenta para completar el asiento, por ejemplo Clientes<br></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=10" alt="Step 11 image" /></p>

                  <p style="display: none;"><b>Step 12</b>: Asignamos un valor al Haber en este caso&nbsp;</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=11" alt="Step 12 image" /></p>

                  <p style="display: none;"><b>Step 13</b>: y volvemos a agregar la cuenta haciendo Click en el botón&nbsp;<span class="component"><i><b>Agregar</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=12" alt="Step 13 image" /></p>

                  <p style="display: none;"><b>Step 14</b>: por ultimo guardamos el asiendo haciendo Click en el botón&nbsp;<span class="component"><i><b>Guardar</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=13" alt="Step 14 image" /></p>

                  <p style="display: none;"><b>Step 15</b>: Eso es todo</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137697&step_index=14" alt="Step 15 image" /></p>
                </p>




            </div>    
            <div id="accederAlPlanDeCuentas">
                <h3>¿Como accedo al plan de cuentas y activo una cuenta al contribuyente?</h3>
                <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; border-bottom: 2px solid #ebebeb; min-width: 100%" src="https://www.iorad.com/player/137979/-Como-accedo-al-plan-de-cuentas-y-activo-una-cuenta-?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p>
                <p style="display: none;">
                  <p style="display: none;"><b>Step 1</b>: Primero vamos al informe&nbsp;<span class="component"><i><b>Mi Estudio</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=0" alt="Step 1 image" /></p>

                  <p style="display: none;"><b>Step 2</b>: cargamos el informe para el periodo seleccionando haciendo Click en&nbsp;<span class="component"><i><b>Aceptar</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=1" alt="Step 2 image" /></p>

                  <p style="display: none;"><b>Step 3</b>: con un Click en el botón de&nbsp;<span class="component"><i><b>Sumas y Saldos&nbsp;</b></i>accedemos al informe del contribuyente<i><b></b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=2" alt="Step 3 image" /></p>

                  <p style="display: none;"><b>Step 4</b>: desde aca podemos ver el botón y hacer Click en&nbsp;<span class="component"><i><b>Plan de cuentas&nbsp;</b></i>lo que nos despliega el plan de cuentas completo del sistema<i><b></b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=3" alt="Step 4 image" /></p>

                  <p style="display: none;"><b>Step 5</b>: para activar una cuenta podemos hacer click en los checkboxes que estan a la derecha</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=4" alt="Step 5 image" /></p>

                  <p style="display: none;"><b>Step 6</b>: como por ejemplo la cuenta Dolares 110104001. Estas cuentas estarán activadas para este contribuyente en todos los ejercicios.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=5" alt="Step 6 image" /></p>

                  <p style="display: none;"><b>Step 7</b>: ahora vamos a buscar una cuenta en el plan de cuentas</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=6" alt="Step 7 image" /></p>

                  <p style="display: none;"><b>Step 8</b>: haciendo Click en&nbsp;<span class="component"><i><b>Buscar Nro. Cuenta&nbsp;</b></i>podemos buscar cuentas, vamos a activar una cuenta de sociedades<i><b></b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=7" alt="Step 8 image" /></p>

                  <p style="display: none;"><b>Step 9</b>: escribimos las palabras que queremos buscar en este campo&nbsp;<span class="component"><i><b>Buscar Nro. Cuenta</b></i></span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=8" alt="Step 9 image" /></p>

                  <p style="display: none;"><b>Step 10</b>: y luego con las flechas podemos recorres las apariciones de la palabra en el plan de cuenta</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=9" alt="Step 10 image" /></p>

                  <p style="display: none;"><b>Step 11</b>: una vez detectada la cuenta que queremos activar, en este caso "<span>110401001 Sociedad", hacemos click en el checkbox de la derecha</span></p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=10" alt="Step 11 image" /></p>

                  <p style="display: none;"><b>Step 12</b>: Eso es todo, ahora estas cuentas pueden ser usadas en los asientos que queramos agregar al contribuyente en&nbsp; cualquier periodo.</p>
                  <p style="display: none;"><img src="https://www.iorad.com/api/tutorial/stepScreenshot?tutorial_id=137979&step_index=11" alt="Step 12 image" /></p>
                </p>
            </div>
          </div>
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

   
