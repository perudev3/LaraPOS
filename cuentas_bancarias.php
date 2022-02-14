<?php
require_once('globales_sistema.php');
if (!isset($_COOKIE['nombre_usuario'])) {
    header('Location: index.php');
}
$titulo_pagina = 'Cuentas Bancarias';
$titulo_sistema = 'Katsu';
require_once('recursos/componentes/header.php');
?>

<link rel="stylesheet" href="sweetalert2.min.css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                <input type='hidden' id='id' name='id' value='0'/>

                                
                <div class='control-group col-md-6'>
                        <label>Banco</label>
                        <select class='form-control' id='banco' name='banco' >
                            <option value='BCP'>Banco de Credito</option>
                            <option value='NACION'>Banco de la Nación</option>
                            <option value="SCOTIABANK">Scotiabank Perú</option>
                            <option value="BBVA">BBVA</option>
                            <option value='INTERBANK'>Interbank</option>
                            <option value="CONTINENTAL">Banco Continental</option>
                            <option value="CAJAPIURA">Caja Piura</option>
                        </select>
                </div>


                <div class='control-group col-md-6'>
                <label>Número de cuenta</label>
                    <input class='form-control' placeholder='Cuenta Bancaria' id='numero_cuenta' name='numero_cuenta' required/>
                </div>

                <div class='control-group col-md-6'>
                    <label>Código CCI</label>
                    <input class='form-control' placeholder='Código CCI' id='codigo_cci' name='codigo_cci' required/>
                </div>


                <div class='control-group col-md-6'>
                    <label>Tipo de Cuenta</label>
                    <select name='tipo_cuenta' id='tipo_cuenta' class='form-control'>
                        <option value="SOLES">Cuenta soles</option>
                        <option value="DOLARES">Cuenta dolares</option>
                    </select>
                </div>

                <input type='hidden' name='estado_fila' id='estado_fila' value='1'/>


                    <div class='control-group col-md-4'>
                        <p></p>
                        <button type='button' class='btn btn-primary' onclick='save()'>Guardar</button>
                        <button type='reset' class='btn'>Limpiar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <?php
    include_once('nucleo/cuentas_bancarias.php');
    $objListar = new cuentas_bancarias();
    $objListar = $objListar->listDB();
    ?>
    <div class='contenedor-tabla'>
        <table id='tb' class='display' cellspacing='0' width='100%'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Banco</th>
                    <th>Numero de Cuenta</th>
                    <th>Codigo CCI</th>
                    <th>Tipo de Cuenta</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($objListar)):
                    foreach ($objListar as $op):
                        ?>
                        <tr>
                            <td><?php echo $op['id']; ?></td>
                            <td><?php echo $op['banco']; ?></td>
                            <td><?php echo $op['numero_cuenta']; ?></td>
                            <td><?php echo $op['codigo_cci']; ?></td>
                            <td><?php echo $op['tipo_cuenta']; ?></td>
                            <td>
                                <a href='#' onclick='sel(<?php echo $op['id']; ?>)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                               
                                <a href='#' onclick='del(<?php echo $op['id']; ?>)'><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
            ?>

    <?php
            $nombre_tabla = 'cuentas_bancarias';
            require_once('recursos/componentes/footer.php');
    ?>
    <script src="sweetalert2.all.min.js"></script>

