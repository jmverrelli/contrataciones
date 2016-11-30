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

    function agregarEspecialidad($data){

        $query="INSERT INTO Especialidades (Especialidad) VALUES ('".$data['especialidadAgre']."');";

        $response = new stdClass();

         try
        {   
            $this->dbesp->conectar();
            $this->dbesp->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbesp->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;   
    }


     function getEspecialidadesJson($page, $rows, $filters, $sidx, $sord){

        $response = new stdClass();
        $this->dbesp->conectar();

        $especialidadArray = $this->getEspecialidades($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadEspecialidades($filters) / $rows);
        $response->records = $this->getCantidadEspecialidades($filters);

        $this->dbesp->desconectar();

        for ($i=0; $i < count($especialidadArray) ; $i++) 
        {
            $certificacion = $especialidadArray[$i];
            //id de fila
            $response->rows[$i]['id'] = $certificacion['IdEspecialidad']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $certificacion['IdEspecialidad'];
            $row[] = $certificacion['Especialidad'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdEspecialidad']= 'IdEspecialidad';
        $response->userdata['Especialidad']= 'Especialidad';
        $response->userdata['Nro Conevnio']= 'Nro Conevnio';
        $response->userdata['myac'] = '';

        return json_encode($response);

    }

    function getEspecialidades($page, $rows, $filters, $sidx, $sord){

        $where = "";

        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=$filters['rules'][$i]['field']." REGEXP '".$filters['rules'][$i]['data']."' ";
            }
        }

        $sort = "ORDER BY ".$sidx." ".$sord;

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    esp.IdEspecialidad,
                    esp.Especialidad
                FROM 
                    Especialidades esp
                WHERE
                    esp.IdEspecialidad IS NOT NULL AND esp.habilitado = 1 ".$where." ".$sort;
        
        //$_SESSION['consultCertificaciones'] = $query;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbesp->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbesp->querySize; $i++)
        {
            $reclamo = $this->dbesp->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
    }

    private function getCantidadEspecialidades($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Especialidades esp WHERE esp.habilitado = 1";
        
        $this->dbesp->ejecutarQuery($query);
        $result = $this->dbesp->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }


        function eliminarEspecialidad($id){

        $query  = "UPDATE Especialidades SET habilitado = 0 WHERE IdEspecialidad = $id;";
        $response = new stdClass();
        try
        {   
            $this->dbesp->conectar();
            $this->dbesp->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbesp->desconectar();
            return $response;   
        }

        $this->dbesp->desconectar();

        $response->message = "Se ha elminado la Especialidad";
        $response->ret = true;

        return $response;
    }

}
