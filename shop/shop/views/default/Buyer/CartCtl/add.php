<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css"/>
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
<style>
    body{height:auto;}
</style>
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
<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
            <form id="form" action="#" method="post">
                <div style="text-align: center;line-height:43px;"><?=__('商品已成功加入购物车！')?></div>
                <div class="bottom">
                    <!--<label class="submit-border"><input id="handle_submit" class="submit" value="返回"/></label>-->
                        <label class="submit-border"><input id="cart" class="submit bbc_btns" value="<?=__('去购物车结算')?>" readonly/></label>
                </div>
            </form>
</div>
<link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script>
    $("#handle_submit").bind("click",function(){
        parent.location.reload();
        $.dialog().close();
    });
$("#cart").bind("click",function(){
    parent.location.href = SITE_URL+ '?ctl=Buyer_Cart&met=cart&typ=e';
    $.dialog().close();
});

</script>