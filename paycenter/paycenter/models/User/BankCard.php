<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class User_BankCard extends Yf_Model
{
    public $_cacheKeyPrefix = 'c|user_bank_card|';
    public $_tableName = 'user_bank_card';
    public $_tablePrimaryKey = 'card_id';

    /**
     * @param string $user User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id = 'paycenter', &$user = null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        $this->_cacheFlag = CHE;
        parent::__construct($db_id, $user);
    }

    public function addUserBankCard($field_row, $return_insert_id = false)
    {
        return $this->add($field_row, $return_insert_id);
    }

    public function removeUserBankCard($key)
    {
        return $this->remove($key);
    }
}