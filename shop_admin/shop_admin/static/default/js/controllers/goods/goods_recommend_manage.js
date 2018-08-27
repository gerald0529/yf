var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
initFilter();

function initField(){
    if(rowData.id){
        $.get(SITE_URL + '?ctl=Goods_Goods&met=getGoodsRecommendById&typ=json&id='+rowData.id, function(data){
            if(data.status)
            {
                var defaultPage = Public.getDefaultPage();
                $.each(defaultPage.SYSTEM.goodsCatInfo, function(k,v){
                    if(data.data['goods_cat_id']!=0)
                    {
                        if(v['cat_id']==data.data['goods_cat_id'])
                        {
                            var cat_name = v['cat_name'];
                            $("#goods_cat").val(cat_name);
                            $("input[name=goods_cat_id]").val(data.data['goods_cat_id']);
                        }
                    }
                });
                $.each(data.data['items'],function(key,val){
                    var goods_name = val['common_name'];
                    var goods_pic  = val['common_image'];
                    var goods_id   = val['common_id'];

                    var obj = $("#selected_goods_list");
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
                    obj.find("ul").append('<li style="float:left;width:12%; margin:0 10px; border:2px solid #999;text-align:center;padding:10px; ">'+text_append+'</li>');
                });
            }
        });
    }
}

function initPopBtns(){
    var operName = oper == "add" ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: 'confirm',
        name: operName[0],
        focus: true,
        callback: function() {
            postData(oper, rowData.id);
            return false;
        }
    },{
        id: 'cancel',
        name: operName[1]
    });
}


function postData(oper, id){

    var id = rowData.id;
    var msg = oper == 'add' ? '新增' : '编辑';

    //params = {article_title:article_title, article_url:article_url, article_sort:article_sort, article_group_id:article_group_id, article_desc:article_desc, article_pic:article_pic,article_status:article_status};

    //rowData.id?params['article_id']=id:'';
    var cat_id = categoryTree.getValue()
    var params = $("#recommend_form").serialize();
    Public.ajaxPost( SITE_URL + '?ctl=Goods_Goods&typ=json&met=' + (oper == 'add' ? 'addGoodsRecommend&goods_cat_id='+cat_id : 'editGoodsRecommend&id='+id+'&goods_cat_id='+cat_id), params, function(data){
        if (data.status == 200) {
            rowData = data.data;
            rowData.operate = oper;
            parent.parent.Public.tips({content : msg + '成功！'});
            if(callback && typeof callback == 'function'){
                callback(rowData, oper, window);
            }
        } else {
            parent.parent.Public.tips({type:1, content : msg + '失败！' + data.msg});
        }
    });
}

function resetForm(data){
    $('#manage-form').validate().resetForm();
    $('#name').val('');
    $('#number').val(Public.getSuggestNum(data.locationNo)).focus().select();
}

function initFilter()
{
    //查询条件
    Business.filterBrand();

    //商品类别
    var opts = {
        width : 200,
        //inputWidth : (SYSTEM.enableStorage ? 145 : 208),
        inputWidth : 145,
        defaultSelectValue : '-1',
        //defaultSelectValue : rowData.categoryId || '',
        showRoot : false
    }

    categoryTree = Public.categoryTree($('#goods_cat'), opts);

}