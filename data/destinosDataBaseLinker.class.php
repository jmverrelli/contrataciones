<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';

class DestinosDataBaseLinker
{
	var $dbdest;
	
	function DestinosDataBaseLinker()
	{
		$this->dbdest = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function getDestinosSelect(){

		$query="SELECT IdDestino, Sector, Director FROM Destinos WHERE habilitado = 1;";

        try {

        	$this->dbdest->conectar();
        	$this->dbdest->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbdest->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbdest->querySize; $i++)
        {
            $profesionales = $this->dbdest->fetchRow($query);
            $ret[] = $profesionales;
        }

        $this->dbdest->desconectar();

        $selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
        	$selectstr .="<option value='".$ret[$i]['IdDestino']."'>".htmlspecialchars($ret[$i]['Sector'])."</option>";
        }

        return $selectstr;

    }

    function traerDestino($idCertificacion){

        $query = "SELECT dest.* FROM Certificaciones cert LEFT JOIN Destinos dest on (dest.idDestino = cert.idDestino) WHERE cert.IdCertificacion = $idCertificacion;";

        try {

            $this->dbdest->conectar();
            $this->dbdest->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbdest->desconectar();
            return false;
        }

        $ret = array();

        $destino = $this->dbdest->fetchRow($query);        

        $this->dbdest->desconectar();

        return $destino;

    }
}