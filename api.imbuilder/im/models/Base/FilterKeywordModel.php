<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_FilterKeywordModel extends Base_FilterKeyword
{
	public $htmlKey = array(
		'keyword_find',
		'keyword_replace'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $keyword_find 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFilterKeywordList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	public function getFilterRule()
	{
		//初始化
		$filter_rows = $this->getFilterKeywordList(array(), array(), 1, 100000);

		$filter_rule_rows                      = array();
		$filter_rule_rows['filter']['find']    = array();
		$filter_rule_rows['filter']['replace'] = array();
		$filter_rule_rows['banned']            = array();

		foreach ($filter_rows['items'] as $key => $filter_row)
		{
			if ('' !== $filter_row['keyword_find'])
			{
				if ('' !== $filter_row['keyword_replace'])
				{
					$filter_rule_rows['filter']['find'][]    = sprintf('/%s/i', addslashes($filter_row['keyword_find']));
					$filter_rule_rows['filter']['replace'][] = addslashes($filter_row['keyword_replace']);
				}
				else
				{
					$filter_rule_rows['banned'][] = addslashes($filter_row['keyword_find']);
				}
			}
		}

		$filter_rule_rows['banned'] = sprintf('/(%s)/i', $filter_rule_rows['banned'] ? implode('|', $filter_rule_rows['banned']) : '阿扁推翻');


		//init file
		$file = INI_PATH . '/filter.ini.php';

		if (!Yf_Utils_File::generatePhpFile($file, array('_CACHE["word_filter"]' => $filter_rule_rows)))
		{
		}

		return $filter_rule_rows;
	}

}

?>