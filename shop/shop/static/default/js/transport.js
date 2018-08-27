/* 运费模板JS
*/
$(function(){
    //记录当前起始值
    StartNum = $('.tbl-except>table>tbody>tr').size();
    //删除当前行的唯一标识
    var curDelNum;

    /*为指定地区设置运费,增加一行运费规则*/
    $('.trans-line').on('click','.t_addRule',function(){
        StartNum +=1;
        cell = RuleCell.replace(/CurNum/g,StartNum); 
        $(this).parent().parent().find('.tbl-except').find('table').append(cell);

    });

    /*首费离开校验*/
    $('.trans-line').on('blur','input[data-field="default_price"]',function (){
            var oNum = new Number($(this).val());
            oNum = oNum.toFixed(2);
            if (oNum > 999.99) oNum = 999.99;
            if (oNum=='NaN') oNum = '0.00'; 
            $(this).val(oNum);
            if($(this)[0].className=='w50 text input-error') $(this).removeClass('input-error');
            if($(this)[0].className=='w50 text input-error') $(this).removeClass('input-error');

            //如果是动态添加的首费,当所有首费输入框都不为空时，提示层span隐藏
            isRemove = true;
            $(this).parent().parent().parent().find('input[data-field="default_price"]').each(function(){
                    if ($(this).val()==''){
                            isRemove = false;return false;
                    }
            });
            //提示层span隐藏
            if (isRemove == true){
                    $(this).parent().parent().parent().parent().parent().parent().find('.tbl-attach').find('.J_SpecialMessage').find('span[error_type="default_price"]').css('display','none');
            }
    });

    /*续费离开校验*/
    $('.trans-line').on('blur','input[data-field="add_price"]',function (){
            var oNum = new Number($(this).val());
            oNum = oNum.toFixed(2);
            if (oNum > 999.99) oNum = 999.99;
            if (oNum=='NaN') oNum = '0.00';
            $(this).val(oNum);
            if($(this)[0].className=='w50  text input-error') $(this).removeClass('input-error');
            if($(this)[0].className=='w50  text input-error') $(this).removeClass('input-error');
            //如果是动态添加的首费,当所有续费输入框都不为空时，提示层span隐藏
            isRemove = true;
            $(this).parent().parent().parent().find('input[data-field="add_price"]').each(function(){
                    if ($(this).val()==''){
                            isRemove = false;return false;
                    }
            });
            //提示层span隐藏
            if (isRemove == true){
                    $(this).parent().parent().parent().parent().parent().parent().find('.tbl-attach').find('.J_SpecialMessage').find('span[error_type="add_price"]').css('display','none');
            }		
    });



    /*	自定义转成整形的方法*/
    jQuery.fn.toInt = function() {
            var s = parseInt(jQuery(this).val().replace( /^0*/,''));
            return isNaN(s) ? 0 : s;
    };



    /*	省份点击事件*/
    $('#dialog_areas').on('click','.J_Province',function(){
            if ($(this).attr('checked')){
                    //选择所有未被disabled的子地区
                    $(this).parent().find('.district-citys-sub').eq(0).find('input[type="checkbox"]').each(function(){
                            if (!$(this).attr('disabled')){
                                    $(this).attr('checked',true);
                            }else{
                                    $(this).attr('checked',false);
                            }
                    });
                    //计算并显示所有被选中的子地区数量
                    num = '('+$(this).parent().find('.district-citys-sub').eq(0).find('input:checked').size()+')';
                    if (num == '(0)') num = '';
                    $(this).parent().parent().find(".check_num").eq(0).html(num);

                    //如果该大区域所有省都选中，该区域选中
                    input_checked 	= $(this).parent().parent().parent().find('input:checked').size();
                    input_all 		= $(this).parent().parent().parent().find('input[type="checkbox"]').size();
                    if (input_all == input_checked){
                            $(this).parent().parent().parent().parent().find('.region_group').attr('checked',true);
                    }	

            }else{
                    //取消全部子地区选择，取消显示数量
                    $(this).parent().parent().find(".check_num").eq(0).html('');
                    $(this).parent().find('.district-citys-sub').eq(0).find('input[type="checkbox"]').attr('checked',false);
                    //取消大区域选择
                    $(this).parent().parent().parent().parent().find('.region_group').attr('checked',false);
            }
    });

    /*	大区域点击事件（华北、华东、华南...）*/
    $('#dialog_areas').on('click','.region_group',function(){
            if ($(this).attr('checked')){
                    //区域内所有没有被disabled复选框选中，带disabled说明已经被选择过了，不能再选
                    $(this).parent().parent().parent().find('input[type="checkbox"]').each(function(){
                            if (!$(this).attr('disabled')){
                                    $(this).attr('checked',true);
                            }else{
                                    $(this).attr('checked',false);
                            }				
                    });
                    //循环显示每个省下面的市级的数量
                    $(this).parent().parent().parent().find('.district-province-list').find('.district-province').each(function(){
                            //显示该省下面已选择的市的数量
                            num = '('+$(this).find('.district-citys-sub').find('input:checked').size()+')';
                            //如果是0，说明没有选择，不显示数量
                            if (num != '(0)'){
                                    $(this).find(".check_num").html(num);
                            }
                    });
            }else{
                    //区域内所有筛选框取消选中
                    $(this).parent().parent().parent().find('input[type="checkbox"]').attr('checked',false);
                    //循环清空每个省下面显示的市级数量
                    $(this).parent().parent().parent().find('.district-province-list').find('.district-province').each(function(){
                            $(this).find(".check_num").html('');
                    });
            }

    });

    /*	关闭弹出的市级小层*/
    $('#dialog_areas').on('click','.areas_icon_close',function(){ 
        $(this).parent().parent().parent().parent().removeClass('showCityPop');
    });

    /*	市级地区单事件*/
    $('#dialog_areas').on('click','.J_City',function(){
            //显示选择市级数量，在所属省后面
            num = '('+$(this).parent().parent().find('input:checked').size()+')';
            if (num=='(0)')num='';
            $(this).parent().parent().parent().find(".check_num").eq(0).html(num);
            //如果市级地区全部选中，则父级省份也选中，反之有一个不选中,则省份和大区域也不选中
            if (!$(this).attr('checked')){
                    //取消省份选择
                    $(this).parent().parent().parent().find('.J_Province').attr('checked',false);
                    //取消大区域选择
                    $(this).parent().parent().parent().parent().parent().parent().find('.region_group').attr('checked',false);
            }else{
                    //如果该省所有市都选中，该省选中
                    input_checked 	= $(this).parent().parent().find('input:checked').size();
                    input_all 		= $(this).parent().parent().find('input[type="checkbox"]').size();
                    if (input_all == input_checked){
                            $(this).parent().parent().parent().find('.J_Province').attr('checked',true);
                    }
                    //如果该大区域所有省都选中，该区域选中
                    input_checked 	= $(this).parent().parent().parent().parent().parent().find('input:checked').size();
                    input_all 		= $(this).parent().parent().parent().parent().parent().find('input[type="checkbox"]').size();
                    if (input_all == input_checked){
                            $(this).parent().parent().parent().parent().parent().parent().find('.region_group').attr('checked',true);
                    }
            }
    });

    /*	省份下拉事件*/
    $('#dialog_areas').on('click','.trigger',function () {

        objTrigger = this;objHead = $(this).parent();objPanel = $(this).next();
        if ($(this).next().css('display') == 'none'){
                //隐藏所有已弹出的省份下拉层，只显示当前点击的层
                $('.ks-contentbox').find('.district-province').removeClass('showCityPop');
                $(this).parent().parent().addClass('showCityPop');
        }else{
                //隐藏当前的省份下拉层
                $(this).parent().parent().removeClass('showCityPop');
        }
        //点击省，市所在的head与panel层以外的区域均隐藏当前层

        var de = document.documentElement?document.documentElement : document.body;
        de.onclick = function(e){
        var e = e || window.event;
        var target = e.target || e.srcElement;
        while(target){
            //循环最外层一个时，会出现异常
            try{
                //jquery 转成DOM对象，比较两个DOM对象
                if(target==$(objHead)[0])return true;
                if(target==$(objPanel)[0])return true;
                        }catch(ex){};
            target = target.parentNode;
        }
        $(objTrigger).parent().parent().removeClass('showCityPop');
    }
    });

    $('#title').blur(function(){
            if ($(this).val() !=''){
                    $('p[error_type="title"]').css('display','none');
            }
    });



    /*保存运费模板*/
    $('#submit_tpl').on('click',function(){
            $('.J_SpecialMessage').html(SpecialMessage);
            isSubmit = true;	
            //首件跟续件由于有默认值，鼠标离开也有默认值，这里只需判断首费与续费即可
            if($('.tbl-except').find('.cell-area').html() != null){
                    isShowError = false;
                    $('.tbl-except').find('input[data-field="default_price"]').each(function(){
                            if ($(this).val()==''){
                                    $(this).addClass('input-error');isShowError = true; isSubmit = false;return false;
                            }
                    });

                    if (isShowError){
                            $('.tbl-attach').find('span[error_type="default_price"]').show();
                    }
            }
            //地区JS空判断-------------------------------
            if($('.tbl-except').find('.cell-area').html() != null){
                    isShowError = false;
                    $('div[data-delivery="kd"]').find('input[type="hidden"]').each(function(){
                            if ($(this).val()==''){
                                    $(this).addClass('input-error'); isShowError = true; isSubmit = false;return false;
                            }
                    });

                    if (isShowError){
                            $('.tbl-attach').find('span[error_type="area"]').show();
                    }
            }	
            //运费模板名称校验
            if ($('#title').val()==''){
                    isSubmit = false;
                    $('p[error_type="title"]').css('display','');
            }else{
                    $('p[error_type="title"]').css('display','none');
            }
            if (isSubmit == true){
                    return true;
            }else{
                    return false;
            }
    });

    /*	选择运送区域*/
    $('.trans-line').on('click','a[entype="t_editArea"]',function () {
            curTransType = 'kd';
            //取消所有已选择的checkbox
            $('#J_CityList').find('input[type="checkbox"]').attr('checked',false).attr('disabled',false);

            //取消显示所有统计数量
            $('#J_CityList').find('.check_num').html('');

            //记录当前行的标识n1,n2,n3....
            curIndex = $(this).attr('data-group');

            //记录当前操作的行，选择完地区会向该区域抛出值
            objCurlArea = $('tr[data-group="'+curIndex+'"]').children(1);
            //记录已选择的所有省及市的value，SelectArea下标为value值，值为true，如江苏省SelectArea[320000]=true,南京市SelectArea[320100]=true
            SelectArea = new Array();

            //取得当前行隐藏域内的city值，放入SelectArea数组中
            var expAreas = $('input[name="areas['+curTransType+']['+curIndex.substring(1)+']"]').val();
            expAreas = expAreas.split('|||');
            expAreas = expAreas[0].split(',');
            try{
                if(expAreas[0] != ''){
                    for(var v in expAreas){
                        SelectArea[expAreas[v]] = true;
                    }
                }
                //初始化已选中的checkbox
                $('#J_CityList').find('.district-province').each(function(){
                    var count = 0;
                    $(this).find('input[type="checkbox"]').each(function(){
                        if(SelectArea[$(this).val()]==true){
                            $(this).attr('checked',true);
                            if($(this)[0].className!='J_Province') count++;
                        }
                    });
                    if (count > 0){
                        $(this).find('.check_num').html('('+count+')');
                    }

                });

                //循环每一行，如果一行省都选中，则大区载选中
                $('#J_CityList>li').each(function(){
                    $(this).find('.region_group').attr('checked',true);
                    father = this;
                    $(this).find('.J_Province').each(function(){
                        if (!$(this).attr('checked')){
                                $(father).find('.region_group').attr('checked',false);
                                return ;
                        }
                    });
                });
            }catch(ex){}
            //其它行已选择的地区，不能再选择了
            $(objCurlArea).parent().parent().find('.area-group').each(function(){
                    if ($(this).next().attr('name') != 'areas['+curTransType+']['+curIndex.substring(1)+']'){
                            expAreas = $(this).next().val().split('|||');
                            expAreas = expAreas[0].split(',');
                            //重置SelectArea
                            SelectArea = new Array();
                            try{
                                    if(expAreas[0] != ''){
                                            for(var v in expAreas){
                                                    SelectArea[expAreas[v]] = true;
                                            }
                                    }

                                    //其它行已选中的在这里都置灰
                                    $('#J_CityList').find('input[type="checkbox"]').each(function(){
                                            if(SelectArea[$(this).val()]==true){
                                                    $(this).attr('disabled',true).attr('checked',false);
                                            }
                                    });
                                    //循环每一行，如果一行的省都被disabled，则大区域也disabled
                                    $('#J_CityList>li').each(function(){
                                            $(this).find('.region_group').attr('disabled',true);
                                            father = this;
                                            $(this).find('.J_Province').each(function(){
                                                    if (!$(this).attr('disabled')){
                                                            $(father).find('.region_group').attr('disabled',false);
                                                            return ;
                                                    }
                                            });
                                    });				
                            }catch(ex){}
                    }
            });
            //定位弹出层的坐标
            $("#dialog_areas").css({'position' : 'absolute','display' : 'block', 'z-index' : '9999','top':'20%','border':'1px solid #E6E6E6'});
            $('.ks-ext-mask').css('display','block');

    }); 

    /*关闭选择区域层*/
    $('#dialog_areas').on('click','.ks-ext-close',function(){
        $("#dialog_areas").css('display','none');
        $("#dialog_batch").css('display','none');
        $('.ks-ext-mask').css('display','none');
        return false;
    });

    $('#dialog_batch').on('click','.ks-ext-close',function(){
        $("#dialog_areas").css('display','none');
        $("#dialog_batch").css('display','none');
        $('.ks-ext-mask').css('display','none');
        return false;
    });

    /*	关闭选择区域层*/
    $('#dialog_areas').on('click','.J_Cancel',function(){
        $("#dialog_areas").css('display','none');
        $("#dialog_batch").css('display','none');
        $('.ks-ext-mask').css('display','none');
    });

    $('#dialog_batch').on('click','.J_Cancel',function(){
        $("#dialog_areas").css('display','none');
        $("#dialog_batch").css('display','none');
        $('.ks-ext-mask').css('display','none');
    });
});