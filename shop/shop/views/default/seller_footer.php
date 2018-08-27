<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
    <script type="text/javascript">
        $('.dropdown').hover(function ()
        {
            $(this).addClass("hover");
        }, function ()
        {
            $(this).removeClass("hover");
        });

        $('.js-sitemap').on('click', function() {
            $('.js-menu-arrow, .sitemap-menu').show();
        });

        $('#closeSitemap').on('click', function() {
            $('.js-menu-arrow, .sitemap-menu').hide();
        })
    </script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.ui.js"></script>

    <script type="text/javascript" src="<?=$this->view->js?>/seller.js"></script>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js"
            charset="utf-8"></script>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>

		</div>
	</div>
</div>
<div class="footer">
	<div class="wrapper">
		<p class="about">
            <?php if(isset($this->bnav) && $this->bnav){
                foreach ($this->bnav['items'] as $key => $nav) {
                    if($key<10){
                        ?>
                        <a href="<?=$nav['nav_url']?>" <?php if($nav['nav_new_open']==1){?>target="_blank"<?php } ?>><?=$nav['nav_title']?></a>
                    <?php }else{
                        return;
                    }}} ?>
		</p>

		<p class="copyright"><?php if(!empty($_COOKIE['sub_site_id']) && Web_ConfigModel::value("subsite_is_open") == Sub_SiteModel::SUB_SITE_IS_OPEN  && isset($_COOKIE['sub_site_copyright'])){ echo $_COOKIE['sub_site_copyright'];}else{ echo  Web_ConfigModel::value('copyright');} ?></p>
	</div>
</div>

<?php if(Yf_Registry::get('im_statu')){ ?>
<iframe id='imbuiler' scrolling="no" frameborder="0" class="im-show"  src='<?=Yf_Registry::get('base_url').'/im/index.php'?>'></iframe>

<?php } ?>
<script type="text/javascript" src="<?= $this->view->js ?>/im.js"></script>
<script>
	$(function(){
		ucenterLogin(UCENTER_URL, SITE_URL, true);
	});
</script>
</body>
</html>