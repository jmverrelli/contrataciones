<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';
include_once '../../../../negocio/locacion.class.php';
include_once '../../../../negocio/detalleLocacion.class.php';
include_once 'hospitalesDataBaseLinker.class.php';
require_once 'Spreadsheet/Excel/Writer.php';
class LocacionesDataBaseLinker
{
	var $dbloc;
	
	function LocacionesDataBaseLinker()
	{
		$this->dbloc = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function agregarLocacion($data){

        $response = new stdClass();

        if(!$this->existeLocacion($data['Profesional'], $data['locacion'])){

            $response->message = "Ya existe la locacion para el Profesional";
            $response->ret = false;
            return $response;
        }

		 $query="INSERT INTO Locaciones (IdProfesional, Locacion, FechaInicio, FechaFinal, IdEspecialidad, fecha_creacion, idUsuario)
                 VALUES (".$data['Profesional'].", '".$data['locacion']."', '".$data['fechaInicioLoc']."' , '".$data['fechaFinalLoc']."',".$data['EspecialidadLoc']." , now() , ".$data['idUsuario']." );";

       
        try
        {   
            $this->dbloc->conectar();
            $this->dbloc->ejecutarAccion($query);
            $lastId = $this->dbloc->lastInsertId();
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbloc->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->lastId = $lastId;
        $response->ret = true;

        return $response;
	}

	    function traerLocacion($id){

        $query = "SELECT cer.*, pro.`Apellido y Nombre` as profesional, es.Especialidad as Especialidad from Locaciones cer LEFT JOIN Profesionales pro on (cer.IdProfesional = pro.IdProfesional) LEFT JOIN Especialidades es on (es.IdEspecialidad = cer.IdEspecialidad) WHERE cer.IdLocacion = $id and cer.habilitado = 1;";

        try {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);    
        } catch (Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            return false;
        }
        
        $result = $this->dbloc->fetchRow($query);
        $this->dbloc->desconectar();
        $locacion = new Locacion();
        $locacion->setIdLocacion($result['IdLocacion']);
        $locacion->setLocacion($result['Locacion']);
        $locacion->setIdProfesional($result['IdProfesional']);
        $locacion->setIdEspecialidad($result['IdEspecialidad']);
        $locacion->setEspecialidad($result['Especialidad']);
        $locacion->setnombreProfesional($result['profesional']);
        $locacion->setFechaInicio($result['FechaInicio']);
        $locacion->setFechaFinal($result['FechaFinal']);
        return $locacion;
    }


	function traerDetallesLocacion($idLocacion){

		$query = "SELECT dc.*, hp.Hospital as Hospital FROM `Detalles de Locacion` dc LEFT JOIN Locaciones pres on (dc.IdLocacion = pres.IdLocacion) LEFT JOIN Hospitales hp on (dc.IdHospital = hp.IdHospital) WHERE dc.IdLocacion = $idLocacion AND dc.habilitado = 1;";

        try {

        	$this->dbloc->conectar();
        	$this->dbloc->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbloc->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);

            $objDetalle = new DetalleLocacion();
            $objDetalle->setIdDetallesLocacion($detalles['IdDetallesLocacion']);
            $objDetalle->setIdLocacion($detalles['IdLocacion']);
            $objDetalle->setHospital($detalles['Hospital']);
            $objDetalle->setMonto($detalles['Monto']);

            $ret[] = $objDetalle;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){
        	return false;
        }

        return $ret;

	}	

