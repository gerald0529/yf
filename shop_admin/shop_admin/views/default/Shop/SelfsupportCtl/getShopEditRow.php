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

        <form method="post" enctype="multipart/form-data" id="shop_edit_class" name="form1">
          <input  name="shop_id" id="shop_id" value="<?=$data['shop_id']?>"  type="hidden"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="shop_name"><i>*</i>店铺名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_name" name="shop_name" value="<?=$data['shop_name']?>" class="ui-input w200" type="text" disabled="disabled" readonly="readonly"/>
                </dd>
            </dl>
            
            <dl class="row">
                <dt class="tit">
                    <label for="shop_name"><i>*</i>店铺地址</label>
                </dt>
                <dd class="opt">
                    <input id="district_name" name="district_name" value="<?=$data['district_name']?>" class="ui-input w200" type="text" disabled="disabled" /><label onclick="modifyDistrict()" style='color: blue;margin-left: 10px; cursor: pointer;'>修改地址</label>
                    <input id="district_id" name="district_id" value="<?=$data['district_id']?>" class="ui-input w200" type="hidden" />
                    <input id="new_district_id" name="new_district_id" value="" class="ui-input w200" type="hidden" />
                </dd>
						
            </dl>
            <dl class="row hidden new_district">
                <dt class="tit">
                    <label for="shop_name"><i>*</i>店铺新地址</label>
                </dt>
               <dd class="opt">
                    <select id="select_1" name="select_1" onChange="getDistrict(1,$(this).val());" class="hidden"></select>
                    <select id="select_2" name="select_2" onChange="getDistrict(2,$(this).val());" class="hidden"></select>
                    <select id="select_3" name="select_3" onChange="getDistrict(3,$(this).val());" class="hidden"></select>
                    <select id="select_4" name="select_4" onChange="getDistrict(4,$(this).val());" class="hidden"></select>
                    <label onclick="delDistrict()" style='color: blue;margin-left: 10px; cursor: pointer;'>取消修改</label> 
                </dd>
						
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="shop_create_time"><i>*</i>开店时间</label>
                </dt>
                <dd class="opt">
                   <?=$data['shop_create_time']?>
                </dd>
              </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="shop_all_class">绑定所有类目</label>
                </dt>
                <dd class="opt">
                <div class="onoff">
                        <label for="shop_all_class1" class="cb-enable <?=($data['shop_all_class'] ? 'selected' : '')?> ">开启</label>
                        <label for="shop_all_class0" class="cb-disable <?=(!$data['shop_all_class'] ? 'selected' : '')?>">关闭</label>
                        <input id="shop_all_class1"  name ="shop_all_class" <?=($data['shop_all_class'] ? 'checked' : '')?>  value="1" type="radio">
                        <input id="shop_all_class0"  name ="shop_all_class"  <?=(!$data['shop_all_class'] ? 'checked' : '')?>   value="0" type="radio">
                       
                    </div>
                 </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_status">状态</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="shop_status1" class="cb-enable <?=($data['shop_status'] ? 'selected' : '')?> ">开启</label>
                        <label for="shop_status0" class="cb-disable <?=(!$data['shop_status'] ? 'selected' : '')?>">关闭</label>
                        <input id="shop_status1"  name ="shop_status" <?=($data['shop_status'] ? 'checked' : '')?>  value="3" type="radio">
                        <input id="shop_status0"  name ="shop_status"  <?=(!$data['shop_status'] ? 'checked' : '')?>   value="0" type="radio">
                       
                    </div>
                    <p class="notic">关闭店铺时，该店铺中的商品将被全部下架，请谨慎操作！！</p>
                 </dd>
              </dl>
        
          
          
        </div>
    </form>

    <script type="text/javascript">

function initPopBtns()
{
    var t = "Add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            console.log(rowData);
            postData(oper, rowData.shop_id);
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
//                'shop_name':'required;' ,
                'shop_status':'required;' 
            },

        valid: function (form)
        {
            var 
              shop_id = $.trim($("#shop_id").val()), 
//              shop_name = $.trim($("#shop_name").val()), 
              shop_all_class = $.trim($("input[name='shop_all_class']:checked").val()),
              shop_status = $.trim($("input[name='shop_status']:checked").val()),
              new_district_id = $("#new_district_id").val(),
              district_id = $("#district_id").val(),
			n = "Add" == t ? "新增店铺" : "修改店铺";
			params =  { shop_id: shop_id, shop_all_class: shop_all_class, shop_status:shop_status, new_district_id:new_district_id, district_id:district_id };
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Selfsupport&met=" + ("Add" == t ? "Add" : "Edit")+ "ShopBase&typ=json", params, function (e)
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
    $("#shop_name").val("");
    $("input[name='shop_all_class']:checked").val("");
    $("input[name='shop_status']:checked").val("");

}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_class"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
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
    $('#new_district_id').val(nodeid);
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

function modifyDistrict(){
    if(!$('.new_district').hasClass('hidden')){
        return false;
    }
    getDistrict(0,'-1');
    $('#select_1').find("option[value='-1']").prop('selected','selected');
    $('.new_district').removeClass('hidden');
}

function delDistrict(){
    $('#new_district_id').val('');
    $('.new_district').addClass('hidden');
}
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
