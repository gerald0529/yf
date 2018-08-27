<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
 <link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/classify.css">
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css" />
<link rel="stylesheet"  type="text/css" href="<?= $this->view->css ?>/iconfont/iconfont.css">
<script type="text/javascript" src="<?= $this->view->js ?>/tuangou-index.js"></script>
<!--<script type="text/javascript" src="<?/*= $this->view->js */?>/index.js"></script>-->
<script type="text/javascript" src="<?= $this->view->js ?>/brank.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
<!-- 内容 -->

<div class="wrapsp clearfix">
    <div class="divhead clearfix">
        <ul class="goods_ul_S clearfix">
            <li><a class="xq" href="index.php?ctl=Goods_Cat&met=goodsCatList" ><?=__('全部商品分类'); ?></a></li>
            <li class="seles"><a class="pl"><?=__('全部品牌');?></a></li>
            <li><a class="xs" href="index.php?ctl=Goods_Goods&met=goodslist"><?=__('全部商品'); ?></a></li>
        </ul>
    </div>
    <div style="background:#ebebeb; padding-bottom: 60px; ">
    <?php if(!empty($data)){
        $i=1;
        foreach($data as $key=>$value):
    ?>
        <div class="floorLoadingj_BrandFloor"  style="background: none; padding-top:10px">
            <div class="brandFloor">
                <h3 class="ui-title"> <em class="ui-title-num"><?=$i ?></em>
                    <p> <a href="index.php?ctl=Goods_Goods&met=goodslist&cat_id=<?=$value['cat_id']; ?>" > <b class="ui-title-text"><?=$value['cat_name'] ?></b> <i class=" iconfont icon-youyuan"></i> </a> </p>
                </h3>
            </div>
            <div class="brandFloor-con clearfix">
                <div class="module">
                    <ul class="brandFloor-list clearfix" >
                        <?php foreach($value['sub'] as $keys=>$values){ ?>
                        <li class="bFl-item">
                            <div class="bFl-item-slide j_FloorSlide">
                                <div class="bFlis-hd"> <span style><?=$values['cat_name'] ?></span>
                                    <ul class="bFlis-hd-nav">
                                        <li class="brandSlide-active ks-switchable-trigger-internal155">•</li>
                                        <li class="ks-switchable-trigger-internal155">•</li>
                                    </ul>
                                </div>
                                <ul class="bFlis-con" >
                                    <li class="bFlis-con-list ks-switchable-panel-internal156" style="display: block; float: left;"> <a target="_blank" href="index.php?ctl=Goods_Goods&met=goodslist&cat_id=<?=$values['cat_id']; ?>"> <img src="<?=$values['cat_pic']; ?>" alt="crocs" height="120" width="190"></a> <a target="_blank" class="bFlis-con-mask" href="#" style="top: 130px;"> </a> </li>
                                </ul>
                            </div>
                            <ul class="bFl-item-logo clearfix">

                                <?php foreach($values['brand'] as $k=>$v){ ?>
                                    <?php if($k%2==0){ ?>
                                <li class="brand_all_sel"> <a class="bFlil-link" target="_blank" href="index.php?ctl=Goods_Goods&met=goodslist&brand_id=<?=$v['brand_id']; ?>" title="<?=$v['brand_name'] ?>"> <span class="bFlil-img"> <img height="45" width="90" alt="<?=$v['brand_name'] ?>" src="<?=$v['brand_pic'] ?>"> </span> </a> </li>
                                    <?php }else{?>
                                <li class=""> <a class="bFlil-link" target="_blank" href="index.php?ctl=Goods_Goods&met=goodslist&brand_id=<?=$v['brand_id']; ?>" title="<?=$v['brand_name'] ?>"> <span class="bFlil-img"> <img height="45" width="90" alt="<?=$v['brand_name'] ?>" src="<?=$v['brand_pic'] ?>"> </span> </a> </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="brandFloor-top">
                    <h4><?=__('本周关注排行榜')?></h4>
                    <div class="module">
                        <ol class="bFt-list j_FloorTop">
                            <?php if(!empty($data_rank[$key])): foreach($data_rank[$key] as $ke=>$val): ?>
                                <li class="<?php if($ke == 0){ ?> bFlil-expand <?php } ?>"> <i class="bFt-list-num"><?=($ke+1) ?></i>
                                    <p class="bFt-list-name"><a href="index.php?ctl=Goods_Brand&met=brandList&brand_id=<?=$val['brand_id']; ?>" target="_blank"><?=$val['brand_name']; ?></a></p>
                                    <p class="bFt-list-sell"><?=__('人气指数')?><em class="num-color ml4"><?=$val['brand_collect']; ?></em></p>
                                </li>
                            <?php endforeach; endif; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    <?php  $i++;
        endforeach;
        } ?>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
<!-- 尾部 -->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>