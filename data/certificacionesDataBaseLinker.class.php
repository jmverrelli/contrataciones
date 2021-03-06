<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';
include_once '../../../../negocio/certificacion.class.php';
require_once 'Spreadsheet/Excel/Writer.php';

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
            $row[] = $certificacion['Nombre'];
            $row[] = $certificacion['IdProfesionales'];
            $row[] = $certificacion['IdHospital'];
            $row[] = $certificacion['FechaInicio'];
            $row[] = $certificacion['FechaFinal'];

            $row[] = '';
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['IdCertificacion']= 'IdCertificacion';
        $response->userdata['Fecha']= 'Fecha';
        $response->userdata['Nombre']= 'FechaFinal';
        $response->userdata['IdProfesionales']= 'IdProfesionales';
        $response->userdata['IdHospital']= 'IdHospital';
        $response->userdata['FechaInicio']= 'FechaInicio';
        $response->userdata['FechaFinal']= 'FechaFinal';
        $response->userdata['verCert'] = '';
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
                    cer.FechaFinal,
                    pro.`Apellido y Nombre` as Nombre
                FROM 
                    Certificaciones cer
                    LEFT JOIN 
                    Profesionales pro on (cer.IdProfesionales = pro.IdProfesional)
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

    function agregarFirmaCertificacion($data){

        $response = new stdClass();
        if($this->tieneFirma($data['id'])){
            $response->message = "Esta certificacion ya se encuentra firmada.";
            $response->ret = false;
            return $response;

        }
        $id = $data['id'];

        $query = "UPDATE Certificaciones SET firmado = 1, fecha_firmado = now() WHERE IdCertificacion = $id;";

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

        $response->message = "Se ha agregado la firma a la certificacion";
        $response->ret = true;

        return $response;

    }

    function tieneFirma($id){

        $query = "SELECT firmado from Certificaciones where IdCertificacion = $id;";
        
        $this->dbcert->conectar();
        $this->dbcert->ejecutarQuery($query);
        $result = $this->dbcert->fetchRow($query);
        $ret = $result['firmado'];
        $this->dbcert->desconectar();
        if($ret == "1"){

            return true; //firmado
        }
        else{

            return false; //no firmado
        }
    }

    function porProfesionaExcel($data){

        $result = new stdClass();
        $result->ret = false;
        $result->message ="No se encontraron registros para la consulta";


        $query = "SELECT cer.Fecha, pro.`Apellido y Nombre`,det.IddetalleCertificacion, det.IdCertificacion, 
cer.IdCertificacion, det.Cantidad, det.valor, cer.IdProfesionales,pre.Prestacion, cer.firmado, DATE(cer.fecha_firmado) AS fecha_firmado
from `Detalle de Certificacion` det LEFT JOIN Certificaciones cer 
ON (det.IdCertificacion = cer.IdCertificacion) 
LEFT JOIN Profesionales pro on (cer.IdProfesionales = pro.Idprofesional)
LEFT JOIN Prestaciones pre on (det.IdPrestacion = pre.IdPrestacion)
WHERE pro.`Apellido y Nombre` is not null and cer.habilitado = 1 and det.habilitado = 1 and cer.Fecha between '".$data['certifPorProfesionalInicio']."' and '".$data['certifPorProfesionalFinal']."'
 ORDER BY  pro.`Apellido y Nombre` ASC, det.IdCertificacion DESC;";

        session_start();
        $_SESSION['porProfesionalCertifExcelQuery'] = $query;

          try { 
            $this->dbcert->conectar();
            $this->dbcert->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbcert->desconectar();
            $result->ret = false;
            $result->message = "Error ejecutando la consulta";
        }

        $certif = array();
        for ($i = 0; $i < $this->dbcert->querySize; $i++)
        {
            $cert = $this->dbcert->fetchRow($query);
            $certif[] = $cert;
        }

        if(count($certif) > 0){

                $result->ret = true;
                $result->message ="Query cargado";
        }

        return $result;

    }


    function porProfesionaDibujarExcel()
    {

         try
        {
            $this->dbcert->conectar();
        }
        catch (Exception $e)
        {
            throw new Exception("Error conectando a la base de datos", 20123);
        }
         
        session_start();   
        $query = $_SESSION['porProfesionalCertifExcelQuery'];
        
        try { 
            $this->dbcert->conectar();
            $this->dbcert->ejecutarQuery($query);
            
        } catch (Exception $e) {
            $this->dbcert->desconectar();
            $result->ret = false;
            $result->message = "Error ejecutando la consulta";
        }

        $certif = array();
        for ($i = 0; $i < $this->dbcert->querySize; $i++)
        {
            $cert = $this->dbcert->fetchRow($query);
            $certif[] = $cert;
        }



        $hora = time();

        $filename = $hora . "_Por_Profesional.xls";
        
        $docExcel = new Spreadsheet_Excel_Writer(); 
        
        $nuevahoja =& $docExcel->addWorksheet("Certificacion Por profesional");
        
        $format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
                                      'Color' => 'black',
                                      'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
        $fila =0;

        $nuevahoja->write($fila, 0,"Fecha",$format);
        $nuevahoja->write($fila, 1,"Profesional",$format);
        $nuevahoja->write($fila, 2,"Prestacion",$format);
        $nuevahoja->write($fila, 3,"Cantidad",$format);
        $nuevahoja->write($fila, 4,"Valor",$format);
        $nuevahoja->write($fila, 5,"Total",$format);
        $nuevahoja->write($fila, 6,"Fecha Firmado",$format);
        
        $fila=1;
        
        $tot = 0;



        for ($i = 0; $i < $this->dbcert->querySize(); $i++) 
        {
            $columna=0;
            
            
            $nuevahoja->write($fila, $columna,utf8_encode($certif[$i]["Fecha"]));
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($certif[$i]["Apellido y Nombre"]));
            $columna++;

            $nuevahoja->write($fila, $columna,$certif[$i]["Prestacion"]);
            $columna++;

            $nuevahoja->write($fila, $columna,$certif[$i]["Cantidad"]);
            $columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($certif[$i]["valor"]));
            $columna++;

            $total = ($certif[$i]["Cantidad"] * $certif[$i]["valor"]);


            $nuevahoja->write($fila, $columna,utf8_encode($total));
            $columna++;

            if($certif[$i]['fecha_firmado'] == null){

                $firma = "NO FIRMO";
            }

            else{

                $firma = $certif[$i]['fecha_firmado'];
            }

            $nuevahoja->write($fila, $columna,utf8_encode($firma));
            $columna++;

            $fila++;
        }

        $this->dbcert->desconectar();
        
        $docExcel->send($filename);

        $docExcel->close();

    }

}