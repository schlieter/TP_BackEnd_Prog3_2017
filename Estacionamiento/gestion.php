<?php
    include_once "./Clases/estacionamiento.php";
    include_once "./Clases/vehiculo.php";
    $nombre = $_POST["nombre"];
    $patente = $_POST['patente'];
    $foto = $_POST["foto"];
    //$accion = $_POST["accion"];
    //var_dump($_GET);
    //var_dump($_POST);
    //var_dump($_FILES);
    //var_dump($patente);
    //var_dump($accion);

    /*
    Si la accion es guardar pasar el vehiculo al metodo guardar de estacionamiento, 
    de ser sacar se llamara al metodo sacar de estacionamiento pasandole el vehiculo como parametro
    */
    
    /*$auto = new vehiculo($patente,$nombre);
    $auto->fechIngreso = date("Y/m/d H:i:s");
    var_dump($auto);
    Estacionamiento::Guardar($auto);*/
    $destino = "./Fotos/"."fotoUno.png";
    $destino2 = "./Fotos/".$nombre.".png";
    
    move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
    
    $im = imagecreatefrompng($destino);
    $estampa = imagecreatefrompng('fotoDos.png');
    
    
    
    // Establecer los márgenes para la estampa y obtener el alto/ancho de la imagen de la estampa
    $margen_dcho = 10;
    $margen_inf = 10;
    $sx = imagesx($estampa);
    $sy = imagesy($estampa);
    
    // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
    // ancho de la foto para calcular la posición de la estampa. 
    imagecopy($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));
    
    // Imprimir y liberar memoria
    header('Content-type: image/png');
    //imagepng($im);
    imagepng($im,$destino2);
    imagedestroy($im);
