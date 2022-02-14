<?php
	require_once('include/SuperClass.php');
	class cuentas_bancarias extends SuperClass{
		private $inputvars = array();
		private $inputname = 'cuentas_bancarias';

        function __construct($id=NULL,$banco=NULL,$numero_cuenta=NULL,$codigo_cci=NULL,$tipo_cuenta=NULL, $estado_fila = NULL)
		{
          $this->inputvars["id"] = $id;
		  $this->inputvars["banco"] = $banco;
		  $this->inputvars["numero_cuenta"] = $numero_cuenta;
		  $this->inputvars["codigo_cci"] = $codigo_cci;
		  $this->inputvars["tipo_cuenta"] = $tipo_cuenta;
          $this->inputvars["estado_fila"] = $estado_fila;
		  parent::__construct($this->inputvars,$this->inputname);
		}
	
            public function setId($newval){
                parent::setVar("id",$newval);
            }
          
            public function getId(){
                return parent::getVar("id");
            }

            public function setBanco($newval){
                parent::setVar("banco",$newval);
            }
        
        
            public function getBanco(){
                return parent::getVar("banco");
            }
        
            public function setNumeroCuenta($newval){
                parent::setVar("numero_cuenta", $newval);
            }
        
        
            public function getNumeroCuenta(){
               return parent::getVar("numero_cuenta");
            }
        
            public function setCodigoCci($newval){
                parent::setVar("codigo_cci", $newval);
            }
        
            public function getCodigoCci(){
               return parent::getVar("codigo_cci");
            }
        
            public function setTipoCuenta($newval){
                parent::setVar("tipo_cuenta", $newval);
            }
        
        
            public function getTipoCuenta(){
               return parent::getVar("tipo_cuenta");
            }

            public function setEstadoFila($newval) {
                parent::setVar("estado_fila", $newval);
            }
        
            public function getEstadoFila() {
                return parent::getVar("estado_fila");
            }
       
    }
?>