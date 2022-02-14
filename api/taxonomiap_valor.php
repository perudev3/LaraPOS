<?php

header("Access-Control-Allow-Origin: *");
require_once('../nucleo/taxonomiap_valor.php');
$objtaxonomiap_valor = new taxonomiap_valor();

require_once('../nucleo/taxonomiap.php');
$objtaxonomiap = new taxonomiap();
$res=0;
if (isset($_GET['padre'])) {
    $id = $_GET['padre'];
    switch ($_GET['q']) {
        case 'valores':
            $res = $objtaxonomiap_valor->where(['id_taxonomiap', '=', $id]);
            echo json_encode($res);
			
            break;

        default:
            # code...
            break;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    switch ($_GET['q']) {
        case 'products':
            $valor = $_GET['valor'];
            $searchPrev=$_GET['valor']; 
            $codificacion=mb_detect_encoding($searchPrev,"ISO-8859-1,UTF-8");            
            $valor = iconv($codificacion,'UTF-8',$searchPrev);          
           
            $limit = 14;
            $page = $_GET['page'];
            $offset = $limit * $page;
         
            $where = " pt.id_taxonomiap = {$id} AND pt.valor = '{$valor}' AND pt.estado_fila = 1";


            $query = "SELECT p.id, p.nombre, p.precio_compra, p.precio_venta, p.incluye_impuesto, p.estado_fila
            FROM producto_taxonomiap pt
            INNER JOIN producto p on pt.id_producto = p.id 
            WHERE {$where}
            ORDER BY p.nombre LIMIT {$limit} OFFSET {$offset}";

            
            $res = $objtaxonomiap_valor->consulta_matriz($query);
            $whereStock = "";
            if (isset($_GET['almacen'])) {
                $almacen = $_GET['almacen'];
                $whereStock = " AND movimiento_producto.id_almacen = {$almacen} ";
            }

            foreach($res as $key=>$response){
                // var_dump($response["id"]);
                $queryStock = "SELECT sum(cantidad) as stock 
                FROM movimiento_producto 
                WHERE id_producto = ".$response["id"]." ".$whereStock ;
                
                $resStock = $objtaxonomiap_valor->consulta_arreglo($queryStock);
                
                //var_dump($resStock);
                $res[$key]["stock"] =  $resStock["stock"];

            }

        
            // $query = "SELECT producto.id,producto.nombre,producto.precio_compra, producto.precio_venta, producto.incluye_impuesto, producto.estado_fila, sum(movimiento_producto.cantidad) as stock 
            // FROM producto_taxonomiap 
            // inner join producto on producto_taxonomiap.id_producto = producto.id 
            // LEFT JOIN movimiento_producto on producto.id = movimiento_producto.id_producto 
            // WHERE  {$where}
            // GROUP BY movimiento_producto.id_producto ORDER BY producto.nombre LIMIT {$limit} OFFSET {$offset}";    

            // var_dump($query);
			
            // $res = $objtaxonomiap_valor->consulta_matriz($query);
            echo json_encode($res);
            break;

        default:
            # code...
			
            break;
    }
}
