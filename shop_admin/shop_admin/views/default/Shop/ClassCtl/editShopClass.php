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
        <form method="post" enctype="multipart/form-data" id="shop_edit_class" name="form1">
          <?php foreach ($data as $key => $value) {
                
            ?>
            <input  name="shop_class_id" value="<?=$value["shop_class_id"]?>"  type="hidden"/>
         <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="shop_class_name">* 分类名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_class_name" name="shop_class_name" value="<?=$value["shop_class_name"]?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
           
           
             <dl class="row">
                <dt class="tit">
                    <label for="shop_class_deposit">* 保证金数</label>
                </dt>
                <dd class="opt">
                    <input id="shop_class_deposit" name="shop_class_deposit"  value="<?=$value["shop_class_deposit"]?>" class="ui-input w200" type="text"/>
                </dd>
          
            </dl>
          
             <dl class="row">
                <dt class="tit">
                    <label for="shop_class_displayorder">*排序</label>
                </dt>
                <dd class="opt">
                    <input id="shop_class_displayorder" name="shop_class_displayorder" value="<?=$value["shop_class_displayorder"]?>" class="ui-input w200" type="text"/>
                </dd>
          
            </dl>
          
          
        </div>
               <?php }?>
    </form>

    <script>

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            console.info(rowData.shop_class_id);
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
                'shop_class_name':'required;' ,
                'shop_class_deposit':'required;integer[+0];' ,
                'shop_class_displayorder':'required;integer[+0];range[0~255 ];' 
            },

        valid: function (form)
        {
            var shop_class_name = $.trim($("#shop_class_name").val()), 
            shop_class_deposit = $.trim($("#shop_class_deposit").val()), 
            shop_class_displayorder = $.trim($("#shop_class_displayorder").val()),
            shop_class_id = $.trim($("input[name='shop_class_id']").val()),

			n = "add" == t ? "新增分类" : "修改分类";
			params = rowData.shop_class_id ? {
				shop_class_id: e, 
				shop_class_name: shop_class_name, 
				shop_class_deposit: shop_class_deposit,
                                shop_class_displayorder:shop_class_displayorder,
                               
			} : {
                shop_class_id: shop_class_id,
				shop_class_name: shop_class_name, 
				shop_class_deposit: shop_class_deposit,
                                shop_class_displayorder:shop_class_displayorder,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Class&met=" + ("add" == t ? "add" : "edit")+ "ShopClassrow&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: n + "成功！"});
					console.log(e.data);
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
