var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
initEvent();

function initField(){
    if(rowData.id){
        $('#article_title').val(rowData.article_title);
        $('#article_url').val(rowData.article_url);
        $('#article_sort').val(rowData.article_sort);

        //$("textarea[name=article_desc]").val(rowData.article_desc);
        //$("#article_desc").append(rowData.article_desc);
        ue.ready(function() {
            ue.setContent(rowData.article_desc);
        });
        if(rowData.article_status==1)
        {
            $("#article_status1").attr('checked', true);
            $("#article_status2").attr('checked', false);
            $('[for="article_status1"]').addClass('selected');
            $('[for="article_status2"]').removeClass('selected');
        }
        else
        {
            $("#article_status1").attr('checked', false);
            $("#article_status2").attr('checked', true);
            $('[for="article_status1"]').removeClass('selected');
            $('[for="article_status2"]').addClass('selected');
        }
		if(rowData.article_type==1)
        {
            $("#article_type1").attr('checked', true);
            $("#article_type2").attr('checked', false);
            $('[for="article_type1"]').addClass('selected');
            $('[for="article_type2"]').removeClass('selected');
        }
        else
        {
            $("#article_type1").attr('checked', false);
            $("#article_type2").attr('checked', true);
            $('[for="article_type1"]').removeClass('selected');
            $('[for="article_type2"]').addClass('selected');
        }
        $('#article_image').attr('src',rowData.article_pic);
        $('#article_logo').val(rowData.article_pic);
    }
}

function initEvent(){
    $("#type").data("defItem",["article_group_id",rowData.article_group_id]);
    group = $("#type").combo({
        data: SITE_URL + "?ctl=Article_Group&met=queryAllGroup&typ=json",
        value: "article_group_id",
        text: "article_group_title",
        width: 130,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.article_group_id ? $("#type").data("defItem") : void 0
    }).getCombo();
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
    var article_group_id = group.getValue();
    var article_id = rowData.id;
    var msg = oper == 'add' ? '新增文章' : '编辑文章';

    //params = {article_title:article_title, article_url:article_url, article_sort:article_sort, article_group_id:article_group_id, article_desc:article_desc, article_pic:article_pic,article_status:article_status};

    //rowData.id?params['article_id']=id:'';
    var params = $("#article_form").serialize();
    Public.ajaxPost( SITE_URL + '?ctl=Article_Base&typ=json&met=' + (oper == 'add' ? 'addArticleBase' : 'editArticleBase&article_id='+article_id) + '&article_group_id='+article_group_id, params, function(data){
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