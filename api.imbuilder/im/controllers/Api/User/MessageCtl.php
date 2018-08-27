<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * 消息托管
 */
class Api_User_MessageCtl extends Yf_AppController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function getList()
    {
        $status = 200;
        $msg = 'success';
        $rows = $_REQUEST['rows'];
        $page = $_REQUEST['page'];
        $sort = $_REQUEST['sord'];
        $msg_list = ImLog::getList([
            'name' => 'hp01',
            'is_read' => 0,
            'group' => 1,
        ]);
        $items = $msg_list['items'];
        if ($items) {
            foreach ($items as $key => $value) {
                $newItems[$key] = $value;
                $newItems[$key]['reply'] = "<a href='" . '?ctl=Message_Tuo&met=message_view&sender=' . $value['msg_sender'] . "&receiver=" . $value['msg_receiver'] . "'>回复</a>";
                $newItems[$key]['msg_sender'] = $value['msg_sender'] . "(<b style='color:red'>" . $value['num'] . "</b>)";
            }
            $msg_list['items'] = $newItems;
        }
        $this->data->addBody(-140, $msg_list, $msg, $status);
    }

}
 