var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();

function initField(){
    if(rowData.id){
        $('#nav_title').val(rowData.nav_title);
        $('#nav_url').val(rowData.nav_url);
        $('#nav_displayorder').val(rowData.nav_displayorder);

        if(rowData.nav_new_open==1)
        {
            $("#nav_new_open1").attr('checked', true);
            $("#nav_new_open0").attr('checked', false);
            $('[for="nav_new_open1"]').addClass('selected');
            $('[for="nav_new_open0"]').removeClass('selected');
        }
        else
        {
            $("#nav_new_open1").attr('checked', false);
            $("#nav_new_open0").attr('checked', true);
            $('[for="nav_new_open1"]').removeClass('selected');
            $('[for="nav_new_open0"]').addClass('selected');
        }

        if(rowData.nav_active==1)
        {
            $("#nav_active1").attr('checked', true);
            $("#nav_active0").attr('checked', false);
            $('[for="nav_active1"]').addClass('selected');
            $('[for="nav_active0"]').removeClass('selected');
        }
        else
        {
            $("#nav_active1").attr('checked', false);
            $("#nav_active0").attr('checked', true);
            $('[for="nav_active1"]').removeClass('selected');
            $('[for="nav_active0"]').addClass('selected');
        }

        var typ_name = 'nav_type_'+rowData.nav_type;
        $("#"+typ_name).attr('checked', true);
        var location_name = 'nav_location_'+rowData.nav_location;
        $("#"+location_name).attr('checked', true);
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

    /*
     if(!$('#manage-form').validate().form()){
     $('#manage-form').find('textarea.valid-error').eq(0).focus();
     return ;
     }
     */
    var	nav_title = $.trim($('#nav_title').val());
    var nav_url = $.trim($('#nav_url').val());
    var nav_displayorder = $.trim($('#nav_displayorder').val());
    var nav_new_open = $("input[name='nav_new_open']:checked").val();
    var nav_active = $("input[name='nav_active']:checked").val();
    var nav_type = $("input[name='nav_type']:checked").val();
    var nav_location = $("input[name='nav_location']:checked").val();

    var msg = oper == 'add' ? '新增导航' : '编辑导航'; 

    if(!nav_type){
        parent.Public.tips({type: 1, content: "导航类型必须" });
        return false;
    }

    if(!nav_title){
        parent.Public.tips({type: 1, content: "标题必须" });
        return false;
    }

    params = {nav_title:nav_title,nav_url:nav_url,nav_displayorder:nav_displayorder,nav_new_open:nav_new_open,nav_type:nav_type,nav_location:nav_location,nav_active:nav_active};

    rowData.id?params['nav_id']=id:'';

    Public.ajaxPost( SITE_URL + '?ctl=Platform_Nav&typ=json&met=' + (oper == 'add' ? 'addPlatformNav' : 'editPlatformNav'), params, function(data){
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