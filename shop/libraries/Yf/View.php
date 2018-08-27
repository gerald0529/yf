<?php
/**
 * 视图类
 * 
 * 用来管理html模板
 * 
 * @category   Framework
 * @package    View
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_View
{
    public $tpl;
    public $tplPath;

    public $stc;
    public $img;
    public $css;
    public $js ;

    public $ctl ;
    public $met ;

    public function __construct(&$ctl, &$met)
    {
		$this->ctl = $ctl;

		$this->setMet($met);
    }


	public function setMet($met=null, $dir='')
	{
            if ($met)
            {
		$this->met = $met;
            }
            else 
            {
                $met = $this->met;
            }
            
            if ($dir)
            {
                   $met = $dir . '/' . $met;
            }

		$this->tpl = TPL_PATH . '/' . implode('/', explode('_', $this->ctl)) . '/' . $met . '.php';

		if (!is_file($this->tpl))
		{
			$this->tpl = TPL_DEFAULT_PATH . '/' . implode('/', explode('_', $this->ctl)) . '/' . $met . '.php';

			$this->tplPath = TPL_DEFAULT_PATH;
		}
		else
		{
			$this->tplPath = TPL_PATH;
		}

		$this->msgTplPath = TPL_PATH . '/msg.php';
	}

    public function getDir()
    {
        return $this->tpl;
    }


    public function getView()
    {
        return $this->tpl;
    }

	public function getTplPath()
	{
		return $this->tplPath;
	}


	public function getMsgPath()
	{
		return $this->msgTplPath;
	}
}
?>