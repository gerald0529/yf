<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
      <div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab">
           <li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message"><?=__('系统消息')?><?=($this->countMessage['message'] ? sprintf('(%d)', $this->countMessage['message']) : '')?></a></li>
			<li <?php if($op == 'receive'){ echo 'class="active"';} ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=receive"><?=__('收到消息')?><?=($this->countMessage['receive'] ? sprintf('(%d)', $this->countMessage['receive']) : '')?></a></li>
            <li <?php if($op == 'send'){ echo 'class="active"';} ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=send"><?=__('已发送消息')?></a></li>
            <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageAnnouncement"><?=__('系统公告')?><?=($this->countMessage['article'] ? sprintf('(%d)', $this->countMessage['article']) : '')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageManage"><?=__('接收设置')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage"><?=__('发送站内信')?></a></li>
			</ul>
        </div>
<div class="message_type fn-clear">
	<a class="<?php if($type=='0'){?>cur<?php }?>" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message"><?=__('全部类型')?></a>
    <?php foreach($remind_cat as $key=>$val){ ?>
    <em>|</em>
	<a class="<?php if($type==$key){?>cur<?php }?>" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&type=<?=$key?>"><?=$val?></a>
    <?php }?>
</div>
<div class="message">
<form id="form" action="" method="post" >
<input type="hidden" name="act" />
<input type="hidden" name="type" value="<?=$type?>" />
       <!-- <h3><span></span></h3>-->
        <?php if($data['items']){ ?>
        <?php foreach($data['items'] as $key=>$val){ ?>
        <dl class="fn-clear">
            <dt>
                <label><input type="checkbox" class="checkitem" name="chk[]" value="<?=$val['message_id'];?>" /></label>
				<?=$val['message_create_time'];?><span class="edit"><a  class="btn-bluejeans" data-id="<?= $val['message_id'] ?>" dialog_width="550" dialog_title="<?=__('查看消息')?>"  href="javascript:void(0)"></a></span>
            </dt>
            <dd>
                <a href="javascript:void(0);">
                    <h4><?=$val['message_title'];?></h4>
                    <p data-id="<?= $val['message_id'] ?>" class="tl btn-bluejeans <?php if($val['message_islook']==0){;?>dark<?php }?>"><?=$val['message_content'];?></p>
                </a>
            </dd>
        </dl>
		<?php }?>
		<?php }else{?>
		 <div class="no_account">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <p><?=__('暂无符合条件的数据记录')?></p>
        </div>  
		<?php }?>
    <?php if($data['items']){ ?>
    <div class="toolBar fn-clear">
        <label class="checkall"><input class="checkall" type="checkbox" /> <?=__('全选')?></label>
        <span>|</span>
        <label class="del" ><i class="iconfont icon-lajitong del_pos"></i><a data-param="{'ctl':'Buyer_Message','met':'delAllMessage'}" ><?=__('删除')?></a></label>
        <div style="clear:both"></div><div class="flip page page_front clearfix">
           <?=$page_nav?>
          </div><div style="clear:both"></div>
    </div>
    <?php }?>
</form>    
</div> 
<script language="javascript">

	$(".btn-bluejeans").bind("click",function(){
	  var obj = $(this);
	  var id = $(this).attr("data-id");
	  var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=changeMessage&typ=json';
	  $.ajax({
			url: ajax_url,
			data:{id:id},
			success:function(a){
				if(a.status == 200)
				{
					// obj.parents("dl").find('.tl').removeClass('dark');
					obj.removeClass('dark');
					location.href = SITE_URL+"?ctl=Buyer_Message&met=message";
				}
				else
				{
					Public.tips.error("<?=__('查看失败！')?>");
				}
			}
		});
  });
</script>
	</div> 
	</div> 
  </div> 
</div> 

 
</div>
  </div>
</div>
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>