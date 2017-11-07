<?php

//////////////////Actualizar



class Estacionamiento{
    //toma el vehiculo y lo guarda en un archivo

    //abrir un puntero a un archivo

    /*public static function Guardar($patente,$email){
        $auto = new vehiculo($patente,$email);
        $auto->fechIngreso = date("Y/m/d H:i:s");
        $archivo = fopen("../Archivos/estacionados.txt","a");
        $renglon = $auto->nombre."-".$auto->patente."-".$auto->fechIngreso."\n";
        fwrite($archivo,$renglon);
        fclose($archivo);
        return "ok";
    }
    //leer explode
    //verifica
    //calcular costo strtotime(feingreso,feEgreso)  *10p(valor del segundo)
    //sacar del array
    //guardar estacionados
    //pegar en facturados
   /* public static function Sacar($auto){
        $archivo = fopen("./Archivos/estacionados.txt","r");
        //fread($archivo);
        //$string = explode("-",$archivo);
        while(!feof($archivo)){
            $datos = fgets($archivo);
            $miArray = explode("-",$datos);
            $todosLosAutos += $miArray;          
        }
        foreach($todosLosAutos as $var){
            if($var->patente == $auto->patente){
                $costo =10 * (strtotime($date("Y/m/d H:i:s")) - strtotime($var->fechIngreso));
                $todosLosAutos.delete               
            }

        }

    }*/
    public static function Ingreso($email, $clave){
        if($email =="admin@utn.com" && $clave =="1234" || $email =="usuario@utn.com" && $clave =="hola"){
            return "Acceso permitido";
        }
        else{
            return "Acceso denegado";
        }
    }
}

