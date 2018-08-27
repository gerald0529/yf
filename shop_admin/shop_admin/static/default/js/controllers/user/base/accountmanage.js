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
        Public.ajaxPost(SITE_URL + "?ctl=User_Base&met=list1&typ=json",{id:rowData.id},function(b){
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

    /*
     if(!$('#manage-form').validate().form()){
     $('#manage-form').find('textarea.valid-error').eq(0).focus();
     return ;
     }
     */
    /*var	article_title = $.trim($('#article_title').val());
     var article_url = $.trim($('#article_url').val());
     var article_sort = $.trim($('#article_sort').val());
     var article_desc = $("textarea[name=article_desc]").val();
     var article_pic = $.trim($('#article_logo').val());
     var article_status =  $.trim($("input[name='article_status']:checked").val());*/
    var service_id = rowData.id;
    var msg = oper == 'add' ? '新增用户' : '编辑用户';

    //params = {article_title:article_title, article_url:article_url, article_sort:article_sort, article_group_id:article_group_id, article_desc:article_desc, article_pic:article_pic,article_status:article_status};

    //rowData.id?params['article_id']=id:'';
    var params = $("#manage-form").serialize();
    $.dialog.confirm('所有信息填写正确么，确认保存？', function () {
        Public. ajaxPost(SITE_URL + '?ctl=User_Base&met=save&typ=json&service_id=' + rowData.id,params,function (b) {
            200 === b.status ? (parent.parent.Public.tips({content : msg + '成功！'}), callback(params, oper, window))  : (parent.parent.Public.tips({type:1, content : msg + '失败！' + data.msg}))
        })
    })
}