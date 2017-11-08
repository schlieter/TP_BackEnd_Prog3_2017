<?php
//include_once "./EmpleadoDB.php";
class Empleado{

    public $nombre;
    public $apellido;
    public $mail;
    public $clave;
    public $turno;
    public $ingreso;

    public static function Ingreso($mail, $clave){
        $empleados = Empleado::TodosLosEmpleados();
        foreach($empleados as $var){
            if($mail == $var->mail && $clave == $var->clave){
                return "Acceso permitido";
            }
        }
        return "Acceso denegado";
    }
    
    public static function TraerUno($mail, $clave){
        $empleados = Empleado::TodosLosEmpleados();
        foreach($empleados as $var){
            if($mail == $var->mail && $clave == $var->clave){
                return $var;
            }
        }
    }

    public function VerificarPorMail($mail){
        $empleados = Empleado::TodosLosEmpleados();
        foreach($empleados as $var){
            if($mail == $var->mail){
                return 1;
            }
        }
        return 0;
    }
    public function VerificarPorNombreApellido($nombre,$apellido){
        $empleados = $this->TodosLosEmpleados();
        foreach($empleados as $var){
            if($nombre == $var->nombre && $apellido == $var->apellido){
                return 1;
            }
        }
        return 0;
    }

    public function Guardar(){
        if($this->VerificarPorMail($this->mail) == 1){
            return "El mail ya se encuentra utilizado por otro usuario";
        }
        $empleadoDB = new EmpleadoDB();
        $empleadoDB->nombre = $this->nombre;
        $empleadoDB->apellido = $this->apellido;
        $empleadoDB->mail = $this->mail;
        $empleadoDB->clave = $this->clave;
        $empleadoDB->turno = $this->turno;
        $empleadoDB->ingreso = $this->ingreso;
        $empleadoDB->InsertarEmpleado();
        return "OK";
    }
    
	public function ToString(){
		return $this->nombre." ".$this->apellido." ".$this->turno." ".$this->mail." ".$this->ingreso;
	}

    public static function Borrar($mail,$usuario){
        if($usuario == "admin@utn.com.ar"){
            if(Empleado::VerificarPorMail($mail) == 0){
                return "El empleado no se encuentra registrado";
            }
            $empleadoDB = new EmpleadoDB();
            $empleadoDB = EmpleadoDB::TraerUnEmpleado($mail);
            $empleadoDB->BorrarEmpleado();
            return "ok";
        }
        else{
            return "Usted no tiene permiso de administrador";
        }
    }

    public function TodosLosEmpleados(){
        return EmpleadoDB::TraerTodosLosEmpleados();
    }

    public function Modificar($usuario){
        if($usuario == "admin@utn.com.ar"){
            if($this->VerificarPorNombreApellido($this->nombre,$this->apellido) == 1){
                $empleadoDB = new EmpleadoDB();
                $empleadoDB = EmpleadoDB:: TraerUnEmpleadoNombreApellido($this->nombre,$this->apellido);
                $empleadoDB->mail = $this->mail;
                $empleadoDB->clave = $this->clave;
                $empleadoDB->turno = $this->turno;
                $empleadoDB->ModificarEmpleado();
                return "ok";
            }
        }
        else{
            return "Usted no tiene permiso de administrador";            
        }
        return "El empleado no se encuentra registrado";
    }
}
