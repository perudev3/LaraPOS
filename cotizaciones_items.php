<?php
require_once('globales_sistema.php');
if (!isset($_COOKIE['nombre_usuario'])) {
    header('Location: index.php');
}

$titulo_sistema = 'Katsu';
$id_cotizacion = $_GET['id'];
require_once('nucleo/include/MasterConexion.php');
$objconn = new MasterConexion();
$cotizacion = $objconn->consulta_arreglo("SELECT * FROM cotizacion where id = ".$id_cotizacion);
$detalle_cotizacion = $objconn->consulta_arreglo("SELECT dt.id, dt.id_producto, dt.cantidad, dt.precio FROM detalles_cotizacion dt INNER JOIN cotizacion co ON dt.id_coti = co.id where dt.id_coti = ".$id_cotizacion);
$cliente_coti = $objconn->consulta_arreglo("SELECT c.nombre from cliente c inner join cotizacion co on c.id = co.id_cliente inner join detalles_cotizacion dt on co.id = dt.id_coti where dt.id_coti = ".$id_cotizacion);
$usuario_coti = $objconn->consulta_arreglo("SELECT u.nombres_y_apellidos from usuario u inner join cotizacion co on u.id = co.id_usuario inner join detalles_cotizacion dt on co.id = dt.id_coti where dt.id_coti = ".$id_cotizacion);
$pro_cotizacion = $objconn->consulta_arreglo("SELECT p.nombre from producto p inner join detalles_cotizacion dt on p.id = dt.id_producto where dt.id_coti = ".$id_cotizacion);


$titulo_pagina = "Cotizacion ID: ".$id_cotizacion;
require_once('recursos/componentes/header.php');


$ultima = $objconn->consulta_arreglo("SELECT * FROM cotizacion ORDER BY id DESC limit 1");
?>

<?php //if (isset($_COOKIE['producto_precio'])): ?>
    <div class="container">
    <div class="row">
        <div class="col-md-12">
        <a href="#" id="showAll" class="btn btn-danger">
            Mostrar todas las cotizaciones
        </a>
        </div>
    </div>
    </div>
<?php //endif; ?>

<input type='hidden' id='id' name='id' value='0'/>
<input type='hidden' id='id_producto' name='id_producto' value='<?php echo $id_producto?>'/>
<div class='control-group col-md-4'>
    <label>SUBTOTAL</label>
    <input class='form-control' placeholder='<?php echo number_format($cotizacion['subtotal'],2); ?>' id='descripcion' name='descripcion'  disabled/>
</div>
<div class='control-group col-md-2'>
    <label>TOTAL IMPUESTOS</label>
    <input class='form-control' placeholder='<?php echo number_format($cotizacion['total_impuestos'],2);?>' id='cantidad' name='cantidad' disabled/>
</div>
<div class='control-group col-md-3'>
    <label>TOTAL</label>
    <input class='form-control' placeholder='<?php echo number_format($cotizacion['total'],2); ?>' type='number'  id='precio_compra' name='precio_compra' disabled />
</div>
<div class='control-group col-md-3'>
    <label>FECHA EMISION</label>
    <input class='form-control'  placeholder="<?php  echo date("d-m-y",strtotime($cotizacion['fecha_hora'])); ?>" id='fecha_emision' name='fecha_emision' disabled />
</div>
<div class='control-group col-md-4'>
    <label>VALIDO HASTA</label>
    <input class='form-control' placeholder='<?php
                                $fecha_actual = $cotizacion['fecha_hora']; 
                                $tiempo_valido = $cotizacion['tiempo_valido'];
                                echo date("d-m-Y",strtotime($fecha_actual."+ $tiempo_valido days "));  
                              ?>' disabled name='tiempo_valido' id='tiempo_valido'>
</div>
<div class='control-group col-md-4'>
    <label>CLIENTE </label>
    <input class='form-control' type='text' id='cliente' name='cliente' placeholder='<?php echo $cliente_coti['nombre']?>' disabled/>
</div>

<div class='control-group col-md-4'>
    <label>ATENDIDO POR </label>
    <input class='form-control' type='text' id='usuario' name='usuario' placeholder='<?php echo $usuario_coti['nombres_y_apellidos']?>' disabled/>
</div>



<input type='hidden' name='estado_fila' id='estado_fila' value='1'/>

</form>
<hr/>

<div class='contenedor-tabla'>
<table id='tb' class='display' cellspacing='0' width='100%'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>OPC</th>
            </tr>
        </thead>
        <tbody>
            
                    <tr>
                        <td><?php echo $detalle_cotizacion['id'];?></td>
                        <td><?php echo $pro_cotizacion['nombre']; ?></td>
                        <td><?php echo $detalle_cotizacion['cantidad']; ?></td>
                        <td><?php echo $detalle_cotizacion['precio'];?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-sm btn-default" href='#' onclick='sel(<?php echo $o["id"]; ?>)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                <a  class="btn btn-sm btn-default" href='#' onclick='del(<?php echo $o["id"]; ?>)'><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </div>
                        </td>
                    </tr>

            <?php
            $nombre_tabla = 'cotizaciones';
            require_once('recursos/componentes/footer.php');
            ?>
            
            <script>

                jQuery.fn.reset = function () {
                $(this).each (function() { this.reset(); });
                };

               
                
                $(document).ready(function() {

                    var tbl = $('#tb').DataTable({
                        responsive: true,
                        "order": [[ 0, "desc" ]],
                        dom: 'Bfrtip',
                        buttons: [
                            'excelHtml5',
                            'pdfHtml5'
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                        }
                    });


                    $("#progress").hide();

                    $('#showAll').on('click', function(e){
                        e.preventDefault();
                        location.href = 'cotizaciones.php';
                    })

                });



            </script>