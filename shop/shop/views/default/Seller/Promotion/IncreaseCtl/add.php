<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Increase&met=add&typ=e">
        <dl>
            <dt><i>*</i><?=__('活动名称')?>：</dt>
            <dd>
                <input type="text" name="increase_name" class="text w300"/>
                <p class="hint"><?=__('活动名称将显示在加价购活动列表中，方便商家管理使用')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('开始时间')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" readonly="readonly" name="increase_start_time" id="start_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint">
                    <?=__('开始时间发布之后不能修改')?></br>
                    <?php if(!$shop_type){ ?>
                        <?=__('开始时间不能早于')?><?=date('Y-m-d H:i:s')?>
                    <?php } ?>
                </p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('结束时间')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" readonly="readonly" name="increase_end_time" id="end_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint"><?=__('结束时间发布之后不能修改')?></br>
                    <?php if(!$shop_type){ ?>
                        <?=__('结束时间不能晚于')?><?=$combo['combo_end_time']?>
                    <?php } ?>
                </p>
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交"  />
                <input type="hidden" name="act" value="add" />
            </dd>
        </dl>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		
		var combo_end_time = $.trim("<?=$combo['combo_end_time']?>");
        var maxdate =  new Date(Date.parse(combo_end_time.replace(/-/g, "/")));
   
         $('#start_time').datetimepicker({
            controlType: 'select',
            minDate:new Date(),
			onShow:function( ct ){
			this.setOptions({
				maxDate:($('#end_time').val() && (new Date(Date.parse($('#end_time').val().replace(/-/g, "/"))) < maxdate))?(new Date(Date.parse($('#end_time').val().replace(/-/g, "/")))):maxdate
				})
			}
		});

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
                'increase_name': 'required;length[~30]',
                'increase_start_time': 'required;greaterThanStartDate["<?=date('Y-m-d H:i:s')?>"];lessThanEndDate["<?=$combo['combo_end_time']?>"]',
				'increase_end_time': 'required;lessThanEndDate["<?=$combo['combo_end_time']?>"];startGrateThansEndDate;',
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_Increase&met=addIncrease&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            var data = e.data;
                            Public.tips.success('操作成功!');

                            var dest_url = "index.php?ctl=Seller_Promotion_Increase&met=index&typ=e&op=edit&id=" + data.increase_id;//成功后跳转
                            setTimeout(window.location.href = dest_url,5000);

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

