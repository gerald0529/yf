<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_MsgModel extends User_Msg
{
    public function getUserList($msg_log_id = null, $page=1, $rows=100, $sort='asc')
    {
        //需要分页如何高效，易扩展
        $offset = $rows * ($page - 1);

        $this->sql->setLimit($offset, $rows);

        $user_log_id_row = array();
        $user_log_id_row = $this->selectKeyLimit();

        //读取主键信息
        $total = $this->getFoundRows();

        $data_rows = array();

        if ($user_log_id_row)
        {
            $data_rows = $this->getUser($user_log_id_row);
        }

        $data = array();
        $data['page'] = $page;
        $data['total'] = ceil_r($total / $rows);  //total page
        $data['totalsize'] = $data['total'];
        $data['records'] = count($data_rows);
        $data['items'] = array_values($data_rows);

        return $data;
    }

    public function getSender()
    {
        $sql = 'SELECT msg_sender FROM user_msg GROUP BY msg_sender';
        $data = $this->sql->getAll($sql);
        return $data;
    }
}
?>