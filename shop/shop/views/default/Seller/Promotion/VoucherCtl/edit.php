<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=editVoucherTemp&typ=e">
        <input type="hidden" name="voucher_t_id" value="<?=$data['voucher_t_id']?>">
        <dl>
            <dt><i>*</i><?=__('代金券名称')?>：</dt>
            <dd>
                <input type="text" name="voucher_t_title" class="text w200" value="<?=@$data['voucher_t_title']?>"/>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('店铺分类')?>：</dt>
            <dd>
                <select id="shop_class" name="shop_class"  class="w70 vt valid">
                    <option value><?=__('请选择')?></option>
                    <?php  foreach($data['shop_class'] as $key=>$value){ ?>
                        <option value="<?=$value['shop_class_id']?>" <?=$data['shop_class_id']==$value['shop_class_id']?'selected':''?>><?=$value['shop_class_name']?></option>
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
                        {
                            ?>
                            <option value="<?=$key?>" <?=$key==@$data['voucher_t_access_method']?"selected":""?>> <?=($value)?></option>
                        <?php }?>
                </select>
                <p class="hint"><?=__('“积分兑换”时会员可以在积分中心用积分进行兑换')?>；</p>
                <p class="hint"><?=__('“免费领取”时会员可以点击店铺的代金券推广广告领取代金券')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('有效期')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="voucher_t_end_date" id="end_date" value="<?=@$data['voucher_t_end_date']?>" class="text w70"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint"><?=__('有效期应在套餐有效期内，使用的套餐有效期截止到')?> <?=$combo['combo_end_time']?></p>
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
                        <option value="<?=$value['voucher_price_id']?>" <?=@$data['voucher_t_price']==$value['voucher_price']?'selected':''?>><?=$value['voucher_price']?></option>
                        <?php  } ?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('可发放总数')?>：</dt>
            <dd>
                <input type="text" name="voucher_t_total" value="<?=@$data['voucher_t_total']?>" class="text w70"/>
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
                    <option value="<?=($i)?>" <?=$i==@$data['voucher_t_eachlimit']?'selected':''?>><?=($i>0)?$i.'张':'不限'?></option>
                    <?php  }?>
                </select>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('消费金额')?>：</dt>
            <dd>
                <div class="exchange_price">
                    <input type="text" name="voucher_t_limit" value="<?=@$data['voucher_t_limit']?>" class="text" style="width:78px;"/><em><?=Web_ConfigModel::value('monetary_unit')?></em>
                    <p class="form-error"></p>
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
                        <option value="<?=($value['user_grade_id'])?>" <?=$data['voucher_t_user_grade_limit']==$value['user_grade_id']?'selected':''?> ><?=($value['user_grade_name'])?></option>
                    <?php }?>
                </select>
                <p class="hint"><?=__('当会员兑换代金券时，需要达到该级别或者以上级别后才能兑换领取')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('代金券描述')?>：</dt>
            <dd>
                <textarea name="voucher_t_desc" class="text textarea w450"><?=@$data['voucher_t_desc']?></textarea>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('代金券图片')?>：</dt>
            <dd>
                <div id="" class="ncsc-upload-thumb voucher-pic">
                    <p><i class="icon-picture"></i></p>
                </div>
                <p class="pic image_review" style="width:200px;height:200px;">
                    <img id="image_review" src="<?=@image_thumb($data['voucher_t_customimg'],200,200)?>"/>
                </p>
                <p class="upload-button">
					<input type="hidden" id="voucher_t_customimg" name="voucher_t_customimg" value="<?=@$data['voucher_t_customimg']?>" />
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
        $('#end_date').datetimepicker({
            controlType: 'select',
            timepicker:false,
            format:'Y-m-d',
            minDate:new Date(),
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

            fields: {
                'voucher_t_title': 'required;',
                'voucher_t_access_method': 'required;integer[+]',
                'voucher_t_end_date': 'required;',
                'voucher_t_price': 'required;',
                'voucher_t_total': 'required;integer[+]',
                'voucher_t_eachlimit': 'required;integer[+0];range[0~<?=Web_ConfigModel::value('promotion_voucher_buyertimes_limit')?>]',
                'voucher_t_limit': 'required;digit',
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
                    url: "index.php?ctl=Seller_Promotion_Voucher&met=editVoucherTemp&typ=json",
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
                            Public.tips.error('操作失败！');
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

