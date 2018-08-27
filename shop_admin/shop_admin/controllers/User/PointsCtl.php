<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class User_PointsCtl extends AdminController
{
	public $webconfigModel = null;

	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}


}

?>