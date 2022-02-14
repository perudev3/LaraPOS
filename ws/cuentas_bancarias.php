<?php

require_once('../nucleo/cuentas_bancarias.php');
$objcuentasbancarias = new cuentas_bancarias();

if (isset($_POST['op'])) {
    switch ($_POST['op']) {
        case 'add':
            $objcuentasbancarias->setVar('id', $_POST['id']);
            $objcuentasbancarias->setVar('banco', $_POST['banco']);
            $objcuentasbancarias->setVar('numero_cuenta', $_POST['numero_cuenta']);
            $objcuentasbancarias->setVar('codigo_cci', $_POST['codigo_cci']);
            $objcuentasbancarias->setVar('tipo_cuenta', $_POST['tipo_cuenta']);
            $objcuentasbancarias->setVar('estado_fila', $_POST['estado_fila']);

            echo json_encode($objcuentasbancarias->insertDB());
            break;

        case 'mod':
            $objcuentasbancarias->setVar('id', $_POST['id']);
            $objcuentasbancarias->setVar('banco', $_POST['banco']);
            $objcuentasbancarias->setVar('numero_cuenta', $_POST['numero_cuenta']);
            $objcuentasbancarias->setVar('codigo_cci', $_POST['codigo_cci']);
            $objcuentasbancarias->setVar('tipo_cuenta', $_POST['tipo_cuenta']);
            $objcuentasbancarias->setVar('estado_fila', $_POST['estado_fila']);
            echo json_encode($objcuentasbancarias->updateDB());
            break;

        case 'del':
            $objcuentasbancarias->setVar('id', $_POST['id']);
            echo json_encode($objcuentasbancarias->deleteDB());
            break;
        
        case 'list':
            $res = $objcuentasbancarias->listDB();
            if (is_array($res)) {
                echo json_encode($res);
            } else {
                echo json_encode(0);
            }
            break;
        
        case 'get':
            $res = $objcuentasbancarias->searchDB($_POST['id'], 'id', 1);
            if (is_array($res)) {
                echo json_encode($res[0]);
            } else {
               echo json_encode(0);
            }
            break;
        
    }
}

?>