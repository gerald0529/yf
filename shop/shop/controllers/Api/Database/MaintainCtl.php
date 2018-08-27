<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Api_Database_MaintainCtl extends Api_Controller
{

    public $databaseMaintainModel = null;

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
        $this->databaseMaintainModel = new Database_Maintain();

    }

    public function TableList()
    {

        $res = $this->databaseMaintainModel->getTableList();

        if(!($res===false)&&!empty($res))
        {
            $status=200;
            $msg='';
            $data['items']=$res['res'];
            $data['records']=count($res['res']);
            foreach($data['items'] as $ke=>$va)
            {
                if(in_array($data['items'][$ke]['Name'],$res['tables']))
                {
                    $data['items'][$ke]['is_use']='true';
                }
                else
                    $data['items'][$ke]['is_use']='false';
            }
        }
        else
        {
            $status=250;
            $msg='failure';
            $data=array();
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function manage()
    {
        $obj=json_decode($_REQUEST['data']);
        fb($_REQUEST['data']);
        $Db  = Yf_Db::get("shop");
        $Dbh = $Db->getDbHandle();
        $db_name=$this->databaseMaintainModel->getDbName();
        if($obj->action=='repair'&&!empty($obj->tables))
        {
            foreach($obj->tables as $ke=>$va)
            {
                if(!empty($va->name))
                {
                    if($va->engine=='InnoDB')
                    {
                        $ret=$this->databaseMaintainModel->tableQuery(' ALTER TABLE '.$va->name.' ENGINE = INNODB ;');
                        if($ret===false)
                        {
                            $status=250;
                            $msg='ALTER TABLE '.$va->name.' ENGINE = INNODB ;';
                            break;
                        }
                    }
                    else if($va->engine=='MyISAM')
                    {
                        $ret=$this->databaseMaintainModel->tableQuery(' REPAIR TABLE '.$va->name.' ; ');
                        if($ret===false)
                        {
                            $status=250;
                            $msg=' REPAIR TABLE '.$va->name.' ; ';
                            break;
                        }
                    }
                }
            }
        }
        else if($obj->action=='optimize'&&!empty($obj->tables))
        {
            foreach($obj->tables as $ke=>$va)
            {
                if(!empty($va->name))
                {
                    $ret=$this->databaseMaintainModel->tableQuery('optimize table '.$va->name.';');
                    if($ret===false)
                    {
                        $status=250;
                        $msg='optimize table '.$va->name.';';
                        break;
                    }
                }
            }
        }
        $data=array();
        $msg=empty($msg)?'':$msg;
        $status=empty($status)?200:250;
        $this->data->addBody(-140, $data, $msg, $status);
    }


}

?>