<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

</head>


<div class="wrapper page">

  <p class="warn_xiaoma"><span></span><em></em></p>
  <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
            <li></li>
            <li></li>
        </ul>
  </div>
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>购物卡管理</h3>
        <h5>购物卡管理操作及相关信息</h5>
      </div>
      <ul class="tab-base nc-row">
          <li><a class="current"><span>购物卡管理</span></a></li>
          <li><a href="index.php?ctl=Paycen_PayCard&met=payCard">管理与详情</a></li>
      </ul>
    </div>
  </div>
  <div class="ncap-form-default">
      <div class="mod-search cf">
        <div class="fl">
          <ul class="ul-inline">
            <li>
                <span id="source"></span>
            </li>
            <li>
              <input type="text" id="cardName" class="ui-input ui-input-ph con" value="请输入卡名称">
            </li>
            <li>
              <label>日期:</label>
              <input type="text" id="beginDate" value="2015-12-02" class="ui-input ui-datepicker-input">
              <i>-</i>
              <input type="text" id="endDate" value="2015-12-08" class="ui-input ui-datepicker-input">
            </li>
            <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
          </ul>
        </div>
        <div class="fr">
            <a href="#" class="ui-btn ui-btn-sp mrb" id="add">新增<i class="iconfont icon-btn03"></i></a>
<!--            <a href="javascript:void(0)" class="ui-btn" id="btn-batchDel">删除<i class="iconfont icon-bin"></i></a>-->
        </div>
      </div>
      <div class="grid-wrap">
          <table id="grid">
          </table>
          <div id="page"></div>
      </div>
  </div>
</div>
<script src="./admin/static/default/js/controllers/paycard/paycard_list.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>