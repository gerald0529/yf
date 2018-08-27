    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="<?= $this->view->css ?>/seller.css?ver=<?=VER?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css_com ?>/ztree.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
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
        <style>
            #eject_con dl{overflow: visible;} 
        </style>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">
<div class="eject_con" id="eject_con">
  <form id="form" method="post" action="#" >
    <input type="hidden" name="entity[province]" id="province" value="" />
    <input type="hidden" name="entity[city]" id="city" value="" />
    <input type="hidden" name="entity[district]" id="district" value="" />
    <input type="hidden" name="entity[street]" id="street" value="" />
    <input type="hidden" name="lng" id="lng" value="" />
    <input type="hidden" name="lat" id="lat" value="" />
    <dl>
      <dt><?=__('当前所在城市：')?></dt>
      <dd><div id="baidu_city"></div></dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?=__('实体店铺名称：')?></dt>
      <dd>
          <input class="text w200"   style=" height: 30px;" type="text" name="entity[entity_name]" value="" />
        <p class="hint"><?=__('不同地址建议使用不同名称以示区别，如“山西面馆(水游城店)”。')?></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?=__('详细地址：')?></dt>
      <dd>
        <input class="text w200"  style=" height: 30px;" type="text" name="entity_xxaddr" id="address_info" value=""  />
        <p class="hint"><?=__('为了准确定位建议地址加上所在城区名字，如“红桥区大丰路18号水游城”。')?></p>
      </dd>
    </dl>
    <dl>
      <dt><?=__('联系电话：')?></dt>
      <dd>
        <input class="text w200"  style=" height: 30px;" type="text" name="entity[entity_tel]" value=""  />
      </dd>
    </dl>
    <dl>
      <dt><?=__('公交信息')?>：</dt>
      <dd>
          <textarea name="entity[entity_transit]"rows="2" class="textarea w200 " style="width:200px;"></textarea>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border "><input type="submit" class="bbc_seller_submit_btns" value="<?=__('确定')?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.ui.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.alerts.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/seller.js"></script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<script type="text/javascript">
    function refreshPage() 
{ 
 parent.location.reload();
} 
    //获取经纬度
    function getCityName(province,district,street,city,lng,lat)
    {
        $("#baidu_city").html(city);
        $("#lng").val(lng);
        $("#lat").val(lat);
        $("#province").val(province);
        $("#city").val(city);
        $("#district").val(district);
        $("#street").val(street);
    }
    var api = frameElement.api;
    var callback = api.data.callback;

    if(callback && typeof callback == 'function')
    {
        callback(getCityName);
    }
    
 $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Entityshop&met=addEntityrow&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                tel:[/^[1][0-9]{10}$/,"<?=__('请输入正确的手机号码')?>"]
            },
            fields: {
                'entity[entity_name]': 'required;length[1~10]',
                'entity_xxaddr':'required;length[1~20]',
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

</script>
