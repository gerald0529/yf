<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_GroupBuy&met=add&typ=e">
        <dl>
            <dt><i>*</i><?=__('团购名称')?>：</dt>
            <dd>
                <input type="text" name="groupbuy_name" class="text w450" value="<?=$group_info['groupbuy_name']?>"/>
                <p class="hint"><?=__('团购标题名称长度最多可输入30个字符')?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('团购副标题')?>：</dt>
            <dd>
                <input type="text" name="groupbuy_remark" class="text w450" value="<?=$group_info['groupbuy_remark']?>"/>
                <p class="hint"><?=__('团购活动副标题最多可输入60个字符')?></p>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('开始时间')?>：</dt>
            <dd>
                <input type="text" readonly="readonly" autocomplete="off" name="groupbuy_starttime" id="start_time" class="text w100" value="<?=$group_info['groupbuy_starttime']?>"/><em><i class="iconfont icon-rili"></i></em>
                <p class="hint"><?=__('团购开始时间不能小于')?><?=$data['combo']['combo_starttime']?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('结束时间')?>：</dt>
            <dd>
                <input type="text" readonly="readonly" autocomplete="off" name="groupbuy_endtime" id="end_time" class="text w100 hasDatepicker" value="<?=$group_info['groupbuy_endtime']?>"/><em><i class="iconfont icon-rili"></i></em>
                <?php if($shop_type){ ?>
                <?php }else{ ?>
                    <p class="hint"><?=__('团购结束时间不能大于')?><?=$data['combo']['combo_endtime']?></p>
                <?php } ?>
            </dd>
        </dl>

        <dl>
            <dt><i>*</i><?=__('团购商品')?>：</dt>
            <dd>

                <div class="selected-goods fn-hide" <?php if(!$group_info['goods']){echo 'hidden';} ?>>
                    <div class="goods-image"><img src="<?=$group_info['goods']['goods_image']?>" /></div>
                    <div class="goods-name"><?=__($group_info['goods']['goods_name'])?></div>
                    <div class="goods-price"><?=__('销售价')?>：<span><?=$group_info['goods']['goods_price']?></span></div>
                </div>
                <a class="bbc_seller_btns button button_blue btn_show_search_goods" href="javascript:void(0);"><?=__('选择商品')?></a>
                <input type="hidden" name="goods_id" id="goods_id" value="<?=$group_info['goods_id']?>" />
                <input type="hidden" name="common_id" id="common_id" value="<?=$group_info['common_id']?>" />
                <div class="search-goods-list fn-clear">
                    <div class="search-goods-list-hd">
                        <label><?=__('搜索店内商品')?></label>
                        <input type="text" name="goods_name" class="text w200" id="key" value="" placeholder="请输入商品名称"/>
                        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                    </div>
                    <div class="search-goods-list-bd fn-clear"></div>
                    <a href="javascript:void(0);" class="close btn_hide_search_goods">X</a>
                </div>
                <p class="hint"><?=__('点击上方输入框从已发布商品中选择要参加团购的商品')?></p>
                <p class="hint"><?=__('团购生效后该商品的所有规格SKU都将执行统一的团购价格')?>。</p>
            </dd>
        </dl>
        <?php if($group_info['goods_price']){$goods_price_status = 'block';}else{$goods_price_status = 'none';} ?>
        <dl class="goods_price" style="display:<?=$goods_price_status?>;">
            <dt><?=__('店铺价格')?>：</dt>
            <dd><span><?=$group_info['goods']['goods_price']?></span><input type="hidden" id="goods_price" value="<?=$group_info['goods']['goods_price']?>"></dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('团购价格')?>：</dt>
            <dd>
                <input type="text" name="groupbuy_price" class="text" style="width:78px;" value="<?=$group_info['groupbuy_price']?>"/><em><?=Web_ConfigModel::value('monetary_unit')?></em>
                <p class="hint"><?=__('团购价格为该商品参加活动时的促销价格')?></p>
                <p class="hint"><?=__('必须是0.01~1000000之间的数字')?>(<?=__('单位：')?><?=Web_ConfigModel::value('monetary_unit')?>)</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('团购活动图片')?>：</dt>
            <dd>
<!--                --><?php //if($group_info['groupbuy_image_rec']){$groupbuy_image_status = 'block';}else{$groupbuy_image_status = 'none';} ?>
                <p class="pic groupbuy_image_review" style="width:400px;height:400px;display:none;">
                    <img id="groupbuy_image_review" src="<?=$group_info['groupbuy_image']?>" width="400" height="400"/>
                </p>
                <p class="upload-button">
                    <input type="hidden" id="group_buy_image" name="groupbuy_image" value="" />
                    <div  id='groupbuy_upload' class="lblock bbc_img_btns" ><i class="iconfont icon-tupianshangchuan" ></i><?=__('图片上传')?></div>
                </p>
                <p class="hint"><?=__('此处为团购活动图片')?>；</p>
                <p class="hint"><?=__('建议使用宽400像素-高400像素内的透明图片,点击下方"提交"按钮后生效')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('团购推荐位图片')?>：</dt>
            <dd>
