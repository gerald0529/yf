<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<form id="form" method="post" name="form">
    <div class="form-style">
        <dl>
            <dt><i>*</i><?=__('套餐购买数量')?>：</dt>
            <dd>
                <input type="text" class="text w50" maxlength="2" name="month" /><em><?=__('个月')?></em>
                <p class="hint"><?=__('购买单位为月(30天)，您可以在所购买的周期内发布代金券')?>。</p>
                <p class="hint"><?=__('每月您需要支付')?><?=format_money(Web_ConfigModel::value('promotion_voucher_price'))?>。</p>
                <p class="hint"><b class="red"><?=__('相关费用会在店铺的账期结算中扣除')?></b>。</p>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="hidden" name="act" value="submit" />
                <input type="submit" class="button button_red bbc_seller_submit_btns" value="提交" />
            </dd>
        </dl>
    </div>
</form>

<div>
    <?php if($data['items']){?>
        <table class="table-list-style">
            <tbody>
            <tr>
                <th width="200"><?=__('活动名称')?></th>
                <th width="200"><?=_('活动单价(￥)')?></th>
                <th width="200"><?=_('购买时长(月)')?></th>
                <th width="200"><?=_('购买总价(￥)')?></th>
                <th width="200"><?=_('购买时间')?></th>
            </tr>
            <?php foreach($data['items'] as $key => $val){?>
                <tr>
                    <td width="200"><?=$val['cost_desc']?></td>
                    <td width="200"><?=$val['activity_price']?></td>
                    <td width="200"><?=$val['activity_month']?></td>
                    <td width="200"><?=$val['cost_price']?></td>
                    <td width="200"><?=$val['cost_time']?></td>
                </tr>
            <?php }?>

            <nav class="page page_front">
                <?=$page_nav?>
            </nav>
            </tbody>
        </table>
    <?php }?>
</div>

<script>
    $(function(){
        $('#form').validator({
            debug:true,
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: true,
            stopOnError: false,
            messages: {
                required: "请填写购买月份"
            },
            fields: {
                'month': 'required;integer[+]'
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_Voucher&met=addCombo&typ=json",
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
