<?php
include_once "./AccesoDatos.php";
class VehiculoDB{

    public $patente;
	public $marca;
	public $color;
    public $ingreso;
    public $egreso;
	public $importe=0;
	public $empleadoIngreso;
	public $empleadoEgreso;

    /*public function VehiculoDB($patent,$mar,$col,$mail){
        $this->patente = $patent;
		$this->marca = $mar;
		$this->color = $col;
		$this->empleado = $mail;
		$this->ingreso = date("Y/m/d H:i:s");
	}*/
	
	public function ToString(){
		return $this->patente." ".$this->marca." ".$this->color." ".$this->empleadoIngreso." ".$this->ingreso;
	}

	public function InsertarVehiculo()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into estacionados (marca,color,patente,ingreso,empleadoIngreso)values(:marca,:color,:patente,:ingreso,:empleadoIngreso)");
			   $consulta->bindValue(':marca',$this->marca, PDO::PARAM_STR);
			   $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
			   $consulta->bindValue(':ingreso', $this->ingreso, PDO::PARAM_STR);
			   $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
			   $consulta->bindValue(':empleadoIngreso', $this->empleadoIngreso, PDO::PARAM_STR);
			   
			   
			   $consulta->execute();		
			   return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
		public function InsertarVehiculoHistorial()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into historial (marca,color,patente,ingreso,empleadoIngreso,empleadoEgreso,egreso,importe)values(:marca,:color,:patente,:ingreso,:empleadoIngreso,:empleadoEgreso,:egreso,:importe)");
			   $consulta->bindValue(':marca',$this->marca, PDO::PARAM_STR);
			   $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
			   $consulta->bindValue(':ingreso', $this->ingreso, PDO::PARAM_STR);
			   $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
			   $consulta->bindValue(':empleadoIngreso', $this->empleadoIngreso, PDO::PARAM_STR);
			   $consulta->bindValue(':empleadoEgreso', $this->empleadoEgreso, PDO::PARAM_STR);
			   $consulta->bindValue(':egreso', $this->egreso, PDO::PARAM_STR);
			   $consulta->bindValue(':importe', $this->importe, PDO::PARAM_STR);

			   
			   $consulta->execute();		
			   return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public function BorrarVehiculo()
	{

		   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		   $consulta =$objetoAccesoDato->RetornarConsulta("
			   delete 
			   from estacionados 				
			   WHERE patente=:patente");	
			   $consulta->bindValue(':patente',$this->patente, PDO::PARAM_STR);		
			   $consulta->execute();
			   return $consulta->rowCount();

	}
	public static function TraerUnVehiculo($patente) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from estacionados WHERE patente=:patente");
			$consulta->bindValue(':patente', $patente, PDO::PARAM_STR);
			$consulta->execute();
			$vehiculoBuscado = $consulta->fetchObject('VehiculoDB');
			return $vehiculoBuscado;

	}
	public function ModificarVehiculo()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
			update estacionados 
			set color=:color,
			marca=:marca,
			ingreso=:ingreso,
			empleadoIngreso=:empleadoIngreso
			WHERE patente=:patente");
		$consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
		$consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);		
		$consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
		$consulta->bindValue(':empleadoIngreso', $this->empleadoIngreso, PDO::PARAM_STR);
		$consulta->bindValue(':ingreso', $this->ingreso, PDO::PARAM_STR);		
		return $consulta->execute();
	}


  	public static function TraerTodosLosEstacionados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from estacionados");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "VehiculoDB");
	}
}