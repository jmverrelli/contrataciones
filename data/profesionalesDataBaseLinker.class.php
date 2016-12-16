<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';

class ProfesionalesDataBaseLinker
{
	var $dbprof;
	
	function ProfesionalesDataBaseLinker()
	{
		$this->dbprof = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function getProfesionalesSelect(){

		$query="SELECT IdProfesional, `Apellido y Nombre` FROM Profesionales WHERE habilitado = 1 ORDER BY `Apellido y Nombre` ASC;";

        try {

        	$this->dbprof->conectar();
        	$this->dbprof->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbprof->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbprof->querySize; $i++)
        {
            $profesionales = $this->dbprof->fetchRow($query);
            $ret[] = $profesionales;
        }

        $this->dbprof->desconectar();

        $selectstr = "";

        for ($i=0; $i < count($ret) ; $i++) { 
        	$selectstr .="<option value='".$ret[$i]['IdProfesional']."'>".htmlspecialchars($ret[$i]['Apellido y Nombre'])."</option>";
        }

        return $selectstr;

    }

    function traerProfesional($id){
        $query="SELECT * FROM Profesionales WHERE IdProfesional = $id";

        try {

            $this->dbprof->conectar();
            $this->dbprof->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbprof->desconectar();
            return false;
        }


        $profesionales = $this->dbprof->fetchRow($query);

        $this->dbprof->desconectar();

        return $profesionales;
    }


    function agregarProfesional($data){

        $apellido = strtoupper($data['apellido']);
        $nombre = strtoupper($data['nombre']);

        $query = "INSERT INTO Profesionales (`Apellido y Nombre`, `Nro Convenio`, `Nro Proveedor`, Telefono) VALUES ('".$apellido." ".$nombre."',".$data['nroConvenio'].", ".$data['nroProveedor'].", '".$data['telefono']."');";



        $response = new stdClass();
        try
        {   
            $this->dbprof->conectar();
            $this->dbprof->ejecutarAccion($query);
            $last_id = $this->dbprof->lastInsertId();
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbprof->desconectar();

        $arrayvinculo = array();
        $arrayvinculo['VincularProfesional'] = $last_id;
        $arrayvinculo['VincularEspecialidad'] = $data['especialidad'];

        $vinculo = $this->agregarVinculoEspecialidad($arrayvinculo);

        if($vinculo->ret == false){
            $response->message = "Error al ingresar especialidad";
            $response->ret = true;
            return $response;   
        }

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;
    }


    function getProfesionalesJson($page, $rows, $filters, $sidx, $sord){

        $response = new stdClass();
        $this->dbprof->conectar();

        $profesesarray = $this->getProfesionales($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadProfesionales($filters) / $rows);
        $response->records = $this->getCantidadProfesionales($filters);

        $this->dbprof->desconectar();

        for ($i=0; $i < count($profesesarray) ; $i++) 
        {
            $certificacion = $profesesarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $certificacion['IdProfesional']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $certificacion['IdProfesional'];
            $row[] = $certificacion['Apellido y Nombre'];
            $row[] = $certificacion['Nro Convenio'];
            $row[] = $certificacion['Nro Proveedor'];
            $row[] = $certificacion['Telefono'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdProfesional']= 'IdProfesional';
        $response->userdata['Apellido y Nombre']= 'Apellido y Nombre';
        $response->userdata['Nro Conevnio']= 'Nro Conevnio';
        $response->userdata['Nro Proveedor']= 'Nro Proveedor';
        $response->userdata['Telefono']= 'Telefono';
        $response->userdata['myac'] = '';

        return json_encode($response);

    }

    function getProfesionales($page, $rows, $filters, $sidx, $sord){

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
                    pro.IdProfesional,
                    pro.`Apellido y Nombre`,
                    pro.`Nro Convenio`,
                    pro.`Nro Proveedor`,
                    pro.Telefono
                FROM 
                    Profesionales pro
                WHERE
                    pro.IdProfesional IS NOT NULL AND pro.habilitado = 1 ".$where." ".$sort;
        
        //$_SESSION['consultCertificaciones'] = $query;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbprof->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbprof->querySize; $i++)
        {
            $reclamo = $this->dbprof->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
    }

    private function getCantidadProfesionales($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Profesionales pro WHERE pro.habilitado = 1";
        
        $this->dbprof->ejecutarQuery($query);
        $result = $this->dbprof->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }

    function eliminarProfesional($id){

        $query  = "UPDATE Profesionales SET habilitado = 0, fecha_deshabilitado = now() WHERE IdProfesional = $id;";
        $response = new stdClass();
        try
        {   
            $this->dbprof->conectar();
            $this->dbprof->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbprof->desconectar();
            return $response;   
        }

        $this->dbprof->desconectar();

        $response->message = "Se ha elminado al profesional";
        $response->ret = true;

        return $response;
    }

    function traerEspecialidades($id){

        $response = new stdClass();

        $especialidades = array();

        $query2 = "SELECT esp.IdEspecialidad as IdEspecialidad, esp.Especialidad as Especialidad FROM Profesional_Especialidad pe LEFT JOIN Especialidades esp on (pe.IdEspecialidad = esp.IdEspecialidad) WHERE pe.IdProfesional = $id and pe.habilitado = 1;";
        
      try { 
            $this->dbprof->conectar();
            $this->dbprof->ejecutarQuery($query2);
            
        } catch (Exception $e) {
            $this->dbprof->desconectar();
            $response->ret = false;
            $response->message = "Error ejecutando la consulta";
        }


        for ($i = 0; $i < $this->dbprof->querySize; $i++)
        {
            $especi = $this->dbprof->fetchRow($query2);
            $especialidades[] = $especi;
        }

        if(count($especialidades) == 0){
            $response->ret = false;
            $response->message = "No tiene especialidades";
            $this->dbprof->desconectar();
            return $response;
        }

        $response->ret = true;
        $response->message = "Especialidades";
        $response->especialidadesProfesional = $especialidades;

        $this->dbprof->desconectar();

        return $response;
    }

    function agregarVinculoEspecialidad($data){

        $response = new stdClass();

        $existeVinculo = $this->existeVinculo($data);

        if($existeVinculo->ret == true) //existe un vinculo, no se hace
        {
            $response->ret = false;
            $response->message = "Ya existe el vinculo entre el profesional y la especialidad.";
            return $response;
        }

        $query = "INSERT INTO Profesional_Especialidad (IdProfesional, IdEspecialidad) VALUES (".$data['VincularProfesional'].", ".$data['VincularEspecialidad'].");";

       
        try
        {   
            $this->dbprof->conectar();
            $this->dbprof->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbprof->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;   
    }

    function existeVinculo($data){

        
        $response = new stdClass();

        $query = "SELECT * FROM Profesional_Especialidad WHERE IdProfesional = ".$data['VincularProfesional']." and IdEspecialidad = ".$data['VincularEspecialidad'].";";

        
        try { 
            $this->dbprof->conectar();
            $this->dbprof->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbprof->desconectar();
            $response->ret = false;
            $response->message = "Error ejecutando la consulta";
        }

        $especialidades = array();

        for ($i = 0; $i < $this->dbprof->querySize; $i++)
        {
            $especi = $this->dbprof->fetchRow($query);
            $especialidades[] = $especi;
        }

        if(count($especialidades) == 0){
            $response->ret = false;
            $response->message = "No existe la vinculacion.";
            $this->dbprof->desconectar();
            return $response;
        }

        $response->ret = true;
        $response->message = "Ya existe la relacion de este profesional con esta especialidad.";

        $this->dbprof->desconectar();

        return $response;

    }

}