<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=addVoucherTemp">
        <dl>
            <dt class="sale_width_reset"><i>*</i><?=__('代金券名称')?>：</dt>
            <dd>
                <input type="text" name="voucher_t_title" class="text w200"/>
            </dd>
        </dl>
        <dl>
            <dt><?=__('店铺分类')?>：</dt>
            <dd>
                <select id="shop_class" name="shop_class" class="w70 vt valid">
                    <option value="0"><?=__('请选择')?></option>
                    <?php  foreach($data['shop_class'] as $key=>$value){ ?>
                        <option value="<?=$value['shop_class_id']?>"><?=$value['shop_class_name']?></option>
                    <?php } ?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt class="achieve_width_reset"><i>*</i><?=__('领取方式')?>：</dt>
            <dd>
                <select id="voucher_t_access_method" name="voucher_t_access_method" class="w70 vt valid">
                        <option value><?=__('请选择')?></option>
                    <?php
                        foreach($data['access_method'] as $key=>$value)
                        { ?>
                            <option value="<?=($key)?>" ><?=($value)?></option>
                        <?php }?>
                    ?>
                </select>
                <p class="hint"><?=__('“积分兑换”时会员可以在积分中心用积分进行兑换')?>；</p>
                <p class="hint"><?=__('“免费领取”时会员可以点击店铺的代金券推广广告领取代金券')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('有效期')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="voucher_t_end_date" id="end_date" class="text w70"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint"><?=__('有效期不能大于')?><?=$combo['combo_end_time']?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('面额')?>：</dt>
            <dd>
                <select id="voucher_t_price" name="voucher_t_price" class="w70 vt valid">
                        <?php
                            foreach($data['denomination'] as $key=>$value)
                            {
                        ?>
                        <option value="<?=($value['voucher_price_id'])?>"><?=($value['voucher_price'])?></option>
                        <?php  } ?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('可发放总数')?>：</dt>
            <dd>
                <input type="text" name="voucher_t_total" class="text w70"/>
                <p class="hint"></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('每人限领')?>：</dt>
            <dd>
                <select id="voucher_t_eachlimit" name="voucher_t_eachlimit" class="w70 vt valid">
                    <?php
                        for($i=0;$i<=Web_ConfigModel::value('promotion_voucher_buyertimes_limit');$i++)
                        {
                    ?>
                    <option value="<?=($i)?>"><?=($i>0?$i.'张':'不限')?></option>
                    <?php  }?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('消费金额')?>：</dt>
            <dd>
                <div class="exchange_price">
                    <input type="text" name="voucher_t_limit" id="voucher_price_limit" class="text" style="width:78px;"/><em><?=Web_ConfigModel::value('monetary_unit')?></em>
					<p class="hint"><?=__('店铺单笔订单满足多少元方可使用此代金券')?></p>
                </div>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('会员级别')?>：</dt>
            <dd>
                <select id="voucher_t_user_grade_limit" name="voucher_t_user_grade_limit" class="w100 vt valid">
                    <?php
                        foreach($data['user_grade'] as $key=>$value)
                        {
                    ?>
                    <option value="<?=($value['user_grade_id'])?>" ><?=($value['user_grade_name'])?></option>
                    <?php }?>
                </select>
                <p class="hint"><?=__('当会员兑换代金券时，需要达到该级别或者以上级别后才能兑换领取')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('代金券描述')?>：</dt>
            <dd>
                <textarea name="voucher_t_desc" class="text textarea w450"></textarea>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('代金券图片')?>：</dt>
            <dd>
                <div id="" class="ncsc-upload-thumb voucher-pic">
                    <p><i class="icon-picture"></i></p>
                </div>
                <p class="pic image_review" style="width:200px;height:200px;" hidden="true">
                    <img id="image_review" src="" height="200" width="200" />
                </p>
                <p class="upload-button">
                    <input type="hidden" id="voucher_t_customimg" name="voucher_t_customimg" value="" />
                    <div  id='image_upload' class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan" ></i><?=__('图片上传')?></div>
                </p>
                <p class="hint"><?=__('此处图片将在代金券模块使用，建议尺寸200*200px')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交"  />
                <input type="hidden" name="act" value="save" />
            </dd>
        </dl>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
    $(document).ready(function(){
        var combo_end_time = $.trim("<?=$combo['combo_end_time']?>");
        var maxdate =  new Date(Date.parse(combo_end_time.replace(/-/g, "/")));

		var currDate = new Date();
		var date = currDate.getDate();
		date = date + 1;
		currDate.setDate(date);
		
        $('#end_date').datetimepicker({
            controlType: 'select',
            timepicker:false,
            format:'Y-m-d',
            minDate:currDate,
            maxDate:maxdate
        });
        //图片上传
        $('#image_upload').on('click', function () {
            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                data: {width:200,height:200 , callback: callback },    // 需要截取图片的宽高比例
                width: '800px',
                lock: true
            })
        });

        //获取选择的代金券金额
        voucher_price = $("#voucher_t_price option:eq(0)").html();
        $("#voucher_t_price").change(function(){
            voucher_price = parseFloat($(this).find("option[value="+ $(this).val() +"]").html());
        });

        //获取输入的消费金额
        $('#voucher_price_limit').blur(function(){
            var price_limit = parseFloat($(this).val());
            if(price_limit < voucher_price)
            {
                $(this).val('');
                Public.tips.error('消费金额必须大于等于代金券金额');
                return false;
            }
        })

        function callback( respone , api ) {
            $('#image_review').attr('src', respone.url);
            $('.image_review').show();
            $('#voucher_t_customimg').attr('value', respone.url);
            api.close();
        }

        if ( window.isIE8 ) {
            $('#image_upload').off("click");

            new UploadImage({
                thumbnailWidth: 200,
                thumbnailHeight: 60,
                imageContainer: '#image_review',
                uploadButton: '#image_upload',
                inputHidden: '#voucher_t_customimg'
            });
        }

        $('#form').validator({
            debug:true,
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
			rules: {
                //自定义规则,大于当前时间，如果通过返回true，否则返回错误消息
                greaterThanStartDate : function(element, param, field)
                {
                    var date1 = new Date(Date.parse((element.value).replace(/-/g, "/")));//开始时间
                    param = JSON.parse(param);
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/")));	//套餐开始时间

                    return date1 > date2 || "活动开始时间不能小于"+ param;
                },
                //自定义规则，小于套餐活动结束时间
                lessThanEndDate  : function(element, param, field)
                {
                    var date1 = new Date(Date.parse((element.value).replace(/-/g, "/")));//选择的结束时间
                    param = JSON.parse(param);
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/")));  //套餐结束时间
                    return date1 < date2 || "活动结束时间不能大于"+ param;
                }
            },
            fields: {
                'voucher_t_title': 'required;length[1~15]',
                'voucher_t_access_method': 'required;integer[+]',
                'voucher_t_end_date': 'required;lessThanEndDate["<?=$combo['combo_end_time']?>"]',
                'voucher_t_price': 'required;',
                'voucher_t_total': 'required;integer[+]',
                'voucher_t_eachlimit': 'required;integer[+0];range[0~<?=Web_ConfigModel::value('promotion_voucher_buyertimes_limit')?>]',
                'voucher_t_limit': 'required;integer[+];',
                'voucher_t_user_grade_limit': 'required;integer[+]',
                'voucher_t_desc': 'required;',
                'voucher_t_customimg': 'required;'
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_Voucher&met=addVoucherTemp&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            Public.tips.success('操作成功!');
                            location.href="index.php?ctl=Seller_Promotion_Voucher&met=index&typ=e"; //成功后跳转
                        }
                        else
                        {
                            Public.tips.error('操作失败,'+ e.msg);
                        }
                        me.holdSubmit(false);
                    }
                });
            }
        });
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

