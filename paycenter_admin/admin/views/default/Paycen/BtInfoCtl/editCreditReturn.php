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
                    <label for="user_nickname">用户账号</label>
                </dt>
                    <dd class="opt">
                        <?=$data['user_name']?>
                    </dd>
              </dl>
               <dl class="row">
                   <dt class="tit">
                       <label for="user_nickname">收款金额</label>
                   </dt>
                   <dd class="opt">
                       <input type="text" name="user_return_credit"  id="user_return_credit" value="">
                   </dd>
               </dl>
  
            <input id="user_id"  name="user_id" value="<?=$data['user_id']?>"  type="hidden"/>

          
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
          

        valid: function (form)
        {
            var user_id = $.trim($("#user_id").val()),
                user_return_credit = $.trim($("#user_return_credit").val());


			params ={
                user_id:user_id,
                user_return_credit: user_return_credit,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Paycen_PayInfo&met=editCreditReturn&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips( {content:"还款成功！"});
					 var callback = frameElement.api.data.callback;
                                            callback();
				}
				else
				{
					parent.parent.Public.tips({type: 1, content:  "还款失败！" + e.msg})
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
    $("#shop_class_name").val("");
    $("#shop_class_deposit").val("");
    $("#shop_class_displayorder").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_class"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
