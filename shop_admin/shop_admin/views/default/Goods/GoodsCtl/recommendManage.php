<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<form id="recommend_form" method="post">
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label for="article_title"><em>*</em>商品分类</label>
            </dt>
            <dd class="opt">
                <input type="hidden" value="" name="goods_cat_id" />
                <input type="text" value="" name="goods_cat" id="goods_cat" class="ui-input">
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="article_title"><em>*</em>商品推荐</label>
            </dt>
            <dd class="opt">
                <input type="text" value="" name="goods_name" id="goods_name" class="ui-input">
                <a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row" id="selected_goods_list">
            <dt class="tit">已推荐商品</dt>
            <dd class="opt">
                <input type="hidden" name="valid_recommend" id="valid_recommend" value="">
                <span class="err"></span>
                <ul class="dialog-goodslist-s1 goods-list scrollbar-box">
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">选择要推荐的商品</dt>
            <dd class="opt">
                <div id="show_recommend_goods_list" class="show-recommend-goods-list scrollbar-box"></div>
                <p class="notic">最多可推荐4个商品</p>
            </dd>
        </dl>
    </div>
</form>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_recommend_manage.js" charset="utf-8"></script>
<script>
    $('#search').on('click',function(){
        var gc_id = categoryTree.getValue();
        if(!(gc_id > 0))
        {
            gc_id = $('input[name=goods_cat_id]').val();
        }
        if(gc_id>0){
            $.get(SITE_URL + '?ctl=Goods_Goods&met=getCatGoodsList&typ=json&goods_cat_id='+gc_id+'&goods_name='+$('#goods_name').val(),function(a){
                if(a.status==200)
                {
                    var str='';
                    str+= "<ul class='dialog-goodslist-s2'>";
                    $.each(a.data['items'],function(key,val){
                        str+= "<li style='float:left;width:12%; margin:0 10px 10px 0; border:2px solid #999;text-align:center;padding:10px; '>";
                        str+="<div class='goods-pic' style='height:68px;overflow:hidden;' onclick='select_recommend_goods("+val['common_id']+");'>";
                        str+="<span class='ac-ico'></span>";
                        str+="<span class='thumb size-72x72'>";
                        str+="<i></i>";
                        str+="<img width='60' src="+val['common_image']+" goods_name="+val['common_name']+" goods_id="+val['common_id']+" title="+val['common_name']+">";
                        str+="</span>";
                        str+="</div>";
                        str+="<div class='goods-name' style='height:40px;overflow:hidden;line-height:20px;'>";
                        str+="<a target='_blank' href='javascript:;' class='cur-text'>"+val['common_name']+"</a>";
                        str+="</div>";
                        str+="</li>";
                    });
                    str+="</ul>";
                    $('#show_recommend_goods_list').html(str);
                }
            });
            //$('#show_recommend_goods_list').load(SITE_URL + '?ctl=Goods_Goods&met=getCatGoodsList&typ=json&goods_cat_id='+gc_id+'&goods_name='+$('#goods_name').val());
        }
        else
        {
            alert('请选择商品类别');
            return false;
        }
    });
    function select_recommend_goods(goods_id) {
        if (typeof goods_id == 'object') {
            var goods_name = goods_id['goods_name'];
            var goods_pic = goods_id['goods_image'];
            var goods_id = goods_id['goods_id'];
        } else {
            var goods = $("#show_recommend_goods_list img[goods_id='"+goods_id+"']");
            var goods_pic = goods.attr("src");
            var goods_name = goods.attr("goods_name");
        }
        var obj = $("#selected_goods_list");
        if(obj.find("img[goods_id='"+goods_id+"']").size()>0) return;//避免重复
        if(obj.find("ul>li").size()>=4){
            alert('最多可推荐4个商品');
            return false;
        }
        var text_append = '';
        text_append += '<div onclick="del_recommend_goods(this,'+goods_id+');" class="goods-pic">';
        text_append += '<span class="ac-ico"></span>';
        text_append += '<span class="thumb size-72x72">';
        text_append += '<i></i>';
        text_append += '<img width="72" goods_id="'+goods_id+'" title="'+goods_name+'" goods_name="'+goods_name+'" src="'+goods_pic+'" />';
        text_append += '</span></div>';
        text_append += '<div class="goods-name">';
        text_append += goods_name+'</a>';
        text_append += '</div>';
        text_append += '<input name="goods_id_list[]" value="'+goods_id+'" type="hidden">';
        obj.find("ul").append('<li style="float:left;width:12%;">'+text_append+'</li>');
    }
    function del_recommend_goods(obj,goods_id) {
        $(obj).parent().remove();
    }

    var goods_list_json = $.parseJSON('[]');
    $.each(goods_list_json,function(k,v){
        select_recommend_goods(v);
    });

</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>