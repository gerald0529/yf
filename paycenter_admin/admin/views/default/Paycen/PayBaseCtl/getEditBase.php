<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js?>/libs/jquery/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/libs/jquery/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>

<body>
        <form method="post" enctype="multipart/form-data" id="shop_edit_class" name="form1">
       <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="user_nickname">* 用户账号</label>
                </dt>
                <dd class="opt">
                    <p><?=$data['user_account']?></p>
                </dd>
              </dl>
           
           <input id="user_account"  name="user_account" value="<?=$data['user_account']?>"  type="hidden"/>
            <input id="user_id"  name="user_id" value="<?=$data['user_id']?>"  type="hidden"/>
             <dl class="row">
                <dt class="tit">
                    <label for="user_money">*充值金额</label>
                </dt>
                <dd class="opt">
                        <input id="add_user_money"  name="add_user_money" value="" class="ui-input w200" type="text"/>
                    <p style="color: red;">最多可充值10000000<p>
                    <p class="notic">负数为减，例如 -1000</p>
                </dd>
          
            </dl>
          
             <dl class="row">
                <dt class="tit">
                    <label for="record_desc">备注</label>
                </dt>
                <dd class="opt">
                   <textarea style="width:200px;height: 73px;" rows="6" class="tarea" name="record_desc" id="record_desc"></textarea>
                </dd>
          
            </dl>
          
          
        </div>
    </form>

    <script>

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            
            postData(oper, rowData.shop_class_id);
           return cancleGridEdit(),$("#shop_edit_class").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
 
	$_form.validator({
               messages: {
                    required: "请填写该字段",
           },
            fields: {
                'add_user_money':'required;integer;range[~10000000]',
            },

        valid: function (form)
        {
            var user_account = $.trim($("#user_account").val()), 
            add_user_money = $.trim($("#add_user_money").val()), 
            user_id = $.trim($("#user_id").val()), 
            record_desc = $.trim($("#record_desc").val()), 
			params ={
                user_id:user_id,
				user_account: user_account, 
				add_user_money: add_user_money,
                                record_desc:record_desc,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Paycen_PayBase&met=editBaseRow&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips( {content:"修改成功！"});
					 var callback = frameElement.api.data.callback;
                                            callback();
				}
				else
				{
					parent.parent.Public.tips({type: 1, content:  "修改失败！" + e.msg})
				}
			})
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    });
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $_form.validate().resetForm();
    $("#user_account").val("");
    $("#add_user_money").val("");
    $("#user_id").val("");
    $("#record_desc").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_class"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
