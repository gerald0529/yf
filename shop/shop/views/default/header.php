<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    include $this->view->getTplPath() . '/' . 'site_nav.php';
    /*$search_words = array_map(function($v) {
        return sprintf('<a href="%s?ctl=Goods_Goods&met=goodslist&typ=e&keywords=%s" class="cheap">%s</a>', Yf_Registry::get('url'), urlencode($v), $v);
    }, explode(',',  Web_ConfigModel::value('search_words')));
    $keywords = current($this->searchWord);*/
    $search_array = array();
    if (is_array($this->searchWord)) {
        foreach ($this->searchWord as $key => $val) {
            $search_array[] = $val['search_keyword'];
        }
    }
    $search_words = array_map(function ($v) {
        return "<a href='".url('Goods_Goods/goodslist',[
            'typ'=>'e',
            'keywords'=>urlencode($v)
        ])."'>".$v."</a>";
        
    }, $search_array);
    $keywords = Web_ConfigModel::value('search_words');
    $shop_keywords = Web_ConfigModel::value('search_shop_words');
?>
<script src="<?= $this->view->js_com ?>/iealert.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css_com ?>/iealert/style.css" />
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.blueberry.js"></script>
<script src="<?= $this->view->js_com ?>/plugins/jquery.timeCountDown.js"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/jquery.lazy.js"></script>
<link href="<?= $this->view->css ?>/select2.min.css" rel="stylesheet">
<script src="<?= $this->view->js ?>/select2.min.js"></script>
<?php include APP_PATH . '/alert_box.php'; ?>
<div class="wrap">
    <div class="head_cont">
        <div style="clear:both;"></div>
        <div class="nav_left">
            <a href="<?= Yf_Registry::get('url') ?>" class="logo">
                <img src="<?php if (Web_ConfigModel::value('subsite_is_open') && isset($_COOKIE['sub_site_logo']) && $_COOKIE['sub_site_logo'] != '' && isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0) {
                    echo $_COOKIE['sub_site_logo'];
                } else {
                    if (@$this->web['web_logo']) {
                        echo @$this->web['web_logo'];
                    } else {
                        echo $this->view->img . '/setting_logo.jpg';
                    }
                } ?>" />
            </a>
            <a href="#" class="download iconfont"></a>
        </div>
        <div class="nav_right clearfix">
            <ul class="clearfix search-types">
                <li class="<?php if (@request_string('ctl') != 'Shop_Index') echo 'active'; ?>" onclick="searchWords()">
                    <a href="javascript:void(0);" data-param='goods'><?= __('宝贝') ?></a>
                </li>
                <li class="<?php if (@request_string('ctl') == 'Shop_Index') echo 'active'; ?>" onclick="searchShopWords()" id = "shop">
                    <a href="javascript:void(0);" data-param='shop'><?= __('店铺') ?></a>
                </li>
            </ul>
            <div class="clearfix">
                <form name="form_search" id="form_search" action="" class="">
                    <input type="hidden" id="search_ctl" name="ctl" value="<?php if (@request_string('ctl') != 'Shop_Index') echo 'Goods_Goods'; else echo 'Shop_Index'; ?>">
                    <input type="hidden" id="search_met" name="met" value="<?php if (@request_string('ctl') != 'Shop_Index') echo 'goodslist'; else echo 'index'; ?>">
                    <input type="hidden" name="typ" value="e">
                    <input name="keywords" id="site_keywords" type="text" value="<?= request_string('keywords') ?>" placeholder="<?php if (@request_string('ctl') == 'Shop_Index') echo $shop_keywords; else echo $keywords ?>">
                    <input type="submit" style="display: none;">
                </form>
                <a href="#" class="ser" id="site_search"><?= __('搜索') ?></a>
                <!-- 购物车 -->
                <div class="bbuyer_cart" id="J_settle_up">
                    <div id="J_cart_head">
                        <a href="<?=url('Buyer_Cart/cart')?>" target="_blank" class="bbc_buyer_icon bbc_buyer_icon2">
                            <i class="ci_left iconfont icon-zaiqigoumai bbc_color rel_top2"></i>
                            <span><?= __('我的购物车') ?></span> <i class="ci_right iconfont icon-iconjiantouyou"></i>
                            <i class="ci-count bbc_bg" id="cart_num">0</i> </a>
                    </div>
                    <div class="dorpdown-layer zIndex12" id="J_cart_body"><span class="loading"></span></div>
                </div>
            </div>
            <div class="nav clearfix searchs">
                <?= implode($search_words) ?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <script>
        // 谁有active就给谁默认词
        $(function () {
            if ($('#shop').is('.active')){
                $("#site_keywords").attr('placeholder', "<?= $shop_keywords;?>");
            }
        });
        // 当点击宝贝时，填充商品关键词
        function searchWords() {
            $("#site_keywords").attr('placeholder', "<?= $keywords;?>");
        }
        
        // 当点击店铺时，填充店铺关键词
        function searchShopWords() {
            $("#site_keywords").attr('placeholder', "<?= $shop_keywords;?>");
        }

    </script>
    <div>
        <div class="thead clearfix">
            <div class="classic clearfix">
                <div class="class_title"><span>&equiv;</span><a href="<?=url('Goods_Cat/goodsCatList')?>" class="ta1"><?= __('全部分类') ?></a></div>
                <div class="tleft" id="show" <?php if (($this->ctl == "Index" && $this->met == "index") || ($this->ctl == "" && $this->met == "")){ ?>style="display:block;"<?php } else { ?> style="display: none;"<?php } ?>>
                    <ul>
                        <?php if ($this->cat) {
                            $i = 0;
                            foreach ($this->cat as $keyone => $catone) {
                                if ($i < 14) {
                                    ?>
                                    <li>
                                        
                                        <h3>
                                            <?php if (!empty($catone['cat_nav'])) { ?>
                                                <?php if ($catone['cat_nav']['goods_cat_nav_pic']) { ?>
                                                    <img width="16" height="16" style="margin-right: 6px;" src="<?= $catone['cat_nav']['goods_cat_nav_pic'] ?>">
                                                <?php } ?>
                                                <a href="<?=url('Goods_Goods/goodslist',['cat_id'=> $catone['cat_nav']['goods_cat_id']]) ?>"><?= $catone['cat_nav']['goods_cat_nav_name'] ?></a><?php } else { ?>
                                                <?php if ($catone['cat_pic']) { ?>
                                                    <img width="16" height="16" style="margin-right: 6px;" src="<?= $catone['cat_pic'] ?>">
                                                <?php } ?>
                                                <a href="<?=url('Goods_Goods/goodslist',['cat_id'=>$catone['cat_id']]) ?>"><?= $catone['cat_name'] ?></a>
                                            <?php } ?>
                                            <span class="iconfont icon-iconjiantouyou"></span>
                                        </h3>
                                        
                                        <div class="hover_content clearfix">
                                            <div class="left">
                                                
                                                <div class="channels">
                                                    <?php if (!empty($catone['brand'])) {
                                                        foreach ($catone['brand'] as $brand_key => $brand_value) {
                                                            if (7 >= $brand_key && $brand_value) {
                                                                ?>
                                                                <a href="<?=url('Goods_Goods/goodslist',['brand_id'=>$brand_value['brand_id']]) ?>"><?= $brand_value['brand_name'] ?><span class="iconfont icon-iconjiantouyou "></span></a>
                                                            <?php }
                                                        }
                                                    } ?>
                                                </div>
                                                
                                                <div class="rel_content">
                                                    <?php
                                                        if (!empty($catone['cat_nav'])) {
                                                            ?>
                                                            <?php
                                                            foreach ($catone['cat_nav']['goods_cat_nav_recommend_display'] as $key => $value) {
                                                                ?>
                                                                <dl class="clearfix">
                                                                    <dt>
                                                                        <a href="<?=url('Goods_Goods/goodslist',['cat_id'=>$value['cat_id']]) ?>">
                                                                            <?= $value['cat_name'] ?>
                                                                            <span class="iconfont icon-iconjiantouyou rel_top1"></span>
                                                                        </a>
                                                                    </dt>
                                                                    <dd>
                                                                        <?php if (!empty($value['sub'])) {
                                                                            foreach ($value['sub'] as $sub_key => $sub_value) {
                                                                                ?>
                                                                                <a href="<?=url('Goods_Goods/goodslist',['cat_id'=>$sub_value['cat_id']]) ?>">
                                                                                    <?= $sub_value['cat_name'] ?>
                                                                                </a>
                                                                            <?php }
                                                                        } ?>
                                                                    </dd>
                                                                </dl>
                                                            <?php } ?>
                                                        <?php } ?>
                                                </div>
                                            </div>
                                            
                                            <!-- 广告位-->
                                            <div class="right">
                                                <!-- 品牌-->
                                                <?php if (!empty($catone['brand'])) { ?>
                                                    <div class="clearfix mb10">
                                                        <a class="fr" href="<?=url('Goods_Brand/index')?>">
                                                            更多品牌
                                                            <span class="middle iconfont icon-btnrightarrow"></span>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <ul class="d1ul clearfix mb10">
                                                    <?php if (!empty($catone['brand'])) {
                                                        foreach ($catone['brand'] as $brand_key => $brand_value) {
                                                            if (3 >= $brand_key && $brand_value) {
                                                                ?>
                                                                <li class="">
                                                                    <a href="<?=url('Goods_Goods/goodslist',['brand_id'=>$brand_value['brand_id']]) ?>"><img src="<?= $brand_value['brand_pic'] ?>" alt="<?= $brand_value['brand_name'] ?>">
                                                                        <span><?= $brand_value['brand_name'] ?></span>
                                                                    </a>
                                                                </li>
                                                            
                                                            <?php }
                                                        }
                                                    } ?>
                                                </ul>
                                                
                                                <ul class="index_ad_big">
                                                    <?php if (!empty($catone['adv'])) {
                                                        $adv_url = explode(',', $catone['cat_nav']['goods_cat_nav_adv_url']);
                                                        foreach ($catone['adv'] as $adv_key => $adv_value) {
                                                            ?>
                                                            <li>
                                                                <a href="<?php if ($adv_url[$adv_key]) {
                                                                    echo $adv_url[$adv_key];
                                                                } else {
                                                                    echo "javascript:;";
                                                                } ?>">
                                                                    <img src="<?= $adv_value ?>"></a>
                                                            </li>
                                                        <?php }
                                                    } ?>
                                                
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                                $i++;
                            }
                        } ?>
                    </ul>
                </div>
            </div>
            <nav class="tnav" shop_id="<?= Perm::$shopId ?>">
                <?php if ($this->nav) {
                    foreach ($this->nav['items'] as $key => $nav) {
                        if ($key < 10) {
                            ?>
                            <a <?php 
                            $_href  = true;
                            if (strpos($nav['nav_title'], '批发市场') !== false) { 
                                if(Perm::$userId<=0){ echo 'class="not_shop_login" ';  $_href  = false;}  
                                elseif(Perm::$shopId <= 0 ){ echo 'class="not_shop"';  $_href  = false;} 
                            } ?> 
                            
                            <?php if($_href){?>  href="<?= $nav['nav_url'] ?>"<?php }?>
                             <?php if ($nav['nav_new_open'] == 1){ ?>target="_blank"<?php } ?>><?= $nav['nav_title'] ?></a>
                        <?php }
                    }
                } ?>
            </nav>
            <p class="high_gou"></p>
        </div>
    </div>
</div>
<div class="hr" style="background:#c51e1e;">
</div>
<div class="J-global-toolbar">
</div>
