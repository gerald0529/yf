<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    <script type="text/javascript" src="<?= $this->view->js ?>/return_add.js"></script>
    <div class="aright">
        <div class="member_infor_content">
            <div class="div_head  tabmenu clearfix">
                <ul class="tab pngFix clearfix">
                    <li class="active">
                        <a><?= $data['text'] ?></a>
                    </li>
                </ul>
            </div>
            <div class="ncm-flow-layout" id="ncmComplainFlow">
                <div class="ncm-flow-container">
                    
                    <div class="ncm-flow-step" style="text-align: center;">
                        <dl id="state_new" class="step-first current1">
                            <dt><?= __('买家申请') ?><?= $data['text'] ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        <dl id="state_appeal">
                            <dt><?= __('商家处理') ?><?= $data['text'] ?><?= __('申请') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        
                        <?php if ($data['class'] !== 'refund') { ?>
                            <dl id="state_talk">
                                <dt><?= __('买家') ?><?= $data['text'] ?><?= __('给商家') ?></dt>
                                <dd class="bg"></dd>
                            </dl>
                        <?php } ?>
                        
                        <dl id="state_handle">
                            <dt><?php if ($data['return_goods']) {
                                    echo __("确认收货，");
                                } ?><?= __('平台审核') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                    </div>
                    <div class="ncm-default-form">
                        <h3><?= __('买家') ?><?= $data['text'] ?><?= __('申请') ?></h3>
                        <form id="form" action="#" method="post">
                            <!-- S 商品信息 -->
                            <ul>
                                <li class="goods_list fot14">
                                    <div class="clearfix pdtb20 ">
                                        <div class="ncm-goods-thumb-mini inblock">
                                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $data['goods']['goods_id'] ?>"> <img
                                                        src="<?= $data['goods']['goods_image'] ?>"></a>
                                            <a target="_blank" class="good-nm" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $data['goods']['goods_id'] ?>"><?= $data['goods']['goods_name'] ?></a>
                                        </div>
                                        <div width="100" hei class="inblock">
                                            <span class="unite-pric"><?= format_money($data['goods']['order_goods_payment_amount']) ?></span><span class="price-num"><b><?= __('x') ?></b><?= $data['goods']['order_goods_num'] ?></span>
                                        </div>
                                        <div class="mt10">
                                            <?php if ($data['goods']['order_spec_info']) {
                                                $s = __("规格：");
                                                foreach ($data['goods']['order_spec_info'] as $k => $v) {
                                                    $s .= $v;
                                                }
                                                echo $s;
                                            } ?>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="clearfix mrb20">
                                        <div class="inblock mr30">
                                            <dt class="inblock"><?= $data['text'] ?><?= __('原因：') ?></dt>
                                            <dd class="inblock">
                                                <select class="w200" name="return_reason_id">
                                                    <?php foreach ($data['reason'] as $v) { ?>
                                                        <option value="<?= $v['order_return_reason_id'] ?>"><?= $v['order_return_reason_content'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </dd>
                                        </div>
                                        <div class="inblock">
                                            <i><?= $data['text'] ?><?= __('数量：') ?></i>
                                            <div class="refundnum inblock">
                                                <input type="hidden" class="gprice" value="<?= $data['goods']['order_goods_payment_amount'] ?>">
                                                <input type="hidden" class="gnum" value="<?= $data['return_goods_nums'] ?>">
                                                <input type="hidden" class="all_gnum" value="<?= $data['nums'] ?>">
                                                <a class="<?php if ($data['return_goods_nums'] == 1) { echo "no_"; } ?>reduce numsclick" style="border-right: none;left: 0px;">-</a>
                                                    <input class="refundnums" data-max="<?= ($data['return_goods_nums']) ?>" name="nums" value="<?= ($data['return_goods_nums']) ?>" style="text-align:center;border: 1px solid #ccc;width: 30px;height:24px;margin-left: -5px;">
                                                <a class="no_add numsclick" style="border-left: none;left: 54px;margin-left: -4px;">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- E 商品信息 -->
                            <input type="hidden" name="order_id" value="<?= $data['order_id'] ?>">
                            <input type="hidden" name="goods_id" value="<?= $data['goods_id'] ?>">
                            <div class="mrb20">
                                <dt class="inblock"><?= $data['text'] ?><?= __('金额：') ?></dt>
                                <dd class="inblock" id="return_cash"><?= format_money($data['return_cash']) ?></dd>
                                <p class="shipping">
                                    <?php if (($data['nums'] == $data['return_goods_nums']) && ($data['order']['order_shipping_fee'] > 0) && $data['order']['order_status'] < Order_StateModel::ORDER_FINISH) {
                                        echo __('（包含运费）');
                                    } ?>
                                </p>
                                <input type="hidden" name="return_cash" id="cash" value="<?= $data['return_cash'] ?>">
                            </div>
                            
                            <div>
                                <dt class="inblock vertop"><?= $data['text'] ?><?= __('说明：') ?></dt>
                                <dd class="inblock">
                                    <textarea id="return_message" name="return_message" maxlength="200" class="w400 textarea_text"></textarea>
                                </dd>
                                <dd style="width:380px; ">
                                <span style="float:right">
                                    <em id="msg_num">0</em>
                                    <em>/ 200</em>
                                </span>
                                </dd>
                            </div>
                            
                            <dl class="foot">
                                <dt>&nbsp;</dt>
                                <dd>
                                    <label id="handle_submit" class="submit-border bbc_btns">
                                        <input type="button" value="<?= __('确认提交') ?>" class="submit bbc_btns">
                                    </label>
                                </dd>
                            
                            </dl>
                        </form>
                    </div>
                </div>
                <div class="ncm-flow-item">
                    <div class="title"><?= __('相关商品交易') ?></div>
                    <!-- <div class="item-goods">
                    <?php foreach ($data['order_goods'] as $v) { ?>
                        <dl>
                            <dt>
                            <div class="ncm-goods-thumb-mini"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $v['goods_id'] ?>"> <img
                                        src="<?= $v['goods_image'] ?>"></a></div>
                            </dt>
                            <dd><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $v['goods_id'] ?>"><?= $v['goods_name'] ?></a>
                                <?= format_money($v['order_goods_payment_amount']) ?> * <?= $v['order_goods_num'] ?> <font
                                    color="#AAA">(<?= __('数量') ?>)</font> <span></span></dd>
                        </dl>
                    <?php } ?>
                </div> -->
                    
                    
                    <div class="item-order">
                        <dl>
                            <dt><?= __('运费：') ?></dt>
                            <dd><strong><?= format_money($data['order']['order_shipping_fee']) ?></strong>
                            </dd>
                        </dl>
                        <dl>
                            <dt><?= __('退款金额：') ?></dt>
                            <dd><strong><?= format_money($data['return_limit']) ?></strong>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt><?= __('订单总额：') ?></dt>
                            <dd><strong><?= format_money($data['order']['order_payment_amount']) ?></strong>
                            </dd>
                        </dl>
                        <dl class="line">
                            <dt><?= __('订单编号：') ?></dt>
                            <dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order']['order_id'] ?>" target="_blank"><?= $data['order']['order_id'] ?> <a
                                            href="javascript:void(0);" class="a"><?= __('更多') ?><i class="iconfont icon-iconjiantouxia"></i>
                                        <div class="more"><span class="arrow"></span>
                                            <?= __('收货人信息') ?>
                                            <ul>
                                                <li><?= __('收货人：') ?><?= $data['order']['order_receiver_name'] ?></li>
                                                <li><?= __('收货地址：') ?><?= $data['order']['order_receiver_address'] ?></li>
                                                <li><?= __('联系电话：') ?><?= $data['order']['order_receiver_contact'] ?></li>
                                            </ul>
                                            <?= __('收票人信息') ?>
                                            <ul>
                                                <li><?= __('订单编号：') ?><?= $data['order']['order_id'] ?></li>
                                                <li><?= __('付款单号：') ?><?= $data['order']['payment_other_number'] ?></li>
                                                <li><?= __('付款方式：') ?><span><?= $data['order']['payment_name'] ?></li>
                                                <li><?= __('下单时间：') ?><span><?= $data['order']['order_create_time'] ?></span></li>
                                                <li><?= __('付款时间：') ?><span><?= $data['order']['payment_time'] ?></span></li>
                                                
                                                <!--<li><? /*=__('订单总额：')*/ ?><span><? /*= format_money($data['order']['order_payment_amount']) */ ?></span></li>
                                            <li><? /*=__('退款金额：')*/ ?><? /*= format_money($data['return_limit']) */ ?></span></li>-->
                                            </ul>
                                        </div>
                                    </a></dd>
                        </dl>
                        <dl class="line">
                            <dt><?= __('商家：') ?></dt>
                            <dd><?= $data['order']['shop_name'] ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var return_cash = '<?=$data['return_cash']?>';
            var gnum = "<?= $data['return_goods_nums'] ?>";  //商品最多可退换数量
            var shipping_fee = "<?= $data['order']['order_shipping_fee'] ?>";  //订单最多可退换数量
            var all_gnum = "<?= $data['nums'] ?>";  //订单最多可退换数量
            var order_status = "<?= $data['order']['order_status'] ?>";  //订单状态
            
            //控制输入框字数
            $("#return_message").on('keyup', function () {
                var msg = $('textarea[name="return_message"]').val();
                $("#msg_num").html(msg.length);
            });
            
            
            function sprintf() {
                var regex = /%%|%(\d+\$)?([\-+'#0 ]*)(\*\d+\$|\*|\d+)?(?:\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
                var a = arguments;
                var i = 0;
                var format = a[i++];
                
                var _pad = function (str, len, chr, leftJustify) {
                    if (!chr) {
                        chr = ' '
                    }
                    var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0).join(chr);
                    return leftJustify ? str + padding : padding + str
                };
                
                var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
                    var diff = minWidth - value.length;
                    if (diff > 0) {
                        if (leftJustify || !zeroPad) {
                            value = _pad(value, minWidth, customPadChar, leftJustify)
                        } else {
                            value = [
                                value.slice(0, prefix.length),
                                _pad('', diff, '0', true),
                                value.slice(prefix.length)
                            ].join('')
                        }
                    }
                    return value
                };
                
                var _formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
                    // Note: casts negative numbers to positive ones
                    var number = value >>> 0;
                    prefix = (prefix && number && {
                        '2': '0b',
                        '8': '0',
                        '16': '0x'
                    }[base]) || '';
                    value = prefix + _pad(number.toString(base), precision || 0, '0', false);
                    return justify(value, prefix, leftJustify, minWidth, zeroPad)
                };
                
                // _formatString()
                var _formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
                    if (precision !== null && precision !== undefined) {
                        value = value.slice(0, precision)
                    }
                    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar)
                };
                
                // doFormat()
                var doFormat = function (substring, valueIndex, flags, minWidth, precision, type) {
                    var number, prefix, method, textTransform, value;
                    
                    if (substring === '%%') {
                        return '%'
                    }
                    
                    // parse flags
                    var leftJustify = false;
                    var positivePrefix = '';
                    var zeroPad = false;
                    var prefixBaseX = false;
                    var customPadChar = ' ';
                    var flagsl = flags.length;
                    var j;
                    for (j = 0; j < flagsl; j++) {
                        switch (flags.charAt(j)) {
                            case ' ':
                                positivePrefix = ' ';
                                break;
                            case '+':
                                positivePrefix = '+';
                                break;
                            case '-':
                                leftJustify = true;
                                break;
                            case "'":
                                customPadChar = flags.charAt(j + 1);
                                break;
                            case '0':
                                zeroPad = true;
                                customPadChar = '0';
                                break;
                            case '#':
                                prefixBaseX = true;
                                break
                        }
                    }
                    
                    // parameters may be null, undefined, empty-string or real valued
                    // we want to ignore null, undefined and empty-string values
                    if (!minWidth) {
                        minWidth = 0
                    } else if (minWidth === '*') {
                        minWidth = +a[i++]
                    } else if (minWidth.charAt(0) === '*') {
                        minWidth = +a[minWidth.slice(1, -1)]
                    } else {
                        minWidth = +minWidth
                    }
                    
                    // Note: undocumented perl feature:
                    if (minWidth < 0) {
                        minWidth = -minWidth;
                        leftJustify = true
                    }
                    
                    if (!isFinite(minWidth)) {
                        throw new Error('sprintf: (minimum-)width must be finite')
                    }
                    
                    if (!precision) {
                        precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined
                    } else if (precision === '*') {
                        precision = +a[i++]
                    } else if (precision.charAt(0) === '*') {
                        precision = +a[precision.slice(1, -1)]
                    } else {
                        precision = +precision
                    }
                    
                    // grab value using valueIndex if required?
                    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];
                    
                    switch (type) {
                        case 's':
                            return _formatString(value + '', leftJustify, minWidth, precision, zeroPad, customPadChar);
                        case 'c':
                            return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
                        case 'b':
                            return _formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                        case 'o':
                            return _formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                        case 'x':
                            return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                        case 'X':
                            return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
                                .toUpperCase();
                        case 'u':
                            return _formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
                        case 'i':
                        case 'd':
                            number = +value || 0;
                            // Plain Math.round doesn't just truncate
                            number = Math.round(number - number % 1);
                            prefix = number < 0 ? '-' : positivePrefix;
                            value = prefix + _pad(String(Math.abs(number)), precision, '0', false);
                            return justify(value, prefix, leftJustify, minWidth, zeroPad);
                        case 'e':
                        case 'E':
                        case 'f': // @todo: Should handle locales (as per setlocale)
                        case 'F':
                        case 'g':
                        case 'G':
                            number = +value;
                            prefix = number < 0 ? '-' : positivePrefix;
                            method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
                            textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
                            value = prefix + Math.abs(number)[method](precision);
                            return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
                        default:
                            return substring
                    }
                };
                
                return format.replace(regex, doFormat)
            }
            
            $(document).ready(function () {
                //退货
                <?php if($data['class'] == 'return'){?>
                var return_url = SITE_URL + "?ctl=Buyer_Service_Return&met=index&state=2";
                <?php }else{ ?>
                <?php if($data['order']['order_is_virtual']){?>
                var return_url = SITE_URL + "?ctl=Buyer_Service_Return&met=index&state=3";
                <?php }else{ ?>
                var return_url = SITE_URL + "?ctl=Buyer_Service_Return&met=index";
                <?php }?>
                <?php }?>
                
                var submit_trigger = true;
                
                $('#form').validator({
                    ignore: ':hidden',
                    theme: 'yellow_right',
                    timely: 1,
                    stopOnError: false,
                    rules: {
                        cash: function (element, params) {
                            var cash = parseFloat(element.value);
                            if (cash < 0 || cash ><?=$data['cash_limit']?>) {
                                return false;
                            } else {
                                return true;
                            }
                        },
                        money: [/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/, '<?=__('请输入金额')?>']
                    },
                    messages: {
                        cash: "<?=__('退款金额不得大于订单金额')?>"
                    },
                    fields: {
                        return_message: 'required',
                        return_cash: 'required;cash;money;'
                    },
                    valid: function (form) {
                        //表单验证通过，提交表单
                        $.ajax({
                            url: SITE_URL + '?ctl=Buyer_Service_Return&met=addReturn&typ=json',
                            data: $("#form").serialize(),
                            success: function (a) {
                                if (a.status == 200) {
                                    Public.tips.success('<?=__('操作成功！')?>');
                                    location.href = return_url;
                                } else {
                                    submit_trigger = true;
                                    Public.tips.error('<?=__('操作失败！')?>');
                                }
                            }
                        });
                    }
                }).on("click", "#handle_submit", function (e) {
                    submit_trigger ? $(e.delegateTarget).trigger("validate") : submit_trigger = false;
                });
                var isBackShippingCost = <?= empty($data['is_back_shipping_cost']) ? 0 : 1 ?>;
                if (isBackShippingCost) {
                    var order_shipping_fee = <?= $data['order']['order_shipping_fee']; ?>;
                    $("input[name='nums']").on("change", function () {
                        if (this.value == order_shipping_fee) {
                            var now_amount = $("#cash").val();
                            $("#return_cash, #cash").val((now_amount + order_shipping_fee).toFixed(2));
                        }
                    });
                }
            });
        </script>
    </div>
<?php
    include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>