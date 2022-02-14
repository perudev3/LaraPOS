<?php

require_once('include/SuperClass.php');

class moneda_cambio extends SuperClass
{

    private $inputvars = array();
    private $inputname = 'moneda_cambio';

    function __construct($id = NULL, $moneda = NULL, $venta = NULL, $compra = NULL, $fecha_cierre = NULL, $estado = NULL)
    {
       // id	moneda	compra	venta	fecha_cierre	estado
        $this->inputvars["id"] = $id;
        $this->inputvars["moneda"] = $moneda;
        $this->inputvars["venta"] = $venta;
        $this->inputvars["compra"] = $compra;
        $this->inputvars["fecha_cierre"] = $fecha_cierre;
        $this->inputvars["estado"] = $estado;

        parent::__construct($this->inputvars, $this->inputname);
    }

    public function setId($newval)
    {
        parent::setVar("id", $newval);
    }

    public function getId()
    {
        return parent::getVar("id");
    }

    public function setMoneda($newval)
    {
        parent::setVar("moneda", $newval);
    }

    public function getMoneda()
    {
        return parent::getVar("moneda");
    }

    public function setVenta($newval)
    {
        parent::setVar("venta", $newval);
    }

    public function getCompra()
    {
        return parent::getVar("compra");
    }
    public function setCompra($newval)
    {
        parent::setVar("compra", $newval);
    }

    public function getVenta()
    {
        return parent::getVar("venta");
    }
    public function setFecha_Cierre($newval)
    {
        parent::setVar("fecha_cierre", $newval);
    }

    public function getFecha_Cierre()
    {
        return parent::getVar("fecha_cierre");
    }


    public function setEstadoFila($newval)
    {
        parent::setVar("estado", $newval);
    }

    public function getEstadoFila()
    {
        return parent::getVar("estado");
    }
}
