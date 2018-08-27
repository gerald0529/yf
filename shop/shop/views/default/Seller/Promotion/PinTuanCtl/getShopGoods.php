<?php if($goods_list){ ?>
    <ul class="fn-clear">
        <?php
        foreach($goods_list['items'] as $key=>$goods){
        ?>
            <li style="height:280px">
                <div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
                <div class="goods-name" style="height:15px"><?=$goods['goods_name']?></div>
                <div class="goods-spec" style="height:31px;overflow:hidden;" title="<?=$goods['spec_title']?>">
                <?php 
                if(is_array($goods['spec'])){
                    foreach($goods['spec'] as $v=>$k){
                ?>
                <?= $v; ?>：<span><?= $k ?></span><br/>
                <?php } }?>
                </div>
                <div class="goods-price"><?=__('销售价')?>：<span><?=$goods['goods_price']?></span></div>
                <input type="hidden" name="goods_stock" value="<?=$goods['goods_stock']?>">
                <div class="goods-btn">
                    <?php if($goods['is_join'] == 'false'){?>
                    <a data-type="btn_add_goods" data-id="<?=$goods['goods_id']?>" data-goods-id="<?=Yf_Registry::get('url')?>?ctl=Goods&met=detail&id=<?=$goods['goods_price']?>"  href="javascript:void(0);" class="button button_green"><i class="iconfont icon-jia"></i><?=__('设置为活动商品')?></a>
                    <?php }else{?>
                    <a class="button button_green had" onclick="add_goods_tips()" data-id="<?=$goods['goods_id']?>" data-goods-id="<?=Yf_Registry::get('url')?>?ctl=Goods&met=detail&id=<?=$goods['goods_price']?>"  href="javascript:void(0);"><?=__('选择为拼团商品')?></a>
                        <i class="icon-had"></i>
                    <?php } ?>
                </div> 
            </li>
        <?php  }	?>
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
