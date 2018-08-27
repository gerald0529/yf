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
			<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=detail"><?=__('查看站内信')?></a></li>
        </ul>
    </div>
   <div class="form-style">
    <form id="form" name="form" action="" method="post">
		<input type="hidden" class="text" maxlength="30" name="user_message_id" id="user_message_id" value="<?=$data['user_message_id']?>">
		<?php foreach($data['list'] as $key=>$val){?> 
        <dl>
            <dt><?=$val['user_message_send']?><?=__('说：')?></dt>
            <dd><?=$val['user_message_content']?></dd>
        </dl>
		<?php }?>
		<dl>
            <dt><?=$data['user_message_send']?><?=__('说：')?></dt>
            <dd><?=$data['user_message_content']?></dd>
        </dl>
        <dl>
            <dt><?=__('回复：')?></dt>
            <dd><textarea class="textarea_text w300" rows="5" cols="40"  name="user_message_content"></textarea></dd>
        </dl>      
        <dl class="foot">
            <dt>&nbsp;</dt>
            <dd><input type="submit" value="<?=__('提交')?>" class="submit bbc_btns"></dd>
        </dl>
    </form>
 </div>
</div>
</div>
</div>
</div>

<script>
 $(document).ready(function(){
          
        var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=addDetail&typ=json';
       
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'user_message_content': 'required;',
            },
            valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功！')?>");
                            location.href= SITE_URL+"?ctl=Buyer_Message&met=message&op=send";
                        }else if(a.status == 230)
						{
                            Public.tips.error("<?=__('你发送的内容涉及到敏感词！')?>");
                        }else if(a.status == 240)
						{
                            Public.tips.error("<?=__('请求用户有问题！')?>");
							
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });

    }); 
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



