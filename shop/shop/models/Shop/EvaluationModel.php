<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_EvaluationModel extends Shop_Evaluation
{
    /**
     * 删除操作
     * @param int $goods_id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeEvalution($evaluation_goods_id)
    {
        $del_flag = $this->remove($evaluation_goods_id);

        //$this->removeKey($goods_id);
        return $del_flag;
    }
}

?>