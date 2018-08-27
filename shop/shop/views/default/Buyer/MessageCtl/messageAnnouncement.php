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
            <li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageAnnouncement"><?=__('系统公告')?><?=($this->countMessage['article'] ? sprintf('(%d)', $this->countMessage['article']) : '')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageManage"><?=__('接收设置')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage"><?=__('发送站内信')?></a></li>
        </ul>
    </div>
      <table class="ncm-default-table annoc_con">
          <thead>
            <tr>
             

              <th  class="w200"><?=__('标题')?></th>
              <th class="w200"><?=__('发布时间')?></th>
              <th class="w110"><?=__('操作')?></th>
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
              <td data-id="<?= $value['article_id'] ?>" class="tl w200 btn-bluejeans <?php if($value['article_islook']==0){;?>dark<?php }?>"><?= $value['article_title'] ?></td>
              <td class="w200"><?= $value['article_add_time'] ?></td>
              <td class="ncm-table-handle"><span>
				<span class="edit"><a  class="btn-bluejeans" dialog_width="550" dialog_title="<?=__('查看消息')?>" data-id="<?= $value['article_id'] ?>" href="javascript:void(0)" ><i class="iconfont iconf icon-chakan"></i><?=__('查看')?></a></span>
			  </td>
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
			<div style="clear:both"></div><div class="page page_front"><?=$page_nav?></div><div style="clear:both"></div>
		<?php } ?>
</div>
</div>
</div>
</div>

<script>
  $(".btn-bluejeans").bind("click",function(){
	  var obj = $(this);
	  var id = $(this).attr("data-id");
	  var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=changeAnnouncement&typ=json';
	  $.ajax({
			url: ajax_url,
			data:{id:id},
			success:function(a){
				if(a.status == 200)
				{
					 obj.parents('tr:first').children('.tl').removeClass('dark');
					location.href = SITE_URL+"?ctl=Article_Base&article_id="+id;
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



