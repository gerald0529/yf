<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
	<input type="hidden" name="order_return_reason_id" id="order_return_reason_id" value="<?=$data['order_return_reason_id']?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">原因</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <input id="order_return_reason_content" name="order_return_reason_content" class="ui-input w200" type="text"  value="<?=$data['order_return_reason_content']?>"/>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">排序</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <input id="order_return_reason_sort" name="order_return_reason_sort" class="ui-input w200" type="text" value="255"  value="<?=$data['order_return_reason_sort']?>"/>
                        </li>
                    </ul>
                    <p class="notic">数字范围为0~255，数字越小越靠前</p>
                </dd>
            </dl>
        </div>
    </form>
    <script type="text/javascript" charset="utf-8">

        function initPopBtns()
        {
            var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
            api.button({
                id: "confirm", name: t[0], focus: !0, callback: function ()
                {
                    postData(oper, rowData.contract_type_id);
                    return cancleGridEdit(),$("#manage-form").trigger("validate"), !1
                }
            }, {id: "cancel", name: t[1]})
        }
        function postData(t, e)
        {
            $_form.validator({
                fields: {
                    order_return_reason_content:"required;",
                    order_return_reason_sort:"required;integer[+];",
                },
                valid: function (form)
                {
                    var me = this;
                    // 提交表单之前，hold住表单，防止重复提交
                    me.holdSubmit();
                    n = "修改";
                    Public.ajaxPost(SITE_URL+"?ctl=Trade_Return&typ=json&met=editReasonBase", $_form.serialize(), function (e)
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
                        // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                        me.holdSubmit(false);
                    })
                },
                ignore: "",
                theme: "yellow_bottom",
                timely: 1,
                stopOnError: !0
            });
        }
        function cancleGridEdit()
        {
            null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
        }
        var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
        initPopBtns();
    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
