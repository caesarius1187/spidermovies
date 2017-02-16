<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 30/01/2017
 * Time: 04:31 PM
 */
?>
<div id="divExportacion" class="index">
        <div id="exportar" class="index" style="overflow-x: visible">
            <?php
            echo $this->Form->create('Empleado',[ 'class'=>'formTareaCargas',]);
            ?>
<table style="width:100%;">
    <tr>
        <?php

        $i =0 ;
        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            ?>
            <td style="border:1px solid black;width:300px;">
                <?php
                //            echo "<td>";
                //            $empleadoid = $empleado['id'];
                //            echo number_format($empleadoDatos[$empleadoid]['ARTart'], 2, ",", ".")."</td>";

                //            CUIL CUIT sin guiones 11
                echo $this->Form->input('Empleado.'.$i.'.cuit',['orden'=>$i,'maxlength'=>'11','value'=>$empleado['cuit']]);
                //            Apellido y Nombre TEXTO 30
                echo $this->Form->input('Empleado.'.$i.'.nombre',['orden'=>$i,'maxlength'=>'30','value'=>$empleado['nombre']]);
                //            Cónyuge SI (1) NO (0) 1
                //todo: AGREGAR SI TIENE CONYUGUE O NO EN EMPLEADOS
                if($empleado['conyugue']){
                    echo $this->Form->input('Empleado.'.$i.'.conyuge',['orden'=>$i,'type'=>'checkbox','checked'=>'checked','maxlength'=>'1']);
                }else{
                    echo $this->Form->input('Empleado.'.$i.'.conyuge',['orden'=>$i,'type'=>'checkbox','maxlength'=>'1']);
                }
                //            Cantidad de Hijos ENTERO 2
                //todo: AGREGAR SI TIENE conyugue, hijos, adherente,
                //todo: codigoactividad, codigosituacion, codigocondicionm, codigozona, codigomodalidadcontratacion
                //todo: codigosiniestrado(codigo incapacidad), tipoempresa
                echo $this->Form->input('Empleado.'.$i.'.hijos',['orden'=>$i,'maxlength'=>'2','value'=>$empleado['hijos']]);
                //            Codigo de Situación ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.codigosituacion',['orden'=>$i,'maxlength'=>'3']);
                //            Codigo de Condición ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.codigocondicion',['orden'=>$i,'maxlength'=>'3']);
                //            Código de Actividad ENTERO 3
                //            $codigoactividad = ['10','15','30','34','35','36','37','38','39','40','50','51','52','53','54','55','56','57','58','59','60','61','62',
                //            '69','75','76','77','78','79','81','82','87','88'];
                echo $this->Form->input('Empleado.'.$i.'.codigoactividad',['orden'=>$i,'maxlength'=>'3','options'=>$codigoactividad,'default'=>$empleado['codigoactividad'],'style'=>'width:300px']);
                //            Código de Zona ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.codigozona',['orden'=>$i,'maxlength'=>'3','default'=>$empleado['codigozona'],'style'=>'width:300px']);
                //            Porcentaje de Aporte Adicional SS IMPORTE 5
                echo $this->Form->input('Empleado.'.$i.'.porcentajeaporteadicionalss',['orden'=>$i,'maxlength'=>'5']);
                //            Porcentaje de Reducción ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.porcentajereduccion',['orden'=>$i,'maxlength'=>'3']);
                //            Código de Modalidad de Contratacion ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.codigomodalidadcontratacion',['orden'=>$i,'maxlength'=>'3','options'=>$codigomodalidadcontratacion,'default'=>$empleado['codigomodalidadcontratacion'],'style'=>'width:300px']);
                //            Código de Obra Social TEXTO 6
                echo $this->Form->input('Empleado.'.$i.'.codigoobrasocial',['orden'=>$i,'maxlength'=>'3']);
                //            Cantidad de Adherentes ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.adherentes',['orden'=>$i,'maxlength'=>'2']);
                //            Remuneración Total IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneraciontotal',['orden'=>$i,'maxlength'=>'15','value'=>$empleadoDatos[$empleadoid]['remtotal']]);
                //            Remuneración Imponible Aportes IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponibleaportes',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['totalContribucionesSS']]);
                //            Remuneración Imponible Contribuciones IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponiblecontribuciones',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['totalContribucionesSS']]);
                //            Asignaciones Familiares Pagadas IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.asignacionesfamiliarespagadas',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['asignacionfamiliar']]);
                //            Importe Aporte SIJP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeaportesijp',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Aporte INSSJP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeaporteinnssjp',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['INSSJP']]);
                //            Importe Aporte Adicional SS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeaporteadicional',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['seguridadsocialaporteadicional']]);
                //            Importe Aporte Voluntario IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeaportevoluntario',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Excedentes Aportes SS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeexcedentesaportesss',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Neto Total Aportes SS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importenetototalaportes',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteSStotal']]);
                //            Importe Aportes OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeaportesos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteOStotal']]);
                //            Importe Adicional OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeadicionalos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteOSaporteadicionalos']]);
                //            Importe Aporte ANSSAL IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeanssal',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['ContribucionesOSANSSAL']]);
                //            Importe Excedentes Aportes OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importeexcedentesaportesos',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Total Aportes OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importetotalaportesos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteOStotal']]);
                //            Importe Contribución SIJP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucionsijp',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Contribución INSSJP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucioninssjp',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['INSSJP']]);
                //            Importe Contribución FNE IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucionfne',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Contribución Asig Familiares IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucionasigfamiliares',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Total Contribuciones SS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importetotalcontribucionesss',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['totalContribucionesSS']]);
                //            Importe Contribución OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucionos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['ContribucionesOScontribucionos']]);
                //            Importe Contribución ANSSAL IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribucionanssal',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['ContribucionesOSANSSAL']]);
                //            Provincia Localidad TEXTO 50
                echo $this->Form->input('Empleado.'.$i.'.provincialocalidad',['orden'=>$i,'maxlength'=>'50']);
                //            Importe Total Contribuciones OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importetotalcontribucionesos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['ContribucionesOStotal']]);
                //            Código de siniestrado ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.codigodesiniestrado',['orden'=>$i,'maxlength'=>'2','options'=>$codigosiniestrado,'style'=>'width:300px']);
                //            Marca de corresponde reducción SI (1) NO (0) 1
                echo $this->Form->input('Empleado.'.$i.'.marcadecorrespondereduccion',['orden'=>$i,'type'=>'checkbox','maxlength'=>'1']);
                //            Remuneración imponible 3 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible3',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['rem3']]);
                //            Remuneración imponible 4 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible4',['orden'=>$i,'maxlength'=>'15']);
                //            Aporte adicional de OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.aporteadicionalos',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteOSaporteadicionalos']]);
                //            Capital de recomposición LRT IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.capitalderecomposicion',['orden'=>$i,'maxlength'=>'15']);
                //            Tipo Emplesa ENTERO 1
                echo $this->Form->input('Empleado.'.$i.'.tipoempresa',['orden'=>$i,'maxlength'=>'1','options'=>$tipoempresa,'style'=>'width:300px']);
                //            Régimen ENTERO 1
                echo $this->Form->input('Empleado.'.$i.'.regimen',['orden'=>$i,'maxlength'=>'1']);
                //            Renatea IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.renatea',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['aporterenatea']]);
                //            DiasTrabajados ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.diastrabajados',['orden'=>$i,'maxlength'=>'2','default'=>$empleadoDatos[$empleadoid]['horasDias']]);
                //            HsExtra monto IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.hsextramonto',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['importehorasextras']]);
                //            SAC IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.sac',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['SAC']]);
                //            Sueldo Adic IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.sualdoadic',['orden'=>$i,'maxlength'=>'15']);
                //            Vacaciones IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.vacaciones',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['vacaciones']]);
                //            Zona Desfavorable IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.zonadesfavorable',['orden'=>$i,'maxlength'=>'15']);
                //            Situacion1 ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.situacion1',['orden'=>$i,'maxlength'=>'3']);
                //            Situacion2 ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.situacion2',['orden'=>$i,'maxlength'=>'3']);
                //            Situacion3 ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.situacion3',['orden'=>$i,'maxlength'=>'3']);
                //            Dia1 ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.dia1',['orden'=>$i,'maxlength'=>'2']);
                //            Dia2 ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.dia2',['orden'=>$i,'maxlength'=>'2']);
                //            Dia3 ENTERO 2
                echo $this->Form->input('Empleado.'.$i.'.dia3',['orden'=>$i,'maxlength'=>'2']);
                //            Remuneración imponible 5 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible5',['orden'=>$i,'maxlength'=>'15']);
                //            MarcaConvencionado SI (1) NO (0) 1
                echo $this->Form->input('Empleado.'.$i.'.marcaconvencionado',['orden'=>$i,'type'=>'checkbox','maxlength'=>'1']);
                //            Dto1273OS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.dto1273OS',['orden'=>$i,'maxlength'=>'15']);
                //            Dto1273INSSJP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.dto1273inssjp',['orden'=>$i,'maxlength'=>'15']);
                //            Remuneración Imponible 6 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible6',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['rem6']]);
                //            Aporte Diferencial SIJIP IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.aportediferencialsijip',['orden'=>$i,'maxlength'=>'15']);
                //            Tipo Operación ENTERO 1
                echo $this->Form->input('Empleado.'.$i.'.tipooperacion',['orden'=>$i,'maxlength'=>'1']);
                //            Adicionales IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.adicionalesimporte',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['AporteOSaporteadicionalos']]);
                //            Premios IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.premiosimporte',['orden'=>$i,'maxlength'=>'15']);
                //            CantidadHorasExtra ENTERO 3
                echo $this->Form->input('Empleado.'.$i.'.cantidadhorasextras',['orden'=>$i,'maxlength'=>'3','default'=>$empleadoDatos[$empleadoid]['horasextras']]);
                //            Sueldo Dto788_05 Rem 8 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.sueldodDto78805rem8',['orden'=>$i,'maxlength'=>'15']);
                //            Aportes SS Dto788_05 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.aportesssdto78805',['orden'=>$i,'maxlength'=>'15']);
                //            Remuneración Imponible 7 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible7',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['rem7']]);
                //            AportesRes33_41_SSS IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.aportesRes3341sss',['orden'=>$i,'maxlength'=>'15']);
                //            Remuneración imponible 8 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible8',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['rem8']]);
                //            Conceptos no remunerativos IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.conceptosnoremunerativos',['orden'=>$i,'maxlength'=>'15']);
                //            Rectificación de remuneración IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.rectificacionremuneracion',['orden'=>$i,'maxlength'=>'15']);
                //            Maternidad IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.maternidad',['orden'=>$i,'maxlength'=>'15']);
                //            Remuneración Imponible 9 IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.remuneracionimponible9',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['rem9']]);
                //            Cantidad Horas Trabajadas IMPORTE 15
                $cantidadhoras = ($empleadoDatos[$empleadoid]['horasDias']*1) *8* ($empleadoDatos[$empleadoid]['jornada']*1);
                echo $this->Form->input('Empleado.'.$i.'.cantidadhorastrabajadas',['orden'=>$i,'maxlength'=>'15','default'=>$cantidadhoras]);
                //            Porcentaje Tarea Dif IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.porcentajetareadif',['orden'=>$i,'maxlength'=>'15']);
                //            Importe Contribución Tarea Dif IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribuciontareadif',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['seguridadsocialcontribtareadif']]);
                //            Importe Contribución Tarea Dif Compen IMPORTE 15
                echo $this->Form->input('Empleado.'.$i.'.importecontribuciontareadifcompen',['orden'=>$i,'maxlength'=>'15','default'=>$empleadoDatos[$empleadoid]['ContribSScontribtareadif']]);?>
            </td>
            <?php
            $i++;
        }
        echo $this->Form->end();
        ?>
    </tr>
</table>
</div>
<div id="txtexportar" class="index">
    <?php echo $this->Form->input('txtareaexportar', array('type' => 'textarea','style'=>'width:100%')); ?>
    <?php echo $this->Form->input('orden', array('type' => 'hidden','value'=>0)); ?>
</div>
</div>