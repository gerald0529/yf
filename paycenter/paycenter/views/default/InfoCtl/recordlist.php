<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

	<div class="bg_pcenter">
		<div class="recharge-content-top content-public clearfix">
			<div class="left">
				<?php if($record_delete){?>
					<span><?=_('回收站')?></span>
				<?php }else{ ?>
					<span><?=_('最近交易')?></span>
				<?php }?>
			</div>
			<div class="right">
				<div class="mg clearfix">
					<p class="splitLine-mg"><?=_('可用余额')?>
						<a>  <?=format_money($user_resource['user_money'])?></a></a><?=_('元')?>
						<span class="splitLine">丨</span></p>
						<p>
							<?php if($record_delete){?>
								<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist"><i class="iconfont icon-shangcidenglushijian"></i><?=_('最近交易')?></a>
							<?php }else{ ?>
								<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist&record_delete=1"><i class="iconfont icon-lajitong"></i><?=_('回收站')?></a>
							<?php }?>
						</p>
				</div>
			</div>
		</div>
		<div class="recharge-content-center content-public clearfix">
			<div class="Second clearfix">
				<a class="acb"><?=_('类型：')?></a>
				<div class="trade_class">
					<span class="<?php if(empty($type)){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('全部')?></a></span>
					<span class="<?php if($type==1){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=1<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('购物')?></a></span>
					<span class="<?php if($type==3){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=3<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('充值')?></a></span>
					<span class="<?php if($type==2){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=2<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('转账')?></a></span>
					<span class="<?php if($type==4){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=4<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('提现')?></a></span>
					<span class="<?php if($type==7){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=7<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('扫码付款')?></a></span>
					<span class="<?php if($type==10){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($status){?>&status=<?=$status?><?php }?>&type=10<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('佣金')?></a></span>
				</div>
			</div>
			<div class="Second clearfix">
				<a class="acb"><?=_('状态：')?></a>
				<div class="trade_class">
					<span class="<?php if(empty($status)){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?><?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('全部')?></a></span>
					<span class="<?php if($status== 'doing'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=doing<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('进行中')?></a></span>
					<span class="<?php if($status== 'waitpay'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=waitpay<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('未付款')?></a></span>

					<span class="<?php if($status== 'waitsend'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=waitsend<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('等待发货')?></a></span>

					<span class="<?php if($status== 'waitconfirm'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=waitconfirm<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('未确认收货')?></a></span>

					<span class="<?php if($status== 'retund'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=retund<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('退款')?></a></span>

					<span class="<?php if($status== 'success'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=success<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('成功')?></a></span>

					<span class="<?php if(!empty($status)&&$status== 'cancel'){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&status=cancel<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('取消')?></a ></span>
				</div>
			</div>
			<div class="Second clearfix">
				<a class="acb"><?=_('身份：')?></a>
				<div class="trade_class">
					<span class="<?php if(!empty($utype)&&$utype == 1){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&utype=1<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('收款方')?></a ></span>
					<span class="<?php if(!empty($utype)&&$utype== 2){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($time){?>&time=<?=$time?><?php }?>&utype=2<?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('付款方')?></a ></span>
				</div>
			</div>
			<div class="first clearfix">
				<div class="time">
					<a class="acb"><?=_('时间：')?></a>
					<div class="trade_class">
						 <form action="" method="get"  id="search_form" >
							<input type="hidden" name="ctl" value="Info"/>
							<input type="hidden" name="met" value="recordlist"/>
							<input type="hidden" name="status" value="<?php if($status){?>&status=<?=$status?><?php }?>" />	
							<input type="hidden" name="utype" value="<?php if($utype){?>&utype=<?=$utype?><?php }?>" />
							<input type="hidden" name="type" value="<?php if($type){?>&type=<?=$type?><?php }?>" />
							<input type="hidden" name="time" value="<?php if($time){?>&time=<?=$time?><?php }?>" />	
							<input type="hidden" name="record_delete" value="<?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>" />	
							<p class="order_time" style="float:left;">
								<span></span>
								<input type="text" value="<?=$start_date?>" class="text w70" id="start_date" name="start_date" placeholder="开始时间" autocomplete="off">
								 <label class="add-on">
									<i class="iconfont icon-rili"></i>
								</label>
								<em style="margin-top: 3px;">&nbsp;&ndash; &nbsp;</em>
								<input type="text" value="<?=$end_date?>" class="text w70" id="end_date" name="end_date" autocomplete="off" placeholder="结束时间">
								 <label class="add-on">
									<i class="iconfont icon-rili"></i>
								</label>
							</p>
							<a class="button btn_search_goods sous btn btn_active" href="javascript:void(0);">
							<i class="iconfont icon-btnsearch  icon_size18"></i><?=_('搜索')?></a>
							 <link href="<?= $this->view->css ?>/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
							<script type="text/javascript" src="<?=$this->view->js?>/jquery.datetimepicker.js" charset="utf-8"></script>
							<script type="text/javascript">
								$(".sous").on("click", function ()
								{
									$("#search_form").submit();
								});
								$('#start_date').datetimepicker({
								controlType: 'select',
								format: "Y-m-d",
								timepicker: false
								});

								$('#end_date').datetimepicker({
									controlType: 'select',
									format: "Y-m-d",
									timepicker: false
								});
								jQuery(function(){
									 jQuery('#start_date').datetimepicker({
									  format:'Y-m-d',
									  onShow:function( ct ){
									   this.setOptions({
										maxDate:jQuery('#end_date').val()?jQuery('#end_date').val():false
									   })
									  },
									  timepicker:false
									 });
									 jQuery('#end_date').datetimepicker({
									  format:'Y-m-d',
									  onShow:function( ct ){
									   this.setOptions({
										minDate:jQuery('#start_date').val()?jQuery('#start_date').val():false
									   })
									  },
									  timepicker:false
									 });
								});
							</script>
						</form> 
						<div class="day">
							<?=_('今天')?><a class="splitLine">丨</a> <?=_('最近：')?>
			                <span class="<?php if(empty($time)){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist<?php if($status){?>&status=<?=$status?><?php }?><?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('全部')?></a></span>
			                <span class="<?php if($time==date("Y-m-d",strtotime("-1 month"))){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist&time=<?=date("Y-m-d",strtotime("-1 month"))?><?php if($status){?>&status=<?=$status?><?php }?><?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('1个月')?></a></span>
							<span class="<?php if($time==date("Y-m-d",strtotime("-3 month"))){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist&time=<?=date("Y-m-d",strtotime("-3 month"))?><?php if($status){ ?>&status=<?=$status?><?php }?><?php if($type){ ?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('3个月')?></a></span>
							<span class="<?php if($time==date("Y-m-d",strtotime("-1 year"))){?>btn btn_active<?php }?>"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist&time=<?=date("Y-m-d",strtotime("-1 year"))?><?php if($status){?>&status=<?=$status?><?php }?><?php if($type){?>&type=<?=$type?><?php }?><?php if($record_delete){?>&record_delete=<?=$record_delete?><?php }?>"><?=_('1年')?></a></span>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
	<?php
