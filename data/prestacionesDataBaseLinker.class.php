<?php 

include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';

class PrestacionesDataBaseLinker
{
	var $dbprest;

	function PrestacionesDataBaseLinker()
	{
		$this->dbprest = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function traerPrestaciones(){

		$query = "SELECT IdPrestacion, Prestacion, Valor FROM Prestaciones;";

        try {

        	$this->dbprest->conectar();
        	$this->dbprest->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbprest->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbprest->querySize; $i++)
        {
            $prestaciones = $this->dbprest->fetchRow($query);
            $ret[] = $prestaciones;
        }

        $this->dbprest->desconectar();

        return $ret;

	}


	function traerPrestacionesSelect(){

		$query = "SELECT IdPrestacion, Prestacion FROM Prestaciones WHERE habilitado = 1 ORDER BY Prestacion ASC;";

        try {

        	$this->dbprest->conectar();
        	$this->dbprest->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbprest->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbprest->querySize; $i++)
        {
            $prestaciones = $this->dbprest->fetchRow($query);
            $ret[] = $prestaciones;
        }

        $this->dbprest->desconectar();

      	$selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
        	$selectstr .="<option value='".$ret[$i]['IdPrestacion']."'>".$ret[$i]['Prestacion']."</option>";
        }

        return $selectstr;

	}

    function traerPrestacionesJson(){

        $query = "SELECT IdPrestacion, Prestacion FROM Prestaciones WHERE habilitado = 1 ORDER BY Prestacion ASC;";

        try {

            $this->dbprest->conectar();
            $this->dbprest->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbprest->desconectar();
            return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbprest->querySize; $i++)
        {
            $prestaciones = $this->dbprest->fetchRow($query);
            $ret[] = $prestaciones;
        }

        $this->dbprest->desconectar();

        return json_encode($ret);

    }

	function traerValor($data){

		$query= "SELECT Valor FROM Prestaciones WHERE IdPrestacion = ".$data['prestacion'].";";

		 try {

        	$this->dbprest->conectar();
        	$this->dbprest->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbprest->desconectar();
        	return false;
        }

        $ret = array();

        $prestaciones = $this->dbprest->fetchRow($query);        

        $this->dbprest->desconectar();

        return $prestaciones['Valor'];


	}

    function agregarNuevaPrestacion($data){

        $query = "INSERT INTO Prestaciones (Prestacion, Valor) VALUES ('".$data['prestacion']."', TRUNCATE(".$data['valor'].",2));";

        $response = new stdClass();
        try
        {   
            $this->dbprest->conectar();
            $this->dbprest->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbprest->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;
    }



     function getPrestacionesJson($page, $rows, $filters, $sidx, $sord){

        $response = new stdClass();
        $this->dbprest->conectar();

        $prestarray = $this->getPrestaciones($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadPrestaciones($filters) / $rows);
        $response->records = $this->getCantidadPrestaciones($filters);

        $this->dbprest->desconectar();

        for ($i=0; $i < count($prestarray) ; $i++) 
        {
            $certificacion = $prestarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $certificacion['IdPrestacion']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $certificacion['IdPrestacion'];
            $row[] = $certificacion['Prestacion'];
            $row[] = $certificacion['Valor'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdPrestacion']= 'IdPrestacion';
        $response->userdata['Prestacion']= 'Prestacion';
        $response->userdata['Valor']= 'Valor';
        $response->userdata['myac'] = '';

        return json_encode($response);

    }

    function getPrestaciones($page, $rows, $filters, $sidx, $sord){

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
                    pres.IdPrestacion,
                    pres.Prestacion,
                    pres.Valor
                FROM 
                    Prestaciones pres
                WHERE
                    pres.IdPrestacion IS NOT NULL AND pres.habilitado = 1 ".$where." ".$sort;
        
        //$_SESSION['consultCertificaciones'] = $query;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbprest->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbprest->querySize; $i++)
        {
            $reclamo = $this->dbprest->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
    }

    private function getCantidadPrestaciones($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Prestaciones pres WHERE pres.habilitado = 1";
        
        $this->dbprest->ejecutarQuery($query);
        $result = $this->dbprest->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }

    function eliminarPrestacion($id){

        $query  = "UPDATE Prestaciones SET habilitado = 0 WHERE IdPrestacion = $id;";
        $response = new stdClass();
        try
        {   
            $this->dbprest->conectar();
            $this->dbprest->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbprest->desconectar();
            return $response;   
        }

        $this->dbprest->desconectar();

        $response->message = "Se ha elminado la prestacion";
        $response->ret = true;

        return $response;
    }

    function modificarPrestacion($data){

         $query  = "UPDATE Prestaciones SET Valor = ".$data['Valor']." WHERE IdPrestacion = ".$data['id'].";";
        $response = new stdClass();
        try
        {   
            $this->dbprest->conectar();
            $this->dbprest->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbprest->desconectar();
            return $response;   
        }

        $this->dbprest->desconectar();

        $response->message = "Se ha modificado la prestacion";
        $response->ret = true;

        return $response;
    }


}
