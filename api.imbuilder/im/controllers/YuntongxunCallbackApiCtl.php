<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class YuntongxunCallbackApiCtl extends Yf_AppController
{
	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		//include $this->view->getView();
	}

	public function CallAuth()
	{
		//获取POST数据
		$result = file_get_contents("php://input");
		//读取日志文件
		$filename = "./log.txt";
		$handle   = fopen($filename, 'a');
		//写入数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "result:" . $result . "\n");
		//解析XML
		$xml = simplexml_load_string(trim($result, " \t\n\r"));
		//获取XML数据
		$action  = $xml->action;
		$type    = $xml->type;
		$orderid = $xml->orderid;
		$subid   = $xml->subid;
		$caller  = $xml->caller;
		$called  = $xml->called;
		$subtype = $xml->subtype;
		$callSid = $xml->callSid;
		//写入解析后数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "action:" . $action . " type:" . $type . " orderid:" . $orderid . " subid:" . $subid . " caller:" . $caller . " called:" . $called . " subtype:" . $subtype . " callSid:" . $callSid . "\n");
		//TODO 请在此处增加逻辑判断代码

		$strXML = "<?xml version='1.0' encoding='utf-8'?>
              <Response>
              <statuscode>0000</statuscode>
              <statusmsg>状态描述信息</statusmsg>
              <record>1</record>
              </Response>";
		echo $strXML;
	}

	public function CallEstablish()
	{
		//获取POST数据
		$result = file_get_contents("php://input");
		//读取日志文件
		$filename = "./log.txt";
		$handle   = fopen($filename, 'a');
		//写入数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "result:" . $result . "\n");
		//解析XML
		$xml = simplexml_load_string(trim($result, " \t\n\r"));
		//获取XML数据
		$action  = $xml->action;
		$type    = $xml->type;
		$orderid = $xml->orderid;
		$subid   = $xml->subid;
		$caller  = $xml->caller;
		$called  = $xml->called;
		$subtype = $xml->subtype;
		$callSid = $xml->callSid;
		//写入解析后数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "action:" . $action . " type:" . $type . " orderid:" . $orderid . " subid:" . $subid . " caller:" . $caller . " called:" . $called . " subtype:" . $subtype . " callSid:" . $callSid . "\n");
		//TODO 请在此处增加逻辑判断代码

		$strXML = "<?xml version='1.0' encoding='utf-8'?>
              <Response>
              <statuscode>0000</statuscode>
              <statusmsg>状态描述信息</statusmsg>
              <billdata>billdata</billdata>
              <sessiontime>30</sessiontime>
              </Response>";
		echo $strXML;
	}

	public function Hangup()
	{
		//获取POST数据
		$result = file_get_contents("php://input");
		//读取日志文件
		$filename = "./log.txt";
		$handle   = fopen($filename, 'a');
		//写入数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "result:" . $result . "\n");
		//解析XML
		$xml = simplexml_load_string(trim($result, " \t\n\r"));
		//获取XML数据
		$action    = $xml->action;
		$type      = $xml->type;
		$orderid   = $xml->orderid;
		$subid     = $xml->subid;
		$caller    = $xml->caller;
		$called    = $xml->called;
		$starttime = $xml->starttime;
		$endtime   = $xml->endtime;
		$billdata  = $xml->billdata;
		$subtype   = $xml->subtype;
		$callSid   = $xml->callSid;
		$recordurl = $xml->recordurl;
		$byetype   = $xml->byetype;
		//写入解析后数据
		fwrite($handle, date("Ymd H:i:s") . "\n");
		fwrite($handle, "action:" . $action . " type:" . $type . " orderid:" . $orderid . " subid:" . $subid . " caller:" . $caller . " called:" . $called . " starttime:" . $starttime . " endtime:" . $endtime . " billdata:" . $billdata . " subtype:" . $subtype . " callSid:" . $callSid . " recordurl:" . $recordurl . " byetype:" . $byetype . "\n");
		//TODO 请在此处增加逻辑判断代码

		$strXML = "<?xml version='1.0' encoding='utf-8'?>
              <Response>
              <statuscode>0000</statuscode>
              <statusmsg>状态描述信息</statusmsg>
              <record>1</record>
              </Response>";
		echo $strXML;
	}
}

?>