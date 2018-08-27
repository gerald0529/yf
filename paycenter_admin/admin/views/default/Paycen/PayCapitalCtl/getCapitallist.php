<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

</head>
<body>
  <div class="wrapper page">
     <div class="fixed-bar">
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
        <div class="item-title">
          <div class="subject">
            <h3>系统资金总览</h3>
            <h5>系统总金额以及交易中的金额总览</h5>
          </div>
           <ul class="tab-base nc-row">
              <li><a class="current"><span>系统资金总览</span></a></li>
          </ul>
        </div>
      </div>
     <!-- 操作说明 -->

     <div class="ncap-form-default">
             <dl class="row">
                    <dt class="tit">账户资金总额</dt>
                    <dd class="opt">
                        <p><?=$data['user_money_sum']?></p>
                        <p class="notic">所有会员账户资金总额</p>
                    </dd>
                </dl>
              <dl class="row">
                    <dt class="tit">冻结资金总额</dt>
                  <dd class="opt">
                        <p><?=$data['user_money_frozen_sum']?></p>
                        <p class="notic">交易的资金</p>
                    </dd>
                </dl>
                  <dl class="row">
                    <dt class="tit">购物卡总额</dt>
                  <dd class="opt">
                        <p><?=$data['user_recharge_card_sum']?></p>
                        <p class="notic">所有会员账户卡资金总额</p>
                    </dd>
                </dl>
                <dl class="row">
                      <dt class="tit">冻结购物卡总额</dt>
                    <dd class="opt">
                          <p><?=$data['user_recharge_card_frozen_sum']?></p>
                          <p class="notic">交易的卡资金</p>
                      </dd>
                </dl>
      </div>
  </div>
</body>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>