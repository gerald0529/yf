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
        <form method="post" enctype="multipart/form-data" id="shop_add" name="form1">
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="shop_name"> *店铺名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_name" name="shop_name" value="" class="ui-input w200" type="text"/>
                    <p class="notic">填写自营店铺的名称。</p>
                </dd>
              </dl>
           
           
             <dl class="row">
                <dt class="tit">
                    <label for="user_name">* 会员账号</label>
                </dt>
                <dd class="opt">
                    <input id="user_name"  name="user_name" value="" class="ui-input w200" type="text"/>
                      <p class="notic">填写会员的账号，用于登录登录</p>
                </dd>
          
            </dl>
           
            <dl class="row">
                <dt class="tit">
                    <label for="user_password">* 登录密码</label>
                </dt>
                <dd class="opt">
                    <input id="user_password"  name="user_password" value="" class="ui-input w200" type="password"/>
                      <p class="notic">填写会员的密码</p>
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
            
            postData(oper, rowData.shop_id);
           return cancleGridEdit(),$("#shop_add").trigger("validate"), !1
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
                'shop_name':'required;' ,
              
            },

        valid: function (form)
        {
            var shop_name = $.trim($("#shop_name").val()), 
            user_name = $.trim($("#user_name").val()), 
            user_password = $.trim($("#user_password").val()), 

			n = "Add" == t ? "新增店铺" : "修改店铺";
			params = rowData.shop_id ? {
				shop_id: e, 
				shop_name: shop_name, 
				user_name: user_name,
                                user_password:user_password,
                               
			} : {
				shop_name: shop_name, 
				user_name: user_name,
                                user_password:user_password,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Supplier_Selfsupport&met=" + ("Add" == t ? "Add" : "Edit")+ "ShopRow&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: n + "成功！"});
					 var callback = frameElement.api.data.callback;
                                         callback();
				}
				else
				{
					parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
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
    $("#shop_name").val("");
    $("#user_name").val("");
    $("#user_password").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_add"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
