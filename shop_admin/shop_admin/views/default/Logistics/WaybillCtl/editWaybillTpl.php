<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style>
    .webuploader-pick{ padding:1px; }
    
</style>
</head>
<body>
<div class="">
    <form method="post" enctype="multipart/form-data" id="tpl-edit-form" name="form">
		<input id="waybill_tpl_id" name="waybill_tpl_id"  value="<?=$data['waybill_tpl_id']?>" class="ui-input w400" type="hidden"/>
        <div class="ncap-form-default">
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>模板名称</label>
                </dt>
                <dd class="opt">
					<input id="waybill_tpl_name" name="waybill_tpl_name"  value="<?=$data['waybill_tpl_name']?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>物流公司</label>
                </dt>
                <dd class="opt">					
					<select name="express_id">
					<?php foreach($data['express']['items'] as $key=>$val){?>
					 <option value ="<?=$val['express_id']?>" <?php if($val['express_id'] == $data['express_id']){?>selected="selected"<?php }?>><?=$val['express_name']?></option>
					<?php }?>
					</select>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>宽度</label>
                </dt>
                <dd class="opt">
					<input id="waybill_tpl_width" name="waybill_tpl_width" value="<?=$data['waybill_tpl_width']?>" class="ui-input w400" type="text"/>
                    <p class="notic"> 运单宽度，单位为毫米(mm)。</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>高度</label>
                </dt>
                <dd class="opt">
					<input id="waybill_tpl_height" name="waybill_tpl_height" value="<?=$data['waybill_tpl_height']?>" class="ui-input w400" type="text"/>
                    <p class="notic">运单高度，单位为毫米(mm)</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>上偏移量</label>
                </dt>
                <dd class="opt">
					<input id="waybill_tpl_top" name="waybill_tpl_top" value="<?=$data['waybill_tpl_left']?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>左偏移量</label>
                </dt>
                <dd class="opt">
					<input id="waybill_tpl_left" name="waybill_tpl_left" value="<?=$data['waybill_tpl_left']?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>模板图片</label>
                </dt>
                <dd class="opt">
                    <img id="waybill_tpl_image_image" name="waybill_tpl_image_image" alt="选择图片" src="<?=$data['waybill_tpl_image']?>" width="120px" height="60px" />

                    <div class="image-line upload-image" id="waybill_tpl_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="waybill_tpl_image"  name="waybill_tpl_image" value="<?=$data['waybill_tpl_image']?>" class="ui-input w400" type="hidden"/>
                    <div class="notic">请上传扫描好的运单图片，图片尺寸必须与快递单实际尺寸相符</div>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
						<div class="onoff" id="waybill_tpl_enable">
								<label title="开启" class="cb-enable <?=($data['waybill_tpl_enable']==1 ? 'selected' : '')?>" for="waybill_tpl_enable_enable">开启</label>
								<label title="关闭" class="cb-disable <?=($data['waybill_tpl_enable']==0 ? 'selected' : '')?>" for="waybill_tpl_enable_disabled">关闭</label>
								<input type="radio" value="1" name="waybill_tpl_enable" id="waybill_tpl_enable_enable"  <?=($data['waybill_tpl_enable']==1 ? 'checked' : '')?>/>
								<input type="radio" value="0" name="waybill_tpl_enable" id="waybill_tpl_enable_disabled" <?=($data['waybill_tpl_enable']==0 ? 'checked' : '')?>/>
						</div>
                        </li>
                    </ul>
                </dd>
         </dl>
          
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script>
	$(function(){

		waybill_tpl_upload = new UploadImage({
			thumbnailWidth: 800,
			thumbnailHeight: 400,
			imageContainer: '#waybill_tpl_image_image',
			uploadButton: '#waybill_tpl_upload',
			inputHidden: '#waybill_tpl_image'
		});

	})
    //图片上传
    /* $(function(){

        waybill_tpl_upload = new UploadImage({
            thumbnailWidth: 600,
            thumbnailHeight: 400,
            imageContainer: '#waybill_tpl_image_image',
            uploadButton: '#waybill_tpl_upload',
            inputHidden: '#waybill_tpl_image'
        });

       
    }) */
	function initPopBtns()
{
	var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm", name: t[0], focus: !0, callback: function ()
		{
			postData(oper, rowData.contract_type_id);
			return cancleGridEdit(),$("#tpl-edit-form").trigger("validate"), !1
		}
	}, {id: "cancel", name: t[1]})
}
			function postData(t, e)
			{
			$_form.validator({
				fields: {
					'waybill_tpl_name': 'required;',
					'express_id': 'required;integer[+]',
					'waybill_tpl_width': 'required;integer[+];',
					'waybill_tpl_height': 'required;integer[+];',
					'waybill_tpl_top': 'required;integer;',
					'waybill_tpl_left': 'required;integer;',
					'waybill_tpl_image': 'required;'
				},
				valid: function (form)
				{
					var me = this;
					// 提交表单之前，hold住表单，防止重复提交
					me.holdSubmit();
					n = "修改";
					Public.ajaxPost(SITE_URL + '?ctl=Logistics_Waybill&met=editWaybillTplDetail&typ=json', $_form.serialize(), function (e)
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
		var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#tpl-edit-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
		initPopBtns();
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>