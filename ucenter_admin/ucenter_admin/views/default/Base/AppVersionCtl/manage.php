<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>


<style>
	body{background: #fff;}
</style>
</head>
<body>





<form method="post" name="manage-form" id="manage-form">
	<input type="hidden" name="form_submit" value="ok">
	<input type="hidden" class="ui-input ui-input-ph w400" name="app_resource_id" id="app_resource_id"  placeholder="" autocomplete="off" />
	
	<div class="ncap-form-default">

        <dl class="row">
            <dt class="tit">
                <label><em>*</em>所属应用</label>
                <input type="hidden" id="app_id_ver" name="app_id_ver" class="ui-input">
            </dt>
            <dd class="opt">
                <span id="app_id_ver_combo"></span>
            </dd>
        </dl>
        
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_version">当前版本</label>
			</dt>
			<dd class="opt">
				<input type="text" class="ui-input ui-input-ph w400" name="app_version" id="app_version"  placeholder="当前版本" autocomplete="off" />
				<span class="err"></span>
				<p class="notic"></p>
			</dd>
		</dl>
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_version_next">下个版本</label>
			</dt>
			<dd class="opt">
				<div style="float: left;width:50%;">
					<input type="text" class="ui-input ui-input-ph w400" name="app_version_next" id="app_version_next"  placeholder="下个版本" autocomplete="off" />
				</div>
			</dd>
		</dl>
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_res_filename">资源名称</label>
			</dt>
			<dd class="opt">
				<div style="float: left;width:50%;">
					<input type="text" class="ui-input ui-input-ph w400" name="app_res_filename" id="app_res_filename"  placeholder="资源名称" autocomplete="off" />
				</div>
			</dd>
		</dl>
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_res_filesize">文件大小</label>
			</dt>
			<dd class="opt">
				<input type="text" class="ui-input ui-input-ph w400" name="app_res_filesize" id="app_res_filesize"  placeholder="文件大小" autocomplete="off" />
			</dd>
		</dl>
		
		
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_package_url">安装包地址</label>
			</dt>
			<dd class="opt">
				<input type="text" class="ui-input ui-input-ph w400" name="app_package_url" id="app_package_url"  placeholder="安装包地址:脚本动态增量安装则不需要固定安装包" autocomplete="off" />
			</dd>
		</dl>
		
		
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_res_time">版本时间</label>
			</dt>
			<dd class="opt">
				<input type="text" class="ui-input ui-input-ph w400" name="app_res_time" id="app_res_time"  placeholder="版本时间" autocomplete="off" />
			</dd>
		</dl>
		
		
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_reinstall">是否需要更新安装包</label>
			</dt>
			<dd class="opt">
				<label title="动态增量升级" for="app_reinstall_0"><input class="cbr cbr-success" id="app_reinstall_0" name="app_reinstall" value="0" type="radio" checked >动态增量升级</label> &nbsp; &nbsp; &nbsp; &nbsp;<label title="强制APP下载全新安装包" for="app_reinstall_1"><input class="cbr cbr-success" id="app_reinstall_1" name="app_reinstall" value="1" type="radio"  >强制APP下载全新安装包</label>
			</dd>
		</dl>
			
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_release">是否发布</label>
			</dt>
			<dd class="opt">
				<label title="预发布" for="app_release_0"><input class="cbr cbr-success" id="app_release_0" name="app_release" value="0" type="radio" checked >预发布</label> &nbsp;<label title="发布" for="app_release_1"><input class="cbr cbr-success" id="app_release_1" name="app_release" value="1" type="radio"  >发布</label>
			</dd>
		</dl>
		
		
		<dl class="row">
			<dt class="tit">
				<label class="input-label" for="app_update_log">更新日志</label>
			</dt>
			<dd class="opt">
				<textarea type="text" class="ui-input ui-input-ph w400" name="app_update_log" id="app_update_log"  placeholder="更新日志" autocomplete="off" ></textarea>
			</dd>
		</dl>
	</div>
</form>

<script type="text/javascript">
	var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
	
	initPopBtns();
	initField();
	
	function initField()
	{
		if (rowData.id)
		{
			$('#app_resource_id').val(rowData.app_resource_id);
			$('#app_id_ver').val(rowData.app_id);
			$('#app_version').val(rowData.app_version);
			$('#app_version_next').val(rowData.app_version_next);
			$('#app_res_filename').val(rowData.app_res_filename);
			$('#app_res_filesize').val(rowData.app_res_filesize);
			$('#app_package_url').val(rowData.app_package_url);
			$('#app_res_time').val(rowData.app_res_time);

			$('#app_update_log').val(rowData.app_update_log);


            $("input[name='app_reinstall'][value=" + rowData.app_reinstall + "]").click();
            $("input[name='app_release'][value=" + rowData.app_release + "]").click();
			//$('#keyword_find').attr("readonly", "readonly");
			//$('#keyword_find').addClass('ui-input-dis');
		}
		
		$('#app_res_time').datepicker();



        var appCombo = Business.categoryCombo($('#app_id_ver_combo'), {
            editable: false,
            extraListHtml: '',
            /*
             addOptions: {
             value: -1,
             text: '选择应用'
             },
             */
            defaultSelected: 0,
            trigger: true,
            width: 200,
            callback: {
                onChange: function (data)
                {
                    $('#app_id_ver').val(this.getValue());
                }
            }
        }, 'app_id');

        appCombo.selectByValue(rowData.app_id);


    }
	
	function initPopBtns()
	{
		var btn = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
		api.button({
			id: "confirm", name: btn[0], focus: !0, callback: function ()
			{
				postData(oper, rowData.id);
				return cancleGridEdit(), $_form.trigger("validate"), !1;
			}
		}, {id: "cancel", name: btn[1]})
	}
	
	function postData(oper, id)
	{
		$("#manage-form").validator({
			ignore: ':hidden',
			theme: 'yellow_bottom',
			timely: 1,
			stopOnError: true,
			fields: {
				//'keyword_find': 'required;'
			},
			valid: function (form)
			{
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
				
				parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
					{
						/*
						 var keyword_find = $.trim($("#keyword_find").val());
						 
						 var params = {keyword_find: keyword_find, keyword_replace: keyword_replace};
						 */
						var n = "add" == oper ? _("新增") : _("修改");
						
						Public.ajaxPost(SITE_URL + "?ctl=Base_AppVersion&typ=json&met=" + ("add" == oper ? "add" : "edit"), $(form).serialize(), function (resp)
						{
							if (200 == resp.status)
							{
								resp.data['id'] = resp.data['app_resource_id'];
								parent.parent.Public.tips({content: n + "成功！"});
								callback && "function" == typeof callback && callback(resp.data, oper, window)
							}
							else
							{
								parent.parent.Public.tips({type: 1, content: n + "失败！" + resp.msg})
							}
							
							// 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
							me.holdSubmit(false);
						})
					},
					function ()
					{
						me.holdSubmit(false);
					});
			},
		}).on("click", "a.submit-btn", function (e)
		{
			$(e.delegateTarget).trigger("validate");
		});
	}
	
	function cancleGridEdit()
	{
		null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
	}
	
	//设置表单元素回车事件
	function bindEventForEnterKey()
	{
		Public.bindEnterSkip($_form, function()
		{
			$('#grid tr.jqgrow:eq(0) td:eq(0)').trigger('click');
		});
	}
	
	function resetForm(t)
	{
		$('#app_resource_id').val('');
		$('#app_id_ver').val('');
		$('#app_version').val('');
		$('#app_version_next').val('');
		$('#app_res_filename').val('');
		$('#app_res_filesize').val('');
		$('#app_package_url').val('');
		$('#app_res_time').val('');
		$('#app_update_log').val('');

        $("input[name='app_reinstall'][value=0]").click();
        $("input[name='app_release'][value=0]").click();
	}
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>


