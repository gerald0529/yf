<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>

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
        <dl>
            <dt><?=__('回复内容：')?></dt>
            <dd>
                <?=$data['custom_service_answer']?>
            </dd>
        </dl>
</div>