<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/skin_0.css" rel="stylesheet" type="text/css">
<script src="<?=$this->view->js_com?>/template.js"></script>
</head>
<body>
	<div class="bbccontent">
        <div class="wrapper page">
           
            <div class="info-panel mainIndex_url clearfix">
                <dl class="member">
                    <dt>
						<div class="ico"><i></i><sub title="会员总数"><span><em id="statistics_member">0</em></span></sub></div>
						<h3>会员</h3>
						<h5>新增会员</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w50pre normal"><a href="index.php?ctl=User_Info&met=info"><span>本周新增</span><sub><em id="statistics_week_add_member">0</em></sub></a></li>
                             <li class="w50pre normal"><a href="index.php?ctl=User_Info&met=info"><span>本月新增</span><sub><em id="statistics_month_add_member">0</em></sub></a></li>
                            <!--<li class="w50pre normal"><a href="">预存款提现<sub><em id="statistics_cashlist">0</em></sub></a></li>-->
                        </ul>
                    </dd>
                </dl>
                
				<dl class="shop">
                    <dt>
						<div class="ico"><i></i><sub title="新增店铺数"><span><em id="statistics_store">0</em></span></sub></div>
						<h3>店铺</h3>
						<h5>新开店铺审核</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w20pre normal"><a href="index.php?ctl=Shop_Manage&met=join"><span>开店审核</span><sub><em id="statistics_store_joinin">0</em></sub></a></li>
                            <li class="w20pre normal"><a href="index.php?ctl=Shop_Manage&met=category"><span>类目申请</span><sub><em id="statistics_store_bind_class_applay">0</em></sub></a></li>
                            <li class="w20pre normal"><a href="index.php?ctl=Shop_Manage&met=reopen"><span>续签申请</span><sub><em id="statistics_store_reopen_applay">0</em></sub></a></li>
                            <li class="w20pre normal"><a href="index.php?ctl=Shop_Manage&met=indexs"><span>已到期</span><sub><em id="statistics_store_expired">0</em></sub></a></li>
                            <li class="w20pre normal"><a href="index.php?ctl=Shop_Manage&met=indexs"><span>即将到期</span><sub><em id="statistics_store_expire">0</em></sub></a></li>
                        </ul>
                    </dd>
                </dl>
                
				<dl class="goods">
                    <dt>
						<div class="ico"><i></i><sub title="商品总数"><span><em id="statistics_goods">0</em></span></sub></div>
						<h3>商品</h3>
						<h5>新增商品/品牌申请审核</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w25pre normal"><a href="index.php?ctl=Goods_Goods&met=common"><span>本周新增</span><sub title=""><em id="statistics_week_add_product">0</em></sub></a></li>
                            <li class="w25pre normal"><a href="index.php?ctl=Goods_Goods&met=common&common_verify=10"><span>商品审核</span><sub><em id="statistics_product_verify">0</em></sub></a></li>
                            <li class="w25pre normal"><a href="index.php?ctl=Trade_Report&met=baseDo"><span>举报</span><sub><em id="statistics_inform_list">0</em></sub></a></li>
                            <li class="w25pre normal"><a href="index.php?ctl=Goods_Brand&met=brand"><span>品牌管理</span><sub><em id="statistics_brand_apply">0</em></sub></a></li>
                        </ul>
                    </dd>
                </dl>
                <dl class="trade">
                    <dt>
						<div class="ico"><i></i><sub title="订单总数"><span><em id="statistics_order">0</em></span></sub></div>
						<h3>交易</h3>
						<h5>交易订单及投诉/举报</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w18pre normal"><a href="index.php?ctl=Trade_Return&met=refundWait&otyp=1"><span>退款</span><sub><em id="statistics_refund">0</em></sub></a></li>
                            <li class="w18pre normal"><a href="index.php?ctl=Trade_Return&met=refundWait&otyp=2"><span>退货</span><sub><em id="statistics_return">0</em></sub></a></li>
                            <li class="w25pre normal"><a href="index.php?ctl=Trade_Return&met=refundWait&otyp=3"><span>虚拟订单退款</span><sub><em id="statistics_vr_refund">0</em></sub></a></li>
                            <li class="w18pre normal"><a href="index.php?ctl=Trade_Complain&met=complain&state=1"><span>投诉</span><sub><em id="statistics_complain_new_list">0</em></sub></a></li>
							 <li class="w20pre normal"><a href="index.php?ctl=Trade_Complain&met=complain&state=4"><span>待仲裁</span><sub><em id="statistics_complain_handle_list">0</em></sub></a></li>
                        </ul>
                    </dd>
                </dl>
                <dl class="operation">
                    <dt>
						<div class="ico"><i></i></div>
						<h3>运营</h3>
						<h5>系统运营类设置及审核</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w15pre none"><a href="?ctl=Config&met=operation&config_type%5B%5D=operation"><span>设置</span><sub><em id="statistics_groupbuy_verify_list">0</em></sub></a></li>
                            <li class="w17pre none"><a href="?ctl=Operation_Settlement&met=settlement"><span>结算管理</span><sub><em id="statistics_points_order">0</em></sub></a></li>
                            <li class="w17pre none"><a href="?ctl=Operation_Settlement&met=settlement&otyp=1"><span>虚拟订单</span><sub><em id="statistics_check_billno">0</em></sub></a></li>
                            <li class="w17pre none"><a href="?ctl=Operation_Custom&met=custom"><span>平台客服</span><sub><em id="statistics_pay_billno">0</em></sub></a></li>
                            <!--<li class="w17pre none"><a href="?ctl=Operation_Delivery&met=delivery"><span>物流自提</span><sub><em id="statistics_mall_consult">0</em></sub></a></li>-->
                            <li class="w34pre none"><a href="?ctl=Operation_Contract&met=log"><span>消费者保障服务</span><sub><em id="statistics_delivery_point">0</em></sub></a></li>
                        </ul>
                    </dd>
                </dl>
                <!--
                <dl class="system">
                    <dt>
						<div class="ico"><i></i></div>
						<h3>BBCBuilder</h3>
						<h5>远丰商城系统</h5>
					</dt>
                    <dd>
                        <ul>
                            <li class="w50pre none"><a href="https://www.yuanfeng.cn/" target="_blank">官方网站<sub></sub></a></li>
                            <li class="w50pre none"><a href="https://www.yuanfengtest.com/" target="_blank">官方演示站<sub></sub></a></li>
                        </ul>
                    </dd>
                </dl>
                -->
                <div class="clear"></div>
                <div class="system-info"></div>
            </div>
        </div>
    </div>
<script>
	$('.mainIndex_url a').click(function(){
		var aurl = $(this).attr('href');
		var text = $(this).find('span').html();


        var target = $(this).attr('target');
        
        if (target == '_blank')
        {
            return true;
        }
        else
        {
            parent.tab.addTabItem({
                text:text,
                url: SITE_URL + '/' +aurl
            });
        }
        
		return false;
	});
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/mainIndex.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>