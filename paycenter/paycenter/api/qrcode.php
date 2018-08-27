<?php

require_once '../configs/config.ini.php';
include  LIB_PATH . "/phpqrcode/phpqrcode.php";

$data = $_REQUEST["data"];

QRcode::png($data);

die();
?>