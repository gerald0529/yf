<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_PinTuan&met=add&typ=e">
        <dl>
            <dt><i>*</i><?=__('活动名称')?>：</dt>
            <dd>
                <span><input type="text" name="pintuan_name" class="text w200" id="pintuan_name"/></span>
                <p class="hint"><?=__('活动名称将显示在拼团活动列表中，方便商家管理使用')?>。</p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('拼团商品')?>：</dt>

            <dd>
                <div class="selected-goods fn-hide" style="display:none">
                    <div class="goods-image"><img src="" /></div>
                    <div class="goods-name"></div>
                    <div class="goods-spec"></div>
                    <div class="goods-price"><?=__('销售价')?>：<span></span></div>
                </div>
                <a class="bbc_seller_btns button button_blue btn_show_search_goods" href="javascript:void(0);"><?=__('选择商品')?></a>

                <input type="hidden" name="goods_id" id="goods_id"  />
                <input type="hidden" name="common_id" id="common_id" />
                <div class="search-goods-list fn-clear">
                    <div class="search-goods-list-hd">
                        <label><?=__('搜索店内商品')?></label>
                        <input type="text" name="goods_name" class="text w200" id="key" value="" placeholder="请输入商品名称"/>
                        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                    </div>
                    <div class="search-goods-list-bd fn-clear"></div>
                    <a href="javascript:void(0);" class="close btn_hide_search_goods">X</a>
                </div>
                <p class="hint"><?=__('虚拟商品不参加拼团')?></p>
                <p class="hint"><?=__('商品在拼团活动时间内，不可参加其他促销活动')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('成团人数')?>：</dt>
            <dd>
