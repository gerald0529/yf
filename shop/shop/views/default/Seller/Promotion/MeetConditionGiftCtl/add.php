<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<style>

    .ms-rule-list li {
        color: #3A87AD;
        filter: progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#3FD9EDF7', endColorstr='#3FD9EDF7');
        background: rgba(217,237,247,0.25);
        border: dashed 1px #BCE8F1;
        padding: 4px 9px;
        margin-bottom: 10px;
    }
</style>

    <div class="alert alert-block">
        <h4><?=__('说明')?>：</h4>
        <ul>
            <li><?=__('满即送活动包括店铺所有商品，活动时间不能和已有活动重叠')?></li>
            <li><?=__('每个满即送活动最多可以设置3个价格级别，点击新增级别按钮可以增加新的级别，价格级别应该由低到高')?></li>
            <li><?=__('每个级别可以有减现金、送礼品2种促销方式，至少需要选择一种')?></li>
        </ul>
    </div>
    <div class="form-style">
        <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_MeetConditionGift&met=add&typ=e">
            <dl>
                <dt><i>*</i><?=__('活动名称')?>：</dt>
                <dd>
                    <input type="text" name="mansong_name" class="text w450"/>
                    <p class="hint"><?=__('活动名称最多为25个字符')?></p>
                </dd>
            </dl>
            <dl>
                <dt><i>*</i><?=__('开始时间')?>：</dt>
                <dd>
                    <input type="text" readonly="readonly" autocomplete="off" name="mansong_start_time" id="start_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                    <p class="hint"><?=__('开始时间不能为空且不能小于')?><?=$data['combo']['combo_start_time']?></p>
                </dd>
            </dl>
            <dl>
                <dt><i>*</i><?=__('结束时间')?>：</dt>
                <dd>
                    <input type="text" readonly="readonly" autocomplete="off" name="mansong_end_time" id="end_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                    <p class="hint"><?=__('结束时间不能为空')?><?php if(!$data['shop_type']){ ?><?=__('且不能晚于')?><?=$data['combo']['combo_end_time']?><?php } ?></p>
                </dd>
            </dl>

            <dl>
                <dt><i>*</i><?=__('满即送规则')?>：</dt>
                <dd>
                    <input type="hidden" id="mansong_rule_count" name="rule_count">
                    <ul class="ms-rule-list"></ul>
                    <a href="javascript:void(0);" id="add_rule_btn" class="button button_blue bbc_seller_btns"><i class="iconfont icon-jia"></i><?=__('添加规则')?></a>
                    <div id="add_rule_box" style="display:none;">
                        <div class="ncsc-mansong-error error">
                            <span id="mansong_price_error" style="display:none;">
                                <i class="iconfont icon-exclamation-sign"></i><?=__('规则金额不能为空且必须为数字')?>
                            </span>
                            <span id="mansong_discount_error" style="display:none;">
                                <i class="iconfont icon-exclamation-sign"></i><?=__('满减金额必须小于规则金额')?>
                            </span>
                        </div>
                        <div class="ncsc-mansong-rule">
                            <span><?=__('单笔订单满')?>&nbsp;<input id="mansong_price" type="text" class="text w50"><em><?=Web_ConfigModel::value('monetary_unit')?></em>，</span>
                            <span><?=__('立减现金')?>&nbsp;<input id="mansong_discount" type="text" class="text w50"><em><?=Web_ConfigModel::value('monetary_unit')?></em>，</span>
                            <span><?=__('送礼品')?>&nbsp;<a href="javascript:void(0);" id="btn_show_search_goods" class="button button_orange bbc_seller_btns"><i class="icon-gift"></i><?=__('选择礼品')?></a></span>

                            <div id="mansong_goods_item" class="gift"></div>

                            <div class="search-goods-list fn-clear" style="display: none;">
                                <div class="search-goods-list-hd">
                                    <label><strong><?=__('第一步：搜索店内商品')?></strong></label>
                                    <input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/>
                                    <a href="javascript:void(0);"  class="button btn_search_goods"/><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                                    <span class="hint" style="margin-left:10px;"><?=__('不输入名称直接搜索将显示店内所有出售中的商品')?></span>
                                </div>
                                <a id="btn_hide_search_goods" class="close" href="javascript:void(0);">X</a>
                                <div class="search-goods-list-bd fn-clear"></div>
                            </div>
                        </div>
                        <div id="mansong_rule_error" style="display:none;"><?=__('请至少选择一种促销方式')?></div>
                        <div class="mt10">
                            <a href="javascript:void(0);" id="btn_save_rule" class="button bbc_seller_btns"><i class="icon-ok-circle"></i><?=__('确定规则设置')?></a>
                            <a href="javascript:void(0);" id="btn_cancel_add_rule" class="button bbc_seller_btns"><i class="icon-ban-circle"></i><?=__('取消')?></a>
                        </div>
                    </div>
                    <span class="error-message"></span>
                    <p class="hint"><?=__('设置当单笔订单满足金额时（必填选项），减免金额（选填）或赠送的礼品（选填）；留空为不做减免金额或赠送礼品处理')?>。</p>
                    <p class="hint"><?=__('系统最多支持设置三组等级规则')?>。</p>
                </dd>
            </dl>

            <dl>
                <dt><?=__('备注')?>：</dt>
                <dd>
                    <textarea name="mansong_remark" class="text textarea w450"></textarea>
                    <p class="hint"><?=__('活动备注最多为100个字符')?></p>
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

