<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
	</div>
		<?php if(!empty($data['items'])){?>
          <div id="favoritesGoods">
            <div class="favorite-goods-list">
              <ul>
				<?php foreach($data['items'] as $val){ ?>
				<?php if(!empty($val['detail'])){?>
                <li class="favorite-pic-list">
                  <div class="favorite-goods-thumb">
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['detail']['goods_id']?>" target="_blank" title="<?=$val['detail']['goods_name']?>">
                      <div class="jqthumb posr" style="width: 150px; height: 150px; opacity: 1;">
                        <?php if($val['detail']['is_del']== 2){ ?>
                      	<p class="old-Failed wp100 old-Failed-wh">此商品已失效</p>
                        <?php } ?>
                        <div style="width: 100%; height: 100%; background-image: url(<?=$val['detail']['goods_image']?>); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"></div>
                      </div>
                      <img src="<?=image_thumb($val['detail']['goods_image'],150,150)?>" style="display: none;"></a>
                  </div>
                  <div class="handle">
                    <a href="javascript:void(0)" data-param="{'ctl':'Buyer_Favorites','met':'delFavoritesGoods','id':'<?=$val['goods_id']?>'}" class="fr ml5 delete" title="<?=__('删除')?>"><i class=""><?=__('删除')?></i>
                    </a>
                      <?php if (!$val['is_virtual']) { ?>
                        <a href="javascript:void(0)" class="fr add_cart" title="<?=__('加入购物车')?>" data-param="{'ctl':'Buyer_Cart','met':'addCart','id':'<?=$val['goods_id']?>','num':'1'}"> <i class=""><?=__('加入购物车')?></i>
                        </a>
                      <?php } ?>
                    
                  </div>
                  <dl class="favorite-goods-info">
                    <dt>
                      <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['detail']['goods_id']?>" target="_blank" title="<?=$val['detail']['goods_name']?>"><?=$val['detail']['goods_name']?></a>
                    </dt>
                    <dd class="goods-price">
                      <strong class="common-color"><?=format_money($val['detail']['goods_price'])?></strong>
                    </dd>
                  </dl>
                </li>
				<?php }?>
				<?php }?>              
              </ul>
            </div>
            </div>

	   <?php }else{?>
		 <div class="no_account">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <p><?=__('暂无符合条件的数据记录')?></p>
        </div> 
        <div style="clear:both"></div> 
  	   <?php }?>
  	   <div class="flip page page_front clearfix">
         <?=$page_nav?>
        </div>
          <div style="clear:both"></div>
       </div>
        
<script type="text/javascript">
$(".add_cart").on('click', function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{goods_id:data_str.id,goods_num:data_str.num},function(data){
		if(data && 200 == data.status){
            var cat_num = parseInt($('#cart_num').html());
            $('#cart_num').html(cat_num+1);
            e.hide('slow');
			Public.tips.success("<?=__('加入成功！')?>");
		}else
		{
			Public.tips.error("<?=__('加入失败！')?>");
		}
	});
});
$(".delete").click(function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));
	$.dialog.confirm("<?=__('确认删除？')?>",function(){ 
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data){
		if(data && 200 == data.status){
		
			e.parents("li:first").hide('slow');

		}else
		{
			Public.tips.error("<?=__('删除失败！')?>");
		}
	});
	});
});
</script>
</div>
</div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
