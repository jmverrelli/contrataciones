<?php
include_once 'dataBaseConnector.php';
require_once 'utils.php';


class DataBaseInformation 
{
	var $firephpinf;
	var $baseDeDAtos;
	var $tables;
	
	function DataBaseInformation(Conexion $connec)
	{
		//TODO: el dataBase Connector deberia recibir la conexion directamente
		$this->baseDeDAtos = new dataBaseConnector($connec->host,$connec->link,$connec->base, $connec->usuario, $connec->contrasenia);
		$this->tables = array();
		
			$this->firephpinf = FirePHPinf::getInstance(true);
			$this->firephpinf->registerErrorHandler(
	            $throwErrorExceptions=false);	
				$this->firephpinf->registerExceptionHandler();
				$this->firephpinf->registerAssertionHandler(
	            $convertAssertionErrorsToExceptions=true,
	            $throwAssertionExceptions=false);
	}
	
	function obtenerPrimaryKey($table)
	{
		$query = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY';";
		
		try
		{
			$this->baseDeDAtos->conectar();
		}
		catch (Exception $e)
		{
			$this->firephpinf->error($e);
			throw $e;
		}
		
		try
		{
			$this->baseDeDAtos->ejecutarQuery($query);
			
		}
		catch (Exception $e)
		{
			$this->firephpinf->error($e);
			/*$this->firephpinf->log($query, 'Ejecutando Query');
			$this->firephpinf->error($e);*/
			throw $e;
		}
		$this->baseDeDAtos->desconectar();
		$ret = array();
		
		for($i=0; $i<$this->baseDeDAtos->querySize();$i++)
		{
			$result = $this->baseDeDAtos->fetchRow();
			$ret[] = $result['Column_name'];
		}
		
		return $ret;
		
	}
	
	function analisisCamposConsulta($consulta)
	{
		$sqlInf = new AnalizadorSQL($consulta);
		$query = $sqlInf->versionLimitDelSelect(null, 1);
		
		try
		{
			$this->baseDeDAtos->conectar();
		}
		catch (Exception $e)
		{
			$this->firephpinf->error($e);
			/*$this->firephpinf->trace('Conectando a la base de datos');
			$this->firephpinf->error($e);*/
			//echo "error conectando:" . $e->getMessage();
			throw $e;
		}
		try
		{
			$this->baseDeDAtos->ejecutarQuery($query);
			
		}
		catch (Exception $e)
		{
			$this->firephpinf->error($e);
			/*$this->firephpinf->log($query, 'Ejecutando Query');
			$this->firephpinf->error($e);*/
			throw $e;
		}
		
		$result = $this->baseDeDAtos->getResult();
		
		$resultInf = new ResultInformation();
		
		$fields = mysql_num_fields($result);
		for ($i = 0; $i < $fields; $i++) {
			$type = mysql_field_type($result, $i);
			$name = mysql_field_name($result, $i);
			$table = mysql_field_table($result, $i);
			$campo = new FieldAtribute($name);
			$campo->setType($type);
			$campo->table = $table;
			if(!empty($table))
			{
				$campo->index = $table.".".$campo->name;
				
			}
			$resultInf->addField($campo);
		}
		if($this->baseDeDAtos->querySize()==0)
		{
			//TODO: pensar el tema de los codigos
			throw new Exception("la consulta $consulta no posee registros", 1224);
		}
		return $resultInf;
	}
	
