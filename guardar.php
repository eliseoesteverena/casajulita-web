<?php

include 'file.php';

$conteo = count($_FILES["archivos"]["name"]);
$files = array();
$dir = "../uploads/";
checkFiles($dir);
try {
    for ($i = 0; $i < $conteo; $i++) {
        $ubicacionTemporal = $_FILES["archivos"]["tmp_name"][$i];
        $msg = $ubicacionTemporal;
        $nombreArchivo = $_FILES["archivos"]["name"][$i];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        // Renombrar archivo
        $uniqid = sprintf("%s_%d.%s", uniqid(), $i, $extension);
        $reemplazo = reemplazarEspeciales($nombreArchivo);
        $nuevoNombre = $reemplazo . $uniqid;
        $ubicacionFinal = '../uploads/' . $nuevoNombre;
        // Mover del temporal al directorio actual
        move_uploaded_file($ubicacionTemporal, $ubicacionFinal);
        $msg = array('original_name' => $nombreArchivo, 'new_name' => $nuevoNombre);
        $files[] = $msg;
    }
} catch (Exception $e) {
    echo json_encode('Excepción capturada: ',  $e->getMessage(), "\n");
}

function reemplazarEspeciales($string) {
    // Lista original de caracteres especiales y con tilde a reemplazar
    $especialesOriginales = array(
        'á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ',
        'ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü',
        'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù',
        'â', 'ê', 'î', 'ô', 'û', 'Â', 'Ê', 'Î', 'Ô', 'Û',
        'ã', 'õ', 'Ã', 'Õ', 'ç', 'Ç',
        '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'
    );
    
    // Lista de caracteres especiales del archivo adjunto
    $especialesAdicionales = array(
        '–', '—', '‘', '’', '‚', '“', '”', '„', '†', '‡', '•', '…', '‰', '€', '™', 'Œ', 'œ', 'Š', 'š', 'Ÿ', 'ƒ', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ',
        'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç',
        'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', '¡', '¢', '£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '­', '®', '¯',
        '{', '|', '}', '~', '`', '[', '\\', ']', '^', '_', '@', ':', ';', '<', '=', '>', '?', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/'
    );
    
    // Combinar las dos listas y eliminar duplicados
    $especiales = array_unique(array_merge($especialesOriginales, $especialesAdicionales));
    
    // Reemplazar cada carácter especial por un guion
    $string = str_replace($especiales, '-', $string);
    
    // Eliminar caracteres no alfanuméricos que no hayan sido reemplazados
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);
    
    // Reemplazar múltiples guiones consecutivos por uno solo
    $string = preg_replace('/-+/', '-', $string);
    
    // Retornar el string resultante
    return $string;
}


// Responder al cliente
echo json_encode($files);
