<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style type="text/css">
.hidden{display:none;}
.area_in_sub{height:30px;line-height: 30px;margin-bottom: 10px;}
.area_in_sub .second{width:95%;display:inline-block;}
.area_in_sub .second select{width:130px;height:30px;}
i{color:red;}
</style>

</head>

<body>
    <form method="post" enctype="multipart/form-data" id="shop_add" name="form1">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="shop_name"><i>*</i>店铺名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_name" name="shop_name" value="" class="ui-input w200" type="text"/>
                    <p class="notic">填写自营店铺的名称，确认后不可修改。</p>
                </dd>
            </dl>
            
           <dl class="row">
                <dt class="tit">
                    <label for="shop_name"><i>*</i>店铺地址</label>
                </dt>
                <dd class="opt">
                    <select id="select_1" name="select_1" onChange="getDistrict(1,$(this).val());"></select>
                    <select id="select_2" name="select_2" onChange="getDistrict(2,$(this).val());" class="hidden"></select>
                    <select id="select_3" name="select_3" onChange="getDistrict(3,$(this).val());" class="hidden"></select>
                    <select id="select_4" name="select_4" onChange="getDistrict(4,$(this).val());" class="hidden"></select>
                    <input id="district_id" name="district_id" value="" class="ui-input w200" type="hidden" />
                </dd>
            </dl>
            
           
            <dl class="row">
                <dt class="tit">
                    <label for="user_name"><i>*</i>会员账号</label>
                </dt>
                <dd class="opt">
                    <input id="user_name"  name="user_name" value="" class="ui-input w200" type="text"/>
                      <p class="notic">填写会员的账号，用于登录登录</p>
                </dd>
          
            </dl>
           
            <dl class="row">
                <dt class="tit">
                    <label for="user_password"><i>*</i>登录密码</label>
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
        rules: {
            checkName:function(element){
                if(element.value.length > 25){
                    return '店铺名称不能超过25个字符';
                }
            }
        },
        messages: {
                required: "请填写该字段",
        },
        fields: {
            'shop_name':'required;checkName;' ,

        },

        valid: function (form)
        {
            var shop_name = $.trim($("#shop_name").val()), 
            user_name = $.trim($("#user_name").val()), 
            user_password = $.trim($("#user_password").val()), 
            district_id = $("#district_id").val(),

			n = "Add" == t ? "新增店铺" : "修改店铺";
			params = rowData.shop_id ? { shop_id: e, shop_name: shop_name, user_name: user_name, user_password:user_password, district_id:district_id } : {
				shop_name: shop_name, user_name: user_name, user_password:user_password, district_id:district_id
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Selfsupport&met=" + ("Add" == t ? "Add" : "Edit")+ "ShopRow&typ=json", params, function (e)
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

//选择地区
function getDistrict(level,nodeid){
    if(nodeid == '-1'){
        $('#select_2').html('');
        $('#select_2').hide();
        $('#select_3').html('');
        $('#select_3').hide();
        $('#select_4').html('');
        $('#select_4').hide();
        return ;
    }
    var next_level = level + 1;
    $('#district_id').val(nodeid);
    $.post(SITE_URL+'?ctl=Base_District&met=district&typ=json&nodeid='+nodeid,function(b){
        if(b.status==200 && b.data.items.length > 0){
            $('#select_'+next_level).show();
            $('#select_'+next_level).html('');
            if(level == 1){
                $('#select_3').html('');
                $('#select_3').hide();
                $('#select_4').html('');
                $('#select_4').hide();
            }
            if(level == 2){
                $('#select_4').html('');
                $('#select_4').hide();
            }
            $('#select_'+next_level).append('<option value="-1"><?=__('--请选择--')?></option>');
            $.each( b.data.items, function(i, v){
                $('#select_'+next_level).append('<option value="'+v.district_id+'">'+v.district_name+'</option>');
            });
        }
    },'json');
} 
getDistrict(0,0);
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
