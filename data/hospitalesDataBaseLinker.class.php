<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';

class HospitalesDataBaseLinker
{
	var $dbhosp;
	
	function HospitalesDataBaseLinker()
	{
		$this->dbhosp = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function getHospitalesSelect(){

		$query="SELECT IdHospital, Hospital FROM Hospitales";

        try {

        	$this->dbhosp->conectar();
        	$this->dbhosp->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbhosp->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbhosp->querySize; $i++)
        {
            $hospitales = $this->dbhosp->fetchRow($query);
            $ret[] = $hospitales;
        }

        $this->dbhosp->desconectar();

        $selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
        	$selectstr .="<option value='".$ret[$i]['IdHospital']."'>".$ret[$i]['Hospital']."</option>";
        }

        return $selectstr;

    }

    function getArrayHosp(){

        $query="SELECT IdHospital, Hospital FROM Hospitales";

        try {

            $this->dbhosp->conectar();
            $this->dbhosp->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbhosp->desconectar();
            return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbhosp->querySize; $i++)
        {
            $hospitales = $this->dbhosp->fetchRow($query);
            $ret[] = $hospitales;
        }

        $this->dbhosp->desconectar();

        return $ret;
    }


}