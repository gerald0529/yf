<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
</head>

<div class="ncsc-vr-order-exchange">
    <dl>
        <dt><h3><?= __('电子兑换码') ?></h3></dt>
        <dd>
            <input class="vr-code" name="vr_code" type="text" id="vr_code" placeholder="<?= __('请输入买家提供的电子兑换码') ?>" maxlength="18">
            <label class="error" id="vr_code_waring" for="vr_code" style="display: inline;"></label>
            <span></span>
            <div class="ncsc-keyboard">
                <button onclick="demo(this,1)">1</button>
                <button onclick="demo(this,1)">2</button>
                <button onclick="demo(this,1)">3</button>
                <button onclick="demo(this,1)">4</button>
                <button onclick="demo(this,1)">5</button>
                <button onclick="demo(this,1)">6</button>
                <button onclick="demo(this,1)">7</button>
                <button onclick="demo(this,1)">8</button>
                <button onclick="demo(this,1)">9</button>
                <button onclick="demo(this,1)">0</button>
                <button class="cn" onclick="demo(this,2)"><?= __('清除') ?></button>
                <button class="cn" onclick="demo(this,3)"><?= __('后退') ?></button>
                <label class="enter-border">
                    <input type="button" id="_submit" class="enter" value="<?= __('提交验证') ?>">
                </label>
            </div>
            <p class="hint"><?= __('请输入买家提供的兑换码，核对无误后提交，每个兑换码抵消单笔消费') ?>。</p>
        </dd>
    </dl>
    
    <div class="bottom">
    
    </div>
</div>
<table class="ncsc-default-table order">
    <thead>
    <tr>
        <th class="w10"></th>
        <th colspan="20" class="tl"></th>
    </tr>
    <tr>
        <th class="w10"></th>
        <th class="w150"><?= __('兑换码') ?></th>
        <th colspan="2"><?= __('商品') ?></th>
        <th><?= __('订单号') ?></th>
        <th><?= __('下单留言') ?></th>
    </tr>
    </thead>
    <tbody id="order_panel"></tbody>
</table>


<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
    $('.tabmenu > ul').find('li:eq(7)').remove();
    
    function demo(obj, tip) {
        document.onkeydown = function mykeyDown(e) {
            e = e || event;
            if (e.keyCode == 13) {
                return;
            }
            return;
        }
        
        if (tip == 1) {
            var con = document.getElementById('vr_code').value;
            document.getElementById('vr_code').value = con + obj.innerHTML;
        } else if (tip == 2) {
            document.getElementById('vr_code').value = "";
        } else if (tip == 3) {
            var con = document.getElementById('vr_code').value;
            document.getElementById('vr_code').value = con.slice(0, -1);
        }
    }
    
    $(function () {
        $('.tabmenu').find('li:eq(4)').hide();
        
        $('#_submit').on('click', function () {
            exPost();
        });
        
        Tpl = "<tr id=\"PEND_ID\"><td class=\"bdl\"></td>\n";
        Tpl += "<td>NC_CODE</td>\n";
        Tpl += "<td class=\"w70\"><div class=\"ncsc-goods-thumb\"><a target=\"_blank\" href=\"NC_GOODS_URL\"><img src=\"NC_IMG60\"></a></div></td>\n";
        Tpl += "<td class=\"tl\"><a href=\"NC_GOODS_URL\" target=\"_blank\">NC_GOODS_NAME</a></td>\n";
        Tpl += "<td><a target=\"_blank\" href=\"NC_GOODS_URL\">NC_ORDER_SN</a></td>\n";
        Tpl += "<td class=\"bdr\">NC_ORDER_MSG</td></tr>\n";
        
        function exPost() {
            $('#vr_code_waring').html('');
            $.getJSON(SITE_URL + '?ctl=Seller_Trade_Order&met=virtualExchange&typ=json&vr_code=' + $("#vr_code").val(), null, function (data) {
                if (data == null) return false;
                if (data.status == 250) {
                    Public.tips({content: data.msg, type: 1});
                    $('#vr_code_waring').show().html(data.error);
                    return false;
                }
                Public.tips({content: data.msg, type: 3});
                content = Tpl.replace(/PEND_ID/g, $("#vr_code").val());
                content = content.replace(/NC_CODE/g, $("#vr_code").val());
                content = content.replace(/NC_GOODS_URL/g, data.data.goods_url);
                content = content.replace(/NC_IMG240/g, data.data.img_240);
                content = content.replace(/NC_IMG60/g, data.data.img_60);
                content = content.replace(/NC_GOODS_NAME/g, data.data.goods_name);
                content = content.replace(/NC_ORDER_URL/g, data.data.order_url);
                content = content.replace(/NC_ORDER_SN/g, data.data.order_sn);
                content = content.replace(/NC_ORDER_MSG/g, data.data.buyer_msg || '');
                $('#order_panel').prepend(content);
                $('#vr_code').val('').focus();
            });
        }
    })
</script>