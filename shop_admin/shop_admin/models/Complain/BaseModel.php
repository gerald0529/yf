<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Complain_BaseModel
{
	public static $stateMap = array(
		'1' => 'new',
		'2' => 'appeal',
		//投诉通过转给被投诉人
		'3' => 'talk',
		//被投诉人已申诉
		'4' => 'handle',
		//提交仲裁
		'0' => 'finish',
	);


}

?>