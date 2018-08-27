var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
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

    var msg = oper == 'editNav' ? '编辑导航' : '新增导航';
    var cat_other_name = $.trim($('#cat_other_name').val());
    var cat_image      = $.trim($('#cat_logo').val());
    var recommend_cat = [];
    var brand_value = [];
    var goods_cat_id = id;
    for(var i=0;i<($("input[name='recommend_cat']:checked").length);i++)
    {
        recommend_cat[i] =  $("input[name='recommend_cat']:checked").eq(i).val();
    }
    for(var i=0;i<($("input[name='brand_value']:checked").length);i++)
    {
        brand_value[i] = $("input[name='brand_value']:checked").eq(i).val();
    }
    var adv_image      = $.trim($('#adv_logo').val());
    var advs_image      = $.trim($('#advs_logo').val());
    params = {goods_cat_id:goods_cat_id,cat_other_name:cat_other_name,cat_image:cat_image,recommend_cat:recommend_cat,brand_value:brand_value,adv_image:adv_image,advs_image:advs_image};

    Public.ajaxPost( SITE_URL + '?ctl=Goods_Cat&typ=json&met=' + (oper == 'addNav' ? 'addNav' : 'editNav'), params, function(data){
        if (data.status == 200) {
            rowData = data.data;
            rowData.operate = oper;
            parent.parent.Public.tips({content : msg + '成功！'});
        } else {
            parent.parent.Public.tips({type:1, content : msg + '失败！' + data.msg});
        }
    });
}
function initField()
{
    var id = rowData.id;
    $.get('./index.php?ctl=Goods_Cat&met=getNav&typ=json&id='+id,function(a){
        if(a.status==200)
        {
            var b= a.data;
            $('#cat_other_name').val(b.goods_cat_nav_name);
            if(b.goods_cat_nav_brand)
            {
                $.each(b.goods_cat_nav_brand, function(key,val){
                    var names = 'recommend_'+val;
                    $("#"+names).attr('checked', true);
                });
            }
            if(b.goods_cat_nav_recommend)
            {
                $.each(b.goods_cat_nav_recommend, function(name,value){
                    var name_re = 'brand_'+value;
                    $("#"+name_re).attr('checked', true);
                });
            }
            if(b.goods_cat_nav_pic)
            {
                $('#cat_image').attr('src', b.goods_cat_nav_pic);
                $('#cat_logo').val(b.goods_cat_nav_pic);
            }

            if(b.goods_cat_nav_adv)
            {
                $('#adv_image').attr('src', b.goods_cat_nav_adv[0]);
                $('#adv_logo').val(b.goods_cat_nav_adv[0]);
                $('#advs_image').attr('src', b.goods_cat_nav_adv[1]);
                $('#advs_logo').val(b.goods_cat_nav_adv[1]);
            }
        }
    });
}