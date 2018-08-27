<?=format_money($this->user_money)?>

<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;

?>


<span><?=format_money($this->user_money_frozen)?></span>

<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;

?>
