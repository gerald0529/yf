<div class="search-goods-list-bd fn-clear">
    <div class="search-goods-list-hd">
        <label><?=__('搜索店内商品')?></label>
        <input type="text" name="goods_name" class="text w200" id="rule_sku_key_<?=@$date_level?>" value="">
        <a class="button btn_search_goods btn-sku-search-goods" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Increase&met=getShopGoodsSku&typ=e&level=<?=@$date_level?>"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
    </div>
    <?php if($data['items']){ ?>
    <ul class="fn-clear">
        <?php
            foreach($data['items'] as $key=>$goods)
            {
                ?>
                <li style="height:300px">
                    <div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
                    <div class="goods-name"><?=$goods['goods_name']?></div>
                    <div class="goods-price"><?=__('销售价')?>：<span><?=$goods['goods_price']?></span></div>
                    <div class="goods-spec" style="height:31px;overflow:hidden;" title="<?=$goods['spec_title']?>">
                        <?php 
                        if(is_array($goods['spec'])){
                            foreach($goods['spec'] as $v=>$k){
                        ?>
                        <?= $v; ?>：<span><?= $k ?></span><br/>
                        <?php } }?>
                    </div>
                    <div class="goods-btn">
                        <div data-type="btn_add_sku_goods"  data-id="<?=$goods['goods_id']?>" btn-sku-enabled="<?=$goods['goods_id']?>" data-level="<?=@$date_level?>" href="javascript:void(0);" class="button button_green"><i class="iconfont icon-jia"></i><?=__('设置为换购商品')?></div>
                        <div data-id="<?=$goods['goods_id']?>" href="javascript:void(0);" btn-sku-disabled="<?=$goods['goods_id']?>" class="<?=$goods['is_join']=='true'?'':'hidden'?>" style="height:28px;"><?=__('已加入活动')?></div>
                    </div>
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
</div>

<script>
    $.extend(window.couLevelSkuInSearch,<?=$rows?>);
</script>