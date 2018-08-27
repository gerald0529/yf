<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

<div class="aright">
<div class="member_infor_content">
      <div class="tabmenu">
		<ul class="tab">
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message"><?=__('系统消息')?><?=($this->countMessage['message'] ? sprintf('(%d)', $this->countMessage['message']) : '')?></a></li>
			<li <?php if($op == 'receive'){ echo 'class="active"';} ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=receive"><?=__('收到消息')?><?=($this->countMessage['receive'] ? sprintf('(%d)', $this->countMessage['receive']) : '')?></a></li>
            <li <?php if($op == 'send'){ echo 'class="active"';} ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=send"><?=__('已发送消息')?></a></li>
            <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageAnnouncement"><?=__('系统公告')?><?=($this->countMessage['article'] ? sprintf('(%d)', $this->countMessage['article']) : '')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageManage"><?=__('接收设置')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage"><?=__('发送站内信')?></a></li>
        </ul>
    </div>
      <table class="ncm-default-table annoc_con">
          <thead>
            <tr>
             
              <th class="w100"><?php if($op=='receive'){?><?=__('发信人')?><?php }else{?><?=__('收件人')?><?php }?></th>
              <th class="tl opti" class="w300"><?=__('内容')?></th>
              <th class="w300"><?=__('最后更新')?></th>
              <th class="w150"><?=__('操作')?></th>
            </tr>

          </thead>
		  <?php
				if($data['items'])
				{
            ?>
		  <tbody>
			
		   <?php
				foreach ($data['items'] as $key => $value)
				{
            ?>
            <tr class="bd-line">
              
              <td><?php if($op=='receive'){?><?= $value['user_message_send'] ?><?php }else{?><?= $value['user_message_receive'] ?><?php }?></td>
              <td class="tl opti <?php if($op=='receive'){?> btn-bluejeans<?php }?> <?php if($value['message_islook']==0){;?>dark<?php }?>" data-id="<?= $value['user_message_id'] ?>"><?= $value['user_message_content'] ?></td>
              <td><?= $value['user_message_time'] ?></td>
              <td class="ncm-table-handle"><span>
			  <?php if($op == 'receive'){ ?>
				<span class="edit"><a  class="btn-bluejeans" data-id="<?= $value['user_message_id'] ?>" dialog_width="550" dialog_title="<?=__('查看消息')?>"  href="javascript:void(0)"><i class="iconfont iconf icon-chakan"></i><?=__('查看')?><!--<p class="bbuyer_news"><?=$value['receive']?></p>--></a></span>
			<?php }?>
					<span class="del"><a class="btn-grapefruit" data-param="{'ctl':'Buyer_Message','met':'delUserMessage','id':'<?=$value['user_message_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span></td>
            </tr>
			<?php }?>
          </tbody>
		  <?php }else{?>
          <tbody>
            <tr>
              <td colspan="20" class="norecord">
			   <div class="no_account">
					<img src="<?= $this->view->img ?>/ico_none.png"/>
					<p><?=__('暂无符合条件的数据记录')?></p>
				</div>  
			  </td>
            </tr>
          </tbody>
		  <?php }?>
        </table>
		<?php if($page_nav){ ?>
			<div style="clear:both"></div>
            <div class="page page_front">
                <?=$page_nav?>
            </div>
            <div style="clear:both"></div>
		<?php } ?>
</div>
</div>
</div>
</div>

<script>
  $(".btn-bluejeans").bind("click",function(){
	  var id = $(this).attr("data-id");
	  var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=changeUserMessage&typ=json';
	  $.ajax({
			url: ajax_url,
			data:{id:id},
			success:function(a){
				if(a.status == 200)
				{
					 $(this).parents('tr:first').children('.tl').removeClass('dark');
					 location.href = SITE_URL+"?ctl=Buyer_Message&met=message&op=detail&id="+id;
					 //window.open(url);
					
				}
				else
				{
					Public.tips.error("<?=__('查看失败！')?>");
				}
			}
		});
  });
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



