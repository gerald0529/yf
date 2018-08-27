<?php
/**
 * 分销模块
 *
 *
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Plugin_Directseller implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	public function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('rec_goods', $this, 'recGoods');
		Yf_Plugin_Manager::getInstance()->register('regDone', $this, 'regDone');
	}

	public static function desc()
	{
		return '分销员系统，使用分销员分佣时，请勿关闭！';
	}
	
	public function recGoods()
	{
        
		$data = array();
		$rec = request_string('rec');
		
		if($rec)
		{
			$cond_row = array();
			$cond_row['shop_directseller_goods_common_code'] = $rec;
			$Distribution_ShopDirectsellerGoodsCommonModel = new Distribution_ShopDirectsellerGoodsCommonModel();
			$recImages = $Distribution_ShopDirectsellerGoodsCommonModel->getOne($cond_row);

			setcookie('yf_recserialize',$rec,time()+60*60*24*3);

			if(!empty($recImages['directseller_images_image']))
			{
				$data = explode(',',$recImages['directseller_images_image']);
			}
		}

		return $data;
	}
	
	/**
	 * 注册完成后，判断是否需要建立分佣关系
	 *
	 * @return mixed
	 */
	public function regDone($user_id)
	{
		$rec = $_COOKIE['yf_recserialize'];

		$b= (strpos($rec,"u"));
		$e= (strpos($rec,"s"));
		$data['user_parent_id'] = substr($rec,$b+1,$e-1); 
		
		/* $User_BaseModel = new User_BaseModel();
		$User_BaseModel->editBase($userid,$data); */
		
		$User_InfoModel = new User_InfoModel();
		$User_InfoModel->editInfo($user_id,$data);
		
		return true;
	}
}
?>