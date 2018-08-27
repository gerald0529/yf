<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=add&op=edit&typ=e">
        <dl>
            <dt><i>*</i><?=__('活动名称')?>：</dt>
            <dd>
                <input type="text" name="discount_name" value="<?=@$data['discount_name']?>" class="text w450"/>
                <p class="hint"><?=__('活动名称将显示在限时折扣活动列表中，方便商家管理使用，最多可输入25个字符')?>。
                </p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('活动标题')?>：</dt>
            <dd>
                <input type="text" name="discount_title" value="<?=@$data['discount_title']?>" class="text w200"/>
                <p class="hint"><?=__('活动标题是商家对限时折扣活动的别名操作，请使用例如“新品打折”、“月末折扣”类短语表现，最多可输入10个字符')?>；</p>
                <p class="hint"><?=__('非必填选项')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('活动描述')?>：</dt>
            <dd>
                <input type="text" name="discount_explain" value="<?=@$data['discount_explain']?>" class="text w450"/>
                <p class="hint"><?=__('活动描述是商家对限时折扣活动的补充说明文字，在商品详情页-优惠信息位置显示')?>；</p>
                <p class="hint"><?=__('非必填选项')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i></i><?=__('购买下限')?>：</dt>
            <dd>
                <input type="text" name="discount_lower_limit" value="<?=@$data['discount_lower_limit']?>" class="text w70"/>
                <p class="hint"><?=__('参加活动的最低购买数量，默认为1')?></p>
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交"  />
                <input type="hidden" name="act" value="edit" />
                <input type="hidden" name="discount_id" value="<?=@$data['discount_id']?>" />
            </dd>
        </dl>
    </form>
</div>




<script>
    $(document).ready(function(){

        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'discount_name': 'required;length[~25]',
                'discount_lower_limit': 'required;integer[+]'
            },
			valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_Discount&met=editDiscount&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            var data = e.data;
                            Public.tips.success('操作成功!');
                            location.href="index.php?ctl=Seller_Promotion_Discount&met=index&op=manage&typ=e&id="+data.discount_id;//成功后跳转
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

