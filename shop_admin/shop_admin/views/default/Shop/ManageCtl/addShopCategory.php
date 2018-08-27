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
            <input  name="shop_id" id="shop_id" value="<?=$data['shop_id']?>"  type="hidden"/>
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="product_class"> 经营类目</label>
                </dt>
                <input type="hidden" name="product_class_id" id="product_class_id">
                <dd class="opt">
                    <p id="cat_name" name ="cat_name"></p>
                </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="commission_rate">分佣比例(%)</label>
                </dt>
                <dd class="opt">
                    <input id="commission_rate" name="commission_rate" value="" class="ui-input w200" type="text"/>
                </dd>
              
              </dl>
           
          
          
        </div>
    </form>


<script>
	$(function() {
		//商品类别
		var opts = {
			width : 160,
			//inputWidth : (SYSTEM.enableStorage ? 145 : 208),
			inputWidth : 180,
			defaultSelectValue : '-1',
			//defaultSelectValue : rowData.categoryId || '',
			showRoot : true,
                        rootTxt: '添加经营类目',
		}

		categoryTree = Public.categoryTree($('#cat_name'), opts);
                
            
			$('#cat_name').change(function(){
                            var i = $(this).data('id');
                           $('#product_class_id').val(i);
		});
	});
</script>
    <script>

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.shop_class_bind_id);
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
                'cat_name':'required',
                'product_class_id':'required;' ,
                'commission_rate':'required;' ,
            },

        valid: function (form)
        {
            var product_class_id = $.trim($("#product_class_id").val()), 
            commission_rate = $.trim($("#commission_rate").val()), 
            shop_id = $.trim($("#shop_id").val()), 
          
    
			n = "add" == t ? "新增经营类目" : "修改经营类目";
			params = rowData.shop_class_bind_id ? {
				shop_class_bind_id: e, 
				product_class_id: product_class_id, 
				commission_rate: commission_rate,
                                shop_id: shop_id,
                            
			} : {
                                shop_id: shop_id,
				product_class_id: product_class_id, 
				commission_rate: commission_rate,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Manage&met=" + ("add" == t ? "add" : "edit")+ "ShopCategoryRow&typ=json", params, function (e)
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
    $("#product_class_id").val("");
    $("#commission_rate").val("");
  
			
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_level"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
