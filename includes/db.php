<?php ob_start();

$db['db_host'] = 'localhost';  // enter your host
$db['db_user'] = 'root';       // enter db username
$db['db_pass'] = '';           // enter db password
$db['db_name'] = 'cms';        // enter db name

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
    or die(mysqli_error($connection));

?>