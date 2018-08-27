<?php 

$data = ob_get_contents();
ob_clean();

echo preg_replace_callback('|.*</head>|',function()use($_js_header){
			
			return $_js_header.'</head>';
			
		}, $data);

 