<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>

<style>
    .add-on {
        height: 28px;
    }

    .ncsc-form-checkbox-list {
        font-size: 0;
    }

    .ncsc-form-checkbox-list li {
        font-size: 12px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        margin-right: 30px;
    }

    .ncsc-form-checkbox-list li {
        width: 20%;
        margin: 0;
    }
    .ncsc-form-checkbox-list input {
        vertical-align: middle;
    }
</style>

<div class="form-style">
    <form method="post" id="form" class="nice-validator n-yellow" novalidate="novalidate">
        <dl>
            <dt><i>*</i><?=__('左偏移')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" value="<?php echo $shop_express_data['user_tpl_left']; ?>" name="store_waybill_left" id="store_waybill_left" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('打印模板左偏移')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('上偏移')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" value="<?php echo $shop_express_data['user_tpl_top']; ?>" name="store_waybill_top" id="store_waybill_top" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('打印模板上偏移')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i class="required">*</i><?=__('显示项目')?>：</dt>
            <dd>
                <ul class="ncsc-form-checkbox-list">
                    <li>
                        <input id="buyer_name" type="checkbox" class="checkbox mar0" name="data[buyer_name]" >
                        <label for="buyer_name"><?=__('收货人')?></label>
                    </li>
                    <li>
                        <input id="buyer_area" type="checkbox" class="checkbox mar0" name="data[buyer_area]" >
                        <label for="buyer_area"><?=__('收货人地区')?></label>
                    </li>
                    <li>
                        <input id="buyer_address" type="checkbox" class="checkbox mar0" name="data[buyer_address]" >
                        <label for="buyer_address"><?=__('收货人地址')?></label>
                    </li>
                    <li>
                        <input id="buyer_mobile" type="checkbox" class="checkbox mar0" name="data[buyer_mobile]" >
                        <label for="buyer_mobile"><?=__('收货人手机')?></label>
                    </li>
                    <li>
                        <input id="buyer_phone" type="checkbox" class="checkbox mar0" name="data[buyer_phone]" >
                        <label for="buyer_phone"><?=__('收货人电话')?></label>
                    </li>
                    <li>
                        <input id="seller_name" type="checkbox" class="checkbox mar0" name="data[seller_name]" >
                        <label for="seller_name"><?=__('发货人')?></label>
                    </li>
                    <li>
                        <input id="seller_area" type="checkbox" class="checkbox mar0" name="data[seller_area]" >
                        <label for="seller_area"><?=__('发货人地区')?></label>
                    </li>
                    <li>
                        <input id="seller_address" type="checkbox" class="checkbox mar0" name="data[seller_address]" >
                        <label for="seller_address"><?=__('发货人地址')?></label>
                    </li>
                    <li>
                        <input id="seller_phone" type="checkbox" class="checkbox mar0" name="data[seller_phone]" >
                        <label for="seller_phone"><?=__('发货人电话')?></label>
                    </li>
                    <li>
                        <input id="seller_company" type="checkbox" class="checkbox mar0" name="data[seller_company]" >
                        <label for="seller_company"><?=__('发货人公司')?></label>
                    </li>
                </ul>
                <p class="hint"><?=__('选中需要打印的项目，未勾选的将不会被打印')?></p>
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="<?=__('提交')?>">
                <input type="hidden" name="user_express_id" value="<?php echo $user_express_id; ?>" >
            </dd>
        </dl>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu').children().children('li:gt(1)').hide();
    $('.tabmenu').children().children('.active').show();

    $(function () {

        //初始化
        <?php if ( !empty($shop_express_data['user_tpl_item']) ) { ?>
        <?php foreach ( $shop_express_data['user_tpl_item'] as $key => $val ) { ?>

        <?php echo $val; ?>.checked = true;
        
        <?php } ?>
        <?php } ?>

        //验证
        $('#form').validator({
            theme: 'yellow_right',
            timely: true,

            rules: {

            },

            fields: {
                'store_waybill_left':'required;range[0.01~9999];',
                'store_waybill_top': 'required;range[0.01~9999];',
            },

            valid: function(form){
                //表单验证通过，提交表单到服务器
                $.post( SITE_URL + "?ctl=Seller_Trade_Waybill&met=waybillSetting&typ=json", $('#form').serialize(), function(data) {

                    if( data.status == 200 ) {
                        Public.tips({ content: '<?=__('保存成功')?>！', type: 3 });
                        setTimeout(function () {
                            window.location.href = SITE_URL + '?ctl=Seller_Trade_Waybill&met=waybillManage&typ=e';
                        }, 1000);
                    } else {
                        Public.tips({ content: '<?=__('保存失败')?>！', type: 1 });
                    }
                })
            }
        });

    })
</script>