	function cargarTablas($filter)
	{
		if(!isset($filter) || empty($filter))
		{
			$query = "SHOW TABLES;";
		}
		else {
			$query = "SHOW TABLES LIKE '%$filter%';";
		}
		
		//$this->baseDeDAtos->conectar();
		try
		{
			$this->baseDeDAtos->conectar();
		}
		catch (Exception $e)
		{
			echo $e;
			/*$this->firephpinf->trace('Conectando a la base de datos');
			$this->firephpinf->error($e);*/
			//echo "error conectando:" . $e->getMessage();
		}
		try
		{
			$this->baseDeDAtos->ejecutarQuery($query);
			
		}
		catch (Exception $e)
		{
			echo $e;
			/*$this->firephpinf->log($query, 'Ejecutando Query');
			$this->firephpinf->error($e);*/
		}
		
		//$this->firephpinf->log($query,"Query");
		for ($i = 0; $i < $this->baseDeDAtos->querySize(); $i++) {
			$result = $this->baseDeDAtos->fetchRow();
			//$this->firephpinf->log($result,"Es una fila");
			$campo = "";
			if(!isset($filter) || empty($filter))
			{
				$campo = "Tables_in_" . $this->baseDeDAtos->base;
			}
			else {
				$campo = "Tables_in_" . $this->baseDeDAtos->base . " (%$filter%)";
			}
			
			
			$nombre = ($result[$campo]);
			$tabla = new Table($nombre);
			array_push($this->tables,$tabla);
		}
		
		for($j=0;$j<count($this->tables);$j++)
		{
			$query = "Describe " . ($this->tables[$j]->name) . ";";
			
			try
			{
				$this->baseDeDAtos->ejecutarQuery($query);
			}
			catch (Exception $e)
			{
				echo $e;
				/*$this->firephpinf->log($query, 'Ejecutando Query');
				$this->firephpinf->error($e);*/
			}
			
			for ($i = 0; $i < $this->baseDeDAtos->querySize(); $i++) {
				$result = $this->baseDeDAtos->fetchRow();
				$nombre = ($result["Field"]);
				$tipo = ($result["Type"]);
				$null = UtilsInform::sqlBoolToPHP($result["Null"]);
				$key = ($result["Key"]);
				
				$field = new Field($nombre,$tipo,$null,$key);
				$this->tables[$j]->addField($field);
			}
			
		}
		//$this->firephpinf->log($this->tables,"Tablas");
		$this->baseDeDAtos->desconectar();
	}
	
	function cargarUnicaTabla($filter)
	{
		
		$query = "SHOW TABLES LIKE '$filter';";
		
		
		//$this->baseDeDAtos->conectar();
		try
		{
			$this->baseDeDAtos->conectar();
		}
		catch (Exception $e)
		{
			echo $e;
			/*$this->firephpinf->trace('Conectando a la base de datos');
			$this->firephpinf->error($e);*/
			//echo "error conectando:" . $e->getMessage();
		}
		try
		{
			$this->baseDeDAtos->ejecutarQuery($query);
			
		}
		catch (Exception $e)
		{
			echo $e;
			/*$this->firephpinf->log($query, 'Ejecutando Query');
			$this->firephpinf->error($e);*/
		}
		
		//$this->firephpinf->log($query,"Query");
		for ($i = 0; $i < $this->baseDeDAtos->querySize(); $i++) {
			$result = $this->baseDeDAtos->fetchRow();
			//$this->firephpinf->log($result,"Es una fila");
			$campo = "";
			
			
			$campo = "Tables_in_" . $this->baseDeDAtos->base . " ($filter)";
			
			
			
			$nombre = ($result[$campo]);
			$tabla = new Table($nombre);
			array_push($this->tables,$tabla);
		}
		
		for($j=0;$j<count($this->tables);$j++)
		{
			$query = "Describe " . ($this->tables[$j]->name) . ";";
			
			try
			{
				$this->baseDeDAtos->ejecutarQuery($query);
			}
			catch (Exception $e)
			{
				echo $e;
				/*$this->firephpinf->log($query, 'Ejecutando Query');
				$this->firephpinf->error($e);*/
			}
			
			for ($i = 0; $i < $this->baseDeDAtos->querySize(); $i++) {
				$result = $this->baseDeDAtos->fetchRow();
				$nombre = ($result["Field"]);
				$tipo = ($result["Type"]);
				$null = UtilsInform::sqlBoolToPHP($result["Null"]);
				$key = ($result["Key"]);
				
				$field = new Field($nombre,$tipo,$null,$key);
				$this->tables[$j]->addField($field);
			}
			
		}
		//$this->firephpinf->log($this->tables,"Tablas");
		$this->baseDeDAtos->desconectar();
	}
	
