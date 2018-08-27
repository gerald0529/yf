<?php if($data){ ?>
    <ul class="fn-clear">
        <?php
        foreach($data as $key=>$goods)
        {
            ?>
            <li>
                <div class="goods-image"><img src="<?=image_thumb($goods['common_image'],140,140)?>" /></div>
                <div class="goods-name"><?=$goods['common_name']?></div>
                <div class="goods-price"><?=__('销售价')?>：<span><?=$goods['common_price']?></span></div>
                <?php if($goods['is_join'] == 'false'){?>
                <div class="goods-btn"><div class="ncbtn-mini" class="button" data-storage="<?=@$goods['goods_stock']?>" data-goods-id="<?=$goods['goods_id']?>" data-common-id="<?=$goods['common_id']?>" data-goods-name="<?=$goods['goods_name']?>" data-goods-img="<?=$goods['goods_image']?>" data-goods-price="<?=$goods['goods_price']?>" data-goods-price-format ="<?=format_money($goods['goods_price'])?>" ><a data-type="btn_add_goods" data-id="<?=$goods['goods_id']?>" common-id="<?=$goods['common_id']?>" href="javascript:void(0);" class="button button_green"><i class="iconfont icon-jia"></i><?=__('选择为团购商品')?></a></div></div> 
                <?php }else{ ?>
                <div class="goods-btn"><div class="ncbtn-mini"><a onclick="add_goods_tips()" data-id="<?=$goods['goods_id']?>" common-id="<?=$goods['common_id']?>" class="button button_green had"><i class="iconfont icon-jia"></i><?=__('选择为团购商品')?></a></div></div><i class="icon-had"></i>
               
                <?php }?>
            </li>
        <?php }	?>
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
