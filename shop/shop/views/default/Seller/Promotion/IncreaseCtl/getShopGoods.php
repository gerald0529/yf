<div class="search-goods-list-hd">
    <label><?=__('搜索店内商品')?></label>
    <input type="text" name="goods_name" class="text w200" id="key" value="">
    <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
</div>
<div class="search-goods-list-bd fn-clear">
    <?php if($data['items']){ ?>
    <ul class="fn-clear">
    <?php
            foreach($data['items'] as $key=>$goods)
            {
     ?>
        <li style="height:280px">
            <div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
            <div class="goods-name col3" style="height:15px"><?=$goods['goods_name']?></div>
            <div class="goods-price cole2"><?=__('销售价')?>：<span class="cole2"><?=format_money($goods['goods_price'])?></span></div>
            <div class="goods-spec one-overflow col9" style="height: 20px;"  title="<?=$goods['spec_title']?>">
                <?php 
                if(is_array($goods['spec'])){
                    foreach($goods['spec'] as $v=>$k){
                ?>
                <?= $v; ?>：<?= $k ?>
                <?php } }?>
            </div>
            <div class="goods-btn">
                <div data-type="btn_add_act_goods"  data-id="<?=$goods['goods_id']?>"  btn-enabled="<?=$goods['goods_id']?>"  class="<?=$goods['is_join']=='true'?'hidden':'button'?> button_green"><i class="iconfont icon-jia"></i><?=__('设置为活动商品')?></div>
               <div data-id="<?=$goods['goods_id']?>" onclick="add_goods_tips()" btn-disabled="<?=$goods['goods_id']?>" class="<?=$goods['is_join']=='true'?'button button_green had':'hidden'?>" style="height:28px;"><i class="icon-had" style="right: -70px;top: -222px;"></i><i class="iconfont icon-jia"></i><?=__('设置为活动商品')?></div>
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
    var initGoodsItems = <?=$rows?>;
    /*设置为加价购活动商品*/
    $('.goods-btn').on('click','[data-type="btn_add_act_goods"]',function(){
        var id = $(this).attr('data-id');

        //同一规格属性的一件商品只可以参加一次
        if ($(".join-act-goods-sku tr[data-goods-id='"+id+"']").length > 0)
        {
            return false;
        }
        var i = initGoodsItems[id];

        var temp = $('#goods-sku-item-tpl').html();
        temp = temp.replace(/__([a-zA-Z]+)/g, function(r, $1) {
            return i[$1];
        });

        var $temp = $(temp);
        $temp.find('img[data-src]').each(function() {
            this.src = $(this).attr('data-src');
        });

        $('.join-act-goods-sku').append($temp);

        // 商品已经添加过活动，按钮切换
        $("div[btn-disabled='"+id+"']").show();
        $("div[btn-disabled='"+id+"']").addClass('button button_green had');
        $("div[btn-enabled='"+id+"']").hide();

    });

    //搜索店内商品
     $('.btn_search_goods').click(function(){
     var increase_id = $(this).attr('data-increase-id');
     var url = SITE_URL + '?ctl=Seller_Promotion_Increase&met=getShopGoods&typ=e&op=edit';
     var key = $("#key").val();
     url = key ? url + "&goods_name=" + key : url;
     $('#cou-sku-options').load(url,{id:increase_id});
     });
    function add_goods_tips(){
        Public.tips.warning('<?=__('该商品已参加活动！')?>');
        return ;
    }

</script>
