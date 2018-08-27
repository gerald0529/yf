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
			<td><input type="checkbox" id="skip_off" value="1" <?php if (request_string('skip_off')) {
					echo 'checked';
				} ?> name="skip_off"> <label class="relative_left" for="skip_off"><?=__('不显示已关闭的订单')?></label>
			</td>
			<th><?=__('下单时间')?></th>
			<td class="w240">
				<input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('起始时间')?>" name="start_date" id="start_date" value="<?php if (request_string('start_date')) {
					echo request_string('start_date');
				} ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label><span class="rili_ge">–</span>
				<input id="end_date" class="text w70 hasDatepicker heigh" placeholder="<?=__('结束时间')?>" type="text" name="end_date" value="<?php if (request_string('end_date')) {
					echo request_string('end_date');
				} ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label>
			</td>
			<th><?=__('买家')?></th>
			<td class="w100">
				<input type="text" class="text w80" placeholder="<?=__('买家昵称')?>" id="buyer_name" name="buyer_name" value="<?php if (request_string('buyer_name')) {
					echo request_string('buyer_name');
				} ?>"></td>
			<th><?=__('订单编号')?></th>
			<td class="w160">
				<input type="text" class="text w150 heigh" placeholder="<?=__('请输入订单编号')?>" id="orderkey" name="orderkey" value="<?php if (request_string('orderkey')) {
					echo $cond_row['orderkey'];
				} ?>"></td>
			<td class="w70 tc"><a onclick="formSub()" class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
			<input name="ctl" value="Distribution_Seller_Setting" type="hidden" /><input name="met" value="directsellerDetail" type="hidden" />
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
		<th class="w150"><?=__('单价')?><!--（<?/*=Web_ConfigModel::value('monetary_unit')*/?>）--></th>
		<th class="w40"><?=__('数量')?></th>
		<th class="w100"><?=__('买家')?></th>
		<th class="w100"><?=__('订单金额')?></th>
		<th class="w90"><?=__('交易状态')?></th>
	</tr>
	</thead>

	<?php if ( !empty($data['items']) ) { ?>
	<?php foreach ( $data['items'] as $key => $val ) { ?>
	<tbody>
	<tr>
		<td colspan="20" class="sep-row"></td>
	</tr>
	<tr>
		<th colspan="20">
			<span class="ml10"><?=__('订单编号：')?><em><?= $val['order_id']; ?></em></span>
			<span><?=__('下单时间：')?><em class="goods-time"><?= $val['order_create_time']; ?></em></span>
		</th>
	</tr>

	<!-- S商品列表 -->
	<?php if( !empty($val['goods_list']) ) { ?>
	<?php foreach( $val['goods_list'] as $k => $v ) { ?>
	<tr>
		<td class="bdl"></td>
		<td class="w70">
			<div class="ncsc-goods-thumb">
				<a href="/index.php?ctl=Goods_Goods&met=snapshot&goods_id=<?= $v['goods_id']; ?>&order_id=<?= $v['order_id']; ?>" target="_blank"><img src="<?= $v['goods_image']; ?>"></a>
			</div>
		</td>
		<td class="tl">
			<dl class="goods-name">
				<dt>
					<?php if($v['order_goods_source_id']){ ?>
						<span class="dis_flag"><?=__('分销')?></span>
					<?php } ?>
					<a target="_blank" href="/index.php?ctl=Goods_Goods&met=snapshot&goods_id=<?= $v['goods_id']; ?>&order_id=<?= $v['order_id']; ?>"><?= $v['goods_name']; ?></a>
					<a target="_blank" class="blue ml5" href="/index.php?ctl=Goods_Goods&met=snapshot&goods_id=<?= $v['goods_id']; ?>&order_id=<?= $v['order_id']; ?>"><?=__('[交易快照]')?></a>
				</dt>
				<dd><strong><?= format_money($v['goods_price']) ?></strong>&nbsp;x&nbsp;<em><?= $v['order_goods_num']; ?></em><?=__('件')?></dd>
				<?php if(isset($v['order_spec_info']) && $v['order_spec_info']){ ?>
					<dd><strong><?=__('规格：')?></strong>&nbsp;&nbsp;<em><?php foreach($v['order_spec_info'] as $oinfokey => $oinfoval){ echo  $oinfoval;} ?></em></dd>
				<?php }?>
				<!-- S消费者保障服务 -->
				<!-- E消费者保障服务 -->
			</dl>
		</td>
		<td>
			<p><?= @format_money($v['goods_price']); ?></p>
			<p><?=__('一级佣金：')?><?=@format_money($v['directseller_commission_0']);?></p>
			<p><?=__('二级佣金：')?><?=@format_money($v['directseller_commission_1']);?></p>
			<p><?=__('三级佣金：')?><?=@format_money($v['directseller_commission_2']);?></p>
		</td>
		<td><?= $v['order_goods_num']; ?></td>

		<!-- S 合并TD -->
		<?php if ( $k == 0 ) { ?>
		<td class="bdl" rowspan="<?= $val['goods_cat_num']; ?>">
			<div class="buyer"><?= $val['buyer_user_name']; ?><p member_id="<?= $val['buyer_user_id']; ?>"></p>
				<div class="buyer-info"><em></em>
					<div class="con">
						<h3><i></i><span><?=__('联系信息')?></span></h3>
						<dl>
							<dt><?=__('姓名：')?></dt>
							<dd><?= $val['buyer_user_name']; ?></dd>
						</dl>
						<dl>
							<dt><?=__('电话：')?></dt>
							<dd><?= $val['order_receiver_contact']; ?></dd>
						</dl>
						<dl>
							<dt><?=__('地址：')?></dt>
							<dd><?= $val['order_receiver_address']; ?></dd>
						</dl>
					</div>
				</div>
			</div>
		</td>
		<td class="bdl" rowspan="<?= $val['goods_cat_num']; ?>" style="width: 126px;">
			<p class="ncsc-order-amount"><?= @format_money($val['order_payment_amount']); ?></p>
			<p class="goods-freight"><?= $val['shipping_info']; ?></p>
			<p class="goods-pay" title="<?=__('支付方式：')?><?=$val['payment_name']?>"><?=$val['payment_name']?></p>
			<?php if ( !empty($val['order_shop_benefit']) ) { ?>
				<span class="td_sale bbc_btns"><?= $val['order_shop_benefit'] ?></span>
			<?php } ?>
		</td>
		<td class="bdl bdr" rowspan="<?= $val['goods_cat_num']; ?>">
			<p><?= $val['order_status_text']; ?></p>
			<!-- 订单查看 -->
			<p><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=<?=($val['order_id'])?>" target="_blank"><?=__('订单详情')?></a></p>
			<p></p>
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
		$('#start_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
				this.setOptions({
					maxDate:$('#start_date').val() ? $('#start_date').val() : false
				})
			}
		});
		$('#end_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
				this.setOptions({
					minDate:$('#end_date').val() ? $('#end_date').val() : false
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

		function createQuery ()
		{
			var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+/) + '&';

			$('#start_date').val() && (url += 'start_date=' + $('#start_date').val() + '&');
			$('#end_date').val() && (url += 'end_date=' + $('#end_date').val() + '&');
			$('#buyer_name').val() && (url += 'buyer_name=' + $('#buyer_name').val() + '&');
			$('#orderkey').val() && (url += 'orderkey=' + $('#orderkey').val() + '&');
			$('#skip_off').prop('checked') && (url += 'skip_off=1&');

			return url;
		}
	});

	function formSub(){
		$('.search-form').parents('form').submit();
	}
</script>