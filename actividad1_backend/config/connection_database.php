<?php
function initConnectionDb() {
    $db_host = 'localhost:33065';
    $db_user = 'root'; // Cambia si usas otro usuario en MAMP
    $db_password = ''; // Cambia si usas otra contraseña en MAMP
    $db_name = 'actividad1_backend'; // Asegúrate de que este sea el nombre correcto de tu base de datos

    $mysqli = @new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($mysqli->connect_error) {
        die('Error en la conexión: ' . $mysqli->connect_error);
    }

    return $mysqli;
}
//MODIFICACION TEST SUBIR COMMIT
?>


