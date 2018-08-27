<?php
class TestCtl extends Yf_AppController
{
	public function index()
	{ 	
		 $r = ImLog::getList([
		 	'name'=>'hp01',
		 	'is_read'=>0,
		 	'group'=>1
		 ]);
		 
		 print_r($r); 
		 
	} 


}
 