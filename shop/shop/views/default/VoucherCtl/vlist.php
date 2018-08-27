<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<link href="<?=$this->view->css?>/tips.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/home.js"></script>
<!-- 内容 -->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="bbc-breadcrumb-layout">
   <div class="bbc-breadcrumb wrapper"><i class="icon-home"></i> <span><a href="<?=Yf_Registry::get('url')?>"><?=__('首页')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span> <span><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=index"><?=__('积分中心')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span> <span><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=index"><?=__('兑换礼品列表')?></a></span> </div>
</div>

<div class="bbc-container">
    <?php if(Perm::checkUserPerm()){ ?>
	<div class="bbc-member-top">
		<div class="bbc-member-info">
			<div class="avatar"><img style="width:80px;height:80px;" src="<?=image_thumb($data['user_info']['user_logo'],80,80)?>">
				<div class="frame"></div>
			</div>
			<dl>
				<dt>Hi, <?=$data['user_info']['user_name']?></dt>
				<dd><?=__('当前等级')?>：<strong class="common-color">V<?=$data['user_info']['user_grade']?></strong></dd>
				<dd><?=__('当前经验值')?>：<strong class="common-color"><?=$data['user_resource']['user_growth']?></strong></dd>
			</dl>
		</div>
		<div class="bbc-member-grade" style="padding:32px 18px;">
			<div class="progress-bar"><em title="V<?=$data['user_info']['user_grade']?><?=__('需经验值')?><?=$data['growth']['grade_growth_start']?>">V<?=$data['user_info']['user_grade']?></em><span title="<?=$data['growth']['grade_growth_per']?>%"><i class="bbc_bg" style="width:<?=$data['growth']['grade_growth_per']?>%;"></i></span><em title="V<?=$data['user_info']['user_grade']+1?><?=__('需经验值')?><?=$data['growth']['grade_growth_end']?>">V<?=$data['user_info']['user_grade']+1?></em></div>
			<div class="progress"><?=__('还差')?><em class="bbc_color"><?=$data['growth']['next_grade_growth']?></em><?=__('经验值即可升级成为V')?><?=$data['user_info']['user_grade']+1?><?=__('等级会员')?></div>
		</div>
		<div class="bbc-member-point">
			<dl style="border-left: none 0;">
				<a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Points&met=points" target="_blank">
					<dt><strong class="bbc_color"><?=$data['user_resource']['user_points']?></strong><?=__('分')?></dt>
					<dd><?=__('我的积分')?></dd>
				</a>
			</dl>
			<dl>
				<a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Voucher&met=voucher" target="_blank">
					<dt><strong class="bbc_color"><?=$data['ava_voucher_num']?></strong><?=__('张')?></dt>
					<dd><?=__('可用代金券')?></dd>
				</a>
			</dl>
			<dl>
				<a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Points&met=points&op=getPointsOrder" target="_blank">
					<dt><strong class="bbc_color"><?=$data['points_order_num']?></strong><?=__('个')?></dt>
					<dd><?=__('已兑换礼品')?></dd>
				</a>
			</dl>
		</div>
		<div class="bbc-memeber-pointcart"> <a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=pointsCart" class="btn bbc_bg_col"><?=__('礼品兑换购物车')?><em><?=$data['points_cart_num']?></em></a></div>
	</div>
    <?php } ?>
	<div class="bbc-main-layout">
        <div class="bbc-category">
            <dl>
                <dt><?=__('选择分类')?>：</dt>
                <dd>
                <ul class="daijing_list">
                    <input type="hidden" id="vc_id" value="">
                    <li class="<?=!(request_int('vc_id'))?'hova':''?>"><a href="javascript:void(0);" onclick="cat(0)" op_type="search_cate"><?=__('所有分类')?></a></li>
                    <?php if($data['shop_cat']){
                        foreach($data['shop_cat'] as $key=>$cat){
                            ?>
                            <li class="<?=request_int('vc_id') == $cat['shop_class_id']?'hova':''?>"><a href="javascript:void(0);" onclick="cat(<?=$cat['shop_class_id']?>)" op_type="search_cate"><?=$cat['shop_class_name']?></a>
                            </li>
                    <?php
                        }
                        }
                    ?>
                </ul>
                </dd>
            </dl>

            <dl class="searchbox">
                <dt><?=__('排序方式')?>：</dt>
                <dd>
                <ul>
                    <input type="hidden" id="orderby" name="orderby" value="default">
                    <li op_type="search_orderby" onclick='list("default")' class="<?=request_string('orderby')=='default' || !request_string('orderby')?'hova':''?>"><?=__('默认排序')?></li>
                    <li class="<?=(request_string('orderby')=='exchangenumdesc' || request_string('orderby')=='exchangenumasc')?'hova':''?>" onclick='list(<?=request_string('orderby')=='exchangenumdesc'?'"exchangenumasc"':'"exchangenumdesc"'?>)' op_type="search_orderby" ><?=__('兑换量')?>
                        <em class="display_arrow">
                            <?php if(request_string('orderby')=='exchangenumdesc' || request_string('orderby')=='exchangenumasc'){ ?>
                                <i class="iconfont <?=(request_string('orderby')=='exchangenumdesc')?'icon-jiantouxiangxia':'icon-jiantouxiangshang'?>"></i>
                            <?php } ?>
                        </em>
                    </li>
                    <li class="<?=(request_string('orderby')=='pointsdesc' || request_string('orderby')=='pointsasc')?'hova':''?>" onclick='list(<?=request_string('orderby')=='pointsdesc'?'"pointsasc"':'"pointsdesc"'?>)' op_type="search_orderby"><?=__('积分值')?>
                        <em class="display_arrow">
                            <?php if(request_string('orderby')=='pointsdesc' || request_string('orderby')=='pointsasc'){ ?>
                                <i class="iconfont <?=(request_string('orderby')=='pointsdesc')?'icon-jiantouxiangxia':'icon-jiantouxiangshang'?>"></i>
                            <?php } ?>
                        </em>
                    </li>


                    <li>&nbsp;</li>
                    <li><?=__('优惠券面额')?>：
                    <select id="price" onchange="price(this);">
                        <option value="">-<?=__('请选择')?>-</option>
                        <?php if($data['price_range'])
                        {
                        foreach($data['price_range'] as $key=>$price)
                        { ?>
                            <option value="<?=$price['voucher_price']?>" <?=request_int('price') == $price['voucher_price']?"selected":""?> ><?=$price['voucher_price']?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>
                    </li>
                    <li>&nbsp;</li>
                    <li><?=__('所需积分')?>：
                    <input type="text" id="points_min" class="text w50" value="<?=request_string('points_min')?>">
                    ~
                    <input type="text" id="points_max" class="text w50" value="<?=request_string('points_max')?>">
                    <a href="javascript:searchvoucher();" class="bbcbtn bbc_btns"><?=__('搜索')?></a> </li>
                </ul>
                </dd>
            </dl>
         </div>
        <?php if($data['voucher']['items'])
            {
        ?>
        <ul class="ncp-voucher-list">
            <?php
                foreach($data['voucher']['items'] as $key=>$value)
                {
            ?>
            <li>
                <div class="ncp-voucher" data-id="<?=$value['voucher_t_id']?>">
                    <div class="cut"></div>
                    <div class="info">
                        <a href="<?=Yf_Registry::get('url')?>?ctl=Shop&met=index&typ=e&id=<?=$value['shop_id']?>" class="store"><?=$value['shop_name']?></a>
                        <p class="store-classify"><?=$value['voucher_t_cat_name']?></p>
                        <div class="pic"><img src="<?=image_thumb($value['voucher_t_customimg'],120,120)?>"></div>
                    </div>
                    <dl class="value">
                        <dt><em class="bbc_color"><?=format_money($value['voucher_t_price'])?></em></dt>
                        <dd><?=__('购物满')?><?=format_money($value['voucher_t_limit'])?><?=__('可用')?></dd>
                        <dd class="time"><?=__('有效期至')?><?=$value['voucher_t_end_date_day']?></dd>
                    </dl>
                    <div class="point">
                        <p class="required"><?=__('需')?><em class="margin2"><?=$value['voucher_t_points']?></em><?=__('积分')?></p>
                        <p><em class="giveout"><?=$value['voucher_t_giveout']?></em><?=__('张已被领取')?></p>
                    </div>
                    <div class="button">

                        <a op_type="exchangebtn" data-param='{"vid":"<?=$value['voucher_t_id']?>"}' class="ncbtn ncbtn-grapefruit bbc_btns"><?=__('立即兑换')?></a>

                    </div>
                </div>
            </li>
        <?php
            }
        ?>
        </ul>
        <?php }else{ ?>
        <div class="no_account">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <p><?= __('暂无符合条件的数据记录') ?></p>
        </div>
        <?php } ?>
        <div class="flip clearfix">
            <?php if($page_nav){ ?>
                <div class="page"><?=$page_nav?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script>

    //综合排序，销量，价格，新品
    function list(e)
    {
        //地址中的参数
        var params= window.location.search;

        params = changeURLPar(params,'orderby',e);

        window.location.href = SITE_URL + params;

    }

    //分类
    function cat(e)
    {
        //地址中的参数
        var params= window.location.search;

        params = changeURLPar(params,'vc_id',e);

        window.location.href = SITE_URL + params;
    }

    function price(e)
    {
        //地址中的参数
        var params= window.location.search;
        e = $(e).val();
        params = changeURLPar(params,'price',e);

        window.location.href = SITE_URL + params;
    }

    function changeURLPar(destiny, par, par_value)
    {
        var pattern = par+'=([^&]*)';
        var replaceText = par+'='+par_value;
        if (destiny.match(pattern))
        {
            var tmp = new RegExp(pattern);
            tmp = destiny.replace(tmp, replaceText);
            return (tmp);
        }
        else
        {
            if (destiny.match('[\?]'))
            {
                return destiny+'&'+ replaceText;
            }
            else
            {
                return destiny+'?'+replaceText;
            }


        }
        return destiny+'\n'+par+'\n'+par_value;
    }

    function searchvoucher(){
        var params= window.location.search;

        var points_min = $("#points_min").val();
        if(points_min){
            params= changeURLPar(params,'points_min',points_min);
        }
        var points_max = $("#points_max").val();
        if(points_max){
            params = changeURLPar(params,'points_max',points_max);
        }
        window.location.href = SITE_URL + params;
    }

</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>