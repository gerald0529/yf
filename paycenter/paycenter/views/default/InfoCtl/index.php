<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<div class="pc_user_about wrap">
	<h4><?=__('财富概况')?></h4>
	<div class="pc_user_mes clearfix">
		<div class="pc_user_mes_lf fl clearfix">
			<p class="pc_user_mes_lf_img fl"><img src="<?=__($user_info['user_avatar'])?>"></p>
			<div class="pc_user_mes_lf_text fr">
				<dl class="clearfix">
					<dt><i class="iconfont icon-yonghuming"></i><?=__('用户名称')?></dt>
					<dd><?=$user_info['user_nickname']?></dd>
				</dl>
				<?php if(!empty($user_info['user_mobile'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shoujihao"></i><?=__('手机号码')?></dt>
					<dd><?=$user_info['user_mobile']?></dd>
				</dl>
				<?php }?>
				<?php if(!empty($user_info['user_email'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-youxiang"></i><?=__('绑定邮箱')?></dt>
					<dd><?=$user_info['user_email']?></dd>
				</dl>
				<?php }?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shangcidenglushijian"></i><?=__('上次登录时间')?></dt>
					<dd><?=$user_base['user_login_time']?></dd>
				</dl>
			</div>
		</div>
		<div class="pc_user_mes_rt fr clearfix">
            <!--用户账户总资产 已取消：【$user_resource['user_rechange_card_frozen']】-->
			<div class="pc_user_mes_rt_percent fl">
				<img src="<?= $this->view->img ?>/percent.png">
				<p class="pc_account"><?=__('账户总财产：')?>
                    <span>
                        <?=(format_money($user_resource['user_money'] + $user_resource['user_money_frozen'] + $user_resource['user_rechange_card']))?>
                    </span>
                </p>
			</div>
			<div class="pc_user_mes_rt_text fl">
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgb"></span><?=__('账户余额：')?></dt>
					<dd><?=(format_money($user_resource['user_money']))?></dd>
				</dl>
				<dl class="clearfix dl-public">
					<dt class="dt_pad"><span class="pc_col_reprens bgy"></span><?=__('卡余额')?><i>：</i></dt>
					<dd><?=(format_money($user_resource['user_recharge_card']))?></dd>
				</dl>
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgr"></span><?=__('冻结资金：')?></dt>
					<dd><?=(format_money($user_resource['user_money_frozen']))?></dd>
				</dl>
				<dl class="clearfix pc_a_btn dl-public">
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url')?>?ctl=Info&met=deposit" class="pc_btn"><?=__('充值')?></a></dd>
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url').'?ctl=Info&met=withdraw&typ=e'?>" class="pc_btn btn_active"><?=__('提现')?></a></dd>
					<dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=transfer&typ=e" class="pc_btn"><?=__('转账')?></a></dd>
				</dl>
			</div>
		</div>
	</div>
</div>

<div class="pc_transaction wrap">
	<h4><?=__('最近交易')?><!--<span class="trade_types"><a target="_blank" href="" ><?/*=_('充值记录')*/?></a>&nbsp;|&nbsp;--><a target="_blank" href="" ><?=__('提现记录')?></a>&nbsp;|&nbsp;<a target="_blank" href="" ><?=__('退款记录')?></a></span></h4>
	
	<div class="pc_table_head clearfix">
		<p class="pc_trans_time"><span><?=__('创建时间')?></span></p>
		<!--显示为“订单编号”-->
        <p class="pc_trans_other">
			<!--<span class="pc_table_num"><?/*=_('名称')*/?> |&nbsp;<?/*=_('对方')*/?>&nbsp;|&nbsp;<?/*=_('交易号')*/?></span>-->
            <span class="wp20"><?=__('订单编号')?></span>
            <span class="wp20"><?=__('金额')?></span>
            <span class="wp20"><?=__('状态')?></span>
            <span class="wp20"><?=__('操作')?></span>
		</p>

	</div>
	<?php foreach($consume_record_list['items'] as $conkey => $conval){?>
	<div class="pc_trans_lists clearfix">
		<div class="pc_trans_time pc_trans_det_time"><?=($conval['record_time'])?></div>
		<div class="pc_trans_det pc_trans_other">
			<p class="pc_table_num"><span><?=($conval['record_title'])?></span><?php if($conval['order_id']){?><span class="jyh"><?=__('交易号:')?><?=($conval['order_id'])?></span><?php }?></p>
			<p class="wp20">
				<span class="textcolor">
						<?=money_format($conval['record_money'])?>
				</span>
			</p>
			<p class="wp20"><span><?=($conval['record_status_con'])?></span></p>
			<p class="wp20"><a href="" class="cb"><?=__('详情')?></a></p>
		</div>
	</div>
	<?php }?>
	<div class="pc_trans_btn"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist" class="btn_big btn_active"><?=__('查看更多账单')?></a></div>
</div>
<script type="text/javascript">
//提现
function get_user_identity(){
    var ajax_url = '<?=Yf_Registry::get('url').'/index.php?ctl=Info&met=getUserInfo&typ=json'?>';
    var user_id = '<?=$user_info['user_id']?>';
    $.ajax({
        url: ajax_url,
        success:function(result){
            if(result.status == 200)
            {
                if(result.data[user_id].user_identity_statu == 2){
                    window.location.href = '<?=Yf_Registry::get('url').'/index.php?ctl=Info&met=withdraw&typ=e'?>';
                    return ;
                }else if(result.data[user_id].user_identity_statu == 0){
                    var notice = '<?=__('您还未实名认证')?>';
                }else{
                    var notice = '<?=__('您还未实名认证成功')?>';
                }
            } else {
                var notice = '<?=__('网络错误')?>';
            }
            $.dialog({
                title: '<?=__('提示')?>',
                content: notice,
                height: 100,
                width: 410,
                lock: true,
                drag: false,
                ok: function () {
                    //window.location.href = '<?//=Yf_Registry::get('url').'/index.php?ctl=Info&met=account&typ=e'?>//';
                    window.location.href = UCENTER_URL + '?ctl=User&met=security';
                }
            })
        }
    });
}
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>