<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_App_NotifyCtl extends Api_Controller
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

    }


    public function getAppNotify()
    {
        $App_NotifyModel = new App_NotifyModel();
        $cond_row['user_id']  = request_int('user_id');

        $data    = $App_NotifyModel->getOneNotify($cond_row);

        if ($data)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     * 编辑
     *
     */
    public function editNotify()
    {
        $App_NotifyModel = new App_NotifyModel();
        $cond_row['user_id']  = request_int('user_id');

        $data    = $App_NotifyModel->getOneNotify($cond_row);

        $field =  request_string('field');
        $update = request_int('update');

        if($data){

            $id = $data['app_notify_id'];
            unset($data['id']);
            unset($data['app_notify_id']);

            $data[$field] = $update;

            $flag = $App_NotifyModel->editNotify($id, $data);
        }
        else
        {
            $data['user_id'] = request_int('user_id');
            $data[$field] = $update;

            $flag = $App_NotifyModel->addNotify($data, true);

        }

        if ($flag !== false)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }



        $this->data->addBody(-140, $data, $msg, $status);
    }


}

?>