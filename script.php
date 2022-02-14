<?php
require_once('globales_sistema.php');
if (!isset($_COOKIE['nombre_usuario'])) {
    header('Location: index.php');
}
$titulo_pagina = 'Servicios';
$titulo_sistema = 'Katsu';
require_once('recursos/componentes/header.php');
require_once('nucleo/include/MasterConexion.php');

$objconn = new MasterConexion();

echo "aqui bienvenido al script";

$tipos = $objconn->consulta_matriz("SELECT * FROM taxonomiap_valor where id_taxonomiap = 2");

// var_dump($tipos);


$conn = mysqli_connect('localhost', 'root', '', 'restaurantes');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


foreach ($tipos as $value) {
    $sql = "INSERT INTO `restaurantes`.`tipos`(`pkTipo`,`descripcion`,`pkCategoria`,`estado`,`imagen`)
    VALUES(null, '{$value["valor"]}',1,0,null)";
    if ($conn->query($sql) === TRUE) {
        echo "Add Tipo \n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // $tiposResta = $conn->query();
}



$taxo = $objconn->consulta_matriz("SELECT DISTINCT id_producto, valor FROM producto_taxonomiap WHERE id_taxonomiap = 2");

foreach ($taxo as $value) {
    // var_dump($value["id_producto"]);

    $sqlExisteTipo = "SELECT * FROM `restaurantes`.`tipos` WHERE descripcion = '{$value["valor"]}'";
    $taxonomiasp = mysqli_query($conn, $sqlExisteTipo);

    if (mysqli_num_rows($taxonomiasp) > 0) {

        while ($rowtax = mysqli_fetch_array($taxonomiasp)) {
            $pkTipo = $rowtax["pkTipo"];
        }

        $informacionDeProductoPOS = $objconn->consulta_arreglo("SELECT nombre, precio_venta FROM producto WHERE id = {$value["id_producto"]}");

        $sqlUltimoPkPlato = "SELECT pkPlato FROM `restaurantes`.`plato` ORDER BY pkPlato DESC LIMIT 1";

        $lastIndexPkPlato = mysqli_query($conn, $sqlUltimoPkPlato);

        $pkPlato = "PL00000";
        while ($rowPkPlato = mysqli_fetch_array($lastIndexPkPlato)) {
            $pkPlato = $rowPkPlato["pkPlato"];
        }
        $explodePkPlato = explode("PL", $pkPlato);
        $correlativo = $explodePkPlato[1] + 1;
        $pk = "PL" . str_pad($correlativo, 4, "0", STR_PAD_LEFT);
        // var_dump();

        $sqlPlatoNuevo = "INSERT INTO `restaurantes`.`plato`
        (`pkPlato`, `descripcion`, `estado`, `pktipo`, `precio_venta`, `pkSucursal_`, `stock`, `pkCategoria`, `personal`, `mediano`, `familiar`, `stockMinimo`, `isAccesoRapido`)
        VALUES
        ('{$pk}' , '{$informacionDeProductoPOS['nombre']}' , 0 , {$pkTipo} , {$informacionDeProductoPOS["precio_venta"]} , 'SU009' , 0, 1 , 0.00, 0.00,  0.00, 0,  0)";

        if ($conn->query($sqlPlatoNuevo) === TRUE) {
            var_dump("add New Plato \n");
        } else {
            var_dump("Error: " . $sqlPlatoNuevo . "<br> " . $conn->error);
        }

        $platoCodigoSunat = $objconn->consulta_arreglo("SELECT valor FROM producto_taxonomiap WHERE id_taxonomiap = -1 AND id_producto = {$value["id_producto"]}");

        $explodeCodSunat = explode(" ", $platoCodigoSunat["valor"]);

        $sqlPlatoCodSunat = "INSERT INTO `restaurantes`.`plato_codigo_sunat`
        (`id`, `id_plato`, `id_codigo_sunat`, `id_tipo_impuesto`, `tipo_articulo`) VALUES (null, '{$pk}', '{$explodeCodSunat[0]}', 1, 1);
        ";

        if ($conn->query($sqlPlatoCodSunat) === TRUE) {
            var_dump("Add PlatoSunat \n");
        } else {
            var_dump("Error: " . $sqlPlatoCodSunat . "<br> " . $conn->error);
        }
        // var_dump($sqlPlatoNuevo);

    }
}
