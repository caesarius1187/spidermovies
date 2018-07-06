<div class="terminos">
    <h1><?php echo __('Terminos y Condiciones'); ?></h1>
    <p style="font-size: 20px;text-align: left">
        Estas condiciones de uso, incluyendo sus anexos (“Condiciones Generales”)
        constituyen un contrato entre cualquier persona (en adelante, “Usuario” 
        o, en plural, “Usuarios”) que desee acceder y/o usar el servicio CONTA 
        S.R.L. CUIT: 30-71574628-6 (“en adelante CONTA”, nosotros, nuestro y 
        términos similares según corresponda), en cuyo caso el Usuario estará 
        sujeto a los términos y condiciones que rigen CONTA.<br/> 
        En estas Condiciones Generales se describen los derechos, 
        responsabilidades y obligaciones del Usuario y de Mercado Pago al 
        utilizar el servicio de procesamiento de pagos, la Plataforma 
        (conforme es definida en estas Condiciones Generales) y cualquier 
        producto o servicio relacionado que ofrezca Mercado Pago (cualquiera de 
        ellos, indistintamente, el “Servicio” y, conjuntamente, los “Servicios”, 
        según corresponda).<br/> 
        El Usuario debe leer, entender y aceptar todas las condiciones 
        establecidas en estas Condiciones Generales y demás políticas y 
        principios incorporados a las mismas por referencia, previo a su 
        registro como Usuario de Mercado Pago y/o la utilización del Servicio. 
        En cualquier caso, la utilización del Servicio implica la aceptación por
        parte del usuario de las Condiciones Generales.<br/> 
        Cualquier persona que no acepte estos términos y condiciones generales, 
        los cuales tiene un carácter obligatorio y vinculante, deberá abstenerse
        de utilizar el sitio y/o los servicios.<br/> 

        1.	el sistema CONTA opera bajo un plan piloto que provee un aporte 
        de información que es actualizada periódicamente, por lo tanto, el 
        usuario debe cotejar en cada caso si la información brindada se 
        encuentra plenamente vigente.<br/> 
        2.	el sistema CONTA en ningún caso sustituye, reemplaza ni 
        releva de la labor profesional.<br/> 
        3.	el sistema CONTA no opera, ni controla ni responde por el 
        contenido creado por el usuario, razón por la cual, CONTA deslinda todo 
        tipo de responsabilidad directa y/o indirecta sobre dicho contenido. <br/> 
        4.	el usuario reconoce incondicionalmente ser el autor y único 
        responsable de los datos y contenidos que procese y/o publique a través 
        del sistema CONTA.<br/> 
        5.	el usuario tiene a disposición un servicio de capacitación 
        inicial y asistencia telefónica y a través de internet mediante un 
        sistema de consultas acerca del funcionamiento del sistema CONTA.<br/> 
        6.	la suscripción al sistema CONTA permite el acceso a una 
        cantidad determinada de usuarios y adicionales, según lo convenido en 
        cada caso.<br/> 
        7.	CONTA se reserva el derecho de incluir nuevas funcionalidades en
        el futuro que puedan formar parte del sistema actual o requieran la 
        suscripción especial o adicional.<br/> 
        8.	Las partes acuerdan que CONTA podrá actualizar el valor de 
        suscripción mensual sin previo aviso, debiendo informar al suscriptor la
        actualización de valor dispuesta con diez días de anticipación al nuevo 
        mes de servicio.<br/> 
        9.	El usuario entiende y acepta que CONTA no hace de forma expresa 
        o tácita, reserva o garantía alguna con respecto al funcionamiento del 
        sistema y/o los materiales, información y/o cualquier otro tipo de 
        contenido incluido en el sitio.<br/> 
        10.	Se prohíbe la reproducción, duplicación, copia, descarga, venta,
        reventa, explotación comercial del sistema ni cualquier porción aislada 
        del mismo sin el consentimiento expreso y por escrito de CONTA.<br/> 
        11.	El usuario no podrá utilizar técnicas de enmarcado o “Framing” 
        para adjuntar cualquier marca, logotipo u otra información propietaria 
        (incluyendo imágenes, textos, diseño de pagina y forma) de CONTA sin el 
        consentimiento expreso por escrito de CONTA.<br/> 
        12.	El usuario no puede utilizar ninguna etiqueta o cualquier otro 
        texto oculto con el nombre o marcas de CONTA, sin el consentimiento 
        expreso por escrito de CONTA. Cualquier uso no autorizado implicará la 
        pérdida de la suscripción, sin perjuicio de la responsabilidad 
        contractual, extracontractual y/o penal que corresponda.<br/> 
        13.	El contenido del sistema es para uso personal. No podrá ser 
        reproducido, transmitido o distribuido sin el consentimiento previo y 
        expreso de CONTA.<br/> 
        14.	El usuario conoce y acepta que CONTA no otorga ninguna garantía 
        de cualquier naturaleza, ya sea expresa o implícita, sobre los datos, 
        contenidos, información y servicios. <br/> 
        15.	El usuario conoce y acepta que CONTA no garantiza ni asume 
        responsabilidad alguna respecto a los posibles daños y perjuicios 
        causados por el uso y utilización de la información, datos y servicios.<br/>  
        16.	CONTA excluye cualquier responsabilidad por los daños y 
        perjuicios que puedan deberse a la información y/o sistema y/o datos 
        suministrados por él o por terceros. Toda responsabilidad será del 
        usuario.<br/> 
        17.	Convienen las partes que toda divergencia emergente del presente
        será sometida para su conocimiento y resolución a los Tribunales 
        Ordinarios de la Provincia de Salta, Distrito Judicial Centro, Salta 
        Capital, con exclusión de todo otro fuero o jurisdicción, a cuyo fin 
        pactan prórroga de esta última.<br/> 
        18.	Se suscriben en este acto dos ejemplares iguales del presente 
        instrumento, quedando uno de ellos en poder del usuario, quien firma de 
        conformidad en el lugar y fecha indicados en este documento. <br/> 
    </p>
    <?php
    echo $this->Form->create('User',['action'=>'terminosycondiciones',$estudioid]);
    echo $this->Form->input('id',['value'=>$estudioid]);
    echo $this->Form->end('Aceptar');
    ?>
</div>