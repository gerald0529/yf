<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?= VER ?>" rel="stylesheet">
<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<style>
	
    a.ncbtn-mini {
        line-height: 16px;
        height: 16px;
        padding: 3px 7px;
        border-radius: 2px;
    }
    
    .tabmenu .tab li {
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        margin-right: -1px;
    }
    
    .tabmenu .tab .active a, .tabmenu .tab .active a:hover {
        padding-bottom: 0px;
        padding-top: 0px;
    }
    
    a.ncbtn {
        margin: 0px;
    }
    
	.btn-width{
		width: 66px;
	    padding: 0;
	    height: 30px;
	    line-height: 30px;
	}
</style>
</head>
</body>
<div id="mainContent">
    <style type="text/css">
        .sticky .tabmenu {
            padding: 0;
            position: relative;
        }
    </style>
    <span class="fr mr5"><a href="<?= $print_tpl_url; ?>" class="ncbtn-mini bbc_seller_btns" target="_blank" title="<?= __('打印运单') ?>"><?= __('打印运单') ?></a></span>
    <div class="">
        <div class="step-title"><em><?= __('第一步') ?></em><?= __('确认收货信息及交易详情') ?></div>
        <form name="deliver_form" method="POST" id="deliver_form">
            <input type="hidden" value="<?= $data['order_id']; ?>" name="order_id" id="order_id">
            <table class="ncsc-default-table order deliver">
                <tbody>
                <tr>
                    <th colspan="20">
                        <a href="index.php?act=store_order&amp;op=order_print&amp;order_id=189" target="_blank" class="fr" title="<?= __('打印发货单') ?>">
                            <i class="print-order"></i>
                        </a>
                        <span class="fr mr30"></span>
                        <span class="ml10 fl ml10"><?= __('订单编号') ?>：<?= $data['order_id']; ?></span>
                        <span class="ml20 fl"><?= __('下单时间') ?>：<em class="goods-time"><?= $data['order_create_time']; ?></em></span>
                    </th>
                </tr>
                <!-- S商品列表 -->
                <?php if (!empty($data['goods_list'])) { ?>
                    <?php foreach ($data['goods_list'] as $key => $val) { ?>
                        <tr>
                            <td class="bdl w10"></td>
                            <td class="w50">
                                <div class="pic-thumb">
                                    <a href="<?= $val['goods_link'] ?>" target="_blank"><img src="<?= $val['goods_image']; ?>"></a>
                                </div>
                            </td>
                            <td class="tl">
                                <dl class="goods-name">
                                    <dt>
                                        <a target="_blank" href="<?= $val['goods_link']; ?>"><?= $val['goods_name']; ?></a>
                                    </dt>
                                    <!--商品单价-->
                                    <dd>
                                        <strong>￥<?= $val['goods_price']; ?></strong>
                                        &nbsp;x&nbsp;
                                        <em><?= $val['order_goods_num']; ?></em>
                                        件
                                    </dd>
                                    <?php if (isset($val['order_spec_info']) && $val['order_spec_info']) { ?>
                                        <dd><strong><?= __('规格') ?>：</strong>&nbsp;&nbsp;<em><?= $val['order_spec_info']; ?></em></dd>
                                    <?php } ?>
                                    <?= $val['return_txt'] ?>
                                </dl>
                            </td>
                            <?php if ($key == 0) { ?>
                                <td class="bdl bdr order-info w500" rowspan="<?= $data['goods_cat_num']; ?>">
                                    <dl>
                                        <dt><?= __('运费') ?>：</dt>
                                        <dd><?= $data['shipping_info']; ?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?= __('发货备忘') ?>：</dt>
                                        <dd>
                                            <textarea name="deliver_explain" maxlength="50" cols="100" rows="2" class="w320 tip-t" title="<?= __('您可以输入一些发货备忘信息（仅卖家自己可见）') ?>"><?= $data['order_shipping_message']; ?></textarea>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt><?= __('给买家留言') ?>：</dt>
                                        <dd>
                                            <textarea name="order_seller_message" maxlength="50" id="order_seller_message" cols="100" rows="2" class="w320 tip-t" title="<?= __('您可以输入一些给买家的留言信息') ?>"><?= $data['order_seller_message'] ?></textarea>
                                        </dd>
                                    </dl>
                                </td>
                            <?php } ?>
                        </tr>
                        <!-- E商品列表 -->
                    <?php } ?>
                <?php } ?>
                <?php if ($data['order_invoice_id']) { ?>
                    <tr>
                        <td colspan="20" class="tl bdl bdr" style="padding:0px">
                            <div class="fapiao-cons clearfix">
                                <div class="left">
                                    <div class="dis-table">
                                        <div class="table-cell">
                                            <i class="iconfont"></i>
                                            <span><?= __('发票信息') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <dl>
                                        <dt><?= __('发票类型：') ?></dt>
                                        <dd><?= $data['invoice']['invoice_statu_txt'] ?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?= __('发票抬头：') ?></dt>
                                        <dd class="relative"><?= $data['invoice']['invoice_title'] ?></dd>
                                    </dl>
                                    <?php
                                        if ($data['invoice']['invoice_code']) {
                                            ?>
                                            <dl>
                                                <dt><?= __('企业税号：') ?></dt>
                                                <dd class="relative"><?= $data['invoice']['invoice_code'] ?></dd>
                                            </dl>
                                        <?php } ?>
                                    <dl>
                                        <dt><?= __('发票内容：') ?></dt>
                                        <dd><?= $data['invoice']['invoice_content'] ?></dd>
                                    </dl>
                                    <?php if ($data['invoice']['invoice_state'] != Order_InvoiceModel::INVOICE_NORMAL) { ?>
                                        <div class="more-infors">
                                            <div class="dis-table">
                                                <div class="table-cell">
                                                    <i class="iconfont"></i><?= __('展开发票完整信息') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="fapiao-company-infors">
                                                
                                                <?php if ($data['invoice']['invoice_state'] == Order_InvoiceModel::INVOICE_ELECTRON) { ?>
                                                    <div class="infors-module">
                                                        <h5><?= __('收票人信息') ?></h5>
                                                        <dl>
                                                            <dt><?= __('收票人手机：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_rec_phone'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('收票人邮箱：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_rec_email'] ?></dd>
                                                        </dl>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($data['invoice']['invoice_state'] == Order_InvoiceModel::INVOICE_ADDTAX) { ?>
                                                    <div class="infors-module">
                                                        <h5><?= __('公司信息') ?></h5>
                                                        <dl>
                                                            <dt><?= __('单位名称：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_company'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('纳税人识别码：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_code'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('注册地址：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_reg_addr'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('注册电话：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_reg_phone'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('开户银行：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_reg_bname'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('银行账户：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_reg_baccount'] ?></dd>
                                                        </dl>
                                                    </div>
                                                    <div class="infors-module">
                                                        <h5><?= _('收票人信息') ?></h5>
                                                        <dl>
                                                            <dt><?= __('发票内容：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_statu_txt'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= _('收票人姓名：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_rec_name'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('收票人手机：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_rec_phone'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('收票人省份：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_rec_province'] ?></dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><?= __('详细地址：') ?></dt>
                                                            <dd><?= $data['invoice']['invoice_goto_addr'] ?></dd>
                                                        </dl>
                                                    </div>
                                                <?php } ?>
                                            
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <script>
                                    $(function () {
                                        $(".more-infors").hover(function () {
                                            $(this).find(".fapiao-company-infors").show();
                                        }, function () {
                                            $(this).find(".fapiao-company-infors").hide();
                                        })
                                    })
                                </script>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="20" class="tl bdl bdr" style="padding:8px" id="address">
                        <input type="hidden" id="receive_name" name="receive_name" value="<?= $data['order_receiver_name']; ?>">
                        <input type="hidden" id="receive_mobile" name="receive_mobile" value="<?= $data['order_receiver_contact']; ?>">
                        <input type="hidden" id="receive_address" name="receive_address" value="<?= $data['order_receiver_address']; ?>">
                        <strong class="fl"><?= __('收货人信息') ?>：</strong>
                        <span id="buyer_address_span">
                            <?= $data['receiver_info']; ?>
                        </span>
                        <a href="javascript:void(0)" dialog_id="edit_buyer_address" data-order_receiver_name="<?= $data['order_receiver_name']; ?>" data-order_receiver_address="<?= $data['order_receiver_address']; ?>" data-order_receiver_contact="<?= $data['order_receiver_contact']; ?>" data-order_id="<?= $data['order_id']; ?>" class="ncbtn-mini fr bbc_seller_btns"><i class="icon-edit"></i><?= __('编辑') ?></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="step-title mt30"><em><?= __('第二步') ?></em><?= __('确认发货信息') ?></div>
            <div class="deliver-sell-info">
                <strong class="fl"><?= __('我的发货信息') ?>：</strong>
                <a href="javascript:void(0);" dialog_id="edit_seller_address" data-shop_id="<?= $data['shop_id']; ?>" class="ncbtn-mini fr bbc_seller_btns"><i class="icon-edit"></i><?= __('编辑') ?></a>
                <span id="seller_address_span" data="<?= $data['shipper'] ?>">
                    <?= $data['shipper_info']; ?>
                </span>
            </div>
            <input type="hidden" name="daddress_id" id="daddress_id" value="">
            <div class="step-title mt30"><em><?= __('第三步') ?></em><?= __('选择物流服务') ?></div>
            <div class="alert alert-success"><?= __('您可以通过"发货设置') ?>-&gt;<a href="<?= $default_express_url; ?>" target="_parent"><?= __('默认物流公司') ?></a>"<?= __('添加或修改常用货运物流') ?>。
            </div>
            <div class="tabmenu">
                <ul class="tab pngFix">
                    <li id="eli1" class="active bbc_seller_bg"><a href="javascript:void(0);" onclick="etab(1)"><?= __('自行联系物流公司') ?></a></li>
                    <li id="eli2" class="normal"><a href="javascript:void(0);" onclick="etab(2)"><?= __('无需物流运输服务') ?></a></li>
                </ul>
            </div>
            <table class="ncsc-default-table order" id="texpress1">
                <tbody>
                <tr>
                    <td class="bdl w150"><?= __('公司名称') ?></td>
                    <td class="w250"><?= __('物流单号') ?></td>
                    <!--<td class="tc"><?= __('备忘') ?></td>-->
                    <td class="bdr w90 tc"><?= __('操作') ?></td>
                </tr>
                <?php if (!empty($express_list)) { ?>
                    <?php foreach ($express_list as $key => $val) { ?>
                        <tr>
                            <td class="bdl" style="width: 151px;"><?= $val['express_name']; ?></td>
                            <?php if (strpos($val['express_name'], '顺丰') !== false) { ?>
                                <td class="bdl" style="width: 249px;" title="获取电子面单后,等待快递员上门揽件"><a nc_type="" id="getShip" href="javascript:void(0);" class="ncbtn bbc_seller_btns"><?= __('获取电子面单') ?></a></td>
                            <?php } else { ?>
                                <td class="bdl" style="width: 249px;"><input name="shipping_code" type="text" class="text w200 tip-r" title="<?= __('正确填写物流单号，确保快递跟踪查询信息正确') ?>" maxlength="20"></td>
                            <?php } ?>
                            <!--<td class="bdl gray"></td>-->
                            <td class="bdl bdr tc"><a nc_value="<?= $val['express_id']; ?>" href="javascript:void(0);" class="ncbtn bbc_seller_btns btn-width"><?= __('确认') ?></a></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
            <table class="ncsc-default-table order" id="texpress2" style="display:none">
                <tbody>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="bdl tr"><?= __('如果订单中的商品无需物流运送，您可以直接点击确认') ?></td>
                    <td class="bdr tl w400"> <a nc_type="eb" nc_value="e1000" href="javascript:void(0);" class="ncbtn bbc_seller_btns"><?= __('确认') ?></a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
    
    function etab(t) {
        if (t == 1) {
            $('#eli1').removeClass('normal').addClass('active bbc_seller_bg');
            $('#eli2').removeClass('active bbc_seller_bg').addClass('normal');
            $('#texpress1').css('display', '');
            $('#texpress2').css('display', 'none');
        } else {
            $('#eli1').removeClass('active bbc_seller_bg').addClass('normal');
            $('#eli2').removeClass('normal').addClass('active bbc_seller_bg');
            $('#texpress1').css('display', 'none');
            $('#texpress2').css('display', '');
        }
    }
    
    $(function () {
        $('.tabmenu > ul').find('li:lt(8)').remove();
        
        //设置发货左侧tab优化
        if (getQueryString("ctl") == "Seller_Trade_Order" && getQueryString("met") == "send") {
            var $leftLayout = $(".left-layout");
            $leftLayout.find(".active").removeClass("active");
            $leftLayout.find("li:eq(3) > a").addClass("active");
            
            $(".right-layout > .path").html(' <i class="iconfont icon-diannao"></i>商家管理中心<i class="iconfont icon-iconjiantouyou"></i>订单物流<i class="iconfont icon-iconjiantouyou"></i>发货');
        }
        
        //选择发货地址
        $('a[dialog_id="edit_seller_address"]').on('click', function () {
            var shipper = "<?=$data['shipper']?>";
            var order_id = $('#order_id').val(),
                shop_id = $(this).data('shop_id'),
                url = SITE_URL + '?ctl=Seller_Trade_Order&met=chooseSendAddress&typ=';
            if (shipper > 0) {
                $.dialog({
                    title: '<?=__('选择发货地址')?>',
                    content: 'url: ' + url + 'e&shop_id=' + shop_id,
                    height: 400,
                    width: 640,
                    lock: true,
                    drag: false,
                    data: {
                        callback: function (send_address, win) {
                            $.post(url + 'json&order_id=' + order_id, {send_address: send_address}, function (data) {
                                if (data.status == 200) {
                                    $('#seller_address_span').html(send_address.seller_address_span);
                                    $("#seller_address_span").attr('data', '1');
                                    parent.Public.tips({content: '设置成功！', type: 3});
                                    win.api.close();
                                } else {
                                    parent.Public.tips({content: '设置失败！', type: 1});
                                }
                            })
                        }
                    }
                })
            } else {
                window.location.href = SITE_URL + '?ctl=Seller_Trade_Deliver&met=deliverSetting&typ=e';
            }
        });
        
        //修改收货人信息
        $('a[dialog_id="edit_buyer_address"]').on('click', function () {
            var _this = $(this),
                buyer_address = $('#buyer_address_span').html();
            address_data = _this.data(),
                address_data.order_id = $('#order_id').val();
            
            $.dialog({
                title: '<?=__('选择收货地址')?>',
                content: 'url: ' + SITE_URL + '?ctl=Seller_Trade_Order&met=editBuyerAddress&typ=e',
                height: 250,
                width: 550,
                lock: true,
                drag: false,
                data: {
                    address_data: address_data,
                    callback: function (data) {
                        $('#buyer_address_span').html(data.receiver_info);
                        $('#receive_name').val(data.order_receiver_name);
                        $('#receive_mobile').val(data.order_receiver_contact);
                        $('#receive_address').val(data.order_receiver_address);
                        
                        _this.data('order_receiver_name', data.order_receiver_name);
                        _this.data('order_receiver_address', data.order_receiver_address);
                        _this.data('order_receiver_contact', data.order_receiver_contact);
                    }
                }
            })
        });
        
        //顺丰获取电子面单
        $('#getShip').on('click', function () {
            var seller_address_span = $("#seller_address_span").attr('data');

            if (seller_address_span == 0) {
                Public.tips({content: '<?=__('请设置发货信息：发货人、电话及地址')?>', type: 1});
            }else{
                var send_ship_info = {
                    receive_name: $('#receive_name').val(),
                    receive_mobile: $('#receive_mobile').val(),
                    receive_address: $('#receive_address').val(),
                    buyer_address_span: $('#buyer_address_span').html().replace(/&nbsp;/g, ' '),
                    seller_address_span: $('#seller_address_span').html().replace(/&nbsp;/g, ' '),
                    order_id: $('#order_id').val(),
                };
                // console.log($('#receive_mobile').val());
                $.post(SITE_URL + '?ctl=Seller_Trade_Order&met=sendShipInfo&typ=json', send_ship_info, function (data) {
                    // console.log(data);return false;
                    if (data.data.ResultCode == 100) {
                        $('#getShip').parent().html('<input name="shipping_code" type="text" class="text w200 tip-r" disabled maxlength="20" value="' + data.data.Order.LogisticCode + '">');
                    } else {
                        Public.tips({content: data.data.Reason + '注意:收货地址省、市、区之间有一个空格', type: 1});
                    }
                })
            }

        });
        
        //提交表单
        $(document).on('click', 'a[nc_value]', function () {
            seller_address_span = $("#seller_address_span").attr('data');

            // alert(seller_address_span);

            if (seller_address_span == 0) {
                Public.tips({content: '<?=__('请设置发货信息：发货人、电话及地址')?>', type: 1});
                return;
            }
            
            var $this = $(this),
                send_data = {
                    order_id: $('#order_id').val(),
                    order_shipping_code: $this.parents('tr').find('input[name="shipping_code"]').val(),
                    order_shipping_express_id: $this.attr('nc_value'),
                    order_shipping_message: $('textarea[name="deliver_explain"]').val(),
                    order_seller_message: $('textarea[name="order_seller_message"]').val()
                };
            //物流单号必填
            var ship_reg = /^[0-9a-zA-Z]{10,20}$/;
            if (send_data['order_shipping_code'] == '') {
                Public.tips({content: '<?=__('请填写物流单号')?>', type: 1});
                return;
            } else {
                if (!ship_reg.test(send_data['order_shipping_code'])) {
                    Public.tips({content: '<?=__('请填写正确的物流单号')?>', type: 1});
                    return;
                }
            }
            
            $.post(SITE_URL + '?ctl=Seller_Trade_Order&met=send&typ=json', send_data, function (data) {
                if (data.status == 200) {
                    //调用快递鸟订阅接口
                    //shop/api/subscribe.php?LogisticCode=运单号&ShipperCode=公司编码&OrderCode=订单号
                    $.post(BASE_URL + "/shop/api/subscribe.php", {"OrderCode": $('#order_id').val(), "ShipperCode": $this.attr('nc_value'), "LogisticCode": $this.parents('tr').find('input[name="shipping_code"]').val()}, function (da) {
                        console.info(da);
                    });
                    
                    
                    Public.tips({content: '<?=__('发货成功')?>', type: 3});
                    window.location.href = SITE_URL + '?ctl=Seller_Trade_Order&met=physical&typ=e';
                } else {
                    Public.tips({content: '<?=__('发货失败')?>', type: 1});
                }
            })
        });
        
        <?php if ( $data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ) { ?>
        /* 如果订单已经发货 初始化单据号 */
        $('#getShip').hide();
        $('#getShip').parent().html('<input name="shipping_code" type="text" class="text w200 tip-r" disabled maxlength="20" value="<?= $data['order_shipping_code'] ?>">');
        var $a = $('a.ncbtn[nc_value="<?= $data['order_shipping_express_id']; ?>"]');
        $a.parents('tr').find('input[name="shipping_code"]').val("<?= $data['order_shipping_code'] ?>");
        <?php } ?>
        
    })
</script>
