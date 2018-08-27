<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
    <?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();


?>
        <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
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
                            <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
                            <?php 
                                }
                            }
                            ?>
                            <!-- <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=onlineTheme&config_type%5B%5D=site"><span>线上模板</span></a></li> -->

                        </ul>
                    </div>
                </div>
                <!-- 操作说明 -->
                <p class="warn_xiaoma"><span></span><em></em></p>
                <div class="explanation" id="explanation">
                    <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                        <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                        <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
                    <ul>
                        <?=$menus['this_menu']['menu_url_note']?>
                    </ul>
                </div>

                <form method="post" enctype="multipart/form-data" id="theme-setting-form" name="form1">
                    <input type="hidden" name="config_type[]" value="site" />

                    <?php
                        $theme_id = $data['theme_id']['config_value'];
                    
                        foreach ($data['theme_row'] as $k => $theme_row)
                        {
                            if ($theme_id == $theme_row['name'])
                            {
                                $config = $theme_row['config'];
                                break;
                            }
                        }
                        ?>





                        <div class="ncap-form-default">
                            <dl class="row">
                                <dt class="tit">
                    <!-- <label for="theme_id"> 网站默认模板</label> -->
                    <input id="theme_id" name="site[theme_id]" value="<?=($data['theme_id']['config_value'])?>" class="ui-input w400" type="hidden"/>
                </dt>
                                <dd class="opt">

                                  <!--   <p class="notic">设置网站默认模板</p> -->
                                    <div class="ncsc-store-templet">
                                        <dl class="current-style">
                                            <?php
                                            foreach ($data['theme_row'] as $id=>$theme_row)
                                            {
                                                if ($theme_row['id'] == $data['theme_id']['config_value'])
                                                {
                                                ?>
    
                                                    <dt class="templet-thumb"><img width="200" height="200" src="<?=$theme_row['config']['preview_img']?>" id="current_theme_img"></dt>
                                                    <dd>当前在用模板名称：<strong id="current_style"><?=$theme_row['name']?></strong></dd>
                                                    <dd>当前在用风格名称：<strong id="current_style"><?=$theme_row['config']['theme_label']?></strong></dd>
                                                    <?php if (isset($config['index_tpl']) && $config['index_tpl']):?>
                                                    <p class="links"><a href="<?= Yf_Registry::get('url') ?>?ctl=Floor_Adpage&met=adpage" onclick="parent.tab.addTabItem({text:'首页模板', url: '<?= Yf_Registry::get('url') ?>?ctl=Floor_Adpage&met=adpage'}); return false;"><span>首页模板设置</span></a></p>
                                                <?php endif;?>
                                                    <?php if (isset($config['index_slider']) && $config['index_slider']):?>
                                                    <p class="links"><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_slider&config_type%5B%5D=index_slider" onclick="parent.tab.addTabItem({text:'首页幻灯片', url: '<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_slider&config_type%5B%5D=index_slider'}); return false;"><span>首页幻灯片设置</span></a></p>
                                                <?php endif;?>
                                                    <?php if (isset($config['index_slider_img']) && $config['index_slider_img']):?>
                                                    <p class="links"><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_liandong&config_type%5B%5D=index_liandong" onclick="parent.tab.addTabItem({text:'首页联动小图', url: '<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_liandong&config_type%5B%5D=index_liandong'}); return false;"><span>首页联动小图设置</span></a></p>
                                                <?php endif;?>
                                                
                                            <?php
                                                }
    
                                            }
                                            ?>
                                        </dl>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <div class="templet-list">
                            <ul>
                                <?php
                                foreach ($data['theme_row'] as $id=>$theme_row)
                                {
                                    if ($theme_row['id'] == $data['theme_id']['config_value'])
                                    {
                                        continue;
                                    }
                                    
                                 ?>
                                    <li>
                                        <dl>
                                            <dt><a href="javascript:void(0)"><img width="200" height="200" id="themeimg_default" src="<?=$theme_row['config']['preview_img']?>"></a></dt>
                                            <dd>模板名称：<?=$theme_row['name']?></dd>
                                            <dd>风格名称：<?=$theme_row['config']['theme_label']?></dd>
                                            <dd class="btn"> <a href="javascript:useTheme('<?=$theme_row['name']?>');" class="yfbtn bbc_seller_btns"><i class="icon-cogs"></i>使用</a></dd>
                                        </dl>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                </form>
            </div>
            <script type="text/javascript">
                var theme_id = <?= encode_json($data['theme_id']['config_value']) ?>;
                var theme_row = <?= encode_json($data['theme_row']) ?>;

                
                function useTheme(theme_id)
                {
                    var d = 'config_type%5B%5D=site&site%5Btheme_id%5D=' + theme_id;

                    parent.$.dialog.confirm('修改风格后，有可能需要修改对应的首页模板、首页幻灯片、首页联动小图, 是否继续？', function ()
                        {
                            Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', d, function (data)
                            {
                                if (data.status == 200)
                                {
                                    parent.Public.tips({content: '修改操作成功！'});
                                    
                                    window.location.reload();
                                }
                                else
                                {
                                    parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                                }

                            });
                        },
                        function ()
                        {
                        });
                }

                
            </script>

            <?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>