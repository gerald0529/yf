<?php 
 
include  __DIR__ . "/../../libraries/phpqrcode/qrlib.php"; 
echo   QRcode::png($_REQUEST['data']);    
exit;