	function agregarMonto($data){

		$query = "INSERT INTO `Detalles de Locacion` (IdHospital, Monto, IdLocacion) VALUES (".$data['hospital'].",".$data['valor'].",".$data['IdLocacion'].");";

		$response = new stdClass();
        try
        {   
            $this->dbloc->conectar();
            $this->dbloc->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbloc->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;
	}


	function getLocacionesJson($page, $rows, $filters, $sidx, $sord){

		$response = new stdClass();
        $this->dbloc->conectar();

        $locacionesarray = $this->getLocaciones($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadLocaciones($filters) / $rows);
        $response->records = $this->getCantidadLocaciones($filters);

        $this->dbloc->desconectar();

        for ($i=0; $i < count($locacionesarray) ; $i++) 
        {
            $locacion = $locacionesarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $locacion['IdLocacion']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $locacion['IdLocacion'];
            $row[] = $locacion['Locacion'];
            $row[] = $locacion['Apellido y Nombre'];
            $row[] = $locacion['IdProfesional'];
            $row[] = $locacion['FechaInicio'];
            $row[] = $locacion['FechaFinal'];
            $row[] = $locacion['Elevado'];
            $row[] = $locacion['FechaElevado'];
            
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdLocacion']= 'IdLocacion';
        $response->userdata['Locacion']= 'Locacion';
        $response->userdata['Apellido y Nombre']= 'Apellido y Nombre';
        $response->userdata['IdProfesional']= 'IdProfesional';
        $response->userdata['FechaInicio']= 'FechaInicio';
        $response->userdata['FechaFinal']= 'FechaFinal';
        $response->userdata['Elevado']= 'Elevado';
        $response->userdata['FechaElevado']= 'FechaElevado';
        
        $response->userdata['myac'] = '';

        return json_encode($response);

	}

	function getLocaciones($page, $rows, $filters, $sidx, $sord){

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
                    loc.*,
                    pro.`Apellido y Nombre`
                FROM 
                    Locaciones loc LEFT JOIN Profesionales pro ON (loc.IdProfesional = pro.IdProfesional)
                WHERE
                    loc.IdLocacion IS NOT NULL and loc.habilitado = 1 ".$where." ".$sort;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbloc->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $reclamo = $this->dbloc->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
	}

    private function getCantidadLocaciones($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Locaciones cer
                WHERE cer.habilitado = 1";
        
        $this->dbloc->ejecutarQuery($query);
        $result = $this->dbloc->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }

    function eliminarDetalleLocacion($IddetalleLocacion){

        
        $query = "SELECT IdLocacion FROM `Detalles de Locacion` WHERE IdDetallesLocacion = $IddetalleLocacion;";

        $response = new stdClass();

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }

        $result = $this->dbloc->fetchRow($query);
        $idLocacion = $result['IdLocacion'];
        
        $query  = "UPDATE `Detalles de Locacion` SET habilitado = 0 WHERE IdDetallesLocacion = $IddetalleLocacion;";
        
        try
        {   
            $this->dbloc->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;   
        }

        $this->dbloc->desconectar();

        $response->message = "El registro se elimino correctamente";
        $response->ret = true;
        $response->IdLocacion = $idLocacion;

        return $response;
    }

    function duplicadosElevacion(){

        $query= "SELECT 
                    pro.IdProfesional as IdProfesional,
                    pro.`Apellido y Nombre` as Profesional, 
                    pro.`Nro Proveedor` as Proveedor, 
                    esp.Especialidad as Especialidad,
                    SUM(dl.Monto) as Monto,
                    loc.FechaInicio as FechaInicio,
                    loc.FechaFinal as FechaFinal,
                    loc.Locacion as Locacion,
                    loc.IdLocacion,
                    hosp.Hospital as Hospital
                FROM
                    Locaciones loc LEFT JOIN `Detalles de Locacion` dl ON (loc.IdLocacion = dl.IdLocacion)
                    LEFT JOIN Profesionales pro ON (pro.IdProfesional = loc.IdProfesional)
                    LEFT JOIN Especialidades esp ON (loc.IdEspecialidad = esp.IdEspecialidad)
                    LEFT JOIN Hospitales hosp ON (hosp.IdHospital = dl.IdHospital) 
                    WHERE loc.habilitado = 1 and dl.habilitado = 1 and loc.Elevado = 0
                    GROUP BY loc.IdLocacion, hosp.IdHospital
                    ORDER BY pro.IdProfesional ASC, Especialidad DESC;
                ";

        try {

            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbloc->desconectar();
            return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);
            $ret[] = $detalles;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){
            return false;
        }

        return $ret;
    }


    function elevarLocaciones($noElevados){

        $query = "UPDATE Locaciones SET Elevado = 1, FechaElevado = now() WHERE ";

        for($i = 0; $i < count($noElevados); $i++){

            $query .= "IdLocacion = ".$noElevados[$i]['IdLocacion']." or ";
        }

        $query = trim($query, ' or ');

        $response = new stdClass();

        $query .= ";";

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;   
        }

