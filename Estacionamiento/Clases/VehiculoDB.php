<?php
include_once "./AccesoDatos.php";
class VehiculoDB{

    public $patente;
	public $marca;
	public $color;
    public $fechIngreso;
    public $fechEgreso;
	public $importe=0;
	public $empleado;

    public function VehiculoDB($patent,$mar,$col,$mail){
        $this->patente = $patent;
		$this->marca = $mar;
		$this->color = $col;
		$this->empleado = $mail;
		$this->fechIngreso = date("Y/m/d H:i:s");
    }

	public function InsertarVehiculo()
	{
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into estacionados (marca,color,patente,ingreso,empleado)values(:marca,:color,:patente,:ingreso,:empleado)");
			   $consulta->bindValue(':marca',$this->marca, PDO::PARAM_STR);
			   $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
			   $consulta->bindValue(':ingreso', $this->fechIngreso, PDO::PARAM_STR);
			   $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
			   $consulta->bindValue(':empleado', $this->empleado, PDO::PARAM_STR);
			   
			   
			   $consulta->execute();		
			   return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
		public function InsertarVehiculoHistorial()
	{
			   $this->CalcularImporte();
			   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			   $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into historial (marca,color,patente,ingreso,empleado,egreso,importe)values(:marca,:color,:patente,:ingreso,:empleado,:egreso,:importe)");
			   $consulta->bindValue(':marca',$this->marca, PDO::PARAM_STR);
			   $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
			   $consulta->bindValue(':ingreso', $this->fechIngreso, PDO::PARAM_STR);
			   $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
			   $consulta->bindValue(':empleado', $this->empleado, PDO::PARAM_STR);
			   $consulta->bindValue(':egreso', $this->fechEgreso, PDO::PARAM_STR);
			   $consulta->bindValue(':importe', $this->importe, PDO::PARAM_STR);

			   
			   $consulta->execute();		
			   return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}
	public function CalcularImporte(){
		$this->fechEgreso = date("Y/m/d H:i:s");
        $tiempoSeg =(strtotime($this->fechEgreso) - strtotime($var->fechIngreso));
        $tiempoMin = $tiempoSeg/60;
        if($tiempoMin <= 60){
        	$this->importe = 10;
        }elseif($tiempoMin > 60 && $tiempoMin <= 720){
        	$this->importe = 90;
        }else{
        	$this->importe = 170;

        }
	}

	public function BorrarVehiculo()
	{

		   $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		   $consulta =$objetoAccesoDato->RetornarConsulta("
			   delete 
			   from estacionados 				
			   WHERE patente=:patente");	
			   $consulta->bindValue(':patente',$this->patente, PDO::PARAM_INT);		
			   $consulta->execute();
			   return $consulta->rowCount();

	}
	public static function TraerUnVehiculo($patente) 
	{
			/*$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select patente, marca, color, ingreso, empleado from estacionados WHERE patente=:patente");
			$consulta->execute();
			$vehiculoBuscado = $consulta->fetchObject('VehiculoDB');
			return $vehiculoBuscado;*/	

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select patente, marca, color, ingreso, empleado from estacionados WHERE patente=:patente");
			$consulta->bindValue(':patente', $patente, PDO::PARAM_STR);
			$consulta->execute();
			$vehiculoBuscado = $consulta->fetchObject('VehiculoDB');
			return $vehiculoBuscado;

	}
	/* public function ModificarVehiculoParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update vehiculo 
				set patente=:patente,
				color=:color,
				marca=:marca,
				ingreso=:ingreso
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':titulo',$this->titulo, PDO::PARAM_INT);
			$consulta->bindValue(':anio', $this->año, PDO::PARAM_STR);
			$consulta->bindValue(':cantante', $this->cantante, PDO::PARAM_STR);
			return $consulta->execute();
	 }

  	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->titulo."  ".$this->cantante."  ".$this->año;
	}
	
	 
	 


  	public static function TraerTodoLosCds()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,titel as titulo, interpret as cantante,jahr as año from cds");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "cd");		
	}

	public static function TraerUnCd($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, titel as titulo, interpret as cantante,jahr as año from cds where id = $id");
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('cd');
			return $cdBuscado;				

			
	}

	public static function TraerUnCdAnio($id,$anio) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=? AND jahr=?");
			$consulta->execute(array($id, $anio));
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}

	public static function TraerUnCdAnioParamNombre($id,$anio) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			$consulta->bindValue(':anio', $anio, PDO::PARAM_STR);
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}
	
	public static function TraerUnCdAnioParamNombreArray($id,$anio) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
			$consulta->execute(array(':id'=> $id,':anio'=> $anio));
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}
*/


}