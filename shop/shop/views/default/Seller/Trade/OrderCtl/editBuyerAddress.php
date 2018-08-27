<!DOCTYPE HTML>
<html>
<head>
    <link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <style>
        textarea, .textarea {
            font: 12px/18px Arial;
            color: #777;
            background-color: #FFF;
            vertical-align: top;
            display: inline-block;
            height: 54px;
            padding: 4px;
            border: solid 1px #CCD0D9;
            outline: 0 none;
        }
    </style>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js"></script>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div class="adds">
            <div id="warning"></div>
            <form id="address_form">
                <input type="hidden" id="order_id" name="order_id" value="">
                <dl>
                    <dt class="required"><?=__('收货人')?>：</dt>
                    <dd>
                        <input type="text" class="text wp55" name="order_receiver_name" id="order_receiver_name" value="">
                    </dd>
                </dl>
                <dl>
                    <dt class="required"><?=__('手机')?>：</dt>
                    <dd>
                        <input type="text" class="text wp55" name="order_receiver_contact" id="order_receiver_contact" value="">
                    </dd>
                </dl>
                <dl>1
                    <dt class="required"><?=__('收货地区')?>：</dt>
                    <dd id="address_info">
                        <input type="text" name="address_area" id="t" value="<?=@$district['shipping_address_area']?>" />
                        <input id="edit_address_info" type="button" value="编辑"  class="btn-edit">
                    </dd>
                    <dd id="choose_address_info" style="display:none">
                        <input type="hidden" name="address_area" id="t" value="<?=@$district['shipping_address_area']?>" />
                        <select id="select_1" name="select_1" onChange="district(this);">
                            <option value=""><?=__('--请选择--')?></option>
                            <?php foreach($district['items'] as $key=>$val){ ?>
                                <option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
                            <?php } ?>
                        </select>
                        <select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
                        <select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
                    </dd>
                </dl>
                <dl style="margin-top:0.5rem">
                    <dt class="required"><?=__('详细地区')?>：</dt>
                    <dd>
                        <textarea style="resize: none;" name="order_receiver_address" id="order_receiver_address" class="wp55"></textarea>
                        <div id='maxLength' class="maxLength"></div>
                    </dd>
                </dl>
            </form>
        </div>
    </div>

</div>

</body>
</html>
<script>
    var SITE_URL = "<?=Yf_Registry::get('url')?>";
    api = frameElement.api,
    address_data = api.data.address_data;
    callback = api.data.callback;

    $( function () {
        console.info(address_data);
        var address_info = address_data.order_receiver_address.split(" ");
        $('#order_id').val(address_data.order_id);
        $('#order_receiver_name').val(address_data.order_receiver_name);
        $('#order_receiver_contact').val(address_data.order_receiver_contact);
        $('#order_receiver_address').html(address_info[3]);
        if(address_info) {
            $("input[name='address_area']").val(address_info[0] + ' ' + address_info[1] + ' ' + address_info[2]);
            $('#edit_address_info').click(function(){
                $("#address_info").css('display', 'none');
                $("#choose_address_info").css('display', 'inline');
            })
        }

        //验证
        $('#address_form').validator({
            theme: 'yellow_right',
            timely: true,
            ignore: ':hidden',
            fields: {
                'order_receiver_name':    'required;length[2~20]',
                'order_receiver_contact': 'required;mobile;',
                'select_1':  'required;',
                'select_2':  'required;',
                'select_3':  'required;',
                'order_receiver_address': 'required;length[0~45]'
            },

            valid: function(form) {
                var data = {
                    order_id: $("#order_id").val(),
                    order_receiver_name: $("#order_receiver_name").val(),
                    order_receiver_contact: $("#order_receiver_contact").val(),
                    address_info:$("#t").val(),
                    order_receiver_address:$("#order_receiver_address").val()
                };
                $.post(parent.SITE_URL + '?ctl=Seller_Trade_Order&met=editBuyerAddress&typ=json',data, function (data) {
                    if ( data.status == 200 ) {
                        parent.Public.tips( { content:'操作成功！', type: 3 } );
                        callback(data.data);
                        api.close();
                    } else {
                        parent.Public.tips( { content:'操作失败！', type: 1 } );
                    }
                })
            }
        });

        api.button({
            id: "confirm",
            name: '<?=__('确定')?>',
            focus: !0,
            callback: function() {
                $('#address_form').trigger("submit");
                return false;
            }
        }, {
            id: "cancel",
            name: '<?=__('取消')?>'
        });
    })
</script>
<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