        $this->dbloc->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;

    }


    function existeLocacion($IdProfesional, $locacion){

        $query = "SELECT * FROM Locaciones WHERE IdProfesional = $IdProfesional and Locacion = '".$locacion."' ;";

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }

        $result = $this->dbloc->fetchRow($query);

        $this->dbloc->desconectar();

        if(!$result){

            return true;
        }

        return false;

        
    }


    function traerLocacionesAnteriores($IdProfesional){

        $query = "SELECT * FROM Locaciones WHERE IdProfesional = $IdProfesional ORDER BY Locacion DESC;";

        $response = new stdClass();

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }

        $ret = array();
        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);
            $ret[] = $detalles;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){

            $response->ret = false;
            $response->message = "Este Profesional no posee locaciones anteriores.";
        }
        else{

            $response->ret = true;
            $response->message = $ret;
        }

        return $response;

    }

        function duplicadosElevacionFecha($fechaPedida){

        $query= "SELECT 
                    pro.IdProfesional as IdProfesional,
                    pro.`Apellido y Nombre` as Profesional, 
                    pro.`Nro Proveedor` as Proveedor, 
                    esp.Especialidad as Especialidad,
                    SUM(dl.Monto) as Monto,
                    loc.FechaInicio as FechaInicio,
                    loc.FechaFinal as FechaFinal,
                    loc.Locacion as Locacion,
                    loc.IdLocacion,
                    hosp.Hospital as Hospital
                FROM
                    Locaciones loc LEFT JOIN `Detalles de Locacion` dl ON (loc.IdLocacion = dl.IdLocacion)
                    LEFT JOIN Profesionales pro ON (pro.IdProfesional = loc.IdProfesional)
                    LEFT JOIN Especialidades esp ON (loc.IdEspecialidad = esp.IdEspecialidad)
                    LEFT JOIN Hospitales hosp ON (hosp.IdHospital = dl.IdHospital) 
                    WHERE loc.habilitado = 1 and dl.habilitado = 1 and DATE(loc.fecha_creacion) = '".$fechaPedida."'
                    GROUP BY loc.IdLocacion, hosp.IdHospital
                    ORDER BY pro.IdProfesional ASC, Especialidad DESC;
                ";

        try {

            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbloc->desconectar();
            return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);
            $ret[] = $detalles;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){
            return false;
        }

        return $ret;
    }


        function getLocacionesAnterioresJson($page, $rows, $filters, $sidx, $sord){

        $response = new stdClass();
        $this->dbloc->conectar();

        $locacionesarray = $this->getLocacionesAnteriores($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadLocacionesAnteriores($filters) / $rows);
        $response->records = $this->getCantidadLocacionesAnteriores($filters);

        $this->dbloc->desconectar();

        for ($i=0; $i < count($locacionesarray) ; $i++) 
        {
            $locacion = $locacionesarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $locacion['FechaElevado']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $locacion['FechaElevado'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }
        $response->userdata['FechaElevado']= 'FechaElevado';
        
        $response->userdata['myac'] = '';

        return json_encode($response);

    }

    function getLocacionesAnteriores($page, $rows, $filters, $sidx, $sord){

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
                    DATE(loc.FechaElevado) as FechaElevado
                FROM 
                    Locaciones loc
                WHERE
                    loc.IdLocacion IS NOT NULL and loc.habilitado = 1 ".$where." GROUP BY loc.FechaElevado ".$sort;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbloc->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $reclamo = $this->dbloc->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
    }

    private function getCantidadLocacionesAnteriores($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Locaciones cer
                WHERE 
                    cer.habilitado = 1 
                GROUP BY cer.FechaElevado";
        
        $this->dbloc->ejecutarQuery($query);
        $result = $this->dbloc->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }


    function PorLocacion($data){

        $query = "SELECT Locaciones.Locacion, Locaciones.FechaInicio, Hospitales.Hospital, Profesionales.`Apellido y nombre`, `Detalles de Locacion`.Monto, Locaciones.IdLocacion,
                Hospitales.IdHospital, Locaciones.FechaFinal, Profesional_Especialidad.IdEspecialidad, Especialidades.Especialidad
                FROM Locaciones LEFT JOIN `Detalles de Locacion` on (Locaciones.IdLocacion = `Detalles de Locacion`.IdLocacion) LEFT JOIN
                Hospitales on (`Detalles de Locacion`.IdHospital = Hospitales.IdHospital) 
                LEFT JOIN Profesionales on (Locaciones.IdProfesional = Profesionales.IdProfesional) LEFT JOIN
                Profesional_Especialidad on (Profesionales.IdProfesional = Profesional_Especialidad.IdProfesional) LEFT JOIN Especialidades
                on (Especialidades.IdEspecialidad = Profesional_Especialidad.IdEspecialidad) 
                WHERE Locaciones.Locacion = '".$data['verPorLocacionLocacion']."' and Hospitales.IdHospital = ".$data['porLocHospital']." ORDER BY  Hospitales.Hospital, Especialidades.Especialidad, Profesionales.`Apellido y nombre`;";

        $response = new stdClass();

        session_start();
        $_SESSION['PorLocacionQuery'] = $query; //Lo guardo en session para poder usar el excel writer

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al encontrar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }

        $ret = array();
        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);
            $ret[] = $detalles;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){

            $response->ret = false;
            $response->message = "No hay cargas para esta locacion.";
        }
        else{

            $response->ret = true;
            $response->message = $ret;
        }

        return $response;

    }

