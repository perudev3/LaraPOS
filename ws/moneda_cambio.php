<?php

require '../vendor/autoload.php';

require_once('../nucleo/moneda_cambio.php');
$objmoneda_cambio = new moneda_cambio();

require_once('../nucleo/caja.php');
$objcaja = new caja();

require_once('../nucleo/turno.php');
$objturno = new turno();


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_REQUEST['op'])) {
    switch ($_REQUEST['op']) {
        case 'add':
            
            //$cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");
            $objmoneda_cambio->setVar('id',null);
         
            $objmoneda_cambio->setVar('moneda', $_REQUEST['moneda']);
            $objmoneda_cambio->setVar('venta', $_REQUEST['venta']);
            $objmoneda_cambio->setVar('compra', $_REQUEST['compra']);
            $objmoneda_cambio->setVar('fecha_cierre', $_REQUEST["fecha_cierre"]);
            $objmoneda_cambio->setVar('estado', $_REQUEST['estado']);
          
           
            echo json_encode($objmoneda_cambio->insertDB());
            break;
        
        case 'addin':
            //Obtenemos fecha cierre y turno
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";

            $turno_act = $objconn->consulta_arreglo($miturno);
            $cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");
            
            $objmoneda_cambio = new moneda_cambio();
            $objmoneda_cambio->setVar('id_caja', $_REQUEST['id_caja']);
            $objmoneda_cambio->setVar('monto', $_REQUEST['monto']);
            $objmoneda_cambio->setVar('tipo_movimiento', $_REQUEST['tipo_movimiento']);
            $objmoneda_cambio->setVar('fecha', date("Y-m-d H:i:s"));
            $objmoneda_cambio->setVar('fecha_cierre', $_REQUEST["fecha_cierre"]);
            $objmoneda_cambio->setVar('id_turno', $_REQUEST["id"]);
            $objmoneda_cambio->setVar('id_usuario', $_REQUEST['id_usuario']);
            $objmoneda_cambio->setVar('estado_fila',"1");
            
            $idmov = $objmoneda_cambio->insertDB();
            
            $idjb = $objconn->consulta_id("Insert into entrada_salida values(NULL,'".$_REQUEST["monto_abs"]."','".$_REQUEST["tipo"]."','".$_REQUEST["descripcion"]."','".$idmov."',1)");

            
            // $objconn->consulta_simple("Insert into tabla_impresion values(NULL,'".$_REQUEST['id_caja']."','".$idjb."','PAGO',0)");

            $objconn->consulta_simple("Insert into cola_impresion values(NULL, {$idjb}, 'PAG', {$_REQUEST["id_caja"]}, '', 1)");

            echo json_encode($idmov);
        break;

        case 'addpayment':
            //Obtenemos fecha cierre y turno
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";

            $turno_act = $objconn->consulta_arreglo($miturno);
            $cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");
            
            $objmovimientos_caja = new moneda_cambio();
            $objmovimientos_caja->setVar('id_caja', $_REQUEST['id_caja']);
            $objmovimientos_caja->setVar('monto', $_REQUEST['monto']);
            $objmovimientos_caja->setVar('tipo_movimiento', $_REQUEST['tipo_movimiento']."|".$_REQUEST['comprobante']);
            $objmovimientos_caja->setVar('fecha', date("Y-m-d H:i:s"));
            $objmovimientos_caja->setVar('fecha_cierre', $cierre_act["fecha_cierre"]);
            $objmovimientos_caja->setVar('id_turno', $turno_act["id"]);
            $objmovimientos_caja->setVar('id_usuario', $_REQUEST['id_usuario']);
            $objmovimientos_caja->setVar('estado_fila',"1");
            
            $idmov = $objmovimientos_caja->insertDB();
            
            $idjb = $objconn->consulta_id("Insert into entrada_salida values(NULL,'".$_REQUEST["monto_abs"]."','Pago Externo','".$_REQUEST["descripcion"]."','".$idmov."',1)");

            $objconn->consulta_simple("Insert into tabla_impresion values(NULL,'".$_REQUEST['id_caja']."','".$idjb."','PAGO',0)");

            $objconn->consulta_simple("Insert into cola_impresion values(NULL, {$idjb}, 'PAG', {$_REQUEST["id_caja"]}, '', 1)");

            echo json_encode($idmov);
        break;

        case 'editpayment':
            //Obtenemos fecha cierre y turno
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";

            $turno_act = $objconn->consulta_arreglo($miturno);
            $cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");

            $objmoneda_cambio->setVar('id', $_REQUEST['id']);
            $objmoneda_cambio->setVar('id_caja', $_REQUEST['id_caja']);
            $objmoneda_cambio->setVar('monto', $_REQUEST['monto']);
            $objmoneda_cambio->setVar('tipo_movimiento', $_REQUEST['tipo_movimiento']."|".$_REQUEST['comprobante']);
            $objmoneda_cambio->setVar('fecha', date("Y-m-d H:i:s"));
            $objmoneda_cambio->setVar('fecha_cierre', $cierre_act["fecha_cierre"]);
            $objmoneda_cambio->setVar('id_turno', $turno_act["id"]);
            $objmoneda_cambio->setVar('id_usuario', $_REQUEST['id_usuario']);
            $objmoneda_cambio->setVar('estado_fila',"1");
            
            $id = $objmoneda_cambio->updateDB();
            
            if( $id > 0 ){
                $objconn->consulta_simple("UPDATE entrada_salida set descripcion = '".$_REQUEST['descripcion']."' WHERE id_moneda_cambio = '".$_REQUEST['id']."'");
            }

            echo json_encode($id);

        break;

        case 'liquidar':
            //Obtenemos fecha cierre y turno
        
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";

            $turno_act = $objconn->consulta_arreglo($miturno);
            $cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");
            $fech_hora = date("Y-m-d H:i:s");

            $objmovimientos_caja = new moneda_cambio();
            $objmovimientos_caja->setVar('id_caja', $_COOKIE['id_caja']);
            $objmovimientos_caja->setVar('monto', $_REQUEST['total']);
            $objmovimientos_caja->setVar('tipo_movimiento', 'LIQ|PEN|EFECTIVO|PX');
            $objmovimientos_caja->setVar('fecha', $fech_hora );
            $objmovimientos_caja->setVar('fecha_cierre', $cierre_act["fecha_cierre"]);
            $objmovimientos_caja->setVar('id_turno', $turno_act["id"]);
            $objmovimientos_caja->setVar('id_usuario', $_COOKIE['id_usuario']);
            $objmovimientos_caja->setVar('estado_fila',"1");
            
            $idmov = $objmovimientos_caja->insertDB();
            
            $idjb = $objconn->consulta_id("Insert into entrada_salida values(NULL,'".$_REQUEST["total"]."','Liquidacion','Liquidacion ".$fech_hora."','".$idmov."',1)");

            $objconn->consulta_simple("Insert into tabla_impresion values(NULL,'".$_COOKIE['id_caja']."','".$idjb."','CAJA',0)");

            $objconn->consulta_simple("Insert into cola_impresion values(NULL, {$idjb}, 'PAG', {$_COOKIE["id_caja"]}, '', 1)");

            echo json_encode($idmov);
            
        break;

        case 'apertura':
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";
            $turno_act = $objconn->consulta_arreglo($miturno);
            
            $objconn->consulta_simple("Update configuracion set fecha_cierre = '".date("Y-m-d")."' where id=1");
            
            if(floatval($_REQUEST["inicial"])>0){
                $sql = "Insert into moneda_cambio values(NULL,'".$_REQUEST["id_caja"]."','".$_REQUEST["inicial"]."','OPEN|PEN|EFECTIVO','".date("Y-m-d H:i:s")."','".date("Y-m-d")."','".$turno_act["id"]."','".$_REQUEST["id_usuario"]."','1')";                     
                $objconn->consulta_simple($sql);
            }
            
            $objconn->consulta_simple("SET SQL_SAFE_UPDATES=0;");
            $objconn->consulta_simple("UPDATE tipo_cambio set compra = '".$_REQUEST["compra"]."', venta = '".$_REQUEST["venta"]."' where moneda_origen = 'USD' AND moneda_destino = 'PEN'");
            $objconn->consulta_simple("SET SQL_SAFE_UPDATES=1;");
            
            echo json_encode("1");
        break;

        case 'nueva_apertura':
            $fecha_tiempo = date("Y-m-d H:i:s", strtotime($_REQUEST['fecha']));
            $fecha = date("Y-m-d", strtotime($_REQUEST['fecha']));
            
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";
            $turno_act = $objconn->consulta_arreglo($miturno);
            
            $objconn->consulta_simple("Update configuracion set fecha_cierre = '".$fecha."' where id=1");
            
            if(floatval($_REQUEST["monto_inicial"])>0){
                $sql = "Insert into moneda_cambio values(NULL,'".$_COOKIE['id_caja']."','".$_REQUEST["monto_inicial"]."','OPEN|PEN|EFECTIVO','".$fecha_tiempo."','".$fecha."','".$turno_act["id"]."','".$_COOKIE["id_usuario"]."','1')";
                $objconn->consulta_simple($sql);
            }
            
            echo json_encode("1");
        break;

        case 'Mod_apertura':
            $objconn = new turno();
            $miturno = "Select * from turno where inicio <= '".date("H:i:s")."' AND fin >= '".date("H:i:s")."'";
            $turno_act = $objconn->consulta_arreglo($miturno);
            
            $objconn->consulta_simple("Update configuracion set fecha_cierre = '".date("Y-m-d")."' where id=1");
            
            if(floatval($_REQUEST["inicial"])>0){
                $sql = "UPDATE moneda_cambio SET monto = '".$_REQUEST["inicial"]."' WHERE id = ".$_REQUEST["id"]."";                     
                $objconn->consulta_simple($sql);
            }
            
            $objconn->consulta_simple("SET SQL_SAFE_UPDATES=0;");
            $objconn->consulta_simple("UPDATE tipo_cambio set compra = '".$_REQUEST["compra"]."', venta = '".$_REQUEST["venta"]."' where moneda_origen = 'USD' AND moneda_destino = 'PEN'");
            $objconn->consulta_simple("SET SQL_SAFE_UPDATES=1;");
            
            echo json_encode("1");
            break;
        
        

        case 'mod':

            $cierre_act = $objconn->consulta_arreglo("Select * from configuracion where id=1");
            $objmoneda_cambio->setVar('id', $_REQUEST['id']);
            $objmoneda_cambio->setVar('id_caja', $_REQUEST['id_caja']);
            $objmoneda_cambio->setVar('monto', $_REQUEST['monto']);
            $objmoneda_cambio->setVar('tipo_movimiento', $_REQUEST['tipo_movimiento']);
            $objmoneda_cambio->setVar('fecha', $_REQUEST['fecha']);
            $objmoneda_cambio->setVar('fecha_cierre', $cierre_act['fecha_cierre']);
            $objmoneda_cambio->setVar('id_turno', $_REQUEST['id_turno']);
            $objmoneda_cambio->setVar('id_usuario', $_REQUEST['id_usuario']);
            $objmoneda_cambio->setVar('estado_fila', $_REQUEST['estado_fila']);

            echo json_encode($objmoneda_cambio->updateDB());
            break;

        case 'del':
            $objmoneda_cambio->setVar('id', $_REQUEST['id']);
            echo json_encode($objmoneda_cambio->deleteDB());
            break;

        case 'get':
            $res = $objmoneda_cambio->searchDB($_REQUEST['id'], 'id', 1);
            if (is_array($res)) {
                $res[0]['id_caja'] = $objcaja->searchDB($res[0]['id_caja'], 'id', 1);
                $res[0]['id_caja'] = $res[0]['id_caja'][0];
                $res[0]['id_turno'] = $objturno->searchDB($res[0]['id_turno'], 'id', 1);
                $res[0]['id_turno'] = $res[0]['id_turno'][0];
                $res[0]['id_usuario'] = $objusuario->searchDB($res[0]['id_usuario'], 'id', 1);
                $res[0]['id_usuario'] = $res[0]['id_usuario'][0];
                echo json_encode($res[0]);
            } else {
                echo json_encode(0);
            }
        break;

        case 'get_':
            $res = $objmoneda_cambio->searchDB($_REQUEST['id'], 'id', 1);
            if (is_array($res)) {
                $res[0]['id_caja'] = $objcaja->searchDB($res[0]['id_caja'], 'id', 1);
                $res[0]['id_caja'] = $res[0]['id_caja'][0];
                $res[0]['id_turno'] = $objturno->searchDB($res[0]['id_turno'], 'id', 1);
                $res[0]['id_turno'] = $res[0]['id_turno'][0];
                $res[0]['id_usuario'] = $objusuario->searchDB($res[0]['id_usuario'], 'id', 1);
                $res[0]['id_usuario'] = $res[0]['id_usuario'][0];
                $res[0]['descripcion'] = $objturno->consulta_arreglo("SELECT * FROM entrada_salida WHERE id_moneda_cambio = '".$res[0]['id']."'");
                echo json_encode($res[0]);
            } else {
                echo json_encode(0);
            }
        break;

        case 'list':
            $res = $objmoneda_cambio->listDB();
            if (is_array($res)) {
                foreach ($res as &$act) {
                    $act['id_caja'] = $objcaja->searchDB($act['id_caja'], 'id', 1);
                    $act['id_caja'] = $act['id_caja'][0];
                    $act['id_turno'] = $objturno->searchDB($act['id_turno'], 'id', 1);
                    $act['id_turno'] = $act['id_turno'][0];
                    $act['id_usuario'] = $objusuario->searchDB($act['id_usuario'], 'id', 1);
                    $act['id_usuario'] = $act['id_usuario'][0];
                }
                echo json_encode($res);
            } else {
                echo json_encode(0);
            }
            break;

        case 'search':
            $res = $objmoneda_cambio->searchDB($_REQUEST['data'], $_REQUEST['value'], $_REQUEST['type']);
            if (is_array($res)) {
                foreach ($res as &$act) {
                    $act['id_caja'] = $objcaja->searchDB($act['id_caja'], 'id', 1);
                    $act['id_caja'] = $act['id_caja'][0];
                    $act['id_turno'] = $objturno->searchDB($act['id_turno'], 'id', 1);
                    $act['id_turno'] = $act['id_turno'][0];
                    $act['id_usuario'] = $objusuario->searchDB($act['id_usuario'], 'id', 1);
                    $act['id_usuario'] = $act['id_usuario'][0];
                }
                echo json_encode($res);
            } else {
                echo json_encode(0);
            }
            break;

        case 'move':
            $objmoneda_cambio = new moneda_cambio();
            $filtro = $objmoneda_cambio->consulta_matriz("SELECT mc.`id`, c.`nombre`, mc.`monto`, `tipo_movimiento`, descripcion, `fecha`, `fecha_cierre`, `id_turno`, `id_usuario` 
                FROM `moneda_cambio` mc 
                INNER JOIN caja c ON mc.id_caja = c.id 
                INNER JOIN entrada_salida es ON mc.id = es.id_moneda_cambio
                WHERE tipo_movimiento like '".$_REQUEST['mov']."'");

            if (is_array($filtro)) {
                echo json_encode($filtro);
            } else {
                echo json_encode(0);
            }
        break;

        case 'josef':

            $data = [];

            $meses = array(null, "Enero","Febrero","Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

            $movimientos_caja = [];
            $egresos = [];

            foreach ($meses as $k => $mes) {
                
                if( !is_null($mes) ){
                    
                    $movimiento = $objcaja->consulta_arreglo("SELECT FORMAT(SUM(monto),2) AS y, MONTH(fecha) as label FROM moneda_cambio where tipo_movimiento NOT LIKE '%PX%' AND YEAR(fecha) = YEAR(NOW()) AND MONTH(fecha) = '".$k."' GROUP by MONTH(fecha)");
                    
                    $egreso = $objcaja->consulta_arreglo("SELECT SUM(ABS(monto)) as y, MONTH(fecha) as label FROM moneda_cambio where tipo_movimiento LIKE '%PX%' AND YEAR(fecha) = YEAR(NOW()) AND MONTH(fecha) = '".$k."' GROUP by MONTH(fecha)");
                    
                    if( $movimiento ){
                        $movimientos_caja[] = $movimiento;
                    }else{
                        $movimientos_caja[] = array( "y" => 0 , "label" => $k );
                    }

                    if( $egreso ){
                        $egresos[] = $egreso;
                    }else{
                        $egresos[] = array( "y" => 0 , "label" => $k );
                    }
                    
                    
                }
            }
            
            $data['movimientos_caja'] = $movimientos_caja;
            $data['egresos'] = $egresos;
            
            
            foreach ($data['movimientos_caja'] as $key => &$mov) {
                $mov['label'] = $meses[$mov['label']];
                $mov['indexLabel'] = $mov['y'];
            }

            foreach ($data['egresos'] as $key => &$eg) {
                $eg['label'] = $meses[$eg['label']];
                $eg['indexLabel'] = $eg['y'];
            }
            

            echo json_encode($data);
            

        break;

        case 'show_liq':
            $liquidaciones = $objmoneda_cambio->consulta_matriz(
            "SELECT * FROM moneda_cambio WHERE tipo_movimiento like '%LIQ%' AND fecha_cierre 
            BETWEEN '".$_REQUEST['starts']."' AND '".$_REQUEST['ends']."'");

            if( is_array($liquidaciones) ){

                foreach ($liquidaciones as $key => &$liquidacion) {
                    $liquidacion['caja'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM caja WHERE id = '".$liquidacion['id_caja']."'");
                    $liquidacion['usuario'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM usuario WHERE id = '".$liquidacion['id_usuario']."'");
                }

            }
        
            echo json_encode($liquidaciones);

        break;

        case 'show_cuadre':
            
            $saldo_anterior = $objmoneda_cambio->consulta_arreglo("SELECT ROUND(SUM(monto), 2) AS saldo FROM moneda_cambio WHERE tipo_movimiento LIKE '%PX%' AND fecha_cierre < '{$_REQUEST["starts"]}'")[0];

            $movimientos = $objmoneda_cambio->consulta_matriz(
            "SELECT * FROM moneda_cambio WHERE tipo_movimiento like '%PX%' AND fecha_cierre 
            BETWEEN '".$_REQUEST['starts']."' AND '".$_REQUEST['ends']."'");

            if( is_array($movimientos) ){

                foreach ($movimientos as $key => &$movimiento) {
                    $movimiento['caja'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM caja WHERE id = '".$movimiento['id_caja']."'");
                    $movimiento['usuario'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM usuario WHERE id = '".$movimiento['id_usuario']."'");
                    $movimiento['descripcion'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM entrada_salida WHERE id_moneda_cambio = '".$movimiento['id']."'");
                    $tipo_mov = explode("|", $movimiento['tipo_movimiento']);
                    if( $tipo_mov[0] == 'EG' ){
                        $movimiento['tipo'] = array("class" => "danger", "tipo" => "EGRESO");
                    }else if(  $tipo_mov[0] == 'LIQ' ){
                        $movimiento['tipo'] = array("class" => "success", "tipo" => "LIQUIDACION");
                    }else if(  $tipo_mov[0] == 'ADV' ){
                        $movimiento['tipo'] = array("class" => "danger", "tipo" => "ADELANTO");
                    }
                    $movimiento['saldo_anterior'] = $saldo_anterior;
                }

            }
            echo json_encode($movimientos);

        break;


        case 'import':
           
            $alphas = range('A', 'D');

            $liquidaciones = $objmoneda_cambio->consulta_matriz(
                "SELECT * FROM moneda_cambio WHERE tipo_movimiento like '%LIQ%' AND fecha_cierre 
                BETWEEN '".$_REQUEST['starts_']."' AND '".$_REQUEST['ends_']."'");
    
                if( is_array($liquidaciones) ){
    
                    foreach ($liquidaciones as $key => &$liquidacion) {
                        $liquidacion['caja'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM caja WHERE id = '".$liquidacion['id_caja']."'");
                        $liquidacion['usuario'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM usuario WHERE id = '".$liquidacion['id_usuario']."'");
                    }
    
                }
           
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue($alphas[0].'1', 'Fecha');
            $sheet->setCellValue($alphas[1].'1', 'Caja');
            $sheet->setCellValue($alphas[2].'1', 'Usuario');
            $sheet->setCellValue($alphas[3].'1', 'Monto');
            
            $indice = 2;
            if( is_array($liquidaciones) ){    
                foreach ($liquidaciones as $k => $value) {
                    $sheet->setCellValue($alphas[0].$indice, $value['fecha']);
                    $sheet->setCellValue($alphas[1].$indice, $value['caja']['nombre']);
                    $sheet->setCellValue($alphas[2].$indice, $value['usuario']['nombres_y_apellidos']);
                    $sheet->setCellValue($alphas[3].$indice, $value['monto']);
                    $indice++;
                }
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'liquidaciones';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output'); // download file

        break;

        case 'import_cuadre':
           
            $alphas = range('A', 'F');

            $movimientos = $objmoneda_cambio->consulta_matriz(
                "SELECT * FROM moneda_cambio WHERE tipo_movimiento like '%PX%' AND fecha_cierre 
                BETWEEN '".$_REQUEST['starts_']."' AND '".$_REQUEST['ends_']."'");
        
                if( is_array($movimientos) ){
        
                    foreach ($movimientos as $key => &$movimiento) {
                        $movimiento['caja'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM caja WHERE id = '".$movimiento['id_caja']."'");
                        $movimiento['usuario'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM usuario WHERE id = '".$movimiento['id_usuario']."'");
                        $movimiento['descripcion'] = $objmoneda_cambio->consulta_arreglo("SELECT * FROM entrada_salida WHERE id_moneda_cambio = '".$movimiento['id']."'");
                        $tipo_mov = explode("|", $movimiento['tipo_movimiento']);
                        if( $tipo_mov[0] == 'EG' ){
                            $movimiento['tipo'] = array("class" => "danger", "tipo" => "EGRESO");
                        }else if(  $tipo_mov[0] == 'LIQ' ){
                            $movimiento['tipo'] = array("class" => "success", "tipo" => "LIQUIDACION");
                        }else if(  $tipo_mov[0] == 'ADV' ){
                            $movimiento['tipo'] = array("class" => "danger", "tipo" => "ADELANTO");
                        }
                    }
        
                }
           
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue($alphas[0].'1', 'Fecha');
            $sheet->setCellValue($alphas[1].'1', 'Caja');
            $sheet->setCellValue($alphas[2].'1', 'Usuario');
            $sheet->setCellValue($alphas[3].'1', 'Descripcion');
            $sheet->setCellValue($alphas[4].'1', 'Tipo');
            $sheet->setCellValue($alphas[5].'1', 'Monto');
            
            $indice = 2;
            if( is_array($movimientos) ){    
                foreach ($movimientos as $k => $value) {
                    $sheet->setCellValue($alphas[0].$indice, $value['fecha']);
                    $sheet->setCellValue($alphas[1].$indice, $value['caja']['nombre']);
                    $sheet->setCellValue($alphas[2].$indice, $value['usuario']['nombres_y_apellidos']);
                    $sheet->setCellValue($alphas[3].$indice, $value['descripcion']['descripcion']);
                    $sheet->setCellValue($alphas[4].$indice, $value['tipo']['tipo']);
                    $sheet->setCellValue($alphas[5].$indice, abs($value['monto']));
                    $indice++;
                }
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'cuadre_general';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output'); // download file

        break;

        case 'consulta':
            $medios = $objmoneda_cambio->consulta_matriz("
                SELECT * FROM venta
                ");
            $resta = 0;


            // echo json_encode($medios);
            for($i=0; $i<count($medios); $i++){
                $fecha_new = date('Y-m-d', strtotime($medios[$i]['fecha_hora']));

                $mod = $objmoneda_cambio->consulta_simple("UPDATE venta SET fecha_cierre = '".$fecha_new."' WHERE id = ".$medios[$i]["id"]);

                echo $mod;
            }
            // $medios = $objmoneda_cambio->consulta_matriz("
            //     SELECT * FROM venta_medio_pago
            //     ");
            // $resta = 0;
            // for($i=0; $i<count($medios); $i++){
            //     $resta = floatval($medios[$i]["monto"] - $medios[$i]["vuelto"]);


            //     $mc = $objmoneda_cambio->consulta_arreglo("
            //         SELECT * FROM moneda_cambio WHERE tipo_movimiento like '%|".$medios[$i]["id"]."'
            //     ");

            //     $mod = $objmoneda_cambio->consulta_simple("
            //         UPDATE moneda_cambio SET monto = ".$resta." WHERE tipo_movimiento like '%|".$medios[$i]["id"]."'");

            //     echo $mod;
            // }

        break;

    }
}?>