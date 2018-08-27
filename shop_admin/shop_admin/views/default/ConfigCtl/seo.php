<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <body>
    <div class="wrapper page">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3><?=$menus['father_menu']['menu_name']?></h3>
                    <h5><?=$menus['father_menu']['menu_url_note']?></h5>
                </div>
                <ul class="tab-base nc-row">
                    <?php
                    foreach($menus['brother_menu'] as $key=>$val){
                        if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                            ?>
                            <li><a href="javascript:void(0);" <?php switch($val['menu_name']){ case '首页':?> class="current" nctype="index"<?php break;case '团购':?> nctype="tg"<?php break;case '品牌':?> nctype="brand" <?php break;case '积分中心':?> nctype="point" <?php break; case '文章': ?> nctype="article" <?php break; case '店铺':?> nctype="shop" <?php break; case '商品':?> nctype="product" <?php break; case '商品分类':?> nctype="category" <?php break; case 'SNS':?> nctype="sns" <?php break;}?> ><span><?=$val['menu_name']?></span></a></li>
                            <?php
                        }
                    }
                    ?>
                    <!-- <li><a href="javascript:void(0);" nctype="index" class="current">首页</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="tg">团购</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="brand">品牌</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="point">积分中心</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="article">文章</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="shop">店铺</a></li>
                    <li><a class="" href="javascript:void(0);" nctype="product">商品</a></li>
                    <li><a href="javascript:void(0);" nctype="category">商品分类</a></li> -->
                    <!--<li><a href="javascript:void(0);" nctype="sns">SNS</a></li>-->
                </ul>
            </div>
        </div>
        <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em> </div>
            <ul>
                <?=$menus['this_menu']['menu_url_note']?>
            </ul>
        </div>
        <form style="" method="post" name="form_index" id="seo-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>首页</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="title" name="seo[title]" value="<?=($data['title']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置标题名</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="keyword" name="seo[keyword]" value="<?=($data['keyword']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置关键搜索词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="description" name="seo[description]" value="<?=($data['description']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置对应的描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_tg" id="tg-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag">
                <a style="padding-left: 5px;">{sitename}</a>
                <!--                <a style="padding-left: 5px;">{name}</a>-->
            </span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>团购</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_title" name="seo[tg_title]" value="<?=($data['tg_title']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置团购首页标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_keyword" name="seo[tg_keyword]" value="<?=($data['tg_keyword']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置团购首页关键字</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_description" name="seo[tg_description]" value="<?=($data['tg_description']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置团购首页描述</p>
                    </dd>
                </dl>
                <div class="title">
                    <h3>团购内容</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_title_content">title</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_title_content" name="seo[tg_title_content]" value="<?=($data['tg_title_content']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置对应标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_keyword_content"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_keyword_content" name="seo[tg_keyword_content]" value="<?=($data['tg_keyword_content']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="tg_description_content"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="tg_description_content" name="seo[tg_description_content]" value="<?=($data['tg_description_content']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相关团购列表的描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_brand" id="brand-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a id="show_name" style="padding-left: 5px;">{name}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>品牌</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_title" name="seo[brand_title]" value="<?=($data['brand_title']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入品牌主题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_keyword" name="seo[brand_keyword]" value="<?=($data['brand_keyword']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入品牌搜索关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_description" name="seo[brand_description]" value="<?=($data['brand_description']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请设置对品牌的描述</p>
                    </dd>
                </dl>
                <div class="title">
                    <h3>某一品牌商品列表</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_title_content"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_title_content" name="seo[brand_title_content]" value="<?=($data['brand_title_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入该品牌的标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_keyword_content"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_keyword_content" name="seo[brand_keyword_content]" value="<?=($data['brand_keyword_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入该品牌的关键词搜索</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="brand_description_content"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="brand_description_content" name="seo[brand_description_content]" value="<?=($data['brand_description_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入对应品牌的描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_point" id="point-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a id="show_name" style="padding-left: 5px;">{name}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>积分中心</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_title" name="seo[point_title]" value="<?=($data['point_title']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入积分标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_keyword" name="seo[point_keyword]" value="<?=($data['point_keyword']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入对应的搜索词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_description" name="seo[point_description]" value="<?=($data['point_description']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="title">
                    <h3>积分中心商品内容</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_title_content"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_title_content" name="seo[point_title_content]" value="<?=($data['point_title_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入商品标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_keyword_content"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_keyword_content" name="seo[point_keyword_content]" value="<?=($data['point_keyword_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="point_description_content"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="point_description_content" name="seo[point_description_content]" value="<?=($data['point_description_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_article" id="article-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a style="padding-left: 5px;">{name}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>文章分类列表</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_title" name="seo[article_title]" value="<?=($data['article_title']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_keyword" name="seo[article_keyword]" value="<?=($data['article_keyword']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_description" name="seo[article_description]" value="<?=($data['article_description']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="title">
                    <h3>文章内容</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_title_content"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_title_content" name="seo[article_title_content]" value="<?=($data['article_title_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_keyword_content"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_keyword_content" name="seo[article_keyword_content]" value="<?=($data['article_keyword_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="article_description_content"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="article_description_content" name="seo[article_description_content]" value="<?=($data['article_description_content']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_shop" id="store-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a style="padding-left: 5px;">{shopname}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>店铺</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="shop_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="shop_title" name="seo[shop_title]" value="<?=($data['shop_title']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="shop_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="shop_keyword" name="seo[shop_keyword]" value="<?=($data['shop_keyword']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="shop_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="shop_description" name="seo[shop_description]" value="<?=($data['shop_description']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_product" id="product-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a style="padding-left: 5px;">{name}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>商品</h3>
                </div>
                <dl class="row">
                    <dt class="tit">
                        <label for="product_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="product_title" name="seo[product_title]" value="<?=($data['product_title']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="product_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="product_keyword" name="seo[product_keyword]" value="<?=($data['product_keyword']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="product_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="product_description" name="seo[product_description]" value="<?=($data['product_description']['config_value'])?>" class="ui-input w280 show_list" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <form style="display: none;" method="post" name="form_category" id="category-setting-form">
            <input type="hidden" name="config_type[]" value="seo"/>
            <input name="form_submit" value="ok" type="hidden">
            <span style="display:none" nctype="hide_tag"> <a style="padding-left: 5px;">{sitename}</a></span>
            <div class="ncap-form-default">
                <div class="title">
                    <h3>商品分类</h3>
                </div>

                <dl class="row">
                    <dt class="tit">
                        <label for="category_title"> title</label>
                    </dt>
                    <dd class="opt">
                        <input id="category_title" name="seo[category_title]" value="<?=($data['category_title']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入标题</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="category_keyword"> keyword</label>
                    </dt>
                    <dd class="opt">
                        <input id="category_keyword" name="seo[category_keyword]" value="<?=($data['category_keyword']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入关键词</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="category_description"> description</label>
                    </dt>
                    <dd class="opt">
                        <input id="category_description" name="seo[category_description]" value="<?=($data['category_description']['config_value'])?>" class="ui-input w280 show" type="text" />
                        <span id="theme"></span>
                        <p class="notic">请输入相应描述</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
        <!--  <form style="display: none;" method="post" name="form_sns" id="sns-setting-form">
      <input type="hidden" name="config_type[]" value="seo"/>
      <input name="form_submit" value="ok" type="hidden">
      <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a><a style="padding-left: 5px;">{name}</a></span>
      <div class="ncap-form-default">
        <div class="title">
        <h3>SNS</h3>
        </div>
        <dl class="row">
        <dt class="tit">
                    <label for="sns_title"> title</label>
                </dt>
        <dd class="opt">
         <input id="sns_title" name="seo[sns_title]" value="<?=($data['sns_title']['config_value'])?>" class="ui-input w280" type="text" />
          <span id="theme"></span>
          <p class="notic"></p>
        </dd>
        </dl>
        <dl class="row">
        <dt class="tit">
                    <label for="sns_keyword"> keyword</label>
                </dt>
        <dd class="opt">
          <input id="keyword" name="seo[sns_keyword]" value="<?=($data['sns_keyword']['config_value'])?>" class="ui-input w280" type="text" />
          <span id="theme"></span>
          <p class="notic"></p>
        </dd>
        </dl>
        <dl class="row">
        <dt class="tit">
                    <label for="sns_description"> description</label>
                </dt>
        <dd class="opt">
          <input id="sns_description" name="seo[sns_description]" value="<?=($data['sns_description']['config_value'])?>" class="ui-input w280" type="text" />
          <span id="theme"></span>
          <p class="notic"></p>
        </dd>
        </dl>
        <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
      </div>
      </form> -->
        <div style="display: none;" id="tag_tips"> <span class="dialog_title">可用的代码，点击插入</span>
            <div style="margin: 0px; padding: 0px;line-height:25px;"><a style="padding-left: 5px; cursor: pointer;">{sitename}</a></div>
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.tab-base').find('a').bind('click',function(){

                $("#tag_tips").css('display','none');
                $('.tab-base').find('a').removeClass('current');
                $(this).addClass('current');
                $('form').css('display','none');
                $('form[name="form_'+$(this).attr('nctype')+'"]').css('display','');
                $('span[nctype="hide_tag"]').find('a').css('padding-left','5px');
                $("#tag_tips>div").html($('form[name="form_'+$(this).attr('nctype')+'"]').find('span').html());
                $("#tag_tips").find('a').css('cursor','pointer');
                $("#tag_tips").find('a').bind('click',function(){
                    var value = $(CUR_INPUT).val();
                    if(value.indexOf($(this).html())<0 ){
                        $(CUR_INPUT).val(value+$(this).html());
                    }
                });
            });

            // $('input[type="text"]').bind('focus',function(){
            //     CUR_INPUT = this;
            //     //定位弹出层的坐标
            //     var pos = $(this).offset();
            //     var pos_x = pos.left+300;
            //     var pos_y = pos.top-20;
            //     $("#tag_tips").css({'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block'});
            // });

            $('input.show').bind('focus',function(){
                CUR_INPUT = this;
                //定位弹出层的坐标
                var pos = $(this).offset();
                var pos_x = pos.left+300;
                var pos_y = pos.top-20;
                $("#tag_tips").css({'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block'});
                $("#tag_tips #show_name").hide();
            });

            $('input.show_list').bind('focus',function(){
                CUR_INPUT = this;
                //定位弹出层的坐标
                var pos = $(this).offset();
                var pos_x = pos.left+300;
                var pos_y = pos.top-20;
                $("#tag_tips").css({'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block'});
                $("#tag_tips #show_name").show();
            });

            $('form').css('display','none');
            $('form[name="form_index"]').css('display','');

            $('#category').bind('change',function(){
                $.getJSON('index.php?act=seo&op=ajax_category&id='+$(this).val(), function(data){
                    if(data){
                        $('#cate_title').val(data.gc_title);
                        $('#cate_keyword').val(data.gc_keyword);
                        $('#cate_description').val(data.gc_description);
                    }else{
                        $('#cate_title').val('');
                        $('#cate_keyword').val('');
                        $('#cate_description').val('');
                    }
                });
            });
            $('.tab-base').find('a').eq(0).click();

        });
    </script>
    <style>
        #tag_tips{
            padding:4px;border-radius: 2px 2px 2px 2px;box-shadow: 0 0 4px rgba(0, 0, 0, 0.75);display:none;padding: 4px;width:300px;z-index:9999;background-color:#FFFFFF;
        }
        .dialog_title {
            background-color: #F2F2F2;
            border-bottom: 1px solid #EAEAEA;
            color: #666666;
            display: block;
            font-weight: bold;
            line-height: 14px;
            padding: 5px;
        }
    </style>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>