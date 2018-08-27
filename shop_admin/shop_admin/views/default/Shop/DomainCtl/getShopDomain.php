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
        <form method="post" enctype="multipart/form-data" id="shop_editdomain" name="form1">
              <?php foreach ($data as $key => $value) {
                
            ?>
            <input  name="shop_id" value="<?=$value['shop_id']?>"  type="hidden"/>
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label >店主名称</label>
                </dt>
                <dd class="opt">
                  <?=$value['user_name']?>
                </dd>
              </dl>
           
           
             <dl class="row">
                <dt class="tit">
                    <label>店铺名称</label>
                </dt>
                <dd class="opt">
                  <?=$value['shop_name']?>
                </dd>
          
            </dl>
          
             <dl class="row">
                <dt class="tit">
                    <label for="shop_edit_domain">*编辑次数</label>
                </dt>
                <dd class="opt">
                    <input id="shop_edit_domain" name="shop_edit_domain" value="<?=$value['shop_edit_domain']?>" class="ui-input w200" type="text"/>
                </dd>
          
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="shop_sub_domain">*二级域名</label>
                </dt>
                <dd class="opt">
                    <input id="shop_sub_domain" name="shop_sub_domain" placeholder="请不要带有.?&等特殊符号" value="<?=$value['shop_sub_domain']?>" class="ui-input w200" type="text"/>
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
            
            postData(oper, rowData.shop_id);
           return cancleGridEdit(),$("#shop_editdomain").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
 
	$_form.validator({
         rules: {
          domainz:[/[^\.\?&]/ , '请填写正确域名'],
        },
           messages: {
                    required: "请填写该字段",
                 
           },
            fields: {
                'shop_edit_domain':'required;integer[+0];' ,
                'shop_sub_domain':'required;domainz',
            },

        valid: function (form)
        {
            var shop_edit_domain = $.trim($("#shop_edit_domain").val()), 
            shop_sub_domain = $.trim($("#shop_sub_domain").val()), 

			n = "add" == t ? "新增域名" : "修改域名";
			params = rowData.shop_id ? {
				shop_id: e, 
				shop_sub_domain: shop_sub_domain, 
				shop_edit_domain: shop_edit_domain,
                               
			} : {
                                shop_sub_domain: shop_sub_domain, 
				shop_edit_domain: shop_edit_domain,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Domain&met=" + ("add" == t ? "add" : "edit")+ "ShopDomainrow&typ=json", params, function (e)
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
    $("#shop_sub_domain").val("");
    $("#shop_edit_domain").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_editdomain"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
