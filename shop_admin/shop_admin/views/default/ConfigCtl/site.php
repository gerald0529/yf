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

            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="shop-setting-form" name="form1">
        <input type="hidden" name="config_type[]" value="site"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">网站名称</label>
                </dt>
                <dd class="opt">
                    <input id="site_name" name="site[site_name]" value="<?=($data['site_name']['config_value'])?>" class="ui-input w346" type="text"/>

                    <p class="notic">网站名称，将显示在前台顶部欢迎信息等位置</p>
                </dd>
            </dl>



            <dl class="row">
                <dt class="tit">
                    <label for="language">系统默认语言</label>
                    <input id="language_id" name="site[language_id]" value="<?=($data['language_id']['config_value'])?>" class="ui-input w346" type="hidden"/>
                </dt>
                <dd class="opt">
                    <span id="language"></span>

                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="time_zone"> 默认时区</label>
                    <input id="time_zone_id" name="site[time_zone_id]" value="<?=($data['time_zone_id']['config_value'])?>" class="ui-input w346" type="hidden"/>
                </dt>
                <dd class="opt">
                    <span id="time_zone"></span>

                    <p class="notic">设置系统使用的时区，中国为+8</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="monetary_unit">货币单位符号</label>
                </dt>
                <dd class="opt">
                    <input id="monetary_unit" name="site[monetary_unit]" value="<?=($data['monetary_unit']['config_value'])?>" class="ui-input w346" type="text"/>

                    <p class="notic">前台显示</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="date_format">日期格式</label>
                    <input id="date_format" name="site[date_format]" value="<?=($data['date_format']['config_value'])?>" class="ui-input w346" type="hidden"/>
                </dt>
                <dd class="opt">
                    <span id="date_format_combo"></span>
                    <p class="notic">前台页面显示</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="time_format">时间格式</label>
                    <input id="time_format" name="site[time_format]" value="<?=($data['time_format']['config_value'])?>" class="ui-input w346" type="hidden"/>

                </dt>
                <dd class="opt">
                    <span id="time_format_combo"></span>
                    <p class="notic">前台页面显示</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="monetary_unit">ICP证书号</label>
                </dt>
                <dd class="opt">
                    <input id="icp_number" name="site[icp_number]" value="<?=($data['icp_number']['config_value'])?>" class="ui-input w346" type="text"/>

                    <p class="notic">前台页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入你的授权码，它将显示在前台页面底部，如果没有请留空</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="statistics_code">第三方流量统计代码</label>
                </dt>
                <dd class="opt">
                    <textarea name="site[statistics_code]" class="ui-input w346" id="statistics_code"><?=($data['statistics_code']['config_value'])?></textarea>

                    <p class="notic">前台页面底部可以显示第三方统计</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="copyright">版权信息</label>
                </dt>
                <dd class="opt">
                    <textarea name="site[copyright]" class="ui-input w346" id="copyright"><?=($data['copyright']['config_value'])?></textarea>

                    <p class="notic">版权信息</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">站点状态</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="site_status1" name="site[site_status]"  value="1" type="radio" <?=($data['site_status']['config_value']==1 ? 'checked' : '')?>>
						<label title="开启" class="cb-enable <?=($data['site_status']['config_value']==1 ? 'selected' : '')?> " for="site_status1">开启</label>

                        <input id="site_status0" name="site[site_status]"  value="0" type="radio" <?=($data['site_status']['config_value']==0 ? 'checked' : '')?>>
						<label title="关闭" class="cb-disable <?=($data['site_status']['config_value']==0 ? 'selected' : '')?>" for="site_status0">关闭</label>
                    </div>
                    <p class="notic">可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="closed_reason">关闭原因</label>
                </dt>
                <dd class="opt">
                    <textarea name="site[closed_reason]" class="ui-input w346" id="closed_reason"><?=($data['closed_reason']['config_value'])?></textarea>

                    <p class="notic">当网站处于关闭状态时，关闭原因将显示在前台</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var time_zone_id =  '<?= ($data['time_zone_id']['config_value']) ?>';

    var language_id = <?= encode_json($data['language_id']['config_value']) ?>;
    var language_row = <?= encode_json($data['language_row']) ?>;

    var theme_id =  <?= encode_json($data['theme_id']['config_value']) ?>;
    var theme_row = <?= encode_json($data['theme_row']) ?>;


    var date_format_combo =  "<?= ($data['date_format']['config_value']) ?>";
    var time_format_combo =  "<?= ($data['time_format']['config_value']) ?>";

</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>