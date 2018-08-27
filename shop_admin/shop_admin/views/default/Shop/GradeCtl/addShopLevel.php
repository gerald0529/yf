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
        <form method="post" enctype="multipart/form-data" id="shop_edit_level" name="form1">
          <input  name="shop_id" value=""  type="hidden"/>
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_name">* 等级名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_grade_name" name="shop_grade_name" value="" class="ui-input w200" type="text"/>
                </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_goods_limit">可发布商品数</label>
                </dt>
                <dd class="opt">
                    <input id="shop_grade_goods_limit" name="shop_grade_goods_limit" value="" class="ui-input w200" type="text"/>
                     <span class="err"></span>
                     <p class="notic">0表示没有限制</p>
                </dd>
              
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_album_limit">可上传图片数</label>
                </dt>
                <dd class="opt">
                    <input id="shop_grade_album_limit" name="shop_grade_album_limit" value="" class="ui-input w200" type="text"/>
                     <span class="err"></span>
                     <p class="notic">0表示没有限制</p>
                </dd>
              
              </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="retain_domain">可选模板套数</label>
                </dt>
                <dd class="opt">
                   (在店铺等级列表设置)
                </dd>
            </dl>
            
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_album_limit">可用附加功能</label>
                </dt>
                <dd class="opt">
                <div class="onoff">
                        <label for="shop_grade_function_id1" class="cb-enable  ">开启</label>
                        <label for="shop_grade_function_id0" class="cb-disable  selected">关闭</label>
                        <input id="shop_grade_function_id1"  name ="shop_grade_function_id"  value="1" type="radio">
                        <input id="shop_grade_function_id0"  name ="shop_grade_function_id"  checked="checked"  value="0" type="radio">
                       
                    </div>
                 </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_fee">*收费标准</label>
                </dt>
                <dd class="opt">
                    <input id="shop_grade_fee" name="shop_grade_fee" value="" class="ui-input w200" type="text"/>
                </dd>
          
            </dl>
            
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_desc">申请说明</label>
                </dt>
                <dd class="opt">
                    <textarea style="width:200px;height: 73px;" rows="6" class="tarea" id="shop_grade_desc" name="sg_description"></textarea>
                </dd>
            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_grade_sort">*级别</label>
                </dt>
                <dd class="opt">
                    <input id="shop_grade_sort" name="shop_grade_sort" value="" class="ui-input w200" type="text"/>
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
            
            postData(oper, rowData.shop_grade_id);
           return cancleGridEdit(),$("#shop_edit_level").trigger("validate"), !1
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
                'shop_grade_name':'required;' ,
                'shop_grade_goods_limit':'integer[+0];' ,
                'shop_grade_album_limit':'integer[+0];' ,
                'shop_grade_fee':'required;integer[+0];' ,
                'shop_grade_sort':'required;integer[+0];' 
            },

        valid: function (form)
        {
            var shop_grade_name = $.trim($("#shop_grade_name").val()), 
            shop_grade_goods_limit = $.trim($("#shop_grade_goods_limit").val()), 
            shop_grade_album_limit = $.trim($("#shop_grade_album_limit").val()), 
            shop_grade_fee = $.trim($("#shop_grade_fee").val()), 
            shop_grade_desc = $.trim($("#shop_grade_desc").val()), 
            shop_grade_function_id = $.trim($("input[name='shop_grade_function_id']:checked").val()),
            shop_grade_sort = $.trim($("#shop_grade_sort").val()), 
    
			n = "add" == t ? "新增等级" : "修改等级";
			params = rowData.shop_grade_id ? {
				shop_grade_id: e, 
				shop_grade_name: shop_grade_name, 
				shop_grade_goods_limit: shop_grade_goods_limit,
                                shop_grade_album_limit:shop_grade_album_limit,
                                shop_grade_fee:shop_grade_fee,
                                shop_grade_desc:shop_grade_desc,
                                shop_grade_function_id:shop_grade_function_id,
                                shop_grade_sort:shop_grade_sort,
			} : {
				shop_grade_name: shop_grade_name, 
				shop_grade_goods_limit: shop_grade_goods_limit,
                                shop_grade_album_limit:shop_grade_album_limit,
                                shop_grade_fee:shop_grade_fee,
                                shop_grade_desc:shop_grade_desc,
                                shop_grade_function_id:shop_grade_function_id,
                                shop_grade_sort:shop_grade_sort,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Grade&met=" + ("add" == t ? "add" : "edit")+ "ShopLevelrow&typ=json", params, function (e)
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
    $("#shop_grade_name").val("");
    $("#shop_grade_goods_limit").val("");
    $("#shop_grade_album_limit").val("");
    $("#shop_grade_fee").val("");
    $("#shop_grade_desc").val("");
    $("#shop_grade_sort").val("");
    $("input[name='shop_grade_function_id']:checked").val("");
			
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_level"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
