<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<script src="<?= $this->view->js ?>/seller_order.js" charset="utf-8"></script>
</head>
<body>
<form>
	
	<table class="search-form">
		<tbody>
		<tr>
			<td>&nbsp;</td>
			<?php if($_GET['met'] == 'physical'){?>
				<td><input type="checkbox" id="skip_off" value="1" <?php if (!empty($condition['order_status:<>'])) {
						echo 'checked';
					} ?> name="skip_off"> <label class="relative_left" for="skip_off"><?=__('不显示已关闭的订单')?></label>
				</td>
			<?php }?>
			<th><?=__('下单时间')?></th>
			<td class="w240">
				<input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('起始时间')?>" name="query_start_date" id="query_start_date" value="<?php if (!empty($condition['order_create_time:>='])) {
					echo $condition['order_create_time:>='];
				} ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label><span class="rili_ge">–</span>
				<input id="query_end_date" class="text w70 hasDatepicker heigh" placeholder="<?=__('结束时间')?>" type="text" name="query_end_date" value="<?php if (!empty($condition['order_create_time:<='])) {
					echo $condition['order_create_time:<='];
				} ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label>
			</td>
			<th><?=__('买家')?></th>
			<td class="w100">
				<input type="text" class="text w80" placeholder="<?=__('买家昵称')?>" id="buyer_name" name="buyer_name" value="<?php if (!empty($condition['buyer_user_name:LIKE'])) {
					echo str_replace('%', '', $condition['buyer_user_name:LIKE']);
				} ?>"></td>
			<th><?=__('订单编号')?></th>
			<td class="w160">
				<input type="text" class="text w150 heigh" placeholder="<?=__('请输入订单编号')?>" id="order_sn" name="order_sn" value="<?php if (!empty($condition['order_id'])) {
					echo $condition['order_id'];
				} ?>"></td>
			<td class="w70 tc"><a onclick="formSub()" class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
			<input name="ctl" value="Seller_Trade_Order" type="hidden" /><input name="met" value="<?=$_GET['met']?>" type="hidden" />
			</td>
			<td class="mar"><a class="button refresh" onclick="location.reload()"><i class="iconfont icon-huanyipi"></i></a><td>
		</tr>
		</tbody>
	</table>
</form>

