<script type="text/javascript" src="<?=$this->view->js?>/buyer.js"></script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/nav.js"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/base.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/order.js"></script>

<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/buyer.js"></script>
</div>
</div>
</div>
<div>
    <div class="wrapper">
        <p class="about">
            <?php if($this->bnav){
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
</div>
<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.ui.js"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<iframe id='imbuiler' scrolling="no" frameborder="0" class="im-show"  src=''></iframe>
<p class="statistics_code"><?php echo Web_ConfigModel::value('statistics_code') ?></p>
<script>
    $(function(){
        ucenterLogin(UCENTER_URL, SITE_URL, true);
    });
</script>
</body>
</html>