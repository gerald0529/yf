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
			<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=messageManage"><?=__('接收设置')?></a></li>
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=sendMessage"><?=__('发送站内信')?></a></li>
        </ul>
    </div>
   <div class="ncm-message-setting">
          <form id="form" name="form"  method="post" action=""  >
              <input value="edit" name="submit" type="hidden">
            <dl>
              <dt><span><i class="iconfont icon-tabcart"></i></span><?=__('订单交易通知')?></dt>
              <dd>
                <ul>
				<?php foreach($data['items'] as $key=>$val){ ?>
				<?php if($val['mold'] == '1'){ ?>
                  <li>
                    <input type="checkbox" name="id[<?=$val['id']?>]" id="<?=$val['id']?>" value="<?=$val['id']?>" <?php if( in_array($val['id'],$all)){?> checked <?php } ?>>
                    <label for="<?=$val['id']?>"><?=$val['name']?></label></li>
				<?php }?>
				<?php }?>
                </ul>
              </dd>
            </dl>
            <dl>
              <dt><span><i class="iconfont icon-daijinquan"></i></span><?=__('余额卡券提醒')?></dt>
              <dd>
                <ul>
				<?php foreach($data['items'] as $key=>$val){ ?>
				<?php if($val['mold'] == '2'){ ?>

                  <li>
                    <input type="checkbox" name="id[<?=$val['id']?>]" id="<?=$val['id']?>" value="<?=$val['id']?>" <?php if( in_array($val['id'],$all)){?> checked <?php } ?>>
                    <label for="<?=$val['id']?>"><?=$val['name']?></label></li>
				<?php }?>
				<?php }?>
                </ul>
              </dd>
            </dl>
            <dl>
              <dt><span><i class="iconfont icon-cha"></i></span><?=__('售后服务消息')?></dt>
              <dd>
                <ul>
				<?php foreach($data['items'] as $key=>$val){ ?>
				<?php if($val['mold'] == '3'){ ?>
                  <li>
                    <input type="checkbox" name="id[<?=$val['id']?>]" id="<?=$val['id']?>" value="<?=$val['id']?>" <?php if( in_array($val['id'],$all)){?> checked <?php } ?>>
                    <label for="<?=$val['id']?>"><?=$val['name']?></label></li>
				<?php }?>
				<?php }?>
                </ul>
              </dd>
            </dl>
            <div class="bottom tc">
              <label class="submit-border">
                <input id="btn_inform_submit" type="submit" class="submit bbc_btns" value="<?=__('保存更改')?>">
              </label>
            </div>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
<script>
 $(document).ready(function(){
          
        var ajax_url = SITE_URL+'?ctl=Buyer_Message&met=editManage&typ=json';
       
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                
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
//                            setTimeout(function() {
//                                location.href=SITE_URL+"?ctl=Buyer_Message&met=message&op=messageManage";
//                            }, 3000)
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



