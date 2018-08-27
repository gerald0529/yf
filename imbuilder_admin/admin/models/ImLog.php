<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

class ImLog
{

    /**
     * ImLog::list([
     *
     *    'name'=>'',
     *    'is_read'=>1,
     *    'group'=>1, //分组显示同一个发送者信息只有一行。
     *
     * ]);
     * @param   array $arr
     * @return  [type]
     * @weichat sunkangchina
     * @date    2018-01-16
     */
    public static function getList($arr = [])
    {
        $is_bind = $arr['is_bind'];

        $select = "*";
        $sql = "SELECT " . $select . " FROM " . TABEL_PREFIX . 'user_msg WHERE  ';

        if ($is_bind) {
            $sql .= " AND is_read=:is_read";
            $bind_value[':is_read'] = $is_bind;
        }

        $sql .= "  ORDER BY date_created DESC ";
        echo $sql.'\n';
        print_r($bind_value);
        $db = new YFSQL();
        return $db->find($sql, $bind_value);

    }

}

 