<!--                <select name="person_num">
                    <option value="">请选择</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>-->
                <input type="text" name="person_num" class="text w200"/>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('拼团库存')?>：</dt>
            <dd>
                <input type="text" name="pintuan_stock" class="text w200" id="pintuan_stock"/>
                <input type="hidden" name="stock" class="text w200"/>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('商品原价')?>：</dt>
            <dd>
                <input type="text" name="goods_price" class="text w200" disabled/>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('单人买价格')?>：</dt>
            <dd>
                <input type="text" name="price_one" class="text w200"/>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('拼团价格')?>：</dt>
            <dd>
                <input type="text" name="pintuan_price" class="text w200"/>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('开始时间')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" readonly="readonly" name="start_time" id="start_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
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
                <input type="text" autocomplete="off" readonly="readonly" name="end_time" id="end_time" class="text w100"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint"><?=__('结束时间发布之后不能修改')?></br>
                    <?php if(!$shop_type){ ?>
                        <?=__('为保障用户拼团顺利，结束时间不可大于72个小时')?>
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
        //添加拼团商品
		$(".btn_show_search_goods").on('click', function() {
            $('.search-goods-list').show();
            $('.btn_search_goods').click();
        });
        $(".btn_hide_search_goods").on('click', function() {
            $('.search-goods-list').hide();
        });
        //搜索店铺商品
        $('.btn_search_goods').on('click', function() {
            var url = "index.php?ctl=Seller_Promotion_PinTuan&met=getShopGoods&typ=e";
            var key = $("#key").val();
            url = key ? url + "&goods_name=" + key : url;
            $('.search-goods-list-bd').load(url);
        });
        //分页
        $('.search-goods-list-bd').on('click', '.page a', function() {
            $('.search-goods-list-bd').load($(this).attr('href'));
            return false;
        });
        $('.search-goods-list-bd').on('click', '[data-type="btn_add_goods"]', function(){
            var goods_id = $(this).attr('data-id');
            var common_id = $(this).attr('common-id');
            var goods_name = $(this).parents("li").find(".goods-name").html();
            var goods_spec = $(this).parents("li").find(".goods-spec").html();
            var goods_price = $(this).parents("li").find(".goods-price span").html();
            var goods_image = $(this).parents("li").find("img").attr("src");
            var goods_stock = $(this).parents("li").find("input[name='goods_stock']").val();
            $("input[name='goods_id']").val(goods_id);
            $("input[name='common_id']").val(common_id);
            $(".selected-goods").find("img").attr("src",goods_image);
            $(".selected-goods").find(".goods-name").html(goods_name);
            $(".selected-goods").find(".goods-spec").html(goods_spec);
            $(".selected-goods").find(".goods-price").find("span").html(goods_price);
            $("input[name='goods_price']").val(goods_price);
            $("input[name='pintuan_stock']").val(goods_stock);
            $("input[name='stock']").val(goods_stock);
            $(".selected-goods").show();
            $(".goods_price").show();
            $('.search-goods-list').hide();
            $('#goods_id').isValid();
            $("#pintuan_stock").isValid();
        });
        
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
            theme: 'yellow_right',
            timely: true,
            stopOnError: true,
            rules:{
                checkStock:function(element) {
                    var pintuan_stock = $("input[name='pintuan_stock']").val();
                    var person_num = $("input[name='person_num']").val();
                    if(!/^[0-9]*[1-9][0-9]*$/.test(pintuan_stock))
                    {
                        return '<?=__("商品库存为正整数")?>';   
                    }
                    if(Number(pintuan_stock) < 5 || Number(pintuan_stock) > 9999)
                    {
                        return '<?=__("请输入5到9999的正整数")?>';
                    }
                    if(Number(person_num) > Number(pintuan_stock))
                    {
                        return '<?=__("拼团库存必须大于成团人数")?>';
                    }
                },
                checkPinTuanPrice:function(element) {
                    var goods_price = $("input[name='goods_price']").val();
                    var price_one = $("input[name='price_one']").val();

                    if(price_one)
                    {
                        if(Number(price_one) <= element.value)
                        {
                            return '<?=__("拼团价格必须小于单人购买价格！")?>';
                        }
                    }

                    if(Number(element.value) < 0.01 || Number(element.value) > 1000000)
                    {
                        return '<?=__("请输入0.01到1000000的数")?>';   
                    }


                    if(isNaN(element.value)){
                        return '<?=__("请输入正确的价格格式")?>';
                    }

                    if(!/^[0-9]\d*(.\d{1,2})?$/.test(element.value))
                    {
                        return '<?=__("价格保留小数点后两位")?>';   
                    }

                },
                checkPriceOne:function(element) {
                    var goods_price = $("input[name='goods_price']").val();
                    var pintuan_price = $("input[name='pintuan_price']").val();

                    if(goods_price)
                    {
                        if(Number(goods_price) <= element.value)
                        {
                            return '<?=__("单人购买价格必须小于商品价格")?>';
                        }
                    }

                    if(Number(element.value) < 0.01 || Number(element.value) > 1000000)
                    {
                        return '<?=__("请输入0.01到1000000的数")?>';   
                    }


                    if(isNaN(element.value)){
                        return '<?=__("请输入正确的价格格式")?>';
                    }

                    if(!/^[0-9]\d*(.\d{1,2})?$/.test(element.value))
                    {
                        return '<?=__("价格保留小数点后两位")?>';
                    }
                },
                checkGoodsPrice:function(element) {
                    var price_one = $("input[name='price_one']").val();
                    var pintuan_price = $("input[name='pintuan_price']").val();
                    if(Number(price_one) >= element.value)
                    {
                        return '<?=__("商品价格必须大于单人购买价格")?>';
                    }
                    if(Number(pintuan_price) >= element.value)
                    {
                        return '<?=__("商品价格必须大于拼团价格")?>';
                    }
                },
                
                 //自定义规则,大于当前时间，如果通过返回true，否则返回错误消息
                greaterThanStartDate : function(element, param, field)
                {
                    var date1 = new Date(Date.parse((element.value).replace(/-/g, "/")));//开始时间
                    param = JSON.parse(param);
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/"))); //套餐开始时间

                    return date1 > date2 || '<?=__("活动开始时间不能小于")?>'+ param;
                },
                //自定义规则，小于套餐活动结束时间
                lessThanEndDate  : function(element, param, field)
                {
                    var date1 = new Date(Date.parse((element.value).replace(/-/g, "/")));//选择的结束时间
                    param = JSON.parse(param);
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/")));  //套餐结束时间
                    return date1 < date2 || '<?=__("活动结束时间不能大于")?>'+ param;
                },
                //自定义规则，结束时间大于开始时间
                startGrateThansEndDate  : function(element, param, field)
                {
                    var s_time = $("#start_time").val();
                    var date1 = new Date(Date.parse(element.value.replace(/-/g, "/")));
                    var date2 = new Date(Date.parse(s_time.replace(/-/g, "/")));
                    var times4 = new Date(s_time).getTime() + 72*60*60*1000;
                    var times3 = new Date(element.value).getTime();

                    if(date1 <= date2)
                    {
                        return '<?=__("结束时间必须大于开始时间")?>';
                    }

                    if(times3 > times4){
                        return '<?=__("结束时间不能大于开始时间的72小时")?>';
                    }

                },
                checkPersonNum:function(element) {
                    var person_num = $("input[name='person_num']").val();
                    var pintuan_stock = $("input[name='pintuan_stock']").val();
                    if(!/^[1-9]*[1-9][0-9]*$/.test(person_num))
                    {
                        return '<?=__("成团数量必须为正整数")?>';   
                    }
                    if(Number(person_num) < 2 || Number(person_num) > 9999)
                    {
                        return '<?=__("请输入2到9999的正整数")?>';
                    }
                }    
            },
            fields: {
                'goods_id': 'required;',
                'pintuan_name': 'required;',
                'person_num': 'required;checkPersonNum',
                'pintuan_stock': 'required;checkStock',
                'goods_price': 'required;range[0.01~1000000];checkGoodsPrice',
                'price_one': 'required;checkPriceOne',
                'pintuan_price': 'required;checkPinTuanPrice',
                // 'start_time': 'required;greaterThanStartDate["<?=date('Y-m-d H:i:s')?>"];lessThanEndDate["<?=$combo['combo_end_time']?>"]',
                // 'end_time': 'required;lessThanEndDate["<?=$combo['combo_end_time']?>"];startGrateThansEndDate;',
            },
            valid: function(form){
                var _this = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                _this.holdSubmit(function(){
                    Public.tips.error('<?=__('正在处理中...')?>');
                });

                var data = {
                    pintuan_name: $("#pintuan_name").val(),
                    person_num: $("input[name='person_num']").val(),
                    start_time: $("input[name='start_time']").val(),
                    end_time: $("input[name='end_time']").val(),
                    goods_id: $("#goods_id").val(),//参加拼团的商品ID
                    details: [
                        {
                            goods_id: $("#goods_id").val(),//参加拼团的商品ID
                            price_ori: $(".selected-goods").find(".goods-price").find("span").html(),//原价格
                            price: $("input[name='pintuan_price']").val(),//团购价格
                            price_one: $("input[name='price_one']").val(),//单独购买价格
                            goods_stock: $("input[name='pintuan_stock']").val(),//拼团库存
                            stock: $("input[name='stock']").val(),//规格商品库存
                        }
                    ]
                };
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_PinTuan&met=addPinTuan&typ=json",
                    data: data,
                    type: "POST",
                    success:function(e){
                        console.log(e);
                        if(e.status == 200)
                        {
                            var data = e.data;
                            Public.tips.success('操作成功!');

                            var dest_url = "index.php?ctl=Seller_Promotion_PinTuan&met=index&typ=e";//成功后跳转
                            setTimeout(window.location.href = dest_url,5000);
                        }
                        else
                        {
                            Public.tips.error(e.msg);
                        }
                        _this.holdSubmit(false);
                    }
                });
            }
        });
    });
    function add_goods_tips()
    {
        Public.tips.warning('<?=__('该商品已参加活动！')?>');
        return ;
    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

