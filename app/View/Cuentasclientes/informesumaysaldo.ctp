<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016
 * Time: 12:15 PM
 */
//Debugger::dump($cliente['Cuentascliente']);
//Debugger::dump($cuentascliente);
?>
<div class="index">
    <label>INFORME SUMA Y SALDO</label>
    <label>Contribuyente: <?php echo $cliente['Cliente']['nombre']; ?> </label>
    <label>Periodo: <?php echo $periodo;?></label>
    <?php echo $this->Form->button('Imprimir',
        array('type' => 'button',
            'class' =>"btn_imprimir",
            'onClick' => "imprimir()"
        )
    );?>
    <?php echo $this->Form->button('Excel',
        array('type' => 'button',
            'id'=>"clickExcel",
            'class' =>"btn_imprimir",
        )
    );?>
</div>
<div class="index">
    <table>
        <thead>
        <tr>
            <td>Numero</td>
            <td>Nombre</td>
            <td>Debitos</td>
            <td>Creditos</td>
            <td>Calculo Saldo Actual</td>
            <td>Saldo Ej Anterior Guardado</td>
            <td>Saldo Ej Actual Guardado</td>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($cliente['Cuentascliente'] as $cuentascliente){?>
            <tr>
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['nombre']; ?>
                </td>
                <?php
                $saldoCalculado = 0;
                $debitos = 0;
                $creditos = 0;
                foreach ($cuentascliente['Movimiento'] as $movimiento){
                $debitos+= $movimiento['debe'];
                $creditos+= $movimiento['haber'];
                $saldoCalculado += $movimiento['debe'];
                $saldoCalculado -= $movimiento['haber'];
                }
                ?>
                <td>
                    <?php echo $debitos; ?>
                </td>
                <td>
                    <?php echo $creditos; ?>
                </td>
                <td>
                <?php echo $saldoCalculado; ?>
                </td>
                <?php
                $saldoanterior=0;
                if(isset($cuentascliente['Saldocuentacliente'][0]['saldoanterior'])){
                    $saldoanterior = $cuentascliente['Saldocuentacliente'][0]['saldoanterior'];
                }
                $saldoactual=0;
                if(isset($cuentascliente['Saldocuentacliente'][0]['saldoactual'])){
                    $saldoactual = $cuentascliente['Saldocuentacliente'][0]['saldoactual'];
                }
                ?>
                <td>
                    <?php echo $saldoanterior; ?>
                </td>
                <td>
                    <?php echo $saldoactual; ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
