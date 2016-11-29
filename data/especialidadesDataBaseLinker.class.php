<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';


class EspecialidadesDataBaseLinker
{
	var $dbesp;
	
	function EspecialidadesDataBaseLinker()
	{
		$this->dbesp = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function EspecialidadesSelectNombre(){

		$query="SELECT IdEspecialidad, Especialidad FROM Especialidades WHERE habilitado = 1 ORDER BY Especialidad ASC;";

        try {

        	$this->dbesp->conectar();
        	$this->dbesp->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbesp->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbesp->querySize; $i++)
        {
            $profesionales = $this->dbesp->fetchRow($query);
            $ret[] = $profesionales;
        }

        $this->dbesp->desconectar();

        $selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
        	$selectstr .="<option value='".$ret[$i]['Especialidad']."'>".htmlspecialchars($ret[$i]['Especialidad'])."</option>";
        }

        return $selectstr;
	}

    function EspecialidadesSelect(){

        $query="SELECT IdEspecialidad, Especialidad FROM Especialidades WHERE habilitado = 1 ORDER BY Especialidad ASC;";

        try {

            $this->dbesp->conectar();
            $this->dbesp->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbesp->desconectar();
            return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbesp->querySize; $i++)
        {
            $profesionales = $this->dbesp->fetchRow($query);
            $ret[] = $profesionales;
        }

        $this->dbesp->desconectar();

        $selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
            $selectstr .="<option value='".$ret[$i]['IdEspecialidad']."'>".htmlspecialchars($ret[$i]['Especialidad'])."</option>";
        }

        return $selectstr;
    }


}