<table class="ncsc-default-table order ncsc-default-table2">
	<thead>
	<tr>
		<th class="w10"></th>
		<th colspan="2"><?=__('商品')?></th>
		<th class="w100"><?=__('单价')?><!--（<?/*=Web_ConfigModel::value('monetary_unit')*/?>）--></th>
		<th class="w40"><?=__('数量')?></th>
		<th class="w100"><?=__('买家')?></th>
		<th class="w100"><?=__('订单金额')?></th>
		<th class="w90"><?=__('交易状态')?></th>
		<th class="w120"><?=__('操作')?></th>
	</tr>
	</thead>

	<?php if ( !empty($data['items']) ) { ?>
	<?php foreach ( $data['items'] as $key => $val ) { ?>
	<tbody>
	<tr>
		<td colspan="20" class="sep-row"></td>
	</tr>
	<tr>
		<th colspan="20"><span class="ml10"><?=__('订单编号')?>：<em><?= $val['order_id']; ?></em>
                </span> <span><?=__('下单时间')?>：<em class="goods-time"><?= $val['order_create_time']; ?></em></span>
			<span class="fr mr5"> <a href="<?= $val['delivery_url']; ?>" class="ncbtn-mini bbc_seller_btns" target="_blank" title="<?=__('打印发货单')?>"><i class="icon-print"></i><?=__('打印发货单')?></a></span>
		</th>
	</tr>

	<!-- S商品列表 -->
	<?php 
			if( !empty($val['goods_list']) ) { 
			$dist_num = 0;	//进货单统计
	 		foreach( $val['goods_list'] as $k => $v ) { 
 	?>
	<tr>
		<td class="bdl"></td>
		<td class="w70">
			<div class="ncsc-goods-thumb">
				<a href="<?= $v['goods_link']; ?>" target="_blank"><img src="<?= $v['goods_image']; ?>"></a>
			</div>
		</td>
		<td class="tl">
			<dl class="goods-name">
				<dt>
					<?php if($v['order_goods_source_id']){ ?>
						<span class="dis_flag"><?=__('分销')?></span>
					<?php } ?>
					<a target="_blank" href="<?= $v['goods_link']; ?>"><?= $v['goods_name']; ?></a>
					<a target="_blank" class="blue ml5" href="<?= $v['goods_link']; ?>">
                    <?php if($val['is_pintuan'] == 1){ ?>
						<span class="dis_flag"><?=__('拼团')?></span>
					<?php } ?>
                    <?php if($val['is_pintuan'] == 2){ ?>
						<span class="dis_flag"><?=__('单独购买')?></span>
					<?php } ?>
                    <?=__('[交易快照]')?></a>
					<?=$v['return_txt']?>
				</dt>
				<dd><strong>￥<?= $v['goods_price']; ?></strong>&nbsp;x&nbsp;<em><?= $v['order_goods_num']; ?></em><?=__('件')?></dd>
				<?php if(isset($v['order_spec_info']) && $v['order_spec_info']){ ?>
                    <!--取消【规格：】显示-->
					<dd>
                        <!--<strong><?/*=__('规格')*/?>：</strong>&nbsp;&nbsp;-->
                        <em><?= $v['order_spec_info']; ?></em>
                    </dd>
				<?php }?>
				<?php if ($v['order_goods_benefit']) { ?>
                    <span class="td_sale bbc_btns"><p><?= ($v['order_goods_benefit']) ?></p></span>
                <?php } ?>
				<!-- S消费者保障服务 -->
				<!-- E消费者保障服务 -->
				<?php if($val['order_goods_benefit']){?>
					<em class="td_sale bbc_btns small_details">$val['order_goods_benefit']</em>
				<?php }?>
			</dl>
		</td>
		<td><p><?= @format_money($v['goods_price']); ?></p>
		</td>
		<td><?= $v['order_goods_num']; ?></td>
		
		<?php if($v['order_goods_source_id']){$dist_num++;}?>
		<!-- S 合并TD -->
		<?php if ( $k == 0 ) { ?>
		<td class="bdl" rowspan="<?= $val['goods_cat_num']; ?>">
			<div class="buyer"><?= $val['buyer_user_name']; ?><p member_id="<?= $val['buyer_user_id']; ?>"></p>
				<div class="buyer-info"><em></em>
					<div class="con">
						<h3><i></i><span><?=__('联系信息')?></span></h3>
						<dl>
							<dt><?=__('姓名')?>：</dt>
							<dd><?= $val['buyer_user_name']; ?></dd>
						</dl>
						<dl>
							<dt><?=__('电话')?>：</dt>
							<dd><?= $val['order_receiver_contact']; ?></dd>
						</dl>
						<dl>
							<dt><?=__('地址')?>：</dt>
							<dd><?= $val['order_receiver_address']; ?></dd>
						</dl>
					</div>
				</div>
			</div>
		</td>
		<td class="bdl" rowspan="<?= $val['goods_cat_num']; ?>" style="width: 126px;">
			<p class="ncsc-order-amount"><?= @format_money($val['order_payment_amount']); ?></p>
			<p class="goods-freight"><?= $val['shipping_info']; ?></p>
			<p class="goods-pay" title="<?=__('支付方式')?>：<?=$val['payment_name']?>"><?=$val['payment_name']?></p>
			<?php if ( $val['order_shop_benefit'] && $val['order_benefits']) { ?>
				<span class="td_sale bbc_btns">
					<?php foreach($val['order_benefits'] as $v){ ?>
						<p><?=($v)?></p>
					<?php } ?>
				</span>
			<?php } ?>
		</td>
		<td class="bdl bdr" rowspan="<?= $val['goods_cat_num']; ?>">
			<p><?= $val['order_stauts_text']; ?></p>
			<!-- 订单查看 -->
			<p><a href="<?= $val['info_url']; ?>" target="_blank"><?=__('订单详情')?></a></p>
			<!-- 标明订单是否是货到付款订单 -->
			<?php if($val['payment_id'] == PaymentChannlModel::PAY_CONFIRM){?>
				<p><?=__('货到付款')?></p>
			<?php }?>
			<!-- 物流跟踪 -->
			<?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ){ ?>
				<a style="position:relative;" onmouseover="show_logistic('<?=($val['order_id'])?>','<?=($val['order_shipping_express_id'])?>','<?=($val['order_shipping_code'])?>')" onmouseout="hide_logistic('<?=($val['order_id'])?>')">
					<i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?>
					<div style="display: none;" id="info_<?=($val['order_id'])?>" class="prompt-01"> </div>
				</a>
			<?php }?>
            
			<p></p>
		</td>

		<!-- 取消订单 -->
		<td class="bdl bdr" rowspan="<?= $val['goods_cat_num']; ?>">
			<!-- 修改价格 -->
			<!-- 发货 -->
			<?php if(!$dist_num){?>
			<p>
				<?= $val['set_html']; ?>
			</p>
			<?php }?>
            <!-- 订单删除 -->
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 0):?>
                      
                        <p><a onclick="hideOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong fz18 align-middle"></i><span class="align-middle"><?=__('删除订单')?></span></a></p>
                      
            <?php endif; ?>
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>
                      
                        <p><a onclick="restoreOrder('<?=$val['order_id']?>')"><i class="iconfont icon-huanyuan fz18 align-middle"></i><?=__('还原订单')?></a></p>
                      
            <?php endif; ?>
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>
                      
                        <p><a onclick="delOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong fz18 align-middle"></i><?=__('彻底删除')?></a></p>
                      
            <?php endif; ?>

			<!-- 已发货和已完成的货到付款订单添加商家确认收款按钮 -->
			<?php if(($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 0 && $val['payment_id'] == PaymentChannlModel::PAY_CONFIRM):?>
				<?php if($val['payment_time'] > 0){?>
					<p><a class=""><?=__('已收款')?></a></p>
				<?php }else{?>
					<p><a onclick="confirmCollection('<?=$val['order_id']?>')" class="btn-get-cold"><?=__('确认收款')?></a></p>
				<?php }?>

			<?php endif; ?>
			<!-- 锁定 -->
		</td>
		<!-- E 合并TD -->
	</tr>
	<?php } ?>
	<?php } ?>
	<?php } ?>
	</tbody>
	<?php } ?>
	<?php } ?>