	function nombreTablas()
	{
		$ret = array();
		for ($i = 0; $i < count($this->tables); $i++) {
			$ret[] = $this->tables[$i]->name;
		}
		return $ret;
	}
	
}

/*
 * Un data base table esta compuesta de fields
 * cada field tiene un nombre un tipo una longitud y flagsg
 * */
class Table
{
	var $name;
	var $fields;
	
	function Table($name)
	{
		$this->name = $name;
		$this->fields = array();
	}
	
	function addField(Field $field)
	{
		array_push($this->fields,$field);
	}
	
	function toHTMLDescription()
	{
		$ret = '<table id="tbl'.$this->name.'" class="seaTable">' . "\n";
		$ret .= "<tr>". "\n";
			$ret .= "<th>";
				$ret .= "Name";
			$ret .= "</th>". "\n";
			
			$ret .= "<th>";
				$ret .= "Type";
			$ret .= "</th>". "\n";
			
			$ret .= "<th>";
				$ret .= "Keys";
			$ret .= "</th>". "\n";
			
		$ret .= "</tr>";
		foreach ($this->fields as $field) {
			$ret .= $field->toHTMLRow(). "\n";
		}
		$ret .= "</table>";
		return $ret;
	}
}

class Field
{
	var $name;
	var $type;
	var $null;
	var $key;
	var $flags;
	var $lenght;
	
	function Field($nam,$typ, $null, $key)
	{
		$this->name= $nam;
		$this->type = $typ;
		$this->null = $null;
		$this->lenght = $null;
		$this->flags = $key;
		$this->key = $key;
		if(!(stripos($this->type, '(')===false))
		{
			$posIni = stripos($this->type, '(');
			$posFin = stripos($this->type, ')');
			$ancho = ($posFin - $posIni)-1;
			$substring = substr($this->type,$posIni +1, $ancho);
			$this->lenght = (int)($substring);
		}
	}
	/**
	 * 
	 * Retorna el tipo del campo, este puede ser date, datetime, time, text, number
	 */
	function tipo()
	{
		if(stripos($this->type, 'char') ===false)
		{
			//No es char ni var char
			if(stripos($this->type, 'time') ===false)
			{
				//no es time ni datetime
				if(stripos($this->type, 'date') ===false)
				{
					if(stripos($this->type, 'text') ===false)
					{
						return 'number';
					}
					else 
					{
						if(stripos($this->type, 'char') ===false)
						{
							return 'text';
						}
						else 
						{
							return 'char';
						}
					}
				}
				else 
				{
					return 'date';
				}
			}
			else 
			{//tiene la palabra time
				if(stripos($this->type, 'date') ===false)
				{
					return 'time';
				}
				else 
				{
					return 'datetime';
				}
			}
				
			
			
		}
		else
		{
			if(stripos($this->type, 'char') ===false)
			{
				return 'text';
			}
			else 
			{
				return 'char';
			}
		}
		
		
	}
	
	function toHTMLRow()
	{
		
		$ret = "<tr>";
			$ret .= "<td>";
				$ret .= $this->name;
			$ret .= "</td>";
			
			$ret .= "<td>";
				$ret .= $this->type;
			$ret .= "</td>";
			
			$ret .= "<td>";
				$ret .= $this->key;
			$ret .= "</td>";
		$ret .= "</tr>";
		
		return $ret;
	}
}


class ResultInformation
{
	var $numFields;
	var $fieldNames;
	var $fieldAttrs;
	var $colModel;
	
	function ResultInformation()
	{
		$this->fieldNames = array();
		$this->fieldAttrs = array();
		$this->colModel = array();
	}
	
	function addField(FieldAtribute $field)
	{
		$this->numFields++;
		array_push($this->fieldNames, $field->name);
		array_push($this->fieldAttrs, $field);
		$col = new ColAtribute($field);
		array_push($this->colModel, $col);
	}
	
	function toJSON()
	{
		return json_encode($this);
	}
	
	
}

class FieldAtribute
{
	var $name;
	var $index;
	var $sorteable;
	var $align;
	var $type;
	var $table;
	//var $editable;
	
