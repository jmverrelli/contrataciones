<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';
include_once '../../../../negocio/certificacion.class.php';

class CertificacionesDataBaseLinker
{
	var $dbcert;
	
	function CertificacionesDataBaseLinker()
	{
		$this->dbcert = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function getCertificacionesJson($page, $rows, $filters, $sidx, $sord){

		$response = new stdClass();
        $this->dbcert->conectar();

        $certificacionesarray = $this->getCertificaciones($page, $rows, $filters, $sidx, $sord);

        $response->page = $page;
        $response->total = ceil($this->getCantidadCertificaciones($filters) / $rows);
        $response->records = $this->getCantidadCertificaciones($filters);

        $this->dbcert->desconectar();

        for ($i=0; $i < count($certificacionesarray) ; $i++) 
        {
            $certificacion = $certificacionesarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $certificacion['IdCertificacion']; 
            //datos de la fila en otro array
            $row = array();
            $row[] = $certificacion['IdCertificacion'];
            $row[] = $certificacion['Fecha'];
            $row[] = $certificacion['IdProfesionales'];
            $row[] = $certificacion['IdHospital'];
            $row[] = $certificacion['FechaInicio'];
            $row[] = $certificacion['FechaFinal'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdCertificacion']= 'IdCertificacion';
        $response->userdata['Fecha']= 'Fecha';
        $response->userdata['IdProfesionales']= 'IdProfesionales';
        $response->userdata['IdHospital']= 'IdHospital';
        $response->userdata['FechaInicio']= 'FechaInicio';
        $response->userdata['FechaFinal']= 'FechaFinal';
        $response->userdata['myac'] = '';

        return json_encode($response);

	}

	function getCertificaciones($page, $rows, $filters, $sidx, $sord){

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
                    cer.IdCertificacion,
                    cer.Fecha,
                    cer.IdProfesionales,
                    cer.IdHospital,
                    cer.FechaInicio,
                    cer.FechaFinal
                FROM 
                    Certificaciones cer
                WHERE
                    cer.IdCertificacion IS NOT NULL and cer.habilitado = 1 ".$where." ".$sort;
        
        //$_SESSION['consultCertificaciones'] = $query;
    
        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->dbcert->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbcert->querySize; $i++)
        {
            $reclamo = $this->dbcert->fetchRow($query);
            $ret[] = $reclamo;
        }

        return $ret;
	}

    private function getCantidadCertificaciones($filters = null)
    {

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    Certificaciones cer";
        
        $this->dbcert->ejecutarQuery($query);
        $result = $this->dbcert->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }

    function agregarCertificacion($data){
        $query="INSERT INTO Certificaciones (Fecha, IdProfesionales, IdEspecialidad, FechaInicio, FechaFinal, IdHospital, idUsuario, idDestino)
                 VALUES (DATE(now()), ".$data['Profesional'].",".$data['EspecialidadCer'].", '".$data['periodoDe']."', '".$data['periodoHasta']."' , ".$data['hospital'].", ".$data['idUsuario'].",".$data['destino']."  );";

        $response = new stdClass();
        try
        {   
            $this->dbcert->conectar();
            $this->dbcert->ejecutarAccion($query);
            $lastId = $this->dbcert->lastInsertId();
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbcert->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->lastId = $lastId;
        $response->ret = true;

        return $response;
    }

    function traerCertificacion($id){

        $query = "SELECT cer.*,es.Especialidad as Especialidad, pro.`Apellido y Nombre` as profesional, hos.Hospital from Certificaciones cer LEFT JOIN Profesionales pro on (cer.IdProfesionales = pro.IdProfesional) LEFT JOIN Hospitales hos on (hos.IdHospital = cer.IdHospital) LEFT JOIN Especialidades es on (es.IdEspecialidad = cer.IdEspecialidad) WHERE cer.IdCertificacion = $id and cer.habilitado = 1;";

        try {
            $this->dbcert->conectar();
            $this->dbcert->ejecutarQuery($query);    
        } catch (Exception $e) {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            return false;
        }
        
        $result = $this->dbcert->fetchRow($query);
        $this->dbcert->desconectar();
        $certificacion = new Certificacion();
        $certificacion->setIdCertificacion($result['IdCertificacion']);
        $certificacion->setFecha($result['Fecha']);
        $certificacion->setIdProfesionales($result['IdProfesionales']);
        $certificacion->setIdEspecialidad($result['idEspecialidad']);
        $certificacion->setnombreEspecialidad($result['Especialidad']);
        $certificacion->setnombreProfesional($result['profesional']);
        $certificacion->setIdHospital($result['IdHospital']);
        $certificacion->setnombreHospital($result['Hospital']);
        $certificacion->setFechaInicio($result['FechaInicio']);
        $certificacion->setFechaFinal($result['FechaFinal']);
        return $certificacion;
    }

    function eliminarCertificacion($id){

        $query  = "UPDATE Certificaciones SET habilitado = 0 WHERE IdCertificacion = $id;";
        $response = new stdClass();
        try
        {   
            $this->dbcert->conectar();
            $this->dbcert->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbcert->desconectar();
            return $response;   
        }

        $this->dbcert->desconectar();

        $response->message = "Se ha elminado la certificacion";
        $response->ret = true;

        return $response;

    }

}