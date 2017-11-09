<?php
//require_once "./VehiculoDB.php";
class Vehiculo{

    public $patente;
	public $marca;
	public $color;
    public $ingreso;
    public $egreso;
	public $importe=0;
	public $empleadoIngreso;
    public $empleadoEgreso;

    public function VerificarPorPatente($patente){
        $estacionados = Vehiculo::TodosLosEstacionados();
        foreach($estacionados as $var){
            if($patente == $var->patente){
                return 1;
            }
        }
        return 0;
    }

    public function Guardar(){
        if($this->VerificarPorPatente($this->patente) == 1){
            return "El vehiculo ya se encuentra estacionado";
        }
        $autoDB = new VehiculoDB();
        $autoDB->patente = $this->patente;
        $autoDB->empleadoIngreso = $this->empleadoIngreso;
        $autoDB->color = $this->color;
        $autoDB->marca = $this->marca;
        $autoDB->ingreso = $this->ingreso;
        $autoDB->InsertarVehiculo();
        return "OK";
    }

    public function CalcularImporte(){
		$this->egreso = date("Y/m/d H:i:s");
        $tiempoMin =(strtotime($this->egreso) - strtotime($this->ingreso))/60;
        if($tiempoMin <= 60){
        	$this->importe = 10;
        }elseif($tiempoMin > 60 && $tiempoMin <= 720){
        	$this->importe = 90;
        }else{
        	$this->importe = 170;
        }
    }
    
    public function ToString(){
		return $this->patente." ".$this->marca." ".$this->color." ".$this->empleadoIngreso." ".$this->ingreso;
	}

    public static function Borrar($patente,$empleado){
        if(Vehiculo::VerificarPorPatente($patente) == 0){
            return "El vehiculo no se encuentra estacionado";
        }
        $autoDB = new VehiculoDB();
        $auto = new Vehiculo();
        $autoDB = VehiculoDB::TraerUnVehiculo($patente);
        $autoDB->BorrarVehiculo();
        $auto->patente = $autoDB->patente;
        $auto->marca = $autoDB->marca;
        $auto->color = $autoDB->color;        
        $auto->ingreso = $autoDB->ingreso;
        $auto->empleadoIngreso = $autoDB->empleadoIngreso;
        $auto->empleadoEgreso = $empleado;
        $auto->CalcularImporte();
        $autoDB->empleadoEgreso = $auto->empleadoEgreso;
        $autoDB->egreso = $auto->egreso;
        $autoDB->importe = $auto->importe;
        $autoDB->InsertarVehiculoHistorial();
        return "ok";
    }

    public function TodosLosEstacionados(){
        return VehiculoDB::TraerTodosLosEstacionados();
    }

    public function Modificar(){
        if($this->VerificarPorPatente($this->patente) == 1){
            $autoDB = new VehiculoDB();
            $autoDB = VehiculoDB::TraerUnVehiculo($this->patente);
            $autoDB->marca = $this->marca;
            $autoDB->color = $this->color;
            $autoDB->empleadoIngreso = $this->empleadoIngreso;
            $autoDB->ModificarVehiculo();
            return "ok";
        }
        return "El vehiculo no se encuentra estacionado";
    }

    public function Historial(){
        return VehiculoDB::TraerHistorial();
    }
}