function PorLocacionExcel(){
        try
        {
            $this->dbloc->conectar();
        }
        catch (Exception $e)
        {
            throw new Exception("Error conectando a la base de datos", 20123);
        }
         
        session_start();   
        $query = $_SESSION['PorLocacionQuery'];
        
        try
        {
            $this->dbloc->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error ejecutando query:" . $query, 20123);
        }
        $hora = time();

        $filename = $hora . "_Por_Locacion.xls";
        
        $docExcel = new Spreadsheet_Excel_Writer(); 
        
        $nuevahoja =& $docExcel->addWorksheet("Locacion");
        
        $format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
                                      'Color' => 'black',
                                      'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
        $fila =0;

        $nuevahoja->write($fila, 0,"Hospital",$format);
        $nuevahoja->write($fila, 1,"Especialidad",$format);
        $nuevahoja->write($fila, 2,"Apellido y nombre",$format);
        $nuevahoja->write($fila, 3,"Monto",$format);
        $nuevahoja->write($fila, 4,"FechaInicio",$format);
        $nuevahoja->write($fila, 5,"FechaFinal",$format);
        
        $fila=1;
        
        $tot = 0;

        for ($i = 0; $i < $this->dbloc->querySize(); $i++) 
        {
            $columna=0;

            $result = $this->dbloc->fetchRow();

            if($i == 0){
                    
                $ant = $result['Especialidad'];
            }

            if($result['Especialidad'] != $ant)
            {
                $nuevahoja->write($fila,$columna,'Total: '.$tot);
                $fila ++;
                $ant = $result['Especialidad'];
                $tot = 0;

            }

            
            
            $nuevahoja->write($fila, $columna,utf8_encode($result["Hospital"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["Especialidad"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["Apellido y nombre"]));
            $columna++;

            $nuevahoja->write($fila, $columna,$result["Monto"]);
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["FechaInicio"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["FechaFinal"]));
            $columna++;

            $fila++;

            $tot += $result["Monto"];
        }

        $nuevahoja->write($fila,0,'Total: '.$tot);

        $this->dbloc->desconectar();
        
        $docExcel->send($filename);

        $docExcel->close();
   
    }


    function PorProfesional($data){

        $query = "SELECT Locaciones.IdProfesional, SUM(`Detalles de Locacion`.Monto) as Monto, Profesionales.`Apellido y Nombre` 
FROM Locaciones LEFT JOIN `Detalles de Locacion` on `Detalles de Locacion`.IdLocacion = Locaciones.IdLocacion
LEFT JOIN Profesionales on Locaciones.IdProfesional = Profesionales.IdProfesional WHERE (Locaciones.FechaInicio BETWEEN '".$data['verPorProfesionalInicio']."' and '".$data['verPorProfesionalFinal']."') GROUP BY Profesionales.IdProfesional;";

        $response = new stdClass();

        session_start();
        $_SESSION['PorProfesionalQuery'] = $query; //Lo guardo en session para poder usar el excel writer

        try
        {
            $this->dbloc->conectar();
            $this->dbloc->ejecutarQuery($query);
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al encontrar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }

        $ret = array();
        for ($i = 0; $i < $this->dbloc->querySize; $i++)
        {
            $detalles = $this->dbloc->fetchRow($query);
            $ret[] = $detalles;
        }

        $this->dbloc->desconectar();

        if(count($ret) == 0){

            $response->ret = false;
            $response->message = "No hay cargas por profesional.";
        }
        else{

            $response->ret = true;
            $response->message = $ret;
        }

        return $response;

    }


    function PorProfesionalExcel(){
        try
        {
            $this->dbloc->conectar();
        }
        catch (Exception $e)
        {
            throw new Exception("Error conectando a la base de datos", 20123);
        }
         
        session_start();   
        $query = $_SESSION['PorProfesionalQuery'];
        
        try
        {
            $this->dbloc->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error ejecutando query:" . $query, 20123);
        }
        $hora = time();

        $filename = $hora . "_Por_Profesional.xls";
        
        $docExcel = new Spreadsheet_Excel_Writer(); 
        
        $nuevahoja =& $docExcel->addWorksheet("Profes");
        
        $format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
                                      'Color' => 'black',
                                      'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
        $fila =0;

        $nuevahoja->write($fila, 0,"Profesional",$format);
        $nuevahoja->write($fila, 1,"Monto",$format);
        
        $fila=1;

        for ($i = 0; $i < $this->dbloc->querySize(); $i++) 
        {
            $columna=0;

            $result = $this->dbloc->fetchRow();
            
            $nuevahoja->write($fila, $columna,utf8_encode($result["Apellido y Nombre"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["Monto"]));
            $columna++;
    
            $fila++;
        }

        $this->dbloc->desconectar();
        
        $docExcel->send($filename);

        $docExcel->close();
    }

    function importeHospProExcel($data){

        $inicio = $data['verPorHospProInicio'];
        $final = $data['verPorHospProFinal'];

        $response = new stdClass();

        $dbhosp = new hospitalesDataBaseLinker();

        $arrayHospitales = $dbhosp->getArrayHosp();

        $arrayFinal = array();

         try
        {
            $this->dbloc->conectar();
        }
        catch(Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al encontrar el registro";
            $response->ret = false;
            $this->dbloc->desconectar();
            return $response;
        }
        for($i = 0; $i < count($arrayHospitales); $i++){

            $query = "SELECT count(distinct(loc.IdProfesional)) as CantidadProfesionales, SUM(det.Monto) as Monto, count(distinct(pro.`Nro Proveedor`)) as cantidadProveedores, h.Hospital as Hospital
            FROM Hospitales h LEFT JOIN `Detalles de Locacion` det on (det.IdHospital = h.IdHospital) 
            LEFT JOIN Locaciones loc on (det.IdLocacion = loc.IdLocacion)
            LEFT JOIN Profesionales pro on (loc.IdProfesional = pro.IdProfesional)
            WHERE h.IdHospital = ".$arrayHospitales[$i]['IdHospital']." and det.habilitado = 1 and loc.habilitado = 1
            and loc.FechaFinal BETWEEN '".$inicio."' and '".$final."';";            

            $this->dbloc->ejecutarQuery($query);
            
            for ($l = 0; $l < $this->dbloc->querySize; $l++)
            {
                $detalles = $this->dbloc->fetchRow($query);
                $arrayFinal[] = $detalles;
            }
        }

        $this->dbloc->desconectar();

        $hora = time();

        $filename = $hora . "_Por_Hospital_Profesional.xls";
        
        $docExcel = new Spreadsheet_Excel_Writer(); 
        
        $nuevahoja =& $docExcel->addWorksheet("HosPro");
        
        $format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
                                      'Color' => 'black',
                                      'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
        $fila =0;

        $nuevahoja->write($fila, 0,"Hospital",$format);
        $nuevahoja->write($fila, 1,"Profesionales",$format);
        $nuevahoja->write($fila, 2,"Proveedores",$format);
        $nuevahoja->write($fila, 3,"Monto",$format);


        
        $fila=1;

        for ($f = 0; $f < count($arrayFinal); $f++) 
        {
            $columna=0;
            
            $nuevahoja->write($fila, $columna,utf8_encode($arrayFinal[$f]["Hospital"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($arrayFinal[$f]["CantidadProfesionales"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($arrayFinal[$f]["cantidadProveedores"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($arrayFinal[$f]["Monto"]));
            $columna++;
    
            $fila++;
        }
        
        $docExcel->send($filename);

        $docExcel->close();

        $response->ret = true;
        $response->message = $filename;

        return $response;

    }

}
