function initField()
{
	$.get('./admin.php?ctl=Database_Backup&met=getBackupList&typ=json&file='+api.data.file,function(data){
		if(data.status==200)
		{
			var a_str="";
			for(var i=0;i<data.data.length;i++)
			{
				a_str+="<input type='checkbox' value='"+data.data[i]+"' name='database[]'><span class='mg-right'>"+data.data[i]+'</span>';
			}
			$('#database_list').html(a_str);
		}
	});
}
function select_all()
{
	if($('#database_select_all').is(':checked'))
	{
		$('#database_list input').prop('checked','checked');
	}
	else
	{
		$('#database_list input').removeProp('checked');
	}
}
function select_reverse()
{
	if($('#database_select_all').is(':checked'))
		$('#database_select_all').removeProp('checked');
	for(var i=0;i<$('#database_list input').length;i++)
	{
		if($('#database_list input').eq(i).is(':checked'))
		{
			$('#database_list input').eq(i).removeProp('checked');
		}
		else
		{
			$('#database_list input').eq(i).prop('checked','checked');
		}
	}
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData();
        }
    }, {id: "cancel", name: t[1]})
}
function postData()
{
	var db_name='';
	if($('#database_list input:checked').length<1)
	{
		parent.parent.Public.tips({type: 2,content: "未选择要恢复的数据库"});
	}
	else
	{
		for(var i=0;i<$('#database_list input:checked').length;i++)
		{
			if(i!=0)
				db_name+=',';
			db_name+=$('#database_list input:checked').eq(i).val();
		}
		callback && "function" == typeof callback && callback(api.data.file, db_name)
	}
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();