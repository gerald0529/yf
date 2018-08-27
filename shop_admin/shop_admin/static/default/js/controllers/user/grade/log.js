//编辑等级设置
$(function ()
{
    $('#grade-edit-form').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            'gr[1][user_grade_demand]': 'required;integer[+0];',
            'gr[2][user_grade_demand]': 'required;integer[+0];',
            'gr[3][user_grade_demand]': 'required;integer[+0];',
            'gr[4][user_grade_demand]': 'required;integer[+0];',
            'gr[5][user_grade_demand]': 'required;integer[+0];',
			'gr[1][user_grade_name]': 'required;',
            'gr[2][user_grade_name]': 'required;',
            'gr[3][user_grade_name]': 'required;',
            'gr[4][user_grade_name]': 'required;',
            'gr[5][user_grade_name]': 'required;'
        },
        valid: function (form)
        {	
            parent.$.dialog.confirm('确认更改会员等级设置？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=User_Grade&met=editGradeLog&typ=json', $("#grade-edit-form").serialize(), function (data)
                    {
                        if (data.status == 200)
                        {
                            parent.Public.tips({content: '编辑成功！'});
                            
                        }
                        else
                        {
                            parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                        }
						
                       
                    });
				
			});
        },
    }).on("click", "a.submit-btn", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });
});
