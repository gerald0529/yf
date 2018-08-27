var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
initEvent();

function initField(){
    if(rowData.id){
        $('#common_id').html(rowData.common_id);
        $('#common_name').html(rowData.common_name);
        $('#common_id_input').val(rowData.common_id);
    }
}

function initEvent(){
    var $number = $('#number');

    Public.limitInput($number, /^[a-zA-Z0-9\-_]*$/);
    Public.bindEnterSkip($('#manage-wrap'), postData, oper, rowData.id);
    initValidator();
    $number.focus().select();
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

function initValidator(){
    $.validator.addMethod('number', function(value){
        return /^[a-zA-Z0-9\-_]*$/.test(value);
    });

    $('#manage-form').validate({
        rules: {
            common_state_remark: {
                required: true
            }
        },
        messages: {
            common_state_remark: {
                required: '违规下架理由不能为空'
            }
        },
        errorClass: 'valid-error'
    });
}

function postData(oper, id){
    if(!$('#manage-form').validate().form()){
        $('#manage-form').find('textarea.valid-error').eq(0).focus();
        return ;
    }
    var	common_state_remark = $.trim($('#common_state_remark').val());
    var msg = oper == 'add' ? '新增商品' : '商品下架';
    if(rowData.id){
        params = { common_id: id, common_state: Goods_CommonModel.GOODS_STATE_ILLEGAL, common_state_remark:common_state_remark};
    }else{
        params = {};
    }

    Public.ajaxPost( SITE_URL + '?ctl=Goods_Goods&typ=json&met=' + (oper == 'add' ? 'add' : 'editCommonState'), params, function(data){
        if (data.status == 200) {
            rowData.common_state = Goods_CommonModel.GOODS_STATE_ILLEGAL;
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