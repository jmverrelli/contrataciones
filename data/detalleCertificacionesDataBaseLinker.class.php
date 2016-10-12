<?php
include_once 'conexion/conectionData.php';
include_once 'conexion/dataBaseConnector.php';
include_once 'usuario/usuario.class.php';
include_once '../../../../negocio/detalleCertificacion.class.php';

class DetalleCertificacionesDataBaseLinker
{
	var $dbdetcert;
	
	function DetalleCertificacionesDataBaseLinker()
	{
		$this->dbdetcert = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
	}

	function traerDetallesCertificacion($idCertificacion){

		$query = "SELECT dc.*, pres.Prestacion FROM `Detalle de Certificacion` dc LEFT JOIN Prestaciones pres on (dc.IdPrestacion = pres.IdPrestacion) WHERE dc.IdCertificacion = $idCertificacion;";

        try {

        	$this->dbdetcert->conectar();
        	$this->dbdetcert->ejecutarQuery($query);
        	
        } catch (Exception $e) {
        	$this->dbdetcert->desconectar();
        	return false;
        }

        $ret = array();

        for ($i = 0; $i < $this->dbdetcert->querySize; $i++)
        {
            $detalles = $this->dbdetcert->fetchRow($query);

            $objDetalle = new DetalleCertificacion();
            $objDetalle->setIddetalleCertificacion($detalles['IddetalleCertificacion']);
            $objDetalle->setIdCertificacion($detalles['IdCertificacion']);
            $objDetalle->setvalor($detalles['valor']);
            $objDetalle->setcantidad($detalles['Cantidad']);
            $objDetalle->setIdPrestacion($detalles['IdPrestacion']);
            $objDetalle->setnombrePrestacion(htmlspecialchars($detalles['Prestacion']));

            $ret[] = $objDetalle;
        }

        $this->dbdetcert->desconectar();

        if(count($ret) == 0){
        	return false;
        }

        return $ret;

	}

	function agregarDetalleCertificacion($data){

		 $query="INSERT INTO `Detalle de Certificacion` (IdCertificacion, valor, Cantidad, IdPrestacion)
                 VALUES (".$data['IdCertificacion'].", TRUNCATE(".$data['valor'].",2), TRUNCATE(".$data['cantidad'].",2), ".$data['prestacion']." );";

        $response = new stdClass();
        try
        {   
            $this->dbdetcert->conectar();
            $this->dbdetcert->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            return $response;
        }

        $this->dbdetcert->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;

        return $response;

	}

	function eliminarDetalleCertificacion($IddetalleCertificacion){

		
		$query = "SELECT IdCertificacion FROM `Detalle de Certificacion` WHERE IddetalleCertificacion = $IddetalleCertificacion;";
		$response = new stdClass();

		try
        {
            $this->dbdetcert->conectar();
			$this->dbdetcert->ejecutarQuery($query);
		}
		catch(Exception $e) {
			throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbdetcert->desconectar();
            return $response;
		}

        $result = $this->dbdetcert->fetchRow($query);
        $idCertificacion = $result['IdCertificacion'];
        
		$query  = "UPDATE `Detalle de Certificacion` SET habilitado = 0 WHERE IddetalleCertificacion = $IddetalleCertificacion;";
	 	
        try
        {   
            $this->dbdetcert->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
         	throw new Exception("Error al conectar con la base de datos", 17052013);
            $response->message = "Error al ingresar el registro";
            $response->ret = false;
            $this->dbdetcert->desconectar();
            return $response;   
        }

        $this->dbdetcert->desconectar();

        $response->message = "El registro se ingreso correctamente";
        $response->ret = true;
        $response->IdCertificacion = $idCertificacion;

        return $response;
	}

}