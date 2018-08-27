function initEvent()
{
	$("#sql_exec").click(function (t)
    {
        t.preventDefault();
		var s=$('#sql_content').val(),a=/\s+/,b = /(^|;)\s*ALTER\s+TABLE\s+((`[^`]+`)|([A-Za-z0-9_$]+))\s+DROP\s/i,c = /(^|;)\s*DELETE\s+FROM\s/i,d = /(^|;)\s*TRUNCATE\s/i,e=/(^|;)\s*DROP\s+(IF EXISTS\s+)?(TABLE|DATABASE|PROCEDURE)\s/i;
		if(s.replace(a,"")!="")
		{
			if(b.test(s)||c.test(s)||d.test(s)||e.test(s))
			{
				parent.parent.Public.tips({type: 1, content: "高危操作，请联系管理员！"});
			}
			else
				Business.verifyRight("INVLOCTION_ADD") && postData(s);
		}
    });
}
function postData(s)
{
	var params={sql_content:s};
	Public.ajaxPost("./index.php?ctl=Database_Update&typ=json&met=manage", params, function (e)
	{
		if (200 == e.status)
		{
			parent.parent.Public.tips({content: "执行成功！"});
		}
		else
		{
			parent.parent.Public.tips({type: 1, content: "执行失败！语句：" + e.msg})
		}
	});
}
initEvent();
