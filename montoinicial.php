<?php
    require_once 'nucleo/include/MasterConexion.php';
    $objcon = new MasterConexion();
    //Obtenemos cierre actual


    $fecha_actual = date("Y-m-d");
    $cnf = $objcon->consulta_arreglo("SELECT * FROM `movimiento_caja` WHERE fecha_cierre = '".$fecha_actual."'  AND id_caja = ".$_COOKIE['id_caja']." AND tipo_movimiento = 'OPEN|PEN|EFECTIVO' order by fecha DESC limit 1");
    if(!empty($cnf['monto'])){
        if($cnf['monto'] > 0){
            $inicial = $cnf['monto'];
            $opc = $cnf['id'];
        }else{
            $inicial = 0;
            $opc = 0;
        }
    }else{
            $inicial = 0;
            $opc = 0;
        }
    
    
    $cambio = $objcon->consulta_arreglo("Select * from tipo_cambio where moneda_origen = 'USD' AND moneda_destino = 'PEN'");
    
    // if(strtotime($fecha_actual)===strtotime($fecha_cierre)){
    //     header('Location: inicio.php');
    // }
?>
<?php
require_once('globales_sistema.php');
if (!isset($_COOKIE['nombre_usuario'])) {
    header('Location: index.php');
}
$titulo_pagina = 'Apertura de Caja';
$titulo_sistema = 'LaraPOS';
require_once('recursos/componentes/header_open.php');
?>
<!-- 
<div class="panel panel-primary" id='panel_pregunta' style="margin: 10px;">
    <div class="panel-heading">
        <h3 class="panel-title">Apertura de Caja Requerida</h3>
    </div>
    <div class="panel-body" style="padding: 10px;">
        <center>
            <h1>La fecha actual es diferente a la fecha de cierre, ¿Deseas aperturar un nuevo día?</h1>
        </center>
        <center>
            <h1>Ultimo cierre: <b><?php echo $fecha_cierre;?></b></h1>
        </center>
        <h1></h1>
        <center>
            <button type='button' class='btn btn-success btn-lg' onclick="aperturar()">Aperturar</button>
            <button type='button' class='btn btn-default btn-lg' onclick="cancelar()" style="margin-left: 10px;">Cancelar</button>
        </center>
    </div>
</div> -->
<div class="panel panel-primary" id='panel_inicial' style="margin: 10px;">
    <div class="panel-heading">
        <h3 class="panel-title">Datos Apertura</h3>
    </div>
    <div class="panel-body">
        <div class='control-group col-md-12'>
            <label>Monto Inicial</label>
            <input class='form-control' type='number' step='1.00' id='inicial' value="<?php echo $inicial; ?>"/><input class='form-control' type='hidden' step='1.00' id='inicialOpc' value="<?php echo $opc; ?>"/>
        </div>
        <div class='control-group col-md-12'>
            <label>Compra Dolar</label>
            <input class='form-control' type='number' step='1.00' id='compra' value="<?php echo $cambio["compra"];?>"/>
        </div>
        <div class='control-group col-md-12'>
            <label>Venta Dolar</label>
            <input class='form-control' type='number' step='1.00' id='venta' value="<?php echo $cambio["venta"];?>"/>
        </div>
        <div class='control-group col-md-12' style="margin-top: 10px;">        
        <center>
            <button type='button' class='btn btn-success btn-lg' onclick="guardar()">Aperturar</button>
            <button type='button' class='btn btn-default btn-lg' onclick="cancelar()" style="margin-left: 10px;">Cancelar</button>
        </center>
        </div>
    </div>
</div>
</form>
<hr/>
<div class='contenedor-tabla'>
<?php
$nombre_tabla = 'dummy';
require_once('recursos/componentes/footer.php');
?>
<script>
    function aperturar(){
        $("#panel_pregunta").hide("fast");
        $("#panel_inicial").show("fast");
    }
    
    function cancelar(){
        location.href = "inicio.php";
    }
    
    function guardar(){
        document.cookie = "apertura=0";
        var id_caja = <?php echo $_COOKIE["id_caja"];?>;
        var id_usuario = <?php echo $_COOKIE["id_usuario"];?>;
        var opc = $("#inicialOpc").val();
        var inicial = $("#inicial").val();
        var compra = $("#compra").val();
        var venta = $("#venta").val();
        if(compra <= 0){
            alert("La Compra Dolar debe ser mayor a cero");
        }else if(venta <= 0){
            alert("La Venta Dolar debe ser mayor a cero");
        }else{ 
            if(opc == 0){
                $.post('ws/movimiento_caja.php', {op: 'apertura',id_caja:id_caja,inicial:inicial,id_usuario:id_usuario,compra:compra,venta:venta}, function(data) {
                    if(data !== 0){
                        location.href = "cierre_caja.php";
                        
                    }
                }, 'json');
            }else{
                $.post('ws/movimiento_caja.php', {op: 'Mod_apertura',inicial:inicial,id:opc, compra:compra,venta:venta}, function(data) {
                    if(data !== 0){
                        location.href = "cierre_caja.php";
                        
                    }
                }, 'json');
            }
        }
        

        
    }
    
</script>