<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Service_IdeaCtl extends Yf_AppController
{
    public $serviceIdeaModel = null;

    /**
     * 初始化方法，构造函数
     *
     * @access public
     */
    public function init()
    {
        //include $this->view->getView();
        $this->serviceIdeaModel = new Service_IdeaModel();
    }

    public  function index()
    {
        include $this->view->getView();
    }

    public function manage()
    {
        include $this->view->getView();
    }

    public function ideaList()
    {
        $serviceIdeaModel = new Service_IdeaModel();
        $data1 = $serviceIdeaModel->getServiceList('*');
        $data['page'] = $data1['page'];
        $data['total'] = $data1['total'];
        $data['totalsize'] = $data1['totalsize'];
        $data['records'] = $data1['records'];
        $data2 = $data1['items'];
        foreach($data2 as $key=>$value)
        {
            $rows[$key]['id'] = $value['idea_id'];
            $rows[$key]['idea_id'] = $value['idea_id'];
            $rows[$key]['title'] = $value['title'];
            $rows[$key]['idea'] = $value['idea'];
            $rows[$key]['creat_time'] = date('Y-m-d h:i:s',$value['creat_time']);
            $creat_id = $value['creat_id'];
            if($creat_id)
            {
                /*$userBaseModel = new User_BaseModel();
                $userbase = $userBaseModel->get($creat_id);
                if($userbase)
                {*/
                    //$rows[$key]['creat_id'] = $creat_id;
                    $rows[$key]['creat_name'] = $creat_id;
                //}
            }
            $idea_status = $value['idea_status'];
            if($idea_status==0)
            {
                $rows[$key]['status'] = '未回复';
                $rows[$key]['respon_time'] = '';
                $rows[$key]['respon_id'] = '';
                $rows[$key]['respon'] = '';
            }
            elseif($idea_status==1)
            {
                $rows[$key]['status'] = '已回复';
                $rows[$key]['respon_time'] = date('Y-m-d h:i:s',$value['respon_time']);
                $rows[$key]['respon_id'] = $value['respon_id'];
                $rows[$key]['respon'] = $value['respon'];
            }
        }
        $data['rows'] = $rows;
        if($rows)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function get()
    {
        $idea_id = $_REQUEST['idea_id'];
        if($idea_id)
        {
            $serviceIdeaModel = new Service_IdeaModel();
            $data = $serviceIdeaModel->get($idea_id);
            $data1 = $data[$idea_id];
            $creat_id = $data1['creat_id'];

            if($creat_id)
            {
                $data1['creat_name'] = $creat_id;
            }

            $creat_time = $data1['creat_time'];
            if($creat_time)
            {
                unset($data1['creat_time']);
                $data1['creat_time'] = date('Y-m-d h:i:s',$creat_time);
            }

            if($data1)
            {
                $msg = 'success';
                $status = 200;
            }
            else
            {
                $msg = 'failure';
                $status = 250;
            }
            $this->data->addBody(-140,$data1,$msg,$status);
        }

    }

    public function edit()
    {
        $user_id = $_COOKIE['id'];
        $idea_id = $_REQUEST['idea_id'];
        $respon = $_REQUEST['respon'];
        $serviceIdeaModel = new Service_IdeaModel();
        $data['respon'] = $respon;
        $data['respon_time'] = time();
        $data['idea_status'] = 1;
        $data['respon_id'] = $user_id;

        $flag = $serviceIdeaModel->edit($idea_id,$data);
        if($flag)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function remove()
    {
        $id = $_REQUEST['idea_id'];
        $serviceIdeaModel = new Service_IdeaModel();
        if($id)
        {
            $flag = $serviceIdeaModel->remove($id);
        }
        if($flag)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
        $data['id'] = $id;
        $this->data->addBody(-140,$data,$msg,$status);
    }
}
?>