<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<style>
.manage-wrap .ncap-form-default,
.manage-wrap .ncap-form-all { width: 96%; margin: 0 auto; padding: 0; }
.manage-wrap .ncap-form-default dt.tit { text-align: right; width: 20%; padding-right: 2%; }
.manage-wrap .ncap-form-default dd.opt { text-align: left; width: 77%; }
.manage-wrap .ncap-form-all dl.row { padding: 8px 0; }
.manage-wrap .ncap-form-all dt.tit { font-size: 12px; font-weight: 600; line-height: 24px; background-color: transparent; height: 24px; padding: 4px; }
.manage-wrap .ncap-form-all dd.opt { font-size: 12px; padding: 0; border: none; }
.manage-wrap .ncap-form-all .search-bar { padding: 4px; }
.manage-wrap .bot { text-align: center; padding: 12px 0 10px 0 !important; }
.manage-wrap .rule-goods-list { position: relative; z-index: 1; overflow: hidden; max-height: 200px; }
.manage-wrap .rule-goods-list ul { font-size: 0; }
.manage-wrap .rule-goods-list ul li { font-size: 12px; vertical-align: top; display: inline-block; width: 48%; padding: 1%; }
.manage-wrap .rule-goods-list ul li img { float: left; width: 32px; height: 32px; margin-right: 5px; }
.manage-wrap .rule-goods-list ul li a,
.manage-wrap .rule-goods-list ul li span { color: #555; line-height: 16px; white-space: nowrap; text-overflow: ellipsis; display: block; float: left; width: 180px; height: 16px; overflow: hidden; }
.manage-wrap .rule-goods-list ul li span { color: #AAA; }
.manage-wrap .rule-goods-list ul li img {
    float: left;
    width: 32px;
    height: 32px;
    margin-right: 5px;
}
.cou-rule { padding: 5px; border: dotted 1px #E7E7E7; margin-bottom: 10px; overflow: hidden; }
.cou-rule span { color: #2cbca3; }
</style>
<body>
	<div id="manage-wrap" class="manage-wrap">
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">活动名称</dt>
				<dd class="opt" id="mansong_name"><?=$data['increase_name']?></dd>
			</dl>
			<dl class="row">
				 <dt class="tit">活动店铺</dt>
				 <dd class="opt" id="store_name"><?=$data['shop_name']?></dd>
			</dl>
			<dl class="row">
				<dt class="tit">活动时间段</dt>
				<dd class="opt"><span id="start_time"><?=$data['increase_start_time']?></span> ~ <span id="end_time"><?=$data['increase_end_time']?></span></dd>
			</dl>
			<dl class="row">
                <dt class="tit">活动参与商品</dt>
                <dd class="opt">
                    <div class="rule-goods-list">
                        <ul class="promotion-ms">
                            <?php foreach($data['goods'] as $key=>$goods){ ?>
                            <li title="<?=$goods['goods_name']?>"> <img alt="" src="<?=$goods['goods_image']?>" style="width:30px;">
                                <a target="_blank" href=""><?=$goods['goods_name']?></a>
                                <span>商品价：<em> ￥<?=$goods['goods_price']?></em></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">活动换购规则</dt>
                <dd class="opt">
                    <div class="rule-goods-list">
                        <?php if(@$data['rule']){ foreach($data['rule'] as $key=>$rule){ ?>
                        <div class="cou-rule">
                            <span>规则<?=$key+1?>：消费满<strong><?=$rule['rule_price']?></strong>元可换购最多<strong><?=$rule['rule_goods_limit']?></strong>种优惠商品</span>
                            <?php if(@$rule['redemption_goods']){ ?>
                            <ul class="promotion-ms">
                                <?php foreach($rule['redemption_goods'] as $kk=>$rede_goods){ ?>
                                    <li title="">
                                        <img alt="" src="<?=$rede_goods['goods_image']?>" style="width:30px;">
                                        <a target="_blank" href=""></a>
                                        <span>换购价：<em> ￥<?=$rede_goods['redemp_price']?></em></span>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php }  ?>
                        </div>
                        <?php } } ?>
                    </div>
                </dd>
            </dl>
	    </div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>