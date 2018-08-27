var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();

function initField(){
    if(rowData.id){
        $('#position_title').val(rowData.position_title);

        if(rowData.position_type==1)
        {
            $("#position_type1").attr('checked', true);
            $("#position_type0").attr('checked', false);
            $('[for="position_type1"]').addClass('selected');
            $('[for="position_type0"]').removeClass('selected');
            $('#position_content').val(rowData.position_content);
            $('#position_url_con').val(rowData.position_url);
            $("#add_content").show();
            $("#add_pic").hide();
        }
        else
        {
            $("#position_type1").attr('checked', false);
            $("#position_type0").attr('checked', true);
            $('[for="position_type1"]').removeClass('selected');
            $('[for="position_type0"]').addClass('selected');
            $("#position_image").attr('src',rowData.position_pic);
            $("#position_logo").val(rowData.position_pic);
            $("#position_url_con").val(rowData.position_url);
            $("#add_content").hide();
            $("#add_pic").show();
        }
        if(rowData.position_alert_type==1)
        {
            $("#position_alert_type1").attr('checked', true);
            $("#position_alert_type0").attr('checked', false);
        }
        else if(rowData.position_alert_type==0)
        {
            $("#position_alert_type0").attr('checked', true);
            $("#position_alert_type1").attr('checked', false);
        }
        $("textarea[name=position_code]").val(rowData.position_code);
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
    var position_title = $.trim($('#position_title').val());
    var position_type = $.trim($("input[name='position_type']:checked").val());
    if(position_type==1)
    {
        var position_pic = '';
        var position_con = $.trim($('#position_content').val());
        var position_url = $.trim($('#position_url_con').val());
    }
    else if(position_type==0)
    {
        var position_pic = $.trim($('#position_logo').val());
        var position_con = '';
        var position_url = $.trim($('#position_url_pic').val());
    }
    var position_code = $("textarea[name=position_code]").val();
    var position_alert_type = $.trim($("input[name='position_alert_type']:checked").val());
    /*var	article_title = $.trim($('#article_title').val());
    var article_url = $.trim($('#article_url').val());
    var article_sort = $.trim($('#article_sort').val());
    var article_desc = $("textarea[name=article_desc]").val();
    var article_pic = $.trim($('#article_logo').val());
    var article_status =  $.trim($("input[name='article_status']:checked").val());
    var article_group_id = group.getValue();*/

    var msg = oper == 'add' ? '新增推荐位' : '编辑推荐位';

    params = {position_title:position_title,position_type:position_type,position_pic:position_pic,position_con:position_con,position_url:position_url,position_alert_type:position_alert_type,position_code:position_code};

    rowData.id?params['position_id']=id:'';

    Public.ajaxPost( SITE_URL + '?ctl=Rec_Position&typ=json&met=' + (oper == 'add' ? 'addRecPosition' : 'editRecPosition'), params, function(data){
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