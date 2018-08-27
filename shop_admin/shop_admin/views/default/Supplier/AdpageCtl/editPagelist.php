<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link rel="stylesheet" href="<?=$this->view->css?>/page.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>

<body>
<div style="   overflow: hidden;
    padding: 10px 3% 0;
    text-align: left;">
    <form name="form"  id="manage-form" action="#" method="post">
   <input type="hidden" name="page_id"  id="page_id" value="<?=$data['items']['page_id']?>" />
    <table class="form-table-style" style="margin-top:-8px;">
       	<tr>
        	<th colspan="2"><em>*</em>板块名称：</th>
        </tr>
        <tr>
            <td width="300"><input type="text" class="text w250" name="page_name" id="page_name" value="<?=$data['items']['page_name']?>" /></td>
            <td><p class="hits"></p></td>
        </tr>
       	<tr>
        	<th colspan="2">色彩风格：</th>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="page_color" id="page_color" value="<?=$data['items']['page_color']?>" />
            <ul class="color_list fn-clear">
            <?php
                if(!empty($data)){
                foreach($data['color'] as $key => $value){
                
             ?>
                <li date-id="<?=$key?>" class="<?=$key?> <?php if($key == $data['items']['page_color']){?>selected<?php }?>">
                    	<span></span>
                        <i class="iconfont">&#xe61d;</i><?=$value?>
                   
                <?php }}?>
            
            </ul>
            </td>
            <td><p class="hits">选择板块色彩风格将影响商城首页模板该区域的边框、背景色、字体色彩，但不会影响板块的内容布局。</p></td>
        </tr>

  
       	<tr>
        	<th colspan="2">排序：</th>
        </tr>
        <tr>
            <td><input type="text" class="text w250" name="page_order" id="page_order" value="<?=$data['items']['page_order']?>" /></td>
            <td><p class="hits">数字范围为0~255，数字越小越靠前</p></td>
        </tr>
       	<tr>
        	<th colspan="2">排序：</th>
        </tr>
        <tr>
            <td>
                <label><input type="radio" name="page_status"  id="page_status" <?php if($data['items']['page_status'] == "1"){ ?> checked="checked" <?php }?>value="1" />开启</label>&nbsp;&nbsp;
            	<label><input type="radio" name="page_status"  id="page_status" <?php if($data['items']['page_status'] == "0"){ ?> checked="checked" <?php }?> value="0" />关闭</label>
            </td>
            <td></td>
        </tr>     
       
    </table>
    </form>
    </div>
    <script>
        $(".color_list li").click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$("input[name='page_color']").val($(this).attr("date-id"));
	});
	$(".frame_list li").click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$("input[name='layout_id']").val($(this).attr("date-id"));
	});
        
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            
            postData(oper, rowData.page_id);
           return cancleGridEdit(),$("#manage-form").trigger("validate"), !1
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
                'page_name':'required;' ,
            },

        valid: function (form)
        {
            var page_order = $.trim($("#page_order").val()), 
            page_name = $.trim($("#page_name").val()), 
            page_color = $.trim($("#page_color").val()), 
            layout_id = $.trim($("#layout_id").val()), 
            page_status = $.trim($("input[name='page_status']:checked").val()), 
			n = "add" == t ? "新增广告页" : "修改广告页";
			params = rowData.page_id ? {
				page_id: e, 
				page_order: page_order, 
				page_name: page_name,
                                page_color:page_color,
                                layout_id:layout_id,
                                page_status:page_status,
			} : {
				page_order: page_order,
				page_name: page_name,
                                page_color:page_color,
                                layout_id:layout_id,
                                page_status:page_status,
			};
			Public.ajaxPost(SITE_URL +"?ctl=Supplier_Adpage&met=" + ("add" == t ? "add" : "edit")+ "Pagerow&typ=json", params, function (e)
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
    $("#page_order").val("");
    $("#page_name").val("");
    $("#page_color").val("");
    $("#layout_id").val("");
    $("input[name='page_status']:checked").val("");
			
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
