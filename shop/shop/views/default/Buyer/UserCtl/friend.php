<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<style>
input.error[type="text"], input.error[type="password"], textarea.error {
    border: 1px dashed #ed5564;
    outline: 0 none;
}
</style>
      <div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab pngFix">
           <li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend"><?=__('查找好友')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend&op=follow"><?=__('我关注的')?></a></li>
            <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend&op=fan"><?=__('关注我的')?></a></li>
			</ul>
        </div>
	<div class="ncm-friend-find"> 
          <!-- 搜索好友start -->
          <form id="search_form" method="post" action="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=friend">
            <div class="search-form">
              <div class="normal">
                <?=__('会员名：')?><input type="text" class="text w300" name="searchname" id="searchname" value="<?=$user_name?>">
                <a class ="bbc_btns ncbtn ncbtn-mint" ><?=__('会员搜索')?></a></div>
            </div>
          </form>
		  <script type="text/javascript">
			$(".ncbtn-mint").on("click", function ()
			{
				// 验证用户名是否为空
				if($('#searchname').val() != ''){
					$('#search_form').submit();
				}else{
					$('#searchname').addClass('error').focus();
				}
			});
			
		</script>
	<?php if($user_name){?>
	<?php if(!empty($user_list['items'])){?>
	<div style=" background:#FFF; padding:2px 20px">
        <div style=" background:#FFF; padding:0px 0">
			<ul class="ncm-friend-list">
				<?php foreach($user_list['items'] as $k=>$v){ ?>
				<li id="recordone_76">
				  <div class="avatar thumbsv"><a target="_blank" data-param="{'id':<?=$v['user_id']?>}" nctype="mcard"><img src="<?php if(!empty($v['user_logo'])){?><?=$v['user_logo']?><?php }else{?><?=$this->web['user_logo']?><?php }?>"></a></div>
				  <dl class="info">
					<dt> <a href="" target="_blank" title="<?=$v['user_name']?>" data-param="{'id':<?=$v['user_id']?>}" nctype="mcard"><?=$v['user_name']?></a><i class=""></i></dt>
					<dd></dd>
					<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage&id=<?=$v['user_id']?>" title="<?=__('站内信')?>"><i class="icon-envelope"></i><?=__('站内信')?></a> </dd>
				  </dl>
				  <div class="follow">
					
					<a href="javascript:void(0)" class="ncbtn-mini-blank bbc_btns" <?php if($v['status']==0){?>style="display:none;"<?php }else{?>style="display:block;"<?php }?>><?=__('已关注')?></a>
					<a class="ncbtn-mini ncbtn-mint bbc_btns"  data-param="{'ctl':'Buyer_User','met':'addFriendDetail','id':'<?=$v['user_id']?>'}" <?php if($v['status']==1){?>style="display:none;"<?php }else{?>style="display:block;"<?php }?> href="javascript:void(0)"><?=__('加关注')?></a>

					</div>
				</li>
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
			Public.tips.success("<?=__('关注成功！')?>");
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
		
		 <?php }else{?>
          <!-- 推荐标签列表start -->
          <div class="ncm-recommend-tag">
			<?php if(!empty($data['items'])){?>
			<?php foreach($data['items'] as $key=>$val){?>
            <dl>
             
              <dd>
                <div class="picture"><span class="thumb size100 thumbsv"><i></i><img title="<?=$val['user_tag_name']?>" src="<?=image_thumb($val['user_tag_image'],120,120)?>" width="120" height="120"></span></div>
                <div class="arrow"></div>
                <div class="content" nctype="content<?=$val['user_tag_id']?>">
                  <p><?=$val['user_tag_content']?></p>
                  <div class="friends">
                    <h5><?=__('这里有')?><strong><?=$val['count']?></strong><?=__('个同样兴趣的用户：')?></h5>
					<?php if(!empty($val['user']['items'])){?>
                     <div>
                     
                      <div class="list" nctype="slider_div">
                        <ul class="F-center">
							<?php foreach($val['user']['items'] as $k=>$v){?>
                            <li><span class="thumb size40 thumbsv"><i></i><a href="javascript:void(0);"><img title="<?=$v['detail']['user_name']?>" src="<?php if(!empty($v['detail']['user_logo'])){?><?=image_thumb($v['detail']['user_logo'],40,40)?><?php }else{?><?=image_thumb($this->web['user_logo'],40,40)?><?php }?>>" width="40" height="40"></a></span></li>
							<?php }?>
                        </ul>
                      </div>
                      
                      <a href="javascript:void(0);" class="care bbc_btns" nctype="batchFollow" data-param="{'ctl':'Buyer_User','met':'addFriends','id':'<?=$val['user_tag_id']?>'}"><i class="iconfont icon-jia"></i><?=__('关注TA们')?></a> </div>
					<?php }else{?>
					   <?=__('暂时没有发现贴上该标签的用户。')?>
						<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=tag"><?=__('点击这里贴上自己的兴趣爱好。')?></a>
					<?php }?>
                    </div>
                </div>
              </dd>
			   <dt><i class="icon-tag"></i><?=$val['user_tag_name']?></dt>
            </dl>
			<?php }?>
			<?php }else{?>
				<div class="no_account">
				  <img src="<?= $this->view->img ?>/ico_none.png"/>
				  <p><?=__('暂无符合条件的数据记录')?></p>
				</div>   
			<?php }?>
          </div>
          <!-- 推荐标签列表end --> 
		 <?php }?>
        </div>
        </div>
      </div>
<script type="text/javascript">
$(function(){
	//关注会员
	$(".care").click(function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));

	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data){
		if(data && 200 == data.status){

			Public.tips.success("<?=__('关注成功！')?>");
			location.href= SITE_URL +"?ctl=Buyer_User&met=friend";
		}else
		{
			Public.tips.error("<?=__('关注失败！')?>");
		}
	});
});
});
</script> 
    </div>
</div>
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>