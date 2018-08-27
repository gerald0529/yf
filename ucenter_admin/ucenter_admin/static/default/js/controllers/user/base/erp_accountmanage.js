var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data || {};
var callback = api.data.callback;

initPopBtns();
initField();
initEvent();
function initEvent(){
    this.$('#sign_time').datepicker(),
    this.$('#effective_date_start').datepicker(),
    this.$('#effective_date_end').datepicker()
}

function initField(){
    if(rowData.id){
        Public.ajaxPost(SITE_URL + "?ctl=User_Base&met=getErpUserList&typ=json",{id:rowData.id},function(b){
            b.data = b.data.items[0];

            $('#company_name').val(b.data.company_name);
            $('#company_phone').val(b.data.company_phone);
            $('#contacter').val(b.data.contacter);
            $('#sign_time').val(b.data.sign_time);
            $('#account_num').val(b.data.account_num);
            $('#user_name').val(b.data.user_name);
            $('#upload_path').val(b.data.upload_path);
            $('#business_agent').val(b.data.business_agent);
            $('#price').val(b.data.price);
            $('#effective_date_start').val(b.data.effective_date_start);
            $('#effective_date_end').val(b.data.effective_date_end);
            $('#company_name').val(b.data.company_name);
            $('#plantform_url').val(b.data.plantform_url);
            $('#bbc_url').val(b.data.bbc_url);

        })
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
    var service_id = rowData.id;
    var msg = oper == 'add' ? '新增用户' : '编辑用户';

    var params = $("#manage-form").serialize();
    $.dialog.confirm('所有信息填写正确么，确认保存？', function () {
        Public. ajaxPost(SITE_URL + '?ctl=User_Base&met=save&typ=json&service_id=' + rowData.id,params,function (b) {
            200 === b.status ? (parent.parent.Public.tips({content : msg + '成功！'}), callback(params, oper, window))  : (parent.parent.Public.tips({type:1, content : msg + '失败！' + b.msg}))
        })
    })
}

function resetForm(t)
{
    $('#company_name').val('');
    $('#company_phone').val('');
    $('#contacter').val('');
    $('#sign_time').val('');
    $('#account_num').val('');
    $('#user_name').val('');
    $('#upload_path').val('');
    $('#business_agent').val('');
    $('#price').val('');
    $('#effective_date_start').val('');
    $('#effective_date_end').val('');
    $('#company_name').val('');
    $('#plantform_url').val('');
    $('#bbc_url').val('');
}