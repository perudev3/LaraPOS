<?php

if (!isset($_COOKIE['nombre_usuario'])) {
    header('Location: index.php');
}
$titulo_sistema = 'LaraPOS';
//require_once('recursos/componentes/nav.php');
header('Location: pantalla_teclado.php');
?>