<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
	<div class="aright">
        <div class="member_infor_content">
			<div class="div_head tabmenu clearfix">
				<ul class="tab clearfix">
					<li  class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=index&typ=e"><?=__('分销申请')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Goods&met=index"><?=__('商品列表')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerList"><?=__('我的推广')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerOrder"><?=__('我的业绩')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerCommission"><?=__('佣金记录')?></a></li>
				</ul>
			</div>
			
			<ul>
				<li>
					<div class="operation">
						<p class="p_title"><?=__('分销中心说明：')?></p>
						<div style="margin-top:10px; margin-left:10px;">
							<p class="p_content"><?=__('1、成为分销员：在店铺消费并且满足店铺设定的金额，可申请成为分销员。')?></p>
							<p class="p_content"><?=__('2、发展下级：分销员A分享链接给B，B通过分享链接注册成功，B既成为A的下级。B分享链接给C，C通过分享链接注册成功后，C即成为B的下级。')?></p>
							<p class="p_content"><?=__('3、获得佣金：当B推广的下级C成功消费一笔订单时，B可以获得佣金奖励（我们将这笔奖励称之为“一级佣金”），A可以获得推荐奖励（我们将这笔奖励称之为“二级佣金”）。')?></p>
							<p class="p_content"><?=__('4、佣金结算：下级用户下单成功，确认收货7天以后，佣金结算到网站支付账户余额中。')?></p>
						</div>
					</div>
				</li>
			</ul>
 
			<table class="ncm-default-table annoc_con">
				<thead>
					<tr class="bortop">
						<th class="w150"><?=__('店铺名称')?></th>
						<th class="tc opti"><?=__('申请条件')?></th>
						<th class="w150"><?=__('消费金额')?></th>
						<th class="w150"><?=__('状态')?></th>
						<th class="w150"><?=__('创建时间')?></th>
						<th class="w150"><?=__('操作')?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data['items'])){ ?>
					<?php foreach($data['items'] as $key=>$val){?>
					<tr class="bd-line">
						<td><?=($val['shop_name'])?></td>
						<td class="tc opti">
							<?php if($val['expenditure']){ ?>
							<?=__('消费满')?>&nbsp;<?=format_money($val['expenditure'])?>&nbsp;<?=__('可申请')?>
							<?php }else{ ?>
							<?=__('成功消费任意金额')?>
							<?php } ?>
						</td>
						<td><?=format_money($val['expends'])?></td>
						<td>
							<?php 
								if(isset($val['status']))
								{
									if($val['status'] == 0){?>
									<span><?=__('待审核')?></span>
									<?php }elseif($val['status']==1){?>
									<span><?=__('审核通过')?></span>
									<?php }									
								}else
								{
							?>
									<span><?=__('未申请')?></span>
							<?php } ?>
						</td>
						<td>
							<?=@$val['directseller']['directseller_create_time']?>
						</td>
						<td class="ncm-table-handle">
							<?php
								//是否存在
								if(isset($val['status']))
								{
							?>
									<a data-dis="1"  class="to_views cgray"><i class="iconfont icon-duigou1"></i><?=__('已经申请')?></a>
						    <?php 
								}elseif($val['expends']>$val['expenditure'])
								{
							?>
									<!---满足申请条件--->
									<a onclick="apply('<?=$val['shop_id']?>')" class="to_views bbc_btns "><i class="iconfont icon-duigou1"></i><?=__('申请分销')?></a>
							<?php
								}
							?>
						</td>
					</tr>
				<?php }?>
				<?php }else{ ?>
					<tr id="list_norecord">
						<td colspan="20" class="norecord">
						  <div class="no_account">
							<img src="<?= $this->view->img ?>/ico_none.png"/>
							<p><?=__('暂无符合条件的数据记录')?></p>
						</div>  
						</td>
					</tr>
				<?php } ?>	
				</tbody>
			</table>
			
			<?php if($page_nav){ ?>
				<div class="page page_front"><?=$page_nav?></div>
			<?php } ?>
			<div style="clear:both"></div>
		</div>
    </div>
 
	<script>
		function apply(id)
		{
			var e = $(this);
			$.dialog.confirm("<?=__('您确定要申请吗?')?>",function()
			{
				$.post(SITE_URL + '?ctl=Distribution_Buyer_Directseller&met=addDirectseller&typ=json',{shop_id:id},function(d)
				{
					if(d.status == 200) 
					{
                        var data = d.data;                 
                        Public.tips.success("<?=__('申请成功!')?>");
						location.href = SITE_URL + '?ctl=Distribution_Buyer_Directseller&met=index';
                    } else 
					{
                        Public.tips.error("<?=__('申请失败！')?>");
						location.href = SITE_URL + '?ctl=Distribution_Buyer_Directseller&met=index';
                    }
				});
			});
		};
	</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>