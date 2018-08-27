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
        <form method="post" enctype="multipart/form-data" id="shop_edit_form" name="form1">
            

          <input id="shop_id" name="shop_id" value="<?= $data['shop_id'] ?>"  type="hidden"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="retain_domain">店主账号</label>
                </dt>
                <dd class="opt">
                   <?= $data['user_name'] ?>
                </dd>
            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="domain_length">*店铺名称</label>
                </dt>
                <dd class="opt">
                    <?= $data['shop_name'] ?>
                </dd>
            </dl>
            
             <dl class="row">
                <dt class="tit">
                    <label for="retain_domain">开店时间</label>
                </dt>
                <dd class="opt">
                   <?= $data['shop_create_time'] ?>
                </dd>
            </dl>
          <dl class="row">
                <dt class="tit">
                  <label for="class_id">所属分类</label>
                 <input id="class_id" name="shop[shop_class_id]" value="<?= $data['shop_class_id']?>"  type="hidden"/>
                </dt>
                <dd class="opt">
                   <span id="class"></span>
                    <p class="notic"></p>
                </dd>
             </dl>
        <dl class="row">
          <dt class="tit">
            <label for="grade_id"> 所属等级 </label>
            <input id="grade_id" name="shop[shop_grade_id]" value="<?= $data['shop_grade_id']?>"  type="hidden"/>
          </dt>
          <dd class="opt">
            <span id="grade"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>有效期至</label>
          </dt>
           <dd class="opt">
               <input type="text" id="endtime"  class="ui-input w200" value="<?= $data['shop_end_time'] ?>" name="shop[shop_end_time]"  readonly="readonly">
            </dd>
        </dl>

            <dl class="row">
                <dt class="tit">状态</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="shop_status1" name="shop[shop_status]"  <?=($data['shop_status'] ? 'checked' : '')?>  value="3" type="radio">
                        <label for="shop_status1" class="cb-enable  <?=($data['shop_status'] ? 'selected' : '')?>">开启</label>

                        <input id="shop_status0" name="shop[shop_status]"   <?=(!$data['shop_status'] ? 'checked' : '')?>   value="0" type="radio">
                        <label for="shop_status0" class="cb-disable  <?=(!$data['shop_status'] ? 'selected' : '')?>">关闭</label>
                    </div>
                    <p class="notic">关闭店铺时，该店铺中的商品将被全部下架，请谨慎操作！！</p>
                    <p class="notic">不可修改时店主填写提交后将不可改动</p>
                </dd>
            </dl>
        </div>
    </form>
   
    <script>
         var grade_id =  '<?= $data['shop_grade_id'] ?>';
         var grade_row = <?= encode_json(array_values($data['grade'])) ?>;
         var class_id =  '<?= $data['shop_class_id'] ?>';
         var class_row = <?= encode_json(array_values($data['class'])) ?>;
    </script>
    <script>
 function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData);
           return cancleGridEdit(),$("#shop_edit_form").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
$(function ()
{
    if ($('#shop_edit_form').length > 0)
    {
        
        var gradeCombo = Business.gradeCombo($('#grade'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: 0,
            trigger: true,
            width: 200,
            callback: {
                onChange: function (data)
                {
                    $('#grade_id').val(this.getValue());
                }
            }
        });

        gradeCombo.selectByValue(grade_id);

        var classCombo = Business.classCombo($('#class'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: 0,
            trigger: true,
            width: 200,
            callback: {
                onChange: function (data)
                {
                    $('#class_id').val(this.getValue());
                }
            }
        });

        classCombo.selectByValue(class_id);
        
   
    }

});
function postData(t, e)
{
 
	$_form.validator({
        valid: function (form)
        {
            var 
                shop_id = $.trim($("#shop_id").val()),
                shop_class_id= $.trim($("#class_id").val()), 
                shop_grade_id = $.trim($("#grade_id").val()), 
                shop_status = $.trim($("input[name='shop[shop_status]']:checked").val()),
    
			n = "add" == t ? "新增店铺" : "修改店铺";

			params = {
				shop_id: shop_id,
				shop_class_id: shop_class_id,
                shop_grade_id: shop_grade_id,
                shop_status: shop_status,
			};
			Public.ajaxPost(SITE_URL + '?ctl=Shop_Manage&met=editShopinformation&typ=json', params, function (e)
			{
			    console.log(e);
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
    $("#shop_id").val("");
    $("#class_id").val("");
    $("#grade_id").val("");
    $("input[name='shop[shop_status]']:checked").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>