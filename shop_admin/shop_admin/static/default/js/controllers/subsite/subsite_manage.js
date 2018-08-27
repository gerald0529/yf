function initField()
{   
    // 新增
    if(api.data.parent_id)
    {
        $.get(SITE_URL + '?ctl=Subsite_Config&met=getSubsiteName&typ=json&id=' + api.data.parent_id, function(a){
            if(a.status==200)
            {
                $("#parent_subsite").val(a.data.sub_site_name);
                $("#parent_id").val(a.data.id);
            }
        });
    }
    // 编辑
    if (rowData.subsite_id)
    {   
        // 请求指定分站id的信息并分配至页面
        $.get(SITE_URL + '?ctl=Subsite_Config&met=getOneSubsite&typ=json&subsite_id=' + rowData.subsite_id, function(sub){
            if(sub.status==200){
                $("#subsite_id").val(sub.data.subsite_id);
                $("#sub_site_name").val(sub.data.sub_site_name);
                $("#sub_site_domain").val(sub.data.sub_site_domain);
                $("#sub_site_template").val(sub.data.sub_site_template);
                $("#sub_site_des").val(sub.data.sub_site_des);
                $("#sub_site_copyright").val(sub.data.sub_site_copyright);
                $("#sub_site_web_title").val(sub.data.sub_site_web_title);
                $("#sub_site_web_keyword").val(sub.data.sub_site_web_keyword);
                $("#sub_site_web_des").val(sub.data.sub_site_web_des);
                $("#sub_site_logo").val(sub.data.sub_site_logo);
                $("#setting_logo_image").attr('src',sub.data.sub_site_logo);

                
            }

        });
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.subsite_id);
            return cancleGridEdit(), $("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e){   

 
    var sub_site_name           = $.trim($("#sub_site_name").val()),
        parent_subsite          = $.trim($("#parent_id").val()),
        sub_site_domain         = $.trim($("#sub_site_domain").val()),
        sub_site_template       = $.trim($("#sub_site_template").val()),
        sub_site_des            = $.trim($("#sub_site_des").val()),
        sub_site_copyright      = $.trim($("#sub_site_copyright").val()),
        sub_site_web_title      = $.trim($("#sub_site_web_title").val()),
        sub_site_web_keyword    = $.trim($("#sub_site_web_keyword").val()),
        sub_site_web_des        = $.trim($("#sub_site_web_des").val()),
        sub_site_logo           = $.trim($("#sub_site_logo").val()),

        n = "add" == t ? "新增分站" : "修改分站";

    var params = {sub_site_name: sub_site_name, parent_subsite:parent_subsite,sub_site_domain:sub_site_domain,sub_site_des:sub_site_des,sub_site_template:sub_site_template,sub_site_copyright:sub_site_copyright,sub_site_web_title:sub_site_web_title,sub_site_web_keyword:sub_site_web_keyword,sub_site_web_des:sub_site_web_des,sub_site_logo:sub_site_logo};
    e ? params.subsite_id= e : '';
    Public.ajaxPost(SITE_URL +"?ctl=Subsite_Config&typ=json&met=" + ("add" === t ? "addSubsite" : "editSubsite"), params, function (e)
    {
        if (200 == e.status)
        {
            parent.parent.Public.tips({content: n + "成功！"});
            callback && "function" == typeof callback && callback(e.data, t, window)
        }
        else
        {
            parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
        }
    })
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();