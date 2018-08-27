<?php if (!defined('ROOT_PATH')){exit('No Permission');}
    
    include $this->view->getTplPath() . '/' . 'header.php';
?>
    </div>
    <div class="ncm-security-user">
        <h3><?=_('您的账户信息')?></h3>
        <div class="ml30">
            <div class="user-avatar"><span><img src="<?php if(!empty($data['user_avatar'])){ echo $data['user_avatar'];}else{echo $this->web['user_avatar']; } ?>"></span></div>
            <div class="user-intro">
                <dl>
                    <dt><?=_('登录账号：')?></dt>
                    <dd>
                        <?=$data['user_name']?>
                    </dd>
                </dl>
                <dl>
                    <dt><?=_('绑定邮箱：')?></dt>
                    <dd>
                        <?=$data['user_email']?>
                    </dd>
                </dl>
                <dl>
                    <dt><?=_('手机号码：')?></dt>
                    <dd>
                        <?=$data['user_mobile']?>
                    </dd>
                </dl>
                <dl>
                    <dt><?=_('上次登录：')?></dt>
                    <dd>
                        <?=date(Web_ConfigModel::value('date_format') . ' ' . Web_ConfigModel::value('time_format'), $data['user_lastlogin_time'])?>
                        <?php if($data['user_lastlogin_ip']){?>|
                            <?=_('IP地址:')?>
                            <?=$data['user_lastlogin_ip']?>&nbsp;
                        <?php }?>
                    </dd>
                </dl>
            
            
            </div>
        </div>
    </div>
    <div class="ncm-security-container">
        <div class="title">
            <?=_('您的安全服务')?>
        </div>
        <?php
            $low_class = '';
            $low_style = "";
            if($data['user_level_id']<1)
            {
                $low_class = 'lower';
                $low_style = "style='width:0%'";
            }elseif($data['user_level_id']==1){
                $low_class = 'medium';
                $low_style = "style='width:50%'";
            }else{
                $low_class = 'high';
                $low_style = "style='width:100%'";
            }?>
        <div class="current low clearfix <?=$low_class?>"><!--对此div添加不同等级的class:低（lower）,中（medium）,高（high） -->
            <div class="fl progress">
                <span <?=$low_style?>></span><!-- 对此span设置宽度百分比 -->
            </div>
            <div class="fl progress-text"><?=_('安全等级：')?><strong><?php if($data['user_level_id']<1){?><?=_('低')?><?php }elseif($data['user_level_id']==1){?><?=_('中')?><?php }else{?><?=_('高')?><?php }?></strong></div>
            <div class="divleft">
                
                <div class="divright">
                    <span><?=_('(建议您开启全部安全设置，以保障账户及资金安全)')?></span>
                </div>
            
            </div>
        
        </div>
        
        <?php if(isset($user_info['user_identity_statu'])){?>
            <dl>
                <dt>
                    <span class="iconfont icon-icon_geren" style="top:4px;"></span><span class="itemss">
		              	<h4><?=_('实名认证')?></h4>
                        <?php if($user_info['user_identity_statu'] == 2){ $str=_('查看实名信息'); ?><h6 class="active"><?=_('已认证')?></h6><?php }else{  $str=_('去实名认证');?><h6><?=_('未认证')?></h6><?php }?></h6>
		              </span>
                </dt>
                <dd>
						<span class="handle">
						<?php if($user_info['user_identity_statu'] == 2){ ?>
                            <?php if($duff_time <= 30){?>
                                <a class="red" href="<?=Yf_Registry::get('paycenter_api_url')?>?ctl=Info&met=account&typ=e"><?=_('证件快到期，前去修改')?></a>
                            <?php }?>
                        <?php }?>
                            <?php if($user_info['user_identity_statu'] !== 2){?>
                                <a class="red" href="<?=Yf_Registry::get('paycenter_api_url')?>?ctl=Info&met=account&typ=e"><?=$str?></a>
                            <?php }?>

						</span>
                </dd>
            </dl>
        <?php }?>
        
        
        
        
        
        <?php $emailh6_class=''; if($data['user_email_verify']){ $emailh6_class="class='active'"; }?>
        
        <dl id="email" class="<?php if($data['user_email_verify']){ ?> yes<?php }else{?>no<?php }?>">
            <dt><span class="iconfont icon-email" style="top:4px;"><?php if($data['user_email_verify']){ ?><?php }?></span><span class="itemss">
              <h4><?=_('邮箱绑定')?></h4>
              <h6 <?=$emailh6_class?>><?php if($data['user_email_verify']){ ?><?=_('已绑定')?><?php }else{?><?=_('未绑定')?><?php }?></h6><!-- 已绑定给h6添加class为active -->
              </span></dt>
            <dd>
                <span class="explain"><?=_('进行邮箱验证后，可用于接收敏感操作的身份验证信息，以及订阅更优惠商品的促销邮件。')?></span>
                <span class="handle">
				<?php if($data['user_email_verify']){?>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=email" class="red ml30"><?=_('修改邮箱')?></a>
                <?php }else{?>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=email&is_bind=1" class="ncbtn ncbtn-aqua bd  bbc_btns"><?=_('绑定邮箱')?></a>
                <?php }?>
				</span>
            </dd>
        </dl>
        
        <?php $mobile_h6_class=''; if($data['user_mobile_verify'] == '1'){ $mobile_h6_class="class='active'"; }?>
        <dl id="mobile" class="<?php if($data['user_mobile_verify']){ ?> yes<?php }else{?>no<?php }?>">
            <dt><span class="iconfont icon-phone" style="top:4px;"><?php if($data['user_mobile_verify']){ ?><?php }?></span><span class="itemss">
              <h4><?=_('手机绑定')?></h4>
              <h6 <?=$mobile_h6_class?>><?php if($data['user_mobile_verify']=='1'){ ?><?=_('已绑定')?><?php }else{?><?=_('未绑定')?><?php }?></h6>
              </span></dt>
            <dd><span class="explain"><?=_('进行手机验证后，可用于接收敏感操作的身份验证信息，以及进行积分消费的验证确认，非常有助于保护您的账号和账户财产安全。')?></span><span class="handle">
			<?php if($data['user_mobile_verify']=='1'){ ?>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=mobile" class="red ml30"><?=_('修改手机')?></a></span></dd>
            <?php }else{?>
                <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=mobile&is_bind=1" class="ncbtn ncbtn-aqua bd  bbc_btns"><?=_('绑定手机')?></a>
            <?php }?>
        </dl>
    
    </div>
    </div>
    </div>
    
    </div>
    
    </div>
    <div class="dialog-alert mask">
        <div class="dis-table">
            <div class="table-cell">
                <div class="bind-tips">
                    <h3>提示<i class="iconfont icon-cuowu fr" id="btn-close"></i></h3>
                    <div class="tips-text">
                        <div class="dis-table">
                            <div class="table-cell">
                                <p><i class="icon-unbind"></i><!-- 绑定成功的话，i标签的class改为icon-success-->
                                    <?php if($identify_status != 2){ ?>
                                        <span class="text"><?=_('您还未实名认证，为了保证账户安全，请进行修改！')?></span>
                                    <?php }else if($duff_time<=0){ ?>
                                        <span class="text"><?=_('您的证件已到期，为了保证账户安全，请进行修改！')?></span>
                                    <?php } ?>
                                </p>
                                <div><a href="<?=Yf_Registry::get('paycenter_api_url')?>?ctl=Info&met=account&typ=e" class="btn btn-sure lh36"><?=_('立即修改')?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
        if('<?=$identify_status?>' != 2)
        {
            $('.mask').show();
        }
        else
        {
            if('<?=$duff_time?>' <= 0)
            {
                $('.mask').show();
            }
        }
        
        
        
        $(function(){
            $("#btn-close").click(function(){
                $('.mask').hide();
            })
        })
    </script>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>