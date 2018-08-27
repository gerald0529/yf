<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
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
                <h3>数据维护&nbsp;</h3>
                <h5>数据维护</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"  ><span>数据维护, 只生成需要执行的SQL语句, 由运维人员确认后执行。</span></a></li>
            </ul>
        </div>
    </div>


    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
            <li>数据维护, 控制在线升级</li>
        </ul>
    </div>
    <div class="mod-search cf" style="text-align:center;padding-top:100px;">
       <?php


	   foreach ($data as $app_id=>$row)
	   {
       ?>
           <a  onclick="parent.tab.addTabItem({tabid: '<?=$app_id?>', text: '<?=$app_id?>', url: './index.php?ctl=User_Db&met=check&typ=e&app_id=<?=$app_id?>&url=<?=urlencode($row['url'])?>', showClose: true});" class="ui-btn ui-btn-sp mrb" id="btn-add"><?=$row['app_name']?><i class="iconfont"></i></a>&nbsp;&nbsp;
        <?php
	   }
       
       ?>
    </div>

</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

