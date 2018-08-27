// 分类选择
function selClass(obj){
    $('.item_list').css('background','');
    $("#span").hide();
    $("#dt").show();
    $("#dd").show();
    $(obj).siblings('li').children('a').attr('class','');
    $(obj).children('a').attr('class','selected');
    tonextClass(obj.id);
}

function tonextClass(text){
    var valarray = text.split('|');
    $('#cat_id').val(valarray[0]);
    $('#dataLoading').show();
    var url = SITE_URL + '?ctl=Seller_Goods_Cat&met=cat&typ=json&cat_id='+valarray[0]+'&deep='+(valarray[1]*1+1);
    $.getJSON(url,function(data){
        data = data.data;
        if(data && data.length > 0){
            $('#button_next_step').attr('disabled',true).removeClass('bbc_seller_submit_btns').addClass('bbc_sellerGray_submit_btns');
            var str = '';
            var class_div_id = parseInt(valarray[1])+1;
            $.each(data, function(i, cat_row){
                str += '<li onclick="selClass(this);" id="'+cat_row.cat_id+'|'+class_div_id+'"><a href="javascript:void(0)"><i class="arrow iconfont icon-btnrightarrow"></i>'+cat_row.cat_name+'</a></li>';
            });
            $('#class_div_'+class_div_id).parents('.item_list').removeClass('blank');
            for (j = class_div_id; j <= 4; j++) {
                $('#class_div_'+(j+1)).parents('.item_list').addClass('blank');
            }
            $('#class_div_'+class_div_id).empty();
            $('#class_div_'+class_div_id).append(str);
            $('#class_div_'+class_div_id).nextAll('div').children('ul').empty();
            var str="";
            $.each($('a[class=selected]'),function(i){
                str+=$(this).html()+"&nbsp;&nbsp;";
            });
            /*str = str.substring(0,str.length-20);
            str = str.substring(41,str.length);*/
            $('#dd').html(str);
            $('#dataLoading').hide();
            // 2016 07 27 Ly
            /*if ( (valarray[1]*1+1) == 4 ) {
                $.dialog({
                    title: '选择商品分类',
                    width: '380px',
                    height: '400px',
                    lock: true,
                    cancel: true,
                    data: { data: data },
                    ok: function (win) {
                        var selected = $(this.content.document).find('.selected');
                        if ( selected.length > 0 ) {
                            $('#cat_id').val(selected.parent().attr('id'));
                            $('#button_next_step').parents('form').submit();
                        } else {
                            return false;
                        }
                    },
                    content: "url:" + SITE_URL + "?ctl=Seller_Goods&met=catListManage&typ=e"
                })
            }*/
        }
        else
        {
            for(var i= parseInt(valarray[1]); i < 3; i++){
                $('#class_div_'+(i+1)).empty();
            }
            var str="";
            $.each($('a[class=selected]'),function(i){
                str+=$(this).html()+"&nbsp;&nbsp;";
            });
            str = str.substring(41,str.length);
            $('#dd').html(str);
            disabledButton();
            $('#dataLoading').hide();
        }
    });
}
function disabledButton(){
    if($('#cat_id').val() != ''){
        $('#button_next_step').attr('disabled',false).addClass('bbc_seller_submit_btns').removeClass('bbc_sellerGray_submit_btns');
    }else {
        $('#button_next_step').attr('disabled',true).removeClass('bbc_seller_submit_btns').addClass('bbc_sellerGray_submit_btns');
    }
}