<!--                --><?php //if(!empty($group_info['groupbuy_image_rec'])){$groupbuy_image_rec_status = 'block';}else{$groupbuy_image_rec_status = 'none';} ?>
                <p class="pic groupbuy_image1_review" style="width:200px;height:100px;display:none">
                    <img id="groupbuy_image1_review" src="<?=$group_info['groupbuy_image_rec']?>" width="200"  height="100"/>
                </p>
                <p class="upload-button">
                    <input type="hidden" id="groupbuy_image_rec" name="groupbuy_image_rec" value="" />
                    <div  id='groupbuy_upload_rec' class="lblock bbc_img_btns" ><i class="iconfont icon-tupianshangchuan" ></i><?=__('图片上传')?></div>
                </p>
                <p class="hint"><?=__('此处为团购推荐位图片')?>；</p>
                <p class="hint"><?=__('建议使用宽612像素-高318像素内的透明图片；点击下方"提交"按钮后生效')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('团购类别')?>：</dt>
            <dd>
                <select id="class_id" name="groupbuy_cat_id" class="w80">
                    <option value="0"><?=__('不限')?></option>
                </select>
                <select id="s_class_id" name="groupbuy_scat_id" class="w80">
                    <option value="0"><?=__('不限')?></option>
                </select>
                <span></span>
                <p class="hint"><?=__('请选择团购商品的所属类别')?></p>
            </dd>
        </dl>

        <dl>
            <dt><?=__('虚拟数量')?>：</dt>
            <dd>
                <input type="text" name="groupbuy_virtual_quantity" value="<?=$group_info['groupbuy_virtual_quantity']?>" class="text w70"/>
                <p class="hint"><?=__('虚拟购买数量，只用于前台显示，不影响成交记录')?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('每人限购')?>：</dt>
            <dd>
                <input type="text" name="groupbuy_upper_limit" value="<?=$group_info['groupbuy_upper_limit']?>" class="text w70"/>件
                <p class="hint"><?=__('每个买家ID可团购的最大数量，不限数量请填 "0"')?></p>
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value=<?=__("提交")?>  />
                <input type="hidden" name="act" value="save" />
                <input type="hidden" name="groupid" value="<?=$_GET['groupid']?>" />
            </dd>
        </dl>
    </form>
</div>

