<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>

<style>
div.zoomMask{position:absolute;background:url("http://demo.lanrenzhijia.com/2015/jqzoom0225/images/mask.png") repeat scroll 0 0 transparent;cursor:move;z-index:1;}
</style>
<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
<script>
$(function(){
	$(".jqzoom").simagezoom();
});
</script>

<body>
<div class="content">
    <?php if($data['items']) {
            foreach ($data['items'] as $key => $value){ ?>

                <div style="clear: both;"></div>
            <div class="evaluation-timeline">
                <!--S 商品信息 -->
                <div class="date">
                    <!-- 商品图片 -->
                    <div class="goods_image">
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value[0]['goods_id'])?>"><img src="<?=image_thumb($value[0]['goods_image'],86,86)?>"/></a>
                    </div>

                    <div class="order_goods">
                        <!-- 订单号 -->
                        <a target="_blank" ><?=($value[0]['order_id'])?></a>
                        <!-- 商品名称 -->
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value[0]['goods_id'])?>"><?=($value[0]['goods_name'])?></a>
                        <!-- 商品价格 -->
                        <?=format_money($value[0]['goods_price'])?>
                    </div>
                </div>
                <!--E 商品信息  -->


                <div style="clear: both;"></div>
                <div class="goods-thumb">
                    <!-- 用户头像 -->
                    <img src="<?=image_thumb($value[0]['user_logo'],60,60)?>">
                    <!-- 用户名称 -->
                    <?php
                        if($value[0]['isanonymous']){
                            $value[0]['member_name'] = substr($value[0]['member_name'],0,1).'***'.substr($value[0]['member_name'],-1);
                        }

                    ?>
                    <p><?=($value[0]['member_name'])?></p>
                </div>

                <!--S 评论内容  -->
                <dl class="detail detail_dls">
                  <dt class="clearfix">
                        <p><?=__('评论时间：')?><?=($value[0]['create_time'])?></p>
                        <span class="ml30">&nbsp;&nbsp;&nbsp;<?=__('商品评分：')?>
                            <em style="width: 100px;" title="<?=__('很满意')?>" class="raty" data-score="5">
                                <?php for($i=1;$i<=$value[0]['scores'];$i++):?>
                                <i class="iconfont icon-xingxing"></i>
                                <?php endfor; ?>
                                <input readonly value="<?=($value[0]['scores'])?>" name="score" type="hidden">
                            </em>
                        </span>
                  </dt>

                  <!-- 评价内容 -->
                  <?php Text_Filter::filterWords($value[0]['content']);?>
                  <dd><?=($value[0]['content'])?></dd>

                  <!-- 评价图片 -->
                  <div class="evaluate_img">
                      <?php if($value[0]['image_row']): foreach($value[0]['image_row'] as $img1key => $img1val ): ?>
                        <img src="<?=image_thumb($img1val,100,100)?>" class="jqzoom" rel="<?=image_thumb($img1val,200,200)?>">
                      <?php endforeach;endif;?>
                  </div>

                  <span>
                    <a class="btnredpj bbc_seller_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Goods_Evaluation&met=evaluation&evaluation_goods_id=<?=($value[0]['evaluation_goods_id'])?>"  id="explain"><i class=" bbc_seller_btns iconfont icon-shangjiaruzhushenqing"></i><?=__('解释')?></a>
                  </span>

                </dl>
                <!--E 评论内容  -->

                <!--S 解释内容  -->
                <?php if($value[0]['explain_content']): ?>
                    <div style="clear: both;"></div>
                    <div class="goods-thumb">
                        <!-- 店铺头像 -->
                        <img src="<?=image_thumb($shop_base['shop_logo'],60,60)?>">
                        <!-- 店铺名称 -->
                        <p><?=($shop_base['shop_name'])?></p>
                    </div>

                    <dl class="detail detail_dls">
                        <dt class="clearfix">
                             <p><?=__('解释时间：')?><?=($value[0]['update_time'])?></p>
                        </dt>

                        <?php Text_Filter::filterWords($value[0]['explain_content']);?>
                        <dd><?=($value[0]['explain_content'])?></dd>
                    </dl>
                <!--E 解释内容  -->
                <?php endif; ?>

                <!--S 追加评价  -->
                <?php if(isset($value[1])):?>
                    <div style="clear: both;"></div>
                    <div class="goods-thumb"></div>

                    <!--S 追加评论评论内容  -->
                    <dl class="detail detail_dls">
                      <dt class="clearfix">
                            <p><?=__('追加时间：')?><?=($value[1]['create_time'])?></p>
                      </dt>

                      <!-- 追加评价内容 -->
                      <?php Text_Filter::filterWords($value[1]['content']);?>
                      <dd><?=($value[1]['content'])?></dd>

                      <!-- 追加评价图片 -->
                      <div class="evaluate_img">
                        <?php if($value[1]['image_row']): foreach($value[1]['image_row'] as $img2key => $img2val ): ?>
                            <img src="<?=image_thumb($img2val,100,100)?>" class="jqzoom" rel="<?=image_thumb($img2val,200,200)?>">
                        <?php endforeach;endif;?>
                      </div>

                      <span>
                        <a class="btnredpj bbc_seller_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Goods_Evaluation&met=evaluation&evaluation_goods_id=<?=($value[1]['evaluation_goods_id'])?>"  id="explain"><i class=" bbc_seller_btns iconfont icon-shangjiaruzhushenqing"></i><?=__('解释')?></a>
                      </span>

                    </dl>
                    <!--E 追加评论内容  -->

                    <!--S 追加评论解释内容  -->
                    <?php if($value[1]['explain_content']): ?>
                        <div style="clear: both;"></div>
                        <div class="goods-thumb">
                            <!-- 店铺头像 -->
                            <img src="<?=image_thumb($shop_base['shop_logo'],60,60)?>">
                            <!-- 店铺姓名 -->
                            <p><?=($shop_base['shop_name'])?></p>
                        </div>

                        <dl class="detail detail_dls">
                            <dt class="clearfix">
                                <p><?=__('解释时间：')?><?=($value[1]['update_time'])?></p>
                            </dt>

                            <?php Text_Filter::filterWords($value[1]['explain_content']);?>
                            <dd><?=($value[1]['explain_content'])?></dd>
                        </dl>
                    <!--E 追加评论解释内容  -->
                    <?php endif; ?>
                <?php endif;?>
                <!--E 追加评价  -->

              </div>
    <?php      } ?>
        <div style="clear: both;"></div>
        <div class="page"><?=($page_nav)?></div>
    <?php   }else{?>
    <div class="no_account">
         <img src="<?= $this->view->img ?>/ico_none.png"/>
         <p><?= __('暂无符合条件的数据记录') ?></p>
    </div>
    <?php }?>

</div>



<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>