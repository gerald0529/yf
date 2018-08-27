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
                    <label for="name">* 名称</label>
                </dt>
                <dd class="opt">
                    <input id="name"  name="name" value="" class="ui-input w200" type="text"/>
                </dd>
              </dl>
           
             <dl class="row">
                <dt class="tit">
                    <label for="fee_rates">*服务费率</label>
                </dt>
                <dd class="opt">
                        <input id="fee_rates"  name="fee_rates" value="" class="ui-input w200" type="text"/>
                    <p class="notic">单位为%</p>
                </dd>
          
            </dl>
          
             <dl class="row">
                <dt class="tit">
                    <label for="fee_min">服务费下限</label>
                </dt>
                <dd class="opt">
                     <input id="fee_min"  name="fee_min" value="" class="ui-input w200" type="text"/>
                </dd>
          
            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="fee_max">服务费上限</label>
                </dt>
                <dd class="opt">
                     <input id="fee_max"  name="fee_max" value="" class="ui-input w200" type="text"/>
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
            postData(oper, rowData.id);
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
                'name':'required;' ,
                 'fee_rates':'required;' ,
                 'fee_min':'required;' ,
                 'fee_max':'required;' ,
            },

        valid: function (form)
            {
                    var name = $.trim($("#name").val()), 
                    fee_rates = $.trim($("#fee_rates").val()), 
                    fee_min = $.trim($("#fee_min").val()), 
                    fee_max = $.trim($("#fee_max").val()), 
			n = "add" == t ? "新增配置" : "修改配置";
			params = rowData.id ? {
				id: e, 
				name: name, 
				fee_rates: fee_rates,
                                fee_min:fee_min,
                                fee_max:fee_max,
			} : {
				name: name, 
				fee_rates: fee_rates,
                                fee_min:fee_min,
                                fee_max:fee_max,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Paycen_PayService&met=" + ("add" == t ? "add" : "edit")+ "ServiceRow&typ=json", params, function (e)
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
