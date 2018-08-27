<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
</div>
<div class="ncm-security-user">
          <h3><?=__('您的账户信息')?></h3>
          <div class="user-avatar"><span><img src="<?php if(!empty($data['user_logo'])){ echo $data['user_logo'];}else{echo $this->web['user_logo']; } ?>"></span></div>
          <div class="user-intro">
            <dl>
              <dt><?=__('登录账号：')?></dt>
              <dd><?=$data['user_name']?></dd>
            </dl>
            <dl>
              <dt><?=__('绑定邮箱：')?></dt>
              <dd><?=$data['user_email']?></dd>
            </dl>
            <dl>
              <dt><?=__('手机号码：')?></dt>
              <dd><?=$data['user_mobile']?></dd>
            </dl>
            <dl>
              <dt><?=__('上次登录：')?></dt>
              <dd><?=$data['lastlogintime']?>　<?php if($data['user_ip']){?>|　<?=__('IP地址:')?><?=$data['user_ip']?>&nbsp;<?php }?></dd>
            </dl>
          </div>
        </div>
<div class="ncm-security-container">
          <div class="title"><?=__('您的安全服务')?></div>
              <div class="current low"><?=__('当前安全等级：')?><strong><?php if($data['user_level_id']==1){?><?=__('低')?><?php }elseif($data['user_level_id']==2){?><?=__('中')?><?php }else{?><?=__('高')?><?php }?></strong><span><?=__('(建议您开启全部安全设置，以保障账户及资金安全)')?></span></div>
 
          <dl id="email" class="<?php if($data['user_email_verify']){ ?> yes<?php }else{?>no<?php }?>">
            <dt><span class="iconfont icon-youxiangbangding" style="top:4px;"><?php if($data['user_email_verify']){ ?><i></i><?php }?></span><span class="itemss">
              <h4><?=__('邮箱绑定')?></h4>
              <h6><?php if($data['user_email_verify']){ ?><?=__('已绑定')?><?php }else{?><?=__('未绑定')?><?php }?></h6>
              </span></dt>
            <dd><span class="explain"><?=__('进行邮箱验证后，可用于接收敏感操作的身份验证信息，以及订阅更优惠商品的促销邮件。')?></span><span class="handle"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=security&op=email" class="ncbtn ncbtn-aqua bd  bbc_btns"><?=__('绑定邮箱')?></a><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=security&op=emails" class="ncbtn ncbtn-bittersweet jc  bbc_btns"><?=__('修改邮箱')?></a></span></dd>
          </dl>
          <dl id="mobile" class="<?php if($data['user_mobile_verify']){ ?> yes<?php }else{?>no<?php }?>">
            <dt><span class="iconfont icon-shoujibangding"><?php if($data['user_mobile_verify']){ ?><i></i><?php }?></span><span class="itemss">
              <h4><?=__('手机绑定')?></h4>
              <h6><?php if($data['user_mobile_verify']=='1'){ ?><?=__('已绑定')?><?php }else{?><?=__('未绑定')?><?php }?></h6>
              </span></dt>
            <dd><span class="explain"><?=__('进行手机验证后，可用于接收敏感操作的身份验证信息，以及进行积分消费的验证确认，非常有助于保护您的账号和账户财产安全。')?></span><span class="handle"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=security&op=mobile" class="ncbtn ncbtn-aqua bd  bbc_btns"><?=__('绑定手机')?></a><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=security&op=mobiles" class="ncbtn ncbtn-bittersweet jc  bbc_btns"><?=__('修改手机')?></a></span></dd>
          </dl>
          
        </div>
      </div>
    </div>
     
</div>

</div>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>