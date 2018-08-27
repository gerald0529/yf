<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}



/**
 *  im是否显示，
 *  解决
 * @todo
 */
class Api_IM_ImCtl extends Yf_AppController
{
	   
	public function index()
	{
		


	  if(Web_ConfigModel::value('im_statu')==1  ){ 
	 
		  	$str = Yf_Registry::get('base_url')."/im/index.php";
		   
		  	echo trim($str);
		  	exit;
	  } 


	 
	}
 
}

 