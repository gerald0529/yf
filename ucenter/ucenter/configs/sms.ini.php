<?php

$sms_config = array();

$sms_config['sms_account'] = '';
$sms_config['sms_pass'] = '';

Yf_Registry::set('sms_config', $sms_config);

return $sms_config;
?>
