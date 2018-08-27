<?php 
/**
 * 目前支持百度bos,
 * 
 * https://cloud.baidu.com/doc/BOS/ProductDescription.html#.E6.A6.82.E8.BF.B0
 * 后一个3.2.0.p1将支持阿里oss
 * @var [type]
 */
$bos_config = [
	'open'=>'',//ali阿里云  bos 百度云 
	'bos'=>[
			'accessKeyId'=>'',
			'secretAccessKey'=>'',
			'endpoint'=> '',
			'bucketName'=> '',
	],
	'ali'=>[
			'accessKeyId'=>'',
			'secretAccessKey'=>'',
			'endpoint'=> '',
			'bucketName'=> '',
	],
	
];

return $bos_config;