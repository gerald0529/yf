<?php
	/**
	 * 验证码
	 */
	include './Verify/Verify.class.php';
	$verify = new Verify(); 
	$verify->useCurve = true;
	$verify->useNoise = false;
	$verify->bg = array(255, 255, 255);
    $verify->config = array(255, 255, 255);
    
    $verify->length = 4;        
    $verify->fontSize = 14;
    $verify->imageW = 100;
    $verify->imageH = 33;
	$verify->entry();
    
 
