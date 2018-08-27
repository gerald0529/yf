<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Promotion_ActIncreaseCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}
}

?>