	function FieldAtribute($name)
	{
		$this->name = utf8_encode($name);
		$this->index = utf8_encode($name);
		$this->sorteable = true;
		//hay que ponerle no editable al que sea key
		//$this->editable = true;
		$this->align = utf8_encode('right');
	}
	function setType($type)
	{
		$this->type = utf8_encode($type);
		if(stripos($type, "string")!=false || stripos($type, "char")!=false || stripos($type, "text")!=false|| stripos($type, "blob")!=false)
		{
			$this->align='left';
		}
	}
}

class ColAtribute
{
	var $name;
	var $index;
	var $sorteable;
	var $search;
	var $align;
	var $editable;
	
	function ColAtribute()
	{
		$numArgs = func_num_args();
        if ($numArgs == 1) {
            $this->ColAtribute1(func_get_arg(0));
        }
        elseif ($numArgs == 5)
        {
        	$this->ColAtribute5(func_get_arg(0));
        }
	}
	
	function ColAtribute5($name, $index, $sorteable,$search,$align)
	{
		$this->name = utf8_encode($name);
		$this->index = utf8_encode($index);
		$this->sorteable = $sorteable;
		$this->search = $search;
		$this->align = $align;
		if($this->name!='id')
		{
			$this->editable = true;
		}
	}
	function ColAtribute1(FieldAtribute $field)
	{
		$this->ColAtribute5($field->name, $field->index, $field->sorteable, empty($field->table)?false:true, $field->align);
	}
}

class Conexion
{ //TODO; Sacar esta clase de aca y ponerla en otro archivo en la carpeta connection imagino
	var $host;
	var $link;
	var $base;
	var $usuario;
	var $contrasenia;
	
	function Conexion($host, $link, $base, $usuario, $contrasenia)
	{
		//TODO: hay que cambiar el host esto se hace asi para testear en la virtual
		$this->host =$host;
		$this->link =$link;
		$this->base =$base;
		$this->usuario =$usuario;
		$this->contrasenia =$contrasenia;
	}
	
}

class AnalizadorSQL 
{//Inicialmente solo va a tener una correspondencia de Select con sus From's
//Ya transforma una version select 
		var $selectPositions;
		var $countSelects;
		var $countFroms;
		var $cadena;
		var $firephpinf;
		
		function AnalizadorSQL($cade)
		{
			$this->cadena = trim($cade);
			$this->selectPositions = array();
			$this->countSelects = 0;
			$this->countFroms = 0;
			
			$this->firephpinf = FirePHPinf::getInstance(true);
			
			$this->firephpinf->registerErrorHandler(
            $throwErrorExceptions=false);	
			$this->firephpinf->registerExceptionHandler();
			$this->firephpinf->registerAssertionHandler(
            $convertAssertionErrorsToExceptions=true,
            $throwAssertionExceptions=false);
		}
		
		function analizarParesSelectFrom()
		{
			//Algunos requisitos son, cada select tiene que tener un from, no valen cosas del tipo "Select 1+2;",
			//la consulta tiene que estar bien formada, no pueden existir espacios iniciales en blanco
			//la consulta tiene que ser del tipo Select
			//$this->firephpinf->log($this->cadena,"cadena");
			
			$cadenaSelects  = $this->cadena;
			$cadenaFroms = $this->cadena;
			$selecs = array();
			$froms = array();
			$nextSum =0;
			//Obtengo la posicion de todos los selects
			while(($pos = stripos($cadenaSelects,"select"))!=false)
			{
				$selecs[]=$pos + $nextSum;
				$this->countSelects++;
				$cadenaSelects = substr($cadenaSelects,$pos+6);
				$nextSum += $pos + 6;
			}
			
			//Obtengo la posicion de todos los FROMS
			$nextSum =0;
			while(($pos = stripos($cadenaFroms,"from"))!=false)
			{
				$froms[]=$pos + $nextSum;
				$this->countFroms++;
				$cadenaFroms = substr($cadenaFroms,$pos+4);
				$nextSum += $pos + 4;
			}
			//ahora viene el analisis real
			
			for($j=0;$j<$this->countFroms;$j++)
			{//Por cada From se busca el select mas cercano en posicion menor
				$fromPosition = $froms[$j];
				
				//$this->firephpinf->log($fromPosition,"From Pos");
				
				$selectIndex = $this->buscarSelectMenorMasProximo($fromPosition,$selecs);
				
				
				$this->selectPositions[$selectIndex] = array($selecs[$selectIndex],$fromPosition);
				
				//$this->firephpinf->log(array($selecs[$selectIndex],$fromPosition),"Select From");
				unset($selecs[$selectIndex]);
			}
			//$this->firephpinf->log($this->selectPositions,"posiciones Select");
						
		}
		
