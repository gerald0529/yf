<?php if (!defined('ROOT_PATH')) exit('No Permission');
/*
聊天记录的，写入，读取。

业务逻辑

weichat: sunkangchina

*/
class Chatlog extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|chatlog|';
    public $_cacheName       = 'chatlog';
    public $_tableName       = 'chatlog';
    public $_tablePrimaryKey = 'id';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='im-builder', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
        parent::__construct($db_id, $user);
    }

    /**
     * 取得聊天记录
     *
     * @param  int   $app_id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getChatlog($condition=null, $sort_key_row=null)
    {
        //没有传聊天，发送者 或 接收者，直接跳出
        if(!$_GET['u'] || !$_GET['to']){
            return;
         }
         //先取得用户信息

         $in = new User_Info;

         $info1 = $in->getInfo($_GET['u']);
         $info2 = $in->getInfo($_GET['to']);
        

         //显示几天内的聊天记录
         $day = $_GET['day']?:30;

         $start = time()-$day*86400;
         
 
        $rows = array();
        //listByWhere($cond_row, $order_row = array(), $page=1, $rows=100)
        $rows = $this->listByWhere($condition, array('id'=>'asc'),(int)$_GET['page'],999999999);

        $sql = "
            SELECT * FROM `".$this->_tableName."`
            WHERE 

            (`sender`='".$_GET['u']."'  AND `receiver`='".$_GET['to']."' AND created >= ".$start.") 
            OR (`sender`='".$_GET['to']."' AND `receiver`='".$_GET['u']."' AND created >= ".$start." ) 
                GROUP BY id
            


        ";
 
        $rows['items'] = $this->sql->getAll($sql);
        
        $rows['user'][$_GET['u']] = $info1[$_GET['u']];
        $rows['user'][$_GET['to']] = $info2[$_GET['to']];
        return $rows;
    }


    /**
     * 取得聊天记录
     *
     * @param  int   $app_id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getMessagelog($condition=null, $sort_key_row=null)
    {
        //没有传聊天发送者，直接跳出
        $u = addslashes($_REQUEST['u']);
        if(!$u){
            return;
        }
        //先取得用户信息

        $in = new User_Info;

        $info = $in->getInfo($u);
        if(!isset($info[$u]) || !$info[$u]){
            return false;
        }
        //显示几天内的聊天记录
        $day = $_GET['day']?:30;

        $start = time()-$day*86400;


        $rows = array();
        //listByWhere($cond_row, $order_row = array(), $page=1, $rows=100)
        $rows = $this->listByWhere($condition, array('id'=>'asc'),(int)$_REQUEST['page'],999999999);

        $sql = "
            SELECT * FROM `".$this->_tableName."`
            WHERE 

           `receiver`='".$u."' AND created >= ".$start." 

           ORDER BY id desc
          
        ";

      
        $rows['items'] = $this->sql->getAll($sql);

        foreach ($rows['items'] as $k=>$v)
        {
            $sender_info = current($in->getInfo($v['sender']));
            $rows['items'][$k]['sender_name'] = $sender_info['user_name'];
            $rows['items'][$k]['sender_logo'] = $sender_info['user_avatar'];
            $rows['items'][$k]['created'] = date('Y-m-d H:i:s', $v['created']);
            unset($sender_info);
        }
        return $rows;
    }

    public function getChatlogHtml($arr){
            $rows = $arr['items'];
            $user = $arr['user'];
            $today = date('Ymd');
            foreach($rows as $v){
                $time = "今天 10:47:51";
                $ti = $v['created'];
                if($today == date('Ymd',$ti)){
                    $time = "今天 ".date('H:i:s',$ti);
                }else{

                    $time = date('Y-m-d H:i:s',$ti);
                }

                if($_GET['u'] == $v['sender']){
                    $class = 'to_msg';
                    $class_time = 'to-msg-time';
                    $icon = "chat_header_icon_sender";
                    $wap_class = 'chatinterfacelist-right';

                }else{
                    $class = 'from_msg';
                    $class_time = 'from-msg-time';
                    $icon = "chat_header_icon_from"; 
                    $wap_class = 'chatinterfacelist-left';
                }

                $v['content'] = html_entity_decode($v['content']);
                if($_GET['is_wap']){
                    $str .= '
                        <div contactor="sender" im_carousel="" msg="msg" 
                    im_msgtype="1" id="'.$v['sender'].'_'.$v['msgid'].'"
                    content_type="'.$v['type'].'"  content_you="'.$v['receiver'].'" 
                    class="'.$wap_class.' clearfix alert-right" style="display:block">
                    <div class="hd-portrait">
                     <img class="'.$icon.'" src="'.$user[$v['sender']]['user_avatar'].'">
                     </div>
                     <div class="chatcontent"><div class="triangle">
                     </div>
                    <p>'.$v['content'].'</p></div></div>
                    ';
                }else{
                    $str .= '<div class="'.$class.'" m_id="'.$v['msgid'].'" id="'.$v['sender'].'_'.$v['msgid'].'" content_type="'.$v['type'].'" content_you="'.$v['receiver'].'" style="display:block"><span class="user-avatar sss"><img src="'.$user[$v['sender']]['user_avatar'].'"></span><dl>
                            <dt class="'.$class_time.'">'.$time.'</dt><dd class="to-msg-text" style="margin-left: 0px;">  '.$v['content'].'</dd>
                            <dd class="arrow"></dd></dl></div>';
                }
                

                

                
            }
            return $str;
    }


     


    /**
     * 插入
     * @param array $field_row 插入数据信息
     * @param bool  $return_insert_id 是否返回inset id
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addChatlog($field_row, $return_insert_id=true)
    {
        
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($app_id);
        return $add_flag;
    }

    /**
     * 删除
     * @param int $t_id 主键id
     * @return bool  是否成功
     * @access public
     */
    public function delChatlog($t_id)
    {

        $del_flag = $this->remove($t_id);

        return $del_flag;
    }
    
    public function getLinkman($name){
//        $sql = 'SELECT * FROM '.$this->_tableName.' WHERE sender= "'.$name.'" GROUP BY receiver LIMIT 100
//            UNION 
//               (SELECT * FROM '.$this->_tableName.' WHERE receiver= "'.$name.'" GROUP BY sender LIMIT 100) ';
        
        $sql = "SELECT * FROM `".$this->_tableName."` WHERE `sender`='".$name."'OR `receiver`='".$name."' ORDER BY id DESC LIMIT 100";
        $result = $this->sql->getAll($sql);
        return $result;
    }
    
    /**
     * wap端消息列表
     * @return boolean
     */
    public function getMessageListlog(){
        //没有传聊天发送者，直接跳出
        $u = addslashes($_REQUEST['u']);
        if(!$u){
            return;
        }
        //先取得用户信息

        $in = new User_Info;

        $info = $in->getInfo($u);
        if(!isset($info[$u]) || !$info[$u]){
            return false;
        }
        //显示几天内的聊天记录
        $day = $_GET['day']?:30;
        $start = time()-$day*86400;
        $rows = array();
        $sql = "SELECT * FROM (SELECT * FROM `".$this->_tableName."` WHERE `receiver`='".$u."' AND created >= ".$start." AND STATUS = 0  ORDER BY id DESC) temp GROUP BY sender ORDER BY id DESC";
        $receiveList = $this->sql->getAll($sql);
        $sql2 = "SELECT * FROM (SELECT * FROM `".$this->_tableName."` WHERE `sender`='".$u."' AND created >= ".$start." AND STATUS = 0  ORDER BY id DESC) temp GROUP BY receiver ORDER BY id DESC";
        $sendList = $this->sql->getAll($sql2);
        $res = $this->merageUserMsg($receiveList,$sendList,$u);
        if(!$res || !is_array($res)){
            return false;
        }
        $msg_list = array();
        foreach ($res as &$v){
            $v['created'] = date('Y-m-d H:i:s', $v['created']);
            $senders[] = $v['sender'] == $u ? $v['receiver'] : $v['sender'];
            $msg_list[] = $v;
            
        }
        $sender_info = $in->getInfo($senders);
        
        //头像
        foreach ($msg_list as $key=>$val) {
            $msg_list[$key]['sender_logo'] = $sender_info[$val['sender']]['user_name'] == $val['sender'] ? $sender_info[$val['sender']]['user_avatar'] : $sender_info[$val['receiver']]['user_avatar'];
            
        }
        $rows['items'] = $msg_list;
        $rows['records'] = count($msg_list);
        return $rows;
        
    }
    
    /**
     * 按用户获取个人的聊天记录
     * @param type $receiveList
     * @param type $sendList
     * @param type $user_account
     * @return type
     */
    public function merageUserMsg($receiveList,$sendList,$user_account){
        if(!is_array($receiveList) || !is_array($sendList) || !$receiveList || !$user_account){
            return [];
        }
        if(!$sendList){
            return $receiveList;
        }
        
        $receiveList = $this->addMsgKey($receiveList, $user_account);
        $sendList = $this->addMsgKey($sendList, $user_account);
        foreach ($receiveList as $key => $value){
            if(!isset($sendList[$value['msg_key']])){
                continue;
            }
          
            if($value['created'] < $sendList[$value['msg_key']]['created']){
                $receiveList[$key] = $sendList[$value['msg_key']];
            }
            //$receiveList[$key]['sender_name'] = $user_account == $receiveList[$key]['sender'] ?  $receiveList[$key]['receiver'] : $receiveList[$key]['sender'];
        }
        return $receiveList;
    }
    
    /**
     * 给聊天记录添加一个用户标记，便于区分对话
     * @param type $list
     * @param type $user_account
     * @return type
     * msg_key 格式 {$sender}__key__{$receiver};
     */
    public function addMsgKey($list,$user_account){
        $result = array();
        foreach ($list as $key => $value){
            if($value['sender'] != $user_account){
                if($value['receiver'] != $user_account){
                    unset($list[$key]);
                    continue;
                }
            }
            $msg_key = $user_account == $value['sender'] ? $value['sender'].'__key__'.$value['receiver'] : $value['receiver'].'__key__'.$value['sender'];
            $result[$msg_key] = $value;
            $result[$msg_key]['msg_key'] = $msg_key;
        }
        return $result;
    }
    
    
    public function editInfo($table_primary_key_value, $field_row, $flag){
        $res = $this->edit($table_primary_key_value, $field_row, $flag);
        return $res;
    }

}