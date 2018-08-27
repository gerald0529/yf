<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
    <div class="pc_user_about">
        <div class="recharge-content-top content-public clearfix">
            <ul class="tab">
                <li class="active"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=btinfo">白条概览</a></li>
                <li><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=btbill">白条账单</a></li>
            </ul>
        </div>
        <div class="wrap">
            <?php  if($user_info['user_bt_status'] == 1 || ($user_info['user_bt_status'] == 2 && $user_info['user_credit_limit'] <= 0)){ ?>
                <div class="speed">
                    <div class="modular modular1">
                        <h3>激活白条</h3>
                        <div class="step">
                            <dl class="step-first current">
                                <dt>【个人实名认证已通过】</dt>
                            </dl>
                            <dl  class="current">
                                <dd></dd>
                                <?php if($user_info['user_bt_status'] == 1){?>
                                <dt>【白条审核中】</dt>
                                <?php }elseif($user_info['user_bt_status'] == 2 && $user_info['user_credit_limit'] <= 0){?>
                                <dt>【额度设置中】</dt>
                                <?php }?>
                            </dl>
                            <dl>
                                <dd></dd>
                                <dt>【已激活】</dt>
                            </dl>
                        </div>
                    </div>

                    <div class="modular modular2 ">
                        <h3>支付密码设置</h3>
                        <?php  if(!$user_info['user_pay_passwd']){ ?>
                        <div>

                            <p class="columns"><span class="modify"><i class="iconfont icon-set-password"></i><a href="<?=Yf_Registry::get('base_url')?>/index.php?ctl=Info&met=passwd&from=bt"  target="_black">设置密码</a></span></p>

                        </div>
                        <?php }else{ ?>
                            <div>
                                <p class="columns"><span class="success mar60 no-active"><i class="iconfont icon-modify-password"></i><a href="<?=Yf_Registry::get('base_url')?>/index.php?ctl=Info&met=passwd&from=bt"  target="_black">修改密码</a></span></p>
                            </div>
                        <?php } ?>
                        <p class="remark">该支付密码可同时用于预存款、白条支付</p>
                    </div>
                </div>

            
            <?php }else{ ?> 
            <ul class="tab-content">
                <li>
                    <?php  if($user_info['user_bt_status'] == 2){ ?>
                    
                    <!-- 已激活 -->
                    <div class="active">
                        <dl>
                            <dt>白条可用额度</dt>
                            <dd><?=format_money($user_info['user_credit_availability'])?></dd>
                        </dl>
                        <dl>
                            <dt><span class="red"></span>待还款金额</dt>
                            <dd><span class="red"><?=format_money($user_info['user_credit_cost'])?></span></dd>
                        </dl>
                        <div><a href="javascript:alert('请尽快联系平台，进行还款！');" class="btn_common btn_active mar40">立即还款</a></div>
                    </div>
                    <?php  }else{ ?>
                    <!-- 未激活 -->
                    <div class="not-account">
                        <p class="no_account"><img src="<?=Yf_Registry::get('static_url')?>/images/ico_none.png" alt=""></p>
                        <p class="red" style="text-align: center">该白条功能主要针对企业之间大宗交易签署线下采购合同的情况</p>
                        <p class="pc_trans_btn"><a href="javascript:;" class="btn_common btn_active btn-activate"  onclick="btActivation();">激活白条</a></p>
                    </div>
                   <?php }?> 
                </li>
                <li>
                    <div></div>
                </li>
            </ul>
        <?php } ?> 
        </div>
    </div>
<script type="text/javascript">;
    function btActivation(){
        //判断用户实名验证是否已审核成功
        if('<?=$user_info['user_identity_statu']?>' == '<?=User_InfoModel::BT_VERIFY_PASS?>')
        {
            var url = "<?= Yf_Registry::get('base_url').'/index.php?ctl=Info&met=btactivation&typ=json'?>";
            $.post(url,'',function(resp){
                if(resp.status == 200){
                    window.location.href = resp.data.url;
                }
            });
        }
        //如果实名认证正在审核中
        else if('<?=$user_info['user_identity_statu']?>' == '<?=User_InfoModel::BT_VERIFY_WAIT?>')
        {
            Public.tips.alert('实名认证正在审核中，请通过后再来激活白条!');
        }
        //没有提交过实名认证，实名认证没有通过
        else
        {
            var url = "<?= Yf_Registry::get('base_url').'/index.php?ctl=Info&met=account&typ=e'?>";
            window.location.href = url;
        }


    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>