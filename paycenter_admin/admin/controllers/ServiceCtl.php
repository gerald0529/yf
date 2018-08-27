<?php
/**
 * jsonp
 *
 * 其它服务器回调
 *
 * @category   Framework
 * @package    Controller
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class ServiceCtl extends Yf_AppController
{
	public function index()
	{
		switch($_REQUEST['type'])
		{
			case 'getsystemmsgforbscm3' :
				$this->getSystemMsgForBsCm3();
				break;
			case 'getallunreadcountforbscm3' :
				$this->getAllUnreadCountForBsCm3();
				break;
			default :
				break;
		}
	}

	public function getSystemMsgForBsCm3()
	{
		echo 'jQuery110205482904262377198_1425607003893({"status":200,"msg":"success","data":[{"msgid":"20000000115","msglinkcolor":"d9254a","msglink":"","msgtitle":"远丰售后启用400通知>>"},{"msgid":"20000000068","msglinkcolor":"d9254a","msglink":"","msgtitle":"远丰在线软件备案信息资料>>"}]})';
	}

	public function getAllUnreadCountForBsCm3()
	{
		echo 'jQuery110204344604119879111_1425607003221({"status":200,"msg":"success","count":33,"percount":0,"syscount":33})';
	}
}
?>
