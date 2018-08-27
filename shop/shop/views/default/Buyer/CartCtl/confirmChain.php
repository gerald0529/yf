<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<script type="text/javascript" src="<?=$this->view->js?>/chain.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/alert.js"></script>
<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<div class="cart-head">
	<div class="wrap">
		<div class="head_cont clearfix">
			<div class="nav_left" style="float:none;">
				<a href="index.php" class=""><img src="<?=$this->web['web_logo']?>"/></a>
				<a class="download iconfont"></a>
			</div>
		</div>
	</div>
</div>
	<div class="wrap">
		<div class="shop_cart_head clearfix">
			<div class="cart_head_left">
				<h4><?=__('填写核对购物信息')?></h4>
				
			</div>
			<div class="cart-head-module clearfix">
				<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('请仔细填写手机号，以确保电子兑换码准确发到您的手机。')?></p>
				<ul class="cart_process">
					<li class="mycart">
						<div class="fl">
							<i class="iconfont icon-wodegouwuche bbc_color"></i>
							<h4><?=__('我的购物车')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart process_selected1">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-iconquerendingdan bbc_color"></i>
							<h4 class=""><?=__('确认订单')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-icontijiaozhifu"></i>
							<h4><?=__('支付提交')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-dingdanwancheng"></i>
							<h4><?=__('订单完成')?></h4><h4>
							</h4>
						</div>
					</li>
				</ul>
			</div>
			
		</div>
		<div class="ncc-receipt-info">

            <h4 class="confirm"><?=__('支付方式')?></h4>
            <div class="pay_way pay-selected" pay_id="1">
                <i></i><?=__('在线支付')?>
            </div>
            <div class="pay_way" pay_id="3">
                <i></i><?=__('门店付款')?>
            </div>
    <div id="invoice_list" class="store-receipt-info current_box">
    	<div class="store-candidate-items">
    		<div class="store-form-default">
		        <dl>
		            <dt><i class="required">*</i><?=__('门店')?>：</dt>
		            <dd>
		                <div class="address-text">
		                    <input name="region" type="hidden"  value="<?=$chain_base['chain_name']?>（<?=$chain_base['chain_province']?> <?=$chain_base['chain_city']?> <?=$chain_base['chain_county']?>）">
		                    <span class="_region_value"><?=$chain_base['chain_name']?>（<?=$chain_base['chain_province']?> <?=$chain_base['chain_city']?> <?=$chain_base['chain_county']?>）</span>
		                    <input type="hidden" name="chain_id" id="chain_id"  value="<?=$chain_base['chain_id']?>">
		                </div>
		            </dd>
		        </dl>

                <!--用户地址列表S-->
                <ul class="receipt_address clearfix">
                    <div id="address_list">
                        <?php if (isset($data['address'])) {
                            $total = 0;
                            $total_dian_rate = 0;
                            foreach ($data['address'] as $key => $value) {
                                ?>
                                <li class="<?php if (!$address_id && $value['user_address_default'] == 1) { ?>add_choose<?php } ?><?php if ($address_id && $value['user_address_id'] == $address_id) { ?>add_choose<?php } ?> "
                                    id="addr<?= ($value['user_address_id']) ?>">
                                    <input type="hidden" id="address_id" value="<?= ($value['user_address_id']) ?>">
                                    <input type="hidden" id="user_address_province_id"
                                           value="<?= ($value['user_address_province_id']) ?>">
                                    <input type="hidden" id="user_address_city_id"
                                           value="<?= ($value['user_address_city_id']) ?>">
                                    <input type="hidden" id="user_address_area_id"
                                           value="<?= ($value['user_address_area_id']) ?>">
                                    <div class="editbox">
                                        <a class="edit_address"
                                           data_id="<?= ($value['user_address_id']) ?>"><?= __('编辑') ?></a>
                                        <a class="del_address"
                                           data_id="<?= ($value['user_address_id']) ?>"><?= __('删除') ?></a>
                                    </div>
                                    <h5 class="js-true-name one-overflow wp70"><?= ($value['user_address_contact']) ?></h5>
                                    <!--<p class="addr-len"><?/*= ($value['user_address_area']) */?> <?/*= ($value['user_address_address']) */?></p>-->
                                    <span class="phone js-phone"><?= ($value['user_address_phone']) ?></span>

                                </li>
                            <?php }
                        } ?>
                    </div>
                    <div class="add_address">
                        <a><?= __('+') ?></a>
                    </div>
                </ul>
                <!--用户地址列表E-->

		    </div>
		</div>
    </div>
  </div>
		<h4 class="confirm contfirm_1"><?=__('门店自提类商品清单')?></h4>
		<div class="cart_goods">
			<ul class="cart_goods_head clearfix">
				<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_num goods_num_1"><?=__('数量')?></li>
				<li class="goods_price goods_price_1"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_name goods_name_1"><?=__('商品')?></li>
			</ul>
			<ul class="cart_goods_list clearfix">
				<li>
					<table>
						<tbody class="rel_good_infor">
							<tr>
								<td class="goods_img"><img src="<?=($data['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name" style="width:536px;">
								<span><?=($data['goods_base']['goods_name'])?></span>
									<p>
											<input type="hidden" id="cart_id" value="<?=($data['cart']['cart_id'])?>">
											<input type="hidden" id="shop_id" value="<?=($data['goods_base']['shop_id'])?>">
										<?php if(!empty($data['goods_base']['spec'])){foreach($data['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
								</td>
								<td class="goods_price goods_price_1 ">
									<?php if($data['goods_base']['old_price'] > 0){?><p class="ori_price"><?=($data['goods_base']['old_price'])?></p><?php }?>
									<p class="now_price"><?=($data['goods_base']['now_price'])?></p>

								</td>

								<td class="goods_num goods_num_1">
									<span><?=($nums)?></span>
								</td>
								<td class="price_all">
									<span class="subtotal"><?=($data['goods_base']['sumprice'])?></span>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>

		
			<div class="goods_remark clearfix pb20">
				<!--1.9店铺代金券-->
				<div class="tlr z-bor clearfix">
					<div class="inline fl mw-800">
						<div class="clearfix">
						<?php  
							$count_v = 0;
							foreach($data['voucherList'] as $v){
								$count_v += $v['permission'];
							}
						?>
						<p class="tl fl mb10">
							<span class="activity-dt lh-28">店铺代金券</span>
							<?php if($data['goodsPromotionStatus'] > 0 ){ ?>
								<span class="isFavoritesShop lh-28">活动商品不可使用店铺代金券</span>
							<?php }else{ ?>
							<input type="hidden" name="voucher" value="<?php if($count_v > 0){?><?php if($data['voucherList']){?>请选择优惠劵<?php }else{ ?>暂无可用的优惠劵<?php } ?><?php }else{ ?>暂无可用的优惠劵<?php } ?>">
								<select class="select z-select js-example-templating shop_voucher w400" id="shop_voucher">
									<option></option>
									<?php foreach($data['voucherList'] as $value){ ?>
									<option value="<?=$value['voucher_price']?>" shop_id="<?=$data['shop_base']['shop_id']?>" <?php if($data['bestOffer']['voucherId'] == $value['voucher_id']){ ?> selected="selected" <?php } ?> <?php if($value['permission'] == 0){ ?> disabled="disabled" <?php } ?> voucher_id="<?=$value['voucher_id']?>">满<?=$value['voucher_limit']?>减<?=$value['voucher_price']?> <time><?=date('Y-m-d',strtotime($value['voucher_end_date']))?>到期</time>
									</option>
									<?php } ?>
									<?php if($count_v > 0){ ?>
		                            	<option value="0" voucher_id="0" shop_id="<?=$data['shop_base']['shop_id']?>"><?=__('不使用店铺优惠券')?></option>
		                            <?php } ?>
								</select>
								<span id="voucher_discount" style="display:none" class="isFavoritesShop lh-28">代金券不与会员折扣共用</span>
							<?php } ?>
							<input type="hidden" name="best_v" value="<?=$data['bestOffer']['voucherPrice']?>">
						</p>
						<p class="remarks"><span class="remarks-z activity-dt"><?=__('备注')?></span><input name="remarks" id="goodsremarks" placeholder="<?=__('限45个字（定制类商品，请将购买需求在备注中做详细说明）')?>"><?=__('提示：请勿填写有关支付、收货、发票方面的信息')?></p>
						</div>
					</div>
				</div>
				
			</div>

			<div class="frank clearfix mb10">
				<div class="invoice tl  pt10 pb20">
					<div  class="invoice-cont clearfix">
						<div class="fl activity-dt">平台红包</div>
							<?php if($data['goodsPromotionStatus'] > 0 ){ ?>
								<span class="isFavoritesShop">活动商品不可使用平台红包</span>
							<?php }else{?>
									<div class=" inline">									
									<?php  
										$count_r = 0;
										foreach($data['redPacketList'] as $vr){
											$count_r += $vr['permission'];
										}
									?>
									<input type="hidden" name="redpacket" value="<?php if($count_r > 0){?><?php if($data['redPacketList']){?>请选择平台红包<?php }else{ ?>暂无可用的平台红包<?php } ?><?php }else{ ?>暂无可用的平台红包<?php } ?>">
								    <select class="z-select js-example-templating order_redpacket w400" id="redpacket">
								    	<option></option>
										<?php foreach($data['redPacketList'] as $rvalue){ ?>
										<option value="<?=$rvalue['redpacket_price']?>" <?php if($data['bestOffer']['redPackId'] == $rvalue['redpacket_id']){ ?> selected="selected" <?php } ?> <?php if($rvalue['permission'] == 0){ ?> disabled="disabled" <?php } ?>  red_id="<?=$rvalue['redpacket_id']?>" <?php if($data['bestOffer']['redPackId'] == $rvalue['redpacket_id']){ ?> selected="selected" <?php } ?>>满<?=$rvalue['redpacket_t_orderlimit']?>减<?=$rvalue['redpacket_price']?> <time><?=date('Y-m-d',strtotime($rvalue['redpacket_end_date']))?>到期</time>
										</option>
										<?php } ?>
										<?php if($count_r > 0){ ?>
											<option  value="0" red_id="0"><?=__('不使用平台红包')?></option>
										<?php } ?>
									</select>
									<span id="redpacket_discount" class="isFavoritesShop" style="display:none">平台红包不与会员折扣共用</span>
							<?php } ?>
							<input type="hidden" name="best_redp" value="<?=$data['bestOffer']['redPackPrice']?>">
						</div>
					</div>
					<div class="invoice-cont">
						<span class="activity-dt">会员折扣</span>
						<div style="display:inline">
							<?php if($data['goodsPromotionStatus'] > 0 ){ ?>
								<span class="isFavoritesShop">活动商品不可使用会员折扣</span>
							<?php }else{ ?>
								<?php if(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'false'){?>
		                            <span><?=__('仅限自营店铺享受会员折扣')?></span>
		                        <?php }else{?>
									<input type="checkbox" name="order_discount" id="is_discount">
									<span class="isFavoritesShop" id="no_discount">开启会员折扣不可使用代金券和店铺红包</span>
									<?php 
										$discount_price = $data['goods_base']['sumprice'] - $user_rate*$data['goods_base']['sumprice'];
									?>
									<span class="isFavoritesShop bbc_color" style="display:none" id="use_discount" discount_price="<?=number_format($discount_price,2,'.','')?>">减￥<?=(number_format($discount_price,2,'.',''))?></span>
		                        <?php }?>
							<?php } ?>
					    </div>
					</div>
				</div>
				<p class="submit" style="text-align: center;">
					<span>
						<?=__('订单金额：')?>
						<strong>
						<?php if($data['goodsPromotionStatus'] > 0){
									$data['bestOffer']['voucherPrice'] = 0;
									$data['bestOffer']['redPackPrice'] = 0;
							   }
						?>
						 	<?php $total = $data['goods_base']['sumprice'] - $data['bestOffer']['voucherPrice']; ?>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="total"><?=(number_format($total,2,'.',''))?></i>
						</strong>
					</span>
					<span>
						<?=__('平台红包：')?>
						<strong>
							<?php if($data['goodsPromotionStatus'] > 0){?>
								<i class="redpacket isFavoritesShop">未使用</i>
							<?php }else{ ?>
								<?php if($data['bestOffer']['redPackPrice']){ ?>
									<i class="redpacket">减￥<?=($data['bestOffer']['redPackPrice'])?></i>
								<?php }else{ ?>
									<i class="redpacket isFavoritesShop">未使用</i>
								<?php } ?>
							<?php } ?>
						</strong>
					</span>
					<?php if(!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true'))
					{}else{$user_rate = 100;}?>
					<?php if($user_rate != 100){ ?>
						<span>
							<?=__('会员折扣：')?>
							<strong>
								<i class="rate_total isFavoritesShop">未使用</i>
							</strong>
						</span>
					<?php } ?>

					<span>
						<b style="font-weight:normal" id='pay'><?=__('支付金额：')?></b>
						<strong class="bbc_color">
							<?=(Web_ConfigModel::value('monetary_unit'))?>
							<input type="hidden" id="orderAmount" value="<?=($data['goods_base']['sumprice'])?>">
							<?php $after_total = $data['goods_base']['sumprice'] - $data['bestOffer']['redPackPrice'] - $data['bestOffer']['voucherPrice']; 
							?>
							<i class="after_total bbc_color js-order-amount"><?=(number_format($after_total,2,'.',''))?></i>
						</strong>
					</span>
					<a id="pay_btn" class="bbc_btns"><?=__('提交订单')?></a>
				</p>

			</div>

		</div>
	</div>

	<!-- 订单提交遮罩 -->
	<div id="mask_box" style="display:none;">
		<div class='loading-mask'></div>
		<div class="loading">
			<div class="loading-indicator">
				<img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>
				<br/><span class="loading-msg"><?=__('正在提交订单，请稍后...')?></span>
			</div>
		</div>
	</div>

<script>

	$(function(){
		$(".sel-goods").click(function(){$(".jia-shop-are").toggle();})

        //删除收货地址
        $(".del_address").click(function(event){
            var id =  $(this).attr('data_id');
            $.dialog({
                title: '删除',
                content: '您确定要删除吗？',
                height: 100,
                width: 410,
                lock: true,
                drag: false,
                ok: function () {
                    $.post(SITE_URL  + '?ctl=Buyer_User&met=delAddress&typ=json',{id:id},function(data)
                        {
                            if(data && 200 == data.status) {
                                Public.tips.success('删除成功!');
                                $("#addr"+id).remove();
                            } else {
                                Public.tips.error('删除失败!');
                            }
                        }
                    );
                }
            })

            if(event && event.stopPropagation)
            {
                event.stopPropagation();
            }
            else
            {
                event.cancelBubble = true;
            }
        });

        //编辑收货地址
        $(".edit_address").click(function(event){
            url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+$(this).attr('data_id');

            $.dialog({
                title: '修改地址',
                content: 'url: ' + url ,
                height: 340,
                width: 580,
                lock: true,
                drag: false

            });

            if(event && event.stopPropagation)
            {
                event.stopPropagation();
            }
            else
            {
                event.cancelBubble = true;
            }
        });

        function changeURLPar(destiny, par, par_value)
        {
            var pattern = par+'=([^&]*)';
            var replaceText = par+'='+par_value;
            if (destiny.match(pattern))
            {
                var tmp = new RegExp(pattern);
                tmp = destiny.replace(tmp, replaceText);
                return (tmp);
            }
            else
            {
                if (destiny.match('[\?]'))
                {
                    return destiny+'&'+ replaceText;
                }
                else
                {
                    return destiny+'?'+replaceText;
                }


            }
            return destiny+'\n'+par+'\n'+par_value;
        }

        window.addAddress = function(val)
        {
            console.info(val);
            //地址中的参数
            var params= window.location.search;

            params = changeURLPar(params,'address_id',val.user_address_id);

            window.location.href = SITE_URL + params;

            if(val.user_address_default == 1)
            {
                def = 'add_choose';

                $(".add_choose").removeClass("add_choose");
            }
            else
            {
                def = '';
            }
            str = '<li class=" ' + def + ' " id="addr'+ val.user_address_id + ' "><div class="editbox"><a onclick="edit_address( ' + val.user_address_id + ' )">编辑</a> <a onclick="del_address( ' + val.user_address_id + ' )">删除</a></div><h5 class="one-overflow wp85"> ' + val.user_address_contact +' </h5><p> ' + val.user_address_area + ' ' + val.user_address_address +' </p><div><span class="phone"><i class="iconfont">&#xe64c;</i><span> ' + val.user_address_phone +' </span></span></div></li>';

            $("#address_list").append(str);

            //location.reload();
        }

        window.editAddress = function(val)
        {
            area = val.user_address_area + ' ' + val.user_address_address;
            $("#addr"+val.user_address_id).find("h5").html(val.user_address_contact);
            $("#addr"+val.user_address_id).find("p").html(area);
            $("#addr"+val.user_address_id).find(".phone").find("span").html(val.user_address_phone);
        }

        //代金券
        $("#shop_voucher").change(function(){
	    	var voucher = parseFloat($("input[name='best_v']").val());//记录之前代金券
	        var total = parseFloat($(".total").html());//订单金额
	        var after_total = parseFloat($(".after_total").html());//支付金额
        	var voucher_price = parseFloat($(this).val());
        	$(".total").html((total + voucher - voucher_price).toFixed(2));//订单金额
        	$(".after_total").html((after_total + voucher - voucher_price).toFixed(2));//支付金额
        	$("input[name='best_v']").val(voucher_price);

        	//判断红包
			var order_price = Number(after_total + voucher - voucher_price + parseFloat($("input[name='best_redp']").val()));
			$.ajax({
				type:"POST",
				url: SITE_URL  + '?ctl=Buyer_Cart&met=getUserRedpacket&typ=json',
				data:{order_price:order_price},
				success:function(result){
					var rpt_list = result.data.rpt_list;
					var rpt_info = result.data.rpt_info[0];
					if(rpt_info)
					{
						$("input[name='redpacket']").val('请选择平台红包');
					}
					else
					{
						$("input[name='redpacket']").val('暂无可用的平台红包');
					}
					var html = "<option></option>";
					for(var v=0; v<rpt_list.length;v++)
					{
						html += "<option value='"+ rpt_list[v].price +"' red_id='"+ rpt_list[v].id +"'";
						if(rpt_info && rpt_list[v].id == rpt_info.id)
						{
							html += "selected='selected'";
						}
						else
						{
							html += " ";
						}
						if(rpt_list[v].isable == 0)
						{
							html += "disabled='disabled'";
						}
						else
						{
							html += " ";						}
						html += ">满" + rpt_list[v].limit + "减" + rpt_list[v].price + " <time>" + rpt_list[v].end_date.split(' ')[0] + "到期</time></option>"
					}
		
					html += "<option  value='0'>不使用平台红包</option>";
					$("#redpacket").html(html);
					if(rpt_info)
					{
						$(".redpacket ").html('减￥'+rpt_info.price);
						$("input[name='best_redp']").val(rpt_info.price);
						$(".redpacket ").removeClass('isFavoritesShop');

						//修改订单金额
						order_price = order_price - rpt_info.price;
						$(".after_total").html((order_price).toFixed(2));
					}
					else
					{
						$(".redpacket ").html('未使用');
						$(".redpacket ").addClass('isFavoritesShop');

						//修改订单金额
						var best_redp = $("input[name='best_redp']").val();
						$(".after_total").html((order_price).toFixed(2));
						$("input[name='best_redp']").val(0);

					}
				}
			});
        });

        //红包
        $('#redpacket').change(function(){
        	var redpacket = parseFloat($("input[name='best_redp']").val());//记录之前红包
	        var after_total = parseFloat($(".after_total").html());//支付金额
        	var redpacket_price = parseFloat($(this).val());
        	if(redpacket_price > 0)
        	{
        		$(".redpacket").html("减￥" + redpacket_price.toFixed(2));
        		$(".redpacket").removeClass('isFavoritesShop');
        	}
        	else
        	{
        		$(".redpacket").addClass('isFavoritesShop');
        		$(".redpacket").html("未使用");
        	}

        	$(".after_total").html((after_total + redpacket - redpacket_price).toFixed(2));
        	$("input[name='best_redp']").val(redpacket_price);
        	redpacket = 0
        });

        //会员折扣
        $("#is_discount").click(function(){
        	var discount_price = parseFloat($("#use_discount").attr("discount_price"));
        	var after_total = parseFloat($("#orderAmount").val());//订单原价
        	if($('#is_discount').is(':checked')) {
        		//会员折扣
        		$("#no_discount").hide();
        		$("#use_discount").show();
        		$(".rate_total ").html("减￥"+discount_price.toFixed(2));
        		$(".rate_total").removeClass('isFavoritesShop');
        		//代金券
        		$("#shop_voucher").hide();
        		$("#voucher_discount").show();
        		//红包
        		$("#redpacket").hide();
        		$("#redpacket_discount").show();
        		$(".redpacket").html('未使用');
        		$(".redpacket").addClass('isFavoritesShop');

				$(".select2").hide();

				//订单金额
				$(".total").html(after_total.toFixed(2));
        		//支付金额
        		$(".after_total").html((after_total - discount_price).toFixed(2));
			}
			else
			{
				//会员折扣
				$("#no_discount").show();
        		$("#use_discount").hide();
        		$(".rate_total ").html("未使用");
        		$(".rate_total").addClass('isFavoritesShop');
        		//代金券
        		$("#shop_voucher").show();
        		$("#voucher_discount").hide();
        		//红包
        		$("#redpacket").show();
        		$("#redpacket_discount").hide();

				$(".select2").show();

        		window.location.reload();
			}
        });



    });
</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