//		echo '<pre>';
//		print_r($consume_record_list['items']);

	?>
	<div class="recharge-content-bottom content-public">
		<div class="pc_transaction">
			<div class="pc_table_head clearfix">
				<p class="pc_trans_time"><span><?=_('创建时间')?></span></p>
				<p class="pc_trans_other">
					<span class="pc_table_num"><?=_('名称')?>&nbsp;|&nbsp;<?=_('对方')?>&nbsp;|&nbsp;<?=_('交易号')?></span><span class="wp20"><?=_('金额(元)')?></span><span class="wp20"><?=_('状态')?></span><span class="wp20"><?=_('操作')?></span>
				</p>
			</div>
			<?php foreach($consume_record_list['items'] as $conkey => $conval){?>
			<div class="pc_trans_lists clearfix">
				<div class="pc_trans_time pc_trans_det_time"><?=($conval['record_time'])?></div>
				<div class="pc_trans_det pc_trans_other">
					<p class="pc_table_num">
                                                <span><?=($conval['record_title'])?><?php if($conval['trade_type_id']==2){?>&nbsp;|&nbsp;<?=($conval['trade_receiver'])?><?php }?></span>
                                                <?php if($conval['order_id']){?><span class="jyh"><?=_('交易号:')?><?=($conval['order_id'])?></span><?php }?>
                                        </p>
					<p class="wp20"><span>
						<?=(format_money($conval['record_money']))?>
					</span></p>
					<p class="wp20"><span><?=($conval['record_status_con'])?></span></p>
					<p class="wp20">
						<?php if($conval['act'] == 'pay'){ ?>
							<?php if($conval['trade_type_id'] == Trade_TypeModel::SHOPPING){?>
								<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=pay&uorder=<?=$conval['uorder']?>" class="cb"><?=_('支付')?></a><a></a>
							<?php }?>
							<?php if($conval['trade_type_id'] == Trade_TypeModel::DEPOSIT){?>
								<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=pay&act=deposit&uorder=<?=$conval['order_id']?>" class="cb"><?=_('支付')?></a><a></a>
							<?php }?>
						<?php }else{ ?>
							<a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recorddetail&id=<?=$conval['consume_record_id']?>" class="cb"><?=_('详情')?></a>

						<?php }?>
						&nbsp;|&nbsp;<a href="javascript:void(0)" data-param="{'ctl':'Info','met':'delRecordlist','id':'<?=$conval['consume_record_id']?>','record_delete':<?=$conval['record_delete']?>}" class="delete cb" title="<?php if(empty($record_delete)){?><?=_('删除')?><?php }else{ ?><?=_('还原')?><?php }?>"><?php if(empty($record_delete)){?><?=_('删除')?><?php }else{ ?><?=_('还原')?><?php }?></a></p>
				</div>
			</div>
			<?php }?>
			<!--翻页-->
        <div style="clear:both"></div>
        <div style="text-align:center;"><div class="page clearfix"><?=$page_nav?></div></div>
        <div style="clear:both"></div>
		</div>

	</div>
<script>
$(".delete").click(function(){
	var e = $(this);
	eval('data_str =' + $(this).attr('data-param'));
	$.dialog.confirm("<?php if(empty($record_delete)){?><?=_('确认删除')?><?php }else{ ?><?=_('确认还原')?><?php }?>",function(){ 
	$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id,record_delete:data_str.record_delete},function(data){

		if(data && 200 == data.status){
		
			Public.tips.success("<?php if(empty($record_delete)){?><?=_('删除成功')?><?php }else{ ?><?=_('还原成功')?><?php }?>");
			location.href= SITE_URL +"?ctl=Info&met=recordlist";

		}else
		{
			Public.tips.error("<?php if(empty($record_delete)){?><?=_('删除失败')?><?php }else{ ?><?=_('还原失败')?><?php }?>");
		}
	});
	});
});
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>