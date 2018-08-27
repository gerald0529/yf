/**
 * Created by rd04 on 2016/6/8.
 */


$(function () {
    
    //图片上传
    var uploadImage = new UploadImage({

        thumbnailWidth: 160,
        thumbnailHeight: 160,
        imageContainer: '#goodsImage',
        uploadButton: '#uploadButton',
        inputHidden: '#imagePath',
        callback: function () {
            $('#imagePath').isValid();
        }
    });


    //编辑器
    window.ue = UE.getEditor('body', {
        minFrameWidth : 800,
        minFrameHeight : 500,
        toolbars: [
            [
                'bold', 'italic', 'underline', 'forecolor', 'backcolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertunorderedlist', 'insertorderedlist', 'blockquote',
                'emotion', 'link', 'removeformat', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'paragraph', 'fontsize', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
                'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols'
            ]
        ],
        autoClearinitialContent: true,
        //关闭字数统计
        wordCount: false,
        //关闭elementPath
        elementPathEnabled: false
    });



    // 定时发布时间

    $('#starttime').datepicker({dateFormat: 'yy-mm-dd', format:'Y-m-d', timepicker:false});

    $(".time").find("input[type='radio']").each(function(){
        if($(this).val() == '2' && $(this).attr("checked") == "checked")
        {
            var obj = $(this).parent();
            obj.nextAll("select").removeClass("disabled").attr("disabled",false);
            obj.nextAll("input").removeClass("disabled").attr("disabled",false);
        }
        $(this).click(function(){
            if($(this).val() == '2'){
                var obj = $(this).parent();
                obj.nextAll("select").removeClass("disabled").attr("disabled",false);
                obj.nextAll("input").removeClass("disabled").attr("disabled",false);
            }
            else
            {
                var obj = $(this).parent().parent().parent();
                obj.find("select").addClass("disabled").attr("disabled",true);
                obj.find("input[type='text']").addClass("disabled").attr("disabled",true);
            }
        })
    });

    //验证
    var firstSub = true;

    $('#form').validator({
        theme: 'yellow_right',
        timely: true,

        rules: {
            price: function(element, params, field){
            	var parent_id  = $('input[name="common_parent_id"]').val();
                var min_price  = $('input[name="min_price"]').val();
                var max_price   = $('input[name="max_price"]').val();
                if ( $('input[name="price"]').val() != '' && $('input[name="market_price"]').val() != '' ) {
                    if (element.name == 'price') {

                        if (Number(element.value) > Number($('input[name="market_price"]').val())) {
                            return '不能高于市场价';

                        } else if(Number(parent_id)>0 && Number(element.value) > Number(max_price)){
                        	return '商品价格不能高于供货商设置的最高价';
                        }else if(Number(parent_id)>0 && Number(element.value) < Number(min_price)){
                        	return '商品价格不能低于供货商设置的最低价';
                        }else{
                            return true;
                        }
                    } else {
                        if (Number(element.value) < Number($('input[name="price"]').val())) {
                            return '不能低于商品价格';
                        } else {
                            return true;
                        }
                    }
                }
            },
			goods_max_price: function(element, params, field){
            	var parent_id  = $('input[name="common_parent_id"]').val();
                var min_price  = $('input[name="min_price"]').val();
                var max_price   = $('input[name="max_price"]').val();
                if ($('input[name="goods_max_price"]').val() != '') 
				{
                    if (element.name == 'goods_max_price') 
					{
                        if(Number(parent_id)>0 && Number(element.value) > Number(max_price))
						{
                        	return '商品规格的价格，必须在供应商设置的最低零售价和最高零售价之间。';
                        }else if(Number(parent_id)>0 && Number(element.value) < Number(min_price)){
                        	return '商品规格的价格，必须在供应商设置的最低零售价和最高零售价之间。';
                        }else{
                            return true;
                        }
                    }
                }
            },
            codeVerify: function ( element, params, field ) {
                var reg = /^\w+$/,
                    codeVal = element.value;

                if ( !reg.test(codeVal) ) {
                    return '仅支持输入字母、数字';
                }
            },

            limitVerify: function ( element, params ) {
                var isLimit = $('input[name="is_limit"]')[1];
                if ( isLimit.checked ) {
                    var reg = /^\d+$/;
                    if ( !reg.test(element.value) ) {
                        return '请填写正整数';
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        },

        fields: {
            'name':             'required;length[3~50];',
            'promotion_tips':   'length[~140];',
            'price':            'required;range[0.01~9999999];price;',
            'market_price':     'required;range[0.01~9999999];price;',
            'cost_price':       'range[0.01~9999999];',
            'stock':            'required;integer[+];range[1~99999999];',
            'cubage':           'required;range[0~99999];',
            'imagePath':        'required;',
            'service':          'length[~200];',
            'alarm':            'integer[+0];',
            'limit':            'limitVerify;',
            'code':             'codeVerify;length[~20];',
			'goods_max_price':  'goods_max_price;',
            packing_list: {
                rule: 'length[~201];',
                msg: {
                    length: "包装清单不能超过200个汉字"
                }
            }
        },

        valid: function(form){

            if (!checkUseSpec()) {
                Public.tips.warning("该商品必须选择规格属性，请选择后重试！");
                return false;
            }

           if ( firstSub ) {
                firstSub = false;
                //表单验证通过，提交表单到服务器
                $.post( SITE_URL + "?ctl=Seller_Goods&met=addOrEditShopGoods&typ=json", $('#form').serialize(), function(data) {

                    if( data.status == 200 ) {
                        Public.tips({ content: '保存成功！', type: 3 });
                        setTimeout(function () {
                            if ( data.data.action && data.data.action == 'edit' )
                            {
                            	if(data.data.dist_goods){
                            		window.location.href = SITE_URL + "?ctl=Seller_Supplier_Goods&met=online&typ=e";
                            	}else{
                                	window.location.href = SITE_URL + "?ctl=Seller_Goods&met=online&typ=e";
                               }
                            } else {
                                window.location.href = SITE_URL + "?ctl=Seller_Goods&met=add&action=goodsImageManage&typ=e&common_id=" + data.data.common_id;
                            }
                        }, 3000);
                    } else {
                        if(data.msg=='')
						{
							data.msg = '保存失败！';
						}
                        Public.tips({ content: data.msg, type: 1 });
                        firstSub = true;
                    }
                }).error(function(){
                    Public.tips({ content: "提交失败，请稍后再试！", type: 1 });
                    firstSub = true;
                });
            }
        }
    });

    $('#imagePath').on('invalid.field', function(e, result, me){
        var _top = $("#goodsImage").offset().top;
        window.scroll(0, _top);
    });

    $('dl[nctype="spec_group_dl"]').on('click', 'span[nctype="input_checkbox"] > input[type="checkbox"]',function(){
        into_array();
        goods_stock_set();
    });

    // 修改规格名称
    $('dl[nctype="spec_group_dl"]').on('click', 'input[type="checkbox"]', function(){

        pv = $(this).parents('li').find('span[nctype="pv_name"]');
        if(typeof(pv.find('input').val()) == 'undefined'){
            pv.html('<input type="text" maxlength="20" class="text" value="'+pv.html()+'" />');
        }else{
            pv.html(pv.find('input').val());
        }
    });

    /* AJAX添加规格值 */
	// 添加规格
	$('a[data-type="add-spec"]').click(function(){
		var _parent = $(this).parents('li:first');
		_parent.find('div[data-type="add-spec1"]').hide();
		_parent.find('div[data-type="add-spec2"]').show();
		_parent.find('input').focus();
	});
	// 取消
	$('a[data-type="add-spec-cancel"]').click(function(){
		var _parent = $(this).parents('li:first');
		_parent.find('div[data-type="add-spec1"]').show();
		_parent.find('div[data-type="add-spec2"]').hide();
		_parent.find('input').val('');
	});
	// 提交
	$('a[data-type="add-spec-submit"]').on('click',function(){
		var _parent = $(this).parents('li:first');
		eval('var data_str = ' + _parent.attr('data-param'));
		var _input = _parent.find('input');
		if(_input.val())
		{
			var url = SITE_URL + "?ctl=Seller_Goods_Spec&met=saveSpecValue&typ=json&position=storeAddGoods";
			$.getJSON(url, {class_id : data_str.class_id , spec_id : data_str.spec_id , name : _input.val()}, function(data){
				if (data.status == 200)
				{
                    var data = data.data;
					_parent.before('<li><span nctype="input_checkbox" data-type="input_checkbox"><input type="checkbox" name="spec_val[' + data_str.spec_id + '][' + data.spec_value_id + ']" nc_type="' + data.spec_value_id  + '" data-type="' + data.spec_value_id + '" value="' +_input.val()+ '" /></span><span nctype="pv_name" data-type="pv_name">' + _input.val() + '</span></li>');
					_input.val('');
				}
				_parent.find('div[data-type="add-spec1"]').show();
				_parent.find('div[data-type="add-spec2"]').hide();
			});
		}
	});

    //新增分类

    $('#add_sgcategory').on('click', function () {
        $(".sgcategory:last").after($(".sgcategory:last").clone(true).val(0));
    });

    // 选择店铺分类
    $('.sgcategory').unbind().change( function(){
        var _val = $(this).val();       // 记录选择的值
        $(this).val('0');               // 已选择值清零
        // 验证是否已经选择
        if (!checkSGC(_val)) {
            /*alert('该分类已经选择,请选择其他分类');*/
            Public.tips({content:'该分类已经选择,请选择其他分类', type: 1});
            return false;
        }
        $(this).val(_val);              // 重新赋值
    });

    // 验证店铺分类是否重复
    function checkSGC($val) {
        var _return = true;
        $('.sgcategory').each(function(){
            if ($val !=0 && $val == $(this).val() || $val == $(this).children(':checked').data('parent_id')) {
                _return = false;
            }
        });
        return _return;
    }

    //物流信息 所在地
    $('#area_1').on('change', function () {
        $('#_area_1').val($(this).val());
        if ( this.value == 0 ){
            if ( $('#area_2').length > 0 ) {
                $('#area_2').remove();
            }
        } else {
            $('#area_2').remove();
            var $this = $(this), pid = $(this).val(), BigCity = [1, 2, 9, 22];

            //排除直辖市

            if( $.inArray(Number(pid), BigCity) == -1  ) {

                $.post(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {pid: pid}, function (data) {
                    var data = data.data;
                    if (data.items && data.items.length > 0) {
                        var options = null, select = null;
                        for ( var i = 0; i < data.items.length; i++ ) {
                            if ( i == 0 ) $('#_area_2').val(data.items[i]['district_id']);
                            options += '<option value="' + data.items[i]['district_id'] + '">' + data.items[i]['district_name'] + '</option>';
                        }
                        select = '<select id="area_2" class="valid">' + options + '</select>';

                        $this.after( select );
                    }
                });
            }
        }
    });

    $('#area_1').parent().on(' change', '#area_2', function () {
        $('#_area_2').val($(this).val());
    });

    // 运费部分显示隐藏
    $('input[nctype="freight"]').click(function(){
        $('input[nctype="freight"]').nextAll('div[nctype="div_freight"]').hide();
        $(this).nextAll('div[nctype="div_freight"]').show();
    });

    $('#postageButton').on('click', function () {

        var falg = true;

        if ( falg ) {

            falg = false;

            $.dialog({
                title: '选择售卖区域',
                content: 'url:' + SITE_URL + '?ctl=Seller_Transport&met=chooseTranDialog&typ=e',
                data: { callback: callback },
                width: 800,
                height: 400,
                max: false,
                min: false,
                lock: true
            });
        }

    });

    function callback (data, win) {

        $('#transport_area_id').val(data.transport_area_id);
        $('#transport_area_name').html(data.transport_area_name);

        win.close();
    };

    //图片空间
    $('#image_space').on('click', function () {

        aloneImage = $.dialog({
            content: 'url: ' + SITE_URL + '?ctl=Upload&met=image&typ=e',
            height: 585,
            width: 900,
            data: { callback: function( list ) {
                    //只取第一张图片
                    $('#goodsImage').prop('src', list[0].src);
                    $('#imagePath').prop('value', list[0].src).isValid();
                }
            }
        })
    });

    $('span[nctype="pv_name"] > input').live('change',function(){
        change_img_name($(this));       // 修改相关的颜色名称
        into_array();           // 将选中的规格放入数组
        goods_stock_set();
    });

    //修改相关的颜色名称
    function change_img_name(Obj){
        var S = Obj.parents('li').find('input[type="checkbox"]');
        S.val(Obj.val());
        var V = $('tr[nctype="file_tr_'+S.attr('nc_type')+'"]');
        V.find('span[nctype="pv_name"]').html(Obj.val());
        V.find('input[type="file"]').attr('name', Obj.val());
    }

    // 修改规格名称
    $('input[nctype="spec_name"]').change(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        if ($(this).val() == '') {
            $(this).val(data_str.name);
        }
        $('th[nctype="spec_name_' + data_str.id + '"]').html($(this).val());
    });

    //限购
    $('input[name="is_limit"]').on('click', function () {
        var limit = $('input[name="limit"]').parent();
        if (this.value == 1) {
            limit.show();
        } else {
            limit.hide();
        }
    });

    //本店分类只能选择下级分类
    $("#add_sgcategory").parent().on('change', '[name="sgcate_id[]"]', function (e) {
        var val = $(this).val(), child;
        child = $(this).children("[data-parent_id="+ val +"]");
        if (child.length > 0) {
            $(this).val('');
            Public.tips.warning('请选择该分类下级');
        }
    })
});

function checkAllPrice(){
    var table = $('[nctype=spec_table]');
    console.log(table);

}

//check goods is open spec and using
function checkUseSpec() {
    var specGroup = $('[nctype=spec_group_dl]');
    if (specGroup.length > 0) {
        var flag = true;
        $.each(specGroup, function(i, v) {
            var specOfChecked = $(specGroup[i]).find("[type=checkbox]:checked");
            if (specOfChecked.length == 0) {
                flag = false;
                return false;
            }
        });
        return flag;
    } else {
        return true;
    }
}

//新增关联版式回调函数
function addLayout(data) {
    var $layout;

    data.position == 1
        ? $layout = $("select[name='formatid_top']")
        : $layout = $("select[name='formatid_bottom']");

    $layout.append("<option value=\""+ data.id +"\">"+ data.name +"</option>");
    $layout.val(data.id);
}
