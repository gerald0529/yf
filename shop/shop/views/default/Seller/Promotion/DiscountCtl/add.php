<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=add&typ=e">
        <dl>
            <dt><i>*</i><?=__('活动名称')?>：</dt>
            <dd>
                <input type="text" name="discount_name" class="text w450"/>
                <p class="hint"><?=__('活动名称将显示在限时折扣活动列表中，方便商家管理使用，最多可输入25个字符')?>。
                </p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('活动标题')?>：</dt>
            <dd>
                <input type="text" name="discount_title" class="text w200"/>
                <p class="hint"><?=__('活动标题是商家对限时折扣活动的别名操作，请使用例如“新品打折”、“月末折扣”类短语表现，最多可输入10个字符')?>；</p>
                <p class="hint"><?=__('非必填选项')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('活动描述')?>：</dt>
            <dd>
                <input type="text" name="discount_explain" class="text w450"/>
                <p class="hint"><?=__('活动描述是商家对限时折扣活动的补充说明文字，在商品详情页-优惠信息位置显示')?>；</p>
                <p class="hint"><?=__('非必填选项，最多可输入30个字符')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('开始时间')?>：</dt>
            <dd>
                <input type="text" readonly="readonly" autocomplete="off" name="discount_start_time" id="start_time" class="text w100 hasDatepicker"/><em><i class="iconfont icon-rili"></i></em>
                <?php if(!$shop_type){ ?>
                <p class="hint"><?=__('开始时间不能为空且不能早于')?><?=$combo['combo_start_time']?></p>
                <?php } ?>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('结束时间')?>：</dt>
            <dd>
                <input type="text" readonly="readonly" autocomplete="off" name="discount_end_time" id="end_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                <?php if(!$shop_type){ ?>
                <p class="hint"><?=__('结束时间不能为空且不能晚于')?><?=$combo['combo_end_time']?></p>
                <?php } ?>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i></i><?=__('购买下限')?>：</dt>
            <dd>
                <input type="text" name="discount_lower_limit" value="1" class="text w70"/>
                <p class="hint"><?=__('参加活动的最低购买数量，默认为1')?></p>
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




<script>
    $(document).ready(function(){
         //日历插件
         $('#start_time').datetimepicker({
            controlType: 'select',
            minDate:new Date(),
			onShow:function( ct ){
			this.setOptions({
				maxDate:($('#end_time').val() && (new Date(Date.parse($('#end_time').val().replace(/-/g, "/"))) < maxdate))?(new Date(Date.parse($('#end_time').val().replace(/-/g, "/")))):maxdate
				})
			}
		});

        var combo_end_time = $.trim("<?=$combo['combo_end_time']?>");
        var maxdate =  new Date(Date.parse(combo_end_time.replace(/-/g, "/")));
        $('#end_time').datetimepicker({
            controlType: 'select',
            maxDate:maxdate,
            onShow:function( ct ){
                this.setOptions({
                    minDate:($('#start_time').val() && (new Date(Date.parse($('#start_time').val().replace(/-/g, "/")))) > (new Date()))?(new Date(Date.parse($('#start_time').val().replace(/-/g, "/")))):(new Date())
                })
            }
        });


        $('#form').validator({
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
                },
                //自定义规则，结束时间大于开始时间
                startGrateThansEndDate  : function(element, param, field)
                {
                    var s_time = $("#start_time").val();
                    var date1 = new Date(Date.parse(element.value.replace(/-/g, "/")));
                    var date2 = new Date(Date.parse(s_time.replace(/-/g, "/")));

                    return date1 > date2 || "结束时间必须大于开始时间";
                }

            },
            fields: {
                'discount_name': 'required;length[~25]',
                'discount_title': 'length[~10]',
                'discount_explain': 'length[~30]',
                'discount_start_time': 'required;greaterThanStartDate["<?=date('Y-m-d H:i:s')?>"];lessThanEndDate["<?=$combo['combo_end_time']?>"]',
                'discount_end_time': 'required;lessThanEndDate["<?=$combo['combo_end_time']?>"];startGrateThansEndDate;',
                'discount_lower_limit': 'required;integer[+];range[1~9999999999]'
            },
			valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_Discount&met=addDiscount&typ=json",
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

