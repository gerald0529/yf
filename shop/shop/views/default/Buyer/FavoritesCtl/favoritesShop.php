<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';

?>

		</div>
         <!--  有内容显示 -->
		  <?php if(!empty($data['items'])){?>
		  <?php foreach($data['items'] as $val){ ?>

		  <?php if(!empty($val['shop'])){?>
          <div class="ncm-favorite-store">
            <div class="store-info">
             <div class="store-pic" > 
                 <img src="<?=image_thumb($val['shop_logo'],150,60)?>"></div>
             <dl>
               <dt>
                 <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&id=<?=$val['shop']['shop_id']?>" target="_blank"><?=$val['shop']['shop_name']?></a>
               </dt>
               <dd>
                 <?=__('联系方式：')?>
                 <span member_id="7"><?=$val['shop']['shop_tel']?></span>
               </dd>

               <dd><?=__('所在地：')?><?=$val['shop_company_address']?></dd>
             </dl>
             <div class="handle" >
              
               <a href="javascript:void(0)" data-param="{'ctl':'Buyer_Favorites','met':'delFavoritesShop','id':'<?=$val['shop']['shop_id']?>'}" class="fr ml5 delete" title="<?=__('删除')?>"><i class="icon-trash iconfont icon-lajitong"></i>
               </a>
             </div>
           </div>
           <div class="store-goods" >
             <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=$val['shop']['shop_id']?>" class="more" target="_blank"><?=__('查看更多')?><i class="iconfont icon-iconjiantouyou"></i></a>
<!--             <div class="show-tab" data-sid="6">
               <a href="javascript:void(0)" class="current "><?//=__('优惠促销')?></a>
             </div>-->
             <div class="show-list" >
               <ul>
				<?php if(!empty($val['shop']['detail']['items'])){?>
				<?php foreach($val['shop']['detail']['items'] as $v){ ?>
                 <li>
                   <div class="goods-thumb">
                     <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods_id']?>" title="<?=$v['common_name']?>" target="_blank">
                       
                       <img src="<?php if($v['common_image']){?><?=image_thumb($v['common_image'],120,120)?><?php }else{?><?=image_thumb($this->web['goods_image'],120,120)?><?php }?>" height="120" width="120"></a>
                   </div>
                   <p><?=format_money($v['common_price'])?></p>
                 </li>
                 <?php }?>
				<?php } ?>
               </ul>
             </div>
             
           </div>
          </div> 
		  <?php }?>
		  <?php }?>
		<?php }else{ ?>
		 <div class="no_account">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <p><?=__('暂无符合条件的数据记录')?></p>
        </div>  
		<?php }?>
          <div class="flip page page_front clearfix">
           <?=$page_nav?>
          </div>
       </div>
     </div>
 </div>
 </div>
<script>	
$(".delete").click(function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));
	$.dialog.confirm("<?=__('确认删除？')?>",function(){ 
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data){
		if(data && 200 == data.status){

			Public.tips.success("<?=__('删除成功！')?>");
			location.href= SITE_URL+"?ctl=Buyer_Favorites&met=favoritesShop";
		}else
		{
			Public.tips.error("<?=__('删除失败！')?>");
		}
	});
	});
});	 
</script>		 
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>