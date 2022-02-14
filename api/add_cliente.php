<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
require_once('../nucleo/cliente.php');
require_once('./Helpers/Helper.php');

$objcliente = new cliente();

if (isset($_POST['op'])) {

    switch ($_POST['op']) {
        case 'add':

            $customer = $objcliente->searchDB($_POST['documento'], 'documento');

            $objcliente->setVar('nombre', Helper::test_input($_POST['nombre']));
            $objcliente->setVar('documento', Helper::test_input($_POST['documento']));
            $objcliente->setVar('direccion', Helper::test_input($_POST['direccion']));
            $objcliente->setVar('correo', Helper::test_input($_POST['correo']));
            $objcliente->setVar('tipo_cliente', Helper::test_input($_POST['tipo_cliente']));
            $objcliente->setVar('fecha_nacimiento', Helper::test_input($_POST['fecha_nacimiento']));
            $objcliente->setVar('estado_fila', "1");
            if ($customer) {
                $objcliente->setVar('id', $customer[0][0]);
                $id = $objcliente->updateDB();
                $customer = $objcliente->consulta_arreglo("SELECT id, nombre, documento, tipo_cliente, 'false' as ok FROM cliente WHERE id = {$customer[0][0]}");
                //echo json_encode(["ok" => false, "msg" => "Cliente fue modicado"]);
                //return;
            }else{
                $id = $objcliente->insertDB();
                $customer = $objcliente->consulta_arreglo("SELECT id, nombre, documento, tipo_cliente, 'true' as ok FROM cliente WHERE id = {$id}");
            }        

            echo json_encode($customer);

            break;

        default:
            # code...
            break;
    }
}