		function buscarSelectMenorMasProximo($fromPosition,$selects)
		{
			$min = 0;
				foreach ($selects as $index => $value)
				{
					if($value < $fromPosition && $index > $min)
					{
						$min = $index;
					}
				}
			return $min;
		}
		
		function toString()
		{
			$ret = "";
			for($i=0;$i<$this->countSelects;$i++)
			{
				$ret .= $this->selectPositions[$i][0] . "\t" . $this->selectPositions[$i][1] . "\n"; 	
			}
			return $ret;
		}
		
		function versionCountDeSelect()
		{	//Esto esta bueno si no termina con Group By en otro caso no me es util
			if(!$this->terminaConGroupBy())
			{
				/*$this->firephpinf->group('Function VersionCount',
                	array('Collapsed' => true,
                      'Color' => '#FF00FF'));*/
				$primerSelect = $this->selectPositions[0][0];
				//$this->firephpinf->log($primerSelect,"primer Select por");
				$primerFrom   = $this->selectPositions[0][1];
				
				//$this->firephpinf->log($primerFrom,"From Asociado");
				
				$cadenaInicio = substr($this->cadena,$primerSelect,6);
				//$this->firephpinf->log($cadenaInicio,"Principio Cadena");
				
				$cadenaFin = substr($this->cadena,$primerFrom);
				
				//$this->firephpinf->log($cadenaFin,"fin de la cadena");
			
				$elemento = UtilsInform::palabra($this->cadena,2);
				
				//Quito el ultimo caracter si es cero
				if($elemento[strlen($elemento)-1] == ",")
				{
					$elemento = substr($elemento,0,strlen($elemento)-1);
				}
				$ret = $cadenaInicio . " Count($elemento) as cantidad " . "$cadenaFin;";
				//$this->firephpinf->groupEnd();
			}
			else
			{
				//Esto hay que ver si es mejorable
				$ret = "Select Count(*) as cantidad From ( "  .$this->cadena . ") ;";
			}
			return $ret;
		}
		
		private function terminaConGroupBy()
		{
			$ultimoFromPos = strripos($this->cadena,"from");
			
			//Si existe un group by despues del ultimo form, estoy en problemas
			if (($ultimoGroupByPos = strripos(UtilsInform::borrarDoblesEspaciosBlancos(substr($this->cadena,$ultimoFromPos)),"group by"))!=false)
			{
				return true;
			}
			return false;
		
		}
		
		function versionLimitDelSelect($start, $limit)
		{
			$ret = UtilsInform::borrarDoblesEspaciosBlancos($this->cadena);
			if(!$ret[strlen($ret)-1] !=";")
			{
				if(!is_null($start))
					$ret .= " LIMIT $start , $limit ;";
				else 
					$ret .= " LIMIT $limit ;";
			}
			else {
				$this->firephpinf->error($e);
				exit(1);
			}
			return $ret;
		}
		
		function versionOrderBy($sid, $sord)
		{//TODO aca puedo verificar que sid sea da la consulta
			$ret = UtilsInform::borrarDoblesEspaciosBlancos($this->cadena);
			$ret .= " ORDER BY $sid $sord ;";
			return $ret;
		}
		
		function versionOrderByAndLimit($sid, $sord,$start, $limit)
		{
			$ret = UtilsInform::borrarDoblesEspaciosBlancos($this->cadena);
			$ret .= " ORDER BY $sid $sord LIMIT $start , $limit ;";
			return $ret;
		}

}
?>
