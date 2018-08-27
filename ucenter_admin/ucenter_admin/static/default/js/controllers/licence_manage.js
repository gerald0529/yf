var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
initEvent();

function initField(){
    if(rowData.id){
        $('#licence_effective_startdate').val(rowData.licence_effective_startdate);
        $('#licence_effective_enddate').val(rowData.licence_effective_enddate);
        $('#app_id').val(rowData.app_id);
        $('#licence_domain').val(rowData.licence_domain);
        $('#licence_id').val(rowData.id);
        $('#company_name').val(rowData.company_name);
    }

    
    var appCombo = Business.categoryCombo($('#app_id_combo'), {
        editable: false,
        extraListHtml: '',
        /*
        addOptions: {
            value: -1,
            text: '选择应用'
        },
        */
        defaultSelected: 0,
        trigger: true,
        width: 120,
        callback: {
            onChange: function (data)
            {
                $('#app_id').val(this.getValue());
            }
        }
    }, 'app_id');

    appCombo.selectByValue(rowData.app_id);
    

    this.$_beginDate = $('#licence_effective_startdate'),
    this.$_endDate = $('#licence_effective_enddate'),

    this.$_beginDate.datepicker(),
    this.$_endDate.datepicker()
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
            common_verify_remark: {
                required: true
            }
        },
        messages: {
            common_verify_remark: {
                required: '违规审核理由不能为空'
            }
        },
        errorClass: 'valid-error'
    });
}

function postData(oper, id){

    var	licence_effective_startdate = $.trim($('#licence_effective_startdate').val());
    var licence_effective_enddate = $.trim($('#licence_effective_enddate').val());
    var app_id = $.trim($('#app_id').val());
    var licence_id = $.trim($('#licence_id').val());
    var licence_domain = $.trim($('#licence_domain').val());
    var company_name = $.trim($('#company_name').val());

    var msg = oper == 'add' ? '新增证书' : '编辑证书';

    params = {
        licence_effective_startdate:licence_effective_startdate,
        licence_effective_enddate:licence_effective_enddate,
        licence_app_id:app_id,
        company_name:company_name,
        licence_id:licence_id,
        licence_domain:licence_domain
    };

    Public.ajaxPost( SITE_URL + '?ctl=Licence&typ=json&met=' + (oper == 'add' ? 'add' : 'edit'), params, function(data){
        if (data.status == 200) {
            rowData = data.data;
            rowData.operate = oper;
            parent.parent.Public.tips({content : msg + '成功！'});

            console.info(rowData);
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