<!--活动规则模板-->
<script id="mansong_rule_template" type="text/html">
    <li nctype="mansong_rule_item" class="mansong_rule_item">
        <span><?=__('单笔订单满')?><strong><?=Web_ConfigModel::value('monetary_unit')?><%=price%></strong>， </span>
        <span><?=__('立减现金')?><strong><?=Web_ConfigModel::value('monetary_unit')?><%=discount%></strong> </span>
        <%if(goods_id>0){%>
        <span>，<?=__('送礼品')?> <%==goods%></span>
        <%}%>
        <input type="hidden" name="mansong_rule[]" value="<%=price%>,<%=discount%>,<%=goods_id%>">
        <a nctype="btn_del_mansong_rule" href="javascript:void(0);" class="mini-btn bbc_seller_btns"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a>
    </li>
</script>

<!--规则下赠送商品模板-->
<script id="mansong_goods_template" type="text/html">
    <div nctype="mansong_goods" class="selected-mansong-goods">
        <a href="<%=goods_url%>" title="<%=goods_name%>" class="goods-thumb" target="_blank">
            <img src="<%=goods_image_url%>"/>
        </a>
        <input nctype="mansong_goods_id" type="hidden" value="<%=goods_id%>">
    </div><a nctype="btn_del_mansong_goods" href="javascript:void(0);" class="mini-btn bg-red"><i class="iconfont icon-trash"></i><?=__('删除已选择的礼品')?></a>
</script>

