<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     zcg <xinze@live.cn>
 */
class Chain_GoodsCtl extends Chain_Controller
{
    public $chainBaseModel = null;

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        //include $this->view->getView();
        $this->Chain_BaseModel = new Chain_BaseModel();
        $this->Goods_CommonModel = new Goods_CommonModel();
        $this->Goods_BaseModel = new Goods_BaseModel();
        $this->Chain_GoodsModel = new Chain_GoodsModel();
    }

    /**
     * 商品库存列表
     *
     * @access public
     */
    public function goods()
    {
        if (Perm::checkUserPerm())
        {
            $chain_id = Perm::$chainId;
            $user_id = Perm::$userId;
            if($chain_id){
                if (1 == request_string('search_type'))
                {
                    $cond_row['common_name:LIKE'] = '%'.request_string('keyword'). '%';
                }
                else if(2 == request_string('search_type'))
                {
                    $cond_row['common_platform_code:LIKE'] = '%'.request_string('keyword'). '%';
                }
                else if(3 == request_string('search_type'))
                {
                    $cond_row['common_id:LIKE'] = '%'.request_string('keyword'). '%';
                }
                $Yf_Page           = new Yf_Page();
                $Yf_Page->listRows = 10;
                $rows              = $Yf_Page->listRows;
                $offset            = request_int('firstRow', 0);
                $page              = ceil_r($offset / $rows);

                $Chain_UserModel  = new Chain_UserModel();
                $chain_rows      = current($Chain_UserModel->getByWhere(array('user_id' => $user_id)));
                $shop_id=$chain_rows['shop_id'];

                $cond_row['shop_id']=$shop_id;
                $cond_row['common_is_virtual'] = 0;
                $goods_common=$this->Goods_CommonModel->getCommonList($cond_row,array(), $page, $rows);

                $this->Goods_BaseModel->sql->setLimit(0,999999999);
                $goods_base=$this->Goods_BaseModel->getByWhere(array('shop_id'=>$shop_id));

                $this->Chain_GoodsModel->sql->setLimit(0,999999999);
                $chain_goods=array_column($this->Chain_GoodsModel->getByWhere(array('chain_id'=>$chain_id)),'goods_stock','goods_id');

                $i=0;
                foreach($goods_common['items'] as $value){
                    foreach($goods_base as $v){
                        if($value['common_id']==$v['common_id']){
                            $goods[$i]['goods_image']=$value['common_image'];
                            $goods[$i]['goods_name']=$value['common_name'];
                            $goods[$i]['SPU']=$value['common_id'];
                            $goods[$i]['goods_id']=$v['goods_id'];
                            $goods[$i]['goods_code']=$value['common_platform_code'];
                            $goods[$i]['goods_price']=$value['common_price'];
                            $goods[$i]['goods_code']=$v['goods_code'];
                            @$goods[$i]['goods_stock']+=$chain_goods[$v['goods_id']];

                            //判断商品是否有效 有效状态：正常并通过审核common_state=1, common_verify=1
                            $goods[$i]['isValid'] = ( $value['common_state'] == Goods_CommonModel::GOODS_STATE_NORMAL &&
                                                        $value['common_verify'] == Goods_CommonModel::GOODS_VERIFY_ALLOW )
                                                    ? 1 
                                                    : 0;
                        }
                    }
                    $i++;
                }

                $Yf_Page->totalRows = $goods_common['totalsize'];
                $page_nav           = $Yf_Page->prompt();
                include $this->view->getView();
            }else{
                header("Location:" . Yf_Registry::get('base_url') . "/error.php?msg=您的帐号不是门店帐号");
            }
        }
        else
        {
            header("Location:" . Yf_Registry::get('url'), "请先登录！");
        }
    }

    /**
     * 商品库存
     *
     * @access public
     */
    public function goodsStock()
    {
        $chain_id = Perm::$chainId;
        $common_id=request_int('common_id');

        $cond_row['chain_id:='] = $chain_id;
        $cond_row['common_id:='] = $common_id;
        $chain_goods=$this->Chain_GoodsModel->getByWhere($cond_row);

        $goods_base=$this->Goods_BaseModel->getByWhere(array('common_id' => $common_id));
        $common_base=current($this->Goods_CommonModel->getByWhere(array('common_id' => $common_id)));
        foreach($goods_base as $k=>$v){
            if($v['goods_spec']){
                $goods_base[$k]['goods_spec']=current($v['goods_spec']);
            }
            if($chain_goods){
                foreach($chain_goods as $key=>$value){
                    if($value['goods_id']==$v['goods_id']){
                        $goods_base[$k]['goods_stock']=$value['goods_stock'];
                    }
                }
            }else{
                $goods_base[$k]['goods_stock']=0;
            }
        }
        include $this->view->getView();
    }

    /**
     * 设置库存
     *
     * @access public
     */
    public function setStock()
    {
        $stock=request_row('stock');
        $common_id=request_int('common_id');
        $shop_id=request_int('shop_id');
        $goods_id=request_row('goods_id');
        $chain_id=Perm::$chainId;

        $cond_row['chain_id'] = $chain_id;
        $cond_row['common_id'] = $common_id;
        $cond_row['goods_id:IN'] = $goods_id;
        $cond_row['shop_id'] = $shop_id;
        $chain_goods=$this->Chain_GoodsModel->getByWhere($cond_row);
        $c_goods_id = array_column($chain_goods,'goods_id');
        foreach($goods_id as $k=>$v)
        {
            if(in_array($v,$c_goods_id))
            {
                unset($goods_id[$k]);
            }
        }
        foreach($chain_goods as $key=>$value){
            $chain_goods_id=$value['chain_goods_id'];
            $goods_stock['goods_stock']=$stock[$value['goods_id']];
//            var_dump($chain_goods_id);
//            var_dump($goods_stock);
            $flag=$this->Chain_GoodsModel->editGoods($chain_goods_id,$goods_stock);
            unset($chain_goods_id);
        }
        if($goods_id){
            foreach($goods_id as $key=>$value){
                $chain_goods_info['chain_id']=$chain_id;
                $chain_goods_info['shop_id']=$shop_id;
                $chain_goods_info['goods_id']=$value;
                $chain_goods_info['common_id']=$common_id;
                $chain_goods_info['goods_stock']=$stock[$value];
                $chain_goods_id=$this->Chain_GoodsModel->addGoods($chain_goods_info,true);
            }
        }
        if ($flag === false)
        {
            $msg = __('failure');
            $status = 250;
        }
        else if($chain_goods_id){
            $msg = __('success');
            $status = 200;
        }
        else
        {
            $msg = __('success');
            $status = 200;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    /**
     * 判断当前购买账号
     */

    public function isValidUser()
    {
        $currentUserId = Perm::$userId;
        $goodsId = request_int('goods_id');

        $goodsModel = new Goods_BaseModel;
        $goods = $goodsModel->getOne($goodsId);

        if (!$goods) {
            throw new Exception('获取商品失败');
        }

        $shopId = $goods['shop_id'];
        $chainUserModel = new Chain_UserModel;
        $chainUser = $chainUserModel->getOneByWhere([
            'shop_id'=> $shopId,
            'user_id'=> $currentUserId
        ]);

        if ($chainUser) {
            $status = 250;
            $msg = '不能使用店铺或门店账号购买自己店铺商品';
        } else {
            $status = 200;
            $msg = 'success';
        }

        $this->data->addBody(-140, [], $msg, $status);
    }
}
?>
