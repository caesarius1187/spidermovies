<table class="tbl_vtas_det" style="border:1px solid white" id="tablaVentas" cellspacing="0" cellpadding="0" >
    <thead>
        <tr>
            <th style="width: 47px">Fecha</th><!--1-->
            <th style="width: 275px;">Comprobante</th><!--2-->
            <th style="width: 95px;">CUIT</th><!--3-->
            <th style="width: 95px;">Nombre</th><!--4-->
            <th style="width: 95px;">Cond.IVA</th><!--5-->
            <th style="width: 144px;">Actividad</th><!--6-->
            <th style="width: 144px;">Localidad</th><!--7-->
            <?php
            if(!$cliente['Cliente']['tieneMonotributo']){
                echo
                '<th style="width: 89px;">Debito</th><!--8-->
                <th style="width:64px">Alicuota</th> <!--9-->
                <th style="width: 89px;" class="sum">Neto</th><!--10-->
                <th style="width: 89px;" class="sum">IVA</th>   <!--11-->
                ';
            }
            if($cliente['Cliente']['tieneIVAPercepciones']){
                echo
                '<th style="width: 89px;" class="sum" >IVA Percep</th><!--12-->';
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){
                echo
                '<th style="width: 89px;" class="sum" >IIBB Percep</th><!--13-->';
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){
                echo
                '<th style="width: 89px;" class="sum" >Act Vs Perc</th><!--14-->';
            }
            if($cliente['Cliente']['tieneImpuestoInterno']){
                echo
                '<th style="width: 89px;" class="sum" >Imp Internos</th><!--15-->';
            }
            if(!$cliente['Cliente']['tieneMonotributo']){
                ?>
                <th style="width: 89px;" class="sum" >No Gravados</th><!--16-->
                <th style="width: 89px;" class="sum" >Exento</th><!--17-->
            <?php } ?>
            <th style="width: 89px;" class="sum" >Exento Act. Econom.</th><!--18-->
            <th style="width: 89px;" class="sum" >Exento Act. Varias</th><!--19-->
            <th style="width: 89px;" class="sum">Total</th><!--20-->
            <th>Acciones</td><!--21-->
        </tr>
    </thead>
    <tbody id="bodyTablaVentas">
    <?php
    foreach($ventas as $v => $venta ){
//        echo $this->Form->create('Venta',array('controller'=>'Venta','action'=>'edit'));
        $tdClass = "tdViewVenta".$venta['Venta']["id"];
        $titleComprobante = $venta["Comprobante"]['nombre']."-".$venta["Puntosdeventa"]['nombre']."-".$venta['Venta']["numerocomprobante"];
        $labelComprobante = $venta["Comprobante"]['abreviacion']."-".$venta["Puntosdeventa"]['nombre']."-".$venta['Venta']["numerocomprobante"];
        ?>
        <tr id="rowventa<?php echo $venta['Venta']["id"]?>">
            <td class="<?php echo $tdClass ?>"><?php echo date('d',strtotime($venta['Venta']["fecha"]))?></td><!--1-->
            <td class="<?php echo $tdClass ?>" title="<?php echo $titleComprobante; ?>" > <?php echo $labelComprobante; ?> </td><!--2-->
            <td class="<?php echo $tdClass ?>"><?php echo $venta["Subcliente"]["cuit"]?></td><!--3-->
            <td class="<?php echo $tdClass ?>" title="<?php echo $venta["Subcliente"]["nombre"]?>"><?php echo $venta["Subcliente"]["nombre"]?></td><!--4-->
            <td class="<?php echo $tdClass ?>"><?php
                switch ($venta['Venta']["condicioniva"]) {
                    case 'monotributista':
                        echo 'Monot.';
                        break;
                    case 'responsableinscripto':
                        echo 'Res.Ins.';
                        break;
                    case 'consf/exento/noalcanza':
                        echo 'Con.F/Ex/NoAl.';
                        break;
                    default:
                        echo 'Monot.';
                        break;
                }
                ?>
            </td><!--5-->
            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Actividade"]["nombre"]?>">
                <?php echo $venta["Actividade"]["nombre"]?>
            </td><!--6-->
            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Localidade"]["nombre"]?>">
                <?php echo $venta['Partido']["nombre"].'-'.$venta["Localidade"]["nombre"]?>
            </td><!--7-->
            <?php
            if($venta['Comprobante']["tipodebitoasociado"]=='Restitucion de debito fiscal'){
                $venta['Venta']["neto"] = $venta['Venta']["neto"]*-1;
                $venta['Venta']["iva"] = $venta['Venta']["iva"]*-1;
                $venta['Venta']["ivapercep"] = $venta['Venta']["ivapercep"]*-1;
                $venta['Venta']["iibbpercep"] = $venta['Venta']["iibbpercep"]*-1;
                $venta['Venta']["actvspercep"] = $venta['Venta']["actvspercep"]*-1;
                $venta['Venta']["impinternos"] = $venta['Venta']["impinternos"]*-1;
                $venta['Venta']["nogravados"] = $venta['Venta']["nogravados"]*-1;
                $venta['Venta']["excentos"] = $venta['Venta']["excentos"]*-1;
                $venta['Venta']["exentosactividadeseconomicas"] = $venta['Venta']["exentosactividadeseconomicas"]*-1;
                $venta['Venta']["exentosactividadesvarias"] = $venta['Venta']["exentosactividadesvarias"]*-1;
                $venta['Venta']["total"] = $venta['Venta']["total"]*-1;
            }
            if(!$cliente['Cliente']['tieneMonotributo']){?>
                <td class="<?php echo $tdClass?>"><?php echo substr($venta['Venta']["tipodebito"],0,10)?></td><!--8-->
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["alicuota"], 2, ",", ".")?>%</td><!--9-->
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["neto"], 2, ",", ".")?></td><!--10-->
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["iva"], 2, ",", ".")?></td><!--11-->
                <?php
                //Hay que agregar Condicion frente al IVA ??
            }
            if($cliente['Cliente']['tieneIVAPercepciones']){?>
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["ivapercep"], 2, ",", ".")?></td><!--12-->
                <?php
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){?>
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["iibbpercep"], 2, ",", ".")?></td><!--13-->
                <?php
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){?>
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["actvspercep"], 2, ",", ".")?></td><!--14-->
                <?php
            }
            if($cliente['Cliente']['tieneImpuestoInterno']){?>
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["impinternos"], 2, ",", ".")?></td><!--15-->
                <?php
            }
            if(!$cliente['Cliente']['tieneMonotributo']){?>
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["nogravados"], 2, ",", ".")?></td><!--16-->
                <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["excentos"], 2, ",", ".")?></td><!--17-->
            <?php } ?>
            <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["exentosactividadeseconomicas"], 2, ",", ".")?></td><!--18-->
            <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["exentosactividadesvarias"], 2, ",", ".")?></td><!--19-->
            <td class="<?php echo $tdClass?> numericTD"><?php echo number_format($venta['Venta']["total"], 2, ",", ".")?></td><!--20-->
            <td class="<?php echo $tdClass?>">
                <?php
                $paramsVenta = $venta['Venta']["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarVenta(".$paramsVenta.")"));
                echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarVenta(".$paramsVenta.")"));
                //echo $this->Form->end();
                ?>
            </td><!--21-->
        </tr>
        <?php
    }
    unset($venta);
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th >Totales</th><!--1-->
        <th ></th><!--2-->
        <th ></th><!--3-->
        <th ></th><!--4-->
        <th ></th><!--5-->
        <th ></th><!--6-->
        <th ></th><!--7-->
        <?php
        if(!$cliente['Cliente']['tieneMonotributo']){
            echo
            '<th></th><!--8-->
                       <th></th><!--9--> 
                       <th></th><!--10-->
                       <th></th><!--11-->   
                       ';
        }
        if($cliente['Cliente']['tieneIVAPercepciones']){
            echo
            '<th></th><!--12-->';
        }
        if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){
            echo
            '<th></th><!--13-->';
        }
        if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){
            echo
            '<th></th><!--14-->';
        }
        if($cliente['Cliente']['tieneImpuestoInterno']){
            echo
            '<th></th><!--15-->';
        }
        if(!$cliente['Cliente']['tieneMonotributo']) {
            echo
            '
                  <th></th><!--16-->
                  <th></th><!--17-->';
        }
        ?>
        <th></th><!--18-->
        <th></th><!--19-->
        <th></th><!--20-->
        <th></th><!--21-->
    </tr>
    </tfoot>
</table>
