<?php
  require "../recursos/numletras/CifrasEnLetras.php";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>COT</title>
    <link rel="stylesheet" href="../recursos/adminLTE/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="impresion.css">
    <script type="text/javascript">
        window.print();
    </script>
</head>

<body>

<style type="text/css" media="print">
    @page {
        margin: 0;
    }
</style>
<?php
require_once '../nucleo/include/MasterConexion.php';
$objcon = new MasterConexion();


$id_coti = $_GET["id"];
$config = $objcon->consulta_arreglo("SELECT * from configuracion");
$config_cuentas = $objcon->consulta_arreglo("SELECT * FROM configuracion con
                                            INNER JOIN cuentas_bancarias cu ON con.id_cuenta_bancaria = cu.id");


$cotizacion = $objcon->consulta_arreglo("SELECT * FROM cotizacion where id = {$id_coti}");

// $venta = $objcon->consulta_arreglo("SELECT * FROM venta where id = {$id_coti}");
// echo json_encode($venta);
$usuario = $objcon->consulta_arreglo("SELECT * FROM usuario where id = {$cotizacion['id_usuario']}");
$cliente = $objcon->consulta_arreglo("SELECT * FROM cliente where id = {$cotizacion['id_cliente']}");

$productos = $objcon->consulta_matriz("SELECT dt.id, p.nombre, p.unidad, dt.cantidad, dt.precio
                                        FROM detalles_cotizacion dt
                                        INNER JOIN producto p ON dt.id_producto = p.id
                                        WHERE dt.id_coti = {$id_coti}");

?>

<!-- <center><img src="../recursos/img/logo.png"  style="width: 50%; margin-bottom: -30px;"></center> <br> -->

<body>
    <div class="container">
        <div id="content">
            <div class="logo">
                <img src="../recursos/img/logo.png" height="120px" width="250px">
                <h5><b><?php echo "Nombre de empresa: ".$config['razon_social']; ?></b></h5>
                <small class="TxtEmpresa"><?php echo "Dirección: ".$config['direccion']; ?></small><br>
                <small class="TxtEmpresa"><?php echo "Teléfono: ".$config['telefono']; ?></small><br>
                <small class="TxtEmpresa"><?php echo "Email: ".$config['correoEmisor'];?></small><br>
                <small class="TxtEmpresa"><?php echo $config_cuentas['banco'].' - CTA.'.$config_cuentas['tipo_cuenta'].' - '.$config_cuentas['numero_cuenta'] ?></small>
            </div>


            <div class="numeracion">
                <br>
                <h3><?php echo $config['nombre_negocio']?></h3>
                <h3>COTIZACIÓN</h3>
                <h4><?php echo "Nº".str_pad($id_coti, 8, "0", STR_PAD_LEFT); ?></h4>
            </div>
        </div>
        <br><br><br><br><br><br><br><br>



        <div id="content">
            <div class="cliente">
                <table>
                    <?php
                        if(strlen($cliente['documento'])> 8){
                            $doc = 'RUC';
                            $cli = 'RAZON SOCIAL';
                        }else{
                            $doc = 'DNI';
                            $cli = 'CLIENTE';
                        }
                    ?>
                    <tr>
                        <td class="tdLabel"><?php echo "Señor (es)" ?> </td>
                        <td>: <?php echo strtoupper($cliente["nombre"]); ?></td>
                    </tr>
                    <tr>
                        <td class="tdLabel"><?php echo "Nº Documento " ?> </td>
                        <td>: <?php echo $cliente["documento"]; ?></td>
                    </tr>
                    <tr>
                        <td class="tdLabel">Dirección</td>
                        <td>: <?php echo strtoupper($cliente["direccion"]); ?></td>
                    </tr>
                </table>
            </div>
            <div class="fechas">
                <table>
                    <tr>
                        <td class="tdLabel">FECHA EMISION </td>
                        <td>: <?php echo date("d-m-Y",strtotime($cotizacion['fecha_hora'])); ?></td>
                       
                    </tr>
                    
                    <tr>
                        <td class="tdLabel">Tipo de moneda </td>
                        <td>: <?php echo $config['moneda']; ?></td>
                    </tr>
                    <tr>
                        <td class="tdLabel">Valido hasta</td>
                        <td>: <?php
                                $fecha_actual = $cotizacion['fecha_hora']; 
                                $tiempo_valido = $cotizacion['tiempo_valido'];
                                echo date("d-m-Y",strtotime($fecha_actual."+ $tiempo_valido days "));  
                              ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div id="content">
            <table align="center" class="items">
                <thead>
                    <th><b>CANTIDAD</b></th>
                    <th>UM</th>
                    <!-- <th>Codigo</th> -->
                    <th class="noBorderLeft">DESCRIPCIÓN</th>
                  
                    <th>P/U</th>
                    <th>IMPORTE</th>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                    if (is_array($productos)):
                        foreach ($productos as $p):
                            ?>
                            <tr>
                                <td><?php echo $p['cantidad']; ?></td>
                                <td><?php echo $p['unidad']; ?></td>
                                <td ><?php echo strtoupper($p['nombre']); ?></td>
                          
                                <td><?php echo number_format($p['precio'], 2, '.', ''); ?></td>
                                <td width="10%"  > <?php echo number_format($p['precio']*$p['cantidad'], 2, '.', ''); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;?>
                </tbody>
            </table>
            <table  align="right" class="totales">
                <!--<tr class="resumen">
                    <td  style="padding-right: 5%;"><b>Descuento</b></td>
                    <td width="10%"><b>0.00</b></td>
                </tr>-->
                <tr class="resumen">
                    <td  style="padding-right: 5%;"><b>Sub-Total</b></td>
                    <td width="10%"><b><?php echo number_format($cotizacion["subtotal"], 2, '.', ''); ?></b></td>
                </tr>
                <tr class="resumen">
                    <td  style="padding-right: 5%;"><b>I.G.V</b></td>
                    <td width="10%"><b><?php echo number_format($cotizacion["total_impuestos"], 2, '.', ''); ?></b></td>
                </tr>
                <tr class="resumen">
                    <td  style="padding-right: 5%;"><b>Total</b></td>
                    <td width="10%"><b><?php echo number_format($cotizacion["total"], 2, '.', ''); ?></b></td>
                </tr>
            </table>
            <br><br><br>
            <div>
                <h4 align="center" style="text-transform: uppercase;"><?php echo "Importe en letras: "  .CifrasEnLetras::convertirNumeroEnLetras(number_format($cotizacion["total"], 2, ',', '.'),1, "nuevo sol","nuevos soles",true, "céntimo","",false)?></h4>

            </div>
        </div>
        <br>

        <div class="content">
            <div class="adicional">
                <h5>Adicional</h5>
                <p><?php echo "Usted fue atendido por: ".$usuario['nombres_y_apellidos']; ?></p>
        </div>
       
        <div class="content">
            <div class="observacion">
                <h5>Observación</h5>
                <p><?php echo "Banco: ".$config_cuentas['banco']; ?></p>
                <p><?php echo "Tipo de cuenta : ".$config_cuentas['tipo_cuenta']; ?></p>
                <p><?php echo "Número de cuenta: ".$config_cuentas['numero_cuenta']; ?></p>
            </div>
        </div>

        <div class="content">
            <div class="mensaje">
                <p>Representación impresa de la COTIZACIÓN, visita www.sistemausqay.com</p>
                <p>Autorizado por SUNAT</p>
            </div>
        </div>
        <br><br>

        <?php
            $qr = "".$config['ruc']." | 03** | ".$cotizacion['id']." | ".$cotizacion['total']." | ".date("d/m/Y")." | 1* | ".$cotizacion['id_cliente']." |";
        ?>

        <div class="qr-code">
          <img src="qrgen.php?data=<?php echo urlencode($qr); ?>" style="width:130px !important;"/>
        </div>
        
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <div align="center">
            <p><b>¡Gracias por su preferencia!</b></p>
        </div>
     
        <br><br>
    </div>
</body>

</body>
</html>
 