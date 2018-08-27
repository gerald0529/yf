<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_ProtocolCtl extends Yf_AppController
{
	public $baseProtocolModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		//include $this->view->getView();
		$this->baseProtocolModel = new Base_ProtocolModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 管理界面
	 *
	 * @access public
	 */
	public function manage()
	{
		include $this->view->getView();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function ProtocolList()
	{
		$user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->baseProtocolModel->getProtocolList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->baseProtocolModel->getProtocolList('*', $page, $rows, $sort);
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$protocol_id = $_REQUEST['protocol_id'];
		$rows        = $this->baseProtocolModel->getProtocol($protocol_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['protocol_id'] = $_REQUEST['protocol_id']; // 协议索引Id
		$data['cmd_id']      = $_REQUEST['cmd_id']; // 协议Id
		$data['ctl']         = $_REQUEST['ctl']; // 控制器类名称
		$data['met']         = $_REQUEST['met']; // 控制器方法
		$data['db']          = $_REQUEST['db']; // 连接数据库类型
		$data['typ']         = $_REQUEST['typ']; // 输出数据默认类型
		$data['rights_id']   = $_REQUEST['rights_id']; // 权限Id
		$data['log']         = $_REQUEST['log']; // 是否记录日志
		$data['struct']      = $_REQUEST['struct']; // 生成结构体，独立使用
		$data['comment']     = $_REQUEST['comment']; // 注释
		$data['`0`']         = $_REQUEST['`0`']; // 第一个字段
		$data['`1`']         = $_REQUEST['`1`']; //
		$data['`2`']         = $_REQUEST['`2`']; //
		$data['`3`']         = $_REQUEST['`3`']; //
		$data['`4`']         = $_REQUEST['`4`']; //
		$data['`5`']         = $_REQUEST['`5`']; //
		$data['`6`']         = $_REQUEST['`6`']; //
		$data['`7`']         = $_REQUEST['`7`']; //
		$data['`8`']         = $_REQUEST['`8`']; //
		$data['`9`']         = $_REQUEST['`9`']; //
		$data['`10`']        = $_REQUEST['`10`']; //
		$data['`11`']        = $_REQUEST['`11`']; //
		$data['`12`']        = $_REQUEST['`12`']; //
		$data['`13`']        = $_REQUEST['`13`']; //
		$data['`14`']        = $_REQUEST['`14`']; //
		$data['`15`']        = $_REQUEST['`15`']; //
		$data['`16`']        = $_REQUEST['`16`']; //
		$data['`17`']        = $_REQUEST['`17`']; //
		$data['`18`']        = $_REQUEST['`18`']; //
		$data['`19`']        = $_REQUEST['`19`']; //
		$data['`20`']        = $_REQUEST['`20`']; //
		$data['`21`']        = $_REQUEST['`21`']; //
		$data['`22`']        = $_REQUEST['`22`']; //
		$data['`23`']        = $_REQUEST['`23`']; //
		$data['`24`']        = $_REQUEST['`24`']; //
		$data['`25`']        = $_REQUEST['`25`']; //
		$data['`26`']        = $_REQUEST['`26`']; //
		$data['`27`']        = $_REQUEST['`27`']; //
		$data['`28`']        = $_REQUEST['`28`']; //
		$data['`29`']        = $_REQUEST['`29`']; //
		$data['`30`']        = $_REQUEST['`30`']; //
		$data['`31`']        = $_REQUEST['`31`']; //
		$data['`32`']        = $_REQUEST['`32`']; //
		$data['`33`']        = $_REQUEST['`33`']; //
		$data['`34`']        = $_REQUEST['`34`']; //


		$protocol_id = $this->baseProtocolModel->addProtocol($data, true);

		if ($protocol_id)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data['protocol_id'] = $protocol_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$protocol_id = $_REQUEST['protocol_id'];

		$flag = $this->baseProtocolModel->removeProtocol($protocol_id);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data['protocol_id'] = $protocol_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['protocol_id'] = $_REQUEST['protocol_id']; // 协议索引Id
		$data['cmd_id']      = $_REQUEST['cmd_id']; // 协议Id
		$data['ctl']         = $_REQUEST['ctl']; // 控制器类名称
		$data['met']         = $_REQUEST['met']; // 控制器方法
		$data['db']          = $_REQUEST['db']; // 连接数据库类型
		$data['typ']         = $_REQUEST['typ']; // 输出数据默认类型
		$data['rights_id']   = $_REQUEST['rights_id']; // 权限Id
		$data['log']         = $_REQUEST['log']; // 是否记录日志
		$data['struct']      = $_REQUEST['struct']; // 生成结构体，独立使用
		$data['comment']     = $_REQUEST['comment']; // 注释
		$data['`0`']         = $_REQUEST['`0`']; // 第一个字段
		$data['`1`']         = $_REQUEST['`1`']; //
		$data['`2`']         = $_REQUEST['`2`']; //
		$data['`3`']         = $_REQUEST['`3`']; //
		$data['`4`']         = $_REQUEST['`4`']; //
		$data['`5`']         = $_REQUEST['`5`']; //
		$data['`6`']         = $_REQUEST['`6`']; //
		$data['`7`']         = $_REQUEST['`7`']; //
		$data['`8`']         = $_REQUEST['`8`']; //
		$data['`9`']         = $_REQUEST['`9`']; //
		$data['`10`']        = $_REQUEST['`10`']; //
		$data['`11`']        = $_REQUEST['`11`']; //
		$data['`12`']        = $_REQUEST['`12`']; //
		$data['`13`']        = $_REQUEST['`13`']; //
		$data['`14`']        = $_REQUEST['`14`']; //
		$data['`15`']        = $_REQUEST['`15`']; //
		$data['`16`']        = $_REQUEST['`16`']; //
		$data['`17`']        = $_REQUEST['`17`']; //
		$data['`18`']        = $_REQUEST['`18`']; //
		$data['`19`']        = $_REQUEST['`19`']; //
		$data['`20`']        = $_REQUEST['`20`']; //
		$data['`21`']        = $_REQUEST['`21`']; //
		$data['`22`']        = $_REQUEST['`22`']; //
		$data['`23`']        = $_REQUEST['`23`']; //
		$data['`24`']        = $_REQUEST['`24`']; //
		$data['`25`']        = $_REQUEST['`25`']; //
		$data['`26`']        = $_REQUEST['`26`']; //
		$data['`27`']        = $_REQUEST['`27`']; //
		$data['`28`']        = $_REQUEST['`28`']; //
		$data['`29`']        = $_REQUEST['`29`']; //
		$data['`30`']        = $_REQUEST['`30`']; //
		$data['`31`']        = $_REQUEST['`31`']; //
		$data['`32`']        = $_REQUEST['`32`']; //
		$data['`33`']        = $_REQUEST['`33`']; //
		$data['`34`']        = $_REQUEST['`34`']; //


		$protocol_id = $_REQUEST['protocol_id'];
		$data_rs     = $data;

		unset($data['protocol_id']);

		$flag = $this->baseProtocolModel->editProtocol($protocol_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>