    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="<?= $this->view->css ?>/seller.css?ver=<?=VER?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css_com ?>/ztree.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/base.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
	<script type="text/javascript">
		var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
		var SITE_URL = "<?=Yf_Registry::get('url')?>";
		var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
		var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";

		var DOMAIN = document.domain;
		var WDURL = "";
		var SCHEME = "default";
		try
		{
			//document.domain = 'ttt.com';
		} catch (e)
		{
		}

		var SYSTEM = SYSTEM || {};
		SYSTEM.skin = 'green';
		SYSTEM.isAdmin = true;
		SYSTEM.siExpired = false;
	</script>
        <style>
            #eject_con dl{overflow: visible;}
        </style>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">
<div class="eject_con" id="eject_con">
  <form id="form" method="post" action="#" >

   
    <dl>
      <dt><i class="required">*</i><?=__('品牌名称：')?></dt>
      <dd>
          <input class="text w200"   style=" height: 30px;" type="text" name="brand_name" value="" />
      </dd>
    </dl>
   
    <dl>
      <dt><?=__('品牌类别：')?></dt>
      <input type="hidden" name="cat_id" id="cat_id">
      <dd>
          <p id="cat_name"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?=__('品牌图标：')?></dt>
     <dd>
         <div ><span class="sign"><img width="150" height="50" id="brand_img" src=""></span></div>
         <input type="hidden" class="input-file" name="brand_pic" value="" id="brand_input">

        <div id="brand_upload" style="width: 190px;margin-top: 10px" >
                <p  class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?></p>
        </div>
        <p class="hint"><?=__('建议上传大小为150x50的品牌图片')?>。<br><?=__('申请品牌的目的是方便买家通过品牌索引页查找商品，申请时请填写品牌所属的类别，方便平台归类。在平台审核前，您可以编辑或撤销申请。')?></p>
      </dd>
    </dl>
    <div class="bottom">
          <label class="submit-border"><input type="submit" class="bbc_seller_submit_btns" value="<?=__('确定')?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.ui.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/seller.js"></script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.combo.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.ztree.all.js"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com?>/plugins/jquery.cookie.js"></script>

<script>
	$(function() {

		//商品类别
		var opts = {
			width : 188,
			//inputWidth : (SYSTEM.enableStorage ? 145 : 208),
			inputWidth : 198,
			defaultSelectValue : '-1',
			//defaultSelectValue : rowData.categoryId || '',
			showRoot : true,
                        rootTxt: '<?=__('选择分类')?>'
		}

		categoryTree = Public.categoryTree($('#cat_name'), opts);
        $('#cat_name').css('height','30px');
		$('#cat_name').change(function(){
			var i = $(this).data('id');
                        $('#cat_id').val(i);
		});

	});
</script>
<script type="text/javascript">

function refreshPage() 
{ 
 parent.location.reload();
} 
    
 $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Brand&met=addBrandrow&typ=json';
        $('#form').validator({
            // ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                tel:[/^[1][0-9]{10}$/,'<?=__('请输入正确的手机号码')?>'],
                A:[ /^[a-zA-Z]{1}$/,"<?=__('请输入首字母')?>"]
            },
            fields: {
              'brand_name': 'required;length[~30]',
              'brand_initial':'required;A',
              'brand_pic':'required'
            },
           valid:function(form){
                var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            parent.Public.tips.success("<?=__('修改成功！')?>");
                            window.setTimeout("refreshPage()",3000);
                        }
                        else
                        {
                             parent.Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });
    });


    //图片上传
    $(function(){
        //图片裁剪
        $('#brand_upload').on('click', function () {
            $.dialog({
                title: "<?=__('图片裁剪')?>",
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                data: { width:150,height:50, callback: callback },    // 需要截取图片的宽高比例
                width: '300px',
                height: '320px',
                lock: true,
                zIndex: 8192
            })
        });

        function callback ( respone , api ) {
            $('#brand_img').attr('src', respone.url);
            $('#brand_input').attr('value', respone.url);
            $("#brand_input").isValid();
            api.close();
        }

        if ( window.isIE8 ) {
            $('#brand_upload').off("click");

            new UploadImage({
                thumbnailWidth: 200,
                thumbnailHeight: 60,
                imageContainer: '#brand_img',
                uploadButton: '#brand_upload',
                inputHidden: '#brand_input'
            });
        }
    
    })
 
</script>
</script>
