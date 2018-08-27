<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
</div>
 <ul class="tracks_con_types clearfix">
			<?php if(!empty($cat)){ ?>
            <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=footprint" class="<?php if($classid == ''){ ?>bbc_btns<?php }?>"><?=__('全部分类')?></a></li>
			
			<?php foreach($cat as $val){ ?>
            <li ><a class="<?php if($classid == $val['cat_id']){ ?>bbc_btns<?php }?>" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=footprint&classid=<?=$val['cat_id']?>"><?=$val['cat_name']?></a></li>
			<?php } ?>
			<?php } ?>
          </ul>
<div class="tracks_con_more">
			<?php if(!empty($data['items'])){?>
			<?php foreach($data['items'] as $key=>$val){ ?>
            <div class="tracks_con_time_list">
              <p class="tracks_con_time_p"><i class="bgred"></i><span></span><time><?=$key?></time><label class="ml10"><input type="checkbox" class="js-selAll ml4">全选</label><a href="javascript:void(0)" data-param="{'ctl':'Buyer_Favorites','met':'delFootPrint','time':'<?=$key?>'}" class="delete" title="<?=__('删除')?>"><i class="icon-trash iconfont icon-lajitong mar0"></i><?=__('删除')?></a></p>
              <ul class="clearfix li_hover">
				<?php foreach($val as $k=>$v){ ?>
				<?php if(!empty($v['goods'])){?>
                <li data-common_id="<?=$v['goods']['common_id']?>">
                 <a class="posr" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods']['goods_id']?>" target="_blank">
                    <?php if($v['goods']['is_del'] == 2){?>
                 	    <p class="old-Failed old-Failed-wh2 ">此商品已失效</p>
                    <?php }?>
                 	<img src="<?php if($v['goods']['common_image']){?><?=image_thumb($v['goods']['common_image'],118,118)?><?php }else{?><?=image_thumb($this->web['goods_image'],118,118)?><?php }?>"/></a>
                  <span><?=format_money($v['goods']['common_price'])?></span>
                 <!--  <a class="add_cart" href="javascript:void(0)" title="<?=__('加入购物车')?>" data-param="{'ctl':'Buyer_Cart','met':'addCart','id':'<?=$v['goods']['goods_id']?>','num':'1'}"><i class="iconfont icon-zaiqigoumai f18 vermiddle"></i><?=__('加入购物车')?></a> -->
	                 <button class="btn-del-footprint">删除足迹</button>
	                 <div class="del-tips">
	                 	<p><i class="iconfont icon-tips"></i>确定删除么？</p>
	                 	<div>
	                 		<button class="btn-del-sure delete" data-param="{'ctl':'Buyer_Favorites','met':'delFootPrint','time':'<?=$key?>','common_id':'<?=$v['goods']['common_id']?>'}">删除</button><button class="btn-del-cancel">取消</button>
	                 	</div>
	                 	
	                 </div>
	                 <div class="sel-active"><i class="iconfont icon-yes"></i></div>
                </li>
				<?php }?>
				<?php }?>
				
              </ul>

            </div>
			<?php }?>
			<?php }else{ ?>
			 <div class="no_account">
				<img src="<?= $this->view->img ?>/ico_none.png"/>
				<p><?=__('暂无符合条件的数据记录')?></p>
			</div>  
			<div style="clear:both"></div>
			<?php } ?>
			<?php if($page_nav){?>
			<div class="flip page page_front clearfix" style="text-align: center;">
				<?=$page_nav?>
			</div>
			<?php }?>
			<div style="clear:both"></div>
          </div> 
          
    </div>
   </div>
 </div>
</div>
<script type="text/javascript">
$(".js-selAll").click(function(){
	if(this.checked==true){
		$(this).parents(".tracks_con_time_list").find(".sel-active").addClass("active");
	}else{
		$(this).parents(".tracks_con_time_list").find(".sel-active").removeClass("active");
	}
})
$(".li_hover li").click(function(){
	if($(this).find(".sel-active").hasClass('active')){
		$(this).find(".sel-active").removeClass("active");
        $('.js-selAll').attr('checked',false);
	}else{
		$(this).find(".sel-active").addClass("active");
	}
})
$(".add_cart").click(function(){
	eval('data_str =' + $(this).attr('data-param'));
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{goods_id:data_str.id,goods_num:data_str.num},function(data){
		if(data && 200 == data.status){
			
			Public.tips.success("<?=__('加入成功！')?>");
		}else
		{
			Public.tips.error("<?=__('加入失败！')?>");
		}
	});
});
$(".delete").click(function(){
	eval('data_str =' + $(this).attr('data-param'));
	var common_id = [];
	if(data_str.common_id)
    {
        common_id.push(data_str.common_id);
    }else{
        $(this).parents('.tracks_con_time_list').find('li').each(function(){
            console.log($(this));
            if($(this).find('.sel-active').hasClass('active'))
            {
                console.log($(this).data('common_id'));
                common_id.push($(this).data('common_id'));
            }
        })
    }
    if(common_id.length <= 0){
        Public.tips.error("<?=__('请选择要删除的足迹！')?>");
        return false;
    }
	$.dialog.confirm("<?=__('确认删除？')?>",function(){
        $.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{time:data_str.time,id:data_str.classid,common_id:common_id},function(data){
            if(data && 200 == data.status){
                // e.parents("div:first").hide('slow');
                window.location.reload();
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