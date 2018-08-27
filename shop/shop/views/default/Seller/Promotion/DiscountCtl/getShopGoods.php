<?php if($data['items']){ ?>
<ul class="fn-clear">
    <?php
        foreach($data['items'] as $key=>$goods)
        {
            ?>
            <li>
                <div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
                <div class="goods-name col3" style="height: 15px;"><?=$goods['goods_name']?></div>
                <div class="goods-price cole2"><?=__('销售价')?>：<span class="cole2"><?=format_money($goods['goods_price'])?></span></div>
                <div class="goods-spec one-overflow col9" style="height: 20px;" title="<?=$goods['spec_title']?>">
                    <?php 
                    if(is_array($goods['spec'])){
                        foreach($goods['spec'] as $v=>$k){
                    ?>
                    <?= $v; ?>：<?= $k ?>
                    <?php } }?>
                </div>
                <?php if($goods['is_join'] == 'false'){?>
                <div class="goods-btn" ><div class="ncbtn-mini" class="button"  ><a data-type="btn_add_goods" data-storage="<?=@$goods['goods_stock']?>" data-goods-id="<?=$goods['goods_id']?>" data-common-id="<?=$goods['common_id']?>" data-goods-name="<?=$goods['goods_name']?>" data-goods-img="<?=$goods['goods_image']?>" data-goods-price="<?=$goods['goods_price']?>" data-goods-price-format ="<?=format_money($goods['goods_price'])?>" data-id="<?=$goods['goods_id']?>" common-id="<?=$goods['common_id']?>" href="javascript:void(0);" class="button button_green"><i class="iconfont icon-jia"></i><?=__('选择为折扣商品')?></a></div></div> 
                <?php }else{ ?>
                <div class="goods-btn"><div class="ncbtn-mini"><a onclick="add_goods_tips()" data-id="<?=$goods['goods_id']?>" common-id="<?=$goods['common_id']?>" class="button button_green had"><i class="iconfont icon-jia"></i><?=__('选择为折扣商品')?></a></div></div><i class="icon-had"></i>
                <!--若已参加同时段的活动时，请给class为button的a标签添加class为had，即可改变背景色 2017.6.27--> 
                <?php }?>
            </li>
        <?php 	}	?>
</ul>
<?php }else{ ?>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p>暂无符合条件的数据记录</p>
    </div>
    <?php
}
?>
<?php if($page_nav){ ?>
    <div class="goods-page fn-clear">
        <div class="mm">
            <div class="page"><?=$page_nav?></div>
        </div>
    </div>
<?php } ?>



<div id="dialog_add_discount_goods" style="display:none;">
    <input id="dialog_goods_id" type="hidden">
    <input id="dialog_common_id" type="hidden">
    <input id="dialog_input_goods_price" type="hidden">
    <input id="dialog_input_goods_price_format" type="hidden">
    <div class="eject_con">
        <div id="dialog_add_discount_goods_error" class="alert alert-error">
            <label for="dialog_xianshi_price" class="error" >
                <i class='iconfont icon-exclamation-sign'></i><?=__('折扣价格不能为空，且必须小于商品价格')?>
            </label>
        </div>
        <div class="selected-goods-info">
            <div class="goods-thumb"><img id="dialog_goods_img" src="" alt=""></div>
            <dl class="goods-info">
                <dt id="dialog_goods_name"></dt>
                <dd><?=__('销售价格')?>：<?=Web_ConfigModel::value('monetary_unit')?><strong class="red"><font id="dialog_goods_price"></font></strong></dd>
                <dd><?=__('库存')?>：<span id="dialog_goods_storage"></span> <?=__('件')?></dd>
                <dd><span style="color: red;">（活动进行中，若当前库存低于购买下限，则剩余商品依旧按优惠价格出售，请留意）</span></dd>
            </dl>
        </div>
        <dl>
            <dt><?=__('限时折扣价格')?>：</dt>
            <dd>
                <input id="dialog_discount_price" type="text" class="text w70"><em class="rmb">￥</em>
                <p class="hint"><?=__('限时折扣价应低于正常商品售价，活动开始时，系统将自动转换销售价为促销价')?>。</p>
            </dd>
        </dl>
        <div class="eject_con">
            <div class="bottom">
                <label class="submit-border"><a id="btn_submit" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('提交')?></a></label>
            </div>
        </div>
    </div>
</div>
