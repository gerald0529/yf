    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="<?= $this->view->css ?>/seller.css?ver=<?=VER?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css_com ?>/ztree.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <link href="<?= $this->view->css ?>/base.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
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
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">
<div class="eject_con" id="eject_con">
  <form id="form" method="post" action="#" >

    <dl>
      <dt><?=__('搜索经营类目：')?></dt>
      <dd><input type="text" class="text w150 heigh" style="" id="searchName" name="searchName" placeholder="<?=__('按经营类目名称搜索')?>"><a class="button button-only" id="searchButton" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i></a></dd>
    </dl>
    <dl>
      <dt><?=__('经营类目：')?></dt>
      <input type="hidden" name="cat_id" id="cat_id">
      <dd>
          <p id="cat_name"></p>
      </dd>
    </dl>
    <dl>
      <dt></dt>
     <dd>
        
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
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.ztree.exhide.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com?>/plugins/jquery.cookie.js"></script>


<script>
  var categoryTree;
	$(function() {

		//商品类别
		var opts = {
			width : 180,
			//inputWidth : (SYSTEM.enableStorage ? 145 : 208),
			inputWidth : 190,
			defaultSelectValue : '-1',
			//defaultSelectValue : rowData.categoryId || '',
			showRoot : true,
            rootTxt: "<?=__('添加经营类目')?>",
            disExpandAll: false,
            searchByName: "#searchName",
            searchButton: "#searchButton"
		}

		categoryTree = Public.categoryTree($('#cat_name'), opts);
        $('#cat_name').css('height','30px');
		$('#cat_name').change(function(){
			var i = $(this).data('id');
                        $('#cat_id').val(i);
		});


    




	});



  ///根据文本框的关键词输入情况自动匹配树内节点 进行模糊查找
    function AutoMatch(txtObj) {
                if (txtObj.value.length > 0) { 
                    var zTree = categoryTree.zTree;
                    console.log(zTree);
                    var nodeList = zTree.getNodesByParamFuzzy("name", txtObj.value); 
                    $.fn.zTree.init($("#cat_name"), setting, nodeList);
                    
                } else {
                      
                               
                }              
    }


</script>
<script type="text/javascript">
function refreshPage() 
{ 
    parent.location.reload();
} 

    
 $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Info&met=addcategoryrow&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
              
            },
            fields: {
                 'entity[entity_name]': 'required',
                'entity[entity_xxaddr]':'required',
                'entity[entity_tel]':'tel'
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
                           parent.Public.tips.success("<?=__('操作成功！')?>");
                            refreshPage();
                        }
                        else
                        {
                            if(a.msg !== 'failure')
                            {
                                parent.Public.tips.error(a.msg);
                            }else{
                                parent.Public.tips.error("<?=__('操作失败！')?>");
                            }

                            me.holdSubmit(false);
                        }
                    }
                });
            }

        });
    });



    

 
</script>
</script>
