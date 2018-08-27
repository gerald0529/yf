var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();

function initField(){
    if(rowData.id){
        $('#member_agreement_title').val(rowData.member_agreement_title);
        $('#member_agreement_image').attr('src',rowData.member_agreement_pic);
        $('#member_agreement_logo').val(rowData.member_agreement_pic);
        ue.ready(function() {
            ue.setContent(rowData.member_agreement_content);
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

    /*
     if(!$('#manage-form').validate().form()){
     $('#manage-form').find('textarea.valid-error').eq(0).focus();
     return ;
     }
     */
   /* var	member_agreement_title = $.trim($('#member_agreement_title').val());
    var member_agreement_content = $("textarea[name=member_agreement_content]").val();
    var member_agreement_pic = $.trim($('#member_agreement_logo').val());*/

    var msg = oper == 'add' ? '新增用户协议' : '编辑用户协议';

   /* params = {member_agreement_title:member_agreement_title, member_agreement_content:member_agreement_content, member_agreement_pic:member_agreement_pic};*/
    var params = $("#article_form").serialize();

    Public.ajaxPost( SITE_URL + '?ctl=Member_Agreement&typ=json&met=' + (oper == 'add' ? 'addMmberAgreement' : 'editMemberAgreement&member_agreement_id='+id), params, function(data){
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