<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>


<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">
</head>
<body>
<div>
    <form id="add_form" action="index.php?act=complain&op=complain_subject_save" enctype="multipart/form-data" method="post">
        <div class="ncap-form-default" style="padding:0px;">
            <dl class="row" style="height: 50px;">
                <dt class="tit">
                    <label for="complain_subject_content">
                        <em>*</em>
                        投诉主题
                    </label>
                </dt>
                <dd class="opt">
                    <input id="complain_subject_content" class="input-txt" type="text" name="complain_subject_content">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" style="height: 110px;">
                <dt class="tit">
                    <label for="complain_subject_desc">
                        <em>*</em>
                        投诉主题描述
                    </label>
                </dt>
                <dd class="opt">
                    <textarea id="complain_subject_desc" class="tarea" rows="6" name="complain_subject_desc"></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot">
                <a id="submitBtn" class="ncap-btn-big ncap-btn-blue" href="JavaScript:void(0);">确认提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
$(function ()
{
        var complain_subject_content = $.trim($("#complain_subject_content").val());
        var complain_subject_desc = $.trim($("#complain_subject_desc").val());

        $('#add_form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'complain_subject_content' : 'required;',
                'complain_subject_desc': 'required;'
            },
            valid: function (form)
            {
                var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();

                parent.$.dialog.confirm('确认新增这项投诉主题？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Trade_Complain&met=addComplainSubject&typ=json', {complain_subject_content:$("#complain_subject_content").val(),complain_subject_desc:$("#complain_subject_desc").val()}, function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '添加成功成功！'});
                                callback && "function" == typeof callback && callback(data.data, '', window)
                            }
                            else
                            {
                                parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                            }

                            // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                            me.holdSubmit(false);
                        });
                    },
                    function ()
                    {
                        me.holdSubmit(false);
                    });
            },
        }).on("click", "a#submitBtn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
});
var api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>