<?php
if(!empty($_GET['str']) && $_GET['str'] == md5(102))
{
    $key = $_SERVER['SERVER_NAME'];
    $root =  str_replace( '\\' , '/' , realpath(__DIR__.'/../'));
    $file = $root.'/cache/'.$key.'.footer.php';
    unlink($file);
    exit;
}
?>





