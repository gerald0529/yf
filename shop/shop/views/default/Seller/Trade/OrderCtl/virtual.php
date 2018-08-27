<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<script src="<?= $this->view->js ?>/seller_order.js" charset="utf-8"></script>


<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
</head>
<body>

<form>
	<table class="search-form">
		<tbody>
		<tr>
			<td>&nbsp;</td>
			<?php if($_GET['met'] == 'virtual'){?>
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
					$condition['order_create_time:<='];
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
				<input name="ctl" value="Seller_Trade_Order" type="hidden" /><input name="met" value="virtual" type="hidden" />
			</td>
			<td class="mar"><a class="button refresh" onclick="location.reload()"><i class="iconfont icon-huanyipi"></i></a><td>
		</tr>
		</tbody>
	</table>
</form>

<table class="table-list-style order-list-style ncsc-default-table2 ncsc-default-table order" width="100%" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th><?=__('商品')?></th>
		<th width="100"><?=__('单价')?><!--（<?/*=Web_ConfigModel::value('monetary_unit')*/?>）--></th>
		<th width="40"><?=__('数量')?></th>
		<th width="80"><?=__('买家')?></th>
		<th width="100"><?=__('订单金额')?></th>
		<th width="80"><?=__('交易状态')?></th>
		<th width="80"><?=__('操作')?></th>
	</tr>
	</thead>

	<tbody>
	<?php if ( !empty($order_virtual_list['items']) ) { ?>
	<?php foreach ( $order_virtual_list['items'] as $key => $val ) { $goods_list = pos($val['goods_list']); ?>
	<tr>
		<td colspan="20" class="sep-row"></td>
	</tr>
	<tr>
		<th colspan="99" class="hd tl">
			<span><?=__('订单编号')?>：<?php echo $val['order_id']; ?></span> <span><?=__('下单时间')?>：<?php echo $val['order_create_time']; ?> </span>
		</th>
	</tr>
	<tr>
		<td class="bdl">
			<dl class="fn-clear">
				<dt>
					<a target="_blank" href="<?php /* 虚拟商品单次购买 */ echo Yf_Registry::get('url') . '?ctl=Goods_Goods&met=goods&type=goods&gid=' . $goods_list['goods_id']; ?>"><img width="60px" height="60px" src="<?php echo $goods_list['goods_image']; ?>"></a>
				</dt>
				<dd>
					<a target="_blank" href="<?php /* 虚拟商品单次购买 */ echo Yf_Registry::get('url') . '?ctl=Goods_Goods&met=goods&type=goods&gid=' . $goods_list['goods_id']; ?>"><?php echo $goods_list['goods_name']; ?></a>
					<?php if($goods_list['order_goods_returnnum']){?>
						<span class="colred"><?=__('已退').$goods_list['order_goods_returnnum'].__('件')?></span>
					<?php }?>
				</dd>
			</dl>
		</td>
		<td>
			<?php echo format_money($goods_list['goods_price']); ?>
		</td>
		<td><?php echo $goods_list['order_goods_num']; ?></td>
		<td class="buyer_name br" rowspan="1"><?php echo $val['buyer_user_name']; ?></td>
		<td class="count br" rowspan="1">
			<p class="red"><?php echo format_money($val['order_payment_amount']); ?> </p>
			<p> <?php if ( $val['order_shipping_fee'] == 0 ) { echo "<?=__('（免运费）')?>"; } else { echo format_money($val['order_shipping_fee']); } ?> </p>
			<p class="red"></p>
		</td>
		<td class="status br" rowspan="1">
			<p><?php echo $val['order_stauts_const']; ?></p>
			<a target="_blank" href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Order&met=virtualInfo&order_id=' . $val['order_id']; ?>" ><?=__('订单详情')?></a>
		</td>
		<td class="operate bdr" rowspan="1">
			<?php if ( $val['order_status'] == Order_StateModel::ORDER_WAIT_PAY ) { ?>
			<p>
				<a href="javascript:void(0)" class="ncbtn bbc_seller_btns mt5" data-order_id = "<?php echo $val['order_id']; ?>" dialog_id="seller_order_cancel_order">
					<i class="icon-remove-circle"></i><?=__('取消订单')?>
				</a>
			</p>
			<?php } ?>
            <!-- 订单删除 -->
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 0):?>
                      
                        <p><a onclick="hideOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('删除订单')?></a></p>
                      
            <?php endif; ?>
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>
                      
                        <p><a onclick="restoreOrder('<?=$val['order_id']?>')"><i class="iconfont icon-huanyuan icon_size22"></i><?=__('还原订单')?></a></p>
                      
            <?php endif; ?>
            <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>
                      
                        <p><a onclick="delOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('彻底删除')?></a></p>
                      
            <?php endif; ?>            
		</td>
	</tr>
	<?php } ?>
	<?php } ?>
	</tbody>
</table>

<?php if ( empty($order_virtual_list['items']) ) { ?>
	<div class="no_account">
		<img src="<?=$this->view->img?>/ico_none.png">
		<p><?=__('暂无符合条件的数据记录')?></p>
	</div>
<?php } ?>
<div class="page">
	<?= $order_virtual_list['page_nav'] ?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
	$(function () {

		$('.tabmenu').find('li:gt(5)').hide();

		//虚拟兑换码
		$('.tabmenu').append('<a href="'+ SITE_URL +'?ctl=Seller_Trade_Order&met=virtualExchange&typ=e" class="bbc_seller_btns ncbtn"><i class="icon-edit"></i><?=__('校验兑换码')?></a>');

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
		})
	});

	function formSub(){
		$('.search-form').parents('form').submit();
	}
</script>
