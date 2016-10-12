<?php

class TipoMotor {
	const MYSQL = 1;
	const SQLSERVER = 2;
}//EndClass

class dataBaseConnector
{//TODO: Mover a otro archivo y hacer los mismo con todas las clases
	var $host;
	var $link;
	var $base;
	var $usuario;
	var $contrasenia;
	var $motor;
	
	var $query;
	var $result;
	var $currentRow;
	var $querySize;
	
	function dataBaseConnector($host, $link, $base, $usuario, $contrasenia, $motor = TipoMotor::MYSQL)
	{
		//TODO: hay que cambiar el host esto se hace asi para testear en la virtual
		$this->host =$host;
		$this->link =$link;
		$this->base =$base;
		$this->usuario =$usuario;
		$this->contrasenia =$contrasenia;
		$this->motor = $motor;
	}
	
	function usarBase($newBase)
	{
		if($this->motor==TipoMotor::MYSQL)
		{
			$this->base = $newBase;
			$setbase = mysql_select_db($newBase,$this->link);
		}
		elseif ($this->motor==TipoMotor::SQLSERVER) 
		{
			$this->base = $newBase;
			$setbase = mssql_select_db($newBase,$this->link);
			
		}
		
	}
	
	function escaparString($string)
	{
		return mysql_real_escape_string($string,$this->link);
	}
	function conectar()
	{
		//TODO: supongo que esta es la forma de tratar estas cosas
		//podria pedir un llamador como par�metro para saber que funcion lo origino
		if($this->motor == TipoMotor::MYSQL)
		{
			try {
				$this->link = mysql_connect($this->host, $this->usuario , $this->contrasenia); 	
				//echo mysql_error($this->link);
				
				//Este setBase deberia ser un atributo?
				$setbase = mysql_select_db($this->base,$this->link);
				
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage(), $e->getCode());
			}
		}
		elseif ($this->motor==TipoMotor::SQLSERVER) 
		{
			try {
				
				$this->link = mssql_connect($this->host, $this->usuario , $this->contrasenia); 	
				//echo mysql_error($this->link);
				
				//Este setBase deberia ser un atributo?
				$setbase = mssql_select_db($this->base,$this->link);
				
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage(), $e->getCode());
			}
		
		}
		return $this->link;
	}
	
	function desconectar()
	{
		if($this->motor == TipoMotor::MYSQL)
		{
			@mysql_close ($this->link);
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			@mssql_close($this->link);
		}
	}
	
	
	function ejecutarAccion($query)
	{ //Esto es para consultas del tipo insert, update y delete
		if($this->motor == TipoMotor::MYSQL)
		{
			try {
				$this->result = mysql_query($query, $this->link);
				if(!$this->result )
				{
					$this->querySize = 0;
				}
				else 
				{
					$this->querySize = mysql_affected_rows();
				}
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
			$this->query= $query;
			
			if (mysql_errno($this->link)){
				throw new Exception(mysql_error($this->link), mysql_errno($this->link));
			}
		}
		elseif ($this->motor == TipoMotor::SQLSERVER)
		{
			try {
				$this->result = mssql_query($query, $this->link);
				if(!$this->result )
				{
					$this->querySize = 0;
				}
				else 
				{
					$this->querySize = mssql_rows_affected($this->link);
				}
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
			$this->query= $query;
			
			/*if (mssql_($this->link)){
				throw new Exception(mysql_error($this->link), mysql_errno($this->link));
			}*/
		}
		return $this->result;		
	}
	
	function ejecutarQuery($query)
	{//TODO: estos query se pueden guardar en un atributo de la clase y entregar el iterador como respuesta 
		if($this->motor == TipoMotor::MYSQL)
		{
			try {
				
				$this->result = mysql_query($query, $this->link);
				if(!$this->result )
				{
					$this->querySize = 0;
				}
				else 
				{
					$this->querySize = mysql_num_rows($this->result);
				}
				//$this->currentRow = mysql_fetch_assoc($this->result);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
			
			if (mysql_errno($this->link)){
				throw new Exception(mysql_error($this->link), mysql_errno($this->link));
			}
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			try {
				
				$this->result = mssql_query($query, $this->link);
				if(!$this->result )
				{
					$this->querySize = 0;
				}
				else 
				{
					$this->querySize = mssql_num_rows($this->result);
				}
				//$this->currentRow = mysql_fetch_assoc($this->result);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), $e->getCode());
			}
		}
		$this->query= $query;
		
	}
	
	function lastInsertId()
	{
		if($this->motor == TipoMotor::MYSQL)
		{
			return mysql_insert_id($this->link);
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			$id = 0;
			$res = mssql_query("SELECT @@IDENTITY AS id");
			if ($row = mssql_fetch_array($res, MSSQL_ASSOC)) {
				$id = $row["id"];
			
				return $id;
			}
		}
	}
	
	function querySize()
	{
		if(isset($this->result))
		{
			return  $this->querySize;
		}
		else
		{
			return 0;
			//EL code de que sirve?, como asigno uno bueno?
			throw new Exception("No se puede contar los resultados sin ejecutar un query", '010101');
		}
		
	}
	function eof()
	{
		return ($this->currentRow == false);
	}
	
	function  fetchRow()
	{
		if($this->motor == TipoMotor::MYSQL)
		{	
			$this->currentRow = mysql_fetch_assoc($this->result);
			//return $this->currentRow;
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			$this->currentRow = mssql_fetch_assoc($this->result);
			
		}
		return $this->currentRow;
	}
	
	function  currentRow()
	{
		return $this->currentRow;
	}
	
	function moveNext()
	{
		//TODO: cada vez que hago esto se mueve un lugar?
		if($this->motor == TipoMotor::MYSQL)
		{	
			$this->currentRow = mysql_fetch_assoc($this->result);
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{		
			$this->currentRow = mssql_fetch_assoc($this->result);
		}
	}

	/**
	 * 
	 * Escapa los caracteres especiales de una cadena
	 * @param string $cadena: La cadena a escapar
	 */
	function realEscape($cadena)
	{
		$ret = '';
		if($this->motor == TipoMotor::MYSQL)
		{
			$ret = mysql_real_escape_string($cadena, $this->link);	
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			//TODO: Chequear como hacerlo bien para MS-SQL Server
			$ret = $cadena;	
		}
		return $ret;
	}
	
	
	function lastQuery()
	{
		return $this->query;
	}
	
	function setResult(&$res)
	{
		$res = $this->result ;
	}
	
	function setQuerySize(&$quer)
	{
		$quer = $this->querySize;
		
	}
	
	function setQuery(&$quer)
	{
		$quer = $this->query;
		
	}
	
	function getResult()
	{
		return $this->result ;
	}
	
	function getQuerySize()
	{
		return $this->querySize;
		
	}
	
	function countFields()
	{
		if($this->motor == TipoMotor::MYSQL)
		{
			return mysql_num_fields($this->result);
		}
		elseif($this->motor == TipoMotor::MYSQL)
		{
			return mssql_num_fields($this->result);
		}
	}
	
	function fieldAt($index)
	{
		if($this->motor == TipoMotor::MYSQL)
		{
			$name = mysql_field_name($this->result,$index);
			$type = mysql_field_type($this->result,$index);
			$lenght = mysql_field_len($this->result,$index);
			$flags = mysql_field_flags($this->result,$index);
			
			$field = array($name,$type,$lenght,$flags);
		}
		elseif($this->motor == TipoMotor::SQLSERVER)
		{
			$name = mssql_field_name($this->result,$index);
			$type = mssql_field_type($this->result,$index);
			$lenght = mssql_field_length($this->result,$index);
			$flags = "";
		
			$field = array($name,$type,$lenght,$flags);
		}
		
		return $field;
	}	
}

//TODO: borrar todo eto no sirve de nada
class queryResult
{
	var $queryNodo;
}
class queryNodo
{	
	var $currentResult;
	var $previous;
	var $next;
	
}
?>