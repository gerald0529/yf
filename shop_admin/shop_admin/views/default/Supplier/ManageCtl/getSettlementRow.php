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
          <input  name="shop_id" id="shop_id"  value="<?=$data['shop_id']?>"  type="hidden"/>
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="shop_name"> *店铺名称</label>
                </dt>
                <dd class="opt">
                    <?=$data['shop_name']?>
                </dd>
              </dl>
           
           
             <dl class="row">
                <dt class="tit">
                    <label for="user_name">* 会员账号</label>
                </dt>
                <dd class="opt">
                   <?=$data['user_name']?>
                </dd>
          
            </dl>
           
            <dl class="row">
                <dt class="tit">
                    <label for="shop_settlement_cycle">* 结算周期</label>
                </dt>
                <dd class="opt">
                    <input id="shop_settlement_cycle"  name="shop_settlement_cycle" value="<?=$data['shop_settlement_cycle']?>" class="ui-input w200" type="text"/>
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
                'shop_settlement_cycle':'required;integer[+0]' ,
              
            },

        valid: function (form)
        {
            var shop_settlement_cycle = $.trim($("#shop_settlement_cycle").val()), 
             shop_id = $.trim($("#shop_id").val()), 
			n = "add" == t ? "新增结算周期" : "修改结算周期";
			params = {
				shop_id: shop_id, 
				shop_settlement_cycle: shop_settlement_cycle, 
			
                               
			};
			Public.ajaxPost(SITE_URL +"?ctl=Supplier_Manage&met=" + ("add" == t ? "add" : "edit")+ "SettlementRow&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: n + "成功！"});
					 var callback = frameElement.api.data.callback;
                                         callback();
				}
				else
				{
					parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg});
                                         var callback = frameElement.api.data.callback;
                                         callback();
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
    $("#shop_settlement_cycle").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_add"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
