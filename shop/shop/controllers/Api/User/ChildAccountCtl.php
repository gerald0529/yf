<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让App等调用
 */
class Api_User_ChildAccountCtl extends Api_Controller
{
    public $childAccountModel;

    function __construct($ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->childAccountModel = new User_SubUserModel;
    }

    /**
     * @throws Exception
     * 判断当前用户是否为子账号
     */
    public function isChildAccount()
    {
        $user_id = request_int('user_id');

        $child_account_rows = $this->childAccountModel->getByWHere([
            'sub_user_id'=> $user_id,
            'sub_user_active'=> User_SubUserModel::IS_ACTIVE
        ]);

        $is_child_account = $child_account_rows
            ? true
            : false;
        
        $this->data->addBody(140, ['is_child_account'=> $is_child_account], 'success', 200);
    }
}