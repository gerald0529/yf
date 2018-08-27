<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_RnameModel extends User_Rname
{
	/**
    *根据用户id和好友id获得主键
    *@param $id 用户id
    *@param $fid 好友id
    */
    public function getRnameId($id=null,$fid=null)
    {
        $this->sql->setWhere('userid',$id);
        $this->sql->setWhere('friendid',$fid);
        $id_row=$this->selectKeyLimit();
        if($id_row){
            //update
            return $id_row;
        }else{
            //insert
            return 0;
        }
    }

    public function getUserRname($id=null,$fid=null)
    {
        $this->sql->setWhere('userid',$id);
        $this->sql->setWhere('friendid',$fid);
        $id_row=$this->selectKeyLimit();

        $data_rows = array();

        if ($id_row)
        {
            $data_rows = $this->getRname($id_row);
        }

        return $data_rows;
    }
}