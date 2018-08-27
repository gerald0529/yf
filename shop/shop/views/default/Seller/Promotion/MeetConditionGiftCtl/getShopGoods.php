<?php if($data['items']){ ?>
	<ul class="fn-clear">
<?php
		foreach($data['items'] as $key=>$goods)
		{
 ?>
		<li>
			<div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
			<div class="goods-name col3" style="height: 15px;"><?=$goods['goods_name']?></div>
			<div class="goods-price cole2"><?=__('销售价')?>：<span class="cole2"><?=$goods['goods_price']?></span></div>
			<div class="goods-spec  one-overflow col9" style="height: 20px;" title="<?=$goods['spec_title']?>">
                <?php 
                if(is_array($goods['spec'])){
                    foreach($goods['spec'] as $v=>$k){
                ?>
                <?= $v; ?>：<?= $k ?>
                <?php } }?>
            </div>
			<div class="goods-btn">
				<?php if($goods['is_join'] == 'false'){?>
					<a data-type="btn_add_goods" data-id="<?=$goods['goods_id']?>" data-goods-id="<?=Yf_Registry::get('url')?>?ctl=Goods&met=detail&id=<?=$goods['goods_price']?>"  href="javascript:void(0);" class="button button_green"><i class="iconfont icon-jia"></i><?=__('设置为活动商品')?></a>
				<?php }else{?> 
					<a class="button button_green had" onclick="add_goods_tips()" data-id="<?=$goods['goods_id']?>" data-goods-id="<?=Yf_Registry::get('url')?>?ctl=Goods&met=detail&id=<?=$goods['goods_price']?>"  href="javascript:void(0);"><?=__('设置为活动商品')?></a>
					 <i class="icon-had"></i>
				<?php } ?>
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

<script type="text/javascript">
    function add_goods_tips(){
        Public.tips.warning('<?=__('该商品已参加活动！')?>');
        return ;
    }
</script>