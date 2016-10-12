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
            $row[] = $certificacion['IdModulado'];
            $row[] = $certificacion['IdProfesionales'];
            $row[] = $certificacion['IdHospital'];
            $row[] = $certificacion['FechaInicio'];
            $row[] = $certificacion['FechaFinal'];
            $row[] = $certificacion['Horas'];
            $row[] = $certificacion['PorMonto'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdCertificacion']= 'IdCertificacion';
        $response->userdata['Fecha']= 'Fecha';
        $response->userdata['IdModulado']= 'IdModulado';
        $response->userdata['IdProfesionales']= 'IdProfesionales';
        $response->userdata['IdHospital']= 'IdHospital';
        $response->userdata['FechaInicio']= 'FechaInicio';
        $response->userdata['FechaFinal']= 'FechaFinal';
        $response->userdata['Horas']= 'Horas';
        $response->userdata['PorMonto']= 'PorMonto';
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
                    cer.IdModulado,
                    cer.IdProfesionales,
                    cer.IdHospital,
                    cer.FechaInicio,
                    cer.FechaFinal,
                    cer.Horas,
                    cer.PorMonto
                FROM 
                    Certificaciones cer
                WHERE
                    cer.IdCertificacion IS NOT NULL ".$where." ".$sort;
        
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
        //POR AHORA AL CAMPO FECHA LO VOY A TRATAR COMO LA FECHA EN LA QUE SE HACE LA CERTIFICACION
        //AUNQUE EN EL ACCESS ES UN CAMPO QUE LO AGREGAN EN 1/80 CERTIFICACIONES
        //HASTA QUE PREGUNTE
        //IGUAL QUE HORA Y MONTO
        $query="INSERT INTO Certificaciones (Fecha, IdProfesionales, FechaInicio, FechaFinal, IdHospital, idUsuario, idDestino)
                 VALUES (DATE(now()), ".$data['Profesional'].", '".$data['periodoDe']."', '".$data['periodoHasta']."' , ".$data['hospital'].", ".$data['idUsuario'].",".$data['destino']."  );";

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

        $query = "SELECT cer.*, pro.`Apellido y Nombre` as profesional, hos.Hospital from Certificaciones cer LEFT JOIN Profesionales pro on (cer.IdProfesionales = pro.IdProfesional) LEFT JOIN Hospitales hos on (hos.IdHospital = cer.IdHospital) WHERE cer.IdCertificacion = $id;";

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
        $certificacion->setIdModulado($result['IdModulado']);
        $certificacion->setIdProfesionales($result['IdProfesionales']);
        $certificacion->setnombreProfesional($result['profesional']);
        $certificacion->setIdHospital($result['IdHospital']);
        $certificacion->setnombreHospital($result['Hospital']);
        $certificacion->setFechaInicio($result['FechaInicio']);
        $certificacion->setFechaFinal($result['FechaFinal']);
        $certificacion->setHoras($result['Horas']);
        $certificacion->setPorMonto($result['PorMonto']);
        return $certificacion;
    }

}