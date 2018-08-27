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
			<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage"><?=__('发送站内信')?></a></li>
        </ul>
    </div>
    <div class="ncm-message-send">
	  <div class="ncm-message-send-form">
		<div class="ncm-default-form">
    <form id="form" name="form" action="" method="post">
		<dl>
           <dt><i class="required">*</i><?=__('收件人：')?></dt>
             <dd>
               <input type="text" class="text w500" name="user_message_receive" value="<?php if($user){?><?=$user['user_name']?>,<?php }else{?><?php }?>">
               <p class="hint"><?=__('多个收件人请用逗号分隔')?></p>
             </dd>
        </dl>
        <dl>
            <dt><i class="required">*</i><?=__('内容：')?></dt>
            <dd><textarea maxlength="200" class="textarea_text w300" rows="5" cols="40"  name="user_message_content"></textarea></dd>
            <dd><span id="font_num" style="margin-left: 350px;margin-top: -10px">能输入200个字</span></dd>
        </dl>
        <dl class="foot">
            <dt>&nbsp;</dt>
            <dd><input type="submit" value="<?=__('确认发送')?>" class="submit bbc_btns"></dd>
        </dl>
    </form>
 </div>
</div>
 <div class="ncm-message-send-friend">
    <h3><?=__('我的好友列表')?></h3>
	<ul>
       <?php if($data['items']){?>
	   <?php foreach($data['items'] as $key=>$val){?>
        <li><a href="javascript:void(0);" id="<?=$val['friend_name']?>" nc_type="to_member_name">
          <div class="avatar"><img src="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?=$val['friend_id']?>"></div>
          <p><?=$val['friend_name']?></p>
        </a></li>
	   <?php }?>
	   <?php }?>
      </ul>

  </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<script>
    $("textarea").keydown(function(){
        var value = $("textarea[name='user_message_content']").val();
        var font_num = 200 - (value).length;
        if (font_num >= 0) {
            $("span[id='font_num']").html('<span id="font_num">还能输入'+font_num+'个字</span>');
        }else{
            $("span[id='font_num']").html('<span id="font_num">你已经超出输入的字数了</span>');
        }
    })
</script>
<script>
$(function(){
    $('a[nc_type="to_member_name"]').click(function (){
        var str = $('input[name="user_message_receive"]').val();
        var parentli=$(this).parent();
        var id = $(this).attr('id');
        if(str.indexOf(id+',') < 0){
            doFriend(id+',', 'add');
            parentli.addClass("border");
        }else{
            doFriend(id, 'delete');
            parentli.removeClass("border");
        }
    });
});
function doFriend(user_name, action){
    var input_name = $("input[name='user_message_receive']").val();
    var key, i = 0;
    var exist = false;
    var arrOld = new Array();
    var arrNew = new Array();
    input_name = input_name.replace(/\uff0c/g,',');
    arrOld     = input_name.split(',');
    for(key in arrOld){
        arrOld[key] = $.trim(arrOld[key]);
        if(arrOld[key].length > 0){
            arrOld[key] == user_name &&  action == 'delete' ? null : arrNew[i++] = arrOld[key]; //剔除好友
            arrOld[key] == user_name ? exist = true : null; //判断好友是否已选
        }
    }
    if(action == 'delete' && arrNew !=''){
    	arrNew = arrNew+',';
    }
    if(!exist && action == 'add'){
        arrNew[i] = user_name;
    }
    $("input[name='user_message_receive']").val(arrNew);
}
 $(document).ready(function(){

        var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=addMessageDetail&typ=json';

        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'user_message_receive': 'required;',
                'user_message_content': 'required;',
            },
            valid:function(form){
                //表单验证通过，提交表单
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功！')?>");
                            location.href=SITE_URL+"?ctl=Buyer_Message&met=message&op=send";
                        }
                        else if(a.status == 230)
						{
                            Public.tips.error("<?=__('你发送的内容涉及到敏感词！')?>");
                        }else if(a.status == 240)
						{
                            Public.tips.error("<?=__('请求用户有问题！')?>");

                        }else if(a.status == 260)
						{
                            Public.tips.error("<?=__('收件人不能为空！')?>");
                        }else if(a.status == 220)
                        {
                            Public.tips.error("<?=__('收件人不是商城用户！')?>");
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
                        }
						    // 提交表单成功后，释放hold，就可以再次提交
							me.holdSubmit(false);
                    }
                });
            }

        });

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



