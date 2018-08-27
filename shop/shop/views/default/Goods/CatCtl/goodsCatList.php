<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
    <link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/classify.css">
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/iconfont/iconfont.css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/tuangou-index.js"></script>
    <!--<script type="text/javascript" src="<?/*= $this->view->js */?>/index.js"></script>-->
    <script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
    <div class="hr"></div>
    <!--  内容-->
    <div class="w wrap clearfix">
        <div class="top-nav clearfix">
            <ul class="nav-items">
                <li class="seles"><a href="#"><?=__('全部商品分类');?></a></li>
                <li><a href="index.php?ctl=Goods_Brand" target="_blank"><?=__('全部品牌'); ?></a></li>
                <li><a href="index.php?ctl=Goods_Goods&met=goodslist" target="_blank"><?=__('全部商品'); ?></a></li>
            </ul>
        </div>
        <div class="main-classify">
            <div class="list">
                <div class="category-items clearfix">
                    <div class="col gallery-wrapper">
                        <?php
                        if (!empty($data))
                        {
                            foreach ($data as $key => $value)
                            {
                                ?>
                                <div class="category-item m white-panel" data-idx="0">
                                    <div class="mt">
                                        <h2 class="item-title"><b></b> <i></i><span><?= $value['cat_name']; ?></span>
                                        </h2>
                                    </div>
                                    <div class="mc">
                                        <div class="item-hot clearfix" clstag="secondtype|keycount|allfenlei|spfl_1">
                                            <?php if (!empty($value['img']))
                                            { ?>
                                                <?php foreach ($value['img'] as $key1 => $value1)
                                            { ?>
                                                <a title="<?= $value1['common_name']; ?>"
                                                   href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value1['goods_id']; ?>"
                                                   target="_blank"><img src="<?= $value1['common_image']; ?>"
                                                                        width="130" height="130"
                                                                        alt="<?= $value1['common_name']; ?>"></a>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <div class="item-info"><?=__('热卖爆品'); ?></div>
                                        <div class="items" clstag="secondtype|keycount|allfenlei|flmc_1">
                                            <?php if (!empty($value['cat']))
                                            { ?>
                                                <?php foreach ($value['cat'] as $ke => $val): ?>
                                                <dl class="clearfix">

                                                    <dt>
                                                        <a href="index.php?ctl=Goods_Goods&met=goodslist&debug=1&cat_id=<?= $val['cat_id'] ?>"
                                                           target="_blank"><?= $val['cat_name'] ?></a></dt>
                                                    <?php if (!empty($val['child']))
                                                    {
                                                        foreach ($val['child'] as $k => $v)
                                                        {
                                                            ?>
                                                            <dd>
                                                                <a href="index.php?ctl=Goods_Goods&met=goodslist&debug=1&cat_id=<?= $v['cat_id'] ?>"
                                                                   target="_blank"><?= $v['cat_name'] ?></a></dd>
                                                        <?php }
                                                    } ?>

                                                </dl>
                                            <?php
                                            endforeach;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div style="float: left ; margin-left: 40px;">
                <div class="current_hot">
                    <h4><?=__('最近浏览');?></h4>
                    <ul>
                        <?php if (!empty($data_recommon_goods))
                        {
                            foreach ($data_recommon_goods as $key_recommon => $value_recommon)
                            {
                                ?>
                                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value_recommon['goods_id'])?>"><img src="<?= $value_recommon['common_image'] ?>"/><h5 class="common-color">
                                            ￥<?= $value_recommon['common_price'] ?></h5></a></li>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                    <div class="hot_all"><a href="<?= Yf_Registry::get('url') ?>?ctl=GroupBuy&met=groupBuyList" class="bbc_bg"><?=__('全部热门团购'); ?></a></div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= $this->view->js ?>/jquery1.11.js" type="text/javascript"></script>
    <script src="<?= $this->view->js ?>/pinterest_grid.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".gallery-wrapper").pinterest_grid({
                no_columns: 2,
                padding_x: 10,
                padding_y: 10,
                margin_bottom: 50,
                single_column_breakpoint: 700
            });

        });
    </script>

    <div class="clr"></div>

    <!-- 尾部 -->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>