<?php

/**
 * Created by PhpStorm.
 * User: Eliu
 * Date: 04/10/2018
 * Time: 16:11
 */
$config_cuentas = "";
require "../recursos/numletras/CifrasEnLetras.php";
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>BOL</title>
</head>

<body>
    <style>
        body {
            font-family: "courier", Monaco, monospace;
            font-size: 12px;
            zoom: 180%;
            font-weight: 200;
            width: 100%;
            margin: 0px;
        }


        .title {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 1px;
            margin-top: 1px;
        }

        hr {
            border: none;
            border-top: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            font-family: "Lucida Console", Monaco, monospace;
            font-size: 12px !important;
        }

        .productos td {
            text-align: center;
        }

        .productos .precio {
            text-align: right;
        }

        table th {
            text-align: center;
        }

        tr.resumen {
            text-align: right;
        }

        .resumen td {
            border: none;
        }

        .precio {
            text-align: right;
        }

        .msg {
            text-align: center;
            justify-content: center;
        }

        .msg2 {
            text-align: center;
            justify-content: center;
        }

        .msg p,
        .msg2 p {
            text-align: center;
            justify-content: center;
        }

        .totales {
            margin-top: 10px;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            margin: 0;
        }
    </style>
    <?php
    require_once '../nucleo/include/MasterConexion.php';
    $objcon = new MasterConexion();


    $id_venta = $_GET["id"];

    $config = $objcon->consulta_arreglo("SELECT * from configuracion");

    $config_cuentas = $objcon->consulta_arreglo("SELECT cu.numero_cuenta FROM configuracion con
                                            INNER JOIN cuentas_bancarias cu ON con.id_cuenta_bancaria = cu.id");


    $venta = $objcon->consulta_arreglo("SELECT * FROM venta where id = {$id_venta}");


    if ($venta['estado_fila'] == 1 || $venta['estado_fila'] == 10) {
        $title = "BOLETA DE VENTA ELECTRONICA";
    } else if ($venta['estado_fila'] == 2) {
        $title = "BOLETA DE VENTA ELECTRONICA";
    } else if ($venta['estado_fila'] == 3) {
        $title = "NOTA DE CREDITO ELECTRONICA";
    } else {
        $title = "NOTA DE DEBITO ELECTRONICA";
    }

    $fecha = $venta["fecha_hora"];
    $total_pedido = 0;

    $usuario = $objcon->consulta_arreglo("SELECT * FROM usuario where id = {$venta['id_usuario']}");
    $cliente = $objcon->consulta_arreglo("SELECT * FROM cliente where id = {$venta['id_cliente']}");
    // echo json_encode($cliente);
    $boleta = $objcon->consulta_arreglo("SELECT id, serie FROM boleta WHERE id_venta = {$id_venta}");


    $entrega = $objcon->consulta_arreglo("SELECT * FROM entregas where id_venta = {$id_venta}");
    $productos = $objcon->consulta_matriz("SELECT p.nombre,p.precio_venta, pv.* from producto_venta pv left join producto p on pv.id_producto = p.id WHERE pv.id_venta = $id_venta");

    if ($productos == 0) {
        $productos = $objcon->consulta_matriz("SELECT producto AS nombre, cantidad, (precio*cantidad) AS total, precio FROM detallesfree WHERE id_venta = $id_venta");
    }

    $servicios = $objcon->consulta_matriz("SELECT s.nombre, sv.* from servicio_venta sv left join servicio s on sv.id_servicio = s.id WHERE sv.id_venta = $id_venta");

    $ventas = $objcon->consulta_arreglo("SELECT nombre FROM venta v, caja c WHERE v.id = $id_venta AND v.id_caja = c.id");
    $medio_pago = $objcon->consulta_arreglo("SELECT * FROM venta_medio_pago WHERE id_venta = $id_venta AND medio NOT LIKE 'DESCUENTO'");
    $Descuento = $objcon->consulta_arreglo("SELECT * FROM venta_medio_pago WHERE id_venta = $id_venta AND medio = 'DESCUENTO'");
    $vuelto = $objcon->consulta_arreglo("SELECT vuelto FROM venta_medio_pago WHERE id_venta = $id_venta AND medio NOT LIKE 'DESCUENTO' and vuelto not like 0");
    if (is_array($vuelto)) {
        $vuelto = $vuelto['vuelto'];
    } else {
        $vuelto = 0;
    }

    $cuotas = $objcon->consulta_matriz("SELECT * FROM ventas_cuotas WHERE id_venta = $id_venta ");
    $medios = $objcon->consulta_arreglo("SELECT group_concat(CONCAT(medio,'=', ROUND((monto-vuelto),2))) as medios FROM venta_medio_pago WHERE id_venta = $id_venta GROUP BY id_venta");
    $formas_de_pago = $objcon->consulta_matriz("SELECT CASE medio WHEN 'CREDITO' THEN 'A Credito' ELSE 'Al Contado' END as formas from venta_medio_pago WHERE id_venta = $id_venta GROUP BY formas ");




    $hash = $objcon->consulta_arreglo("SELECT * FROM comprobante_hash WHERE pkComprobante =" . $boleta['id']);

    // echo "SELECT * FROM comprobante_hash WHERE pkComprobante = $id_venta";
    $items = $objcon->consulta_arreglo("SELECT pv.id_producto
                FROM producto_venta pv
                INNER JOIN producto p ON pv.id_producto = p.id
                WHERE pv.id_venta = $id_venta
                UNION
                SELECT sv.id_servicio
                FROM servicio_venta sv
                INNER JOIN servicio s ON sv.id_servicio = s.id
                WHERE sv.id_venta = $id_venta
                ");


    $incluye = $objcon->consulta_arreglo("SELECT incluye_impuesto
                FROM producto p
                WHERE p.id = " . $items[0] . "
                UNION
                SELECT incluye_impuesto
                FROM servicio s
                WHERE s.id = " . $items[0] . "");

    if ($incluye[0] == 0) {
        $GEIG = "INAFECTA";
    } else if ($incluye[0] == 1) {
        $GEIG = "GRAVADA";
    } else if ($incluye[0] == 2) {
        $GEIG = "EXONERADA";
    } else {
        $GEIG = "GRATUITA";
    }
    // echo json_encode($hash);
    $bolsa = 0;
    $total_items = 0;
    ?>
    <!--<center><img src="../recursos/img/logo34.png" style="width: 70%; margin-bottom: 0px;"></center> <br>-->
    <?php if (file_exists("../recursos/img/logo34.png")) {?>
        <center><img src="../recursos/img/logo34.png" style="width: 70%; margin-bottom: 0px;"></center> <br>
    <?php }
            else { ?>
            <h3 class="title"><?php echo $config['nombre_negocio']; ?></h3>
    <?php } ?>    
    
    <p class="title"><?php echo "RUC: " . $config['ruc']; ?></p>
    <p class="title"><?php echo $config['razon_social']; ?></p>
    <p class="title"><?php echo $config['direccion']; ?></p>
    <p class="title"><?php echo "N??mero de cuenta: " . $config_cuentas['numero_cuenta']; ?></p>
    <p class="title"><?php echo $config['telefono']; ?></p>
    <hr>
    <p class="title"><b><?php echo $title; ?></b></p>
    <p class="title"><b>SERIE: <?php echo $boleta['serie'] . "-" . str_pad($boleta['id'], 8, "0", STR_PAD_LEFT); ?></b></p>
    <!--<p class="title">serie impresora: --><?php //echo $config['serie_impresora']; 
                                                ?>
    <!--</p>-->
    <!--<p class="title">Fecha:  ?></p>-->
    <p class="title"> <b>Fecha: : <?php echo date('Y-m-d H:i:s', strtotime($fecha)); ?> </b> </p>


    <hr>

    <table align="center">

        <tr>
            <!--  <td>Moneda</td>
        <td>:
            <?php
            if ($medio_pago != 0)
                if ($medio_pago['moneda'] == 'PEN')
                    echo 'Soles';
                else
                    echo 'Dolares';
            else
                echo 'Soles';
            ?>
        </td>-->
        </tr>
        <!--  <tr>
        <td>Cliente</td>
        <td>: <?php echo $ventas['nombre'] ?></td>
    </tr>-->
        <?php if ($cliente['id'] != 0) { ?>


            <tr>
                <?php
                if (strlen($cliente['documento']) > 8) {
                    $doc = 'RUC';
                    $cli = 'RAZON SOCIAL';
                } else {
                    $doc = 'DNI';
                    $cli = 'SOCIO(A)';
                }
                ?>
                <td><?php echo $doc; ?></td>
                <td>: <?php echo $cliente['documento']; ?> </td>
            </tr>
            <tr>
                <td><?php echo $cli; ?></td>
                <td>: <?php echo $cliente['nombre']; ?> </td>
            </tr>
            <tr>
                <td>Direccion</td>
                <td>: <?php echo $cliente['direccion'] ?></td>
            </tr>
        <?php } ?>
        <?php if (is_array($entrega)) : ?>
            <tr>
                <td>Fecha de Entrega</td>
                <td>: <?php echo $entrega['fecha']; ?> </td>
            </tr>
            <tr>
                <td>Entrega A: </td>
                <td>: <?php echo $entrega['cliente']; ?> </td>
            </tr>
            <tr>
                <td>Comentarios</td>
                <td>: <?php echo $entrega['comentarios']; ?> </td>
            </tr>
        <?php endif; ?>
    </table>

    <hr>

    <table align="center" class="productos">
        <tr>
            <th>Producto</th>
            <th>Cant</th>

            <!-- <th>Precio Cat</th>-->
            <th>P Unit</th>

            <!--  <th></th>-->
            <th style="border-style: none;" class="precio">Total</th>
        </tr>
        <?php
        if (is_array($productos)) :
            foreach ($productos as $p) :
                $nombre = $p['nombre'];
                if ($p['prod_secundario'] != 0) {
                    $prod_second = $objcon->consulta_arreglo("SELECT * FROM productos_precios WHERE id= " . $p['prod_secundario'] . "");
                    $nombre = $nombre . " - " . $prod_second["descripcion"];
                }

                $plastico = $objcon->consulta_arreglo("SELECT * FROM ley_plastico WHERE id_producto = '" . $p['id_producto'] . "'");

                if (is_array($plastico)) {
                    $bolsa += $p["cantidad"] * $config["impuesto_bolsa"];
                }
                $tipoPrecio = "";
                if ($p['precio_venta'] > ($p['total'] / $p['cantidad'])) {
                    $tipo_precio = "M";
                } elseif ($p['precio_venta'] == ($p['total'] / $p['cantidad'])) {
                    $tipo_precio = "C";
                }

        ?>
                <tr>

                    <td><?php echo strtoupper($nombre); ?></td>
                    <td><?php $total_items += $p['cantidad'];
                        echo $p['cantidad']; ?></td>

                    <!--  <td><?php echo $p['precio_venta']; ?></td>-->
                    <td><?php echo number_format($p['total'] / $p['cantidad'], 2, '.', ''); ?></td>
                    <!--  <td  width="1%">S/</td>-->
                    <td class="precio">S/ <?php echo number_format($p['total'], 2, '.', '') ?></td>
                </tr>
        <?php
            endforeach;
        endif; ?>

        <?php
        if (is_array($servicios)) :
            foreach ($servicios as $s) : ?>
                <tr>
                    <td><?php echo $s['cantidad']; ?></td>
                    <td><?php echo $s['nombre']; ?></td>
                    <td><?php echo number_format($p['total'] / $p['cantidad'], 2, '.', ''); ?></td>
                    <!--     <th style="border-style: none;
    border: 1px solid black;" width="1%">S/</th-->
                    <td width="5%" class="precio">S/ <?php echo number_format($s['total'], 2, '.', ''); ?></td>
                </tr>
        <?php
            endforeach;
        endif; ?>
    </table>

    <table align="center" class="totales">
        <?php
        if (is_array($Descuento) && $Descuento["medio"]) {
            $descuento = $Descuento["monto"] / 1.18;
            $totalventa = $venta['total'] - $Descuento["monto"];
            $total = ($venta['total'] / 1.18) - $descuento;
            $totaligv = $totalventa - ($totalventa / 1.18);

        ?>
            <tr class="resumen">
                <td colspan="2" style="padding-right: 5%;"><b>Descuento</b></td>
                <td width="1%">S/</td>
                <td width="5%"><b><?php echo number_format($Descuento["monto"], 2, '.', ''); ?></b></td>
            </tr>
            <tr class="resumen">
                <td colspan="2" style="padding-right: 5%;"><b><?php echo $GEIG; ?></b></td>
                <td width="1%">S/</td>
                <td width="5%"><b><?php echo number_format($total, 2, '.', ''); ?></b></td>
            </tr>
            <?php if ($bolsa) : ?>
                <tr class="resumen">
                    <td colspan="2" style="padding-right: 5%;"><b>ICBPER</b></td>
                    <td width="1%">S/</td>
                    <td width="5%"><b><?php echo number_format($bolsa, 2, '.', ''); ?></b></td>
                </tr>
            <?php endif; ?>
            <tr class="resumen">
                <td colspan="2" style="padding-right: 5%;"><b>I.G.V</b></td>
                <td width="1%">S/</td>
                <td width="5%"><b><?php echo number_format($totaligv, 2, '.', ''); ?></b></td>
            </tr>
            <tr class="resumen">
                <td colspan="2" style="padding-right: 5%;"><b>Total</b></td>
                <td width="1%">S/</td>
                <td width="5%"><b><?php echo number_format($totalventa, 2, '.', ''); ?></b></td>
            </tr>
            <tr class="resumen">
                <td colspan="2" style="padding-right: 5%;"><b>VUELTO: </b></td>
                <td width="1%">S/</td>
                <td width="5%"><b><?php //echo number_format($vuelto, 2, '.', ''); 
                                    ?></b></td>
            </tr>

    </table>
    <h4 class="msg" style="text-transform: uppercase;"><?php echo CifrasEnLetras::convertirNumeroEnLetras(number_format($totalventa, 2, ',', '.'), 1, "sol", "soles", true, "c??ntimos", "", false) ?></h4>

<?php
        } else {
?>

    <tr class="resumen">
        <td colspan="2" style="padding-right: 5%;"><b><?php echo $GEIG; ?></b></td>
        <td width="1%">S/</td>
        <td width="5%"><b><?php echo number_format($venta['subtotal'], 2, '.', ''); ?></b></td>
    </tr>
    <?php if ($bolsa) : ?>
        <tr class="resumen">
            <td colspan="2" style="padding-right: 5%;"><b>ICBPER</b></td>
            <td width="1%">S/</td>
            <td width="5%"><b><?php echo number_format($bolsa, 2, '.', ''); ?></b></td>
        </tr>
    <?php endif; ?>
    <tr class="resumen">
        <td colspan="2" style="padding-right: 5%;"><b>I.G.V</b></td>
        <td width="1%">S/</td>
        <td width="5%"><b><?php echo number_format($venta['total_impuestos'], 2, '.', ''); ?></b></td>
    </tr>
    <tr class="resumen">
        <td colspan="2" style="padding-right: 5%;"><b>Total</b></td>
        <td width="1%">S/</td>
        <td width="5%"><b><?php echo number_format($venta['total'], 2, '.', ''); ?></b></td>
    </tr>
    <tr class="resumen">
        <td colspan="2" style="padding-right: 5%;"><b>VUELTO: </b></td>
        <td width="1%">S/</td>
        <td width="5%"><b><?php echo number_format($vuelto, 2, '.', '');
                            ?></b></td>
    </tr>
    </table>
    <h4 class="msg" style="text-transform: uppercase;"><?php echo CifrasEnLetras::convertirNumeroEnLetras(number_format($venta['total'], 2, ',', '.'), 1, "sol", "soles", true, "c??ntimo", "", false)
                                                        ?></h4>

<?php
        }
?>

<h5 class="msg" style="text-transform: uppercase;">Medios de Pago: <?php echo $medios["medios"]; ?> </h5>
<h5 class="msg" style="text-transform: uppercase; ">Formas de Pago:

    <?php foreach ($formas_de_pago as $f) : ?>
        <td><?php echo $f["formas"]; ?></td>
    <?php endforeach;  ?>

</h5>
<?php if (!empty($venta["observaciones"])) : ?>
    <h5 class="msg" style="text-transform: uppercase;">observaciones: <?php echo $venta["observaciones"]; ?> </h5>
<?php endif; ?>

<?php
if (is_array($cuotas)) :

    $emision = new DateTime(date('d-m-Y H:i:s', strtotime($fecha)));
    $vence = new DateTime($cuotas[sizeof($cuotas) - 1]["fecha_pago"]);
    $diff = $emision->diff($vence);
?>

    <h5 class="msg" style="text-transform: uppercase;">Condici??n de pago: <?php echo $diff->days + 1; ?> d??as para cancelar el cr??dito
    </h5>

<?php
endif;
?>

<br>

<?php
if (is_array($cuotas)) :
?>
    <table border="1" align="center" class="productos">
        <tr>
            <th style="width: 20%;">Nro. de Cuota</th>
            <th style="width: 30%;">Fecha de vencimiento</th>

            <!-- <th>Precio Cat</th>-->
            <th style="width: 20%;">Tipo de moneda</th>

            <!--  <th></th>-->
            <th style="border-style: none; width: 30%;" class="precio">Monto de cuota</th>
        </tr>
        <?php
        foreach ($cuotas as $c) : ?>
            <tr>
                <td><?php echo $c["cuota"]; ?></td>
                <td><?php echo $c['fecha_pago']; ?></td>
                <td>Soles</td>
                <td width="5%" class="precio">S/ <?php echo number_format($c['importe'], 2, '.', ''); ?></td>
            </tr>
        <?php
        endforeach; ?>
    </table>



<?php endif; ?>

<br>


<!--<?php if ($cliente['id'] != 0) { ?>
    <table align="center">
        <tr>
            <td>RUC</td>
            <td>: <?php echo $cliente['documento']; ?> </td>
        </tr>
        <tr>
            <td>Razon Social</td>
            <td>: <?php echo $cliente['nombre']; ?> </td>
        </tr>
        <tr>
            <td>Direccion</td>
            <td>: <?php echo $cliente['direccion'] ?></td>
        </tr>
    </table>
<?php } ?>-->

<div class="msg" style="padding: 0px 0px;">
    <!--  <p>Usted ha sido atendido por <?php echo $usuario['nombres_y_apellidos'] ?>  ??Gracias por su Preferencia! </p>-->

    <p>Representacion impresa de la <br>BOLETA DE VENTA ELECTRONICA</p>

</div>

<!-- <div class="msg2">
    <p>Estimado Cliente: </p>
    <p>Por favor, revise su producto y su vuelto antes de salir.<br>Despu??s no se aceptar??n reclamaciones.</p>
    <br>
    <p>Todo cambio se realizar?? dentro de las 24 horas.</p>
</div> -->

<div class="msg2" style="padding: 0px 0px;">
    <!--   <p>Para ver el documento visita <b><?php echo $config['pagina_web']; ?></b></p>-->

    <p><b>Autorizado por la SUNAT</b> mediante Resolucion de Intendencia No. <b>034-00</b></p>
    <br>
    <!--  <p><b>USQAY</b>, es Facturacion Electronica visitanos en www.sistemausaqy.com o www.facebook.com/usqayperu</p>
-->
</div>


<?php
$qr = "" . $config['ruc'] . " | 03** | " . $boleta['serie'] . " | " . str_pad($boleta['id'], 8, "0", STR_PAD_LEFT) . " | " . $venta['total_impuestos'] . " | " . $venta['total'] . " | " . date("d/m/Y") . " | 1* | " . $venta['id_cliente'] . " |";
?>

<center>
    <img src="qrgen.php?data=<?php echo urlencode($qr); ?>" style="width:130px !important;" />
</center>

<center>
    <p>hash: <b><?php echo $hash["hash"]; ?></b></p>
</center>
<table align="center" class="totales">
    <tr class="resumen">
        <td colspan="3" style="padding-right: 5%;"><b>Items</b></td>
        <td width="5%"><b><?php echo $total_items; ?></b></td>
    </tr>
</table>

</body>

</html>