<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
	<link href="<?= $this->view->css ?>/iconfont/iconfont.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">

        <div class="ncap-form-default">
			<dl class="row">
                <dt class="tit">卡名</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							 <input id="card_name" name="card_name" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">相关内容</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span>
								总数：<input id="card_num" name="card_num" class="ui-input w20"/>
								前缀：<input id="card_id" name="card_id" class="ui-input w100"/>
							</span>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">面额(元)</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
								<input id="money" name="money" class="ui-input w200"/>
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">积分</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							 <input id="point" name="point" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">时间</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<input id="start_time" name="start_time" class="ui-input  ui-datepicker-input" type="text" readonly />
							至
							<input id="end_time" name="end_time" class="ui-input  ui-datepicker-input" type="text"  readonly />
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">描述</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<input id="card_desc" name="card_desc" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">图片</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
								<img id="setting_card_image" alt="选择图片" src="" class="image-line" />
								<div class="image-line" style="margin-left: 10px;" id="setting_image_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
								<input id="card_image"  name="card_image" type="hidden"/>
                        </li>
                    </ul>
                </dd>
            </dl>
        </div>
    </form>
	<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
		<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
		//日历插件
        $('#start_time').datetimepicker({
            controlType: 'select',
			timepicker:false
        });

        $('#end_time').datetimepicker({
            controlType: 'select',
			timepicker:false
        });
		
		setting_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 60,
            imageContainer: '#setting_card_image',
            uploadButton: '#setting_image_upload',
            inputHidden: '#card_image'
        });
	function initPopBtns()
				{
					var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
					api.button({
						id: "confirm", name: t[0], focus: !0, callback: function ()
						{
							postData(oper, rowData.contract_type_id);
							return cancleGridEdit(),$("#manage-form").trigger("validate"), !1
						}
					}, {id: "cancel", name: t[1]})
				}
			function postData(t, e)
			{
			$_form.validator({
				rules: {
						//自定义规则，结束时间大于开始时间
						eGrateThansDate  : function(element, param, field)
						{
							var s_time = $("#start_time").val();
							var date1 = new Date(Date.parse(element.value.replace(/-/g, "/")));
							var date2 = new Date(Date.parse(s_time.replace(/-/g, "/")));

							return date1 > date2 || "结束时间必须大于开始时间";
						}
				},
				fields: {
						card_name:"required;",
						card_num:"required;integer[+];",
						card_id:"required;integer[+];",
						money:"required;integer[+];",
						point:"required;integer[+];",
						start_time:"required;",
						end_time:"required;eGrateThansDate;"		
				},
				valid: function (form)
				{
					var me = this;
					// 提交表单之前，hold住表单，防止重复提交
					me.holdSubmit();
					n = "添加";
					Public.ajaxPost(SITE_URL+"?ctl=Operation_Card&typ=json&met=addCardBase", $_form.serialize(), function (e)
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
						// 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
						me.holdSubmit(false);
					})
				},
				ignore: "",
				theme: "yellow_bottom",
				timely: 1,
				stopOnError: !0
			});
		}
		function cancleGridEdit()
		{
			null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
		}
		var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
		initPopBtns();
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>