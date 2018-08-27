<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
      <div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab pngFix">
           <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend"><?=__('查找好友')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend&op=follow"><?=__('我关注的')?></a></li>
            <li  class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend&op=fan"><?=('关注我的')?></a></li>
			</ul>
        </div>
	<div class="ncm-friend-find">  
	<?php if(!empty($friend_list['items'])){?>
	<div style=" background:#FFF; padding:2px 20px">
        <div style=" background:#FFF; padding:0px 0">
			<ul class="ncm-friend-list">
				<?php foreach($friend_list['items'] as $k=>$v){ ?>
				<?php if(!empty($v['detail'])){?>
				<li id="recordone_76">
				  <div class="avatar thumbsv"><a href="" target="_blank" data-param="{'id':<?=$v['detail']['user_id']?>}" nctype="mcard"><img src="<?php if(!empty($v['detail']['user_logo'])){?><?=image_thumb($v['detail']['user_logo'],60,60)?><?php }else{?><?=$this->web['user_logo']?><?php }?>" alt="<?=$v['detail']['user_name']?>"></a></div>
				  <dl class="info">
					<dt> <a href="" target="_blank" title="<?=$v['detail']['user_name']?>" data-param="{'id':<?=$v['detail']['user_id']?>}" nctype="mcard"><?=$v['detail']['user_name']?></a><i class=""></i></dt>
					<dd></dd>
					<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage&id=<?=$v['detail']['user_id']?>"  title="<?=__('站内信')?>"><i class="icon-envelope"></i><?=__('站内信')?></a> </dd>
				  </dl>
				  <div class="follow">
				 
					<p class="bbc_btns" <?php if($v['detail']['status']==1){?>style="display:block;"<?php }else{?> style="display:none;"<?php }?>>
						<i></i>
						<?=__('互相关注')?>
					</p>
					<a class="ncbtn-mini ncbtn-mint  bbc_btns"  data-param="{'ctl':'Buyer_User','met':'addFriendDetail','id':'<?=$v['detail']['user_id']?>'}" <?php if($v['detail']['status']==1){?>style="display:none;"<?php }else{?>style="display:block;"<?php }?> href="javascript:void(0)"><?=__('加关注')?></a>

					</div>
				</li>
				<?php }?>
				<?php }?>
			</ul> 
        </div>
     </div>
<script>	
$(".ncbtn-mint").click(function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data){
		if(data && 200 == data.status){
	
			e.prev().css('display','block');
			e.css('display','none');
			
		}else
		{
			Public.tips.error("<?=__('关注失败！')?>");
		}
	});
	
});	 
</script>		
	<?php }else{?>
	 <div class="no_account">
		<img src="<?= $this->view->img ?>/ico_none.png"/>
		<p><?=__('暂无符合条件的数据记录')?></p>
	</div>  
	<?php }?>
		
    </div>
</div>
</div>
</div>
</div>
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>