</table>

<?php if ( empty($data['items']) ) { ?>
<div class="no_account">
	<img src="<?=$this->view->img?>/ico_none.png">
	<p><?=__('暂无符合条件的数据记录')?></p>
</div>
<?php } ?>
<div class="page">
	<?= $data['page_nav']; ?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

	$('.tabmenu').find('li:gt(6)').hide();

	$(function () {

		//时间
		$('#query_start_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
				this.setOptions({
					maxDate:$('#query_end_date').val() ? $('#query_end_date').val() : false
				})
			}
		});
		$('#query_end_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
				this.setOptions({
					minDate:$('#query_start_date').val() ? $('#query_start_date').val() : false
				})
			},
		});

		//搜索

		var URL;

		$('input[type="submit"]').on('click', function (e) {

			e.preventDefault();

			URL = createQuery();
			window.location = URL;
		});

		$('#skip_off').on('click', function () {

			URL = createQuery();
			window.location = URL;
		});

		function createQuery () {

			var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+/) + '&';

			$('#query_start_date').val() && (url += 'query_start_date=' + $('#query_start_date').val() + '&');
			$('#query_end_date').val() && (url += 'query_end_date=' + $('#query_end_date').val() + '&');
			$('#buyer_name').val() && (url += 'buyer_name=' + $('#buyer_name').val() + '&');
			$('#order_sn').val() && (url += 'order_sn=' + $('#order_sn').val() + '&');
			$('#skip_off').prop('checked') && (url += 'skip_off=1&');

			return url;
		}

		//取消订单
		$('a[dialog_id="seller_order_cancel_order"]').on('click', function () {

			var order_id = $(this).data('order_id'),
				url = SITE_URL + '?ctl=Seller_Trade_Order&met=orderCancel&typ=';

			$.dialog({
				title: '<?=__('取消订单')?>',
				content: 'url: ' + url + 'e',
				data: { order_id: order_id },
				height: 250,
				width: 400,
				lock: true,
				drag: false,
				ok: function () {

					var form_ser = $(this.content.order_cancel_form).serialize();

					$.post(url + 'json', form_ser, function (data) {
						if ( data.status == 200 ) {
							parent.Public.tips({
								content: '<?=__('修改成功')?>',
								type: 3
							}), window.location.reload();
							return true;
						} else {
							parent.Public.tips({
								content: '<?=__('修改失败')?>',
								type: 1
							});
							return false;
						}
					})
				}
			})
		});
	});

	function formSub(){
		$('.search-form').parents('form').submit();
	}

	window.hide_logistic = function (order_id)
	{
		$("#info_"+order_id).hide();
		$("#info_"+order_id).html("");
	}

	window.show_logistic = function (order_id,express_id,shipping_code)
	{
		$("#info_"+order_id).show();
		$.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

			if(da)
			{
				$("#info_"+order_id).html(da);
			}
			else
			{
				$("#info_"+order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
			}

		})
	}


</script>