<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
   $(document).ready(function(){
       /* 开始时间限制*/
        var s_date = $.trim("<?=$data['combo']['combo_starttime']?>");
        var mindate =  new Date(Date.parse(s_date.replace(/-/g, "/")));
        var mintime = new Date(Date.parse(s_date.replace(/-/g, "/")));

      /* 结束时间限制*/
        var combo_end_time = $.trim("<?=$data['combo']['combo_endtime']?>");
        var maxdate = new Date(Date.parse(combo_end_time.replace(/-/g, "/")));

         $('#start_time').datetimepicker({
            controlType: 'select',
            minDate:mindate,
            minTime:mintime,
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

       $('#groupbuy_upload').on('click', function () {
           $.dialog({
               title: '<?=__('图片裁剪')?>',
               content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
               data: {width:400,height:400 , callback: callback1 },    // 需要截取图片的宽高比例
               width: '800px',
               lock: true
           })
       });

       function callback1( respone , api ) {
           $('#groupbuy_image_review').attr('src', respone.url);
           $('.groupbuy_image_review').show();
           $('#group_buy_image').attr('value', respone.url);
           api.close();
       }

       if ( window.isIE8 ) {
           $('#groupbuy_upload').off("click");

           new UploadImage({
               thumbnailWidth: 200,
               thumbnailHeight: 60,
               imageContainer: '#groupbuy_image_review',
               uploadButton: '#groupbuy_upload',
               inputHidden: '#group_buy_image'
           });
       }

       //推荐位图片上传
        $('#groupbuy_upload_rec').on('click', function () {
           $.dialog({
               title: '<?=__('图片裁剪')?>',
               content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
               data: {width:612,height:318 , callback: callback2 },    // 需要截取图片的宽高比例
               width: '800px',
               /*height: '310px',*/
               lock: true
           })
        });

        function callback2( respone , api ) {
            $('#groupbuy_image1_review').attr('src', respone.url);
            $('.groupbuy_image1_review').show();
            $('#groupbuy_image_rec').attr('value', respone.url);
            api.close();
        }

       if ( window.isIE8 ) {
           $('#groupbuy_upload_rec').off("click");

           new UploadImage({
               thumbnailWidth: 200,
               thumbnailHeight: 60,
               imageContainer: '#groupbuy_image1_review',
               uploadButton: '#groupbuy_upload_rec',
               inputHidden: '#groupbuy_image_rec'
           });
       }

        $(".btn_show_search_goods").on('click', function() {
            $('.search-goods-list').show();
            $('.btn_search_goods').click();
        });
        $(".btn_hide_search_goods").on('click', function() {
            $('.search-goods-list').hide();
        });
        //搜索店铺商品
        $('.btn_search_goods').on('click', function() {
            var url = "index.php?ctl=Seller_Promotion_GroupBuy&met=getShopGoods&typ=e";
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
            var goods_price = $(this).parents("li").find(".goods-price span").html();
            var goods_image = $(this).parents("li").find("img").attr("src");
            $("input[name='goods_id']").val(goods_id);
            $("input[name='goods_name']").val(goods_name);
            $("input[name='common_id']").val(common_id);
            $(".selected-goods").find("img").attr("src",goods_image);
            $(".selected-goods").find(".goods-name").html(goods_name);
            $(".selected-goods").find(".goods-price").find("span").html(goods_price);
            $(".goods_price").find("span").html(goods_price);
            $("#goods_price").val(goods_price);
            $(".selected-goods").show();
            $(".goods_price").show();
            $('.search-goods-list').hide();
        });

       (function(data) {
           var s = '<option value="0"><?=__('不限')?></option>';
           if (typeof data.children != 'undefined') {
               if (data.children[0]) {
                   $.each(data.children[0], function(k, v) {
                       s += '<option value="'+v+'">'+data['name'][v]+'</option>';
                   });
               }
           }
           $('#class_id').html(s).change(function() {
               var ss = '<option value="0"><?=__('不限')?></option>';
               var v = this.value;
               if (parseInt(v) && data.children[v]) {
                   $.each(data.children[v], function(kk, vv) {
                       ss += '<option value="'+vv+'">'+data['name'][vv]+'</option>';
                   });
               }
               $('#s_class_id').html(ss);
           });
       })($.parseJSON('<?=$data['groupbuy_cat']?>'));



    $('#form').validator({
            debug:true,
            //ignore: ':hidden',
            theme: 'yellow_right',
            timely: true,
            stopOnError: true,
            rules:{
                noGreaterThanGoodsPrice:function(element) {
                    var goods_price = $("#goods_price").val();
                    if(!/^\d+(\.\d{1,2})?$/.test(element.value))
                    {
                      return '<?=__("小数点后只能保留两位！")?>';
                    }
                    if(Number(element.value) >= Number(goods_price))
                    {
                      return '<?=__("团购价格必须小于商品价格！")?>';
                    }
                    // return Number(element.value) < Number(goods_price) || '<?=__("团购价格必须小于商品价格！")?>';
                },
				
				 //自定义规则,大于当前时间，如果通过返回true，否则返回错误消息
                greaterThanStartDate : function(element, param, field)
                {
                    var date1 = new Date(Date.parse((element.value).replace(/-/g, "/")));//开始时间
                    param = JSON.parse(param);
                    var date2 = new Date(Date.parse(param.replace(/-/g, "/")));	//套餐开始时间

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

                    return date1 > date2 || '<?=__("结束时间必须大于开始时间")?>';
                },
                myRemote: function(element){
                    var start_time = $("#start_time").val();
                    var end_time = $("#end_time").val();
                    var flag = false;
                    $.ajax({
                        url: SITE_URL + '?ctl=Seller_Promotion_GroupBuy&met=checkGroupBuyGoods&typ=json',
                        type: 'POST',
                        data:{start_time: start_time,end_time:end_time,common_id: element.value},
                        dataType: 'json',
                        async: false,
                        success: function(d){
                            //window.console && console.log(d);
                            if(d.status ==200)
                            {
                                flag = true;
                            }
                            else
                                flag = false;
                        }
                    });
                    return flag;
                }
            },
            messages: {
                myRemote: '<?=__("该商品已经参加了同时段的活动")?>'
            },
            fields: {
                'groupbuy_name': 'required;length[~30]',
                'groupbuy_remark': 'length[~60]',
                'groupbuy_starttime':'required;greaterThanStartDate["<?=$data['combo']['combo_starttime']?>"];lessThanEndDate["<?=$data['combo']['combo_endtime']?>"];',
                'groupbuy_endtime': 'required;lessThanEndDate["<?=$data['combo']['combo_endtime']?>"];startGrateThansEndDate;',
                'goods_id': 'required;integer[+];',
                'common_id': 'required;integer[+];myRemote;',
                'groupbuy_price': 'required;range[0.01~1000000];noGreaterThanGoodsPrice',
                'groupbuy_image': 'required;',
                'groupbuy_image_rec': 'required;',
                'groupbuy_virtual_quantity': 'integer[+0];range[0~9999999999]',
                'groupbuy_upper_limit': 'integer[+0];'
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('<?=__('正在处理中...')?>');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Promotion_GroupBuy&met=addGroupBuy&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            var data = e.data;
                            Public.tips.success('<?=__('操作成功!')?>');
                            setTimeout(window.location.href='index.php?ctl=Seller_Promotion_GroupBuy&met=index&typ=e',5000);
                            //成功后跳转
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
    });
    
    function add_goods_tips(){
        Public.tips.warning('<?=__('该商品已参加活动！')?>');
        return ;
    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

