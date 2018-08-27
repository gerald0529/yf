<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/headfoot.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/index.css" />
<link rel="stylesheet"  type="text/css" href="<?=$this->view->css?>/iconfont/iconfont.css">
<script type="text/javascript" src="<?=$this->view->js?>/nav.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/base.js"></script>

<!-- 内容 -->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="bbc-breadcrumb-layout">
	<div class="bbc-breadcrumb wrapper"><i class="icon-home"></i> <span><a href="<?=Yf_Registry::get('url')?>"><?=__('首页')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span> <span><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=index"><?=__('积分中心')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span> <span><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=pList"><?=__('兑换礼品列表')?></a></span> </div>
</div>

<div class="bbc-container">
	<?php if(Perm::checkUserPerm()){ ?>
	<div class="bbc-member-top">
		<div class="bbc-member-info">
			<div class="avatar"><img src="<?=image_thumb($data['user_info']['user_logo'],80,80)?>">
				<div class="frame"></div>
			</div>
			<dl>
				<dt>Hi, <?=$data['user_info']['user_name']?></dt>
				<dd><?=__('当前等级')?>：<strong>V<?=$data['user_info']['user_grade']?></strong></dd>
				<dd><?=__('当前经验值')?>：<strong><?=$data['user_resource']['user_growth']?></strong></dd>
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
				<dt><strong  class="bbc_color"><?=$data['ava_voucher_num']?></strong><?=__('张')?></dt>
				<dd><?=__('可用代金券')?></dd>
				</a>
			</dl>
			<dl>
				<a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Points&met=points&op=getPointsOrder" target="_blank">
					<dt><strong  class="bbc_color"><?=$data['points_order_num']?></strong><?=__('个')?></dt>
					<dd><?=__('已兑换礼品')?></dd>
				</a>
			</dl>
		</div>
		<div class="bbc-memeber-pointcart"> <a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=pointsCart" class="btn bbc_bg_col"><?=__('礼品兑换购物车')?><em><?=$data['points_cart_num']?></em></a></div>
	</div>
	<?php } ?>
	<div class="bbc-main-layout">
		<div class="bbc-category">
			<dl class="searchbox">
				<dt><?=__('排序方式')?>：</dt>
				<dd>
				<ul>
                    <li onclick='list("default")' <?php if(!request_string('orderby') || request_string('orderby') =='default'){ ?>class="hova" <?php }else{ ?> op_type="search_orderby" data-param='{"orderval":"default"}' style="cursor: pointer;" <?php } ?>><?=__('默认排序')?></li>
                    <li op_type="search_orderby" class="<?=(request_string('orderby')=='pointsdesc' || request_string('orderby')=='pointsasc')?'hova':''?>" onclick='list(<?=request_string('orderby')=='pointsdesc'?'"pointsasc"':'"pointsdesc"'?>)'><?=__('积分值')?>
                        <em class="display_arrow">
                            <?php if(request_string('orderby')=='pointsdesc' || request_string('orderby')=='pointsasc'){ ?>
                                <i class="iconfont <?=(request_string('orderby')=='pointsdesc')?'icon-jiantouxiangxia':'icon-jiantouxiangshang'?>"></i>
                            <?php } ?>
                        </em>
                    </li>
                    <li op_type="search_orderby" class="<?=(request_string('orderby')=='stimedesc' || request_string('orderby')=='stimeasc')?'hova':''?>" onclick='list(<?=request_string('orderby')=='stimedesc'?'"stimeasc"':'"stimedesc"'?>)'><?=__('上架时间')?>
                        <em class="display_arrow">
                            <?php if(request_string('orderby')=='stimedesc' || request_string('orderby')=='stimeasc'){ ?>
                            <i class="iconfont <?=(request_string('orderby')=='stimedesc')?'icon-jiantouxiangxia':'icon-jiantouxiangshang'?>"></i>
                            <?php } ?>
                        </em>
                    </li>
					<li>&nbsp;</li>
					<li><?=__('会员等级')?>：
						<select id="level" onchange="level(this);">
							<option value="0" selected=""><?=__('-请选择-')?></option>
                            <?php if($data['user_grade'] ) {
                                foreach ($data['user_grade'] as $key => $grade) {
                                    ?>
                                    <option value="<?=$grade['user_grade_id']?>" <?=request_int('level')==$grade['user_grade_id']?'selected':''?> ><?=$grade['user_grade_name']?></option>
                                <?php
                                }
                             }
                            ?>
						</select>
					</li>
					<li>&nbsp;</li>
					<li><?=__('所需积分')?>：
						<input id="points_min" class="text w50" value="<?=request_string('points_min')?>" type="text">
						~
						<input id="points_max" class="text w50" value="<?=request_string('points_max')?>" type="text">
						<a href="javascript:searchPointsGoods();" class="bbcbtn bbc_btns"><?=__('搜索')?></a> </li>
					<li>&nbsp;</li>
					<li>
						<label for="isable"><input class="jifen_input" id="isable" <?php if(request_int('isable')==1){ ?> checked="checked" <?php } ?> onclick="available(this);" type="checkbox">
					  &nbsp;<?=__('只看我能兑换')?></label>
					</li>
                </ul>
				</dd>
			</dl>
		</div>

        <?php
            if($data['points_goods']['items'])
            {
        ?>
		<ul class="bbc-exchange-list">
            <?php
                foreach($data['points_goods']['items'] as $key=>$value)
                {
                    ?>
                    <li>
                        <div class="gift-pic">
                            <a target="_blank" href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>"><img src="<?=image_thumb($value['points_goods_image'],150,150)?>" alt="<?=$value['points_goods_name']?>"> </a>
                        </div>
                        <div class="gift-name">
                            <a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>" target="_blank" tile="<?=$value['points_goods_name']?>"><?=$value['points_goods_name']?></a>
                        </div>
                        <div class="exchange-rule">
                            <?php
                                if($value['points_goods_limitgrade']-1){
                            ?>
                            <span class="pgoods-grade"><img src="<?=$this->view->img?>/V<?=$value['points_goods_limitgrade']-1?>.png"></span>
                            <?php }?>
                            <span class="pgoods-price one-overflow wp100"><label><?=__('参考价格')?>：</label><em><?=format_money($value['points_goods_price'])?></em></span>
                            <span class="pgoods-points"><label><?=__('所需积分')?>：</label><strong class="bbc_color"><?=$value['points_goods_points']?></strong></span>
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
        //排序
        function list(e)
        {
            //地址中的参数
            var params= window.location.search;
            params = changeURLPar(params,'orderby',e);
            window.location.href = SITE_URL + params;
        }

        //level
        function level(e)
        {
            //地址中的参数
            var params= window.location.search;
            e = $(e).val();
            params = changeURLPar(params,'level',e);

            window.location.href = SITE_URL + params;
        }
        //可换购商品
        function available(e)
        {
            //地址中的参数

            if($("#isable").is(':checked')){
                var isable = 1;
            }
            else{
                isable = 0;
            }
            var params= window.location.search;
            params = changeURLPar(params,'isable',isable);
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

        function searchPointsGoods(){
            var params= window.location.search;
            var points_min = $("#points_min").val();
            params= changeURLPar(params,'points_min',points_min);
            var points_max = $("#points_max").val();
            params = changeURLPar(params,'points_max',points_max);
            console.log(params);
            window.location.href = SITE_URL + params;
        }
    </script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>