<script type="text/javascript" src="<?=$this->view->js_com?>/jquery.tmpl.min.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //日历插件
        var combo_start_time = $.trim("<?=$data['combo']['combo_start_time']?>");
        var mindate =  new Date(Date.parse(combo_start_time.replace(/-/g, "/")));

        var combo_end_time = $.trim("<?=$data['combo']['combo_end_time']?>");
        var maxdate =  new Date(Date.parse(combo_end_time.replace(/-/g, "/")));

         $('#start_time').datetimepicker({
            controlType: 'select',
            minDate:mindate,
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
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/"))); //套餐开始时间

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
                'mansong_name': 'required;length[~25]',
                'mansong_start_time': 'required;greaterThanStartDate["<?=$data['combo']['combo_start_time']?>"];lessThanEndDate["<?=$data['combo']['combo_end_time']?>"]',
                'mansong_end_time': 'required;lessThanEndDate["<?=$data['combo']['combo_end_time']?>"];startGrateThansEndDate;',
                'mansong_remark': 'length[~100];'
            },
              valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_MeetConditionGift&met=addManSong&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            Public.tips.success('操作成功!');
                            location.href="index.php?ctl=Seller_Promotion_MeetConditionGift&met=index&typ=e"; //成功后跳转
                        }
                        else
                        {
                            Public.tips.error(e.msg);
                        }
                        me.holdSubmit(false);
                    }
                });
            }
        });


        // 限时添加规则窗口
        $('#add_rule_btn').on('click', function() {
            $('#mansong_price').val('');
            $('#mansong_discount').val('');
            $('#mansong_goods_item').html('');
            $('#mansong_price_error').hide();
            $('#mansong_rule_error').hide();
            $('#add_rule_box').show();
            $('#add_rule_btn').hide();
        });

        // 保存规则
        $('#btn_save_rule').on('click', function() {
            var mansong = {};
            mansong.price = Number($('#mansong_price').val());
            if(isNaN(mansong.price) || mansong.price <= 0) {
                $('#mansong_price_error').show();
                return false;
            } else {
                $('#mansong_price_error').hide();
            }
            mansong.discount = Number($('#mansong_discount').val());
            if(isNaN(mansong.discount) || mansong.discount < 0 || mansong.discount >= mansong.price) {
                $('#mansong_discount_error').show();
                return false;
            } else {
                $('#mansong_discount_error').hide();
            }
            mansong.goods = $('#mansong_goods_item').find('[nctype="mansong_goods"]').html();
            mansong.goods_id = Number($('#mansong_goods_item').find('[nctype="mansong_goods_id"]').val());
            if(isNaN(mansong.goods_id)) {
                mansong.goods_id = 0;
            }
            if(mansong.discount == 0 && mansong.goods_id == 0) {
                $('#mansong_rule_error').show();
                return false;
            } else {
                $('#mansong_rule_error').hide();
            }
            var mansong_rule_item = template.render('mansong_rule_template', mansong);
            $('.ms-rule-list').append(mansong_rule_item);
            close_add_rule_box();
        });

        // 删除已添加的规则
        $('.ms-rule-list').on('click', '[nctype="btn_del_mansong_rule"]', function() {
            $(this).parents('[nctype="mansong_rule_item"]').remove();
            close_add_rule_box();
        });

        // 取消添加规则
        $('#btn_cancel_add_rule').on('click', function() {
            close_add_rule_box();
        });

        // 关闭规则添加窗口
        function close_add_rule_box() {
            var rule_count = $('.ms-rule-list').find('[nctype="mansong_rule_item"]').length;
            if( rule_count >= 3) {
                $('#add_rule_btn').hide();
            } else {
                $('#add_rule_btn').show();
            }
            $('#add_rule_box').hide();
            $('#mansong_rule_count').val(rule_count);
        }

        // 显示商品选择窗口
        $('#btn_show_search_goods').on('click', function(){
            $('.search-goods-list').show();
            $('.btn_search_goods').click();
        });

        //搜索店铺商品
        $('.btn_search_goods').on('click', function() {
            var url = "index.php?ctl=Seller_Promotion_MeetConditionGift&met=getShopGoods&typ=e";
            var key = $("#search_goods_name").val();
            url = key ? url + "&goods_name=" + key : url;
            $('.search-goods-list-bd').load(url);
        });
        //店铺商品分页
        $('.search-goods-list-bd').on('click', '.page a', function() {
            $('.search-goods-list-bd').load($(this).attr('href'));
            return false;
        });

        // 关闭商品选择窗口
        $('#btn_hide_search_goods').on('click', function() {
            $('.search-goods-list').hide();
        });

        // 选择商品
        $('.search-goods-list-bd').on('click', '[data-type="btn_add_goods"]', function() {
            var _SELF = $(this);
            var goods = {};
            goods.goods_id = _SELF.attr('data-id');
            goods.goods_name = _SELF.parents("li").find(".goods-name").html();
            goods.goods_image_url = _SELF.parents("li").find("img").attr("src");
            goods.goods_url = _SELF.attr('data-goods-url');
            var mansong_goods_item = template.render('mansong_goods_template', goods);
            $('#mansong_goods_item').html(mansong_goods_item);
            $('.search-goods-list').hide();
        });

        // 删除以选的商品
        $('#mansong_goods_item').on('click', '[nctype="btn_del_mansong_goods"]', function() {
            $('#mansong_goods_item').html('');
        });

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

